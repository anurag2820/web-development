<?php
	include('connection.php');
	$updID=$_GET['updID'];
	$userID=$_GET['userID'];
	$frndID=$_GET['frndID'];
	$relation=$_GET['relation'];
	//initializing
	$likes=0;
	$frnd_not_table='notifications_'.$frndID;
	$name=mysql_query("select * from user_info where userID='$userID' ");
	while($row=mysql_fetch_array($name)){
		$name_user=$row['name'];
	}
	$notification=$name_user.' likes your update'; //notifiaction message
	$notification_type='update'; //means update table's content has been liked
	

	//checking whether already liked or not
	$rst=mysql_query("select * from likes where ID='$updID' and liked_by='$userID' ");
 	if ($row=mysql_fetch_array($rst)) {
 		mysql_query("delete from likes where ID='$updID' and liked_by='$userID' ");
 	}
 	else{
 		mysql_query("insert into likes(relation,ID,liked_by) values('$relation','$updID','$userID') ");	

 		//send the notification now to the friend whose update/photo_update has been liked
 		if($userID!=$frndID)
 		mysql_query("insert into $frnd_not_table(notification,notification_type,notification_id) values('$notification','$notification_type','$updID') ");	
 	}
	
		$result=mysql_query("select count(*) from likes where ID='$updID' ");
		$row=mysql_fetch_array($result);
		$likes=$row[0];
		echo $likes;
?>