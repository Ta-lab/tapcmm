<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$dt=date("Y-m-d");
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_GET['pnum']) && $_GET['pnum']!="" )
{
	$pnum=$_GET['pnum'];
	//$result = mysqli_query($con,"SELECT * FROM npdparts where pnum='$pnum'");
	//$row1 = mysqli_fetch_array($result);
	mysqli_query($con,"DELETE FROM `npdparts` where pnum='$pnum'");
	//echo "DELETE FROM `npdparts` where pnum='$pnum'";
	//mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'COMMIT DELETED', 'PART No:($pnum) : cq:$qty,iq=$i,area=$f', '$u', '$ip')");
}
header("location: npdparts.php");
?>