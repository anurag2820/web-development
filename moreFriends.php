<?php
	session_start(); //always start the session before using it's variables
	if(isset($_SESSION['userID'])){
		$userID=$_SESSION['userID'];
	}
	else{
		die("you're not logged in");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>chitchat</title>
	<link rel="shortcut icon" href="logo_2.ico">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
</head>
<body>
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

	<nav class="navbar navbar-inverse">
		<div class="container">
			<div class="navbar-header">
 				<a class="navbar-brand" href="user.php" oncontextmenu="return false"><img src="logo_2.ico" style="display:inline;"> chitchat</a>
 			</div>
			<ul class="nav navbar-nav navbar-left">
				<li><a href="user.php" oncontextmenu="return false"><span class="glyphicon glyphicon-home"></span> Home</a></li>
				<li><a href="profile.php" oncontextmenu="return false"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
				<li><a href="messages.php" oncontextmenu="return false"><span class="glyphicon glyphicon-envelope"></span> Messages</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li onclick="destroy_session();"><a href="#" oncontextmenu="return false"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
				<li><a href="settings.php" oncontextmenu="return false"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
			</ul>
		</div>
	</nav>

	<div class="container">
		<div class="row">

			<div class="col-xs-7">
				<div class="row">

					<div class="col-xs-5">
						<form>
							<div class="form-group">
								<label for="searchbox"></label>
								<div class=" has-feedback">
									<input type="text" id="searchbox" class="form-control" placeholder="search for a friend" onkeyup="searchFriend();" >
									<i class="glyphicon glyphicon-search form-control-feedback" style="color:gray;"></i>
								</div>
							</div>						
						</form>
					</div>
					<div class="col-xs-7">
						<div class="alert alert-info text-center">
							<p style="display:inline;">People on chitchat</p><h5 style="display:inline;"><small> add them to your Friend List</small></h5> <span class="glyphicon glyphicon-repeat" style="margin-left:20px; cursor:pointer;" onclick="refreshPage();"></span>
						</div>
					</div>
					
					<div class="col-xs-12">
						<div class="panel panel-default" id="suggestedFriends" style="height:450px; overflow-y:scroll;">
							<?php
								include('connection.php');
								$fav_relation='favourites_'.$userID;
								$result=mysql_query("select * from user_info info where userID not in(select userID from $fav_relation )");
								while($row=mysql_fetch_array($result)){
									$frndID=$row['userID'];
									if($frndID==$userID)
										continue;
									$name=$row['name'];
									$photo_directory='user'.$frndID.'/'.$row['profilepic'];
									$city=$row['city'];
								?>
									<div>
										<div class="col-xs-8" style="margin-top:8px;">
											<img src="<?php echo $photo_directory; ?>" height="50" width="50" style="border-radius:5px;"> <a href="#" onclick="openProfile(<?php echo $frndID; ?>,<?php echo $userID; ?>);" oncontextmenu="return false"><?php echo $name;?></a> <h5 style="display:inline;"><small>from <?php echo $city;?></small></h5>
										</div>
										<div class="col-xs-4" style="margin-top:8px;">
											<div id="<?php echo 'add'.$frndID; ?>" onclick="addFriend(<?php echo $frndID; ?>,<?php echo $userID; ?>);" class="label label-success" style=" cursor:pointer; position:relative; top:17px;"> <span class="glyphicon glyphicon-plus"></span> Add to your Friend List</div>
										</div>
									</div>

								<?php
								}
							?>
						</div>
					</div>
					
				</div>
			</div>

				<script>
					function addFriend(frndID,userID){
						var xmlHttp,rsp;
						var ID='add'+frndID;
						xmlHttp=new XMLHttpRequest();
						xmlHttp.onreadystatechange=function(){
							if(xmlHttp.readyState==4 && xmlHttp.status==200){
								rsp=xmlHttp.responseText;
								if(rsp=='success'){
									document.getElementById(ID).innerHTML='added to your Friend List';
									document.getElementById(ID).className='label label-primary';
								}
							}
						}
						xmlHttp.open("GET","moreFriendsAjax.php?frndID="+frndID+"&userID="+userID,true);
						xmlHttp.send();
					}
					function removeFriend(frndID,userID){
						var xmlHttp,rsp;
						var ID='remove'+frndID;
						xmlHttp=new XMLHttpRequest();
						xmlHttp.onreadystatechange=function(){
							if(xmlHttp.readyState==4 && xmlHttp.status==200){
								rsp=xmlHttp.responseText;
								if(rsp=='success'){
									document.getElementById(ID).innerHTML='Removed from List';
									document.getElementById(ID).className='label label-danger';
								}
							}
						}
						xmlHttp.open("GET","moreFriendsAjax2.php?frndID="+frndID+"&userID="+userID,true);
						xmlHttp.send();
					}
					function refreshPage(){
						window.location.href='moreFriends.php';
					}
					function searchFriend(){
						var xmlHttp,rsp;
						val=document.getElementById('searchbox').value;
						xmlHttp=new XMLHttpRequest();
						xmlHttp.onreadystatechange=function(){
							if(xmlHttp.readyState==4 && xmlHttp.status==200){
								rsp=xmlHttp.responseText;
								document.getElementById('suggestedFriends').innerHTML=rsp;
							}
						}
						xmlHttp.open("GET","suggestedFriendsAjax.php?val="+val,true);
						xmlHttp.send();
						
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

				</script>



			<div class="col-xs-5">
				<div class="row">
					<div class="col-xs-12">
						<div class="alert alert-info text-center">
							<p style="display:inline;">Your Friend List</p><h5 style="display:inline;"><small> edit your Friend List</small></h5> <span class="glyphicon glyphicon-repeat" style="margin-left:50px; cursor:pointer;" onclick="refreshPage();"></span>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="panel panel-default" id="FriendList" style="height:450px; overflow-y:scroll;">
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
										<div class="col-xs-7" style="margin-top:8px; padding-right:0px;">
											<img src="<?php echo $photo_directory; ?>" height="50" width="50" style="border-radius:5px;"/> <a href="#" onclick="openProfile(<?php echo $frndID; ?>,<?php echo $userID; ?>);" oncontextmenu="return false"><?php echo $name;?></a>
										</div>
										<div class="col-xs-3" style="margin-top:7px; padding:0px;">
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

		</div>
	</div>



	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
</body>
</html>