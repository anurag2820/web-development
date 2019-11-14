<!DOCTYPE html>
<html>
<head>
	<title>chitchat</title>
	<link rel="shortcut icon" href="logo_2.ico">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
</head>
<body class="bg-info">

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
 				<li><a href="messages.php" oncontextmenu="return false"><span class="glyphicon glyphicon-envelope"></span>  Messages</a></li>
 			</ul>
 			
 			<!--navbar-right-->
 			<ul class="nav navbar-nav navbar-right">
 				<li onclick="destroy_session();"><a href="#" oncontextmenu="return false"><span class="glyphicon glyphicon-off"></span>  Logout</a></li>
 				<li><a href="settings.php" oncontextmenu="return false"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
 			</ul>
 		</div> 		
 	</nav>

 	<div class="container">
 		<div class="col-xs-offset-1 col-xs-10">
 		<?php
 			include('connection.php');
			session_start();
			$userID=$_SESSION['userID'];
			$sno=$_SESSION['sno'];
			$not_table='notifications_'.$userID;
			$result=mysql_query("select notification_type,notification_id from $not_table where sno='$sno' ");
			$row=mysql_fetch_array($result);//first result
				$notification_type=$row['notification_type'];
				$notification_id=$row['notification_id'];
				$result2=mysql_query("select * from updates where updID='$notification_id' ");//second result
				$row2=mysql_fetch_array($result2);
					$updID=$notification_id;
					$update_content=$row2['update_content'];
					$updated_by=$row2['updated_by'];
						$result3=mysql_query("select name,profilepic from user_info where userID='$updated_by' ");//third result
 						$row3=mysql_fetch_array($result3);
 							$updated_by_name=$row3['name'];
 							$photo_directory='user'.$updated_by.'/'.$row3['profilepic'];
 		?>
 							
 							<img src="<?php echo $photo_directory;?>" height="45" width="45" style="border-radius:5px;"> <a href="#" onclick="openProfile(<?php echo $updated_by; ?>,<?php echo $userID; ?>);" oncontextmenu="return false"><?php echo $updated_by_name; ?></a> <h5 style="display:inline;"><small> posted an update</small></h5>
 							<div class="jumbotron" style="margin-top:5px; background-color:rgb(245,245,245);">
 								<?php echo $update_content; ?>
 							</div>

 							<div id="allcomments">
 							<?php
								$rslt=mysql_query("select * from comments where ID='$updID' ");
								while ($cmnt=mysql_fetch_array($rslt)) {
									$comment=$cmnt['comment_content']; //comment
									$commented_by=$cmnt['commented_by']; //userID of commenter
									$rslt2=mysql_query("select name,profilepic from user_info where userID='$commented_by' ");
									while($cmnt2=mysql_fetch_array($rslt2)){
									$commented_by_name=$cmnt2['name']; //who commented it
									$commenter_photo_directory='user'.$commented_by.'/'.$cmnt2['profilepic']; //his profilepic
								?>
									
									<div class="alert alert-info" style="padding:0px;">
										<img src="<?php echo $commenter_photo_directory; ?>" height="25" width="25" style="border-radius:5px;"> <a href="#" onclick="openProfile(<?php echo $commented_by; ?>,<?php echo $userID; ?>);" oncontextmenu="return false"><?php echo $commented_by_name; ?></a>						 															
										<p style="color:black; overflow-x:hidden; word-wrap:break-word;"><?php echo $comment; ?></p>						 															
									</div>
									
								<?php
									}
								}
							?>
							</div>

							<textarea id="commentbox" rows="1" class="col-xs-12" placeholder="write your comment"></textarea><button class="btn btn-default" onclick="comment(<?php echo $userID; ?>,<?php echo $updID; ?>,<?php echo $updated_by; ?>);">comment</button>
 		</div>					
 	</div>
 							<script>
 								function comment(userID,updID,updated_by){
 									var xmlHttp,comment;
	 									comment=document.getElementById('commentbox').value; //comment to be posted
	 									xmlHttp=new XMLHttpRequest();
	 									xmlHttp.onreadystatechange=function(){
	 										if(xmlHttp.readyState==4 && xmlHttp.status==200){
	 											rsp=xmlHttp.responseText;
	 											document.getElementById('allcomments').innerHTML=rsp;
	 											if(comment!=''){
	 												alert('comment posted');	
	 											}	 											
	 										}
	 									}
	 									var params="userID="+userID+"&updID="+updID+"&updated_by="+updated_by+"&comment="+comment;
	 									xmlHttp.open("POST","comment_view.php",true);
	 									xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
										xmlHttp.setRequestHeader("Content-length", params.length);
										xmlHttp.setRequestHeader("Connection", "close");
	 									xmlHttp.send(params);
 									/*photo_directory='user1/shubham.jpg';
 									commented_by_name='shubham srivastava';
 									document.getElementById(bodyID).innerHTML='<div class="alert alert-info" style="padding:0px;">'+'<img src="'+photo_directory+'" height="25" width="25" style="border-radius:5px;">'+'<a href="">'+commented_by_name+'</a>'+'<p style="color:black;">'+comment+'</p>'+'</div>';*/
 									document.getElementById('commentbox').value='';
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

 	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
</body>
</html>