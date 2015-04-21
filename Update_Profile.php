<?php
//start session
ob_start();
session_start();

//Check whether the session variables are present
 if(!isset($_SESSION['SESS_userid']) || (trim($_SESSION['SESS_userid']) == ''))
 {
	exit();
 }
session_save_path();
//ob_end_flush();
?>
<html>
<head>
	<link href="style.css" rel="stylesheet" type="text/css">
	<h3 style="background-color:hsla(120,100%,25%,0.3)"> Welcome <?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?>! </h3>
	<style>
		.error {color: #FF0000;}
	</style>
</head>
<body>
<?php
// define variables and set to empty values
   	
   	$passwordErr = $emailErr = $errormessage= $msg="";
   	$password = $email = "";
   	

if ($_SERVER["REQUEST_METHOD"] == "POST"){

	if (empty($_POST["password"])&& empty($_POST["email"])){
     	$errormessage = "You should enter at least one value.";
     }
     
	if (!empty($_POST["email"])){
		$email = test_input($_POST["email"]);
     // check if e-mail address syntax is valid
     if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
       {
       $emailErr = "Invalid email format";
       }
    
	}
	if (!empty($_POST["password"])){
		$password = test_input($_POST["password"]);
     	//verify the length of password
    	 if( strlen($password) < 6 ) {
			$passwordErr = 'Password need to have at least 6 characters!';
		}
 
		if( strlen($password) > 20 ) {
			$passwordErr = 'Password needs to have less than 20 characters!';
		}
 
		if( !preg_match("#[0-9]+#", $password) ) {
			$passwordErr = 'Password must include at least one number!';
		}
 
		if( !preg_match("#[a-z]+#", $password) ) {
			$passwordErr = 'Password must include at least one letter!';
		}
 
		if( !preg_match("#[A-Z]+#", $password) ) {
			$passwordErr = 'Password must include at least one uppercase letter!';
		}
     }
     
     if(empty($passwordErr) && empty($emailErr) && empty($errormessage)){

		$con=mysqli_connect("127.0.0.1","community","monu@8137","test");

		// Check connection
		if (mysqli_connect_errno($con))
  		{
  			echo "Failed to connect to MySQL: " . mysqli_connect_error();
  		}
		//Create query
		$qry="SELECT * FROM Users WHERE Email='$_POST[email]'";
		$result=mysqli_query($con,$qry);
		if(!empty($result) && mysqli_num_rows($result)>0){
			$emailErr="This Email already exist, Please enter different email id";
		}
		if(empty($emailErr)){
			//Update Profile
		$qry= "Update Users set ";
		if(!empty($_POST["email"])){
		 	$qry=$qry."Email='".$_POST['email']."'";
		 	if(!empty($_POST["password"])){
		 		$qry=$qry.",";
		 	}
		 }
		if(!empty($_POST["password"])){
			$qry=$qry."password='".$_POST['password']."'";
			}
		$qry=$qry." where user_id='".$_SESSION['SESS_userid']."'";
		$result=mysqli_query($con,$qry);
		$msg="Successfully Updated";
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

?>

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
&nbsp;&nbsp;<span class="error"><?php echo $errormessage;?></span><br>
&nbsp;&nbsp;<span><?php echo $msg;?></span><br><br>
<form name='loginform' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
&nbsp;&nbsp;New Password:&nbsp;<input type="password" name="password"/>
<span class="error"><?php echo $passwordErr;?></span>
<br><br>
&nbsp;&nbsp;New Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="email"/>
<span class="error"><?php echo $emailErr;?></span>
<br><br>
&nbsp;&nbsp;<input type="submit" value="Update"/>
</form>
</body>
</html>
