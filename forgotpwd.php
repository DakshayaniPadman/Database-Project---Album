<html>
<head>
	<style>
        .error {color: #FF0000;}
    </style>
</head>
<body>
<h3>Welcome to Forgot Password Page!</h3>
<?php
	$emailErr = "";
	$email = "";
	
	//default variables
  $host="127.0.0.1";
  $user="app1";
  $pswd="apptest1";
  $db="test";
  
	if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
		
   		if (empty($_POST["email"])){
   		
    		$emailErr = "Email is required";
    	
     	}else{
    		if(empty($emailErr)){

   				$con=mysqli_connect("$host","$user","$pswd","$db");
				// Check connection
				if (mysqli_connect_errno($con))
  				{
  					echo "Failed to connect to MySQL: " . mysqli_connect_error();
  				}
				//Create query
				$qry="SELECT password FROM Users WHERE Email='$_POST[email]'";
				$result=mysqli_query($con,$qry);
				//Check whether the query was successful or not
				if(!empty($result) && mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_array($result)) {
						 echo "Your password is: ".$row['password']."<br><br>";
					}
				}else{
					$emailErr= "Email ".$_POST['email']." does not exist.Please enter again!!";
					}
				}
    
    	}
    }
?>
<span class="error"><?php echo $emailErr;?></span><br>
 <form name='forgotpwdform' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
 <br>
	Enter Your Email:<input type="text" name="email"/><br><br>
	<input type="submit" value="Submit"/>
	<a href="Login.php">Back To Login!</a>
 </form>
 
</body>
</html>
