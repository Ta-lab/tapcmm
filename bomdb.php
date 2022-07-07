<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$dt=date("Y-m-d");
if(isset($_SESSION['user']) && isset($_SESSION['access']) && $_SESSION['access']=="ALL")
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
		$rmcategory = $_POST['rmcategory'];
		$ouse = $_POST['ouse'];
		$pn = $_POST['pn'];
		$rm = $_POST['rm'];
		$uom = $_POST['uom'];
		$use = $_POST['use'];
		$mcode = $_POST['mcode'];
		$fm = $_POST['fm'];
		$crce = $_POST['crce'];
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
			mysqli_query($con,"UPDATE M13 SET useage='$use' where pnum='$pn'");
			mysqli_query($con,"UPDATE rmcategory SET bom='$use' where pnum='$pn'");
			mysqli_query($con,"UPDATE rmcategory SET obom='$ouse' where pnum='$pn'");
			mysqli_query($con,"UPDATE rmcategory SET category='$rmcategory' where pnum='$pn'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['useage'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'BOMMASTER', 'BOM CHANGED($pn) : $t TO $use', '$u', '$ip')");
			}
			mysqli_query($con,"UPDATE M13 SET cnc_excep='$crce' where pnum='$pn'");
			//echo "UPDATE M13 SET cnc_excep='$crce' where pnum='$pn'";
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['cnc_excep'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'CNC PART EX', 'CNC PART EX($pn) : $t TO $crce', '$u', '$ip')");
				echo "INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'CNC PART EX', 'CNC PART EX($pn) : $t TO $crce', '$u', '$ip')";
			}
			mysqli_query($con,"UPDATE M13 SET m_code='$mcode' where rmdesc='$rm' and pnum='$pn'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['m_code'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'BOMMASTER', 'M CODE CHANGED($pn) : $t TO $mcode', '$u', '$ip')");
			}
			mysqli_query($con,"UPDATE M13 SET foreman='$fm' where pnum='$pn'");
			if(mysqli_affected_rows($con)>0)
			{
				$t=$prevrow['foreman'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', 'BOMMASTER', 'FOREMAN CHANGED($pn) : $t TO $fm', '$u', '$ip')");
			}
		}
		else
		{
			mysqli_query($con,"INSERT INTO m13(pnum,rmdesc,uom,useage,m_code,foreman) VALUES('$pn','$rm','$uom','$use','$mcode','$fm')");
			mysqli_query($con,"INSERT INTO rmcategory(pnum,rm,bom,obom,category) VALUES('$pn','$rm','$use','$ouse','$rmcategory')");
		}
		header("location: inputlink.php");
	}
	echo "<script type='text/javascript'>alert('Not Successfully updated');</script>";
mysqli_close($con);
?>