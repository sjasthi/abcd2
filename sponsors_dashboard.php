<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<body>

    <div class="container mt-5">
        <h1>Sponsors Dashboard</h1>
        <p>This is the main management panel for sponsors. Here you can add new sponsors, update existing ones, or delete them.</p>
        
        <div class="mt-4">
            <a href="create_sponsor.php" class="btn btn-primary">Add New Sponsor</a>
            <a href="update_sponsor.php" class="btn btn-warning">Update Sponsor</a>
            <a href="delete_sponsor.php" class="btn btn-danger">Delete Sponsor</a>
            <a href="list_sponsors.php" class="btn btn-secondary">View All Sponsors</a>
        </div>
    </div>

    <?php include "footer.php"; ?> 

</body>
</html>

