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
   $pbErr = "";
   $pb_name = $privacy= "";
   $errormessage="";

//Checking Input Parameters
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
     if (empty($_POST["pb_name"]))
     {

         $pbErr = "Pinboard name is required";
     }
     else
     {
        $pb_name = $_POST["pb_name"];
     }
    if (empty($_POST["priv"]))
     {
         $privacy = 0;
     }
     else
     {
        $privacy=$_POST["priv"];
     }

     if(empty($pbErr))
     {
        $con=mysqli_connect("$host","$user","$pswd","$db");

        // Check connection
        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        //Check if Pinboard name exists, to maintain board name uniqueness
         $qry1 = "SELECT * FROM Pinboard WHERE User_Id = '$pb_user' AND PBoard_Name = '$pb_name'";
         $result1 = mysqli_query($con,$qry1);

         if(!($result1))
         {
           die('Could not select data: ' .mysqli_error($con));
         }
         else
         {
            if(mysqli_num_rows($result1) > 0)
            {
               $pbErr= "Pinboard name already exists!";
            }
            else
            {
              //Create query
              $qry = "INSERT INTO Pinboard (PBoard_Name, User_Id, Access_Status, Creation_Time, Last_Updated_Time)".
                     " VALUES ('$pb_name', '$pb_user', '$privacy', current_timestamp, current_timestamp)";
              $result=mysqli_query($con,$qry);

              //Check whether the query was successful or not
              if(!($result))
              {
                 die('Could not enter data: ' .mysqli_error($con));
              }
              else
              {
                 //Insert Successful
                  $qry2 = "SELECT PBoard_Id FROM Pinboard WHERE User_Id = '$pb_user' AND PBoard_Name = '$pb_name'";
                  $result2 = mysqli_query($con,$qry2);
                  if(!empty($result2) && mysqli_num_rows($result2)>0)
                  {
                     $row = mysqli_fetch_array($result2);
                     header("Location:Created_Pinboard.php?param=$pb_name&pboardid=$row[0]");
                     exit();
                  }
              }
           }
        }
        mysqli_close($con);
     }

  }
ob_end_flush();
?>
<p><span class="error">* required field.</span></p>
<span class="error"><?php echo $errormessage;?></span><br>
<form name='form3' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        Pinboard:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="pb_name">
         <span class="error">* <?php echo $pbErr;?></span>
         <br><br>
        Privacy:&nbsp;&nbsp;&nbsp;&nbsp;
          <input type='radio' name='priv' value=0>Public
          <input type='radio' name='priv' value=1>Friends Only<br><br>
        Default Setting is Public
         <br><br>
        <input type="submit" value="Create"><br><br>
</form>
</body>
</html>

