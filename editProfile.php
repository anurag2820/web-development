<?php
	include('connection.php');
	$userID=$_GET['userID'];
	$name=$_GET['name'];
	$email=$_GET['email'];
	$password=$_GET['password'];
	$age=$_GET['age'];
	$city=$_GET['city'];
	$phone=$_GET['phone'];
	mysql_query("update user_info set name='$name',email='$email',password='$password',age='$age',city='$city',phone='$phone' where userID='$userID' ");
?>