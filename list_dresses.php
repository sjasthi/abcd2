<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

require 'bin/functions.php';
require 'db_configuration.php';

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

$query = "SELECT id, name, LEFT (description, $description_length) as description, LEFT (did_you_know, $did_you_know_length) as did_you_know, category, type, state_name, key_words, image_url, status, notes FROM dresses";

$GLOBALS['data'] = mysqli_query($db, $query);

?>


<?php $page_title = 'Project ABCD > dresses'; ?>

<?php include('header.php'); 
    $page="dresses_list.php";
   // verifyLogin($page);
?>

<style>
    #title {
        text-align: center;
        color: darkgoldenrod;
    }
    #toggle {
        color: 	#4397fb;
    }
    #toggle:hover {
        color: #467bc7
    }
    thead input {
        width: 100%;
    }
    .thumbnailSize{
        height: 100px;
        width: 100px;
        transition:transform 0.25s ease;
    }
    .thumbnailSize:hover {
        -webkit-transform:scale(3.5);
        transform:scale(3.5);
    }
    
</style>

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
    
    <div id="customerTableView">
        <button><a class="btn btn-sm" href="create_dress.php">Create a Dress</a></button>
        <table class="display" id="ceremoniesTable" style="width:100%">
            <div class="table responsive">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Did you know?</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>State Name </th>
                    <th>Key Words</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Image</th>
                    <th>Display</th>
                    <?php

                        if(isset($_SESSION['role'])) {
                        echo '<th>Resource</th>';
                        echo '<th>Modify</th>';
                        echo '<th>Delete</th>';
                        }
                    ?>
                </tr>
                </thead>
                <tbody>
                <div>
                    <strong> Toggle column: </strong> 
                    <a id="toggle" class="toggle-vis" data-column="0">Id</a> - 
                    <a id="toggle" class="toggle-vis" data-column="1">Name</a> - 
                    <a id="toggle" class="toggle-vis" data-column="2">Description</a> - 
                    <a id="toggle" class="toggle-vis" data-column="3">Did You Know</a> - 
                    <a id="toggle" class="toggle-vis" data-column="4">Category</a> - 
                    <a id="toggle" class="toggle-vis" data-column="5">Type</a> - 
                    <a id="toggle" class="toggle-vis" data-column="6">State Name</a> -
                    <a id="toggle" class="toggle-vis" data-column="7">Key Words</a> - 
                    <a id="toggle" class="toggle-vis" data-column="8">Status</a> -
                    <a id="toggle" class="toggle-vis" data-column="9">Notes</a> - 
                    <a id="toggle" class="toggle-vis" data-column="10">Image</a> - 
                    <a id="toggle" class="toggle-vis" data-column="11">Resource</a> - 
                    <a id="toggle" class="toggle-vis" data-column="11">Display</a> -
                    <a id="toggle" class="toggle-vis" data-column="12">Modify</a> - 
                    <a id="toggle" class="toggle-vis" data-column="13">Delete</a> 
                </div> <br>
                
                <?php
                // fetch the data from $_GLOBALS
                if ($data->num_rows > 0) {
                    // output data of each row
                    while($row = $data->fetch_assoc()) {
                    $ID = $row["id"];
                    $name = $row["name"];
                    $description = $row["description"];
                    $did_you_know = $row["did_you_know"];
                    $category = $row["category"];
                    $type = $row["type"];
                    $state_name = $row["state_name"];
                    $key_words = $row["key_words"];
                    $status = $row["status"];
                    $notes = $row["notes"];
                    $image = $row["image_url"];

                    if(isset($_SESSION['role'])) {
                        ?>
                <tr>
                    <td><?php echo $ID; ?></td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'name','<?php echo $ID; ?>')"><?php echo $name; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'description','<?php echo $ID; ?>')"><?php echo $description; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'did_you_know','<?php echo $ID; ?>')"><?php echo $did_you_know; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'category','<?php echo $ID; ?>')"><?php echo $category; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'type','<?php echo $ID; ?>')"><?php echo $type; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'state_name','<?php echo $ID; ?>')"><?php echo $state_name; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'key_words','<?php echo $ID; ?>')"><?php echo $key_words; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'status','<?php echo $ID; ?>')"><?php echo $status; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'notes','<?php echo $ID; ?>')"><?php echo $notes; ?></div></span> </td>
                    <?php echo '<td><img src="images/dress_images/'.$row["image_url"].'" style="width:100px;height:120px;">' ?>
                    <?php echo '<td><a class="btn btn-info btn-sm" href="display_the_dress.php?id='.$row["id"].'">Display</a></td>' ?>
                    <?php echo '<td><a class="btn btn-info btn-sm" href="display_the_resource.php?name='.$row["name"].'">Resource</a></td>' ?>
                    <?php
                    if ($_SESSION['role'] == 'admin'){
                        echo '<td><a class="btn btn-warning btn-sm" href="modify_dress.php?id='.$row["id"].'">Modify</a></td>';
                        echo '<td><a class="btn btn-danger btn-sm" href="deleteDress.php?id='.$row["id"].'">Delete</a></td>';
                    }
                    ?>
                </tr>
                 <?php  
                    } else{
                        echo '<tr>
                        <td>'.$row["id"].'</td>
                        <td> </span> <a href="display_the_dress.php?id='.$row["id"].'">'.$row["name"].'</a></td>
                        <td>'.$row["description"].'</td>
                        <td>'.$row["did_you_know"].'</td>
                        <td>'.$row["category"].' </span> </td>
                        <td>'.$row["type"].'</td>
                        <td>'.$row["state_name"].'</td>
                        <td>'.$row["key_words"].' </span> </td>
                        <td>'.$row["status"].' </span> </td>
                        <td>'.$row["notes"].' </span> </td>
                        <td><img class="thumbnailSize" src="' . "images/dress_images/" .$row["image_url"]. '" alt="'.$row["image_url"].'"></td>
                        <td><a class="btn btn-info btn-sm" href="display_the_dress.php?id='.$row["id"].'">Display</a></td>
                       
                    </tr>';    

                    }//end while
                }//end if
            }//end second if 
  
                ?>

                </tbody>
            </div>
        </table>
    </div>
</div>

<!-- /.container -->
<!-- Footer -->
<footer class="page-footer text-center">
    <p>Created for ICS 325 Summer Project "Team Alligators"</p>
</footer>

<!--JQuery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 

<script type="text/javascript" charset="utf8"
        src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<!--Data Table-->
<script type="text/javascript" charset="utf8"
        src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script type="text/javascript" language="javascript">
    $(document).ready( function () {
        
        $('#ceremoniesTable').DataTable( {
            dom: 'lfrtBip',
            buttons: [
                'copy', 'excel', 'csv', 'pdf'
            ] }
        );

        $('#ceremoniesTable thead tr').clone(true).appendTo( '#ceremoniesTable thead' );
        $('#ceremoniesTable thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    
            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        } );
    
        var table = $('#ceremoniesTable').DataTable( {
            orderCellsTop: true,
            fixedHeader: true,
            retrieve: true
        } );
        
    } );

    $(document).ready(function() {
        
    var table = $('#ceremoniesTable').DataTable( {
        retrieve: true,
        "scrollY": "200px",
        "paging": false
    } );
 
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );
} );


function updateValue(element,column,id){
        var value = element.innerText
        $.ajax({
            url:'editable_list.php',
            type: 'post',
            data:{
                value: value,
                column: column,
                id: id
            },
            success:function(php_result){
				console.log(php_result);
				
            }
            
        })
    }




</script>

</body>
</html>