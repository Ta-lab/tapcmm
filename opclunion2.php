<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['user']=="123" || $_SESSION['user']=="100")
	{
		$id=$_SESSION['user'];
		$activity="WM MP REPORT";
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
		<h4 style="text-align:center"><label> WEEKLY/MONTHLY MATERIAL STOCK PROCESSING </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'OPENING')" value="Export to Excel">
	</div>
	
	<br><br>
	
	<script>
			function reload(form)
			{
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				self.location='opclunion2.php?f='+s0+'&tt='+s1;
			}
	</script>
	
	<div class="divclass">
	<div class="find">
				<label>FROM DATE</label>
							<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['f']))
							{
								echo $_GET['f'];
							}
							else
							{
								echo date('Y-m-d',strtotime('-7 days'));
							}
							?>"/>
			</div>
			
			<div class="find1">
				<label>TO DATE</label>
							<input type="date" id="tt" name="tt"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['tt']))
							{
								echo $_GET['tt'];
							}
							else
							{
								echo date('Y-m-d');
							}
							?>"/>
			</div>

	<br><br><br>
	
	
	<div class="divclass">
			<?php
			
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-7 days'));
					$tt = date('Y-m-d');
				}
				else
				{
					$f = $_GET['f'];
					$tt= $_GET['tt'];
				}
				
				
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
			
			echo'<table id="testTable" align="center">
				<tr>
					<th>OPERATION</th>
					<th>RCNO</th>
					<th>PARTNUMBER</th>
					<th>FOREMAN</th>
					<th>CATEGORY</th>
					<th>OPENING STOCK(KG)</th>
					<th>RECEIVED QTY(KG)</th>
					<th>OK QUANTITY(KG)</th>
					<th>SCRAP QUANTITY(KG)</th>
					<th>RETURN QUANTITY(KG)</th>
					<th>CLOSING STOCK(KG)</th>
					<th>SHOULD BE CLOSING(KG)</th>
					<th>DIFFERENCE(KG)</th>
					
					
					<th></th>
				</tr>';
								
				
				$query = "SELECT DISTINCT *,(openingstk_KG+ret) AS OPENKG FROM(SELECT DISTINCT *,IF(finalcategory IS NULL,'A-Coil',finalcategory) AS FCat,IF(unit LIKE '%Nos%',openingstk*finalbom,openingstk) AS openingstk_KG,IF(unit LIKE '%Nos%',opissqty*finalbom,opissqty) AS opissqty_KG,IF(unit LIKE '%Nos%',weekrec*finalbom,weekrec) AS weekrec_KG,IF(unit LIKE '%Nos%',weekrej*finalbom,weekrej) AS weekrej_KG,IF(unit LIKE '%Nos%',closingstk*finalbom,closingstk) AS closingstk_KG FROM(SELECT *,(wreceived*bom) AS weekrec,(wrejected*bom) AS weekrej,(wreceived*bom+wrejected*bom) AS weekused,IF(ibom IS NULL,tot,IF(tot IS NULL,ibom,ibom)) AS finalbom,IF(category IS NULL,invcategory,IF(invcategory IS NULL,category,category)) AS finalcategory,IF(foreman IS NULL,'Bala',foreman) AS finalforeman FROM(SELECT * FROM
(SELECT * FROM(SELECT DISTINCT rcno,operation,foreman,0 AS fore,date,closedate,issqty,0 AS opissqty,pnum,received,rejected,rm,unit,used,notused AS openingstk,0 AS opnotused,days,bom,rate,per,value FROM(SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*((T7.useage/T7.tot)*rate))/per)*v)/100,(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per)*v)/100)  as value,v FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<'$f' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.closedate,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND (d11.closedate='0000-00-00' OR d11.closedate>='$f') AND d11.operation!='FG For Invoicing' AND d11.date<'$f' AND d12.date<'$f' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper GROUP BY T2.rcno HAVING notused>0 ORDER BY `T2`.`operation` ASC) AS OPENING 
UNION 
SELECT DISTINCT rcno,operation,foreman,foreman1,date,closedate,0,opissqty,pnum,received,rejected,rm,unit,used,0,notused,days,bom,rate,per,value FROM(SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.closedate,T2.issqty AS opissqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date>='$f' AND d12.date<='$tt' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,d11.closedate,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date>='$f' AND d11.date<='$tt' AND d11.operation!='FG For Invoicing' AND d11.pnum!='' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN (SELECT rcno,dcnum,scn FROM `d11` LEFT JOIN (SELECT dcnum,scn FROM `dc_det`) AS T8 ON d11.rcno=CONCAT('DC-',T8.dcnum) WHERE operation='FG For S/C' AND closedate='0000-00-00') AS T9 ON T2.rcno=T9.rcno GROUP BY T2.rcno order by t2.date,t2.rcno,t2.operation) AS OPERATION) AS OPENOPER

LEFT JOIN(SELECT DISTINCT clrcno,operation AS cloperation,foreman AS clforeman,date AS cldate,closedate AS clclosedate,issqty AS clissqty,pnum AS clpnum,received AS clreceived,rejected AS clrejected,rm AS clrm,unit AS clunit,used AS clused,notused AS closingstk,days AS cldays,bom AS clbom,rate AS clrate,per AS clper,value AS clvalue FROM(SELECT T2.rcno AS clrcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*((T7.useage/T7.tot)*rate))/per)*v)/100,(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per)*v)/100)  as value,v FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$tt' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,d11.closedate,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND (d11.closedate='0000-00-00' OR d11.closedate>='$tt') AND d11.operation!='FG For Invoicing' AND d11.date<='$tt' AND d12.date<='$tt' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper GROUP BY T2.rcno HAVING notused>0 order by t2.operation ) AS CLOSING) AS CLOSINGSTK ON OPENOPER.rcno=CLOSINGSTK.clrcno
) AS OPCL
LEFT JOIN(SELECT prcno,d12.pnum AS wpnum,SUM(partreceived) as wreceived,SUM(qtyrejected) as wrejected FROM `d12` WHERE d12.date>='$f' AND d12.date<='$tt' AND prcno!='' GROUP BY d12.prcno) AS WEEKRECV ON WEEKRECV.prcno=OPCL.rcno) AS FINALQ 

LEFT JOIN(SELECT date AS retdate,rcno AS retrcno,IF(ret IS NULL,0,ret) AS ret FROM `d14` WHERE date>='$f' AND date<='$tt' ) AS RET ON FINALQ.rcno=RET.retrcno

LEFT JOIN(SELECT DISTINCT pnum AS m13pnum,useage AS ibom FROM `m13`) AS m13bom ON pnum=m13bom.m13pnum

LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum

LEFT JOIN(SELECT DISTINCT mpnum,category FROM(SELECT DISTINCT pnum AS mpnum FROM `m13`) AS rmcat LEFT JOIN(SELECT pnum,category FROM rmcategory) AS rmcat1 ON rmcat.mpnum=rmcat1.pnum GROUP BY mpnum) AS RMCATEG ON pnum=RMCATEG.mpnum

LEFT JOIN(SELECT invpnum AS rminvpnum,category AS invcategory FROM (SELECT DISTINCT pn_st.pnum,invpnum,category FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY rminvpnum) AS INVRMCAT ON pnum=INVRMCAT.rminvpnum

) AS FINISHQ ) AS PFINALQ ORDER BY operation,finalforeman,FCat";
				
				$result = $conn->query($query);
				while($row = mysqli_fetch_array($result))
				{
					
					echo"<tr><td>".$row['operation']."</td>";
					echo"<td>".$row['rcno']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['finalforeman']."</td>";
					echo"<td>".$row['FCat']."</td>";
					echo"<td>".round($row['OPENKG'],2)."</td>";
					echo"<td>".round($row['opissqty_KG'],2)."</td>";
					echo"<td>".round($row['weekrec_KG'],2)."</td>";
					echo"<td>".round($row['weekrej_KG'],2)."</td>";
					echo"<td>".round($row['ret'],2)."</td>";
					echo"<td>".round($row['closingstk_KG'],2)."</td>";
					
					$sh=$row['OPENKG']+$row['opissqty_KG']-$row['weekrec_KG']-$row['weekrej_KG']-$row['ret'];
					echo"<td>".round($sh,2)."</td>";
					
					$dif=$row['closingstk_KG']-$sh;
					echo"<td>".round($dif,2)."</td>";
					
					
					
					echo"</tr>";
				}
				
				
				
			?>
		</div>
		
</body>
</html>
