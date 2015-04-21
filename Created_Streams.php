<?php
ob_start();
session_start();
//Check whether the session variables are present
 if(!isset($_SESSION['SESS_userid']) || (trim($_SESSION['SESS_userid']) == ''))
 {
        exit();
 }

//Get session variable
$pb_user=$_SESSION['SESS_userid'];

//Get params from $_GET variables
$stream = $_GET["param"];
?>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<h3 style="background-color:hsla(120,100%,25%,0.3)"> Welcome <?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?>! </h3>
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
<h3> <center><?php echo "{$stream}"?></center> </h3>
<a href="Delete_Stream.php?param=<?php echo "{$stream}"?>" style="float:right;">Delete Stream</a><br><br>
<?php

//Other variables

   //Connect to MYSQL
   $con=mysqli_connect("127.0.0.1","app1","apptest1","test");

   // Check connection
   if (mysqli_connect_errno($con))
   {
     echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
   //Query to Get Pictures
   $qry1 = "SELECT Image_URL, Pic_Id, PinnedPicture.Pin_Id from PinnedPicture NATURAL JOIN Picture NATURAL JOIN FollowStream WHERE FollowStream_Name = '$stream' AND User_Id = '$pb_user'";
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
         $pinid = $row['Pin_Id'];
         $picid = $row['Pic_Id'];
         $imageURL=$row['Image_URL'];
         
         //Check if file exists in path
         if(file_exists($imageURL))
         {
            echo "<li style=\"display: inline;\"><a href=\"likecomments.php?pinid=$pinid&picid=$picid\"><img src=\"{$imageURL}\"></a></li>";
         }
         else
         {
           //Download the image from Database
            $qry2 = "SELECT Image FROM Picture WHERE Pic_Id = $picid";
            $result2 = mysqli_query($con, $qry2);

            if(!$result2)
            {
               echo "Could not sucessfully run query($qry2) from db:".mysqli_error($con);
               continue;
            }

            if(mysqli_num_rows($result2) > 0)
            {
               $row1 = mysqli_fetch_array($result2);
               file_put_contents($ImageURL, $row1[0]);
               echo "<li style=\"display: inline;\"><a href=\"likecomments.php?pinid=$pinid&picid=$picid\"><img src=\"{$imageURL}\"></a></li>";
            }
            else
            {
               echo "There is no image in db";
               continue;
            }
 
         }
         
      }
      mysqli_free_result($result1);
      mysqli_close($con);
			
   }
session_write_close();
?>
</form>
</div>
<?php ob_end_flush();?>
</body>
</html>

