<?php
/*
 * rag_ingest.php
 * RAG ingestion for "Ask ABCD": copies every dress (title, description, did you know)
 * into the rag_chunks knowledge-base table, with embeddings when an OpenAI key is set.
 *
 * Two ways to run it:
 *   Browser (admins only):  http://localhost/abcd2/rag_ingest.php  -> click "Update index"
 *   Terminal:               php rag_ingest.php            (only changed entries)
 *                           php rag_ingest.php --force    (rebuild everything)
 *
 * It is safe to run as often as you like: an entry is only re-processed when its text changed
 * (each chunk stores a SHA-256 hash of its text), and deleted dresses are removed from the index.
 */

$isCli = (PHP_SAPI === 'cli');
if (!$isCli && session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db_configuration.php';
require_once __DIR__ . '/tag_functions.php';
require_once __DIR__ . '/rag_functions.php';

/* ---------- Terminal mode ---------- */
if ($isCli) {
    $force = in_array('--force', $argv, true);
    echo "Ask ABCD ingestion" . ($force ? " (full rebuild)" : "") . "\n";
    $cfg = rag_config();
    echo "Semantic search: " . ($cfg['embed_key'] !== '' ? "ON (" . RAG_EMBED_MODEL . ")" : "OFF (keyword search only)") . "\n";
    $stats = rag_ingest_all($db, $force, function ($m) { echo "  $m\n"; });
    printf("Done. Indexed %d, unchanged %d, removed %d, with embeddings %d.\n",
        $stats['indexed'], $stats['skipped'], $stats['removed'], $stats['embedded']);
    foreach ($stats['errors'] as $e) echo "Warning: $e\n";
    exit(0);
}

/* ---------- Browser mode (admins only) ---------- */
if (!abcd_is_admin()) {
    echo "<script>alert('Unauthorized access. Admin privileges are required.'); window.location.href = 'loginForm.php';</script>";
    exit;
}

$csrf  = abcd_tag_csrf_token();
$stats = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!abcd_tag_check_csrf($_POST['csrf'] ?? '')) {
        $stats = ['error' => 'Your session expired. Reload the page and try again.'];
    } else {
        @set_time_limit(300);
        $stats = rag_ingest_all($db, !empty($_POST['force']));
    }
}

rag_ensure_table($db);
$cfg      = rag_config();
$total    = (int)$db->query("SELECT COUNT(*) FROM dresses")->fetch_row()[0];
$indexed  = (int)$db->query("SELECT COUNT(*) FROM rag_chunks")->fetch_row()[0];
$embedded = (int)$db->query("SELECT COUNT(*) FROM rag_chunks WHERE embedding IS NOT NULL")->fetch_row()[0];
$lastRun  = $db->query("SELECT MAX(updated_at) FROM rag_chunks")->fetch_row()[0];

$page_title = 'Project ABCD > Ask ABCD Index';
include('header.php');
?>
<div class="container mt-5">
    <br><br>
    <h2>Ask ABCD &ndash; Knowledge Base Index</h2>
    <p>The chatbot only answers from entries in this index. Run it after adding or editing dresses.
       Edits made on Modify Dress are re-indexed automatically.</p>

    <table class="table table-sm" style="max-width: 640px">
        <tr><th>Entries in the database</th><td><?= $total ?></td></tr>
        <tr><th>Entries in the index</th><td><?= $indexed ?></td></tr>
        <tr><th>Entries with embeddings</th><td><?= $embedded ?></td></tr>
        <tr><th>Search method</th><td><?= $cfg['embed_key'] !== '' ? 'Semantic + keyword (OpenAI ' . RAG_EMBED_MODEL . ')' : 'Keyword (BM25), no embedding key set' ?></td></tr>
        <tr><th>Answer method</th><td><?= $cfg['chat']['provider'] === 'none' ? 'No AI key set &ndash; answers quote the best matching entry' : abcd_e(ai_tagger_method_label($cfg['chat'])) . ' writes answers from the entries' ?></td></tr>
        <tr><th>Last update</th><td><?= $lastRun ? abcd_e($lastRun) : 'never' ?></td></tr>
    </table>

    <?php if ($stats && isset($stats['error'])): ?>
        <div class="alert alert-danger"><?= abcd_e($stats['error']) ?></div>
    <?php elseif ($stats): ?>
        <div class="alert alert-success">
            Indexed <?= $stats['indexed'] ?>, unchanged <?= $stats['skipped'] ?>,
            removed <?= $stats['removed'] ?>, with embeddings <?= $stats['embedded'] ?>.
            <?php foreach ($stats['errors'] as $e): ?><br><strong>Warning:</strong> <?= abcd_e($e) ?><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf" value="<?= abcd_e($csrf) ?>">
        <label><input type="checkbox" name="force" value="1"> Rebuild every entry (slower)</label><br>
        <button type="submit" class="btn btn-primary mt-2">Update index</button>
        <a href="chatbot.php" class="btn btn-outline-secondary mt-2">Try the chatbot</a>
    </form>
</div>
<?php include('footer.php'); ?>
