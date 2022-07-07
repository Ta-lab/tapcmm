<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if(isset($_POST['submit']))
{
	
	$date=date("Y-m-d");
	$pnum=$_POST['partnumber'];
	$stkpt=$_POST['operation']; 
	$prcno=$_POST['prcno'];
	$qty=$_POST['rcpt'];
	
	mysqli_query($con,"INSERT INTO d12 (`date`, `stkpt`, `pnum`, `rcno`, `prcno`, `partissued`, `username`, `ip`)
								VALUES ('$date','FG For Invoicing','$pnum','$prcno','$prcno','$qty','$u','$ip')");

	mysqli_query($con,"INSERT INTO d12 (`date`, `stkpt`, `pnum`, `prcno`, `partreceived`, `username`, `ip`)
								VALUES ('$date','FG For Scrap','$pnum','$prcno','$qty','$u','$ip')");
	
	
	header("location: fgscrap.php");

}
?>
