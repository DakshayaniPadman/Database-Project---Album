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
</div>

<div id="body">
<?php
// define variables and set to empty values
   $txtErr = "";
   $board_arr = "";

//Checking Input Parameters
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
     if(!empty($_POST["stream"])) 
     {
         if(empty($_POST["board"]))
            $txtErr = "Please select Pinboards to create a Stream";
         else
         {
            $board_arr = $_POST["board"];
            $stream = $_POST["stream"];

            $con=mysqli_connect("127.0.0.1","app1","apptest1","test");
            // Check connection
            if (mysqli_connect_errno($con))
            {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }

            for($i = 0; $i < count($board_arr); $i++)
            {
               //echo "{$board_arr[$i]}";

               $qry = "INSERT INTO FOLLOWSTREAM VALUES('$stream', '$pb_user', $board_arr[$i], current_timestamp)";
               $result=mysqli_query($con,$qry);
               if(!$result)
                  die('Could not enter data: ' .mysqli_error($con));
               else
                  mysqli_free_result($result);
            }
            
            //Insert data was all successful.
            header("Location: User_Streams.php");
            exit();
         }
     }
     else
        $txtErr = "Stream Name is Required!";
     
  }
ob_end_flush();
?>
<h3>Select Boards to add to a stream</h3>
<p><span class="error">* required field.</span></p><br>
<form name='createstream' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;StreamName: <input type="text" name="stream"/><br>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Create Stream"/>
         <span class="error">* <?php echo $txtErr;?></span><br><br><br>
<?php
   $con=mysqli_connect("127.0.0.1","app1","apptest1","test");
   if (mysqli_connect_errno($con))
   {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
   //Select Board Names to be displayed
   $qry = "SELECT PBoard_Name, PBoard_Id FROM PinBoard WHERE User_Id !='$pb_user' AND Pboard_Id NOT IN".
          "(Select Pboard_Id FROM Follow NATURAL JOIN FollowStream WHERE User_Id='$pb_user')";
   $result=mysqli_query($con,$qry);

   //Check whether the query was successful or not
   if(!empty($result) && mysqli_num_rows($result)> 0)
   {
       while($row = mysqli_fetch_array($result))
       {
          echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"board[]\" value=\"{$row[1]}\">$row[0]<br>";
       }
   }
mysqli_close($con);
?>
</form>
</div>
</div>
</body>
</html>

