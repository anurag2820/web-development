<?php
	include('connection.php');
	session_start();
	$sno=$_GET['sno'];
	$_SESSION['sno']=$sno;
	$userID=$_SESSION['userID'];
	$not_table='notifications_'.$userID;
	$result=mysql_query("select notification_id,notification_type from $not_table where sno='$sno' ");
	while($row=mysql_fetch_array($result)){
		$notification_type=$row['notification_type'];
		if($notification_type=='update')
			echo "update";
		else{
			$_SESSION['frndID']=$row['notification_id'];
			echo "friends";
		}
	}
?>