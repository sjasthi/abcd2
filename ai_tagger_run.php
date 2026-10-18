<?php
/*
 * ai_tagger_run.php
 * Background worker for the AI-Assisted Tagger page (Issue #67).
 *
 * ai_tagger.php calls this file over and over, one small batch of dresses at a time,
 * so a run over ~500 dresses never hits PHP's time limit and the admin sees live progress.
 *
 * POST fields:
 *   csrf     - token from ai_tagger.php
 *   mode     - 'untagged' = only dresses with no tags yet, 'all' = re-tag every dress
 *   after_id - continue with dresses whose id is greater than this (0 to start)
 *
 * Replies with JSON:
 *   { ok, done, last_id, processed, remaining, results: [{id, name, tags, source}], warning }
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_configuration.php';
require_once 'tag_functions.php';
require_once 'ai_tagger_classifier.php';

header('Content-Type: application/json; charset=utf-8');

function ai_tagger_reply($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !abcd_is_admin()) {
    ai_tagger_reply(['ok' => false, 'error' => 'Admins only.'], 403);
}
if (!abcd_tag_check_csrf($_POST['csrf'] ?? '')) {
    ai_tagger_reply(['ok' => false, 'error' => 'Your session expired. Reload the page and try again.'], 403);
}

@set_time_limit(180);
abcd_ensure_tag_tables($db);

$tagList = abcd_tag_list();
if (!$tagList) {
    ai_tagger_reply(['ok' => false, 'error' => 'The tag list is empty. Add tags with "Modify Tag List" first.']);
}

$mode    = ($_POST['mode'] ?? 'untagged') === 'all' ? 'all' : 'untagged';
$afterId = max(0, (int)($_POST['after_id'] ?? 0));
$config  = ai_tagger_config();

// AI requests handle a few dresses at a time; the keyword rules are fast, so do more.
$batchSize = $config['provider'] === 'none' ? 50 : 8;

// "untagged" mode skips dresses that already have any tag (manual or earlier run).
$untaggedOnly = $mode === 'untagged'
    ? 'AND NOT EXISTS (SELECT 1 FROM dress_tags dt WHERE dt.dress_id = d.id)'
    : '';

$stmt = $db->prepare("SELECT d.id, d.name, d.description, d.did_you_know
                      FROM dresses d
                      WHERE d.id > ? $untaggedOnly
                      ORDER BY d.id
                      LIMIT ?");
$stmt->bind_param('ii', $afterId, $batchSize);
$stmt->execute();
$dresses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (!$dresses) {
    ai_tagger_reply(['ok' => true, 'done' => true, 'last_id' => $afterId, 'processed' => 0,
                     'remaining' => 0, 'results' => []]);
}

// Classify the batch, then save each dress's tags.
$classified = ai_tagger_classify_batch($dresses, $tagList, $config);
$warning    = $classified['warning'] ?? null;
$results    = [];

foreach ($dresses as $d) {
    $id   = (int)$d['id'];
    $pick = $classified[$id];
    // Replace this dress's earlier automatic tags (from either method); manual tags stay.
    abcd_set_dress_tags($db, $id, [], 'ai');
    abcd_set_dress_tags($db, $id, [], 'rules');
    abcd_set_dress_tags($db, $id, $pick['tags'], $pick['source']);
    $results[] = [
        'id'     => $id,
        'name'   => $d['name'],
        'tags'   => abcd_get_dress_tags($db, $id),   // everything the dress now has
        'source' => $pick['source'],
    ];
}

$lastId = (int)end($dresses)['id'];

// How many dresses are still waiting after this batch.
$stmt = $db->prepare("SELECT COUNT(*) FROM dresses d WHERE d.id > ? $untaggedOnly");
$stmt->bind_param('i', $lastId);
$stmt->execute();
$remaining = (int)$stmt->get_result()->fetch_row()[0];
$stmt->close();

ai_tagger_reply([
    'ok'        => true,
    'done'      => $remaining === 0,
    'last_id'   => $lastId,
    'processed' => count($results),
    'remaining' => $remaining,
    'results'   => $results,
    'warning'   => $warning,
]);
?>
