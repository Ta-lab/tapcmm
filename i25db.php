<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
if(isset($_SESSION['user']) && isset($_SESSION['access'])  && ($_SESSION['access']=="ALL" || $_SESSION['user']=="123" ) )
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
	if(isset($_POST['rc']))
	{
		$rcno=$_POST['rc'];
		$query2 = "SELECT rowid FROM d12 where rcno='$rcno' OR prcno='$rcno'";
		$result2 = $con->query($query2);
		$dt=date("Y-m-d");
		while($row1 = mysqli_fetch_array($result2))
		{
			$i=$row1['rowid'];
			$sp=$_POST["sp".$i];
			$rq=$_POST["rq".$i];
			$pc=$_POST["pc".$i];
			$pi=$_POST["pi".$i];
			$qr=$_POST["qr".$i];
			$pr=$_POST["pr".$i];
			$gn=$_POST["gn".$i];
			$rs=$_POST["rs".$i];
			$prev = "SELECT * from d12 where rowid='$i'";
			$resprev = $con->query($prev);
			$prevrow = mysqli_fetch_array($resprev);
			$result=mysqli_query($con,"UPDATE d12 SET stkpt='$sp' WHERE rowid='$i'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['stkpt'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'STKPT UPDATED : $t TO $sp', '$u', '$ip')");
			}
			$result=mysqli_query($con,"UPDATE d12 SET rmissqty='$rq' WHERE rowid='$i'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['rmissqty'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'RM QTY UPDATED : $t -> $rq', '$u', '$ip')");
			}
			$result=mysqli_query($con,"UPDATE d12 SET prcno='$pc' WHERE rowid='$i'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['prcno'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'PREV RCNO UPDATED : $t -> $pc', '$u', '$ip')");
			}
			$result=mysqli_query($con,"UPDATE d12 SET partissued='$pi' WHERE rowid='$i'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['partissued'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'ISSUED QTY UPDATED : $t -> $pi', '$u', '$ip')");
			}
			$result=mysqli_query($con,"UPDATE d12 SET qtyrejected='$qr' WHERE rowid='$i'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['qtyrejected'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'REJECTION QTY UPDATED : $t -> $qr', '$u', '$ip')");
			}
			$result=mysqli_query($con,"UPDATE d12 SET partreceived='$pr' WHERE rowid='$i'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['partreceived'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'RECEIVED QTY UPDATED : $t -> $pr', '$u', '$ip')");
			}
			$result=mysqli_query($con,"UPDATE d12 SET inv='$gn' WHERE rowid='$i'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['inv'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'GIN NO UPDATED : $t -> $gn', '$u', '$ip')");
			}
			$result=mysqli_query($con,"UPDATE d12 SET rsn='$rs' WHERE rowid='$i'");
			if(mysqli_affected_rows($con)==1)
			{
				$t=$prevrow['rsn'];
				mysqli_query($con,"INSERT INTO `correction` (`date`, `rcno`, `detail`, `cby`, `ip`) VALUES ('$dt', '$rcno', 'REJECTION REASON UPDATED : $t -> $rs', '$u', '$ip')");
			}
		}
		header("Location: i25.php?rcno=$rcno");
	}
}
?>