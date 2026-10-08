<?php
// require_once 'bin/debug_config.php'; //uncomment if debugging is needed

session_start();

require 'bin/functions.php';
require_once 'db_configuration.php';

//Read cookies for description and did you know length, use defauts if not set.
$description_length = 1000;
$did_you_know_length = 1000;

$description_length_cookie_name = "description_length";
$did_you_know_length_cookie_name = "did_you_know_length";

if (isset($_COOKIE[$description_length_cookie_name])) {
    $description_length = $_COOKIE[$description_length_cookie_name];
}

if (isset($_COOKIE[$did_you_know_length_cookie_name])) {
    $did_you_know_length = $_COOKIE[$did_you_know_length_cookie_name];
}
//Cookies read
// Sort alphabetically so the list is predictable before DataTables even loads
$query = "SELECT id, name, type, category, state_name, key_words, image_url, status, notes, tag_line
          FROM dresses ORDER BY name";
$data = mysqli_query($db, $query);

// One flag for "is admin", used everywhere. The old code used isset($_SESSION['role'])
// for the headers but == 'admin' for the rows, so a logged-in non-admin got
// 14 header cells and 8 row cells, which breaks DataTables.
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Escape anything from the database before printing it, so a quote or <tag>
// inside a dress name can't break the page (or inject script).
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Shows an em dash instead of a blank cell, so empty fields are visibly empty
function orDash($value) {
    return ($value === null || trim($value) === '') ? '<span class="empty">—</span>' : e($value);
}

// Builds an inline-editable cell for admins. The column name and id go into data-*
// attributes, and the JavaScript at the bottom of the page reads them on blur.
function editableCell($column, $value, $id) {
    return '<td><div class="editable" contenteditable="true" spellcheck="false" data-column="' . e($column) .           '" data-id="' . (int)$id . '">' . e($value) . '</div></td>';
}


?>

<?php $page_title = 'Project ABCD > dresses'; ?>

<?php include('header.php'); 
    $page="dresses_list.php";
   // verifyLogin($page);
?>

<head>
<link href="css/list_dresses.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
  
</head>



<!-- Page Content -->
<br><br>
<div class="container-fluid">
    <?php
        if(isset($_GET['createDress'])){
            if($_GET["createDress"] == "Success"){
                echo '<br><h3>Success! Your Dress has been added!</h3>';
            }
        }

        if(isset($_GET['DressUpdated'])){
            if($_GET["DressUpdated"] == "Success"){
                echo '<br><h3>Success! Your Dress has been modified!</h3>';
            }
        }

        if(isset($_GET['DressDeleted'])){
            if($_GET["DressDeleted"] == "Success"){
                echo '<br><h3>Success! Your Dress has been deleted!</h3>';
            }
        }

        if(isset($_GET['createTopic'])){
            if($_GET["createTopic"] == "Success"){
                echo '<br><h3>Success! Your topic has been added!</h3>';
            }
        }
    ?>
   
    <h2 id="title">Dresses List</h2><br>

    <div id="buttonContainer">
    <button><a class="btn btn-sm" href="create_dress.php">Create a Dress</a></button>
    </div>
    
   <!-- Toggles now sit OUTSIDE the table (they were inside <tbody> before) -->
<div class="toggles">
    <strong>Show/hide columns:</strong>
    <a href="#" class="toggle-vis" data-column="0">Name</a>
    <a href="#" class="toggle-vis" data-column="1">Tag Line</a>
    <a href="#" class="toggle-vis" data-column="2">Category</a>
    <a href="#" class="toggle-vis" data-column="3">Type</a>
    <a href="#" class="toggle-vis" data-column="4">State Name</a>
    <a href="#" class="toggle-vis" data-column="5">Key Words</a>
    <a href="#" class="toggle-vis" data-column="6">Image</a>
    <a href="#" class="toggle-vis" data-column="7">Display</a>
    <?php if ($isAdmin): ?>
        <a href="#" class="toggle-vis" data-column="8">ID</a>
        <a href="#" class="toggle-vis" data-column="9">Status</a>
        <a href="#" class="toggle-vis" data-column="10">Notes</a>
        <a href="#" class="toggle-vis" data-column="11">Resource</a>
        <a href="#" class="toggle-vis" data-column="12">Modify</a>
        <a href="#" class="toggle-vis" data-column="13">Delete</a>
    <?php endif; ?>
</div>

<!-- This wrapper lets the table scroll sideways on small screens instead of overflowing -->
<div class="table-scroll">
    <table class="display" id="dressesTable" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Tag Line</th>
            <th>Category</th>
            <th>Type</th>
            <th>State Name</th>
            <th>Key Words</th>
            <!-- no-sort: no sort arrows; no-filter: no search box (pointless for images/buttons) -->
            <th class="no-sort no-filter">Image</th>
            <th class="no-sort no-filter">Display</th>
            <?php if ($isAdmin): ?>
                <th>ID</th>
                <th>Status</th>
                <th>Notes</th>
                <th class="no-sort no-filter">Resource</th>
                <th class="no-sort no-filter">Modify</th>
                <th class="no-sort no-filter">Delete</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
                
<?php if ($data && $data->num_rows > 0): ?>
    <?php while ($row = $data->fetch_assoc()): $id = (int)$row['id']; ?>
        <tr>
            <?php if ($isAdmin): ?>
                <?= editableCell('name', $row['name'], $id) ?>
                <?= editableCell('tag_line', $row['tag_line'], $id) ?>
                <?= editableCell('category', $row['category'], $id) ?>
                <?= editableCell('type', $row['type'], $id) ?>
                <?= editableCell('state_name', $row['state_name'], $id) ?>
                <?= editableCell('key_words', $row['key_words'], $id) ?>
            <?php else: ?>
                <td><a href="display_the_dress.php?id=<?= $id ?>"><?= e($row['name']) ?></a></td>
                <td><?= orDash($row['tag_line']) ?></td>
                <td><?= orDash($row['category']) ?></td>
                <td><?= orDash($row['type']) ?></td>
                <td><?= orDash($row['state_name']) ?></td>
                <td><?= orDash($row['key_words']) ?></td>
            <?php endif; ?>

            <!-- loading="lazy" means images below the fold load only when you scroll to them -->
            <td><img class="thumbnailSize" loading="lazy"
                     src="images/dress_images/<?= e($row['image_url']) ?>"
                     alt="<?= e($row['name']) ?>"></td>
            <td><a class="btn btn-info btn-sm" href="display_the_dress.php?id=<?= $id ?>">Display</a></td>

            <?php if ($isAdmin): ?>
                <td><?= $id ?></td>
                <!-- Status is a colored badge; the class name comes from the status text -->
                <td><span class="status-badge status-<?= e(str_replace(' ', '-', strtolower($row['status'] ?? ''))) ?>">
                    <?= orDash($row['status']) ?></span></td>
                <td><?= orDash($row['notes']) ?></td>
                <!-- urlencode so names with spaces or & produce a valid link -->
                <td><a class="btn btn-info btn-sm" href="display_the_resource.php?name=<?= urlencode($row['name']) ?>">Resource</a></td>
                <td><a class="btn btn-warning btn-sm" href="modify_dress.php?id=<?= $id ?>">Modify</a></td>
                <!-- Delete now asks first instead of deleting on a single click -->
                <td><a class="btn btn-danger btn-sm" href="deleteDress.php?id=<?= $id ?>"
                       onclick="return confirm('Delete this dress? This cannot be undone.');">Delete</a></td>
            <?php endif; ?>
                </tr>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- csv upload form for admin -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
    <h1 id="section-heading">Upload CSV to Update Dress Keywords</h1>
    <form action="upload_dresses_keywords.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="csvFile" accept=".csv" required>
        <button type="submit">Upload and Update</button>
    </form>
<?php } ?>

<!-- /.container -->
<!-- Footer -->
<footer class="page-footer text-center">
    <p>Created for ICS 325 Summer Project "Team Alligators"</p>
    <p>© Summer 2025 Updated by Team DOLPHIN 🐬</p><br>
</footer>


<!-- Load each library ONCE: full jQuery (header.php only loads the "slim" build,
     which lacks $.ajax), then DataTables, then the export buttons -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function () {
    var $table = $('#dressesTable');

    // Build the per-column search row BEFORE DataTables starts, so it's set up once
    $table.find('thead tr').clone().addClass('filters').appendTo($table.find('thead'));
    $table.find('thead tr.filters th').each(function (i) {
        var $th = $(this).removeClass('sorting sorting_asc sorting_desc');
        if ($th.hasClass('no-filter')) { $th.empty(); return; }
        $th.html('<input type="text" data-col="' + i + '" placeholder="' + $th.text().trim() + '">');
    });

    var table = $table.DataTable({
        orderCellsTop: true,            // sort using the top header row, not the search row
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        dom: 'lfrtBip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        columnDefs: [{ targets: 'no-sort', orderable: false }],
        language: { search: 'Search all:', emptyTable: 'No dresses found.' }
    });

    // Wire up the per-column search boxes
    $table.find('thead tr.filters input')
        .on('click', function (e) { e.stopPropagation(); })   // clicking a box shouldn't re-sort
        .on('keyup change', function () {
            var col = table.column($(this).data('col'));
            if (col.search() !== this.value) col.search(this.value).draw();
        });

    // Show/hide columns; "off" grays out the link so you can see what's hidden
    $('a.toggle-vis').on('click', function (e) {
        e.preventDefault();
        var col = table.column($(this).data('column'));
        col.visible(!col.visible());
        $(this).toggleClass('off', !col.visible());
    });

    // On phones, start with the less important columns hidden
    if (window.innerWidth < 768) {
             [1, 4, 5, 7].forEach(function (i) {       // Tag Line, State Name, Key Words, Display
            table.column(i).visible(false);
            $('a.toggle-vis[data-column="' + i + '"]').addClass('off');
        });
    }

    // Inline editing for admins. Remember the value on focus, save on blur only if it changed.
    $table.on('focusin', '.editable', function () {
        $(this).data('original', $(this).text());
    });
    $table.on('focusout', '.editable', function () {
        var $cell = $(this);
        var value = $cell.text().trim();
        if (value === $cell.data('original')) return;   // nothing changed, skip the request

        $.post('editable_list.php', {
            column: $cell.data('column'),
            id: $cell.data('id'),
            value: value
        }).done(function (res) {
            $cell.addClass(res.ok ? 'saved' : 'save-error');
            if (res.ok) table.cell($cell.closest('td')).invalidate();  // keeps sorting/search current
        }).fail(function () {
            $cell.addClass('save-error');
        }).always(function () {
            setTimeout(function () { $cell.removeClass('saved save-error'); }, 1500);
        });
    });
});
</script>
</body>
</html>