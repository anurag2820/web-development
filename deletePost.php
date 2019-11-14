<?php
	include('connection.php');
	$updID=$_GET['updID'];
	$userID=$_GET['userID'];
	mysql_query("delete from updates where updID='$updID' ");
	//now display result
	$fav_user='favourites_'.$userID;
	$table_result=mysql_query("select * from updates where updated_by='$userID' order by updID desc ");
	while($upd=mysql_fetch_array($table_result)){
		$updID=$upd['updID'];
		$updated_by=$upd['updated_by'];
		$update_content=$upd['update_content'];
			$result=mysql_query("select name,profilepic from user_info where userID='$updated_by' ");
			$row=mysql_fetch_array($result);
			$updated_by_name=$row['name'];
			$photo_directory='user'.$updated_by.'/'.$row['profilepic'];

 			echo '<div class="col-xs-12">';
 				echo '<div class="row">';
 					echo '<div class="col-xs-6">';
 						echo '&nbsp;<img src="'.$photo_directory.'" height="45" width="45" style="border-radius:5px;"> <a href="profile.php">'.$updated_by_name.'</a> <h5 style="display:inline;"><small> posted an update</small></h5>';
 					echo '</div>';
 					echo '<div class="col-xs-offset-4 col-xs-2" style="position:relative; top:12px;">';
 						echo '&times; <a href="#" onclick="deletePost('.$updID.','.$userID.'">delete post</a>';
 					echo '</div>';
 				
 					echo '<div class="col-xs-12 jumbotron" style="margin:0px; background-color:rgb(245,245,245);">';
 						echo '<p class="text-justify" style="font-size:13px;">'.$update_content.'</p>';
 					echo '</div>';
	 			echo '</div>';
	 		echo '</div>';

	}			
?>