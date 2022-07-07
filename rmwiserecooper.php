<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="RM WISE RECON";
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
		<h4 style="text-align:center"><label> RM WISE RECONCILATION OPERATION</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<br><br>
	<div class="divclass">
			<?php
				
				$date = date('Y-m-d', strtotime('-1 days'));
				//$date = date('Y-m-d');
				//echo $date;
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>DATE</th>
						<th>RAW MATERIAL</th>
						<th>OPERATION</th>
						<th>RC NO</th>
						<th>PART NUMBER</th>
						<th>UNIT</th>
						<th>ISSUED</th>
						<th>RECEIVED</th>
						<th>USED</th>
						<th>SCRAP</th>
						
						
					  </tr>';
				
				//$query2 = "SELECT T2.rcno,T2.operation,T2.date,T2.closedate,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2019-12-01' AND d11.date<='2019-12-31' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno,t2.operation";
				
				//workingquery
				//$query2 = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2019-12-01' AND d11.date<='2019-12-31' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='2019-12-01' AND d14.date<='2019-12-31' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
				
				//$query2 = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-02-13' AND d11.date<='2020-02-13' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='2020-02-13' AND d14.date<='2020-02-13' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
				
				$query2 = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
				
				$result2 = $conn->query($query2);
				while($row = mysqli_fetch_array($result2))
				{
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['category']."</td>";
					echo"<td>".$row['operation']."</td>";
					echo"<td>".$row['rcno']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['unit']."</td>";
					echo"<td>".$row['issqty']."</td>";
					echo"<td>".$row['received']."</td>";
					echo"<td>".round($row['used'],2)."</td>";
					echo"<td>".$row['rejected']."</td>";
					
					if($row['closedate']=='0000-00-00'){
						//echo"<td>".'0'."</td>";
					}else{
						//echo"<td>".round($row['notused'],2)."</td>";
					}
					
				
				
				/*$query3 = "SELECT date,rcno,ret FROM `d14` WHERE rcno='".$row['rcno']."' AND date>='2019-12-01' AND date<='2019-12-31'";
				$result3 = $conn->query($query3);
				$row3 = mysqli_fetch_array($result3);
				if($row3['ret']==''){
					//echo"<td>".'0'."</td>";
				}else{
					//echo"<td>".$row3['ret']."</td>";
				}*/
				
				
				
				echo"</tr>";
				
				}
				
				
				
				
			?>
		</div>
		
</body>
</html>