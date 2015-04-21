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
?>

<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<h4><?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?> </h4>
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
<br>
</div>

<div id="body">
<h3> <center>You Follow</center></h3> 
<?php
//define mysql variables
$host="127.0.0.1";
$user="app1";
$pswd="apptest1";
$db="test";

//Other variables
  $board_id = $board_name = "";
  $pb_user = $_SESSION['SESS_userid'];

   //Connect to MYSQL
   $con=mysqli_connect("$host","$user","$pswd","$db");

   // Check connection
   if (mysqli_connect_errno($con))
   {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
   //Create query
   $qry="SELECT Follow.Pboard_Id, PBoard_Name FROM Follow JOIN PinBoard USING(Pboard_Id) WHERE Follow.User_Id ='$pb_user'";
   $result=mysqli_query($con,$qry);

   if(!$result)
   {
      echo "Could not sucessfully run query($qry) from db:".mysqli_error();
      //exit();
   }
   if(mysqli_num_rows($result) == 0)
   {
      //Returned no Rows
      echo "This User has no Boards Created\n";
   }
   else
   {
         while($row=mysqli_fetch_array($result))
         {
            $board_id =$row['Pboard_Id'];
            $board_name = $row['PBoard_Name'];
            echo "&nbsp;&nbsp";
            echo "<a href=\"Followed_Boards.php?param=$board_name&pboardid=$board_id\">$board_name</a><br>";
         }

   }
   //mysqli_free_result($result);

   mysqli_close($con);

session_write_close();
ob_end_flush();
?>
</div>

</body>
</html>

