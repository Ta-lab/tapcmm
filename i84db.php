<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
$conn=mysqli_connect('localhost','root','Tamil','storedb');
if(!$con)
	die(mysqli_error());
		$tdate = $_POST['tdate'];
		$stkpt = "Returned";						
		$rcno = $_POST['ircn'];
		$uom = $_POST['uom'];
		$rmiq = $_POST['rmqty'];
		$pnum = $_POST['pnum'];
		$rin = $_POST['rin'];
		$arm = $_POST['arm'];
		if(rmiq>=$arm)
		{
			mysqli_query($conn,"UPDATE rin_receipt SET rin_status='$tdate' where rin='$rin'");
		}
		mysqli_query($conn,"UPDATE rin_receipt SET issued=issued+'$rmiq' where rin='$rin'");
		mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('$stkpt','$tdate','$pnum','$rcno')");
		mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,rcno,partissued,inv,heat,lot,coil,username,ip) VALUES('$tdate','$stkpt', '$pnum' ,'$rcno','$rmiq','$rin','','','','$u','$ip')");
		header("location: inputlink.php");
?>