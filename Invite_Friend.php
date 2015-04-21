<?php
ob_start();
session_start();

//Check whether the session variables are present
 if(!isset($_SESSION['SESS_userid']) || (trim($_SESSION['SESS_userid']) == ''))
 {
	exit();
 }
session_save_path();


 	$emailErr = $message = "";
 		
 //default variables
  $host="127.0.0.1";
  $user="app1";
  $pswd="apptest1";
  $db="test";
 		
if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		
   		if (empty($_POST["invitebyemail"]))
     	{
    	 
    	 	$emailErr = "Email is required";
     	}
   else
     	{
     		$email = test_input($_POST["invitebyemail"]);
     		// check if e-mail address syntax is valid
    	 if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
       		{
       			$emailErr = "Invalid email format";
       		}
     	}
     	// Create connection
	if(empty($emailErr)){

		if($_SESSION['SESS_email']==$_POST["invitebyemail"]){
			$message= "You can not invite yourself.Enter different Email!";
		}else{

   		$con=mysqli_connect("$host","$user","$pswd","$db");

		// Check connection
		if (mysqli_connect_errno($con))
  		{
  			echo "Failed to connect to MySQL: " . mysqli_connect_error();
  		}
		//Create query
		$qry="SELECT user_id FROM Users WHERE Email='$_POST[invitebyemail]'";
		$result=mysqli_query($con,$qry);
		//Check whether the query was successful or not
		if(!empty($result) && mysqli_num_rows($result)>0){
		  $user = mysqli_fetch_assoc($result);
		  
		
		  $query="select * from Friend where User_Id in ('".$user['user_id']."','". $_SESSION['SESS_userid']."') and Friend_Id in ('".$user['user_id']."','". $_SESSION['SESS_userid']."')";
		 
		  $result1=mysqli_query($con,$query);
		   if(!empty($result1) && mysqli_num_rows($result1)>0){
		      $res = mysqli_fetch_assoc($result1);
		      if($res['Friendship_Status']=="Pending"){
		        $message="You have already sent friend request to ".$_POST['invitebyemail']." and status is pending.";
		      	//echo "You have already sent request to ".$_POST['invitebyemail']." and it is pending";
		      }
		      else if($res['Friendship_Status']=="Accepted"){
		      $message= $_POST['invitebyemail']." is already your friend.";
		      }
		      else if($res['Friendship_Status']=="Rejected"){
		       $message= $_POST['invitebyemail']." has already rejected your friendship.";
		      }
		    }
		   else{
		    $query1="insert into Friend"."(User_Id,Friend_Id,Friendship_Status,Friendship_Time)"."values ('".$user['user_id']."','".$_SESSION['SESS_userid']."', 'Pending', current_timestamp)";
		    $result2=mysqli_query($con,$query1);
		    $message= "request has been sent to email $_POST[invitebyemail]"; 
		   }
		   
		}else{
			$message= "Email does not exist in Pinterest Database.";
		}
		
     }
     }
  }  
    function test_input($data)
		{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
		}
ob_end_flush();
    ?>
<html>
	<head>
	<style>
		.error {color: #FF0000;}
	</style>
		<link href="style.css" rel="stylesheet" type="text/css">
		<h3 style="background-color:hsla(100,100%,25%,0.3)"><?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?>! </h3>
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
<a href="Add_Streams.php" style="color:#000000">Create FollowStream</a><br> 
<a href="Follow_Boards.php" style="color:#000000">Follow Boards</a> <br>
 <a href="Add_pinboard.php" style="color:#000000">Create Pinboard</a><br> 
<a href="Create_Pin.php" style="color:#000000">Add a Pin</a><br> 
<a href="Invite_Friend.php" style="color:#000000">Invite Friends</a><br>
<a href="Update_Profile.php" style="color:#000000">Update Profile</a><br>
 </div>


		<br>
		<p><span class="error">*required field.</span></p>
		&nbsp;&nbsp;<span class="error"><?php echo $message; ?></span><br>
		<form name='inviteform' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		&nbsp;&nbsp;Invite By Email: &nbsp;<input type="text" name="invitebyemail"/>
		<span class="error">* <?php echo $emailErr;?> <br><br>
		&nbsp;&nbsp;<input type="submit" value="Invite"/>
		
		</form>
		
	</div>
	</body>
	
</html>
