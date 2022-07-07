<?php
$appno = $_GET['appno'];
$canceldate = date("Y-m-d");
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if($appno!="")
{
	mysqli_query($con,"UPDATE npd_invoicing SET cancelrequest='CANCELLED',canceldate='$canceldate' WHERE appno='$appno'");	
}
header("location: inputlink.php");
?>