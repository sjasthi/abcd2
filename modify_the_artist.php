<?php 
session_start();
include 'db_configuration.php';

if (isset($_POST['update_artist']))
{
	$user_id =  $_POST['user_id'];
	$profile_picture =  $_POST['profile_picture'];
	$description =  $_POST['description'];
	$country =  $_POST['country'];
	$facebook =  $_POST['facebook'];
	$instagram = $_POST['instagram'];
	$twitter = $_POST['twitter'];
	$whatsapp = $_POST['whatsapp'];
	$art_site = $_POST['art_site'];
	$other = $_POST['other'];


	$query = "UPDATE artists
        SET user_id = '$user_id',
			profile_picture = '$profile_picture',
			description = '$description',
			country = '$country',
			facebook = '$facebook',
			instagram = '$instagram',
			twitter = '$twitter',
			whatsapp = '$whatsapp',
			art_site = '$art_site',
			other = '$other'
        WHERE user_id ='$user_id'";

	$query_use = mysqli_query($db, $query);

	if ($query_use)
	{
		header('location:artistEdit.php?Updated=Success');
	}
	else
	{
		header('location:artistEdit.php?Updated=Failed&id='.$id);
	}
}
?>;