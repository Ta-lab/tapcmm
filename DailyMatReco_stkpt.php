<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['user']=="123")
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
		<h4 style="text-align:center"><label>DAILY MATERIAL RECONCILATION STOCKING POINT</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'DMR')" value="Export to Excel">
	</div>
	<br><br>
	
	<script>
			function reload(form)
			{
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				self.location='DailyMatReco_stkpt.php?f='+s0+'&tt='+s1;
			}
	</script>
	
	<div class="divclass">
	<!--<div class="find">
				<label>OPENING DATE</label>
							<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
							/*if(isset($_GET['f']))
							{
								echo $_GET['f'];
							}
							else
							{
								echo date('Y-m-d',strtotime('-1 days'));
							}
							?>"/>
			</div>
			
			<div class="find1">
				<label>CLOSING DATE</label>
							<input type="date" id="tt" name="tt"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['tt']))
							{
								echo $_GET['tt'];
							}
							else
							{
								echo date('Y-m-d');
							}*/
							?>"/>
			</div>--!>

			<br><br><br>
		
			<?php
			
			
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-1 days'));
					$tt = date('Y-m-d');
					$ydate=$f;
					$tdate=$tt;
					
				}
				else
				{
					$f = $_GET['f'];
					$tt= $_GET['tt'];
					$ydate=$f;
					$tdate=$tt;
				}
				
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				
				//$tdate=date('Y-m-d');
				//$ydate = date('Y-m-d', strtotime('-1 days'));
			
				
				
			echo'<table id="testTable" align="center">
				<tr>
					
					<th>RC/DC NUMBER</th>
					<th>PART NUMBER</th>
					<th>STOCKING POINT</th>
					<th>YESTERDAY OPENING STOCK</th>
					<th>RECEIVED</th>
					<th>ISSUED</th>
					<th>TODAY OPENING STOCK</th>
					<th>SHOULD BE OPENING</th>
					<th>VARIANCE</th>
					<th>FOREMAN</th>
					
					<th></th>
				</tr>';
				
				//$query = "SELECT urcno,IF(stkpt IS NULL,stkptrec,stkpt) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,iF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT rcno AS urcno FROM `stkptopening` UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-02-25' AND d12.date<='2020-02-25' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening) AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-02-25' AND d12.date<='2020-02-25' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening) AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				
				//$query = "SELECT urcno,IF(stkpt IS NULL,stkptrec,stkpt) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,iF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT rcno AS urcno FROM `stkptopening` UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-02-26' AND d12.date<='2020-02-26' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening) AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-02-26' AND d12.date<='2020-02-26' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening) AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				
				//working query
				//$query = "SELECT DISTINCT urcno,IF(stkpt IS NULL,stkptrec,stkpt) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,iF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT DISTINCT rcno AS urcno FROM `stkptopening` WHERE edate='2020-02-27' UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-02-27' AND d12.date<='2020-02-27' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening WHERE edate='2020-02-27') AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-02-27' AND d12.date<='2020-02-27' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT DISTINCT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening WHERE edate='2020-02-28') AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				
				//$query = "SELECT DISTINCT urcno,IF(stkpt IS NULL,stkptrec,stkpt) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,iF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT DISTINCT rcno AS urcno FROM `stkptopening` WHERE edate='2020-03-02' UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-03-02' AND d12.date<='2020-03-02' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening WHERE edate='2020-03-02') AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-03-02' AND d12.date<='2020-03-02' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT DISTINCT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening WHERE edate='2020-03-03') AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				
				//final
				//$query = "SELECT DISTINCT urcno,IF(stkpt IS NULL,stkptrec,stkpt) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT DISTINCT rcno AS urcno FROM `stkptopening` WHERE edate='$ydate' UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening WHERE edate='$ydate') AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT DISTINCT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening WHERE edate='$tdate') AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				//echo "SELECT DISTINCT urcno,IF(stkpt IS NULL,stkptrec,stkpt) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT DISTINCT rcno AS urcno FROM `stkptopening` WHERE edate='$ydate' UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening WHERE edate='$ydate') AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT DISTINCT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening WHERE edate='$tdate') AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				
				//$query = "SELECT DISTINCT urcno,IF(stkpt IS NULL,stkptrec,stkpt) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT DISTINCT rcno AS urcno FROM `stkptopening` WHERE edate='2020-03-09' AND stkpt!='FG For Scrap' UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-03-09' AND d12.date<='2020-03-09' AND d12.pnum!='' and stkpt!='' AND stkpt!='FG For Scrap' GROUP BY prcno,stkpt) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening WHERE edate='2020-03-09') AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='2020-03-09' AND d12.date<='2020-03-09' AND d12.pnum!='' and stkpt!='' AND stkpt!='FG For Scrap' GROUP BY prcno,stkpt) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT DISTINCT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening WHERE edate='2020-03-10' AND stkpt!='FG For Scrap') AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				
				//$query = "SELECT DISTINCT urcno,IF(stkpt IS NULL,stkptrec,stkpt) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT DISTINCT rcno AS urcno FROM `stkptopening` WHERE edate='$ydate' AND stkpt!='FG For Scrap' UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' AND stkpt!='FG For Scrap' GROUP BY prcno,stkpt) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening WHERE edate='$ydate') AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' AND stkpt!='FG For Scrap' GROUP BY prcno,stkpt) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT DISTINCT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening WHERE edate='$tdate' AND stkpt!='FG For Scrap') AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				$query = "SELECT DISTINCT urcno,IF(stkptrec IS NULL,stkpt,stkptrec) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT DISTINCT rcno AS urcno FROM `stkptopening` WHERE edate='$ydate' AND stkpt!='FG For Scrap' UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' AND stkpt!='FG For Scrap' GROUP BY prcno,stkpt) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening WHERE edate='$ydate') AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' AND stkpt!='FG For Scrap' GROUP BY prcno,stkpt) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT DISTINCT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening WHERE edate='$tdate' AND stkpt!='FG For Scrap') AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				//echo "SELECT DISTINCT urcno,IF(stkpt IS NULL,stkptrec,stkpt) AS stkpt,stkptrec,pnumrec,IF(pnum IS NULL,pnumrec,pnum) AS upnum,qty AS oqty,IF(received IS NULL,0,received) AS received,IF(issued IS NULL,0,issued) AS issued,s,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT DISTINCT rcno AS urcno FROM `stkptopening` WHERE edate='$ydate' AND stkpt!='FG For Scrap' UNION SELECT prc FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' AND stkpt!='FG For Scrap' GROUP BY prcno,stkpt) AS yrec) AS yyrec LEFT JOIN(SELECT stkpt,rcno,pnum,qty FROM stkptopening WHERE edate='$ydate') AS openstk ON yyrec.urcno=openstk.rcno LEFT JOIN(SELECT prc,stkpt AS stkptrec,pnum AS pnumrec,received,issued,s FROM(select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived) AS received,sum(partissued) AS issued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.date>='$ydate' AND d12.date<='$ydate' AND d12.pnum!='' and stkpt!='' AND stkpt!='FG For Scrap' GROUP BY prcno,stkpt) AS reciss) AS recviss ON yyrec.urcno=recviss.prc LEFT JOIN(SELECT DISTINCT rcno,pnum AS npnum,qty AS nqty FROM stkptnxtopening WHERE edate='$tdate' AND stkpt!='FG For Scrap') AS nxtopenstk ON yyrec.urcno=nxtopenstk.rcno";
				
				$result = $conn->query($query);
				while($row = mysqli_fetch_array($result))
				{
					//if($row['received']!=0 || $row['issued']!=0)
					//if($row['urcno']!='B2020001667' && $row['urcno']!='E2020001441' && $row['urcno']!='B2020001000' && $row['urcno']!='E2020001422' && $row['urcno']!='E2020000046' && $row['urcno']!='B2020001584' && $row['urcno']!='E2020000981' && $row['urcno']!='E2020001201')  
					{
					echo"<tr>";
					echo"<td>".$row['urcno']."</td>";
					echo"<td>".$row['upnum']."</td>";
					echo"<td>".$row['stkpt']."</td>";
					echo"<td>".round($row['oqty'],2)."</td>";
					echo"<td>".round($row['received'],2)."</td>";
					echo"<td>".round($row['issued'],2)."</td>";
					echo"<td>".round($row['nqty'],2)."</td>";
					
					$shouldbeclosing=$row['oqty']+$row['received']-$row['issued'];
					echo"<td>".round($shouldbeclosing,2)."</td>";
					
					$variance=$row['nqty']-$shouldbeclosing;
					echo"<td>".round($variance,2)."</td>";
					
					$pnum=$row['upnum'];
					$query1 = "SELECT DISTINCT pnum,foreman FROM m13 WHERE pnum LIKE '%$pnum%'";
					$result1 = $conn->query($query1);
					$row1 = mysqli_fetch_array($result1);
					echo"<td>".$row1['foreman']."</td>";
					
					
					
					echo"</tr>";
					}
				}
				
			?>
		</div>
		
</body>
</html>

