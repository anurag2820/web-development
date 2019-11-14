<?php
	include('connection.php');
	$val=$_GET['val'];
	session_start();
	$userID=$_SESSION['userID'];
	$frnd_not='favourites_'.$userID;
	$result=mysql_query("select * from user_info where userID not in (select userID from $frnd_not union select userID from user_info where userID='$userID' ) and name like '$val%' ");
	while($row=mysql_fetch_array($result)){
		$frndID=$row['userID'];
		$name=$row['name'];
		$photo_directory='user'.$frndID.'/'.$row['profilepic'];
		$city=$row['city'];
		echo '<div>';
			echo '<div class="col-xs-8" style="margin-top:8px;">';
				echo '<img src="'.$photo_directory.'" height="50" width="50" style="border-radius:5px;"> <a href="#" onclick="openProfile('.$frndID.','.$userID.');" oncontextmenu="return false">'.$name.'</a> <h5 style="display:inline;"><small>from '.$city.'</small></h5>';
			echo '</div>';
			echo '<div class="col-xs-4" style="margin-top:8px;">';
				$add_frndID='add'.$frndID;
				echo '<div id="'.$add_frndID.'" onclick="addFriend('.$frndID.','.$userID.')" class="label label-success" style=" cursor:pointer; position:relative; top:17px;"> <span class="glyphicon glyphicon-plus"></span> Add to your Friend List</div>';
			echo '</div>';
		echo '</div>';
	}

?>