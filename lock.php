<?php
session_start();
if(isset($_SESSION['user']))
{
	//if($_SESSION['access']=="ALL" && $_SESSION['user']=='123' || $_SESSION['user']=='100')
	if($_SESSION['access']=="ALL" && $_SESSION['user']=='123')
	{
		$id=$_SESSION['user'];
		$activity="LOCK ERP";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
		mysqli_query($con,"UPDATE status set erp='1'");
		header("location: inputlink.php");
	}
	else
	{
		header("location: logout.php");
	}
}
else
{
	header("location: index.php");
}