<?php
$con=mysqli_connect('localhost','root','Tamil','purchasedb');
		if(!$con)
			die(mysqli_error());
	if(isset($_POST['submit']))
	{
		$scode=$_POST['scode'];
		$mcode=$_POST['mcode'];
		$mdesc=$_POST['mdesc'];
		$mdescp=$_POST['mdescp'];
		$hsnc=$_POST['hsnc'];
		$rate=$_POST['rate'];
		$uom=$_POST['uom'];
		mysqli_query($con,"DELETE FROM pomaster WHERE scode='$scode' and mcode='$mcode'");
		mysqli_query($con,"INSERT INTO `pomaster` (`scode`, `mcode`, `mdesc`, `mdesc2`, `uom`, `sob`, `hsnc`, `rate`) VALUES ('$scode', '$mcode', '$mdesc', '$mdescp', '$uom', '', '$hsnc', '$rate')");
		echo "INSERT INTO `pomaster` (`scode`, `mcode`, `mdesc`, `mdesc2`, `uom`, `sob`, `hsnc`, `rate`) VALUES ('$scode', '$mcode', '$mdesc', '$mdescp', '$uom', '', '$hsnc', '$rate')";
		header("location: i72.php?code=$scode");
	}
	else
	{
		header("location: inputlink.php");
	}
?>