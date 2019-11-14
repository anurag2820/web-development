<?php
	include('connection.php');
	$frndID=$_GET['frndID'];
	$userID=$_GET['userID'];
	$user_fav='favourites_'.$userID;
	$frnd_fav='favourites_'.$frndID;
	$frnd_not='notifications_'.$frndID;
	$result=mysql_query("select * from $user_fav where userID='$frndID' ");
	if($row=mysql_fetch_array($result)){
		echo 'not allowed';
	}
	else{
		mysql_query("insert into $user_fav values('$frndID') ");
		mysql_query("insert into $frnd_fav values('$userID') ");
		//sending notification to the friend
		$name=mysql_query("select * from user_info where userID='$userID' ");
		while($row=mysql_fetch_array($name)){
			$name_user=$row['name'];
		}
		$notification=$name_user.' added you as Friend';
		$notification_type='friends';	
		mysql_query("insert into $frnd_not(notification,notification_type,notification_id) values('$notification','$notification_type','$userID') ");	
		echo 'success';
	}
	
?>