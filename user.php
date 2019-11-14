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
 <body class="bg-info">
 	<!--navbar-->
 	<nav class="navbar navbar-inverse">
 		<div class="container">
 			<!--navbar-header-->
 			<div class="navbar-header">
 				<a class="navbar-brand" oncontextmenu="return false" href="user.php"><img src="logo_2.ico" style="display:inline;"> chitchat</a>
 			</div>
 			<!--ul-->
 			<ul class="nav navbar-nav">
 				<li class="active"><a href="user.php" oncontextmenu="return false"><span class="glyphicon glyphicon-home"></span> Home</a></li>
 				<li><a href="profile.php" oncontextmenu="return false"><span class="glyphicon glyphicon-user"></span>  Profile</a></li>
 				<li><a href="messages.php" oncontextmenu="return false"><span class="glyphicon glyphicon-envelope"></span>  Messages <span class="badge" id="msg_count_navbar" style="background-color:rgb(249,53,53);"><?php $result=mysql_query("select count(*) from messages where reciever='$userID' and seen is NULL ");
																																																							$row=mysql_fetch_array($result);
																																																							if($row[0]!=0)
																																																							echo $row[0]; ?></span></a></li>
 			</ul>
 			<!--navbar-form-->
 			<form class="form navbar-form navbar-left">
 				<div class="form-group">
 					<label for="searchbox"></label>
 					<input type="text" size="40" class="form-control" placeholder="search for a friend" id="searchbox" onkeyup="searchFriend_USERPAGE();">
 				</div>
 			</form>
 			<!--navbar-right-->
 			<ul class="nav navbar-nav navbar-right">
 				<li onclick="destroy_session();"><a href="#" oncontextmenu="return false"><span class="glyphicon glyphicon-off"></span>  Logout</a></li>
 				<li><a href="settings.php" oncontextmenu="return false"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
 			</ul>
 		</div> 		
 	</nav>

 	<div class="col-xs-12 panel panel-default" id="suggestions" style="display:none; position:absolute; overflow-y:auto; height:200px; width:300px; background-color:white; left:510px; top:50px; z-index:10;">
		<!--search result div-->
	</div> 
		<script>
			function searchFriend_USERPAGE(){
				var xmlHttp,rsp;
				val=document.getElementById('searchbox').value;
				xmlHttp=new XMLHttpRequest();
				xmlHttp.onreadystatechange=function(){
					if(xmlHttp.readyState==4 && xmlHttp.status==200){
						rsp=xmlHttp.responseText;
						
						if(val=='')
							document.getElementById('suggestions').style.display="none"; 
						else
							document.getElementById('suggestions').style.display="block";

						document.getElementById('suggestions').innerHTML=rsp;
					}
				}
				xmlHttp.open("GET","searchFriend_USERPAGE.php?val="+val,true);
				xmlHttp.send();
				
			}
		</script>

 	
 	<div class="container" style="position:relative; top:-18px;">

 		<div class="row">
 			

 			<script>
 				function refresh_notifications(userID){
 					var xmlHttp,rsp;
 					xmlHttp=new XMLHttpRequest();
 					xmlHttp.onreadystatechange=function(){
 						if (xmlHttp.readyState==4 && xmlHttp.status==200){
 							rsp=xmlHttp.responseText;
 							document.getElementById('notification_div').innerHTML=rsp;
 							
 						}
 					}
 					xmlHttp.open("GET","notifications.php?userID="+userID,true);
 					xmlHttp.send();
 				}
 				setInterval(function(){
 								document.getElementById('notification_repeat').click();
 								count_newMsg();
 							},1000);

 				function count_newMsg(){
 					var xmlHttp,rsp;
 					xmlHttp=new XMLHttpRequest();
 					xmlHttp.onreadystatechange=function(){
 						if (xmlHttp.readyState==4 && xmlHttp.status==200){
 							rsp=xmlHttp.responseText;
 							if(rsp==0){
 								document.getElementById('msg_count_navbar').innerHTML='';
 							}
 							else{
 								document.getElementById('msg_count_navbar').innerHTML=rsp;	
 							}
 							
 							
 						}
 					}
 					xmlHttp.open("GET","msg_count_navbar.php?",true);
 					xmlHttp.send();
 				}
  			</script>


 			<!--notification portion-->	
 			<div class="col-xs-2" >
 				
 				<div class="row" style="padding-right:13px;">
 					<div class="alert alert-info" style="padding:10px;">
	 					<strong ><a href="#"><h5 class="text-center"><strong>Notifications</strong></h5></a></strong> <span id="notification_repeat" onclick="refresh_notifications(<?php echo $userID; ?>);"></span> 
	 				</div>
 				</div>
 				
 				<div class="row" style="padding-right:13px;">
 					<div id="notification_div" style="height:auto; background-color:white;">
 						<?php
 							include('connection.php');
 							$not_table='notifications_'.$userID;							
 							$result=mysql_query("select * from $not_table order by sno desc");
							if($result === FALSE) { 
    die(mysql_error()); // TODO: better error handling
}
 							while($row=mysql_fetch_array($result)){
 								$sno=$row['sno'];
 								$notification=$row['notification'];
 								$link='#'; //create a  link from notification_id
 							?>
 								<div class="alert alert-info" style="padding:0px; margin:0px;"><a href="<?php echo $link;?>" onclick="move2notification(<?php echo $sno; ?>);" oncontextmenu="return false"><h6 style="font-size:10px;" class="text-center"><?php echo $notification; ?></h6></a></div>
 							<?php 							
 							}
 						?>
 						
 					</div>
 				</div>

 			</div>
 				<script>
 					function move2notification(sno){
 						var xmlHttp,rsp;
 						xmlHttp=new XMLHttpRequest();
 						xmlHttp.onreadystatechange=function(){
 							if(xmlHttp.readyState==4 && xmlHttp.status==200){
 								rsp=xmlHttp.responseText;
 								if(rsp=='update')
 									window.location.href='notification_view.php';
 								else if(rsp=='friends')
 									window.location.href='profileFrnd.php';
 							}
 						}
 						xmlHttp.open("GET","move2notificationAjax.php?sno="+sno,true);
 						xmlHttp.send();
 					}
 				</script>






 			<!--middle posts portion-->	
 			<div class="col-xs-7" style="background-color:white; padding-top:7px; padding-bottom:7px; height:215px;">
 				
 				<ul class="nav nav-tabs">
 					<li class="active"><a href="#update" data-toggle="tab">Post an Update</a></li>
 					<li><a href="#photo_update" data-toggle="tab">Upload a Photo</a></li>
 				</ul>

 				<div class="tab-content">
 					<div class="tab-pane fade in active" id="update">
 						<br>
 						<textarea rows="4" id="wrtbox" placeholder="Write Something..." class="col-xs-12"></textarea>
 						<button class="btn btn-default" style="display:block;" onclick="update(<?php echo $userID?>); ">Post</button>
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
 								function openProfile(frndID,userID){
 									if(frndID==userID){
 										window.location.href='profile.php';
 										return ;
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
 								function photoUpdate(){
 									alert('This Feature is currently under development'); 									
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



 					<div class="tab-pane fade in" id="photo_update">
 						<br>
 						<textarea class="col-xs-12" rows="3" placeholder="Add a caption to this image"></textarea>
 						<input type="file">
 						<input type="submit" value="Upload" class="btn btn-default" onclick="photoUpdate();">
 					</div>
 				</div>


 				<br><br>
 				
 					<div class="row" style="background-color:white;">

 						<?php
 							include('connection.php');
 							$fav_user='favourites_'.$userID;
 							//selecting updates only from those who are your frnd including u yourself
 							$table_result=mysql_query("select * from updates where updated_by in(select userID from $fav_user union select userID from user_info where userID='$userID' ) order by updID desc ");
 							if($table_result === FALSE) { 
    die(mysql_error()); // TODO: better error handling
}
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
							 				&nbsp;<img src="<?php echo $photo_directory;?>" height="45" width="45" style="border-radius:5px;"> <a href="#" onclick="openProfile(<?php echo $updated_by; ?>,<?php echo $userID; ?>);" oncontextmenu="return false" ><?php echo $updated_by_name; ?></a> <h5 style="display:inline;"><small> posted an update</small></h5>
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
						 															<img src="<?php echo $commenter_photo_directory; ?>" height="25" width="25" style="border-radius:5px;"> <a href="user.php"><?php echo $commented_by_name; ?></a>						 															
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
 						&nbsp;<img src="logo.png" height="45" width="45" style="border-radius:5px;"> <a href="#" oncontextmenu="return false" >chitchat</a> <h5 style="display:inline;"><small> Welcomes you</small></h5>
 						<div class="jumbotron" style="margin:0px; background-color:rgb(245,245,245);">
	 						<p class="text-justify" style="font-size:13px;"><ul>
	 							<li><strong>Add Friends</strong> to your <strong>Friend List</strong></li>
	 							<li><strong>Instant chatting</strong> with your Friends</li>
	 							<li><strong>Post Updates</strong></li>
	 							<li><strong>Like</strong> and <strong>Comment</strong> on updates</li>
	 							<li>get <strong>Notifications</strong></li>	 							
	 						</ul></p>
	 					</div>
	 					
 						
 					</div>

 				
 			</div>










 			<!--right portion-->	
	 		<div class="col-xs-3">
	 			<div class="alert alert-info" style="margin:0px; padding:5px 15px 5px 15px;">
					<strong>Friend List </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="moreFriends.php">find more friends</a>
				</div>

	 			<div class="col-xs-12 panel panel-default" style="height:220px; overflow-y:scroll;">
	 				<div class="panel-content" id="friendlistPanel">
	 					<?php
	 						$favourites_table='favourites_'.$userID; //tables of user's favourites
	 						$query=mysql_query("select * from $favourites_table "); //selecting all his favourites
	 						while($arr=mysql_fetch_array($query)){
	 							$frndID=$arr[0];
	 							$result=mysql_query("select * from user_info where userID='$frndID' ");
								while($row=mysql_fetch_array($result)){
									$name=$row['name'];
									$profilepic=$row['profilepic'];
									$photo_directory='user'.$frndID.'/'.$profilepic;
									?>
									<div class="row">
										<div class="col-xs-9" style="margin-top:5px;">
											<img src="<?php echo $photo_directory;?>" height="30" width="30" style="margin-bottom:2px; border-radius:2px;">
											<a href="#" onclick="openProfile(<?php echo $frndID; ?>,<?php echo $userID; ?>);" oncontextmenu="return false"  style="font-size:13px;"><?php echo $name;?></a>
										</div>
										<div class="col-xs-3" style="margin-top:4px; padding:0px;">
											<button class="btn btn-default btn-sm" onclick="setValues('<?php echo $name; ?>',<?php echo $frndID; ?>,'<?php echo $photo_directory; ?>',<?php echo $userID ?>);">chat</button>
										</div>
									</div>
									<?php
					 			}	
	 						}
	 							 						
	 					?>

	 				</div>
	 			</div>

					 			<script>
					 				rspXML='';
					 				function setValues(name,frndID,photo_directory,userID) {
										document.getElementById('convTitle').innerHTML='<p style="font-size:12px; display:inline;" >'+name+'</p>'+'&nbsp;'+'<img src="'+photo_directory+'" height="30" width="30" style="margin-bottom:1px; border-radius:2px;"/>'+' <span id="count_unreadMsg" class="badge" style="background-color:rgb(249,53,53);"></span>';
										document.getElementById('send').setAttribute("onclick","sendmessage("+frndID+","+userID+")"); //set the attribute of send button by providing the sender's ID and reciever's ID
										document.getElementById('refresh').setAttribute("onclick","refresh("+frndID+","+userID+")");
										document.getElementById('count_msg_id').setAttribute("onclick","setmsg_read("+frndID+","+userID+")");
										sendmessage(frndID,userID);

									}

									function sendmessage(frndID,userID) {
										var xmlHttp,msg;
										msg=document.getElementById('textbox').value;
										xmlHttp=new XMLHttpRequest();
										xmlHttp.onreadystatechange=function(){
											if(xmlHttp.readyState==4 && xmlHttp.status==200){
												rspXML=xmlHttp.responseText;
												displayConv();
												setInterval(function(){document.getElementById("refresh").click();
															count_msg(frndID,userID);
															var objDiv = document.getElementById("autoscroll");
															objDiv.scrollTop = objDiv.scrollHeight;
												}, 1000); //automatically click the refresh button after 3seconds
												//do very litle task in setInterval method otherwise server starts hanging(i.e low buffer-memory)
			
											}
										}
										xmlHttp.open("GET","chat_conv.php?msg="+msg+"&userID="+userID+"&frndID="+frndID,true);
										xmlHttp.send();

										//empty the text box now
										document.getElementById('textbox').value='';
									}
									function displayConv(){
										document.getElementById('convBox').innerHTML=rspXML;
										
									}
									function refresh(frndID,userID) {
										var xmlHttp,msg;
										msg=document.getElementById('textbox').value;
										xmlHttp=new XMLHttpRequest();
										xmlHttp.onreadystatechange=function(){
											if(xmlHttp.readyState==4 && xmlHttp.status==200){
												rspXML=xmlHttp.responseText;
												displayConv();
			
											}
										}
										xmlHttp.open("GET","chat_conv2.php?msg="+msg+"&userID="+userID+"&frndID="+frndID,true);
										xmlHttp.send();
									}
									function setmsg_read(frndID,userID){
										//sets the seen of message as read
										var xmlHttp;
										xmlHttp=new XMLHttpRequest();
										xmlHttp.onreadystatechange=function(){
											if(xmlHttp.readyState==4 && xmlHttp.status==200){
												//do nothing beacuse setmsg_read.php has done it's work of setting seen='yes'	
											}
										}
										xmlHttp.open("GET","setmsg_read.php?userID="+userID+"&frndID="+frndID,true);
										xmlHttp.send();
									}
									function count_msg(frndID,userID){
										//count the unread message and display it dynamically because this function is being called in setInterval() function above
										var xmlHttp;
										xmlHttp=new XMLHttpRequest();
										xmlHttp.onreadystatechange=function(){
											if(xmlHttp.readyState==4 && xmlHttp.status==200){
												rsp=xmlHttp.responseText;	
												if(rsp==0){
													document.getElementById('count_unreadMsg').innerHTML='';	
												}
												else{
													document.getElementById('count_unreadMsg').innerHTML='+'+rsp;	
												}												
											}
										}
										xmlHttp.open("GET","count_msg.php?userID="+userID+"&frndID="+frndID,true);
										xmlHttp.send();										
									}

								</script>

				<div class="alert alert-info" style="margin:0px; ">
					<strong style="text-decoration:underline; font-size:12px;">CHATBOX</strong> <a><span id="refresh"></span></a> <!--class="glyphicon glyphicon-repeat" style="cursor:pointer;" was inside span tag to put a refresh glyphicon-->

					<p><strong id="convTitle"></strong></p>
				</div>
			
	 			<div class="col-xs-12 panel panel-default" id="autoscroll" style="height:210px; overflow-y:scroll;" >
	 				<div class="panel-content" id="convBox"> <!--conversation box-->
	 					
	 					<div class="jumbotron" style="margin-top:8px;">
	 						<h6 style="text-decoration:underline;"><strong>CHAT HISTORY</strong></h6>
	 						<h5 style="margin:3px;"><small>&nbsp;select a friend</small></h5>
	 						<h5 style="margin:3px;"><small>&nbsp;&nbsp;&nbsp;from your</small></h5>
	 						<h5 style="margin:3px;"><small>&nbsp;&nbsp;&nbsp;Friend List</small></h5>
	 					</div>	 					
	 					
	 				</div>
	 			</div>
	 			<!--send your message box-->
	 			<div class="col-xs-12" id="count_msg_id" style="position:relative; top:-20px;">
	 			<div class="row">
	 				<div class="panel panel-default">
	 					<div class="panel-content">
	 						<div class="row">
	 							<div class="col-xs-8">
				 					<textarea rows="1" cols="23" placeholder="type your message here" id="textbox"></textarea> <!--textbox-->
				 				</div>
					 			<div class="col-xs-4" >
					 				<button class="btn btn-default btn-sm" style="margin-left:0px;" id="send">send</button>
					 			</div>
	 						</div>
	 						
	 					</div>
		 				
	 				</div>
	 			</div>
	 			</div>


	 		</div>
	 	</div>
 	</div>
	


 	<!--jquery and bootstrap's javascript file-->
 	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
 </body>
 </html> 