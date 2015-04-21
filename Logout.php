<?php
session_start();
//Destroy session, user logged out
session_destroy();
header("Location: Login.php");
?>
