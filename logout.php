<?php
session_start();
if(isset($_SESSION['user']))
{
	$id=$_SESSION['user'];
	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	if(!$con)
		die(mysqli_error());
	date_default_timezone_set('Asia/Kolkata');
	$time=date("Y-m-d g:i:s a");
	mysqli_query($con,"UPDATE admin1 set status='0',lastact='$time' where userid='$id'");
	unset($_SESSION['user']);
	unset($_SESSION['username']);
	unset($_SESSION['access']);
	unset($_SESSION['ip']);
	session_destroy();
}
header("location: index.php?err1=3");
?>