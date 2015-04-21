<?php
ob_start();
session_start();
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
	<style>
                .error {color: #FF0000;}
        </style>
<?php
//Copy user id
$pb_user = $_SESSION['SESS_userid'];
//Mysql variables
  $host="127.0.0.1";
  $user="app1";
  $pswd="apptest1";
  $db="test";

// define variables and set to empty values
        $imageErr = $pageErr = "";
        $orig_image_url = $page_url = "";
        $upload_path = "/Users/ypadman/Sites/dpcs/Thumbnails/";
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
     if (empty($_POST["orig_image_url"]))
     {

         $imageErr = "Image URL is required";
     }
     else
     {
        $orig_image_url = test_input($_POST["orig_image_url"]);
        if(!preg_match('/\/([^\/]+\.[a-z]{3,4})$/i', $orig_image_url, $matches))
        {
           $imageErr = "Please enter correct image url";
        }
        $image_name = strToLower($matches[1]);
        echo $image_name;
        
     }
     if (empty($_POST["page_url"]))
     {

         $pageErr = "WebPage URL is required";
     }
     else
     {
        $page_url = $_POST["page_url"];
     }

     if(empty($imageErr) && empty($pageErr))
     {
        //copy from website
        echo "$path\n";
        echo $image_name;

        //Check if same image has been uploaded already by any user(to restrict duplicacy in Picture table).
        //Query is used to check if image already exists, as it could be deleted from thumbnails folder.
        $thumb_image_url = "Thumbnails/".$image_name;
 
        $con=mysqli_connect("$host","$user","$pswd","$db");

        // Check connection
        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        //Create query
        $qry = "SELECT Pic_Id FROM Picture WHERE Page_URL = '$page_url' AND Image_URL = '$thumb_image_url'";
        $result=mysqli_query($con,$qry);

        //Check whether the query was successful or not
        if(!$result)
        {
           echo "Could not sucessfully run query($qry) from db:".mysqli_error();
           exit();
        }

        echo mysqli_num_rows($result);
        if(mysqli_num_rows($result) > 0)
        {
           //Record exists, So No need to upload image to Db, just use Pic id to add pin
           $row1 = mysqli_fetch_array($result);
           $pic_id = $row1[0];
           mysqli_free_result($result);
        }
        else
        {
           //There is no record of same picture, then insert data.
       
           //shrink the picture before saving it to db.
           echo copy($orig_image_url, "Images/$image_name");

           createThumbnail("Images/", "Thumbnails/", $image_name);


           //Upload Thumbnail image to Database
           $upload_image = $upload_path.$image_name;
           $qry1 = "INSERT INTO Picture (Page_URL, Image_URL, Image) VALUES ('$page_url', '$thumb_image_url', Load_File('$upload_image'))";
           $result1 = mysqli_query($con,$qry1);

           //Check whether the query was successful or not
           if(!$result1)
           {
              echo "Could not sucessfully run query($qry1) from db:".mysqli_error($con);
              exit();
           }
           //Query was successful
           mysqli_free_result($result1);

           //Select Pic_id query is repeated to get pic-id of just inserted data.
           $qry2 = "SELECT Pic_Id FROM Picture WHERE Page_URL = '$page_url' AND Image_URL = '$thumb_image_url'";
           $result2 = mysqli_query($con,$qry2);

           //Check whether the query was successful or not
           if(!$result2)
           {
              echo "Could not sucessfully run query($qry2) from db:".mysqli_error();
              exit();
           }

           if(mysqli_num_rows($result2) > 0)
           {
              $row = mysqli_fetch_array($result2);
              $pic_id = $row[0];
              mysqli_free_result($result2);
           }
        }
        mysqli_close($con);

        //Call php file to add pin
        $_SESSION['SESS_picid']=$pic_id;
           echo "3-{$pic_id}\n";
           //echo $_SESSION['SESS_picid'];
        $_SESSION['SESS_timage'] = $thumb_image_url;
        //session_write_close();
        header("Location: Select_Board.php");
        exit();

     }
     

   }

//PHP FUNCTIONS
function createThumbnail($path_to_image_directory, $path_to_thumbs_directory, $filename)
{
	$thumbnail_width = 189;
    	$thumbnail_height = 189;

	if(preg_match('/[.](jpg)$/', $filename)) {
		$src_img = imagecreatefromjpeg($path_to_image_directory . $filename);
	} else if (preg_match('/[.](gif)$/', $filename)) {
		$src_img = imagecreatefromgif($path_to_image_directory . $filename);
	} else if (preg_match('/[.](png)$/', $filename)) {
		$src_img = imagecreatefrompng($path_to_image_directory . $filename);
	}

        $size = GetImageSize($path_to_image_directory.$filename);
        $orig_width = $size[0];
        $orig_height = $size[1];

	$thumb_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);

	imagecopyresized($thumb_image, $src_img, 0,0,0,0,$thumbnail_width,$thumbnail_height,$orig_width, $orig_height);
	imagejpeg($thumb_image, $path_to_thumbs_directory . $filename);

       //echo "image created";
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



<h1>Upload a Pin</h1>
<p><span class="error">* required field.</span></p><br><br>
<span class="error"><?php echo $errormessage;?></span><br>
<form name='form2' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">

        Image URL: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="orig_image_url">
        <span class="error">* <?php echo $imageErr;?></span>
         <br><br>
        Page URL: <input type="text" name="page_url">
        <span class="error">* <?php echo $pageErr;?></span>
        <br><br>
        <input type="submit" value="Upload"><br><br>
</form>
</div>
</div>
</body>
</html>

