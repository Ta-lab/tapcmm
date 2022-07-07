<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$dt=date("Y-m-d");
if(isset($_SESSION['user']) && isset($_SESSION['access']) && $_SESSION['access']=="ALL")
{
	
}
else
{
	header("location: index.php");
}
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_POST['rcno']))
{
	$r=substr($_POST['rcno'],0,1);
	if($r=="A" || $r=="B")
	{
		header("location: addpart.php?rcno=$_POST[rcno]&pnum=$_POST[partnumber]'");
	}
	else
	{
		header("location: partchange.php");
	}
}

if(isset($_POST['rcno']) && $_POST['rcno']!="" && isset($_POST['pnum']) && $_POST['pnum']!="")
{
	$rc =$_POST['rcno'];
	$pn=$_POST['pnum'];
	$prev = "SELECT * from d11 where rcno='$rc'";
	$resprev = $con->query($prev);
	$prevrow = mysqli_fetch_array($resprev);
	mysqli_query($con,"UPDATE d12 set pnum='$pn' where rcno='$rc'");
	mysqli_query($con,"UPDATE d11 set pnum='$pn' where rcno='$rc'");
	if(mysqli_affected_rows($con)==1)
	{
		$t=$prevrow['pnum'];
		mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rc', 'PART NO CHANGE: $t TO $pn', '$u', '$ip')");
	}
	mysqli_close($con);
	header("location: inputlink.php"); 
}
?>