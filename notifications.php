<?php
	
	include('connection.php');
	$userID=$_GET['userID'];
	$not_table="notifications_".$userID;
	$result=mysql_query("select * from $not_table order by sno desc");
	if($result === FALSE) { 
    die(mysql_error()); // TODO: better error handling
}
	while($row=mysql_fetch_array($result)){
		$sno=$row['sno'];
		$notification=$row['notification'];
		$link='#'; //create a  link from notification_id

		echo '<div class="alert alert-info" style="padding:0px; margin:0px;">'.'<a href="'.$link.'" onclick="move2notification('.$sno.');" oncontextmenu="return false">'.'<h6 style="font-size:10px;" class="text-center">'.$notification.'</h6></a></div>';

	}

?>