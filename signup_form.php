<?php
	error_reporting(0);
	mysql_connect("localhost","root","root");
	mysql_select_db("social");
	$name=$_POST['name'];	
	$password=$_POST['password'];	
	$email=$_POST['email'];	
	$gender=$_POST['gender'];	
	$age=$_POST['age'];	
	$city=$_POST['city'];	
	$phone=$_POST['phone'];	
	//file
	$file=$_FILES['profilepic'];
	$file_name=$file['name'];
	if (!is_numeric($password[0]) && strlen($password)>=6 && filter_var($email,FILTER_VALIDATE_EMAIL) && is_numeric($age) && is_numeric($phone))
	 {
		mysql_query(" insert into user_info(name,password,email,gender,age,city,phone,profilepic) values('$name','$password','$email','$gender','$age','$city','$phone','$file_name') ");
	//store file to directory
		if($file['size']>0){

			$file_tmp_name=$file['tmp_name'];
			$result=mysql_query("select userID from user_info where name='$name' and password='$password' and phone='$phone' ");
			while($row=mysql_fetch_array($result)){
				$directory="user".$row[0];  //directory name will be = user1
				mkdir($directory);
				$store=$directory."/".$file_name;
			}
			move_uploaded_file($file_tmp_name,$store);
		}
		header('Location:login_page.php');	
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
	<script type="text/javascript">
		function check(ele_ID,div_ID){
			var xmlHttp,val,msg;
			val=document.getElementById(ele_ID).value;
			xmlHttp=new XMLHttpRequest();
			xmlHttp.onreadystatechange=function(){
				if(xmlHttp.readyState==4 && xmlHttp.status==200){
					//change class of div dynamically
					msg=xmlHttp.responseText;
					if(msg=='error'){
						document.getElementById(div_ID).className="form-group has-error";
					}
					else if(msg=='success'){
						document.getElementById(div_ID).className="form-group has-success";
					}
					else{
						document.getElementById(div_ID).className="form-group";	
					}
				}
			}
			xmlHttp.open("GET","signup.php?val="+val+"&ele_ID="+ele_ID,true);
			xmlHttp.send();
		}
	</script>
</head>
<body>

	<div class="container">
		<div class="col-xs-12">
			<h2 style="text-decoration:underline; text-align:center;">Submit Your Details</h2>
			<form action="" method="POST" enctype="multipart/form-data" class="form-horizontal bg-info" style="padding:30px 30px 20px 0px; border-radius:10px;">
				<div class="form-group" id="first">
					<label for="name" class="col-xs-2 control-label">Name</label>
					<div class="col-xs-10">
						<input type="text" name="name" placeholder="Type your Full Name" id="name" value="<?php echo $name; ?>" class="form-control" oninput="check('name','first');" required>
					</div>
				</div>
				<div class="form-group" id="second">
					<label for="password" class="col-xs-2 control-label">Password</label>
					<div class="col-xs-10">
						<input type="password" name="password" id="password" value="<?php echo $password; ?>" placeholder="atleast 6 characters and alphanumeric (eg. shu721)" class="form-control" onkeyup="check('password','second');" required>
					</div>
				</div>
				<div class="form-group" id="third">
					<label for="email" class="col-xs-2 control-label">Email</label>
					<div class="col-xs-10">
						<input type="text" name="email" placeholder="Type your email" id="email" value="<?php echo $email; ?>" class="form-control" oninput="check('email','third');" required>
					</div>
				</div>
				<div class="form-group" id="fourth">
					<label for="gender" class="col-xs-2 control-label">Gender</label>
					<div class="col-xs-10">
						<select class="form-control" name="gender" id="gender" onclick="check('gender','fourth');" onblur="check('gender','fourth');">
							<option value="male">Male</option>
							<option value="female">Female</option>
						</select>
					</div>
				</div>
				<div class="form-group" id="fifth">
					<label class="col-xs-2 control-label" for="age">Age</label>
					<div class="col-xs-10">
						<input type="text" name="age" placeholder="Enter your Age" id="age" value="<?php echo $age; ?>" class="form-control" oninput="check('age','fifth');" required>
					</div>
				</div>
				<div class="form-group" id="sixth">
					<label class="col-xs-2 control-label" for="city">City</label>
					<div class="col-xs-10">
						<input type="text" name="city" placeholder="Enter your City" id="city" value="<?php echo $city; ?>" class="form-control" oninput="check('city','sixth');" required>
					</div>
				</div>
				<div class="form-group" id="seventh">
					<label class="col-xs-2 control-label" for="phone">Phone no.</label>
					<div class="col-xs-10">
						<div class="input-group">
						<span class="input-group-addon" id="countryCode">+91</span>
						<input type="text" name="phone" placeholder="10 digit Phone Number" id="phone" value="<?php echo $phone; ?>" class="form-control" aria-describedby="countryCode" oninput="check('phone','seventh');" required>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-2 control-label">Upload profile pic</label>
					<div class="col-xs-10">
						<input type="file" name="profilepic" required>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-offset-2 col-xs-10">
						<input type="submit" name="submit" value="Submit" class="btn btn-default" oninvalid="alert('You must fill out the form!');">
						<input type="reset" name="reset" value="Reset" class="btn btn-default">
					</div>
				</div>

			</form>
		</div>
	</div>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>