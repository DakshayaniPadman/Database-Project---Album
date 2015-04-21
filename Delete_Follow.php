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
//default variables
  $host="127.0.0.1";
  $user="app1";
  $pswd="apptest1";
  $db="test";

//Get variables from session
   $pb_user = $_SESSION['SESS_userid'];
// define variables and set to empty values
   $pbi = $_GET["param"];

//Checking Input Parameters
     if(!empty($pbi))
     {
        $con=mysqli_connect("$host","$user","$pswd","$db");

        // Check connection
        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        //Check if Pinboard name exists, to maintain board name uniqueness
         $qry1 = "DELETE FROM Follow WHERE User_Id = '$pb_user' AND PBoard_Id = $pbi";
         $result1 = mysqli_query($con,$qry1);

         if(!($result1))
         {
           die('Could not delete data: ' .mysqli_error($con));
         }
         else
         {
            header("Location: User_Follows.php");
            exit();
         }
         mysqli_close($con);
      }

?>
<br><br><br>
<center>Board not being Followed anymore</center>
</body>
</html>
