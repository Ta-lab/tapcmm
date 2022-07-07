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
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	echo "connection failed";
if(isset($_GET['id']))
{
	$id=$_GET['id'];
	$rcno=$_GET['rcno'];
	$prev = "SELECT * from d12 where rowid='$i'";
	$resprev = $con->query($prev);
	$prevrow = mysqli_fetch_array($resprev);
	if($prevrow['qtyrejected']!='')
	{
		$t=$prevrow['qtyrejected'];
		mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'REJECTION ($t) ENTRY DELETED', '$u', '$ip')");
		mysqli_query($con,"DELETE FROM d12 WHERE rowid='$id'");
	}
	else if($prevrow['partreceived']!='')
	{
		$t=$prevrow['partreceived'];
		mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'RECEIVED ($t) ENTRY DELETED', '$u', '$ip')");
		mysqli_query($con,"DELETE FROM d12 WHERE rowid='$id'");
	}
	else
	{
		mysqli_query($con,"DELETE FROM d12 WHERE rowid='$id'");
	}
	header("Location: i25.php?rcno=$rcno");
}
if(isset($_GET['status']))
{
	$rcno=$_GET['rc'];
	$s=$_GET['status'];
	if($s=="0")
	{
		mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'RCNO RE-OPENED BY ADMIN', '$u', '$ip')");
		$s="0000-00-00";
		$rmk='';
	}
	else
	{
		mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'RCNO CLOSED BY ADMIN', '$u', '$ip')");
		$s=date("Y-m-d");
		$rmk="CLOSED BY ADMIN";
	}
	if(isset($_GET['rsn']))
	{
		$rmk=$_GET['rsn'];
		$rmk=$rmk." Verified By : $u";
	}
	mysqli_query($con,"UPDATE d11 SET closedate='$s',rmk='$rmk' WHERE rcno='$rcno'");
	if(isset($_GET['rsn']))
	{
		header("Location: i26.php?rcno=$rcno");
	}
	else
	{
		header("Location: i25.php?rcno=$rcno");
	}
}
?>