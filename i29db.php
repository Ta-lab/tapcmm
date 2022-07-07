<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

if(isset($_POST['submit']))
	{
		$query = "SELECT week FROM `d19`";
		$result = $con->query($query);
		$row = mysqli_fetch_array($result);
		$dt=$row['week'];
		$tdate = $_POST['tdate'];
		$name=$_POST['names'];
		$part=$_POST['pnum'];
		$cat=$_POST['categories'];
		$doc=$_POST['doc_ref'];
		$qtyr=$_POST['qtyrejected'];
		$pd=$_POST['prob_description'];
		$rmk=$_POST['rmk'];
		$capa=$_POST['capa'];
		mysqli_query($con,"INSERT INTO `capalog` (`date`, `week`, `cname`, `pnum`, `capano`, `docref_no`, `category`, `qtyrejected`, `prob_description`, `remark`) VALUES ('$tdate', '$dt', '$name', '$part', '$capa', '$doc', '$cat', '$qtyr', '$pd', '$rmk')");
		mysqli_close($con);
		header("location: inputlink.php");
	}
?>