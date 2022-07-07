<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$dt=date("Y-m-d");
if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL")
{
	
}
else
{
	header("location: index.php");
}
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

if(isset($_POST['submit']))
	{
		$pn = $_POST['pn'];
		$rm = $_POST['rm'];
		$uom = $_POST['uom'];
		$use = $_POST['use'];
		$mcode = $_POST['mcode'];
		$fm = $_POST['fm'];
		$result = mysqli_query($con,"SELECT *,count(*) as c FROM m13 where pnum='$pn' and rmdesc='$rm'");
		$row1 = mysqli_fetch_array($result);
		if($row1['c']>0)
		{
			$prev = "SELECT * from m13 where pnum='$pn' and rmdesc='$rm'";
			$resprev = $con->query($prev);
			$prevrow = mysqli_fetch_array($resprev);
			mysqli_query($con,"UPDATE M13 SET uom='$uom' where rmdesc='$rm' and pnum='$pn'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['uom'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'BOMMASTER', 'UOM CHANGED($pn) : $t TO $uom', '$u', '$ip')");
			}
			mysqli_query($con,"UPDATE M13 SET useage='$use' where rmdesc='$rm' and pnum='$pn'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['useage'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'BOMMASTER', 'BOM CHANGED($pn) : $t TO $use', '$u', '$ip')");
			}
			mysqli_query($con,"UPDATE M13 SET m_code='$mcode' where rmdesc='$rm' and pnum='$pn'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['m_code'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'BOMMASTER', 'M CODE CHANGED($pn) : $t TO $mcode', '$u', '$ip')");
			}
			mysqli_query($con,"UPDATE M13 SET foreman='$fm' where rmdesc='$rm' and pnum='$pn'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['foreman'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'BOMMASTER', 'FOREMAN CHANGED($pn) : $t TO $fm', '$u', '$ip')");
			}
		}
		else
		{
			mysqli_query($con,"INSERT INTO m13(pnum,rmdesc,uom,useage,m_code,foreman) VALUES('$pn','$rm','$uom','$use','$mcode','$fm')");
		}
		header("location: newprocess.php?part=$pn");
	}
	echo "<script type='text/javascript'>alert('Not Successfully updated');</script>";
mysqli_close($con);
?>