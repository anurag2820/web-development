<?php
	include('connection.php');
	$val=$_GET['val'];
	session_start();
	$userID=$_SESSION['userID'];
	$frnd_not='favourites_'.$userID;
	$result=mysql_query("select * from user_info where userID in (select userID from $frnd_not) and name like '$val%' ");
	while($row=mysql_fetch_array($result)){
		$frndID=$row['userID'];
		$name=$row['name'];
		$photo_directory='user'.$frndID.'/'.$row['profilepic'];
		$badgeID='badge'.$frndID;
		$value='';
		$result2=mysql_query("select count(*) from messages where reciever='$userID' and sender='$frndID' and seen is NULL ");
		$row2=mysql_fetch_array($result2);
		if($row2[0]!=0)
			$value=$row2[0]; 
		echo '<div>';
			echo '<div class="col-xs-10" style="margin-top:8px; padding-right:0px;">';
				echo '<img src="'.$photo_directory.'" height="50" width="50" style="border-radius:5px;"/> <a href="#" onclick="openProfile('.$frndID.','.$userID.');" oncontextmenu="return false">'.$name.'</a>';
				echo ' <span class="badge" id="'.$badgeID.'" style="background-color:rgb(249,53,53);">'.$value.'</span>';
			echo '</div>';
			echo '<div class="col-xs-2" style="margin-top:14px; padding:0px;">';
				echo '<button class="btn btn-default" onclick="chat('.$userID.','.$frndID.','.'\''.$photo_directory.'\''.','.'\''.$name.'\''.' );">chat</button>';
			echo '</div>';			
		echo '</div>';

	}

?>