<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_POST['submit']))
{
	$invno = $_POST['invno'];
	$invdt = date("Y-m-d",strtotime($_POST['invdt']));
	$pn = $_POST['pn'];
	$qty = $_POST['qty'];
	$ccode = $_POST['ccode'];
	$reason = $_POST['rsn'];
	mysqli_query($con,"INSERT INTO `inv_correction` (`invno`, `invdt`, `ccode`, `pn`, `qty`, `reason`, `apby`, `status`) VALUES ('$invno', '$invdt', '$ccode', '$pn', '$qty', '$reason', '', 'F')");
	header("location: inputlink.php");
}
?>