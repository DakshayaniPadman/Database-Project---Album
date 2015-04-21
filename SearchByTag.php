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
    <h3 style="background-color:hsla(100,100%,25%,0.3)"><?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?>! </h3>
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
<a href="Add_Streams.php" style="color:#000000">Create FollowStream</a><br> 
<a href="Follow_Boards.php" style="color:#000000">Follow A Board</a> <br>
 <a href="Add_pinboard.php" style="color:#000000">Create Pinboard</a><br> 
<a href="Create_Pin.php" style="color:#000000">Add a Pin</a><br> 
<a href="Invite_Friend.php" style="color:#000000">Invite Friends</a><br>
<a href="Update_Profile.php" style="color:#000000">Update Profile</a><br>
 </div>


<div id="body">
<?php

//default variables
 		 $host="127.0.0.1";
 		 $user="app1";
 		 $pswd="apptest1";
 		 $db="test";

// Create connection
$con=mysqli_connect("$host","$user","$pswd","$db");

// Check connection
if (mysqli_connect_errno($con))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

if(!empty($_POST["tag"])){
	if($_POST["tag"]=="_"){
		$qry= "select pp.Pic_Id,p.Image_URL,pp.tag, pp.Pin_Id from PinnedPicture pp,Picture p where tag like '%/_%' ESCAPE '/' and pp.pic_id=p.pic_id order by Pinned_Time";
	}else{
		$qry="select pp.Pic_Id,p.Image_URL,pp.tag, pp.Pin_Id from PinnedPicture pp,Picture p where tag like '%".$_POST['tag']."%' and pp.pic_id=p.pic_id order by Pinned_Time";
	}
	$result = mysqli_query($con,$qry);
	if(!empty($result) && mysqli_num_rows($result)>0){
	echo "<b>Images for ".$_POST["tag"]."</b>";
	while($row = mysqli_fetch_array($result))
  		{
                        echo "<ul><a href=\"likecomments.php?pinid=$row[3]&picid=$row[0]\"><img src=\"{$row[1]}\"></a></ul>";
       		 echo "<b>Description:</b> {$row[2]}<br><br>";
		}
	}else{
		echo "No search result found!";
	}
}else{
	echo "Search box should not be empty!";
}
?>
</div>
</div>
</body>
</html>





