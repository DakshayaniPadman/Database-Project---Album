<?php
ob_start();
session_start();
?>

<?php
//default variables
 		 $host="127.0.0.1";
 		 $user="app1";
 		 $pswd="apptest1";
 		 $db="test";
 		 
// define variables and set to empty values
        $passwordErr = $emailErr = "";
        $password = $email = "";
        $errormessage="";

//Checking Input Parameters
  if ($_SERVER["REQUEST_METHOD"] == "POST")
                {

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
       }
     }
     if (empty($_POST["password"]))
     {
     $passwordErr = "Password is required";
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

     // Create connection
        if(empty($passwordErr) && empty($emailErr)){
				
                $con=mysqli_connect("$host","$user","$pswd","$db");

                // Check connection
                if (mysqli_connect_errno($con))
                {
                        echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }
                //Create query
                $qry="SELECT user_id, first_Name, last_Name FROM Users WHERE Email='".$email."' AND Pasword='".$password."'";
                $result=mysqli_query($con,$qry);

                //Check whether the query was successful or not
                if($result) {
                        if(mysqli_num_rows($result) > 0) {
                        //Login Successful
                        session_regenerate_id();
                        $user = mysqli_fetch_assoc($result);
                      //Setting Cookies for 1 hour  
                      $expire=time()+60*60*24*30;
                      setcookie($email,$password, $expire);
					 // setcookie('$pass',$password, $expire);
                        
                        $_SESSION['SESS_userid'] = $user['user_id'];
                        $_SESSION['SESS_fname'] = $user['first_Name'];
                        $_SESSION['SESS_lname'] = $user['last_Name'];
                        $_SESSION['SESS_email'] = $email;
                        //session_write_close();
                        header("Location: Welcome.php");
                        exit();
                }else {
                        //Login failed
                        $errormessage = 'Email and password not present in database';
                      
                        }
                }else {
                        $errormessage="Email/Password is not valid";
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
       <script>
	function myFunction()
	{
		if(document.getElementById("email")!=null && document.getElementById("email")!=""){
			var cookieemail=document.getElementById("email").value;
			var cookiepass = getCookie(cookieemail);
			document.form1.password.value= cookiepass;
		}
		
	}
	
	function getCookie(c_name)
{
var c_value = document.cookie;
var c_start = c_value.indexOf(" " + c_name + "=");
if (c_start == -1)
  {
  c_start = c_value.indexOf(c_name + "=");
  }
if (c_start == -1)
  {
  c_value = null;
  }
else
  {
  c_start = c_value.indexOf("=", c_start) + 1;
  var c_end = c_value.indexOf(";", c_start);
  if (c_end == -1)
  {
c_end = c_value.length;
}
c_value = unescape(c_value.substring(c_start,c_end));
}
return c_value;
}
	</script>
</head>
<body>
<h1>Welcome To Pinterest!</h1>
<p><span class="error">* required field.</span></p>
<span class="error"><?php echo $errormessage;?></span><br><br>
<form name='form1' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

        Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="email" id="email" onchange="myFunction()">
         <span class="error">* <?php echo $emailErr;?></span>
         <br><br>
        Password: 
       <input type="password" name="password" />
         <span class="error">* <?php echo $passwordErr;?></span>
         <br><br>
        <input type="submit" value="Login"><br><br>

        <a href="forgotpwd.php">Forgot your password?</a>&nbsp;

        <a href="register.php">Sign-Up</a>


</form>
</body>
</html>

