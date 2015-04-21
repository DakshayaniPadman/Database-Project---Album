<?php
ob_start();
session_start();
?>
<html>
<body>

<?php
// define variables and set to empty values
   $picErr = "";
   $pic_id = $board_name = $pin_tag = "";

//Get Session Variables
$pb_user = $_SESSION['SESS_userid'];
$pic_id = $_SESSION['SESS_picid'];
$pin_id = $_SESSION['SESS_pinid'];

//Get POST method variables
  $board_id = $_POST["board"];
  $pin_tag = $_POST["desc"];

//Mysql variables
  $host="127.0.0.1";
  $user="app1";
  $pswd="apptest1";
  $db="test";

  if(empty($pic_id))
  {
     echo "Cannot Pin Image without Image;";
     header("Location: likecomments.php?pinid=$pin_id&picid=$pic_id");
     exit();
  }
  else if(empty($board_id))
  {
     echo "Pinboard name must be selected; Redirecting to Select Board Page";
     header("Location: Select_Board_Repin.php");
     exit();
  }
  else
  {
        //We have enough information to insert data into PinnedPicture table.
 
        $con=mysqli_connect("$host","$user","$pswd","$db");

        // Check connection
        if (mysqli_connect_errno($con))
        {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $qry = "SELECT PBoard_Name FROM PinBoard WHERE PBoard_Id='$board_id'";
        $result=mysqli_query($con,$qry);

        //Check whether the query was successful or not
        if(!$result)
        {
           echo "Could not sucessfully run query($qry) from db:".mysqli_error($con);
           exit();
        }
        else
        {
           $row = mysqli_fetch_array($result);
           $board_name = $row[0];
        }
 
        //Select The original pin id for repin value
        $qry2 = "SELECT IFNULL(Repin_Id,0) FROM PinnedPicture WHERE Pin_Id = $pin_id";
        $result2 = mysqli_query($con,$qry2);

        if(!empty($result2) && mysqli_num_rows($result2)>0)
        {
           $row = mysqli_fetch_array($result2);
           if($row[0] != 0)
              $pin_id = $row[0];
        }

        //Create query
        $qry1 = "INSERT INTO PinnedPicture (PBoard_Id, Pic_Id, Tag, Repin_Id, Pinned_Time) VALUES ($board_id, $pic_id, '$pin_tag', $pin_id, current_timestamp)";
        $result1=mysqli_query($con,$qry1);

        //Check whether the query was successful or not
        if(!$result1)
        {
           echo "Could not sucessfully run query($qry1) from db:".mysqli_error($con);
           exit();
        }

        //Insert was Successful
        echo "insert successful";

        //Free sql connections
        mysqli_free_result($result1);
        mysqli_close($con);

        //Unset session variables
        header("Location: Created_Pinboard.php?param=$board_name&pboardid=$board_id");
        exit();
   }

//PHP FUNCTIONS
function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
ob_end_flush();
?>

</body>
</html>
