<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
		$tdate = $_GET['tdate'];
		$stkpt = $_GET['stockingpoint'];						
		$rcno = $_GET['ircn'];
		$rm = $_GET['raw'];
		$uom = $_GET['uom'];
		$rmiq = $_GET['rmqty'];
		$inum = $_GET['inum'];
		$hno = $_GET['hno'];
		$pnum = $_GET['pnum'];
		$lno = $_GET['lno'];
		$cno = $_GET['cno'];
		$query = "SELECT DISTINCT useage FROM `m13` WHERE pnum='$pnum' and rmdesc='$rm'";
		$result = $con->query($query);
		$row = mysqli_fetch_array($result);
		$t=$rmiq/$row['useage'];
		$query = "SELECT week FROM `d19`";
		$result = $con->query($query);
		$row = mysqli_fetch_array($result);
		$dt=$row['week'];
		mysqli_query($con,"UPDATE commit SET issuedqty=issuedqty+'$t' where week='$dt' and pnum='$pnum' and foremac='CNC_SHEARING'");
		mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('$stkpt','$tdate','$pnum','$rcno')");
		mysqli_query($con,"INSERT INTO d12(date,stkpt,rm,rmissqty,pnum,rcno,inv,heat,lot,coil,username,ip) VALUES('$tdate','$stkpt','$rm','$rmiq', '$pnum' ,'$rcno','$inum','$hno','$lno','$cno','$u','$ip')");
		header("location: inputlink.php");
?>