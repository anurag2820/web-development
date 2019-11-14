<?php
	include('connection.php');
	$frndID=$_GET['frndID'];
	$userID=$_GET['userID'];
	$frnd_fav="favourites_".$frndID;
	$user_fav="favourites_".$userID;
	mysql_query("delete from $frnd_fav where userID='$userID' ");
	mysql_query("delete from $user_fav where userID='$frndID' ");
?>