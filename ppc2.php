<?php
$t2=0;
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="PRODUCTION PLAN RECOMMENDED REPORT";
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
	<link rel="stylesheet" type="text/css" href="ppc.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
		<h4 style="text-align:center"><label> PRODUCTION PLAN RECOMMENDED REPORT </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<script>
		$("number").keypress(function(e) {
			if (isNaN(String.fromCharCode(e.which))) e.preventDefault();
		});
	</script>
	<br><br>
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>PART NUMBER</th>
						
						<th>CNC PLAN</th>
						
						<th>MANUAL PLAN</th>
						
						<th>PRIORITY</th>
						
						
						<th>ENTER CNC</th>
						<th>ENTER MANUAL</th>
						
					  </tr>';
				
				//$query2 = "SELECT * FROM (SELECT DISTINCT T.pnum AS pn,part,type,monthly,vmi_fg,sf,SUM(stock) AS stock,IF(SUM(orderbook.qty) IS NULL,0,SUM(orderbook.qty)) AS qty FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T LEFT JOIN orderbook ON T.pnum=orderbook.pnum GROUP BY T.pnum) AS CNC LEFT JOIN (SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1,IF(SUM(orderbook.qty) IS NULL,0,SUM(orderbook.qty)) AS qty1 FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' AND d11.operation NOT LIKE 'Semifinished%' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt NOT LIKE 'Semi%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T LEFT JOIN orderbook ON T.pnum=orderbook.pnum GROUP BY pnum) AS MANUAL ON CNC.pn=MANUAL.pnum LEFT JOIN (SELECT * FROM (SELECT demandmaster.pnum,order_date,req_date,commit,c,qty AS cqty FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum LEFT JOIN (SELECT DISTINCT pnum,COUNT(*) AS c FROM (SELECT demandmaster.pnum,order_date,req_date,commit FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum) AS C GROUP BY pnum) AS CC ON demandmaster.pnum=CC.pnum) AS C) AS CC ON CC.pnum=CNC.pn";
				$query2 = "SELECT pn,CNC.part,type,monthly,vmi_fg,sf,stock,stock1,order_date,req_date,commit,IF(c IS NULL,0,c) AS c,w1,w2,w3,cqty FROM (SELECT DISTINCT T.pnum AS pn,part,type,monthly,vmi_fg,sf,SUM(stock) AS stock FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY T.pnum) AS CNC LEFT JOIN (SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1 FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation LIKE 'FG For S/C' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt NOT LIKE 'Semi%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY pnum) AS MANUAL ON CNC.pn=MANUAL.pnum LEFT JOIN (SELECT * FROM (SELECT demandmaster.pnum,order_date,req_date,commit,c,qty AS cqty FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum LEFT JOIN (SELECT DISTINCT pnum,IF(COUNT(*) IS NULL,0,COUNT(*)) AS c FROM (SELECT demandmaster.pnum,order_date,req_date,commit FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum) AS C GROUP BY pnum) AS CC ON demandmaster.pnum=CC.pnum) AS C) AS CC ON CC.pnum=CNC.pn LEFT JOIN (SELECT T1.pnum,w1,w2,w3 FROM (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w1 FROM `orderbook` WHERE commit>='2019-08-07' AND commit<'2019-08-28' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w2 FROM `orderbook` WHERE commit>='2019-08-28' AND commit<'2019-09-05' GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w3 FROM `orderbook` WHERE commit>='2019-09-05' AND commit<'2019-09-12' GROUP BY pnum) AS T3 ON T1.pnum=T3.pnum) AS OB ON CC.pnum=OB.pnum";
				$result2 = $conn->query($query2);
				$pnum="";
				$rn=1;
				while($row1 = mysqli_fetch_array($result2))
				{
					$md=$row1['monthly'];
					$fg=$row1['vmi_fg'];
					$sf=$row1['sf'];
					$ob=$row1['w1'];
					$md1=$row1['monthly'];
					$fg1=$row1['vmi_fg'];
					$sf1=$row1['sf'];
					$ob1=$row1['w1'];
					$obw4=$row1['w2'];
					$obw5=$row1['w3'];
					$obw4_1=$row1['w2'];
					$obw5_1=$row1['w3'];
					$mw1=0;
					$mw2=0;
					$mw3=0;
					$cw1=0;
					$cw2=0;
					$cw3=0;
					// ROW SPAN FOR FIRST ORDER FILTER
					if($row1['c']<4)
					{
						$f=1;
					}
					else
					{
						$f=round($row1['c']/3,0);
					}
					
					
					$stock=$row1['stock'];
					$stock1=$row1['stock1'];
					
					
					$t2=0;
					if($row1['part']==$pnum)
					{
						//echo "<script>alert('coming')</script>";
						$rn=$rn+1;
					}
					else
					{
						$rn=1;
						$pnum=$row1['pn'];
					}
					$rs1=$row1['c'];
					if($rs1<3)
					{
						$rs1=3;
					}
					else
					{
						
					}
						
					
					// WEEK 1 CALCULATION
					
					$carry=0;
					$carry1=0;
					
					if($row1['type']=="Kanban")
					{
						//CNC
						$d=($fg+$sf+($md/4))-$stock;
						//MANUAL
						$d1=($fg1+$sf1+($md/4))-$stock1;
					}
					if($row1['type']=="Regular")
					{
						//CNC
						//$d=($ob+$fg+$sf)-$stock; FG AND SF VMI INCLUDED
						$d=($ob)-$stock;
						//MANUAL
						$d1=($ob1+$fg1+$sf1)-$stock1;
					}
					if($row1['type']=="Stranger")
					{
						//CNC
						$d=($ob)-$stock;
						//MANUAL
						$d1=($ob1)-$stock1;
						//CNC
						if($ob==0)
						{
							$d=0;
						}
						//MANUAL
						if($ob1==0)
						{
							$d1=0;
						}
					}
					//CNC
					if($d<0)
					{
						//echo"<td>0</td>";
						//echo"<td>".$d."</td>";
					}
					else
					{
						$cw1=round($d,0);
						//echo"<td>".round($d,0)."</td>";
					}
					//MANUAL
					if($d1<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						//echo"<td>".round($d1,0)."</td>";
						$mw1=round($d1,0);
					}
					//CNC
					if($d<0)
					{
						$carry=$d;
					}
					else
					{
						$carry=0;
					}
					//MANUAL
					if($d1<0)
					{
						$carry1=$d1;
					}
					else
					{
						$carry1=0;
					}
					
					
					// WEEK 2 CALCULATION
					
					
					if($row1['type']=="Kanban")
					{
						//CNC
						$d=($md/4)+$carry;
						//MANUAL
						$d1=($md1/4)+$carry1;
					}
					if($row1['type']=="Regular")
					{
						//CNC
						$d=($md/4)+$carry;
						//MANUAL
						$d1=($md1/4)+$carry1;
					}
					if($row1['type']=="Stranger")
					{
						//CNC
						$d=$obw4-$carry;
						//MANUAL
						$d1=$obw4_1-$carry1;
						//CNC
						if($obw4==0)
						{
							$d=0;
						}
						//MANUAL
						if($obw4_1==0)
						{
							$d1=0;
						}
					}
					//CNC
					if($d<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						$cw2=round($d,0);
						//echo"<td>".round($d,0)."</td>";
					}
					//MANUAL
					if($d1<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						//echo"<td>".round($d1,0)."</td>";
						$mw2=round($d1,0);
					}
					
					//CNC
					if($d<0)
					{
						$carry=$d;
					}
					else
					{
						$carry=0;
					}
					
					//MANUAL
					if($d1<0)
					{
						$carry1=$d1;
					}
					else
					{
						$carry1=0;
					}
					
					
					// WEEK 3 CALCULATION
					
					
					if($row1['type']=="Kanban")
					{
						//CNC
						$d=($md/4)+$carry;
						//MANAUL
						$d1=($md1/4)+$carry1;
					}
					if($row1['type']=="Regular")
					{
						//CNC
						$d=($md/4)+$carry;
						//MANUAL
						$d1=($md1/4)+$carry1;
					}
					if($row1['type']=="Stranger")
					{
						//CNC
						$d=$obw5-$carry;
						//MANUAl
						$d1=$obw5_1-$carry1;
						//CNC
						if($obw5==0)
						{
							$d=0;
						}
						//MANUAL
						if($obw5_1==0)
						{
							$d1=0;
						}
					}
					//CNC
					if($d<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						$cw3=round($d,0);
						//echo"<td>".round($d,0)."</td>";
					}
					//MANUAL
					if($d1<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						//echo"<td>".round($d1,0)."</td>";
						$mw3=round($d1,0);
					}
					
					//CNC
					if($d<0)
					{
						$carry=$d;
					}
					else
					{
						$carry=0;
					}
					//MANUAL
					if($d1<0)
					{
						$carry1=$d1;
					}
					else
					{
						$carry1=0;
					}
					
					//PRIORITY SETTING FOR w2 & W3
					if($row1['type']=="Kanban")
					{
						$p2=2;
						$p3=5;
					}
					else if($row1['type']=="Regular")
					{
						$p2=3;
						$p3=6;
					}
					else
					{
						$p2=4;
						$p3=7;
					}
					
					
					if(($row1['c']<2 || $row1['c']=="") && $rn==1)
					{
						echo"<tr><td>".$row1['pn']."</td>";
						echo"<td>".$cw1."</td>";
						echo"<td>".$mw1."</td>";
						echo"<td>1</td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo "</tr>";
						
						
						echo"<tr><td>".$row1['pn']."</td>";
						echo"<td>".$cw2."</td>";
						echo"<td>".$mw2."</td>";
						echo"<td>".$p2."</td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"</tr>";
						
						echo"<tr><td>".$row1['pn']."</td>";
						echo"<td>".$cw3."</td>";
						echo"<td>".$mw3."</td>";
						echo"<td>".$p3."</td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"</tr>";
					}
					else if($row1['c']==2 && $rn==1)
					{
						$f=1;
						$s=2;
						echo"<tr><td>".$row1['pn']."</td>";
						echo"<td>".$cw1."</td>";
						echo"<td>".$mw1."</td>";
						echo"<td>1</td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='number' contenteditable='TRUE'></td></tr>";
							
						$row1 = mysqli_fetch_array($result2);
						
						echo"<tr><td>".$row1['pn']."</td>";
						echo"<td rowspan='$f'>".$cw2."</td>";
						echo"<td rowspan='$f'>".$mw2."</td>";
						echo"<td rowspan='$f'>".$p2."</td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td></tr>";
						
						echo"<tr><td>".$row1['pn']."</td>";
						echo"<td rowspan='$f'>".$cw3."</td>";	
						echo"<td rowspan='$f'>".$mw3."</td>";	
						echo"<td rowspan='$f'>".$p3."</td>";	
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";	
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td></tr>";	
					
					}
					else
					{
						$f=round($row1['c']/3,0);
						$s=round($row1['c']/3,0);
						$t=$row1['c']-($f+$s);
						
						echo"<tr><td>".$row1['pn']."</td>";
						echo"<td rowspan='$f'>".$cw1."</td>";
						echo"<td rowspan='$f'>".$mw1."</td>";
						echo"<td rowspan='$f'>1</td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td></tr>";
						
						do{
							
							if($f>1)
							{
								$row1 = mysqli_fetch_array($result2);
								echo "<tr>";
							}
							$f--;
						}while($f>0);
						
						$row1 = mysqli_fetch_array($result2);
						echo"<tr><td>".$row1['pn']."</td>";
						echo"<td rowspan='$s'>".$cw2."</td>";
						echo"<td rowspan='$s'>".$mw2."</td>";
						echo"<td rowspan='$s'>".$p2."</td>";
						echo"<td rowspan='$s' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$s' class='number' contenteditable='TRUE'></td></tr>";
						
						do{
							
							if($s>1)
							{
								$row1 = mysqli_fetch_array($result2);
								echo "<tr>";
							}
							$s--;
						}while($s>0);
						
						$row1 = mysqli_fetch_array($result2);
						echo"<tr><td>".$row1['pn']."</td>";
						echo"<td rowspan='$t'>".$cw3."</td>";
						echo"<td rowspan='$t'>".$mw3."</td>";
						echo"<td rowspan='$t'>".$p3."</td>";
						echo"<td rowspan='$t' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$t' class='number' contenteditable='TRUE'></td>";
						do{
							
							if($t>1)
							{
								$row1 = mysqli_fetch_array($result2);
								echo "<tr>";
							}
							$t--;
						}while($t>0);
					}
				}
			?>
		</div>
		
</body>
</html>