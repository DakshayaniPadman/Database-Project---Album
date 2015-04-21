<?php
ob_start();
session_start();
//Check whether the session variables are present
 if(!isset($_SESSION['SESS_userid']) || (trim($_SESSION['SESS_userid']) == ''))
 {
        exit();
 }
 //Get session variable
	$pb_user=$_SESSION['SESS_userid'];
	if(!empty($_GET['pinid']) && $_GET['pinid']!=0){
		$_SESSION['SESS_pinid'] = $_GET['pinid'];
	}
	if(!empty($_GET['picid']) && $_GET['picid']!=0){
		$_SESSION['SESS_picid'] = $_GET['picid'];
	}
?>

<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<h3 style="background-color:hsla(120,100%,25%,0.3)"> Welcome <?php echo "{$_SESSION['SESS_fname']} {$_SESSION['SESS_lname']}"?>! </h3>
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
<?php

		//default variables
 		 $host="127.0.0.1";
 		 $user="app1";
 		 $pswd="apptest1";
 		 $db="test";
 		 			
				//create connection
   				$con=mysqli_connect("$host","$user","$pswd","$db");
				// Check connection
				if (mysqli_connect_errno($con))
  				{
  					echo "Failed to connect to MySQL: " . mysqli_connect_error();
  				}
  				//check for like
				if(!empty($_POST['like']))
				{
					$qry="insert into Likes (User_Id,Pin_Id) values ('".$_SESSION['SESS_userid']."','".$_SESSION['SESS_pinid']."')";
					mysqli_query($con,$qry);
				}
				else if(!empty($_POST['unlike']))
                                {
                                        $qry="delete from Likes where User_Id ='".$_SESSION['SESS_userid']."' and Pin_Id =".$_SESSION['SESS_pinid'];
                                        mysqli_query($con,$qry);
                                        echo "You have unliked the picture\n";
                                }
                else if(!empty($_POST['repin']))
				{
                                   header("Location: Select_Board_Repin.php");
                                   exit();
				}
                else if(!empty($_POST['DelPin']))
                    {
                   		 $qry="delete from PinnedPicture where Pin_Id =".$_SESSION['SESS_pinid'];
                   		 mysqli_query($con,$qry);
                        echo "Your pin has been successfully deleted!";
                        
                    }
				else if(!empty($_POST['comment']) && $_POST['comment'])
				{
					//Create query
					$qry2="insert into Comments (User_Id,Pin_Id,Comment,Comment_Time) values ('$_SESSION[SESS_userid]','$_SESSION[SESS_pinid]','$_POST[commentText]',current_timestamp)";
					$result2=mysqli_query($con,$qry2);
				
				}
?>
<br><br>
<?php
   //Get Image URL for display
   $qry="select Image_URL, Tag from PinnedPicture Natural Join Picture where Pin_Id =".$_SESSION['SESS_pinid'];
   $result=mysqli_query($con,$qry);
   //Check whether the query was successful or not
   if(!empty($result) && mysqli_num_rows($result)>0)
   {
       $row = mysqli_fetch_array($result);
       $_SESSION['SESS_timage']=$row[0];
       echo "<ul><img src=\"{$row[0]}\"></ul>";
       echo "<b>Description:</b> {$row[1]}<br><br>";
   }
?>
<form name="likecommentsform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
<?php 
   //Get count of Likes
   $qry0="select count(*) from Likes Natural join (select pin_id from PinnedPicture where Pic_Id =".$_SESSION['SESS_picid'].")A";
   $result0=mysqli_query($con,$qry0);
   //Check whether the query was successful or not
   if(!empty($result0) && mysqli_num_rows($result0)> 0)
   { 
       $row = mysqli_fetch_array($result0);
       echo "<font size=\"3\" color=\"green\">$row[0] Likes</font><br>";
   }

   //Check if user has liked the picture already
   $qry="select * from likes where User_Id='".$_SESSION['SESS_userid']. "' and Pin_Id =".$_SESSION['SESS_pinid'];
   $result=mysqli_query($con,$qry);
   //Check whether the query was successful or not
   if(!empty($result) && mysqli_num_rows($result)>0)
   {
       echo "You like this picture.\n";
       echo "<input type=\"submit\" name=\"unlike\" value=\"UnLike\"/>&nbsp;";
   }
   else
      echo "<input type=\"submit\" name=\"like\" value=\"Like\"/>&nbsp;";

ob_end_flush();
?>
<input type="submit" name="repin" value="Repin"/>
<?php
   //Display link to delete a pin only if you are the owner of the pin
   $qry1="select User_Id, Pinboard.PBoard_Id, PBoard_Name from PinnedPicture JOIN Pinboard USING(PBoard_Id) where Pin_Id =".$_SESSION['SESS_pinid'];
   $result1=mysqli_query($con,$qry1);
   //Check whether the query was successful or not
   if(!empty($result1) && mysqli_num_rows($result1)>0)
   {
       $row = mysqli_fetch_array($result1);
       $pin_owner = $row[0];
       if($pin_owner == $_SESSION['SESS_userid'])
       {
          echo "<input type=\"submit\" name=\"DelPin\" value=\"Delete Pin\"/><br><br>";
       }
   }
?>
<font size="3" color="Black"><b>Comments</b></font><br>

	<?php
			//select from comment and show that comments
			//check pinboard setting
			//Create query
			$qry3="select Comment,User_Id from Comments where Pin_Id = ".$_SESSION['SESS_pinid'];
			$result3=mysqli_query($con,$qry3);
			if(!empty($result3) && mysqli_num_rows($result3)>0){
				while($row = mysqli_fetch_array($result3))
  					{
  					echo $row['User_Id'].": ";
  					echo $row['Comment'];
  					echo "<br><br>";
  					}
			}
			$qry4="select Access_Status,User_Id from PinBoard where PBoard_Id ='".$_SESSION['SESS_pbi']."'";
			$result4=mysqli_query($con,$qry4);
			if(!empty($result4) && mysqli_num_rows($result4)>0){
				$row = mysqli_fetch_array($result4);
  					
  					//echo "pinboard setting is ".$row['Access_Status'];
  					
  					//echo "<br>";
  					
			}
			$commentAllow=false;
			if($_SESSION['SESS_userid']==$row['User_Id']){
				$commentAllow=true;
			}else if($row['Access_Status']==1){
				$qry5="select * from Friend where User_Id in ('".$_SESSION['SESS_userid']."', '".$row['User_Id']."') and Friend_Id in ('".$_SESSION['SESS_userid']."', '".$row['User_Id']."') and Friendship_Status='Accepted'";
				$result5=mysqli_query($con,$qry5);
				if(!empty($result5) && mysqli_num_rows($result5)>0){
					$commentAllow=true;
				}
			}
			else if($row['Access_Status']==0){
				$commentAllow=true;				
			}
			
			if($commentAllow){
				?>
				<input type="text" name="commentText" style="width: 300px;"/>
				<input type="submit" name="comment" value="comment"/>
					<?php
			}
		?>
	
</form>
</div>
</div>
</body>
</html>
