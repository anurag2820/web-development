<?php
	include('connection.php');
	$frndID=$_GET['frndID'];
	$userID=$_GET['userID'];
	$result=mysql_query("select count(*) from messages where seen='$frndID' and reciever='$userID' and seen is NULL ");
	$row=mysql_fetch_array($result);
	echo $row[0];
?>