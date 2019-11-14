<?php
	include('connection.php');
	session_start();
	if(isset($_SESSION['userID'])){
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
 			<!--navbar-header-->
 			<div class="navbar-header">
 				<a class="navbar-brand" href="user.php" oncontextmenu="return false"><img src="logo_2.ico" style="display:inline;"> chitchat</a>
 			</div>
 			<!--ul-->
 			<ul class="nav navbar-nav">
 				<li><a href="user.php" oncontextmenu="return false"><span class="glyphicon glyphicon-home"></span> Home</a></li>
 				<li><a href="profile.php" oncontextmenu="return false"><span class="glyphicon glyphicon-user"></span>  Profile</a></li>
 				<li class="active" ><a href="messages.php" oncontextmenu="return false"><span class="glyphicon glyphicon-envelope"></span>  Messages</a></li>
 			</ul>
 			
 			<!--navbar-right-->
 			<ul class="nav navbar-nav navbar-right">
 				<li onclick="destroy_session();"><a href="#" oncontextmenu="return false"><span class="glyphicon glyphicon-off"></span>  Logout</a></li>
 				<li><a href="settings.php" oncontextmenu="return false"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
 			</ul>
 		</div> 		
 	</nav>

 	<div class="container">
 		<div class="row">
 			<div class="col-xs-4"> 
 				<div class="row">
 					<div class="col-xs-12">
 						<textarea rows="1" class="col-xs-12" onkeyup="searchFriend();" id="searchbox" placeholder="search a Friend" style="padding-top:6px; padding-bottom:6px;"></textarea>
 					</div>
 				</div>
 					<script>
 						function searchFriend(){
							var xmlHttp,rsp;
							val=document.getElementById('searchbox').value;
							xmlHttp=new XMLHttpRequest();
							xmlHttp.onreadystatechange=function(){
								if(xmlHttp.readyState==4 && xmlHttp.status==200){
									rsp=xmlHttp.responseText;
									document.getElementById('FriendList').innerHTML=rsp;
								}
							}
							xmlHttp.open("GET","message_searchFriend.php?val="+val,true);
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


				<div class="col-xs-12">
					<div class="row">
						<div class="panel panel-default" id="FriendList" style="height:480px; overflow-y:scroll;">
							<?php
								include('connection.php');
								$fav_relation='favourites_'.$userID;
								$result=mysql_query("select * from user_info info where userID in(select userID from $fav_relation )");
								while($row=mysql_fetch_array($result)){
									$frndID=$row['userID'];
									$name=$row['name'];
									$photo_directory='user'.$frndID.'/'.$row['profilepic'];
									$city=$row['city'];
									$badgeID='badge'.$frndID;
								?>
									<div>
										<div class="col-xs-10" style="margin-top:8px; padding-right:0px;">
											<img src="<?php echo $photo_directory; ?>" height="50" width="50" style="border-radius:5px;"/> <a href="#" onclick="openProfile(<?php echo $frndID; ?>,<?php echo $userID; ?>);" oncontextmenu="return false"><?php echo $name;?></a>
											<span class="badge" id="<?php echo $badgeID;?>" style="background-color:rgb(249,53,53);"><?php $result2=mysql_query("select count(*) from messages where reciever='$userID' and seen='$frndID' and seen is NULL ");
											if($result2 === FALSE) { 
    die(mysql_error()); // TODO: better error handling
}
																										 $row2=mysql_fetch_array($result2);
																										 if($row2[0]!=0)
																										 echo $row2[0]; ?></span>
										</div>
										<div class="col-xs-2" style="margin-top:14px; padding:0px;">
											<button class="btn btn-default" onclick="chat(<?php echo $userID; ?>,<?php echo $frndID; ?>,'<?php echo $photo_directory;?>','<?php echo $name;?>' );">chat</button>
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
					function chat(userID,frndID,photo_directory,name) {
						document.getElementById('conv').innerHTML='<h5 style="text-align:center;"><strong>CONVERSATION</strong></h5><span id="repeat"></span><img src="'+photo_directory+'" height="40" width="40"/>&nbsp;&nbsp;<a href="#">'+name+'</a>';
						var xmlHttp,msg;
						msg='';
						xmlHttp=new XMLHttpRequest();
						xmlHttp.onreadystatechange=function(){
							if(xmlHttp.readyState==4 && xmlHttp.status==200){
								rsp=xmlHttp.responseText;
								document.getElementById('chatHistory').innerHTML=rsp;
								setInterval(function(){document.getElementById('repeat').click(); },1000);
								setmsg_read(userID,frndID); //make all messages read onclick of chat button or send button or textarea
								document.getElementById('textbox').setAttribute("onclick","setmsg_read("+userID+","+frndID+")");
							}
						}
						xmlHttp.open("GET","message_chat.php?msg="+msg+"&userID="+userID+"&frndID="+frndID,true);
						xmlHttp.send();
						document.getElementById('sendButton').setAttribute("onclick","sendMessage("+frndID+","+userID+")");
						document.getElementById('repeat').setAttribute("onclick","repeat("+frndID+","+userID+")");
					}
					function sendMessage(frndID,userID){
						var xmlHttp,msg;
						msg=document.getElementById('textbox').value;
						xmlHttp=new XMLHttpRequest();
						xmlHttp.onreadystatechange=function(){
							if(xmlHttp.readyState==4 && xmlHttp.status==200){
								rsp=xmlHttp.responseText;
								document.getElementById('chatHistory').innerHTML=rsp;
								setmsg_read(userID,frndID);
							}
						}
						xmlHttp.open("GET","message_chat.php?msg="+msg+"&userID="+userID+"&frndID="+frndID,true);
						xmlHttp.send();
						document.getElementById('textbox').value='';						
					}
					function repeat(frndID,userID){
						var xmlHttp,msg;
						msg='';
						xmlHttp=new XMLHttpRequest();
						xmlHttp.onreadystatechange=function(){
							if(xmlHttp.readyState==4 && xmlHttp.status==200){
								rsp=xmlHttp.responseText;
								document.getElementById('chatHistory').innerHTML=rsp;

							}
						}
						xmlHttp.open("GET","message_chat.php?msg="+msg+"&userID="+userID+"&frndID="+frndID,true);
						xmlHttp.send();
					}
					function setmsg_read(userID,frndID){
						var xmlHttp;						
						xmlHttp=new XMLHttpRequest();
						xmlHttp.onreadystatechange=function(){
							if(xmlHttp.readyState==4 && xmlHttp.status==200){													
								var badgeID='badge'+frndID;
								document.getElementById(badgeID).innerHTML='';
							}
						}
						xmlHttp.open("GET","setmsg_read.php?userID="+userID+"&frndID="+frndID,true);
						xmlHttp.send();
					}
				</script>


			<div class="col-xs-8">
				<div class="row">
					<div class="alert alert-info" style="padding:6px; margin:0px;" id="conv">
						<h5 style="text-align:center;"><strong>CONVERSATION</strong></h5><span id="repeat"></span>						
					</div>
				</div>
				<div class="row">
					<div class="panel panel-default" id="chatHistory" style="height:381px; margin-bottom:2px; overflow-y:scroll;" >
						
					</div>
				</div>

				<div class="row">
					<textarea rows="2" class="col-xs-11" id="textbox" placeholder="Type your message here"></textarea>
					<div class="col-xs-1" style="display:inline; padding-left:5px;">
						<button class="btn btn-default" id="sendButton">send</button>
					</div>				
				</div>
			</div>


 		</div>
 	</div>

 	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>

</body>
</html>