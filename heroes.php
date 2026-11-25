<?php
/*
 * heroes.php
 * Public "Heroes" page for Project ABCD.
 *
 * WHAT IT SHOWS
 *   Every entry from the `dresses` table that is tagged "Heroes" (the same entries that appear
 *   on the Dresses page). A "Sheroes" tab shows the entries tagged "Sheroes" the same way.
 *   Each card shows the picture, name and state, and links to display_the_dress.php for details.
 *
 * WHERE THE TAG COMES FROM (two sources, so it works on any copy of the database)
 *   1. The `dress_tags` table (tag_name = 'Heroes'), used by the Tags admin page and AI Tagger.
 *   2. The `key_words` column of `dresses`, for older databases where the tag is only typed
 *      into the keywords (e.g. "heroes, patriotism").
 *   If `dress_tags` does not exist yet, the page quietly falls back to the keywords only.
 *
 * URL OPTIONS
 *   heroes.php                 -> Heroes
 *   heroes.php?group=sheroes   -> Sheroes
 *   heroes.php?q=bhagat        -> search by name / state inside the current group
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db_configuration.php';

/* ---------- 1. Read and clean the inputs ---------- */

// Only two groups are allowed; anything else falls back to Heroes.
$groups   = ['heroes' => 'Heroes', 'sheroes' => 'Sheroes'];
$groupKey = strtolower($_GET['group'] ?? 'heroes');
if (!isset($groups[$groupKey])) {
    $groupKey = 'heroes';
}
$tagName = $groups[$groupKey];                 // 'Heroes' or 'Sheroes' (as stored in the database)

$search = trim($_GET['q'] ?? '');              // optional search box text

/* ---------- 2. Find the matching entries ---------- */

/**
 * Returns the dresses carrying $tag, optionally filtered by $search.
 * Uses prepared statements so user input can never change the SQL.
 */
function heroes_fetch($db, $tag, $search) {
    // Keyword match: turn "heroes, patriotism" into ",heroes,patriotism," and look for ",heroes,"
    $keywordMatch = "LOCATE(CONCAT(',', LOWER(?), ','),
                     REPLACE(CONCAT(',', LOWER(IFNULL(d.key_words, '')), ','), ', ', ',')) > 0";

    $searchSql = '';
    if ($search !== '') {
        $searchSql = " AND (d.name LIKE ? OR d.state_name LIKE ? OR d.key_words LIKE ?)";
    }

    // First choice: dress_tags table OR keywords.
    $sqlWithTags = "SELECT DISTINCT d.id, d.name, d.state_name, d.image_url, d.tag_line
                    FROM dresses d
                    LEFT JOIN dress_tags t ON t.dress_id = d.id AND t.tag_name = ?
                    WHERE (t.dress_id IS NOT NULL OR $keywordMatch) $searchSql
                    ORDER BY TRIM(REPLACE(d.name, CHAR(9), '')) ASC";

    // Fallback when the dress_tags table does not exist on this database.
    $sqlKeywordsOnly = "SELECT d.id, d.name, d.state_name, d.image_url, d.tag_line
                        FROM dresses d
                        WHERE $keywordMatch $searchSql
                        ORDER BY TRIM(REPLACE(d.name, CHAR(9), '')) ASC";

    $like = '%' . $search . '%';

    try {
        $stmt = $db->prepare($sqlWithTags);
        if ($search !== '') {
            $stmt->bind_param('sssss', $tag, $tag, $like, $like, $like);
        } else {
            $stmt->bind_param('ss', $tag, $tag);
        }
    } catch (mysqli_sql_exception $e) {
        // Most likely "Table 'dress_tags' doesn't exist": use keywords only.
        $stmt = $db->prepare($sqlKeywordsOnly);
        if ($search !== '') {
            $stmt->bind_param('ssss', $tag, $like, $like, $like);
        } else {
            $stmt->bind_param('s', $tag);
        }
    }

    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

$entries = heroes_fetch($db, $tagName, $search);

/* ---------- 3. Page output ---------- */

$page_title = 'Project ABCD > ' . $tagName;
include('header.php');

// Small helper so every value printed into HTML is escaped (protects against XSS).
function h($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;600&display=swap" rel="stylesheet">

<style>
    /* Page-specific styles kept here so the page is a single file. */
    .heroes-page      { max-width: 1200px; margin: 30px auto; padding: 0 16px; font-family: 'Kanit', sans-serif; }
    .heroes-page h1   { text-align: center; font-weight: 600; letter-spacing: 1px; margin-bottom: 4px; }
    .heroes-sub       { text-align: center; color: #555; margin-bottom: 20px; }

    .heroes-tabs      { display: flex; justify-content: center; gap: 10px; margin-bottom: 16px; }
    .heroes-tabs a    { padding: 8px 22px; border-radius: 20px; border: 2px solid #ff3d1f;
                        color: #ff3d1f; text-decoration: none; font-weight: 600; }
    .heroes-tabs a.active, .heroes-tabs a:hover { background: #ff3d1f; color: #fff; }

    .heroes-search    { display: flex; justify-content: center; gap: 8px; margin-bottom: 10px; }
    .heroes-search input  { width: 320px; max-width: 70vw; padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; }
    .heroes-search button { padding: 8px 16px; border: 0; border-radius: 6px; background: #ff3d1f; color: #fff; }
    .heroes-count     { text-align: center; color: #777; margin-bottom: 20px; }

    .heroes-grid      { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 22px; }
    .hero-card        { background: #fff; border-radius: 10px; overflow: hidden; text-decoration: none; color: #222;
                        box-shadow: 0 2px 8px rgba(0,0,0,.12); transition: transform .15s, box-shadow .15s; }
    .hero-card:hover  { transform: translateY(-4px); box-shadow: 0 6px 16px rgba(0,0,0,.18); color: #222; text-decoration: none; }
    .hero-card img    { width: 100%; height: 280px; object-fit: cover; background: #f2f2f2; display: block; }
    .hero-card .info  { padding: 10px 12px 14px; }
    .hero-card .name  { font-weight: 600; font-size: 1.05rem; }
    .hero-card .state { color: #888; font-size: .9rem; }
    .hero-card .tag   { color: #555; font-size: .85rem; margin-top: 4px; }

    .heroes-empty     { text-align: center; color: #777; padding: 60px 0; }
</style>

<main class="heroes-page">
    <h1><?= strtoupper(h($tagName)) ?></h1>
    <p class="heroes-sub">
        <?= $tagName === 'Heroes'
            ? 'The heroes of Project ABCD: freedom fighters, leaders and legends.'
            : 'The sheroes of Project ABCD: women who changed history.' ?>
    </p>

    <!-- Heroes / Sheroes tabs -->
    <nav class="heroes-tabs">
        <?php foreach ($groups as $key => $label): ?>
            <a href="heroes.php?group=<?= h($key) ?>" class="<?= $key === $groupKey ? 'active' : '' ?>"><?= h($label) ?></a>
        <?php endforeach; ?>
    </nav>

    <!-- Search inside the current group (GET form, so the URL can be shared) -->
    <form class="heroes-search" method="get" action="heroes.php">
        <input type="hidden" name="group" value="<?= h($groupKey) ?>">
        <input type="text" name="q" value="<?= h($search) ?>" placeholder="Search by name or state...">
        <button type="submit">Search</button>
        <?php if ($search !== ''): ?>
            <a href="heroes.php?group=<?= h($groupKey) ?>" style="align-self:center;">Clear</a>
        <?php endif; ?>
    </form>

    <p class="heroes-count"><?= count($entries) ?> <?= h(strtolower($tagName)) ?> found</p>

    <?php if (empty($entries)): ?>
        <p class="heroes-empty">
            No <?= h(strtolower($tagName)) ?> found<?= $search !== '' ? ' for "' . h($search) . '"' : '' ?>.
        </p>
    <?php else: ?>
        <div class="heroes-grid">
            <?php foreach ($entries as $row): ?>
                <?php
                    // Same image folder the home page uses; a blank grey box shows if the file is missing.
                    $img = 'images/dress_images/' . rawurlencode((string)$row['image_url']);
                ?>
                <a class="hero-card" href="display_the_dress.php?id=<?= (int)$row['id'] ?>" title="<?= h($row['name']) ?>">
                    <img src="<?= h($img) ?>" alt="<?= h($row['name']) ?>" loading="lazy">
                    <div class="info">
                        <div class="name"><?= h(trim($row['name'])) ?></div>
                        <?php if (!empty($row['state_name'])): ?>
                            <div class="state"><?= h($row['state_name']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($row['tag_line'])): ?>
                            <div class="tag"><?= h($row['tag_line']) ?></div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
