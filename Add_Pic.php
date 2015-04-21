<?php
ob_start();
session_start();

//Copy user id
$pb_user = $_SESSION['SESS_userid'];

$image_name=$_GET["param"];

// define variables and set to empty values
        $page_url = "http://localhost/ypadman/Sites/dpcs";
        $thumb_image_url = "Thumbnails/".$image_name;
        $upload_image = "/Users/ypadman/Sites/dpcs/Thumbnails/".$image_name;
  if(!empty($image_name))
  {
     $con=mysqli_connect("127.0.0.1","app1","apptest1","test");

     // Check connection
     if (mysqli_connect_errno($con))
     {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
     }
     //Create query
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
     }
     mysqli_close($con);

     //Call php file to add pin
     $_SESSION['SESS_picid']=$pic_id;
     //echo $_SESSION['SESS_picid'];
     $_SESSION['SESS_timage'] = $thumb_image_url;
     //session_write_close();

     header("Location: Select_Board.php");
     exit();


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

