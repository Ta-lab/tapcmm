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
				$stkpt = $_POST['operation'];
				$prcno = $_POST['rcno'];
				$pnum = $_POST['partnumber'];
				$rcp = $_POST['rcpt'];
				$rsn = $_POST['rsn'];
				$workcentre = $_POST['workcentre'];
				
				
				$query = "SELECT * FROM m13 where pnum='$pnum' ";
				$result = $con->query($query);
				$row = mysqli_fetch_array($result);
				if($row['pnum']==$pnum)
				{				
					$query1 = "SELECT useage FROM m13 where pnum='$pnum' ";
					$result1 = $con->query($query1);
					$row1 = mysqli_fetch_array($result1);
					$rcpt = $rcp/$row1['useage'];	
					mysqli_query($con,"INSERT INTO d12(date,operation,workcentre,pnum,prcno,qtyrejected,rsn,username,ip) VALUES('$tdate','$stkpt','$workcentre','$pnum','$prcno','$rcpt','$rsn','$u','$ip')");
				}	
				else{
					$query2 ="SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum WHERE invpnum='$pnum') AS T0 GROUP BY invpnum";
					$result2 = $con->query($query2);
					$row2 = mysqli_fetch_array($result2);
					$rcpt = $rcp/$row2['tot'];	
					mysqli_query($con,"INSERT INTO d12(date,operation,workcentre,pnum,prcno,qtyrejected,rsn,username,ip) VALUES('$tdate','$stkpt','$workcentre','$pnum','$prcno','$rcpt','$rsn','$u','$ip')");
				}
				
				
				
				mysqli_close($con);
				header("location: inputlink.php");
			}
?>