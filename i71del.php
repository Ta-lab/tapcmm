<?php
$con=mysqli_connect('localhost','root','Tamil','purchasedb');
if(!$con)
	die(mysqli_error());
$ponum=$_GET['ponum'];
$sno=$_GET['sno'];
mysqli_query($con,"DELETE FROM pogoodsinfo WHERE ponum='$ponum' and sno='$sno'");
mysqli_query($con,"UPDATE pogoodsinfo SET sno=sno-1 WHERE ponum='$ponum' and sno>'$sno'");
header("location: i71.php?ponum=$ponum");
?>