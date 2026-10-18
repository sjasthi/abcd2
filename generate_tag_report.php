<?php
/*
 * generate_tag_report.php
 * Admin > Tags > Run Tag Report  (Issue #67)
 *
 * Shows how many dresses carry each tag in the fixed tag list, as
 *   (1) a text table and (2) a bar chart, plus a CSV download.
 *
 * Why it was rewritten: the old report counted the free-form `key_words` column
 * ("saree, red, gold"), so almost nothing matched the fixed tags and nearly everything
 * was counted as "custom". Tags are now stored per dress in the dress_tags table
 * (filled by Modify Dress and by the AI-Assisted Tagger), and this report counts those.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_configuration.php';
require_once 'tag_functions.php';
date_default_timezone_set('America/Chicago'); // show report time in Minnesota time

// Admins only (same rule as the other tag pages).
if (!abcd_is_admin()) {
    echo "<script>alert('Unauthorized access. Admin privileges are required.'); window.location.href = 'loginForm.php';</script>";
    exit;
}

abcd_ensure_tag_tables($db);
$tagList = abcd_tag_list();
$report  = abcd_tag_report_data($db, $tagList);
$counts  = $report['counts'];
$total   = max(1, $report['total']);   // avoid dividing by zero on an empty database

// ---- CSV copy of the report (the "Download CSV" button) ----
$csvPath = __DIR__ . '/reports/tag_report.csv';
if ($fh = @fopen($csvPath, 'w')) {
    fputcsv($fh, ['Tag', 'Dresses', 'Percent of dresses']);
    foreach ($counts as $tag => $n) {
        fputcsv($fh, [$tag, $n, round(100 * $n / $total, 1) . '%']);
    }
    if ($report['other'] > 0) {
        fputcsv($fh, ['(tags no longer in the list)', $report['other'], round(100 * $report['other'] / $total, 1) . '%']);
    }
    fputcsv($fh, ['(untagged dresses)', $report['untagged'], round(100 * $report['untagged'] / $total, 1) . '%']);
    fclose($fh);
    $csvReady = true;
} else {
    $csvReady = false;   // reports/ folder not writable; the on-screen report still works
}

// ---- Bar chart data: biggest first, untagged last in gray ----
$chart = $counts;
arsort($chart);
$chartMax = max(1, max($chart ?: [0]), $report['untagged']);

$page_title = 'Project ABCD > Tag Report';
include('header.php');
?>
<link rel="stylesheet" href="css/tags.css">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">

<div class="container mt-5 tag-report">
    <br><br>
    <h2 id="title">Tag Usage Report</h2>

    <!-- Summary line -->
    <p class="report-summary">
        <strong><?= $report['total'] ?></strong> dresses &middot;
        <strong><?= $report['tagged'] ?></strong> tagged &middot;
        <strong><?= $report['untagged'] ?></strong> untagged
        <span class="report-date">(as of <?= date('M j, Y g:i a') ?>)</span>
    </p>

    <?php if (!$tagList): ?>
        <div class="alert alert-warning">The tag list is empty. Add tags with "Modify Tag List" first.</div>
    <?php endif; ?>

    <div class="row">
        <!-- (1) Text table -->
        <div class="col-lg-5">
            <h4 class="report-heading">Table</h4>
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr><th>Tag</th><th class="text-right">Dresses</th><th class="text-right">% of dresses</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($counts as $tag => $n): ?>
                        <tr>
                            <td><?= abcd_e($tag) ?></td>
                            <td class="text-right"><?= $n ?></td>
                            <td class="text-right"><?= round(100 * $n / $total, 1) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($report['other'] > 0): ?>
                        <tr class="text-muted">
                            <td><em>Tags no longer in the list</em></td>
                            <td class="text-right"><?= $report['other'] ?></td>
                            <td class="text-right"><?= round(100 * $report['other'] / $total, 1) ?>%</td>
                        </tr>
                    <?php endif; ?>
                    <tr class="table-warning">
                        <td><strong>Untagged dresses</strong></td>
                        <td class="text-right"><strong><?= $report['untagged'] ?></strong></td>
                        <td class="text-right"><strong><?= round(100 * $report['untagged'] / $total, 1) ?>%</strong></td>
                    </tr>
                </tbody>
            </table>
            <p class="report-note">A dress can carry several tags, so the tag counts can add up to more than the number of dresses.</p>
        </div>

        <!-- (2) Bar chart (plain HTML/CSS bars, so it works offline in XAMPP) -->
        <div class="col-lg-7">
            <h4 class="report-heading">Bar chart: dresses per tag</h4>
            <div class="bar-chart" role="img" aria-label="Bar chart of dresses per tag">
                <?php foreach ($chart as $tag => $n): ?>
                    <div class="bar-row">
                        <span class="bar-label"><?= abcd_e($tag) ?></span>
                        <span class="bar-track">
                            <span class="bar-fill<?= $n ? "" : " bar-zero" ?>" style="width: <?= round(100 * $n / $chartMax, 2) ?>%"></span>
                        </span>
                        <span class="bar-value"><?= $n ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="bar-row bar-row-untagged">
                    <span class="bar-label">Untagged</span>
                    <span class="bar-track">
                        <span class="bar-fill" style="width: <?= round(100 * $report['untagged'] / $chartMax, 2) ?>%"></span>
                    </span>
                    <span class="bar-value"><?= $report['untagged'] ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
        <a href="manage_tags.php" class="btn btn-secondary">&larr; Back to Manage Tags</a>
        <?php if ($csvReady): ?>
            <a href="reports/tag_report.csv" class="btn btn-primary" download>Download CSV Report</a>
        <?php endif; ?>
    </div>
</div>

<style>
/* Tag Report layout (Issue #67) */
.tag-report .report-summary { font-size: 18px; text-align: center; margin: 10px 0 25px; }
.tag-report .report-date    { color: #6c757d; font-size: 14px; }
.tag-report .report-heading { font-size: 20px; margin-bottom: 12px; }
.tag-report .report-note    { color: #6c757d; font-size: 13px; }

.bar-chart  { display: flex; flex-direction: column; gap: 6px; }
.bar-row    { display: grid; grid-template-columns: 140px 1fr 44px; align-items: center; gap: 10px; font-size: 14px; }
.bar-label  { text-align: right; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bar-track  { background: #f1f1f4; border-radius: 4px; height: 22px; overflow: hidden; }
.bar-fill   { display: block; height: 100%; background: #e8590c; border-radius: 4px; min-width: 2px; }
.bar-value  { font-variant-numeric: tabular-nums; }
.bar-row-untagged .bar-fill  { background: #adb5bd; }
.bar-fill.bar-zero { min-width: 0; }
.bar-row-untagged .bar-label { font-style: italic; }

@media (max-width: 576px) {
    .bar-row { grid-template-columns: 100px 1fr 36px; font-size: 13px; }
}
</style>

<footer class="page-footer text-center">
    <br>
    <p>&copy; Summer 2025 Team DOLPHIN 🐬 &middot; Tag Report updated Fall 2026 (Issue #67)</p>
</footer>
