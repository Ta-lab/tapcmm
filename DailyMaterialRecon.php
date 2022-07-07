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
					
					
					<th>INPUT BOM</th>
					<th>OUTPUT BOM</th>
					
					<th>OPENING IN KG</th>
					<th>RECEIVED IN KG</th>
					<th>CLOSED IN KG</th>
					<th>SCRAP IN KG</th>
					<th>NEXTDAY OPENING IN KG</th>
					<th>SHOULD BE CLOSING IN KG</th>
					<th>VARIANCE IN KG</th>
					
					
					
					
					
					<th>SHOULD BE CLOSING</th>
					<th>VARIANCE</th>
					
					<th></th>
				</tr>';
				//$query = "SELECT DISTINCT date,operation,category,pnum,opstk.rcno,oqty,opisss.issued,opisss.received,opisss.used,opisss.scrap,nop.nqty FROM(SELECT edate,date,operation,category,pnum,rcno,unit,issued,received,used,scrap,qty FROM `opiss` UNION SELECT edate,date,operation,category,pnum,rcno,unit,0,0,0,0,qty FROM openingstock UNION SELECT edate,date,operation,category,pnum,rcno,unit,0,0,0,0,qty FROM nextdayopening ) AS dmr LEFT JOIN(SELECT nextdayopening.edate,rcno,qty AS nqty FROM nextdayopening WHERE nextdayopening.edate='2020-02-21') AS nop ON dmr.rcno=nop.rcno LEFT JOIN(SELECT openingstock.edate,rcno,qty AS oqty FROM openingstock WHERE openingstock.edate='2020-02-20') AS opstk ON dmr.rcno=opstk.rcno LEFT JOIN(SELECT opiss.edate,rcno,issued,received,used,scrap FROM opiss WHERE opiss.edate='2020-02-20') AS opisss ON dmr.rcno=opisss.rcno";
				//$query = "SELECT * FROM(SELECT rcno AS urcno FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno FROM opiss WHERE edate='2020-02-20') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-20') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno";
				
				//$query = "SELECT urcno,pnum,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT rcno AS urcno,pnum FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno,pnum FROM opiss WHERE edate='2020-02-21') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-21') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno";
				
				//working query
				//$query = "SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-02-21') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-21') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno";
				
				$query = "SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,obom,IF(tot IS NULL,bom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-02-21') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-21') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum";
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
					
					//echo"<td>".$row['bom']."</td>";
					//echo"<td>".$row['obom']."</td>";
					
					echo"<td>".$row['tot']."</td>";
					echo"<td>".$row['tot1']."</td>";
					
					
					$pnum=$row['pnum'];
					$operation=$row['operation'];
					$query1 = "SELECT * FROM `processmaster` WHERE pnum='$pnum' AND operation='$operation'";
					$result1 = $conn->query($query1);
					$row1 = mysqli_fetch_array($result1);
					
					if($row['operation']=='CNC_SHEARING')
					{
						echo"<td>".$row['oqty']."</td>";
						echo"<td>".$row['issued']."</td>";
						echo"<td>".$row['used']."</td>";
						echo"<td>".$row['scrap']."</td>";
						echo"<td>".$row['nqty']."</td>";
						
						$shouldbeclosingkg=$row['oqty']+$row['issued']-$row['used']-$row['scrap'];
						echo"<td>".$shouldbeclosingkg."</td>";
					
						$variancekg=$row['nqty']-$shouldbeclosingkg;
						echo"<td>".$variancekg."</td>";
					
						
					}
					else{
						//opening
						if($row1['inbom']=='1'){
							$openingkg=$row['oqty']*$row['tot'];
							echo"<td>".$row['oqty']*$row['tot']."</td>";
						}
						else{
							$openingkg=$row['oqty']*$row['tot1'];
							echo"<td>".$row['oqty']*$row['tot1']."</td>";
						}
						
						//issued
						if($row1['opbom']=='1'){
							$issuedkg=$row['issued']*$row['tot'];
							echo"<td>".$row['issued']*$row['tot']."</td>";
						}
						else{
							$issuedkg=$row['issued']*$row['tot1'];
							echo"<td>".$row['issued']*$row['tot1']."</td>";
						}
						
						//used
						if($row1['opbom']=='1'){					
							$usedkg=$row['used']*$row['tot1'];
							echo"<td>".$row['used']*$row['tot1']."</td>";
						}
						else{
							$usedkg=$row['used']*$row['tot'];
							echo"<td>".$row['used']*$row['tot']."</td>";
						}
						
						//scrap
						if($row1['inbom']=='1'){	
							$scrapkg=$row['scrap']*$row['tot'];
							echo"<td>".$row['scrap']*$row['tot']."</td>";
						}
						else{
							$scrapkg=$row['scrap']*$row['tot1'];
							echo"<td>".$row['scrap']*$row['tot1']."</td>";
						}
						
						//nextdayopening
						if($row1['inbom']=='1'){
							$nextdayopeningkg=$row['nqty']*$row['tot'];	
							echo"<td>".$row['nqty']*$row['tot']."</td>";
						}
						else{
							$nextdayopeningkg=$row['nqty']*$row['tot1'];
							echo"<td>".$row['nqty']*$row['tot1']."</td>";
						}	
							
						$shouldbeclosingkg=$openingkg+$issuedkg-$usedkg-$scrapkg;
						echo"<td>".$shouldbeclosingkg."</td>";

						$variancekg=$nextdayopeningkg-$shouldbeclosingkg;
						echo"<td>".$variancekg."</td>";
						
						
					}	
					
					
					
					
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

