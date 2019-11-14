<?php
	include('connection.php');
	$val=$_POST['val'];
	$userID=$_POST['userID'];
	if($val!=''){
		mysql_query("insert into updates(update_content,updated_by) values('$val','$userID') ");	
	}
	

?>