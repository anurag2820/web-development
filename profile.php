<?php
	include('connection.php');
	session_start();
	if(isset($_SESSION['userID'])){
		$userID=$_SESSION['userID'];
	}
	else{
		die("You're not logged in");
	}
	$frndID=$userID;	

	function checkFriend($userID,$frndID){
		$fav_user='favourites_'.$userID;
		$result=mysql_query("select * from $fav_user where userID='$frndID' ");
		while($row=mysql_fetch_array($result)){
			 return '<span class="glyphicon glyphicon-ok"></span> Friend';
		}
		 return '<span class="glyphicon glyphicon-plus"></span> Add as friend';

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
 				<li class="active" oncontextmenu="return false" ><a href="profile.php"><span class="glyphicon glyphicon-user"></span>  Profile</a></li>
 				<li><a href="messages.php" oncontextmenu="return false"><span class="glyphicon glyphicon-envelope"></span>  Messages</a></li>
 			</ul>
 			
 			<!--navbar-right-->
 			<ul class="nav navbar-nav navbar-right">
 				<li onclick="destroy_session();"><a href="#" oncontextmenu="return false"><span class="glyphicon glyphicon-off"></span>  Logout</a></li>
 				<li><a href="settings.php" oncontextmenu="return false"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
 			</ul>
 		</div> 		
 	</nav>

 		<script>
 			function addFriend(frndID){
 				val=document.getElementById('addAsFriend').innerHTML;
 				if(val=='<span class="glyphicon glyphicon-ok"></span> Friend'){
 					return;
 				}
 				var xmlHttp,rsp;
				xmlHttp=new XMLHttpRequest();
				xmlHttp.onreadystatechange=function(){
					if(xmlHttp.readyState==4 && xmlHttp.status==200){
						rsp=xmlHttp.responseText;
						if(rsp=='success'){
							document.getElementById('addAsFriend').innerHTML='<span class="glyphicon glyphicon-ok"></span> Friend';
							
						}
																		 											
					}
				}
				xmlHttp.open("GET","addAsFriend.php?frndID="+frndID,true);
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

 	<div class="container">
 		<div class="row">
 				<?php
 					$result=mysql_query("select * from user_info where userID='$frndID' ");
 					$row=mysql_fetch_array($result);
 					$profilepic=$row['profilepic'];
 					$directory="user".$frndID."/".$profilepic;
 					$name=$row['name'];
 					$city=$row['city'];
 					$email=$row['email'];
 					$age=$row['age'];
 					$gender=$row['gender'];
 					$phone=$row['phone'];
 				?>
 			<div class="col-xs-4">
 					<div class="row">
 						<div class="col-xs-6">
 							<img src="<?php echo $directory; ?>" height="170" width="170" />
 						</div>
 						<div class="col-xs-6">
 							<a href="#"><?php echo $name; ?></a>
 							<h5 style="margin:0px; padding:0px; margin-bottom:5px;"><small>Lives in <?php echo $city;?></small></h5>
 							<div>
 								<span class="glyphicon glyphicon-edit"></span> <a href="settings.php">edit profile</a>
 							</div>
 						</div>
 					</div>
 					<br>
 					<div class="row">
 						<div class="col-xs-11">

 							<ul class="nav nav-tabs">
 								<li class="active"><a href="#about" data-toggle="tab">About</a></li>
 								<li><a href="#friends" data-toggle="tab">Friends</a></li>
 								<li><a href="#photos" data-toggle="tab">Photos Uploaded</a></li>
 							</ul>

 							<div class="tab-content">
 								<div class="tab-pane fade in active" id="about">
	 								<div class="panel panel-default">
	 									<?php
	 										echo '<h3 style="margin:0px; margin-left:5px;"><small>'.$name.'</small></h3>'."<br>";
	 										echo '<h3 style="margin:0px; margin-left:5px;"><small>'.$email.'</small></h3>'."<br>";
	 										echo '<h3 style="margin:0px; margin-left:5px;"><small>'.$gender.'</small></h3>'."<br>";
	 										echo '<h3 style="margin:0px; margin-left:5px;"><small>'.$age.'</small></h3>'."<br>";
	 										echo '<h3 style="margin:0px; margin-left:5px;"><small>'.$city.'</small></h3>'."<br>";	 										
	 										echo '<h3 style="margin:0px; margin-left:5px;"><small>'.$phone.'</small></h3>'."<br>";
	 										

	 									?>
	 								</div>
	 							</div>
	 							<div class="tab-pane fade in" id="friends">
	 								<div class="panel panel-default" style="height:280px; overflow-y:auto;">
										<?php
											include('connection.php');
											$fav_relation='favourites_'.$frndID;
											$result=mysql_query("select * from user_info info where userID in(select userID from $fav_relation )");
											while($row=mysql_fetch_array($result)){
												$user_frndID=$row['userID'];
												$name=$row['name'];
												$photo_directory='user'.$user_frndID.'/'.$row['profilepic'];
												$city=$row['city'];
											?>
												<div>
													<div class="col-xs-12" style="margin-top:8px; padding-right:0px;">
														<img src="<?php echo $photo_directory; ?>" height="50" width="50" style="border-radius:5px;"/> <a href="#" onclick="openProfile(<?php echo $user_frndID; ?>,<?php echo $userID; ?>);" oncontextmenu="return false"><?php echo $name;?></a>
													</div>													
												</div>

											<?php
											}
										?>
									</div>
	 							</div>
	 							<div class="tab-pane fade in" id="photos">
	 								xxxxxxxxxxxxx
	 							</div>
	 						</div>

 						</div>
 					</div>
 			</div>


 			<script>
				function update(userID) {
					var xmlHttp,val;
					val=document.getElementById('wrtbox').value;
					xmlHttp=new XMLHttpRequest();
					xmlHttp.onreadystatechange=function(){
						if(xmlHttp.readyState==4 && xmlHttp.status==200){
							alert('succesfully posted');
							window.location.href='user.php';
						}
					}
					var params="val="+val+"&userID="+userID;
					
					xmlHttp.open("POST","wrtbox.php",true);
					//using POST
					xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlHttp.setRequestHeader("Content-length", params.length);
				xmlHttp.setRequestHeader("Connection", "close");
					
					xmlHttp.send(params);

					//at the end set the value of wrtbox=''
					document.getElementById('wrtbox').value='';

				}

				function set_like(updID,userID,frndID,relation) {
					var xmlHttp,likes,likebtn,prev_likes;
					xmlHttp=new XMLHttpRequest();
					xmlHttp.onreadystatechange=function(){
						if(xmlHttp.readyState==4 && xmlHttp.status==200){
							prev_likes=document.getElementById(updID).innerHTML; //previous value of like
							likes=xmlHttp.responseText; //new value
							likebtn='likebtn'+updID;
							document.getElementById(updID).innerHTML=likes;
							if(likes-prev_likes>0){
								//means liked
								document.getElementById(likebtn).setAttribute("style","color:rgb(113,184,255);");
								document.getElementById(updID).setAttribute("style","background-color:rgb(113,184,255);");	
							}
							else{
								//means disliked
								document.getElementById(likebtn).setAttribute("style","color:black;");
								document.getElementById(updID).setAttribute("style","background-color:black;");
							}
							
						}
					}
					xmlHttp.open("GET","like.php?updID="+updID+"&userID="+userID+"&frndID="+frndID+"&relation="+relation,true);
					xmlHttp.send();

				}

				function comment(modalID,userID,updID,updated_by){
					var bodyID="body"+modalID;  //id of the modal body so that we can insert the response in the innerHTML 
					var textareaID="textarea"+modalID;  //textarea id to get the value
					var xmlHttp,comment;
						comment=document.getElementById(textareaID).value; //comment to be posted
						xmlHttp=new XMLHttpRequest();
						xmlHttp.onreadystatechange=function(){
							if(xmlHttp.readyState==4 && xmlHttp.status==200){
								rsp=xmlHttp.responseText;
								document.getElementById(bodyID).innerHTML=rsp;
								if(comment!=''){
									alert('comment posted');	
								}	 											
							}
						}
						var params="userID="+userID+"&updID="+updID+"&updated_by="+updated_by+"&comment="+comment;
						xmlHttp.open("POST","comment.php",true);
						xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xmlHttp.setRequestHeader("Content-length", params.length);
					xmlHttp.setRequestHeader("Connection", "close");
						xmlHttp.send(params);
					/*photo_directory='user1/shubham.jpg';
					commented_by_name='shubham srivastava';
					document.getElementById(bodyID).innerHTML='<div class="alert alert-info" style="padding:0px;">'+'<img src="'+photo_directory+'" height="25" width="25" style="border-radius:5px;">'+'<a href="">'+commented_by_name+'</a>'+'<p style="color:black;">'+comment+'</p>'+'</div>';*/
					document.getElementById(textareaID).value='';
				}
				function refresh_comments(modalID,userID,updID,updated_by){
					comment(modalID,userID,updID,updated_by);
				}
				function countCommentAjax(updID){
					var xmlHttp,comments;
					var comment_button_ID='count_comment'+updID;
					xmlHttp=new XMLHttpRequest();
					xmlHttp.onreadystatechange=function(){
						if(xmlHttp.readyState==4 && xmlHttp.status==200){
							comments=xmlHttp.responseText;
							document.getElementById(comment_button_ID).innerHTML=comments;												 											
						}
					}
					xmlHttp.open("GET","countCommentAjax.php?updID="+updID,true);
					xmlHttp.send();

				}

				
			</script>

			<?php
				function countLikes($updID,$userID)
				{
					$likes=0;
					$likebtn='likebtn'.$updID;
					include('connection.php');
					$result=mysql_query("select count(*) from likes where ID='$updID' "); //count so that is stays
					$row=mysql_fetch_array($result);
					$likes=$row[0];
					//check blue
					$rst=mysql_query("select * from likes where ID='$updID' and liked_by='$userID' "); //for showing blue thumbs up that have been liked already
					if ($row=mysql_fetch_array($rst)) {
					?>
						<script>document.getElementById('<?php echo $likebtn; ?>').setAttribute("style","color:rgb(113,184,255);");</script>
						<script>document.getElementById('<?php echo $updID; ?>').setAttribute("style","background-color:rgb(113,184,255);");</script>
					<?php
					}
					return $likes;
				}
				function countComments($updID)
				{
					$comments=0;
					$comment_button_ID='count_comment'.$updID;
					$result=mysql_query("select count(*) from comments where ID='$updID' ");
					$row=mysql_fetch_array($result);
					$comments=$row[0];
					return $comments;
				}

			?>



 			<div class="col-xs-8">
 				<?php
					include('connection.php');
					$fav_user='favourites_'.$userID;
					//selecting updates only from those who are your frnd including u yourself
					$table_result=mysql_query("select * from updates where updated_by='$frndID' order by updID desc ");
					while($upd=mysql_fetch_array($table_result)){
						$updID=$upd['updID'];
						$updated_by=$upd['updated_by'];
						$update_content=$upd['update_content'];
							$result=mysql_query("select name,profilepic from user_info where userID='$updated_by' ");
							$row=mysql_fetch_array($result);
							$updated_by_name=$row['name'];
							$photo_directory='user'.$updated_by.'/'.$row['profilepic'];
							$modalID='mymodal'.$updID;
							
	 					?>

			 			<div class="col-xs-12" >
			 				<div class="row">
				 				&nbsp;<img src="<?php echo $photo_directory;?>" height="45" width="45" style="border-radius:5px;"> <a href=""><?php echo $updated_by_name; ?></a> <h5 style="display:inline;"><small> posted an update</small></h5>
				 					<div class="jumbotron" style="margin:0px; background-color:rgb(245,245,245);">
				 						<p class="text-justify" style="font-size:13px;"><?php echo $update_content; ?></p>
				 					</div>
				 					<button class="btn btn-default" id="<?php echo 'likebtn'.$updID; ?>" onclick="set_like(<?php echo $updID; ?>,<?php echo $userID; ?>,<?php echo $updated_by; ?>,'<?php echo "updates"; ?>');"><span class="glyphicon glyphicon-thumbs-up"></span> <span class="badge" id="<?php echo $updID; ?>"> <?php echo countLikes($updID,$userID);?> </span></button>
				 					<button class="btn btn-default" data-toggle="modal" data-target="<?php echo '#'.$modalID; ?>" onclick="refresh_comments('<?php echo $modalID; ?>',<?php echo $userID; ?>,<?php echo $updID; ?>,<?php echo $updated_by; ?>);">Comment <span class="badge" id="<?php echo 'count_comment'.$updID;?>"><?php echo countComments($updID);?></span></button>
				 						<div class="modal fade" id="<?php echo $modalID; ?>" style="top:100px; right:75px;">
				 							<div class="modal-dialog">
				 								<div class="modal-content">
				 									<div class="modal-header">
												       <button type="button" class="close" data-dismiss="modal" onclick="countCommentAjax(<?php echo $updID; ?>);">&times;</button>
												    </div>
				 									<div class="modal-body" style="max-height:300px; overflow-y:scroll;">
				 										<div class="row" id="<?php echo 'body'.$modalID; ?>">
				 											<?php
				 												include('connection.php');
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
			 															<img src="<?php echo $commenter_photo_directory; ?>" height="25" width="25" style="border-radius:5px;"> <a href=""><?php echo $commented_by_name; ?></a>						 															
			 															<p style="color:black; overflow-x:hidden; word-wrap:break-word;"><?php echo $comment; ?></p>						 															
			 														</div>
				 													
				 												<?php
				 													}
				 												}
				 											?>
				 											
				 										</div>							 										
				 									</div>
				 									<div class="modal-footer">
				 											<div>
				 												<textarea rows="1" id="<?php echo 'textarea'.$modalID; ?>" placeholder="write your comment" class="col-xs-10"></textarea>
					 											<div class="col-xs-2">
					 												<button class="btn btn-default" onclick="comment('<?php echo $modalID; ?>',<?php echo $userID; ?>,<?php echo $updID; ?>,<?php echo $updated_by; ?>);">comment</button>
					 											</div>
				 											</div>							 											
				 									</div>

				 								</div>
				 							</div>
				 						</div>

				 			</div>

				 			<hr>
			 			</div>
				 		<?php
				 	}
				?>
 			</div>


 		</div>
 	</div>


 		

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>

</body>
</html>