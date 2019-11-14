<?php
	include('connection.php');
	$userID=$_POST['userID'];
	$updID=$_POST['updID'];
	$updated_by=$_POST['updated_by'];
	$comment=$_POST['comment'];
	$relation='updates';
	if($comment!=''){
		mysql_query("insert into comments(comment_content,relation,ID,commented_by) values('$comment','$relation','$updID','$userID') ");
		
	}
	//reload the comment history and sending it via ajax
	$rslt=mysql_query("select * from comments where ID='$updID' ");
	if($rslt === FALSE) { 
    die(mysql_error()); // TODO: better error handling
}
	while ($cmnt=mysql_fetch_array($rslt)) {
		$comment=$cmnt['comment_content']; //comment
		$commented_by=$cmnt['commented_by']; //userID of commenter
		$rslt2=mysql_query("select name,profilepic from user_info where userID='$commented_by' ");
		while($cmnt2=mysql_fetch_array($rslt2)){
			$commented_by_name=$cmnt2['name']; //who commented it
			$commenter_photo_directory='user'.$commented_by.'/'.$cmnt2['profilepic']; //his profilepic
			echo '<div class="alert alert-info" style="padding:0px;">';
				echo '<img src="'.$commenter_photo_directory.'" height="25" width="25" style="border-radius:5px;">'.'<a href="">'.$commented_by_name.'</a>';
				echo '<p style="color:black; overflow-x:hidden; word-wrap:break-word;">'.$comment.'</p>';				
			echo '</div>';
		}
	}

?>