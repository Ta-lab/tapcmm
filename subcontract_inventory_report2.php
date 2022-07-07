<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="SC INV REPORT";
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
	<script src = "js\excelreport.js"></script>
	<link rel="stylesheet" type="text/css" href="design1.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
		<h4 style="text-align:center"><label> SUB CONTRACTOR OPEN DC STOCK REPORT </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'FG')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
			
			echo'<table id="testTable" align="center">
				<tr>
					<th>DC DATE</th>
					<th>PART NUMBER</th>
					<th>SUB CONTRACTOR</th>
					<th>DC NUMBER</th>
					<th>INVOICED QTY</th>
					<th>QTY AVAILABLE IN FG</th>
					<th>QTY UNDER PROCESSING</th>
					<th></th>
				</tr>';
				
				/*$query2 = "SELECT * FROM(SELECT * FROM `subcondb`) AS TSUB
							LEFT JOIN(SELECT prcno,sum(partreceived) as rec,sum(qtyrejected) as rej FROM d12 GROUP BY prcno) AS Td12 ON TSUB.dcnum=Td12.prcno";
				*/
				
				//good working query
				
				/*$query2 = "SELECT * FROM(SELECT dcnum,date,scn,pnum,total_dc_qty,total_rec_qty FROM `subcondb`) AS TSUB
				
LEFT JOIN(SELECT dcnum,SUM(invqty) AS invqty FROM `subcondb_invlink` GROUP BY dcnum) AS TSUBINV ON TSUB.dcnum=TSUBINV.dcnum

LEFT JOIN(SELECT prcno,sum(partreceived) as rec,sum(qtyrejected) as rej FROM d12 where rcno!='' GROUP BY prcno) AS Td12 ON TSUB.dcnum=Td12.prcno

LEFT JOIN(SELECT * FROM `d11`) AS Td11 ON TSUB.dcnum=Td11.rcno WHERE Td11.closedate='0000-00-00'";

				*/
				
				/*$query2 = "SELECT TSUB.dcnum AS fdcnum,TSUB.date,scn,TSUB.pnum,TSUB.total_dc_qty,TSUB.total_rec_qty,TSUBINV.dcnum,TSUBINV.invqty,Td12.prcno,Td12.rec,Td12.rej,Td11.rcno,Td11.closedate FROM(SELECT dcnum,date,scn,pnum,total_dc_qty,total_rec_qty FROM `subcondb`) AS TSUB
				
LEFT JOIN(SELECT dcnum,IF(SUM(invqty) IS NULL,0,SUM(invqty)) AS invqty FROM `subcondb_invlink` GROUP BY dcnum) AS TSUBINV ON TSUB.dcnum=TSUBINV.dcnum

LEFT JOIN(SELECT prcno,sum(partreceived) as rec,sum(qtyrejected) as rej FROM d12 where rcno!='' GROUP BY prcno) AS Td12 ON TSUB.dcnum=Td12.prcno

LEFT JOIN(SELECT * FROM `d11`) AS Td11 ON TSUB.dcnum=Td11.rcno WHERE Td11.closedate='0000-00-00'";
				*/
				
				//GOOD WORKING
				/*$query2 = "SELECT TSUB.dcnum AS fdcnum,TSUB.date,scn,TSUB.pnum,TSUB.total_dc_qty,TSUB.total_rec_qty,TSUBINV.dcnum,IF(TSUBINV.invqty IS NULL,0,TSUBINV.invqty) AS invqty,Td12.prcno,Td12.rec,Td12.rej,Td11.rcno,Td11.closedate FROM(SELECT dcnum,date,scn,pnum,total_dc_qty,total_rec_qty FROM `subcondb`) AS TSUB
				
LEFT JOIN(SELECT dcnum,IF(SUM(invqty) IS NULL,0,SUM(invqty)) AS invqty FROM `subcondb_invlink` GROUP BY dcnum) AS TSUBINV ON TSUB.dcnum=TSUBINV.dcnum

LEFT JOIN(SELECT prcno,sum(partreceived) as rec,sum(qtyrejected) as rej FROM d12 where rcno!='' GROUP BY prcno) AS Td12 ON TSUB.dcnum=Td12.prcno

LEFT JOIN(SELECT * FROM `d11`) AS Td11 ON TSUB.dcnum=Td11.rcno WHERE Td11.closedate='0000-00-00'";
				*/
				
				//corr
				/*$query2 = "SELECT TSUB.dcnum AS fdcnum,TSUB.date,scn,TSUB.pnum,TSUB.total_dc_qty,TSUB.total_rec_qty,TSUBINV.dcnum,IF(TSUBINV.invqty IS NULL,0,TSUBINV.invqty) AS invqty,Td12.prcno,Td12.rec,TTd12.rej,Td11.rcno,Td11.closedate FROM(SELECT dcnum,date,scn,pnum,total_dc_qty,total_rec_qty FROM `subcondb`) AS TSUB
				
LEFT JOIN(SELECT dcnum,IF(SUM(invqty) IS NULL,0,SUM(invqty)) AS invqty FROM `subcondb_invlink` GROUP BY dcnum) AS TSUBINV ON TSUB.dcnum=TSUBINV.dcnum

LEFT JOIN(SELECT prcno,sum(partreceived) as rec,sum(qtyrejected) as rej FROM d12 where rcno!='' GROUP BY prcno) AS Td12 ON TSUB.dcnum=Td12.prcno

LEFT JOIN(SELECT prcno,sum(qtyrejected) as rej FROM d12 GROUP BY prcno) AS TTd12 ON TSUB.dcnum=TTd12.prcno

LEFT JOIN(SELECT * FROM `d11`) AS Td11 ON TSUB.dcnum=Td11.rcno WHERE Td11.closedate='0000-00-00' ORDER BY pnum";
				*/
				
				
				$query2 = "SELECT TSUB.dcnum AS fdcnum,TSUB.date,scn,TSUB.pnum,TSUB.total_dc_qty,TSUB.total_rec_qty,TSUBINV.dcnum,IF(TSUBINV.invqty IS NULL,0,TSUBINV.invqty) AS invqty,Td12.prcno,Td12.rec,TTd12.rej,Td11.rcno,Td11.closedate FROM(SELECT dcnum,date,scn,pnum,total_dc_qty,total_rec_qty FROM `subcondb`) AS TSUB
				
LEFT JOIN(SELECT dcnum,IF(SUM(invqty) IS NULL,0,SUM(invqty)) AS invqty FROM `subcondb_invlink` GROUP BY dcnum) AS TSUBINV ON TSUB.dcnum=TSUBINV.dcnum

LEFT JOIN(SELECT prcno,sum(partreceived) as rec,sum(qtyrejected) as rej FROM d12 GROUP BY prcno) AS Td12 ON TSUB.dcnum=Td12.prcno

LEFT JOIN(SELECT prcno,sum(qtyrejected) as rej FROM d12 GROUP BY prcno) AS TTd12 ON TSUB.dcnum=TTd12.prcno

LEFT JOIN(SELECT * FROM `d11`) AS Td11 ON TSUB.dcnum=Td11.rcno WHERE Td11.closedate='0000-00-00' ORDER BY date,pnum,scn";
				
				
				$result2 = $conn->query($query2);
				while($row2 = mysqli_fetch_array($result2))
				{	
					echo"<tr><td>".$row2['date']."</td>";
					echo"<td>".$row2['pnum']."</td>";
					echo"<td>".$row2['scn']."</td>";
					echo"<td>".$row2['fdcnum']."</td>";
					
					
					echo"<td>".$row2['invqty']."</td>";
					
					$qty_in_fg= $row2['total_rec_qty']-$row2['invqty'];
					echo"<td>".$qty_in_fg."</td>";
					
					//$qty_under_process = $row2['total_dc_qty']-($row2['rec']+$row2['rej']);
					
					$qty_under_process = $row2['total_dc_qty']-($row2['rec']+$row2['rej'])-($row2['total_rec_qty']-$row2['invqty']);
					
					if($qty_under_process>0)
					{
						echo"<td>".$qty_under_process."</td>";
					}
					else
					{
						echo"<td>"."0"."</td>";
					}
					
					//subcon_del - use this link
					echo "<td><a href='subcondbdel.php?dcnum=".$row2['fdcnum']."'>DELETE</a></td>";
					
					echo"</tr>";
				}
				
			?>
		</div>
		
</body>
</html>

