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
   $fol_user = $_SESSION['SESS_usrfol'];
   $pbi = $_SESSION['SESS_pbi'];

// define variables and set to empty values
   $pbErr = "";
   $errormessage="";

//Checking Input Parameters
   $pb_name = $_GET["param"];
   //echo "Add follow: $pb_name\n";
   $con=mysqli_connect("$host","$user","$pswd","$db");

   // Check connection
   if (mysqli_connect_errno($con))
   {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
   //Create query
   $qry = "INSERT INTO Follow VALUES ('$pb_user', $pbi, current_timestamp)";
   $result=mysqli_query($con,$qry);

   //Check whether the query was successful or not
   if(!($result))
   {
      echo "Could not sucessfully run query($qry) from db:".mysqli_error($con);
      exit();
   }
   else
   {
      //Insert Successful
      header("Location:User_Follows.php");
      mysqli_free_result($result);
      mysqli_close($con);
      exit();

   }
ob_end_flush();
?>
</body>
</html>

