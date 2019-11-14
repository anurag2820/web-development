<?php
	include('connection.php');
	$updID=$_GET['updID'];
	$comments=0;
	$comment_button_ID='count_comment'.$updID;
	$result=mysql_query("select count(*) from comments where ID='$updID' ");
	$row=mysql_fetch_array($result);
	$comments=$row[0];
	echo $comments;
?>