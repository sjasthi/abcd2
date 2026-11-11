<?php
/*
 * abcd_copyrights.php   (FP9 - Copyrights)
 * -------------------------------------------------------------------
 * Lists every copyright violation: web pages (for example Pinterest
 * pins) that use Project ABCD pictures without permission.
 *
 * Admin only. From this one page an administrator can:
 *   - Create a violation (paste the URL of the offending page)
 *   - Update a violation (fix the URL and/or change its status)
 *   - Delete a violation
 *   - Click a URL to open the offending page in a new tab
 *
 * Data lives in the `copyright_violations` table (id, url, status).
 * Status values:
 *   Created   - we found it and logged it
 *   Contacted - a letter was sent to them
 *   Closed    - they removed the copyrighted picture
 *
 * The table is created automatically on first load (CREATE TABLE IF
 * NOT EXISTS); the same schema is in sql/copyright_violations.sql.
 * -------------------------------------------------------------------
 */

if (!isset($_SESSION)) {
    session_start();
}

require_once 'db_configuration.php';

// ---------- admin check (same pattern as admin.php) ----------
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Unauthorized access. Admin privileges are required.');
            window.location.href = 'loginForm.php';
          </script>";
    exit;
}

// Escape anything printed into HTML so stored text can't inject markup.
function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// One CSRF token per session; every form below sends it back.
if (empty($_SESSION['cv_csrf'])) {
    $_SESSION['cv_csrf'] = bin2hex(random_bytes(16));
}

// The only allowed statuses, in workflow order.
$statuses = ['Created', 'Contacted', 'Closed'];

// ---------- make sure the table exists ----------
$db->query("CREATE TABLE IF NOT EXISTS `copyright_violations` (
  `id`     int(11)      NOT NULL AUTO_INCREMENT,
  `url`    varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Created','Contacted','Closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Created',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Returns the cleaned URL, or null if it isn't a valid http(s) link.
function clean_url($raw)
{
    $url = trim((string)$raw);
    if ($url === '' || strlen($url) > 500 || !filter_var($url, FILTER_VALIDATE_URL)) {
        return null;
    }
    $scheme = strtolower(parse_url($url, PHP_URL_SCHEME) ?? '');
    return in_array($scheme, ['http', 'https'], true) ? $url : null;
}

// ---------- create / update / delete ----------
$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['cv_csrf'], $_POST['csrf'] ?? '')) {
        $error = 'Your session expired. Please try again.';
    } else {
        $action = $_POST['action'] ?? '';
        $id     = (int)($_POST['id'] ?? 0);
        $status = in_array($_POST['status'] ?? '', $statuses, true) ? $_POST['status'] : 'Created';

        if ($action === 'create') {
            $url = clean_url($_POST['url'] ?? '');
            if ($url === null) {
                $error = 'Please enter a valid web address starting with http:// or https://';
            } else {
                $stmt = $db->prepare("INSERT INTO copyright_violations (url, status) VALUES (?, ?)");
                $stmt->bind_param('ss', $url, $status);
                $stmt->execute();
                $stmt->close();
                $message = 'Violation created.';
            }
        } elseif ($action === 'update') {
            $url = clean_url($_POST['url'] ?? '');
            if ($url === null) {
                $error = "Violation #$id was not saved: the web address is not valid.";
            } else {
                $stmt = $db->prepare("UPDATE copyright_violations SET url = ?, status = ? WHERE id = ?");
                $stmt->bind_param('ssi', $url, $status, $id);
                $stmt->execute();
                $stmt->close();
                $message = "Violation #$id updated.";
            }
        } elseif ($action === 'delete') {
            $stmt = $db->prepare("DELETE FROM copyright_violations WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            $message = "Violation #$id deleted.";
        }
    }
}

// ---------- load all violations + counts per status ----------
$violations = $db->query("SELECT id, url, status FROM copyright_violations ORDER BY id DESC")
                 ->fetch_all(MYSQLI_ASSOC);

$counts = array_fill_keys($statuses, 0);
foreach ($violations as $v) {
    $counts[$v['status']]++;
}

$page_title = 'Project ABCD > Copyrights';
include('header.php');
?>

<style>
    #cv-wrap { max-width: 1100px; margin: 40px auto; padding: 0 15px; }
    #cv-wrap h2 { text-align: center; margin-bottom: 6px; }
    .cv-summary { text-align: center; margin-bottom: 20px; }
    .cv-status { font-weight: bold; padding: 2px 8px; border-radius: 4px; color: #fff; }
    .cv-Created   { background: #dc3545; }   /* red    - still up      */
    .cv-Contacted { background: #fd7e14; }   /* orange - letter sent   */
    .cv-Closed    { background: #28a745; }   /* green  - picture gone  */
    #cvTable td { vertical-align: middle; }
    #cvTable input[type=url] { min-width: 320px; }
    .cv-link { word-break: break-all; }
</style>

<div id="cv-wrap">
    <h2>Copyright Violations</h2>
    <p class="cv-summary">
        <?php foreach ($statuses as $s): ?>
            <span class="cv-status cv-<?php echo $s; ?>"><?php echo $s; ?>: <?php echo $counts[$s]; ?></span>&nbsp;
        <?php endforeach; ?>
    </p>

    <?php if ($message): ?><div class="alert alert-success"><?php echo h($message); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo h($error); ?></div><?php endif; ?>

    <!-- [1] Create a new violation -->
    <form method="post" class="form-inline mb-4 p-3 border rounded">
        <input type="hidden" name="action" value="create">
        <input type="hidden" name="csrf" value="<?php echo h($_SESSION['cv_csrf']); ?>">
        <label class="mr-2" for="new_url"><strong>New violation URL</strong></label>
        <input class="form-control mr-2 flex-grow-1" id="new_url" name="url" type="url" maxlength="500" required
               placeholder="https://in.pinterest.com/pin/...">
        <select class="form-control mr-2" name="status">
            <?php foreach ($statuses as $s): ?><option><?php echo $s; ?></option><?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>

    <?php if (count($violations) === 0): ?>
        <p class="text-center text-muted">No copyright violations recorded yet.</p>
    <?php else: ?>
        <table id="cvTable" class="display table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($violations as $v): $fid = 'cv-edit-' . (int)$v['id']; ?>
                <tr>
                    <td><?php echo (int)$v['id']; ?></td>
                    <td>
                        <!-- [2] Clicking the link opens the offending page in a new tab -->
                        <a class="cv-link" href="<?php echo h($v['url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo h($v['url']); ?></a>
                        <!-- Editable copy of the URL, submitted with the Update form -->
                        <input class="form-control form-control-sm mt-1" type="url" name="url" form="<?php echo $fid; ?>"
                               value="<?php echo h($v['url']); ?>" maxlength="500" required aria-label="Edit URL">
                    </td>
                    <td>
                        <!-- [3] Status column: Created / Contacted / Closed -->
                        <span class="cv-status cv-<?php echo h($v['status']); ?>"><?php echo h($v['status']); ?></span>
                        <select class="form-control form-control-sm mt-1" name="status" form="<?php echo $fid; ?>" aria-label="Change status">
                            <?php foreach ($statuses as $s): ?>
                                <option <?php echo $s === $v['status'] ? 'selected' : ''; ?>><?php echo $s; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <form method="post" id="<?php echo $fid; ?>">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo (int)$v['id']; ?>">
                            <input type="hidden" name="csrf" value="<?php echo h($_SESSION['cv_csrf']); ?>">
                            <button class="btn btn-warning btn-sm">Update</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" onsubmit="return confirm('Delete violation #<?php echo (int)$v['id']; ?>?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo (int)$v['id']; ?>">
                            <input type="hidden" name="csrf" value="<?php echo h($_SESSION['cv_csrf']); ?>">
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>

<!-- DataTables: search, sort and paging, like the Dresses page -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
    $(function () {
        if ($('#cvTable').length) {
            $('#cvTable').DataTable({ order: [[0, 'desc']], pageLength: 25 });
        }
    });
</script>
