<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="111")
	{
		$id=$_SESSION['user'];
		$activity="KANBAN STOCK REPORT";
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
		<h4 style="text-align:center"><label> KANBAN STATUS REPORT </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
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
						<th>INVOICE PART NUMBER</th>
						<th>PART NUMBER</th>
						<th>MONTHY DEMAND</th>
						<th>FG VMI STOCK</th>
						<th>FG ACTUAL STOCK</th>
						<th>FG SHORTFALL</th>
						<th>FG SURPLUS QTY</th>
						<th>SF VMI STOCK</th>
						<th>SF ACTUAL STOCK</th>
						<th>SF SHORTFALL</th>
						<th>SF SURPLUS QTY</th>
					  </tr>';
				//$query2 = "SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1  FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation LIKE 'FG For S/C' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt NOT LIKE 'Semi%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T WHERE type='kanban' GROUP BY pnum";
				$query2 = "SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1,IF(SUM(s) IS NULL,0,SUM(s)) AS SF_STOCK  FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation LIKE 'FG For S/C' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt LIKE 'FG%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T LEFT JOIN (SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt LIKE 'Semi%') AS SFS ON T.pnum=SFS.pnum WHERE type='kanban' GROUP BY pnum";
				$result2 = $conn->query($query2);
				while($row1 = mysqli_fetch_array($result2))
				{
					echo"<tr><td>".$row1['pnum']."</td>";
					echo"<td>".$row1['part']."</td>";
					echo"<td>".$row1['monthly1']."</td>";
					echo"<td>".$row1['vmi_fg1']."</td>";
					echo"<td>".$row1['stock1']."</td>";
					if($row1['vmi_fg1']<$row1['stock1'])
					{
						echo"<td>0</td>";
						echo"<td>".($row1['stock1']-$row1['vmi_fg1'])."</td>";
					}
					else
					{
						echo"<td>".($row1['vmi_fg1']-$row1['stock1'])."</td>";
						echo"<td>0</td>";
					}
					echo"<td>".$row1['sf1']."</td>";
					echo"<td>".$row1['SF_STOCK']."</td>";
					if($row1['sf1']<$row1['SF_STOCK'])
					{
						echo"<td>0</td>";
						echo"<td>".($row1['SF_STOCK']-$row1['sf1'])."</td>";
					}
					else
					{
						echo"<td>".($row1['sf1']-$row1['SF_STOCK'])."</td>";
						echo"<td>0</td>";
					}
					echo"</tr>";
				}
			?>
		</div>
		
</body>
</html>




















