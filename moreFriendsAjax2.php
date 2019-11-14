<?php
	include('connection.php');
	$frndID=$_GET['frndID'];
	$userID=$_GET['userID'];
	$user_fav='favourites_'.$userID;
	$frnd_fav='favourites_'.$frndID;
	$result=mysql_query("select * from $user_fav where userID='$frndID' ");
	if($row=mysql_fetch_array($result)){
		mysql_query("delete from $user_fav where userID='$frndID' ");
		mysql_query("delete from $frnd_fav where userID='$userID' ");
		echo 'success';
	}
	else{
		
		echo 'not allowed';
	}
	
?>