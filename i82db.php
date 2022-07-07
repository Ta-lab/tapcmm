<?php
if(isset($_POST['submit']))
{
	$con=mysqli_connect('localhost','root','Tamil','storedb');
	if(!$con)
		die(mysqli_error());
	$gnum=$_POST['gnum'];	
	$dtinsp=$_POST['dtinsp'];	
	$inspby=$_POST['inspby'];	
	$qtyok=$_POST['qtyok'];	
	$qtyrej=$_POST['qtyrej'];	
	$reason=$_POST['reason'];
	mysqli_query($con,"INSERT INTO inspdb (`grnnum`, `inspdate`, `inspby`, `quantityaccepted`, `quantityrejected`, `reason`) VALUES ('$gnum','$dtinsp','$inspby','$qtyok','$qtyrej','$reason')");
	$query = "SELECT ponum,sname,rmdesc FROM `receipt` WHERE grnnum='$gnum'";
	$result = $con->query($query);
	$row = mysqli_fetch_array($result);
	$po=$row['ponum'];
	$sname=$row['sname'];
	$rm=$row['rmdesc'];
	mysqli_query($con,"INSERT INTO `rejection_inv_track` (`ponumber`, `grnnumber`, `scode`, `mcode`, `quantity_rej`, `inv_status`) VALUES ('$po', '$gnum', '$sname', '$rm', '$qtyrej', '0000-00-00')");
	mysqli_close($con);
	header("location: inputlink.php");
}
?>