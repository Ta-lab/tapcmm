<?php
$rm1=$_POST['rm1'];
$rm2=$_POST['rm2'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
mysqli_query($con,"UPDATE d12 set rm='$rm2' where rm='$rm1'");
mysqli_query($con,"UPDATE m13 set rmdesc='$rm2' where rmdesc='$rm1'");
header("location: test.php");
?>