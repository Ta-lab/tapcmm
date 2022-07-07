<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','storedb');

if(!$con)
	die(mysqli_error());
		
		$grnnum = $_POST['rc'];
		$qty = $_POST['qty'];
		
		mysqli_query($con,"UPDATE receipt SET quantity_received=quantity_received+'$qty' WHERE grnnum='$grnnum'");
		mysqli_query($con,"UPDATE inspdb SET quantityaccepted=quantityaccepted+'$qty' WHERE grnnum='$grnnum'");
		
		//echo "UPDATE receipt SET quantity_received=quantity_received+'$qty' WHERE grnnum='$grnnum'";
		//echo "UPDATE inspdb SET quantityaccepted=quantityaccepted+'$qty' WHERE grnnum='$grnnum'";
		
		header("location: inputlink.php");

?>