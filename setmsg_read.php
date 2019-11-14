<?php
	include('connection.php');
	$frndID=$_GET['frndID'];
	$userID=$_GET['userID'];
	mysql_query("update messages set seen='yes' where sender='$frndID' and reciever='$userID' ");
?>