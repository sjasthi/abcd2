<?php
/*
 * ai_tagger.php
 * Admin > Tags > AI-Assisted Tagger  (Issue #67)
 *
 * Reads each dress's title, description and "did you know" text and gives it one or
 * more tags from the fixed tag list. The work is done in small batches by
 * ai_tagger_run.php; this page starts the run, shows a progress bar and lists every
 * dress with the tags it received.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_configuration.php';
require_once 'tag_functions.php';
require_once 'ai_tagger_classifier.php';

if (!abcd_is_admin()) {
    echo "<script>alert('Unauthorized access. Admin privileges are required.'); window.location.href = 'loginForm.php';</script>";
    exit;
}

abcd_ensure_tag_tables($db);
$tagList = abcd_tag_list();
$config  = ai_tagger_config();
$report  = abcd_tag_report_data($db, $tagList);
$csrf    = abcd_tag_csrf_token();

$page_title = 'Project ABCD > AI-Assisted Tagger';
include('header.php');
?>
<link rel="stylesheet" href="css/tags.css">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">

<div class="container mt-5 ai-tagger">
    <br><br>
    <h2 id="title">AI-Assisted Tagger</h2>
    <p class="tagger-intro">
        Reads each dress's <strong>title</strong>, <strong>description</strong> and <strong>did you know</strong>
        text and gives it one or more tags from the fixed tag list. Tags ticked by hand on Modify Dress are never removed.
    </p>

    <!-- Current state -->
    <div class="tagger-facts">
        <div><span class="fact-label">Dresses</span><span class="fact-value"><?= $report['total'] ?></span></div>
        <div><span class="fact-label">Already tagged</span><span class="fact-value"><?= $report['tagged'] ?></span></div>
        <div><span class="fact-label">Untagged</span><span class="fact-value" id="untagged-count"><?= $report['untagged'] ?></span></div>
        <div><span class="fact-label">Method</span><span class="fact-value fact-method"><?= abcd_e(ai_tagger_method_label($config)) ?></span></div>
    </div>

    <?php if ($config['provider'] === 'none'): ?>
        <div class="alert alert-info">
            No AI key is set up, so the built-in keyword rules will be used. To use an AI model, copy
            <code>ai_config.sample.php</code> to <code>ai_config.php</code> and add an OpenAI or Anthropic API key.
        </div>
    <?php endif; ?>

    <p class="tagger-tags"><strong>Tags (<?= count($tagList) ?>):</strong> <?= abcd_e(implode(', ', $tagList)) ?></p>

    <?php if (!$tagList): ?>
        <div class="alert alert-warning">The tag list is empty. Add tags with "Modify Tag List" first.</div>
    <?php else: ?>
        <!-- Run options -->
        <form id="tagger-form" class="tagger-form">
            <input type="hidden" name="csrf" value="<?= abcd_e($csrf) ?>">
            <label class="tagger-option">
                <input type="radio" name="mode" value="untagged" checked>
                Tag only dresses that have no tags yet
            </label>
            <label class="tagger-option">
                <input type="radio" name="mode" value="all">
                Re-tag every dress (replaces earlier automatic tags; manual tags stay)
            </label>
            <div class="tagger-buttons">
                <button type="submit" class="btn btn-primary" id="tagger-start">Start AI-Assisted Tagging</button>
                <button type="button" class="btn btn-outline-secondary" id="tagger-stop" hidden>Stop</button>
            </div>
        </form>

        <!-- Progress -->
        <div id="tagger-progress" hidden>
            <div class="progress" style="height: 24px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" id="tagger-bar"
                     role="progressbar" style="width: 0%">0%</div>
            </div>
            <p id="tagger-status" class="tagger-status">Starting&hellip;</p>
            <div id="tagger-warning" class="alert alert-warning" hidden></div>
        </div>

        <!-- Results -->
        <table class="table table-sm table-striped tagger-results" id="tagger-results" hidden>
            <thead><tr><th>ID</th><th>Dress</th><th>Tags</th><th>Method</th></tr></thead>
            <tbody></tbody>
        </table>
    <?php endif; ?>

    <div class="d-flex justify-content-between mt-4 mb-5">
        <a href="manage_tags.php" class="btn btn-secondary">&larr; Back to Manage Tags</a>
        <a href="generate_tag_report.php" class="btn btn-primary" id="tagger-report">Run Tag Report</a>
    </div>
</div>

<style>
/* AI-Assisted Tagger page (Issue #67) */
.ai-tagger .tagger-intro { font-size: 17px; text-align: center; max-width: 760px; margin: 0 auto 20px; }
.tagger-facts { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; margin-bottom: 20px; }
.tagger-facts > div { background: #fff; border: 1px solid #dee2e6; border-radius: 8px; padding: 10px 18px; text-align: center; min-width: 130px; }
.fact-label  { display: block; font-size: 13px; color: #6c757d; }
.fact-value  { display: block; font-size: 24px; font-weight: 600; }
.fact-method { font-size: 15px; font-weight: 600; max-width: 260px; }
.tagger-tags { font-size: 15px; }
.tagger-form { background: #f8f9fa; border-radius: 8px; padding: 16px 20px; margin-bottom: 20px; }
.tagger-option { display: block; font-size: 16px; margin-bottom: 6px; cursor: pointer; }
.tagger-buttons { margin-top: 12px; display: flex; gap: 10px; }
.tagger-status { margin-top: 8px; font-size: 15px; }
.tagger-results td:nth-child(3) .badge { margin: 0 4px 2px 0; font-size: 13px; font-weight: 500; }
.badge-tag { background: #fde3d3; color: #8a2e00; }
</style>

<script>
/*
 * Runs the tagger batch by batch: each request to ai_tagger_run.php tags a few dresses
 * and returns where to continue, until no dresses are left (or Stop is pressed).
 */
(function () {
  var form      = document.getElementById('tagger-form');
  if (!form) return;
  var startBtn  = document.getElementById('tagger-start');
  var stopBtn   = document.getElementById('tagger-stop');
  var progress  = document.getElementById('tagger-progress');
  var bar       = document.getElementById('tagger-bar');
  var status    = document.getElementById('tagger-status');
  var warningEl = document.getElementById('tagger-warning');
  var table     = document.getElementById('tagger-results');
  var tbody     = table.querySelector('tbody');
  var stopped   = false;
  var done      = 0;
  var total     = 0;

  function esc(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function setBar(pct) {
    pct = Math.max(0, Math.min(100, Math.round(pct)));
    bar.style.width = pct + '%';
    bar.textContent = pct + '%';
  }

  function finish(message, ok) {
    bar.classList.remove('progress-bar-animated');
    bar.classList.toggle('bg-success', ok);
    status.innerHTML = message;
    startBtn.disabled = false;
    startBtn.textContent = 'Run again';
    stopBtn.hidden = true;
    form.querySelectorAll('input').forEach(function (i) { i.disabled = false; });
  }

  function runBatch(mode, afterId) {
    if (stopped) {
      finish('Stopped after ' + done + ' dresses. Press "Run again" to continue.', false);
      return;
    }
    var data = new FormData();
    data.append('csrf', form.csrf.value);
    data.append('mode', mode);
    data.append('after_id', afterId);

    fetch('ai_tagger_run.php', { method: 'POST', body: data, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res.ok) { throw new Error(res.error || 'Unknown error'); }

        res.results.forEach(function (r) {
          var tags = r.tags.map(function (t) { return '<span class="badge badge-tag">' + esc(t) + '</span>'; }).join('');
          var row = document.createElement('tr');
          row.innerHTML = '<td>' + r.id + '</td><td>' + esc(r.name) + '</td><td>' + tags + '</td><td>' +
                          (r.source === 'ai' ? 'AI model' : 'Keyword rules') + '</td>';
          tbody.appendChild(row);
        });
        if (res.warning) { warningEl.textContent = res.warning; warningEl.hidden = false; }

        done += res.processed;
        if (total === 0) { total = done + res.remaining; }
        setBar(total ? (100 * done / total) : 100);
        status.textContent = 'Tagged ' + done + ' of ' + total + ' dresses' + (res.done ? '.' : '…');

        if (res.done) {
          finish('Done: tagged <strong>' + done + '</strong> dresses. Run the Tag Report to see the result.', true);
        } else {
          runBatch(mode, res.last_id);
        }
      })
      .catch(function (err) {
        finish('<span class="text-danger">Stopped: ' + esc(err.message) + '</span>', false);
      });
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var mode = form.mode.value;
    if (mode === 'all' && !confirm('Re-tag every dress? Earlier automatic tags will be replaced (manual tags stay).')) {
      return;
    }
    stopped = false; done = 0; total = 0;
    tbody.innerHTML = '';
    warningEl.hidden = true;
    progress.hidden = false;
    table.hidden = false;
    bar.classList.add('progress-bar-animated');
    bar.classList.remove('bg-success');
    setBar(0);
    status.textContent = 'Starting…';
    startBtn.disabled = true;
    stopBtn.hidden = false;
    stopBtn.disabled = false;
    form.querySelectorAll('input[type=radio]').forEach(function (i) { i.disabled = true; });
    runBatch(mode, 0);
  });

  stopBtn.addEventListener('click', function () { stopped = true; stopBtn.disabled = true; });
})();
</script>

<footer class="page-footer text-center">
    <br>
    <p>&copy; Project ABCD &middot; AI-Assisted Tagger added Fall 2026 (Issue #67)</p>
</footer>
