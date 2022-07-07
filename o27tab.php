<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		
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
		<h4 style="text-align:center"><label>STOCK SUMMARY REPORT</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'SUMMARY')" value="Export to Excel">
		</div>
		<br/></br>
		
		<div class="divclass">
		<form method="GET">	
			</br>
			<div class="find">
				<label>FROM DATE</label>
							<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['f']))
							{
								echo $_GET['f'];
							}
							else
							{
								echo date('Y-m-d',strtotime('-1 days'));
							}
							?>"/>
							<script>
						function reload(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='o27tab.php?f='+s0+'&tt='+s1;
						}
					</script>
			</div>
			
			<div class="find1">
				<label>TILL DATE</label>
							<input type="date" id="tt" name="tt"  onchange="reload0(this.form)" value="<?php
							if(isset($_GET['tt']))
							{
								echo $_GET['tt'];
							}
							else
							{
								echo date('Y-m-d');
							}
							?>"/>
							<script>
						function reload0(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='o27tab.php?f='+s0+'&tt='+s1;
						}
					</script>
			</div>
			<br>
			</form>
			<br>
			<?php
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-1 days'));
					$tt = date('Y-m-d');
				}
				else
				{
					$f = $_GET['f'];
					$tt = $_GET['tt'];
				}
				$con=mysqli_connect('localhost','root','Tamil','storedb');
				$conn=mysqli_connect('localhost','root','Tamil','mypcm');
				
				$query = "SELECT SUM(accepted)-sum(used) storeopen FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<'$f' GROUP BY inv) AS T ON receipt.grnnum=T.inv  WHERE date<'$f' AND (closed>='$f' OR closed='0000-00-00') GROUP BY grnnum) AS T";
				///echo "SELECT SUM(accepted)-sum(used) storeopen FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<'$f' GROUP BY inv) AS T ON receipt.grnnum=T.inv  WHERE date<'$f' AND (closed>='$f' OR closed='0000-00-00') GROUP BY grnnum) AS T";
				$res_openstores = $con->query($query);
				$openstores = $res_openstores->fetch_assoc();
				//STORE STOCK -A
				
				$query = "SELECT SUM(kg) AS openoper FROM (SELECT IF(rcno LIKE 'A20%',notused,notused*IF(IF(bom1 IS NULL,0,bom1) > IF(bom2 IS NULL,0,bom2),bom1,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<'$f' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<'$f' AND (d11.closedate>='$f' OR d11.closedate='0000-00-00')  AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS A";
				echo "SELECT SUM(kg) AS openoper FROM (SELECT IF(rcno LIKE 'A20%',notused,notused*IF(IF(bom1 IS NULL,0,bom1) > IF(bom2 IS NULL,0,bom2),bom1,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<'$f' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<'$f' AND (d11.closedate>='$f' OR d11.closedate='0000-00-00')  AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS A";
				$res_open_operstock = $conn->query($query);
				$open_operstock = $res_open_operstock->fetch_assoc();
				//OPEN RC IN KG PREV DATE - B
				
				$query = "SELECT  SUM(s*bom) AS openstock FROM (SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno WHERE d12.date<'$f' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt LIKE '%%' ORDER BY days DESC) STOCK  LEFT JOIN (SELECT DISTINCT pnum,useage AS bom FROM m13) AS BOM ON STOCK.pnum=BOM.pnum";
				//echo "SELECT  SUM(s*bom) AS openstock FROM (SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno WHERE d12.date<'$f' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt LIKE '%%' ORDER BY days DESC) STOCK  LEFT JOIN (SELECT DISTINCT pnum,useage AS bom FROM m13) AS BOM ON STOCK.pnum=BOM.pnum";
				$res_open_spstock = $conn->query($query);
				$open_spstock = $res_open_spstock->fetch_assoc();
				//STOCK ON OPEN DATE - B1
				
				
				$query = "SELECT SUM(accepted) AS received FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' GROUP BY inv) AS T ON receipt.grnnum=T.inv  GROUP BY grnnum) AS T WHERE date>='$f' AND date<='$tt'";
				//echo "SELECT SUM(accepted) AS received FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' GROUP BY inv) AS T ON receipt.grnnum=T.inv  GROUP BY grnnum) AS T WHERE date>='$f' AND date<='$tt'";
				$res_storesreceive = $con->query($query);
				$storesreceive = $res_storesreceive->fetch_assoc();
				//STORES RECEIPT BETWEEN THAT PERIOD - C
				
				$query = "SELECT SUM(rmissqty) AS issued FROM `d12` WHERE date>='$f' AND date<='$tt' AND rmissqty!=''";
				//echo "SELECT SUM(rmissqty) AS issued FROM `d12` WHERE date>='$f' AND date<='$tt' AND rmissqty!=''";
				$res_storesissue = $conn->query($query);
				$storesissue = $res_storesissue->fetch_assoc();
				//STORES ISSUANCE BETWEEN THAT PERIOD - D
				
				$query = "SELECT invno,invdt,qty*IF(IF(bom1 IS NULL,0,bom1) > IF(bom2 IS NULL,0,bom2),bom1,bom2) AS kg FROM (SELECT * FROM `inv_det`) AS INV LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum) AS BOM1 ON INV.pn=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON INV.pn=BOM2.pnum";
				$res_invoice = $conn->query($query);
				$ss=0;
				while($row = $res_invoice->fetch_assoc()){
					$d1 = date("Y-m-d", strtotime($row['invdt']));
					if($d1>=$f && $d1<=$tt)
					{
						$ss=$ss+$row['kg'];
					}
				}
				//INVOICE
				
				$query = "SELECT SUM(qtyrejected*useage) AS kg FROM (SELECT prcno,pnum,qtyrejected FROM `d12` WHERE date>='$f' AND date<='$tt' AND qtyrejected!='') AS REJ LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS bom ON REJ.pnum=bom.pnum";
				//echo "SELECT SUM(qtyrejected*useage) AS kg FROM (SELECT prcno,pnum,qtyrejected FROM `d12` WHERE date>='$f' AND date<='$tt' AND qtyrejected!='') AS REJ LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS bom ON REJ.pnum=bom.pnum";
				$res_rejection = $conn->query($query);
				$rejection = $res_rejection->fetch_assoc();
				//REJECTION ON THAT PERIOD - E
				
				$query = "SELECT SUM(accepted)-sum(used) storelive FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<='$tt' GROUP BY inv) AS T ON receipt.grnnum=T.inv  WHERE date<='$tt' AND (closed>='$tt' OR closed='0000-00-00') GROUP BY grnnum) AS T";
				//echo "SELECT SUM(accepted)-sum(used) storelive FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<='$tt' GROUP BY inv) AS T ON receipt.grnnum=T.inv  WHERE date<='$tt' AND (closed>='$tt' OR closed='0000-00-00') GROUP BY grnnum) AS T";
				$res_livestores = $con->query($query);
				$livestores = $res_livestores->fetch_assoc();
				//STORE STOCK - Z
				
				$query = "SELECT SUM(kg) AS liveoper FROM (SELECT IF(rcno LIKE 'A20%',notused,notused*IF(IF(bom1 IS NULL,0,bom1) > IF(bom2 IS NULL,0,bom2),bom1,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$tt' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<='$tt' AND (d11.closedate>'$tt' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS A";
				echo "SELECT SUM(kg) AS liveoper FROM (SELECT IF(rcno LIKE 'A20%',notused,notused*IF(IF(bom1 IS NULL,0,bom1) > IF(bom2 IS NULL,0,bom2),bom1,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$tt' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<='$tt' AND (d11.closedate>'$tt' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS A";
				$res_live_operstock = $conn->query($query);
				$live_operstock = $res_live_operstock->fetch_assoc();
				//OPEN RC IN KG LIVE
				
				$query = "SELECT  SUM(s*bom) AS livestock FROM (SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno WHERE d12.date<='$f' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt LIKE '%%' ORDER BY days DESC) STOCK  LEFT JOIN (SELECT DISTINCT pnum,useage AS bom FROM m13) AS BOM ON STOCK.pnum=BOM.pnum";
				//echo "SELECT  SUM(s*bom) AS livestock FROM (SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno WHERE d12.date<='$f' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt LIKE '%%' ORDER BY days DESC) STOCK  LEFT JOIN (SELECT DISTINCT pnum,useage AS bom FROM m13) AS BOM ON STOCK.pnum=BOM.pnum";
				$res_live_spstock = $conn->query($query);
				$live_spstock = $res_live_spstock->fetch_assoc();
				//STOCK IN KG LIVE
				echo round($open_operstock['openoper'])."<br>";
				echo round($open_spstock['openstock'])."<br>";
				echo round($live_operstock['liveoper'])."<br>";
				echo round($live_spstock['livestock'])."<br>";
				
				echo'<table id="testTable" align="center">
					  <tr>
						<th> STORE SUMMARY </th><th> QUANITY (KG) </th>
					  </tr>';
				echo "<tr><td> OPENING STOCk (KG) - $f </td><td>".round($openstores['storeopen'])."</td></tr>
						<td> RECEIPT (KG) ($f To $tt) </td><td>".round($storesreceive['received'])."</td></tr>
						<td> ISSUANCE (KG) ($f To $tt) </td><td>".round($storesissue['issued'])."</td></tr>
						<td> REJECTION (KG) ($f To $tt) </td><td>0</td></tr>
						<td> SHOULD BE CLOSING STOCK (KG) - $tt </td><td>".((round($openstores['storeopen'])+round($storesreceive['received']))-round($storesissue['issued']))."</td></tr>
						<td> ERP BE CLOSING STOCK (KG) - $tt </td><td>".round($livestores['storelive'])."</td></tr>
						<th> VARIANCE </td><td>".(((round($openstores['storeopen'])+round($storesreceive['received']))-round($storesissue['issued']))-round($livestores['storelive']))."</td></tr>";
				echo '</table>';
				
				echo'<br><br><table id="testTable" align="center">
					  <tr>
						<th> PRODUCTION SUMMARY </th><th> QUANITY (KG) </th>
					  </tr>';
				echo "<tr><td> PROD OPENING STOCk (KG) - $f </td><td>".round($open_operstock['openoper']+$open_spstock['openstock'])."</td></tr>
						<td> RECEIPT (CNC) (KG) ($f To $tt) </td><td>".round($storesissue['issued'])."</td></tr>
						<td> INVOICED (KG) ($f To $tt) </td><td>".round($ss)."</td></tr>
						<td> REJECTION (KG) ($f To $tt) </td><td>".round($rejection['kg'])."</td></tr>
						<td> SHOULD BE CLOSING STOCK (KG) - $tt </td><td>".((round($open_operstock['openoper']+$open_spstock['openstock'])+round($storesissue['issued']))-(round($ss)+round($rejection['kg'])))."</td></tr>
						<td> ERP BE CLOSING STOCK (KG) - $tt </td><td>".round($live_operstock['liveoper']+$live_spstock['livestock'])."</td></tr>
						<th> VARIANCE </td><td>".(((round($open_operstock['openoper']+$open_spstock['openstock'])+round($storesissue['issued']))-(round($ss)+round($rejection['kg'])))-round($live_operstock['liveoper']+$live_spstock['livestock']))."</td></tr>";
				echo '</table>';
				
			?>
		</div>
</body>
</html>
</html>