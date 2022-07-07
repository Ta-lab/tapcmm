<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		
		if(isset($_POST['submit']))
			{
				$tdate = $_POST['tdate'];
				$rcno = $_POST['rcno'];
				$ndc = $_POST['ndc'];
				$pnum= $_POST['pnum'];
				$qty = $_POST['qty'];
				mysqli_query($con,"INSERT INTO `d18`(`date`, `prcno`, `ndc`, `pnum`, `iqty`) VALUES('$tdate','$rcno','$ndc','$pnum','$qty')");
				mysqli_close($con);
				header("location: inputlink.php"); 
			}
?>