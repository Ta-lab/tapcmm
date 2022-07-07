<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con = mysqli_connect('localhost','root','Tamil','mypcm');
$conn = mysqli_connect('localhost','root','Tamil','storedb');
if(isset($_POST['rmdcok']))
{
	$tdate=$_POST['tdate'];
	$pn=$_POST['pn'];
	$tiqty=$_POST['tiqty'];
	$dc=$_POST['dc'];
	$dt="DC-".$dc;
	$dct=$_POST['dctype'];
	$sc=$_POST['sc'];
	$mot=$_POST['mot'];
	$vno=$_POST['vno'];
	if(isset($_POST['prcno']) && $_POST['prcno']!="")
	{
		$grn=$_POST['prcno'];
		mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty` , `vehiclenumber` , `type`) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty' , '$vno' , '1')");
		mysqli_query($con,"INSERT INTO `rmdc` (`dcnum`, `grnnum`, `date`, `scn`, `rm`, `mot`, `qty` , `type`) VALUES ('$dc', '$grn', '$tdate', '$sc', '$pn', '$mot', '$tiqty' , '1')");
		mysqli_query($con,"INSERT INTO `d11` (`operation`, `date`, `rcno`, `pnum`) VALUES ('To S/C', '$tdate', '$dt', '$pn')");
		mysqli_query($conn,"UPDATE `receipt` SET sent_thr_dc=sent_thr_dc+$tiqty WHERE grnnum='$grn'");
		header("location: inputlink.php");
	}
}
?>
