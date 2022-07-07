<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_GET['rid']) && $_GET['rid']!="" )
{
	$d=$_GET['rid'];
	mysqli_query($con,"DELETE FROM `downtime` where rid='$d'");
}
header("location: downtime.php");
?>