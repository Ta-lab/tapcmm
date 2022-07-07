<?php
			if(isset($_POST['submit']))
			{
				$con=mysqli_connect('localhost','root','Tamil','storedb');
				if(!$con)
					die(mysqli_error());
				$date=date('Y-m-d');
				$part= $_POST['part'];	
				$rin=$_POST['rin'];	
				$invno=$_POST['invno'];
				$cname=$_POST['cname'];
				$docno=$_POST['docno'];	
				$qty=$_POST['qty'];	
				
				mysqli_query($con,"INSERT INTO `rin_receipt` (`rin`, `date`, `cname`, `docno`, `invoice`, `pnum`, `qty`, `insp_status`, `issued`, `rin_status`) VALUES ('$rin', '$date', '$cname', '$docno', '$invno', '$part', '$qty', '$date', '0', '0000-00-00')");
				mysqli_close($con);
				header("location: inputlink.php");
			}
?>