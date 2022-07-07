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
				$stkpt = $_POST['operation'];
				if(isset($_POST['area']) && $_POST['area']!="")
				{
					$stkpt=$_POST['area'];
				}
				$prcno = $_POST['prcno'];
				$pnum = $_POST['partnumber'];
				$rcpt = $_POST['rcpt'];
				$rmk = $_POST['rmk'];
				//if($stkpt=="FG For Invoicing" || $stkpt=="FG For S/C")
				if($stkpt=="CLE UNIT 2")
				{
	
					mysqli_query($con,"INSERT INTO d12 (date,pnum,prcno,partreceived,username,ip) VALUES ('$tdate','$pnum','$prcno','$rcpt','$u','$ip')");
					
					mysqli_query($con,"UPDATE d12 SET partreceived=partreceived-'$rcpt' WHERE rcno='$prcno'");
					
					mysqli_query($con,"UPDATE cledb SET received_qty=received_qty+'$rcpt' WHERE rcno='$prcno'");
					
					//mysqli_query($con,"INSERT INTO `f_insp` (`date`, `stkpt`, `pnum`, `prcno`, `qty`, `user`, `ip`, `date_insp`, `status`) VALUES ('$tdate', '$stkpt', '$pnum', '$prcno', '$rcpt', '$u', '$ip', '0000-00-00', 'F')");
					//mysqli_query($con,"UPDATE cledb SET received_qty=received_qty+'$rcpt' WHERE rcno='$prcno'");
					//echo "UPDATE cledb SET received_qty=received_qty+'$rcpt' WHERE rcno='$prcno'";
					
				}
				else
				{
					//mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,prcno,partreceived,username,ip) VALUES ('$tdate','$stkpt','$pnum','$prcno','$rcpt','$u','$ip')");
				}
				if(isset($_POST['close']))
				{
					$close = $_POST['close'];
					mysqli_query($con,"UPDATE d11 SET closedate='$close' WHERE rcno='$prcno'");
					//mysqli_query($con,"UPDATE d11 SET rmk='$rmk' WHERE rcno='$prcno'");				
				}
				echo "<script type='text/javascript'>alert('Successfully updated');</script>";
			}
			header("location: inputlink.php");
			if(!isset($_POST['submit']))
				echo "<script type='text/javascript'>alert('Not Successfully updated');</script>";
		mysqli_close($con);
?>
