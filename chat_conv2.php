<?php
	include('connection.php'); 
	$msg=$_GET['msg'];
	$userID=$_GET['userID'];
	$frndID=$_GET['frndID'];
	$result=mysql_query("select * from messages where (seen='$userID' or reciever='$userID') and (seen='$frndID' or reciever='$frndID')  ");
	while ($row=mysql_fetch_array($result)) {
		
		if($row['seen']==$userID){
			echo '<div class="col-xs-offset-4 col-xs-8">';
				echo '<div class="alert alert-success" style="padding:0px;">';
	 				echo $row['msg'];					 
	 			echo '</div>';
	 		echo '</div>';
		}
		else{
			echo '<div class="col-xs-8">';
				echo '<div class="alert alert-warning" style="padding:0px;">';
	 				echo $row['msg'];					 
	 			echo '</div>';
	 		echo '</div>';
		}
	}
?>