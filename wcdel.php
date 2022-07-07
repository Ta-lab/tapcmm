<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$dt=date("Y-m-d");
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_GET['rid']) && $_GET['rid']!="" )
{
	$d=$_GET['rid'];
	$result = mysqli_query($con,"SELECT * FROM commit where rid='$d'");
	$row1 = mysqli_fetch_array($result);
	$pnum=$row1['pnum'];
	$qty=$row1['qty'];
	$i=$row1['issuedqty'];
	$f=$row1['foremac'];
	mysqli_query($con,"DELETE FROM `commit` where rid='$d'");
	mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'COMMIT DELETED', 'PART No:($pnum) : cq:$qty,iq=$i,area=$f', '$u', '$ip')");
}
header("location: weeklycommit.php");
?>