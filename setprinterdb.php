<?php
$print = $_POST['print'];
$port = $_POST['port'];
$con = mysqli_connect('localhost','root','Tamil','mypcm');
mysqli_query($con,"UPDATE invprinter SET popt='$print',pname='$port'");
header("location: inputlink.php");
?>