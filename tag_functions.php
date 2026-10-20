<?php
/*
 * tag_functions.php
 * Shared helpers for dress tagging (Issue #67: Tag Report + AI-Assisted Tagger).
 *
 * How tags are stored
 * -------------------
 *  - The FIXED list of tags lives in reports/abcd_tags.txt, one tag per line.
 *    "Admin > Tags > Modify Tag List" edits that file (and copies it into dresses_tags_tbl).
 *  - Which dress has which tag lives in the dress_tags table: one row per (dress, tag).
 *    A dress can have several tags. The `source` column records who assigned it:
 *      'manual' = an admin ticked it on Modify Dress
 *      'ai'     = the AI-Assisted Tagger chose it using an AI model
 *      'rules'  = the AI-Assisted Tagger chose it using the built-in keyword rules
 *                 (used when no AI key is configured, or the AI returned no valid tag)
 *
 * Before this file there was no dress-to-tag table at all (dresses_tags_tbl only holds
 * the tag names), so the old Tag Report counted the free-form key_words column instead.
 */

/**
 * Returns the fixed tag list, in the order an admin wrote it.
 * Reads reports/abcd_tags.txt first (the file Modify Tag List edits); falls back to the
 * older copy in the project root if the reports file is missing.
 */
function abcd_tag_list() {
    $candidates = [__DIR__ . '/reports/abcd_tags.txt', __DIR__ . '/abcd_tags.txt'];
    foreach ($candidates as $file) {
        if (is_readable($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $tags  = [];
            foreach ($lines as $line) {
                $tag = trim($line);
                // Skip blanks, duplicates (case-insensitive) and the catch-all "Custom".
                if ($tag === '' || strcasecmp($tag, 'custom') === 0) continue;
                $tags[strtolower($tag)] = $tag;
            }
            if ($tags) {
                return array_values($tags);
            }
        }
    }
    return [];
}

/**
 * Creates the dress_tags table the first time tagging is used, so nobody has to import
 * SQL by hand on their XAMPP install (same approach as the Gallery feature).
 */
function abcd_ensure_tag_tables($db) {
    mysqli_query($db, "CREATE TABLE IF NOT EXISTS `dress_tags` (
        `dress_id`   INT(11)      NOT NULL,
        `tag_name`   VARCHAR(255) NOT NULL,
        `source`     ENUM('manual','ai','rules') NOT NULL DEFAULT 'manual',
        `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`dress_id`, `tag_name`),
        KEY `idx_tag_name` (`tag_name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

/** Tags already assigned to one dress, as a plain list of names. */
function abcd_get_dress_tags($db, $dressId) {
    $tags = [];
    $stmt = $db->prepare('SELECT tag_name FROM dress_tags WHERE dress_id = ? ORDER BY tag_name');
    $stmt->bind_param('i', $dressId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['tag_name'];
    }
    $stmt->close();
    return $tags;
}

/**
 * Replaces the tags of one dress that came from $source with $tags.
 * Tags from other sources are left alone, so re-running the AI tagger never erases
 * tags an admin ticked by hand (and saving Modify Dress replaces only manual tags).
 * A tag the dress already has from another source is not duplicated.
 */
function abcd_set_dress_tags($db, $dressId, array $tags, $source) {
    $del = $db->prepare('DELETE FROM dress_tags WHERE dress_id = ? AND source = ?');
    $del->bind_param('is', $dressId, $source);
    $del->execute();
    $del->close();

    // INSERT IGNORE skips a (dress, tag) pair that already exists from another source.
    $ins = $db->prepare('INSERT IGNORE INTO dress_tags (dress_id, tag_name, source) VALUES (?, ?, ?)');
    foreach (array_unique($tags) as $tag) {
        $ins->bind_param('iss', $dressId, $tag, $source);
        $ins->execute();
    }
    $ins->close();
}

/**
 * Saves the "Assign Tags" checkboxes from Modify Dress. What the admin ticked is final:
 * unticked tags are removed whatever their source (even ones the AI added), and every
 * ticked tag is stored as 'manual'.
 */
function abcd_save_manual_tags($db, $dressId, array $picked) {
    $picked  = array_values(array_unique($picked));
    $current = abcd_get_dress_tags($db, $dressId);

    $del = $db->prepare('DELETE FROM dress_tags WHERE dress_id = ? AND tag_name = ?');
    foreach (array_diff($current, $picked) as $tag) {
        $del->bind_param('is', $dressId, $tag);
        $del->execute();
    }
    $del->close();

    // Every ticked tag becomes 'manual': new ones are added, and AI-picked ones the admin kept
    // are marked as confirmed, so a later AI re-run can never drop them.
    $up = $db->prepare("INSERT INTO dress_tags (dress_id, tag_name, source) VALUES (?, ?, 'manual')
                        ON DUPLICATE KEY UPDATE source = 'manual'");
    foreach ($picked as $tag) {
        $up->bind_param('is', $dressId, $tag);
        $up->execute();
    }
    $up->close();
}

/**
 * Numbers for the Tag Report.
 * Returns [
 *   'counts'   => ['Sheroes' => 12, ...]   one entry per fixed tag, in list order
 *   'other'    => 3     assignments whose tag is no longer in the fixed list
 *   'total'    => 500   number of dresses
 *   'tagged'   => 497   dresses with at least one tag
 *   'untagged' => 3     dresses with no tag
 * ]
 */
function abcd_tag_report_data($db, array $tagList) {
    $counts = array_fill_keys($tagList, 0);
    $lookup = [];
    foreach ($tagList as $tag) {
        $lookup[strtolower($tag)] = $tag;
    }

    // Count dresses per tag. Joining to dresses ignores rows left behind by deleted dresses.
    $other  = 0;
    $result = mysqli_query($db, "SELECT dt.tag_name, COUNT(DISTINCT dt.dress_id) AS n
                                 FROM dress_tags dt JOIN dresses d ON d.id = dt.dress_id
                                 GROUP BY dt.tag_name");
    while ($result && ($row = $result->fetch_assoc())) {
        $key = strtolower($row['tag_name']);
        if (isset($lookup[$key])) {
            $counts[$lookup[$key]] += (int)$row['n'];
        } else {
            $other += (int)$row['n'];
        }
    }

    $total  = (int)mysqli_fetch_row(mysqli_query($db, 'SELECT COUNT(*) FROM dresses'))[0];
    $tagged = (int)mysqli_fetch_row(mysqli_query($db,
        'SELECT COUNT(DISTINCT dt.dress_id) FROM dress_tags dt JOIN dresses d ON d.id = dt.dress_id'))[0];

    return [
        'counts'   => $counts,
        'other'    => $other,
        'total'    => $total,
        'tagged'   => $tagged,
        'untagged' => $total - $tagged,
    ];
}

/** True when the logged-in user is an admin. */
function abcd_is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/** Per-session token that protects the tagger's POST requests. */
function abcd_tag_csrf_token() {
    if (empty($_SESSION['tag_csrf'])) {
        $_SESSION['tag_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['tag_csrf'];
}

function abcd_tag_check_csrf($token) {
    return isset($_SESSION['tag_csrf']) && is_string($token)
        && hash_equals($_SESSION['tag_csrf'], $token);
}

/** Short HTML-escape helper. */
function abcd_e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
