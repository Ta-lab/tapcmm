<?php
			if(isset($_POST['submit']))
			{
				$con=mysqli_connect('localhost','root','Tamil','mypcm');
				if(!$con)
					die(mysqli_error());
				$tdate = $_POST['tdate'];
				$shift = $_POST['shift'];
				$operation = $_POST['operation'];
				$groupid = $_POST['groupid'];
				$mid = $_POST['mid'];
				$operid = $_POST['operid'];
				$rcno = $_POST['rcno'];
				$pnum = $_POST['partnumber'];
				$qtyprod = $_POST['qtyprod'];
				
				$lname=$_POST['lname'];
				$qty=$_POST['qty'];
				$td=$_POST['td'];
				$reason=$_POST['reason'];
				$dept=$_POST['dept'];
				
				
				$count=count($lname);
				mysqli_query($con,"INSERT INTO `d21` (`date`, `shift`, `operation`, `groupid`, `midmwc`, `oper`, `rcno`, `pnum`, `qtyprod`) VALUES ('$tdate', '$shift', '$operation', '$groupid', '$mid', '$operid', '$rcno', '$pnum', '$qtyprod')");
				for($i=0;$i<$count;$i++)
				{
					//mysqli_query($con,"INSERT INTO d14 (date,shift,rcno,pnum,operation,oid,gid,mid,qtyproduced,type,time1,reason,dept) VALUES ('$tdate','$shift','$rcno','$pnum','$operation','$oid','$groupid','$mid','$pro','$lname[$i]','$td[$i]','$reason[$i]','$dept[$i]')");
					mysqli_query($con,"INSERT INTO `d22` (`date`, `shift`, `operation`, `groupid`, `midmwc`, `oper`, `rcno`, `pnum`, `typelose`, `qty`, `timetak`, `reason`, `dept`) VALUES ('$tdate', '$shift', '$operation', '$groupid', '$mid', '$operid', '$rcno', '$pnum', '$lname[$i]', '$qty[$i]', '$td[$i]', '$reason[$i]', '$dept[$i]')");
				}
				header("location: inputlink.php");
			}
			?>