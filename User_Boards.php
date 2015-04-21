<?php
//start session
ob_start();
session_start();

//Check whether the session variables are present
 if(!isset($_SESSION['SESS_userid']) || (trim($_SESSION['SESS_userid']) == ''))
 {
	exit();
 }
?>

<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<h3 style="background-color:hsla(100,100%,25%,0.3)"> Welcome <?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?>! </h3>
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
<?php
//Mysql parameters
$host="127.0.0.1";
$user="app1";
$pswd="apptest1";
$db="test";

//Other variables
  $board = "";
  $pb_user = $_SESSION['SESS_userid'];

   //Connect to MYSQL
   $con=mysqli_connect("$host","$user","$pswd","$db");

   // Check connection
   if (mysqli_connect_errno($con))
   {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
   //Create query
   $qry="SELECT PBoard_Name,PBoard_Id FROM PinBoard WHERE User_Id ='".$pb_user."'";
   $result=mysqli_query($con,$qry);

   if(!$result)
   {
      echo "Could not sucessfully run query($qry) from db:".mysqli_error();
      exit();
   }

   if(mysqli_num_rows($result) == 0)
   {
      //echo "Returned no Rows";
      echo "0 Boards\n";
   }
   else
   {
      while($board=mysqli_fetch_array($result))
      {
    	  $t1 = $board['PBoard_Name'];
         $pboardid=$board['PBoard_Id'];
         echo "&nbsp;&nbsp";
         echo "<a href=\"Created_Pinboard.php?param=$t1&pboardid=$pboardid\">$t1</a><br>";
      }
   }
   //mysqli_free_result($result);

   mysqli_close($con);

ob_end_flush();
?>
</div>

</body>
</html>

