<?php
$con=mysqli_connect('localhost','root','Tamil','purchasedb');
		if(!$con)
			die(mysqli_error());
	if(isset($_POST['submit']))
	{
		$ponum=$_POST['ponum'];
		$scode=$_POST['scode'];
		$mcode=$_POST['mcode'];
		$mdesc=$_POST['mdesc'];
		$mdescp=$_POST['mdescp'];
		$hsnc=$_POST['hsnc'];
		$rate=$_POST['rate'];
		$dd=$_POST['dd'];
		$uom=$_POST['uom'];
		$qty=$_POST['qty'];
		$query1 = "SELECT count(*) as sno FROM pogoodsinfo where ponum='$ponum'";
		$result1 = $con->query($query1);
		$row1 = mysqli_fetch_array($result1);
		$lno=$row1['sno']+1;
		$t=$rate*$qty;
		mysqli_query($con,"INSERT INTO `pogoodsinfo` (`status` , `ponum`, `sno`, `mcode` , `description`, `description2`, `hsnc`, `duedate`, `quantity`, `uom`, `rate`, `total`) VALUES ('F' , '$ponum', '$lno', '$mcode' , '$mdesc', '$mdescp', '$hsnc', '$dd', '$qty', '$uom', '$rate', '$t')");
		header("location: i71.php?ponum=$ponum");
	}
	if(isset($_GET['place']))
	{
		$ponum = $_GET['ponum'];
		mysqli_query($con,"UPDATE poinfo SET status='T' where ponum='$ponum'");
		mysqli_query($con,"UPDATE pogoodsinfo SET status='T' where ponum='$ponum'");
		header("location: inputlink.php");
	}
?>