<?php
$con=mysqli_connect('localhost','root','Tamil','purchasedb');
if(!$con)
	die(mysqli_error());
$scode=$_GET['code'];
$mcode=$_GET['mcode'];
mysqli_query($con,"DELETE FROM pomaster WHERE scode='$scode' and mcode='$mcode'");
header("location: i72.php?code=$scode");
?>