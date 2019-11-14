<?php
	include('connection.php');
	session_start();
	$val=$_GET['val'];
	$userID=$_SESSION['userID'];
	$frnd_fav='favourites_'.$userID;
	$result=mysql_query("select * from user_info where name like '$val%' ");
	if($result === FALSE) { 
    die(mysql_error()); // TODO: better error handling
}
	while($row=mysql_fetch_array($result)){
		$frndID=$row['userID'];
		$name=$row['name'];
		$photo_directory='user'.$frndID.'/'.$row['profilepic'];
		$city=$row['city'];
		echo '<div style="margin-left:0px;">';
			echo '<div class="col-xs-12" style="margin-top:8px;">';
				echo '<img src="'.$photo_directory.'" height="25" width="25" style="border-radius:5px;"> <a href="#" onclick="openProfile('.$frndID.','.$userID.');" oncontextmenu="return false" >'.$name.'</a> <h5 style="display:inline;"><small>from '.$city.'</small></h5>';
			echo '</div>';
		echo '</div>';

	}
?>

