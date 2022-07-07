<?php
$inum = $_GET['inum'];
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if($inum!="")
{
	mysqli_query($con,"DELETE FROM inv_det WHERE invno='$inum'");
	mysqli_query($con,"DELETE FROM d12 WHERE rcno='$inum'");	
	mysqli_query($con,"DELETE FROM d11 WHERE rcno='$inum'");
	
	//TO UPDATE D11 CLOSEDATE
	$query = "SELECT * FROM `subcondb_invlink` WHERE invno='$inum'";
	$result = $con->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$dcnum = $row['dcnum'];
		mysqli_query($con,"UPDATE d11 SET closedate='0000-00-00' WHERE rcno='$dcnum'");		
	}
	
	mysqli_query($con,"DELETE FROM `subcondb_invlink` WHERE invno='$inum'");	
	
	mysqli_query($con,"UPDATE npd_invoicing SET invno='' WHERE invno='$inum'");		

}
//mysqli_query($con,"UPDATE inv_correction SET status='F' WHERE invno='$inum'");
header("location: inputlink.php");
?>