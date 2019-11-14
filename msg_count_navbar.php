<?php
	include('connection.php');
	session_start();
	$userID=$_SESSION['userID'];
	$result=mysql_query("select count(*) from messages where reciever='$userID' and seen is NULL ");
	if($result === FALSE) { 
    die(mysql_error()); // TODO: better error handling
}
	$row=mysql_fetch_array($result);
	echo $row[0];
?>