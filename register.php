<html>
<head>
	<style>
		.error {color: #FF0000;}
	</style>
</head>
<body>
<h3>Welcome To Sign Up Page!</h3>
<?php
// define variables and set to empty values
   	$useridErr = $passwordErr = $passErrc = $emailErr = $firstnameErr = $lastnameErr = $passMathErr= $dobErr = $genErr = "";
   	$userid = $password = $pass_conf = $email = $firstname = $lastname = $dob = $sex = "";
   	$errormessage="";
   	//$sex = 'F';
   //	$dob='1980-06-06';
   	
   	//default variables
 		 $host="127.0.0.1";
 		 $user="app1";
 		 $pswd="apptest1";
 		 $db="test";
   
   	if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
		if (empty($_POST["userid"]))
     		{
    	 		$useridErr = "User Id is required";
     		}else{
     			$userid = test_input($_POST["userid"]);
     			$con=mysqli_connect("$host","$user","$pswd","$db");

					// Check connection
				if (mysqli_connect_errno($con))
  					{
  						echo "Failed to connect to MySQL: " . mysqli_connect_error();
  					}
  				$qry="Select * from Users where User_ID='$userid'";
  				$result=mysqli_query($con,$qry);
  				if($result && mysqli_num_rows($result) > 0) {
  					$useridErr=" User Id already present in database";
                }       	
     		}
		if (empty($_POST["firstname"]))
     		{
    	 		$firstnameErr = "First Name is required";
     		}
     	if (empty($_POST["lastname"]))
     		{
    	 		$lastnameErr = "Last Name is required";
     		}
   		if (empty($_POST["email"]))
     		{
    	 
    		 $emailErr = "Email is required";
    		 }
   		else
     		{
     		$email = test_input($_POST["email"]);
     		// check if e-mail address syntax is valid
     		if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
       			{
       				$emailErr = "Invalid email format";
       			}else{
       				$con=mysqli_connect("$host","$user","$pswd","$db");

					// Check connection
				if (mysqli_connect_errno($con))
  					{
  						echo "Failed to connect to MySQL: " . mysqli_connect_error();
  					}
  				$qry="Select * from Users where Email='$email'";
  				$result=mysqli_query($con,$qry);
  				if($result && mysqli_num_rows($result) > 0) {
  					$emailErr="Email already present in database";
                }     
       			}
     		}
     	if (empty($_POST["password"]))
     		{
     			$passwordErr = "Password is requireds";
     		}
   		else
     		{
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
     		if (empty($_POST["pass_conf"]))
   				{
        			$passErrc = "Confirm password is required!";
    			}
    		else
    			{
        			$pass_conf = test_input($_POST["pass_conf"]);
   				}
   		
    		if($password != $pass_conf){
        	$passErrc= "Confirm Password Should match the Passwords!";
        	// die($passMathErr);
    		} 	
    		
    		if (empty($_POST["dob"]))
     		{
     			$dobErr = "DOB is required";
     		}else{
     			
     			if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST["dob"])){
     				$dobErr=" Date format is invalid";
     			}
     		}
     		
     		if (empty($_POST["sex"]))
     		{
     			$genErr = "Gender is required";
     		}
    		
        	 	// Create connection
        	 	   	if(empty($passwordErr) && empty($emailErr) && empty($firstnameErr) && empty($lastnameErr) && empty($useridErr) && empty($passErrc) && empty($dobErr) && empty($genErr)){

                    $con=mysqli_connect("$host","$user","$pswd","$db");

					// Check connection
					if (mysqli_connect_errno($con))
  						{
  							echo "Failed to connect to MySQL: " . mysqli_connect_error();
  						}
					//Create query	
					$qry="insert into Users"."(User_Id,Password,First_Name,Last_Name,Email,Gender,DOB,Creation_Time,Last_Updated_Time)".
					"values('$_POST[userid]','$_POST[password]','$_POST[firstname]','$_POST[lastname]','$_POST[email]','$_POST[sex]','$_POST[dob]',current_timestamp,current_timestamp)";
					
					$result=mysqli_query($con,$qry);
 					if(!($result)){
 						die('Could not enter data: ' .mysqli_error($con));
					}
					echo "Entered data successfully\n";
					//header("location: login.php");
					mysqli_close($con);
					
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

<p><span class="error">* required field.</span></p>
<span class="error"><?php echo $errormessage;?></span><br>
<form name='register' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
First Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<input type='text' name='firstname'/>
	 <span class="error">* <?php echo $firstnameErr;?></span>
<br><br>
Last Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type='text' name='lastname'/>
	 <span class="error">* <?php echo $lastnameErr;?></span>
<br><br>
User Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    
<input type='text' name='userid'/>
	 <span class="error">* <?php echo $useridErr;?></span>
<br><br>
Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
<input type='text' name='email'/>
    <span class="error">* <?php echo $emailErr;?></span>
<br><br>
Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  
<input type='password' name='password' id='password' maxlength="20" />
    <span class="error">* <?php echo $passwordErr;?></span>
<br><br>
Confirm Password:&nbsp;&nbsp;&nbsp;&nbsp; <input type='password' name='pass_conf' id='pass_conf' maxlength="20" />
    <span class="error">* <?php echo $passErrc;?></span>
<br><br>
DOB:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
 <input type='text' name='dob'/>&nbsp;&nbsp;(YYYY-MM-DD)
 <span class="error">*<?php echo $dobErr;?></span>
 <br><br>
Gender:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
<input type='radio' name='sex' value='M'>Male
<input type='radio' name='sex' value='F'>Female
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="error">* <?php echo $genErr;?></span>
<br><br>
<input type='submit' name='Submit' value='Submit' /><br><br>
<a href='Login.php'>Back To Login!</a>
</form>
</body>
</html>
