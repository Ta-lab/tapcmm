<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
date_default_timezone_set('Asia/Kolkata');
$dt=date("Y-m-d");
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_POST['submit']))
{
	$pnum = $_POST['pnum'];
	
	mysqli_query($con,"INSERT INTO `npdparts` (`pnum`) VALUES ('$pnum')");
	
	mysqli_close($con);
	header("location: npdparts.php");
}
?>