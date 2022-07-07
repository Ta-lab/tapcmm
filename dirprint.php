<?php
$dc = $_GET['dc'];
$pnum = $_GET['pnum'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
$result = $con->query("SELECT * FROM m12 WHERE pnum='$pnum' and operation='FG For S/C')");
if($result === FALSE) { 
	//header("location: printphp.php?invno=$dc&n=1");
	$result1 = $con->query("SELECT * from invprinter");
	$row = mysqli_fetch_array($result1);
	if($row['popt']=="0")
	{
		mysqli_query($con,"UPDATE inv_det set ok='T' where invno='$dc'");
		mysqli_query($con,"UPDATE inv_correction SET status='T' WHERE invno='$dc'");
		header("location: printphp.php?invno=$dc&n=0");
	}
	else
	{
		mysqli_query($con,"UPDATE inv_det set ok='T' where invno='$dc'");
		mysqli_query($con,"UPDATE inv_correction SET status='T' WHERE invno='$dc'");
		header("location: inputlink.php");
	}
}
else
{
	header("location: invslip.php?invno=$dc");
}
?>