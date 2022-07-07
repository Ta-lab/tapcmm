<?php
$appno = $_GET['appno'];
$rejreason = $_GET['rejreason'];
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if($appno!="")
{
	mysqli_query($con,"UPDATE npd_invoicing SET reject='REJECTED',rejreason='$rejreason' WHERE appno='$appno'");	
}
header("location: inputlink.php");
?>
