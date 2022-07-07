<?php
$inv="";
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="TRACEBILITY REPORT";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
	}
	else
	{
		header("location: logout.php");
	}
}
else
{
	header("location: index.php");
}
?><!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<script src = "js\excelreport1.js"></script>
	<link rel="stylesheet" type="text/css" href="design2.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>TRACEBILITY REPORT  [ O15 ]</label></h4>
		<div>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tablesToExcel(['testTable', 'testTable1','testTable2','testTable3','testTable4'], ['TRACE','b','c','d','e'], 'myfile.xls')" value="Export to Excel">
		</div>
		<br/></br>
		<div>
		<?php
			$str="";
			$con=mysqli_connect('localhost','root','Tamil','mypcm');
			echo"</table>";
							echo'<br><table id="testTable4" align="center">
									<tr>
										<th>INV NO</th>
										<th>RC NO</th>
										<th>DATE</th>
										<th>RAW MATERIAL</th>
										<th>RMISSQTY</th>
										<th>GIN NO</th>
										<th>HEAT NO</th>
										<th>COIL NO</th>
									</tr>';
			if(!$con)
				die(mysqli_error());
			$servername = "localhost";
			$username = "root";
			$password = "Tamil";
			$conn = new mysqli($servername, $username, $password, "mypcm");
		$queryq = "SELECT DISTINCT invno FROM `inv_det` LEFT JOIN (SELECT invpnum,IF(SUM(useage) IS NULL,0,SUM(useage)) as bom FROM (SELECT invpnum,SUM(useage) as useage FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt='FG For Invoicing' GROUP BY pn_st.invpnum,n_iter) AS T GROUP BY invpnum) AS BOM ON inv_det.pn=BOM.invpnum where pn='29192689' AND invno LIKE '______-23%'";
		$resultq = $conn->query($queryq);
		while($rowq = $resultq->fetch_assoc())
		{
			//echo $rowq['invno']."<br>"; 
			$inv=$rowq['invno'];
			track($inv,$inv);
		}
		function track($rcno,$inv)
			{
				//echo $rcno."<br>";
				$con=mysqli_connect('localhost','root','Tamil','mypcm');
				$ret = $con->query("select stkpt,prcno,rcno from d12 where d12.rcno='$rcno' and (partissued!='' or rmissqty!='')");
				$c= $ret->num_rows;
				if($c==0)
				{
					
				}
				else
				{
					$row2 = mysqli_fetch_array($ret);
					do{
						$temp=$row2['rcno'];
						if(substr($row2['rcno'],0,1)=="A")
						{
							$temp=$row2['rcno'];		
							$query4 = "select date,rm,rmissqty,inv,heat,lot,coil from d12 where rcno='$temp'";
							$result4 = $con->query($query4);
							while($row4 = mysqli_fetch_array($result4))
							{
								$d1 = date("d-m-Y", strtotime($row4['date']));
								echo"<tr>
										<td>".$inv."</td>
										<td>".$temp."</td>
										<td>".$d1."</td>
										<td>".$row4['rm']."</td>
										<td>".$row4['rmissqty']."</td>
										<td>".$row4['inv']."</td>
										<td>".$row4['heat']."</td>
										<td>".$row4['coil']."</td>
									</tr>";
							}
						}
						if($row2['prcno']!="")
						{
							track($row2['prcno'],$inv);
						}
					}while($row2 = $ret->fetch_assoc());
				}
			}
			
	?>
		</div>
		<br>
		<br>
</body>
</html>