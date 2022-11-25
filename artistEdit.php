<?php

if(!isset($_SESSION))
{
    session_start();
}

require 'bin/functions.php';
require 'db_configuration.php';
include('header.php');

$query = "SELECT id, CONCAT(first_name,' ',last_name) AS name, profile_picture, description, country, facebook, instagram, twitter, whatsapp, art_site, other FROM artists LEFT JOIN users ON id = user_id";

$GLOBALS['data'] = mysqli_query($db, $query);
?>

<html>

<head>
    <title>ABCD</title>
    <link rel="stylesheet" href="css/artistEdit.css">
</head>
<style>
</style>

<div class="container-fluid">
<body>

	<h1 id="title2">Artist Showcase</h1>
    <h2 id="title">Scroll down to view artists and their products available for purchase using the site art.</h2><br>
    
    <div id="customerTableView">
        <button class="artistEditBtn"><a class="btn btn-sm" href="artistForm.php">Become an Affiliated Artist</a></button>
        <table class="display" id="ceremoniesTable" style="width:100%">
            <div class="table responsive">
                <thead>
                <tr>
                	<th>ID</th>
                    <th>Name</th>
                    <th>Profile Picture</th>
                    <th>Description</th>
                    <th>Country</th>
                    <th>Facebook</th>
                    <th>Instagram</th>
                    <th>Twitter</th>
                    <th>WhatsApp</th>
                    <th>Art Site</th>
                    <th>Other</th>
                </tr>
                </thead>
                <tbody>
                <div>
                    <strong> Toggle column: </strong> 
                    <a id="toggle" class="toggle-vis" data-column="0">ID</a> - 
                    <a id="toggle" class="toggle-vis" data-column="1">Name</a> - 
                    <a id="toggle" class="toggle-vis" data-column="2">Profile Picture</a> - 
                    <a id="toggle" class="toggle-vis" data-column="3">Description</a> - 
                    <a id="toggle" class="toggle-vis" data-column="4">Country</a> - 
                    <a id="toggle" class="toggle-vis" data-column="5">Facebook</a> - 
                    <a id="toggle" class="toggle-vis" data-column="6">Instagram</a> -
                    <a id="toggle" class="toggle-vis" data-column="7">Twitter</a> - 
                    <a id="toggle" class="toggle-vis" data-column="8">WhatsApp</a> -
                    <a id="toggle" class="toggle-vis" data-column="9">Art Site</a> - 
                    <a id="toggle" class="toggle-vis" data-column="10">Other</a>
                </div> <br>
                
                <?php
                // fetch the data from $_GLOBALS
                if ($data->num_rows > 0) {
                    // output data of each row
                    while($row = $data->fetch_assoc()) {
                    $id = $row["id"];
                    $name = $row["name"];
                    $profile_picture = $row["profile_picture"];
                    $description = $row["description"];
                    $country = $row["country"];
                    $facebook = $row["facebook"];
                    $instagram = $row["instagram"];
                    $twitter = $row["twitter"];
                    $whatsapp = $row["whatsapp"];
                    $art_site = $row["art_site"];
                    $other = $row["other"];

                    if(isset($_SESSION['role'])) {
                        ?>
                <tr>
                    <td><?php echo $id; ?></td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'name','<?php echo $ID; ?>')"><?php echo $name; ?></div></span> </td>
                    <?php echo '<td><img src="images/profile_images/'.$row["profile_picture"].'" style="width:100px;height:120px;">' ?>
                    <td><div contenteditable="true" onBlur="updateValue(this,'description','<?php echo $ID; ?>')"><?php echo $description; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'country','<?php echo $ID; ?>')"><?php echo $country; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'facebook','<?php echo $ID; ?>')"><?php echo $facebook; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'instagram','<?php echo $ID; ?>')"><?php echo $instagram; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'twitter','<?php echo $ID; ?>')"><?php echo $twitter; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'whatsapp','<?php echo $ID; ?>')"><?php echo $whatsapp; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'art_site','<?php echo $ID; ?>')"><?php echo $art_site; ?></div></span> </td>
                    <td><div contenteditable="true" onBlur="updateValue(this,'other','<?php echo $ID; ?>')"><?php echo $other; ?></div></span> </td>
                </tr>
                 <?php  
                    } else{
                      echo '<tr>
                      <td>'.$row["id"].'</td>
                      <td> </span> <a href="display_the_artist.php?id='.$row["id"].'">'.$row["name"].'</a></td>
                      <td><img class="thumbnailSize" src="' . "images/profile_images/" .$row["profile_picture"]. '" alt="'.$row["profile_picture"].'"></td>
                      <td>'.$row["description"].'</td>
                      <td>'.$row["country"].' </span> </td>
                      <td>'.$row["facebook"].'</td>
                      <td>'.$row["instagram"].'</td>
                      <td>'.$row["twitter"].' </span> </td>
                      <td>'.$row["whatsapp"].' </span> </td>
                      <td>'.$row["art_site"].' </span> </td>
                      <td>'.$row["other"].' </span> </td>
                  </tr>';    

                    }//end while
                }//end if
            }//end second if 
  
                ?>

                </tbody>
            </div>
        </table>
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
        
//         $('#ceremoniesTable').DataTable( {
//             dom: 'lfrtBip',
//             buttons: [
//                 'copy', 'excel', 'csv', 'pdf'
//             ] }
//         );

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
