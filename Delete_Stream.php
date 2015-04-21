<?php
ob_start();
session_start();
?>
<html>
<head>
   <style>
      .error {color: #FF0000;}
   </style>
<link href="style.css" rel="stylesheet" type="text/css">
<h4> <?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?> </h4>
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

</div>

</head>
<body>
<?php
//Get variables from session
   $pb_user = $_SESSION['SESS_userid'];
// define variables and set to empty values
   $stream = $_GET["param"];

//Checking Input Parameters
     if(!empty($stream))
     {
        $con=mysqli_connect("127.0.0.1","app1","apptest1","test");

        // Check connection
        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

echo $stream;
echo $pb_user;
         $qry1 = "DELETE FROM FollowStream WHERE User_Id = '$pb_user' AND FollowStream_Name = '$stream'";
         $result1 = mysqli_query($con,$qry1);
echo $qry1;
         if(!$result1)
        {
            echo "Could not sucessfully run query($qry1) from db:".mysqli_error($con);
            //header("Location: User_Streams.php");
            //exit();
         }         
         else
            echo "success";

         mysqli_close($con);
      }
ob_end_flush();
?>
<br><br><br>
<center>Stream not being Followed anymore</center>
</body>
</html>
