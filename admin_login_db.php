<?php

$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

$userid = $_GET['userid'];
//echo $userid;

mysqli_query($con,"UPDATE `admin1` SET status='0' WHERE userid='$userid'");
//echo "UPDATE `admin1` SET status='0' WHERE userid='$userid'";


mysqli_close($con);
header("location: admin_login.php");

?>