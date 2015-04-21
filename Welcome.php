<?php
//start session
ob_start();
session_start();

//Check whether the session variables are present
 if(!isset($_SESSION['SESS_userid']) || (trim($_SESSION['SESS_userid']) == ''))
 {
	exit();
 }
session_save_path();
ob_end_flush();
?>

<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<h4> <?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?> </h4>
</head>
<body>

<div id="container">

<div id="header">
   <form name='searchform' action="SearchByTag.php" method="post">
   <input type="text" name="tag"/>
   <input type="submit" value="Search"/>


   <a href="Welcome.php">Home</a>
   <a href="Logout.php">Logout</a>
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
 Collect Photos of your interest by uploading the photos from web or your computer.
<br><br>
<img src="Images/office-art-1.jpg" width="750" height="600">
</div>

</body>
</html>

