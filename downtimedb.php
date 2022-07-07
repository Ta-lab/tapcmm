<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_POST['submit']))
{
	$query = "SELECT week FROM `d19`";
	$result = $con->query($query);
	$row = mysqli_fetch_array($result);
	$dt=$row['week'];
	$area = $_POST['area'];
	$fd = $_POST['fd'];
	$ft = $_POST['ft'];
	$mid = $_POST['mid'];
	$td = $_POST['td'];
	$tt = $_POST['tt'];
	mysqli_query($con,"INSERT INTO `downtime` (`area`, `week` , `machine`, `fdate`, `ftime`, `tdate`, `ttime`) VALUES ('$area', '$dt'  , '$mid', '$fd', '$ft', '$td', '$tt')");
	mysqli_close($con);
	header("location: downtime.php");
}
?>