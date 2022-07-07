<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_POST['submit']))
{
	$date = date("Y-m-d");
	$pn = $_POST['pn'];
	$qty = $_POST['qty'];
	$type=$_POST['type'];
	
	mysqli_query($con,"INSERT INTO `npd_invoicing` (`reqdate`, `pnum`, `reqqty`, `submissiontype`, `approvedby`, `approveddate`, `invno`, `cancelrequest`, `canceldate` ) VALUES ('$date', '$pn', '$qty', '$type', '', '', '', '', '' )");
	
	header("location: inputlink.php");
}
?>