<?php
ob_start();
session_start();
//Copy user id
$pb_user = $_SESSION['SESS_userid'];
$pic_url = $_SESSION['SESS_timage'];
//Mysql variables
                  $host="127.0.0.1";
                  $user="app1";
                  $pswd="apptest1";
                  $db="test";
                  //Other variables
                  $board_arr = array();

                  //Select PinBoard names of the User so that User can select the Pinboard to which he/she can pin the picture
                  $con=mysqli_connect("$host","$user","$pswd","$db");

                  // Check connection
                  if (mysqli_connect_errno($con))
                  {
                     echo "Failed to connect to MySQL: " . mysqli_connect_error();
                  }
                  //Create query
                  $qry = "SELECT PBoard_Name, PBoard_Id FROM PinBoard WHERE User_Id='$pb_user'";
                  $result=mysqli_query($con,$qry);

                  //Check whether the query was successful or not
                  if(!$result)
                  {
                     echo "Could not sucessfully run query($qry) from db:".mysqli_error();
                     exit();
                  }
                  if(mysqli_num_rows($result) != 0)
                  {
                     $board_arr[] = array(0, "");
                     while($row=mysqli_fetch_array($result))
                     {
                        $board_arr[] = array($row['PBoard_Id'] ,$row['PBoard_Name']);

                     }
                  }
                  mysqli_free_result($result);
                  mysqli_close($con);


function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
?>
<html>
<head>
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
<h1>Select PinBoard</h1>
<br><br>
<font size="3" color="red">*Images can be Pinned Only to Existing Pinboards. Go back to create New pinboard.</font>
<form name='form5' action="Add_Repin.php" method="POST">
<ul><img src="<?php echo $pic_url;?>"></ul>
  Tag: <input type="text" name="desc" id="keyword width="30">
  <br><br>
  PinBoard: <select name="board">
           <?php
                  //echo Pinboard names in Select Option
                  for($i = 0; $i < count($board_arr); $i++)
                  {
                     echo "<option value=\"{$board_arr[$i][0]}\">{$board_arr[$i][1]}</option>";
                  }

            unset($board_arr);
            ob_end_flush();
            ?>
                  </select>
   <input type="Submit" value="Select"><br><br>
</form>
</div>
</div>
</body>
</html>

