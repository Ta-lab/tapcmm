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
			$ccode = $_POST['ccode'];
			$pnum = $_POST['partnumber'];
			$vmi = $_POST['vmi'];
			mysqli_query($con,"INSERT INTO vmimaster(date,ccode,pnum,vmiqty) VALUES('$tdate','$ccode','$pnum','$vmi')");
			mysqli_close($con);
			header("location: inputlink.php");
		}
?>