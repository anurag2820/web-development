<?php
	error_reporting(0);
	$val=$_POST['val'];
	$ele_ID=$_POST['ele_ID'];
	if ($ele_ID=='name') {
		if(strlen($val)==0)
		{
			echo 'error';
		}
		else{
			echo 'success';
		}
	}
	if ($ele_ID=='password') {
		if(strlen($val)<6)
		{
			echo 'error';
		}
		else if(is_numeric($val[0]))
		{
			echo 'error';
		}
		else{
			echo 'success';
		}
	}
	if ($ele_ID=='email') {
		if(filter_var($val,FILTER_VALIDATE_EMAIL))
		{
			echo 'success';
		}
		else{
			echo 'error';
		}
	}
	if ($ele_ID=='gender') {
		echo 'success';
	}

	if ($ele_ID=='age') {
		$age_length = strlen((string)$val);
		if(!is_numeric($val))
		{
			echo 'error';
		}
		else if($age_length>3)
		{
			echo 'error';
		}
		else{
			echo 'success';
		}
	}
	if ($ele_ID=='city') {
		if(strlen($val)==0)
		{
			echo 'error';
		}
		else{
			echo 'success';
		}
	}
	if ($ele_ID=='phone') {
		$num_length = strlen((string)$val);
		if(!is_numeric($val))
		{
			echo 'error';
		}
		else if($num_length<10)
		{
			echo 'error';
		}
		else{
			echo 'success';
		}
	}


	
	
?>