<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
date_default_timezone_set('Asia/Kolkata');
$dt=date("Y-m-d");
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_POST['submit']))
{
	$type = $_POST['type'];
	$pnum = $_POST['pnum'];
	$mqty = $_POST['mqty'];
	$vfqty = $_POST['vfqty'];
	$vsqty = $_POST['vsqty'];
	$query = "SELECT rno,COUNT(*) AS c FROM `demandmaster` where pnum='$pnum'";
	$result = $con->query($query);
	$row = mysqli_fetch_array($result);
	if($row['c']>0)
	{
		$rno=$row['rno'];
		mysqli_query($con,"UPDATE demandmaster SET `type`='$type',`monthly`='$mqty',`vmi_fg`='$vfqty',`sf`='$vsqty' WHERE rno='$rno'");
	}
	else
	{
		mysqli_query($con,"INSERT INTO `demandmaster` (`pnum`, `type`, `monthly`, `vmi_fg`, `sf`) VALUES ('$pnum', '$type', '$mqty', '$vfqty', '$vsqty');");
	}
	mysqli_close($con);
	header("location: vssorder.php");
}
?>