<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="DMR";
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
		<h4 style="text-align:center"><label>DAILY MATERIAL RECONCILATION </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'DMR')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				
				$tdate=date('Y-m-d');
				$ydate = date('Y-m-d', strtotime('-1 days'));
			
				
				
			echo'<table id="testTable" align="center">
				<tr>
					
					<th>RC/DC NUMBER</th>
					<th>PART NUMBER</th>
					<th>OPERATION</th>
					<th>CATEGORY</th>
					<th>OPENING QTY</th>
					<th>RECEIVED</th>
					<th>CLOSED</th>
					<th>SCRAP</th>
					<th>NEXTDAY OPENING QTY</th>
					<th>SHOULD BE CLOSING</th>
					<th>VARIANCE</th>
					<th>BOM</th>
					
					
					<th></th>
				</tr>';
				//$query = "SELECT DISTINCT date,operation,category,pnum,opstk.rcno,oqty,opisss.issued,opisss.received,opisss.used,opisss.scrap,nop.nqty FROM(SELECT edate,date,operation,category,pnum,rcno,unit,issued,received,used,scrap,qty FROM `opiss` UNION SELECT edate,date,operation,category,pnum,rcno,unit,0,0,0,0,qty FROM openingstock UNION SELECT edate,date,operation,category,pnum,rcno,unit,0,0,0,0,qty FROM nextdayopening ) AS dmr LEFT JOIN(SELECT nextdayopening.edate,rcno,qty AS nqty FROM nextdayopening WHERE nextdayopening.edate='2020-02-21') AS nop ON dmr.rcno=nop.rcno LEFT JOIN(SELECT openingstock.edate,rcno,qty AS oqty FROM openingstock WHERE openingstock.edate='2020-02-20') AS opstk ON dmr.rcno=opstk.rcno LEFT JOIN(SELECT opiss.edate,rcno,issued,received,used,scrap FROM opiss WHERE opiss.edate='2020-02-20') AS opisss ON dmr.rcno=opisss.rcno";
				//$query = "SELECT * FROM(SELECT rcno AS urcno FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno FROM opiss WHERE edate='2020-02-20') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-20') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno";
				
				//$query = "SELECT urcno,pnum,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT rcno AS urcno,pnum FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno,pnum FROM opiss WHERE edate='2020-02-21') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-21') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno";
				
				$query = "SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-02-21') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-21') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno";
				$result = $conn->query($query);
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr>";
					//echo"<td>".$row['date']."</td>";
					//echo"<td>".$row['operation']."</td>";
					//echo"<td>".$row['category']."</td>";
					
					echo"<td>".$row['urcno']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['operation']."</td>";
					echo"<td>".$row['category']."</td>";
					echo"<td>".$row['oqty']."</td>";
					echo"<td>".$row['issued']."</td>";
					echo"<td>".$row['used']."</td>";
					echo"<td>".$row['scrap']."</td>";
					echo"<td>".$row['nqty']."</td>";
					
					$shouldbeclosing=$row['oqty']+$row['issued']-$row['used']-$row['scrap'];
					echo"<td>".$shouldbeclosing."</td>";
					
					$variance=$row['nqty']-$shouldbeclosing;
					echo"<td>".$variance."</td>";
					
					
					
					
					
					
					echo"</tr>";
				}
				
				
			?>
		</div>
		
</body>
</html>

