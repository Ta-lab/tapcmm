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
			$pnum = $_POST['pnum'];
			$parttype = $_POST['parttype'];
			$orderdate = $_POST['orderdate'];
			$orderqty = $_POST['orderqty'];
			$orderref = $_POST['orderref'];
			$reqdate = $_POST['reqdate'];
			$commitdate = $_POST['commitdate'];
			//mysqli_query($con,"DELETE FROM `orderbook` WHERE pnum='$pnum'");
			mysqli_query($con,"INSERT INTO `orderbook` (`date`, `pnum`, `type`, `qty`, `ref_no`, `order_date`, `req_date`, `commit`, `invstatus`, `notify`) VALUES ('$tdate', '$pnum', '$parttype', '$orderqty', '$orderref', '$orderdate', '$reqdate', '$commitdate', '1', '1')");
			mysqli_close($con);
			header("location: inputlink.php");
		}
?>