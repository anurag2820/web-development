<?php
		error_reporting(0);
		session_start();
		if(isset($_SESSION['userID']))
		{
			header('Location:user.php');
		}
		include('connection.php');
		$c=0;
		$clicked_login=0;
		if(isset($_POST['login'])){
			$clicked_login=1;
		}
		$login_email=$_POST['login_email'];
		$login_password=$_POST['login_password'];
		$result=mysql_query("select userID,password from user_info where email='$login_email' ");
		while($row=mysql_fetch_array($result))
			{
				if($login_password==$row['password']){
					$c=$c+1;
					header('Location:user.php');
					session_start();
					$_SESSION['userID']=$row['userID'];
				}
			
			}
			if($c == 0 && $clicked_login==1) {
			   echo "<script>";
			   echo "alert('Invalid username or password');";
			   echo "</script>";
		} 
?>

<!DOCTYPE html>
<html>
<head>
	<title>chitchat</title>
	<link rel="shortcut icon" href="logo_2.ico" />
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">		

</head>
<body class="bg-info">

<div class="container">
	<div class="jumbotron" style="background-color:rgb(149,190,227);">
		<img src="logo.png" style="margin-left:50px;">
		 <h3 style="display:inline; margin-left:105px;"><span>WELCOME TO chitchat.com <small>stay connected with your friends</small> </span></h3>
		<h5 style="margin-left:50px;">chitchat.com</h5>

		
	</div>	

	<div class="row">
	<div class="col-xs-offset-3 col-xs-6" style="background-color:rgb(154,230,196); border-radius:5px; margin-top:15px; padding-top:20px; padding-bottom:11px;">

		<div class="row">	
			<div class="col-xs-offset-2 col-xs-7" style="padding-bottom:7px; ">
				<small>Login to your account</small>
			</div>
			<div class="col-xs-12">
				<form class="form-horizontal" action="" method="POST">
					<div class="form-group has-feedback"> <!--has feedback in input for adding glyphicon-->
						<label for="login_email" class="col-xs-2 control-label"></label>
						<div class="col-xs-7">
							<input type="text" class="form-control" placeholder="email" name="login_email" id="login_email">
							<i class="glyphicon glyphicon-user form-control-feedback"></i> <!-- for adding the glyphicon in input-->
						</div>
					</div>
					<div class="form-group has-feedback">
						<label for="login_password" class="col-xs-2 control-label"></label>
						<div class="col-xs-7">
							<input type="password" class="form-control" placeholder="password" name="login_password" id="login_password">
							<i class="glyphicon glyphicon-lock form-control-feedback"></i>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-offset-2 col-xs-7">
							<input type="submit" name="login" value="login" class="btn btn-primary">
						</div>
					</div>
					
				</form>
			</div>
		</div>
	

		<div class="row">
			<div class="col-xs-offset-2 col-xs-10">
				<small> or need an account ? then </small>
				<a data-toggle="modal" href="#mymodal">Sign Up</a> <small>here</small>
				<div class="modal fade" id="mymodal">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<button type="button" class="close" data-dismiss="modal">&times;</button> <!--close button-->
								<?php
									error_reporting(0);
									include('connection.php'); //connection made
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
										
									//store file to directory
										if($file['size']>0){

											mysql_query(" insert into user_info(name,password,email,gender,age,city,phone,profilepic) values('$name','$password','$email','$gender','$age','$city','$phone','$file_name') ");

											$file_tmp_name=$file['tmp_name'];
											$result=mysql_query("select userID from user_info where name='$name' and password='$password' and phone='$phone' ");
											$userID="";
											while($row=mysql_fetch_array($result)){
												$userID=$row[0];
												$directory="user".$row[0];  //directory name will be = user1
												mkdir($directory);
												$store=$directory."/".$file_name;
											}
											move_uploaded_file($file_tmp_name,$store);

											$fav_table='favourites_'.$userID;
											$notification_table='notifications_'.$userID;
											mysql_query("create table $fav_table(
												userID int
												)"  );

											mysql_query("create table $notification_table(
												sno int not null auto_increment,
												notification varchar(200),
												notification_type varchar(50),
												notification_id varchar(50),
												seen varchar(5),
												time int,
												primary key(sno)
												)"  );

											header('Location:login.php');	
										}
										
									}

								?>


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
										params="val="+val+"&ele_ID="+ele_ID;
										xmlHttp.open("POST","signup.php",true);
										
										xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
										xmlHttp.setRequestHeader("Content-length", params.length);
										xmlHttp.setRequestHeader("Connection", "close");
										
										xmlHttp.send(params);
									}
								</script>

								<div class="container">
									<div class="col-xs-6" style="padding-right:30px; padding-left:0px; padding-bottom:20px;">
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
													<input type="submit" name="submit" value="Submit" class="btn btn-default">
													<input type="reset" name="reset" value="Reset" class="btn btn-default">
												</div>
											</div>

										</form>
									</div>
								</div>

								<script type="text/javascript" src="js/jquery.min.js"></script>
								<script type="text/javascript" src="js/bootstrap.min.js"></script>
								</div>
								
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
		

	<div class="container">

		<div class="row" style="margin-top:40px;">
			<div class="col-xs-offset-9 col-xs-3">
				<h4><small><span style="text-decoration:underline;">Contact us - </span></h4>
				<h4><small>artistanurag09@gmail.com</small></h4>
				<h4 style="display:inline;"><small>Linked In profile </small></h4>
				<a href="https://www.linkedin.com/in/akshay-srivastava-6b0995152/"><img src="akshay-srivastava-6b0995152-.png" style="display:inline;"></a>
			</div>

		</div>
	</div>


		<script src="js/jquery.min.js"></script>
			<!-- Latest compiled and minified JavaScript -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>