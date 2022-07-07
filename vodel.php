<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$dt=date("Y-m-d");
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_GET['rid']) && $_GET['rid']!="" )
{
	$d=$_GET['rid'];
	mysqli_query($con,"DELETE FROM `demandmaster` where rno='$d'");
}
header("location: vssorder.php");
?>