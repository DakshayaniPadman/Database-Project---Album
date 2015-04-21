<?php
	//start session
	ob_start();
	session_start();
	
   
	//Check whether the session variables are present
 	if(!isset($_SESSION['SESS_user_id']) || (trim($_SESSION['SESS_user_id']) == ''))
 		{
			exit();
 		}
 		session_save_path();
	    ob_end_flush();
	
?>
 		
 	<html>
	<head>
	<link href="style.css" rel="stylesheet" type="text/css">
	<h3 style="background-color:hsla(120,100%,25%,0.3)"> Welcome <?php echo "{$_SESSION['SESS_first_name']} {$_SESSION['SESS_last_name']}"?>! </h3>
	</head>
	<body>
	<div id="container">

		<div id="header">
<form name='searchform' action="SearchByTag.php" method="post">
   <input type="text" name="tag"/>
   <input type="submit" value="Search"/>
   <a href="Welcome.php" style="color:#000000">Home</a>
   <a href="Logout.php" style="color:#000000">Logout</a>
   </form>
		</div>

		<div id="leftnav">
<a href="User_Boards.php" style="color:#000000">Your Boards</a><br>
<a href="User_Follows.php" style="color:#000000">Followed Boards</a><br>
<a href="User_Streams.php" style="color:#000000">Followed Streams</a><br>
<a href="AllPinboards.php" style="color:#000000">All Pinboards</a><br>
<a href="Response_Friendrequest.php" style="color:#000000">Pending Requests</a><br><br><br>
<a href="Add_pinboard.php" style="color:#000000">Create Pinboard </a><br>
<a href="Create_Pin.php" style="color:#000000">Add a Pin </a><br>
<a href="Follow_Boards.php" style="color:#000000">Follow Boards </a> <br>
<a href="Add_Streams.php" style="color:#000000">Create FollowStream</a><br>
<a href="Invite_Friend.php" style="color:#000000">Invite Friends</a><br>
<a href="Update_Profile.php" style="color:#000000">Update Profile</a>
		</div>
		<div id="body">
 		<?php
 				//create connection
	 			$con=mysqli_connect("127.0.0.1","app1","apptest1","test");
				// Check connection
				if (mysqli_connect_errno($con))
  					{
  						echo "Failed to connect to MySQL: " . mysqli_connect_error();
  					}
  					
  					if(!empty($_POST['accept']) && $_POST['accept'])
  						{
  						if(!empty($_POST['check_list'])) {
    						foreach($_POST['check_list'] as $check) {
           					 $qry="update Friend set friendship_status='Accepted', friendship_time=current_timestamp where User_ID in ('".$_SESSION['SESS_user_id']."','".$check."') and Friend_Id in 
		                	('".$_SESSION['SESS_user_id']."','".$check."') and friendship_status='pending';";
		                	$result=mysqli_query($con,$qry);
		               	 echo "you accepted the friend request for ".$check."<br>";
         		                
   						 }
						}
  						 
		               }
		            else if(!empty($_POST['reject']) && $_POST['reject'])
		               {
		               	if(!empty($_POST['check_list'])) {
    						foreach($_POST['check_list'] as $check) {
           					 $qry="update Friend set friendship_status='Rejected', friendship_time=current_timestamp where User_ID in ('".$_SESSION['SESS_user_id']."','".$check."') and Friend_Id in 
		                	('".$_SESSION['SESS_user_id']."','".$check."') and friendship_status='pending';";
		                	$result=mysqli_query($con,$qry);
		               	 echo "you Rejected the friend request for ".$check."<br>";
         		                
   						 }
						}
  						 
		               
		               }
	
?>
</div>
		</div>
</body>
</html>

