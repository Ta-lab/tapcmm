<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_POST['submit']))
{
	header("location: inputlink.php");
}
if(isset($_GET['pnum']) && $_GET['pnum']!="")
{
	$pnum = $_GET['pnum'];
	$appno = $_GET['appno'];
	$approveddate = date("Y-m-d");
	
	$stat = $_GET['stat'];
	if($stat==0)
	{
		mysqli_query($con,"UPDATE npd_invoicing SET approvedby='',approveddate='0000-00-00' where pnum='$pnum' and appno=$appno" );
	}
	else
	{
		mysqli_query($con,"UPDATE npd_invoicing SET approvedby='$u',approveddate='$approveddate' where pnum='$pnum' and appno=$appno" );
	}
	header("location: npd_inv_app.php");
}
?>