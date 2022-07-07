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
	$week = $_POST['week'];
	$area = $_POST['area'];
	$pnum = $_POST['pnum'];
	$cqty = $_POST['cqty'];
	if(isset($_POST['ucqty']))
	{
		$ucqty = $_POST['ucqty'];
		mysqli_query($con,"UPDATE commit SET qty='$ucqty' where pnum='$pnum' and week='$week' and foremac='$area' and qty='$cqty'");
	}
	else
	{
		mysqli_query($con,"INSERT INTO `commit` (`week`, `pnum`, `foremac`, `qty`, `issuedqty`) VALUES ('$week', '$pnum', '$area', '$cqty', '0')");
		echo "INSERT INTO `commit` (`week`, `pnum`, `foremac`, `qty`, `issuedqty`) VALUES ('$week', '$pnum', '$area', '$cqty', '0')";
	}
	if(date("D")!="Sat")
	{
		mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'COMMIT UPDATED', 'PART No:($pnum) : $cqty', '$u', '$ip')");
		echo "INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'COMMIT UPDATED', 'PART No:($pnum) : $cqty', '$u', '$ip')";
	}
	mysqli_close($con);
	header("location: weeklycommit.php");
}
?>