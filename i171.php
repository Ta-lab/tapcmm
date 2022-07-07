<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		
		if(isset($_POST['submit']))
			{
				$tdate = $_POST['tdate'];
				$rcno = $_POST['rcno'];
				$rm = $_POST['rm'];
				$pnum = $_POST['pnum'];
				$to = $_POST['to'];
				$uom= $_POST['uom'];
				$avl = $_POST['avl'];
				$ret = $_POST['ret'];
				mysqli_query($con,"UPDATE m13 SET returnd='$tdate' where pnum='$pnum'");
				//echo "UPDATE m13 SET returnd='$tdate' where pnum='$pnum'";
				mysqli_query($con,"INSERT INTO d14(date,rcno,qty,ret,username,ip) VALUES('$tdate','$rcno','$avl','$ret','$u','$ip')");
				if($to=='A'){
					$query = "SELECT DISTINCT useage FROM `m13` WHERE pnum='$pnum' and rmdesc='$rm'";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					$t=$rmiq/$row['useage'];
					$query = "SELECT week FROM `d19`";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					$dt=$row['week'];
					mysqli_query($con,"UPDATE commit SET issuedqty=issuedqty-'$t' where week='$dt' and pnum='$pnum' and foremac='CNC_SHEARING'");
					mysqli_query($con,"UPDATE d12 set rmissqty=rmissqty-".$ret." where rcno='$rcno' and rm!=''and partissued='0' and qtyrejected='0' and partreceived='0'");
					$query = "SELECT inv FROM `d12` WHERE rcno='$rcno'";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					$grn=$row['inv'];
					if($grn!="")
					{
						mysqli_query($con,"UPDATE `receipt` SET closed='0000-00-00' WHERE grnnum='$grn'");
					}
				}
				else{
					mysqli_query($con,"UPDATE d12 set partissued=partissued-".$ret." where rcno='$rcno'");
					echo "UPDATE d12 set partissued=partissued-".$ret." where rcno='$rcno'";
				}
				if(isset($_POST['close']))
				{
					$close = $_POST['close'];
					$rmk = $_POST['rmk'];
					mysqli_query($con,"UPDATE d11 SET closedate='$close' WHERE rcno='$rcno'");
					mysqli_query($con,"UPDATE d11 SET rmk='$rmk' WHERE rcno='$rcno'");				
				}
				mysqli_close($con);
				header("location: inputlink.php"); 
			}
?>