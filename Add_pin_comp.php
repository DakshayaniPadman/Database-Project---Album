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
$pb_user='dp1688';
// define variables and set to empty values
        $uploadErr = "";
        $page_url = "http://localhost/~ypadman/dpcs";
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if ((($_FILES["file"]["type"] == "image/gif") 
          || ($_FILES["file"]["type"] == "image/jpeg")
          || ($_FILES["file"]["type"] == "image/jpg")
          || ($_FILES["file"]["type"] == "image/pjpeg")
          || ($_FILES["file"]["type"] == "image/x-png")
          || ($_FILES["file"]["type"] == "image/png"))
          && in_array($extension, $allowedExts))
        {
           if ($_FILES["file"]["error"] > 0)
           {
              echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
           }
           else
           {
              //echo "Upload: " . $_FILES["file"]["name"] . "<br>";
              //echo "Type: " . $_FILES["file"]["type"] . "<br>";
              //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
              //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

              move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
              //echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
           }
        }
        else
           $uploadErr = "Invalid File Selected";

        if(empty($uploadErr))
        {
           $upload_path = "upload/".$_FILES["file"]["name"];

           if(!preg_match('/\/([^\/]+\.[a-z]{3,4})$/i', $upload_path, $matches))
           {
              die('Some Error in reading image name');
           }
           $image_name = strToLower($matches[1]);

           //Create Thumbnail
           createThumbnail("upload/", "Thumbnails/", $image_name);

           header("Location: Add_Pic.php?param=$image_name");
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


ob_end_flush();
?>

<h1>Upload a File</h1>
<p><span class="error">* required field.</span></p><br><br>
<form name='fileupload' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">

Select a file to upload: <br>
        <input type="file" name="file">
        <br><br>
        <input type="submit" value="Upload File">
        <span class="error">* <?php echo $uploadErr;?></span>

</form>
</div>
</div>
</body>
</html>

