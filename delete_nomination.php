<?php
/*
 * delete_nomination.php
 * Issue #62 - Review Nominations: Delete a nomination
 *
 * Confirmation page shown when an admin clicks "Delete" on Manage Nominations.
 * Rebuilt so its flow and look match Modify Nomination (modify_nomination.php):
 *   - same page skeleton, stylesheet family, title style and stacked 400px fields
 *   - shows every nomination field (Id, Category, Name, Description, Nominator)
 *   - centered Delete + Cancel buttons; Cancel returns to Manage Nominations
 *   - admin-only, like Manage Nominations
 *   - id is read with a prepared statement and all output is escaped
 */

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Same guard as manageNominations.php: only admins may manage nominations.
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$page_title = 'Project ABCD > Delete Nomination';
require 'bin/functions.php';
require 'db_configuration.php';
include('header.php');
$page = "manageNominations.php";
verifyLogin($page);
ob_end_flush();

// Small helper so every value printed into the page is HTML-safe.
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Look up the nomination being deleted (id must be a positive integer).
$row = null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $stmt = $db->prepare("SELECT id, category, name, description, nominator FROM nominations WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<head>
    <link href="css/deleteNomination.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
</head>

<div class="container">
    <h2 id="title">Delete Nomination</h2><br>

<?php if ($row) { ?>
    <p class="confirmText">
        Are you sure you want to delete the nomination for
        <strong><?php echo e($row['name']); ?></strong>? This cannot be undone.
    </p>

    <form action="delete_the_nomination.php" method="POST">
        <!-- Read-only fields, laid out exactly like Modify Nomination -->
        <div>
            <label for="id">Id</label>
            <input type="text" class="form-control" id="id" name="id" value="<?php echo e($row['id']); ?>" style="width:400px" readonly><br>
        </div>

        <div>
            <label for="category">Category</label>
            <input type="text" class="form-control" id="category" value="<?php echo e($row['category']); ?>" style="width:400px" readonly><br>
        </div>

        <div>
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" value="<?php echo e($row['name']); ?>" style="width:400px" readonly><br>
        </div>

        <div>
            <label for="description">Description</label>
            <!-- textarea so long descriptions are shown in full, as on the Modify page -->
            <textarea class="form-control" id="description" style="width:400px" rows="6" readonly><?php echo e($row['description']); ?></textarea><br>
        </div>

        <div>
            <label for="nominator">Nominator</label>
            <input type="text" class="form-control" id="nominator" value="<?php echo e($row['nominator']); ?>" style="width:400px" readonly><br>
        </div>

        <!-- Centered buttons, matching the btnContainer on Modify Nomination -->
        <div class="btnContainer">
            <button type="submit" name="delete_nomination" class="btn btn-danger btn-md">Delete Nomination</button>
            <a href="manageNominations.php" class="btn btn-secondary btn-md">Cancel</a>
        </div>
        <br>
        <br>
    </form>

<?php } else { ?>
    <!-- Friendly message instead of a bare "0 results" -->
    <p class="confirmText">That nomination could not be found. It may have already been deleted.</p>
    <div class="btnContainer">
        <a href="manageNominations.php" class="btn btn-primary btn-md">Back to Manage Nominations</a>
    </div>
    <br>
<?php } ?>
</div>
