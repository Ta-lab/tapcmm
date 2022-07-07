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
				
				$total_rec_qty = $_POST['rcpt'] + $_POST['recqty'];
				
				
				$dc_num = substr($prcno,3);
				$query2 = "SELECT * FROM dc_det WHERE dcnum='$dc_num' ";
				$result2 = $con->query($query2);
				$row2 = mysqli_fetch_array($result2);
				
				$scn = $row2['scn'];
				$total_dc_qty = $row2['qty'];
				
				//echo $scn;
				//echo $total_dc_qty;
				//echo $rcpt;
				//echo $total_rec_qty;
				
				
				
				if($stkpt=="FG For Invoicing" || $stkpt=="FG For S/C")
				{
					$query3 = "SELECT * FROM `unit2receiveentry` WHERE dcnum='$prcno' ";
					//$query3 = "SELECT * FROM `subcondb` WHERE dcnum='$prcno' ";
					//echo "SELECT * FROM `subcondb` WHERE dcnum='$prcno' ";
					$result3 = $con->query($query3);
					$row3 = mysqli_fetch_array($result3);
					
					//echo $row3['dcnum'];
					
					if($row3['dcnum']=="")
					{
						//mysqli_query($con,"INSERT INTO `subcondb` (`dcnum`,`date`, `scn`, `pnum`, `total_dc_qty`, `total_rec_qty`, `username`, `ip`) VALUES ('$prcno', '$tdate', '$scn', '$pnum', '$total_dc_qty', '$rcpt', '$u', '$ip')");
						mysqli_query($con,"INSERT INTO `unit2receiveentry` (`dcnum`,`date`, `scn`, `pnum`, `total_dc_qty`, `total_rec_qty`, `username`, `ip`) VALUES ('$prcno', '$tdate', '$scn', '$pnum', '$total_dc_qty', '$rcpt', '$u', '$ip')");
					}
					else{
						mysqli_query($con,"UPDATE `unit2receiveentry` SET total_rec_qty=total_rec_qty+'$rcpt' where dcnum='$prcno' ");
					}
				
					
					//mysqli_query($con,"INSERT INTO `subcondb` (`dcnum`,`date`, `scn`, `pnum`, `total_dc_qty`, `total_rec_qty`) VALUES ('$prcno', '$tdate', '$scn', '$pnum', '$total_dc_qty', '$total_rec_qty')");
					//echo "INSERT INTO `subcondb` (`date`, `scn`, `pnum`, `dcnum`, `dcqty`) VALUES ('$tdate', '$scn', '$pnum', '$prcno', '$rcpt')";
					
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
