<?php
$dc= $_GET['dc'];
$s=strlen($dc);
$dc1 = substr($dc,3,$s);
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if($dc1!="" && $dc!="")
{
	mysqli_query($con,"DELETE FROM dc_det WHERE dcnum='$dc1'");
	mysqli_query($con,"DELETE FROM d12 WHERE rcno='$dc'");
	mysqli_query($con,"DELETE FROM d11 WHERE rcno='$dc'");
}
header("location: inputlink.php");
?>