<?php
	include('connection.php');
	session_start();
	if(isset($_SESSION['userID']))
	{
		$userID=$_SESSION['userID'];
	}
	else{
		die("You're not logged in"); 
	}
?>
<!DOCTYPE html>
<html>
 <head>
 	<title>chitchat</title>
 	<link rel="shortcut icon" href="logo_2.ico"/> <!--for adding a icon in title bar-->

 	<!-- Latest compiled and minified CSS -->
 	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	<script>
		
		function destroy_session(){
			var xmlHttp;
			xmlHttp=new XMLHttpRequest();
			xmlHttp.onreadystatechange=function(){
				if(xmlHttp.readyState==4 && xmlHttp.status==200){
					window.location.href='login.php';
				}
			}
			xmlHttp.open("GET","destroy.php?",true);
			xmlHttp.send();
		}
	</script>

 </head>
 <body>
 	<!--navbar-->
 	<nav class="navbar navbar-inverse">
 		<div class="container">
 			<!--navbar-header-->
 			<div class="navbar-header">
 				<a class="navbar-brand" href="user.php"  oncontextmenu="return false"><img src="logo_2.ico" style="display:inline;"> chitchat</a>
 			</div>
 			<!--ul-->
 			<ul class="nav navbar-nav">
 				<li><a href="user.php"  oncontextmenu="return false"><span class="glyphicon glyphicon-home"></span> Home</a></li>
 				<li><a href="profile.php" oncontextmenu="return false"><span class="glyphicon glyphicon-user"></span>  Profile</a></li>
 				<li><a href="messages.php" oncontextmenu="return false"><span class="glyphicon glyphicon-envelope"></span>  Messages</a></li>
 			</ul>
 			
 			<!--navbar-right-->
 			<ul class="nav navbar-nav navbar-right">
 				<li onclick="destroy_session();"><a href="#" oncontextmenu="return false"><span class="glyphicon glyphicon-off"></span>  Logout</a></li>
 				<li class="active"><a href="settings.php" oncontextmenu="return false"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
 			</ul>
 		</div> 		
 	</nav>

 		<script>
 			function updateProfile(userID){
 				//get all the values
				name=document.getElementById('name').value;
				email=document.getElementById('email').value;
				password=document.getElementById('password').value;
				age=document.getElementById('age').value;
				city=document.getElementById('city').value;
				phone=document.getElementById('phone').value;
				var xmlHttp; 									
				xmlHttp=new XMLHttpRequest();
				xmlHttp.onreadystatechange=function(){
					if(xmlHttp.readyState==4 && xmlHttp.status==200){
						alert("successfully updated !");
					}
				}
				xmlHttp.open("GET","editProfile.php?userID="+userID+"&name="+name+"&email="+email+"&password="+password+"&age="+age+"&city="+city+"&phone="+phone,true);
				xmlHttp.send();
 			}
 			function changepic(photo_directory){
 				document.getElementById('ppc').setAttribute("src",photo_directory);
 			}
 			function openProfile(frndID,userID){
				if(frndID==userID){
					window.location.href="profile.php";
					return;
				}
				var xmlHttp; 									
				xmlHttp=new XMLHttpRequest();
				xmlHttp.onreadystatechange=function(){
					if(xmlHttp.readyState==4 && xmlHttp.status==200){
						window.location.href='profileFrnd.php';
					}
				}
				xmlHttp.open("GET","setFrnd_SESSION.php?frndID="+frndID,true);
				xmlHttp.send();
			}
			function removeFriend(frndID,userID){
				var xmlHttp; 									
				xmlHttp=new XMLHttpRequest();
				xmlHttp.onreadystatechange=function(){
					if(xmlHttp.readyState==4 && xmlHttp.status==200){
						id='remove'+frndID;
						document.getElementById(id).className="label label-danger";
						document.getElementById(id).innerHTML="Removed from your List";
					}
				}
				xmlHttp.open("GET","removeFriend.php?frndID="+frndID+"&userID="+userID,true);
				xmlHttp.send();
			}
			function deletePost(updID,userID){
				var xmlHttp; 									
				xmlHttp=new XMLHttpRequest();
				xmlHttp.onreadystatechange=function(){
					if(xmlHttp.readyState==4 && xmlHttp.status==200){
						rsp=xmlHttp.responseText;
						document.getElementById('managePosts').innerHTML=rsp;
						alert('post deleted');	
					}
				}
				xmlHttp.open("GET","deletePost.php?updID="+updID+"&userID="+userID,true);
				xmlHttp.send();
			}
 		</script>

 	<div class="container">
 		<div class="row">
 			<div class="col-xs-offset-1 col-xs-8">
 				<!-- nav-tabs -->
 				<ul class="nav nav-tabs">
 					<li class="active"><a href="#editProfile" data-toggle="tab">Edit Profile</a></li>
 					<li><a href="#manageFriends" data-toggle="tab">Manage Friend List</a></li>
 					<li><a href="#managePosts" data-toggle="tab">Manage Posts</a></li>
 				</ul>
 				<!-- tab-content -->
 				<div class="tab-content">
 					<!-- edit profile tab-pane -->
 					<div class="tab-pane fade in active" id="editProfile">
 						<div class="row">
 							
 							<?php
 								$result=mysql_query("select * from user_info where userID='$userID' ");
 								$row=mysql_fetch_array($result);
 									$name=$row['name'];
 									$password=$row['password'];
 									$email=$row['email'];
 									$gender=$row['gender'];
 									$age=$row['age'];
 									$city=$row['city'];
 									$phone=$row['phone'];
 									$profilepic=$row['profilepic'];
 									$photo_directory='user'.$userID.'/'.$profilepic;
 							?>

 							<div class="col-xs-9">
 								<form class="form-horizontal" style="padding-top:20px;">
 									<div class="form-group">
 										<label class="col-xs-2 text-right" for="name"><h5><strong>Name</strong></h5></label>
 										<div class="col-xs-10">
 											<input type="text" class="form-control" value="<?php echo $name; ?>" id="name">
 										</div> 										
 									</div>
 									<div class="form-group">
 										<label class="col-xs-2 text-right" for="email"><h5><strong>Email</strong></h5></label>
 										<div class="col-xs-10">
 											<input type="text" class="form-control" value="<?php echo $email; ?>" id="email">
 										</div> 										
 									</div>
 									<div class="form-group">
 										<label class="col-xs-2 text-right" for="password"><h5><strong>Password</strong></h5></label>
 										<div class="col-xs-10">
 											<input type="text" class="form-control" value="<?php echo $password; ?>" id="password">
 										</div> 										
 									</div>
 									<div class="form-group">
 										<label class="col-xs-2 text-right" for="gender"><h5><strong>Gender</strong></h5></label>
 										<div class="col-xs-10">
 											<input type="text" class="form-control" value="<?php echo $gender; ?>" id="gender" disabled>
 										</div> 										
 									</div>
 									<div class="form-group">
 										<label class="col-xs-2 text-right" for="age"><h5><strong>Age</strong></h5></label>
 										<div class="col-xs-10">
 											<input type="text" class="form-control" value="<?php echo $age; ?>" id="age">
 										</div> 										
 									</div>
 									<div class="form-group">
 										<label class="col-xs-2 text-right" for="city"><h5><strong>City</strong></h5></label>
 										<div class="col-xs-10">
 											<input type="text" class="form-control" value="<?php echo $city; ?>" id="city">
 										</div> 										
 									</div>
 									<div class="form-group">
 										<label class="col-xs-2 text-right" for="phone"><h5><strong>Phone</strong></h5></label>
 										<div class="col-xs-10">
 											<input type="text" class="form-control" value="<?php echo $phone; ?>" id="phone">
 										</div> 										
 									</div>
 									<div class="col-xs-offset-2">
 										<span class="btn btn-primary btn-lg" onclick="updateProfile(<?php echo $userID;?>);">Update Profile</span>
 									</div>
 								</form>
 							</div>
 							<div class="col-xs-3">
 								<img src="<?php echo $photo_directory;?>" height="190" width="190" id="ppc">
 								<span class="glyphicon glyphicon-edit"></span> <h5 style="display:inline;"><small>change profile picture</small></h5>
 								<form action="" method="POST" enctype="multipart/form-data">
 									<input type="file" name="img" id="new_profilepic">
 									<input class="btn btn-default" type="submit" name="upload" value="upload">
 								</form>
 							</div>
 						</div>
 					</div>
 								<?php
 									if(isset($_POST['upload']) && $_FILES['img']['size']>0){
 										unlink($photo_directory);
 										$tmp_name=$_FILES['img']['tmp_name'];
 										$name=$_FILES['img']['name'];
 										$photo_directory="user".$userID."/".$name;
 										move_uploaded_file($tmp_name,$photo_directory);
 										mysql_query("update user_info set profilepic='$name' where userID='$userID' ");
 										echo '<script>';
 											echo 'changepic("'.$photo_directory.'");';
 										echo '</script>';
 									}
 								?>

 					<!-- manage Friends tab-pane -->
 					<div class="tab-pane fade in" id="manageFriends">
 						<div class="row">
							<div class="col-xs-12">
								<div class="alert alert-info text-center">
									<p style="display:inline;">Your Friend List</p><h5 style="display:inline;"><small> edit your Friend List</small></h5>
								</div>
							</div>
							<div class="col-xs-12">
								<div class="panel panel-default" id="FriendList" style="height:400px; overflow-y:scroll;">
									<?php
										include('connection.php');
										$fav_relation='favourites_'.$userID;
										$result=mysql_query("select * from user_info info where userID in(select userID from $fav_relation )");
										while($row=mysql_fetch_array($result)){
											$frndID=$row['userID'];
											$name=$row['name'];
											$photo_directory='user'.$frndID.'/'.$row['profilepic'];
											$city=$row['city'];
										?>
											<div>
												<div class="col-xs-8" style="margin-top:8px; padding-right:0px;">
													<img src="<?php echo $photo_directory; ?>" height="50" width="50" style="border-radius:5px;"/> <a href="#" onclick="openProfile(<?php echo $frndID; ?>,<?php echo $userID; ?>);" oncontextmenu="return false"><?php echo $name;?></a>
												</div>
												<div class="col-xs-2" style="margin-top:7px; padding:0px;">
													<div id="<?php echo 'remove'.$frndID; ?>" onclick="removeFriend(<?php echo $frndID; ?>,<?php echo $userID; ?>);" class="label label-info" style=" cursor:pointer; position:relative; top:17px;">Remove from Friend List</div>
												</div>
												
											</div>

										<?php
										}
									?>
								</div>
							</div>
						</div>
 					</div>

 					<!-- manage posts tab-pane -->
 					<div class="tab-pane fade in" id="managePosts">
 						<?php
							include('connection.php');
							$fav_user='favourites_'.$userID;
							//selecting updates only from those who are your frnd including u yourself
							$table_result=mysql_query("select * from updates where updated_by='$userID' order by updID desc ");
							while($upd=mysql_fetch_array($table_result)){
								$updID=$upd['updID'];
								$updated_by=$upd['updated_by'];
								$update_content=$upd['update_content'];
									$result=mysql_query("select name,profilepic from user_info where userID='$updated_by' ");
									$row=mysql_fetch_array($result);
									$updated_by_name=$row['name'];
									$photo_directory='user'.$updated_by.'/'.$row['profilepic'];									
			 					?>

					 			<div class="col-xs-12" >
					 				<div class="row">
					 					<div class="col-xs-6">
					 						&nbsp;<img src="<?php echo $photo_directory;?>" height="45" width="45" style="border-radius:5px;"> <a href="profile.php"><?php echo $updated_by_name; ?></a> <h5 style="display:inline;"><small> posted an update</small></h5>
					 					</div>
					 					<div class="col-xs-offset-4 col-xs-2" style="position:relative; top:12px;">
					 						&times; <a href="#" onclick="deletePost(<?php echo $updID; ?>,<?php echo $userID; ?>);">delete post</a>
					 					</div>
					 				
					 					<div class="col-xs-12 jumbotron" style="margin:0px; background-color:rgb(245,245,245);">
					 						<p class="text-justify" style="font-size:13px;"><?php echo $update_content; ?></p>
					 					</div>
						 			</div>
						 		</div>

								<?php
								}
						 ?>
 					</div>
 				</div>
 							
 			</div>
 		</div>
 	</div>


 	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
 </body>
</html>
