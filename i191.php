<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		
		if(isset($_POST['submit']))
			{
				$tdate = date("Y-m-d", strtotime($_POST['tdate']));
				
				$rcno = $_POST['rcno'];
				$ndc = $_POST['ndc'];
				$pnum = trim($_POST['pnum']);
				$rcno=$_POST['rcno'];
				$rqty = $_POST['rqty'];
				$reject= $_POST['reject'];
				$rsn=$_POST['rsn'];
				echo $pnum.$reject.$rsn;
				mysqli_query($con,"UPDATE `d18` SET `rqty`='$rqty',`reject`='$reject' WHERE ndc='$ndc' and prcno='$rcno'");
				if($reject>0)
				{
					mysqli_query($con,"INSERT INTO d12(date,operation,pnum,rcno,qtyrejected,rsn,username,ip) VALUES('$tdate','From S/C','$pnum','$rcno','$reject','$rsn','$u','$ip')");
				}
				if(isset($_POST['close']))
				{
					$close = $_POST['close'];
					mysqli_query($con,"UPDATE d18 SET closedate='$close' WHERE prcno='$rcno'");			
				}
			}
			mysqli_close($con);
			header("location: inputlink.php");
		?>