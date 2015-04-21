<?php
ob_start();
session_start();
?>
<html>
<body>

<?php
// define variables and set to empty values
   $picErr = "";
   $pic_url = $pic_id = $board_name = $pin_tag = "";

//Get Session Variables
$pb_user = $_SESSION['SESS_userid'];
$pic_id = $_SESSION['SESS_picid'];
echo $pic_id;
$pic_url = $_SESSION['SESS_picurl'];

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
     echo "Cannot Pin Image without Image; Please Upload Image Again: Redirecting to Add Pin Choices";
     header("Location: Create_Pin.php");
     exit();
  }
  else if(empty($board_id))
  {
     echo "Pinboard name must be selected; Redirecting to Select Board Page";
     header("Location: Select_Board.php");
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
 
        //Create query
        $qry1 = "INSERT INTO PinnedPicture (PBoard_Id, Pic_Id, Tag, Repin_Id, Pinned_Time) VALUES ($board_id, $pic_id, '$pin_tag', NULL, current_timestamp)";
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
