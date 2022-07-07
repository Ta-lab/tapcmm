<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		
		if(isset($_POST['submit']))
			{
				$stkpt = $_POST['operation'];
				$tdate = date("Y-m-d", strtotime($_POST['tdate']));
				$prcno = $_POST['prcno'];
				$pnum = $_POST['partnumber'];
				$rcpt = $_POST['rcpt'];
				$rsn = $_POST['rsn'];
				$operation = "alfa-n-ind-prim";
				
				if( $stkpt=="CLE UNIT 2")
				{
				
					$operation = "cle unit 2";
				}
				else
				{
					$operation = "alfa-n-ind-prim";
				}
				
				if($stkpt=="FG For Invoicing" || $stkpt=="FG For S/C" || $stkpt=="CLE UNIT 2")
				{
						
					mysqli_query($con,"INSERT INTO d12(date,operation,pnum,prcno,qtyrejected,rsn,username,ip) VALUES('$tdate','$operation','$pnum','$prcno','$rcpt','$rsn','$u','$ip')");
					//echo "INSERT INTO d12(date,operation,pnum,prcno,qtyrejected,username,ip) VALUES('$tdate','$operation','$pnum','$prcno','$rcpt','$u','$ip')";
					
					mysqli_query($con,"UPDATE cledb SET rejected=rejected+'$rcpt' WHERE rcno='$prcno'");
					
				}
				else
				{
					
				}
				/*if(isset($_POST['close']))
				{
					$close = $_POST['close'];
					mysqli_query($con,"UPDATE d11 SET closedate='$close' WHERE rcno='$prcno'");
					mysqli_query($con,"UPDATE d11 SET rmk='$rmk' WHERE rcno='$prcno'");				
				}*/
				echo "<script type='text/javascript'>alert('Successfully updated');</script>";
			}
			header("location: inputlink.php");
			if(!isset($_POST['submit']))
				echo "<script type='text/javascript'>alert('Not Successfully updated');</script>";
		mysqli_close($con);
?>
