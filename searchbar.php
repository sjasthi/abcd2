<?php
session_start();

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

$keywords = $_POST['search'];

$query = "SELECT id, name, LEFT (description, $description_length) as description, LEFT (did_you_know, $did_you_know_length) as did_you_know, category, type, state_name, key_words, image_url, status, notes FROM dresses WHERE (description like '%$keywords%' OR name like '%$keywords%')";

$GLOBALS['data'] = mysqli_query($db, $query);

?>


<?php $page_title = 'Project ABCD > searchbar'; ?>

<?php include('header.php'); 
    $page="searchbar.php";
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
   
    <h2 id="title">Search Results</h2><br>
    
    <div id="customerTableView">
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
                </tr>
                </thead>
                <tbody>
                
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
                    <td><div onBlur="updateValue(this,'name','<?php echo $ID; ?>')"><?php echo"<a href='./display_the_dress.php?id=$ID'>$name</a>"; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'description','<?php echo $ID; ?>')"><?php echo $description; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'did_you_know','<?php echo $ID; ?>')"><?php echo $did_you_know; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'category','<?php echo $ID; ?>')"><?php echo $category; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'type','<?php echo $ID; ?>')"><?php echo $type; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'state_name','<?php echo $ID; ?>')"><?php echo $state_name; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'key_words','<?php echo $ID; ?>')"><?php echo $key_words; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'status','<?php echo $ID; ?>')"><?php echo $status; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'notes','<?php echo $ID; ?>')"><?php echo $notes; ?></div></span> </td>
                    <?php echo '<td><a href="./display_the_dress.php?id='.$ID.'"><img src="images/dress_images/'.$row["image_url"].'" style="width:100px;height:120px;"></a></td>' ?>
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
        

        //$('#ceremoniesTable thead tr').clone(true).appendTo( '#ceremoniesTable thead' );
//         $('#ceremoniesTable thead tr:eq(1) th').each( function (i) {
//             var title = $(this).text();
//             $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    
//             $( 'input', this ).on( 'keyup change', function () {
//                 if ( table.column(i).search() !== this.value ) {
//                     table
//                         .column(i)
//                         .search( this.value )
//                         .draw();
//                 }
//             } );
//         } );
    
//         var table = $('#ceremoniesTable').DataTable( {
//             orderCellsTop: true,
//             fixedHeader: true,
//             retrieve: true
//         } );
        
//     } );

//     $(document).ready(function() {
        
//     var table = $('#ceremoniesTable').DataTable( {
//         retrieve: true,
//         "scrollY": "200px",
//         "paging": false
//     } );
 
//     $('a.toggle-vis').on( 'click', function (e) {
//         e.preventDefault();
 
//         // Get the column API object
//         var column = table.column( $(this).attr('data-column') );
 
//         // Toggle the visibility
//         column.visible( ! column.visible() );
//     } );
// } );




</script>

</body>
</html>
