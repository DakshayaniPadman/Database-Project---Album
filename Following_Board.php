<?php
ob_start();
session_start();
//Check whether the session variables are present
 if(!isset($_SESSION['SESS_userid']) || (trim($_SESSION['SESS_userid']) == ''))
 {
        echo "session user id not set!";
        exit();
 }

//Get session variable
$pb_user=$_SESSION['SESS_userid'];
//Get params from $_GET variables
$pbname = $_GET["param"];
$pbi = $_GET["pboardid"];
$_SESSION['SESS_pbi'] = $pbi;
echo $pbname;
?>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<h3>
<?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?>
 </h3>

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
<form>
<h3> <center><?php echo "{$pbname}"?></center> </h3>
<a href="Follow_Boards.php" style="float:left;">Back</a>
<a href="Add_Follow.php?param=$pbname" style="float:right;">Follow</a>
<br><br>
<?php

//Mysql parameters
$host="127.0.0.1";
$user="app1";
$pswd="apptest1";
$db="test";

//Other variables
  $pic = $board = "";
  $pic_arr=array();

   //Connect to MYSQL
   $con=mysqli_connect("$host","$user","$pswd","$db");

   // Check connection
   if (mysqli_connect_errno($con))
   {
     echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }

   //Query to Get Pictures
   $qry1 = "SELECT Image_URL from PinnedPicture NATURAL JOIN Picture WHERE PBoard_Id = $pbi";
   $result1 = mysqli_query($con, $qry1);

   if(!$result1)
   {
      echo "Could not sucessfully run query($qry1) from db:".mysqli_error($con);
      exit();
   }

   if(mysqli_num_rows($result1) > 0)
   {
      while($row=mysqli_fetch_array($result1))
      {
         $pic_url = $row['Image_URL'];
 
         //Check if file exists in path
         if(file_exists($pic_url))
         {
            echo "<li style=\"display: inline;\"><img src=\"{$pic_url}\"></li>";
         }
         else
         {
            //Download the image from Database
            $qry2 = "SELECT Image FROM Picture WHERE Image_URL = '$pic_url'";
            $result2 = mysqli_query($con, $qry2);

            if(!$result2)
            {
               echo "Could not sucessfully run query($qry2) from db:".mysqli_error($con);
               continue;
            }

            if(mysqli_num_rows($result2) > 0)
            {
               $row = mysqli_fetch_array($result2);
               file_put_contents($pic_url, $row[0]);
               echo "<li style=\"display: inline;\"><img src=\"{$pic_url}\"></li>";
            }
            else
            {  
               echo "There is no image in db";
               continue;
            }

         }
      }
   }
   else
   {
      echo "This Board has no Pictures Pinned to It.";
   }

session_write_close();
ob_end_flush();
?>
</form>
</div>
<?php ob_end_flush();?>
</body>
</html>

