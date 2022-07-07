<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

if(isset($_POST['submit']))
{
	$tdate = $_POST['tdate'];
	$prcno = $_POST['prcno'];
	$pnum = $_POST['partnumber'];
	$fin = $_POST['fin'];
	$iss = $_POST['iss'];
	$result = $con->query("SELECT distinct invpnum FROM `pn_st` WHERE pnum='$pnum' and n_iter=1");
	echo "SELECT distinct invpnum FROM `pn_st` WHERE pnum='$pnum' and n_iter=1";
	$row = mysqli_fetch_array($result);
	$pnum=$row['invpnum'];
	echo $pnum;
	mysqli_query($con,"DELETE FROM fi_rcno WHERE fi_id='$fin'");
	mysqli_query($con,"INSERT INTO `fi_rcno` (`date`, `fi_id` , `pnum`, `issue_level` , `rcno`) VALUES ('$tdate', '$fin', '$pnum', '_$iss' , '$prcno')");
	mysqli_close($con);
	header("location: insdetail.php?fin=$fin&prcno=$prcno&partnumber=$pnum&ccode=$ccode");
}
?>