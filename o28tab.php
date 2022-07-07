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
				<label>OPEN DATE</label>
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
							<script>
						function reload(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='o28tab.php?f='+s0+'&tt='+s1;
						}
					</script>
			</div>
			
			<div class="find1">
				<label>CLOSE DATE</label>
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
							self.location='o28tab.php?f='+s0+'&tt='+s1;
						}
					</script>
			</div>
			<br>
			</form>
			<br>
			<?php
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-7 days'));
					$tt = date('Y-m-d');
				}
				else
				{
					$f = $_GET['f'];
					$tt = $_GET['tt'];
				}
				$con=mysqli_connect('localhost','root','Tamil','storedb');
				$conn=mysqli_connect('localhost','root','Tamil','mypcm');
				
				// OPENING STOCK
				
				
				$query = "SELECT SUM(accepted)-sum(used) storeopen FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<'$f' GROUP BY inv) AS T ON receipt.grnnum=T.inv  WHERE date<'$f' AND (closed>='$f' OR closed='0000-00-00') GROUP BY grnnum) AS T";
				//echo "SELECT SUM(accepted)-sum(used) storeopen FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<'$f' GROUP BY inv) AS T ON receipt.grnnum=T.inv  WHERE date<'$f' AND (closed>='$f' OR closed='0000-00-00') GROUP BY grnnum) AS T";
				$res_openstores = $con->query($query);
				//STORE STOCK -A
				
				$query = "SELECT operation,stock FROM (SELECT operation,SUM(kg) as stock FROM (SELECT rcno,operation,OPENRC.pnum,issqty,received,rejected,used,notused,bom1,bom2,rate,per,IF(rcno LIKE 'A20%',notused,notused*IF(bom2 IS NULL,bom1/2,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<'$f' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<'$f' AND (d11.closedate>='$f' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS T1 GROUP BY operation) AS OPERWISE LEFT JOIN (SELECT name,fno FROM `m15` WHERE type='OPERATION' ORDER BY fno) AS TAB ON TAB.name=OPERWISE.operation ORDER BY fno";
				echo "SELECT operation,stock FROM (SELECT operation,SUM(kg) as stock FROM (SELECT rcno,operation,OPENRC.pnum,issqty,received,rejected,used,notused,bom1,bom2,rate,per,IF(rcno LIKE 'A20%',notused,notused*IF(bom2 IS NULL,bom1/2,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<'$f' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<'$f' AND (d11.closedate>='$f' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS T1 GROUP BY operation) AS OPERWISE LEFT JOIN (SELECT name,fno FROM `m15` WHERE type='OPERATION' ORDER BY fno) AS TAB ON TAB.name=OPERWISE.operation ORDER BY fno";
				$res_open_operstock = $conn->query($query);
				//OPEN OPER STOCK IN KG AS ON DATE
				
				$query = "SELECT stkpt,livestock,fno FROM (SELECT stkpt,SUM(s*bom) AS livestock FROM (SELECT pnum,stkpt,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno WHERE d12.date<'$f' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt LIKE '%%' ORDER BY days DESC) STOCK LEFT JOIN (SELECT DISTINCT pnum,useage AS bom FROM m13) AS BOM ON STOCK.pnum=BOM.pnum GROUP BY stkpt) AS STOCK LEFT JOIN m15 ON STOCK.stkpt=m15.name ORDER BY fno";
				//echo "SELECT stkpt,livestock,fno FROM (SELECT stkpt,SUM(s*bom) AS livestock FROM (SELECT pnum,stkpt,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno WHERE d12.date<'$f GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt LIKE '%%' ORDER BY days DESC) STOCK LEFT JOIN (SELECT DISTINCT pnum,useage AS bom FROM m13) AS BOM ON STOCK.pnum=BOM.pnum GROUP BY stkpt) AS STOCK LEFT JOIN m15 ON STOCK.stkpt=m15.name ORDER BY fno";
				$res_open_spstock = $conn->query($query);
				//STOCK OFSTKPT ON AS ON DATE
				
				
				
				// RECEIVING STOCK
				
				//$query = "SELECT SUM(accepted) AS received FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' GROUP BY inv) AS T ON receipt.grnnum=T.inv  GROUP BY grnnum) AS T WHERE date>='$f' AND date<='$tt'";
				$query = "SELECT SUM(quantityaccepted) AS received FROM `inspdb` WHERE  inspdate>='$f' AND inspdate<='$tt'";
				//echo "SELECT SUM(accepted) AS received FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' GROUP BY inv) AS T ON receipt.grnnum=T.inv  GROUP BY grnnum) AS T WHERE date>='$f' AND date<='$tt'";
				$res_storesreceive = $con->query($query);
				//STORES RECEIPT BETWEEN THAT PERIOD - C
				
				
				$query = "SELECT m15.name,IF(stock IS NULL,0,stock) AS stock,m15.fno FROM (SELECT name,fno,SUM(kg) AS stock FROM (SELECT date,name,rcno,OPENRC.pnum,qty,bom1,bom2,IF(rcno LIKE 'A20%',qty,qty*IF(bom2 IS NULL,bom1 / 2,bom2)) AS kg,fno FROM (SELECT date,name,rcno,pnum,qty,fno FROM (SELECT ISSUE.date,stkpt,ISSUE.rcno,IF(stkpt='Stores',ISSUE.pnum,d11.pnum) AS pnum,qty FROM (SELECT date,stkpt,rcno,prcno,pnum,rm,rmissqty+partissued AS qty FROM `d12` WHERE date>='$f' AND date<='$tt' AND (partissued!='' OR rmissqty!='') AND stkpt!='FG For Invoicing') AS ISSUE LEFT JOIN d11 ON ISSUE.prcno=d11.rcno) AS DB LEFT JOIN m15 ON DB.stkpt=m15.prev) AS OPENRC LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS BYOPER GROUP BY name ORDER BY fno) AS RECEIPT RIGHT JOIN m15 ON RECEIPT.name=m15.name WHERE m15.type='OPERATION' ORDER BY m15.fno";
				//echo "SELECT m15.name,stock,m15.fno FROM (SELECT name,fno,SUM(kg) AS stock FROM (SELECT date,name,rcno,OPENRC.pnum,qty,bom1,bom2,IF(rcno LIKE 'A20%',qty,qty*IF(bom2 IS NULL,bom1 / 2,bom2)) AS kg,fno FROM (SELECT date,name,rcno,pnum,qty,fno FROM (SELECT ISSUE.date,stkpt,ISSUE.rcno,IF(stkpt='Stores',ISSUE.pnum,d11.pnum) AS pnum,qty FROM (SELECT date,stkpt,rcno,prcno,pnum,rm,rmissqty+partissued AS qty FROM `d12` WHERE date>='$f' AND date<='$tt' AND (partissued!='' OR rmissqty!='') AND stkpt!='FG For Invoicing') AS ISSUE LEFT JOIN d11 ON ISSUE.prcno=d11.rcno) AS DB LEFT JOIN m15 ON DB.stkpt=m15.prev) AS OPENRC LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS BYOPER GROUP BY name ORDER BY fno) AS RECEIPT RIGHT JOIN m15 ON RECEIPT.name=m15.name WHERE m15.type='OPERATION' ORDER BY m15.fno";
				$res_operreceipt = $conn->query($query);
				$res_stkptissue = $conn->query($query);
				//OPERATION RECEIPT BETWEEN THAT PERIOD - C
				
				
				$query = "SELECT m15.name,m15.fno,IF(stock IS NULL,0,stock) AS stock FROM (SELECT stkpt,fno,SUM(stock) AS stock FROM (SELECT date,stkpt,STST.pnum,prcno,partreceived,bom1,bom2,IF(bom2 IS NULL,bom1/2,bom2) AS bom,partreceived*IF(bom2 IS NULL,bom1/2,bom2) AS stock,fno FROM (SELECT date,stkpt,pnum,prcno,partreceived,fno FROM `d12` LEFT JOIN m15 ON d12.stkpt=m15.name WHERE date>='$f' AND date<='$tt' AND partreceived!='') AS STST LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON STST.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON STST.pnum=BOM2.pnum) AS STKRCPT GROUP BY stkpt ORDER BY fno) AS STRECEIPT RIGHT JOIN m15 ON m15.name=STRECEIPT.stkpt WHERE type='STOKINGPOINT' ORDER BY fno";
				//echo "SELECT m15.name,m15.fno,stock FROM (SELECT stkpt,fno,SUM(stock) AS stock FROM (SELECT date,stkpt,STST.pnum,prcno,partreceived,bom1,bom2,IF(bom2 IS NULL,bom1/2,bom2) AS bom,partreceived*IF(bom2 IS NULL,bom1/2,bom2) AS stock,fno FROM (SELECT date,stkpt,pnum,prcno,partreceived,fno FROM `d12` LEFT JOIN m15 ON d12.stkpt=m15.name WHERE date>='$f' AND date<='$tt' AND partreceived!='') AS STST LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON STST.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON STST.pnum=BOM2.pnum) AS STKRCPT GROUP BY stkpt ORDER BY fno) AS STRECEIPT RIGHT JOIN m15 ON m15.name=STRECEIPT.stkpt WHERE type='STOKINGPOINT' ORDER BY fno";
				$res_stkptreceipt = $conn->query($query);
				//STOCKING POINT RECEIPT BETWEEN THAT PERIOD - C
				
				
				
				// ISSANCE / HANDEDOVER 
				
				$query = "SELECT SUM(rmissqty) AS issued FROM `d12` WHERE date>='$f' AND date<='$tt' AND rmissqty!=''";
				//echo "SELECT SUM(rmissqty) AS issued FROM `d12` WHERE date>='$f' AND date<='$tt' AND rmissqty!=''";
				$res_storesissue = $conn->query($query);
				//STORES ISSUANCE BETWEEN THAT PERIOD - D
				
				
				
				
				$query = "SELECT m15.name,m15.fno,IF(stock IS NULL,0,stock) AS stock FROM (SELECT stkpt,fno,SUM(stock) AS stock FROM (SELECT date,stkpt,STST.pnum,prcno,partreceived,bom1,bom2,IF(bom2 IS NULL,bom1/2,bom2) AS bom,partreceived*IF(bom2 IS NULL,bom1/2,bom2) AS stock,fno FROM (SELECT d12.date,m15.name AS stkpt,d11.operation,d12.pnum,prcno,partreceived,fno FROM `d12` LEFT JOIN d11 ON d12.prcno=d11.rcno LEFT JOIN m15 ON d11.operation=m15.prev WHERE d12.date>='$f' AND d12.date<='$tt' AND partreceived!='') AS STST LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON STST.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON STST.pnum=BOM2.pnum) AS STKRCPT WHERE stkpt!='' GROUP BY stkpt ORDER BY fno) AS HANDOVEROPER RIGHT JOIN m15 ON HANDOVEROPER.stkpt=m15.name WHERE m15.type='OPERATION' ORDER BY m15.fno";
				//echo "SELECT m15.name,m15.fno,IF(stock IS NULL,0,stock) AS stock FROM (SELECT stkpt,fno,SUM(stock) AS stock FROM (SELECT date,stkpt,STST.pnum,prcno,partreceived,bom1,bom2,IF(bom2 IS NULL,bom1/2,bom2) AS bom,partreceived*IF(bom2 IS NULL,bom1/2,bom2) AS stock,fno FROM (SELECT d12.date,m15.name AS stkpt,d11.operation,d12.pnum,prcno,partreceived,fno FROM `d12` LEFT JOIN d11 ON d12.prcno=d11.rcno LEFT JOIN m15 ON d11.operation=m15.prev WHERE d12.date>='$f' AND d12.date<='$tt' AND partreceived!='') AS STST LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON STST.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON STST.pnum=BOM2.pnum) AS STKRCPT WHERE stkpt!='' GROUP BY stkpt ORDER BY fno) AS HANDOVEROPER RIGHT JOIN m15 ON HANDOVEROPER.stkpt=m15.name WHERE m15.type='OPERATION' ORDER BY m15.fno";
				$res_oper_handover = $conn->query($query);
				//OPERATION HAND VER TO STKPT
				
				$query = "SELECT invno,invdt,qty*IF(IF(bom1 IS NULL,0,bom1) > IF(bom2 IS NULL,0,bom2),bom1,bom2) AS kg FROM (SELECT * FROM `inv_det`) AS INV LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON INV.pn=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON INV.pn=BOM2.pnum";
				$res_invoice = $conn->query($query);
				$ss=0;
				while($row = $res_invoice->fetch_assoc()){
					$d1 = date("Y-m-d", strtotime($row['invdt']));
					if($d1>=$f && $d1<=$tt)
					{
						$ss=$ss+$row['kg'];
					}
				}
				//INVOICED OR FG FOR INVOICING HANDED OVER STOCK
				
				
				// REJECTION DETAILS
				
				$query = "SELECT m15.name,IF(rj IS NULL,0,rj) AS rj,m15.fno FROM `m15` LEFT JOIN (SELECT name,fno,SUM(rejected) AS rj FROM (SELECT REJ.date,prcno,REJ.pnum,qtyrejected,name,fno,useage,qtyrejected*useage AS rejected FROM (SELECT d12.date,d11.operation,name,prcno,d12.pnum,qtyrejected,fno FROM `d12` LEFT JOIN d11 ON d12.prcno=d11.rcno LEFT JOIN m15 ON d11.operation=m15.prev WHERE d12.date>='$f' AND d12.date<='$tt' AND qtyrejected!='') AS REJ LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS bom ON REJ.pnum=bom.pnum) AS REJECTION GROUP BY name) AS T ON T.name=m15.name WHERE m15.type='OPERATION' ORDER BY m15.fno";
				//echo "SELECT m15.name,IF(rj IS NULL,0,rj) AS rj,m15.fno FROM `m15` LEFT JOIN (SELECT name,fno,SUM(rejected) AS rj FROM (SELECT REJ.date,prcno,REJ.pnum,qtyrejected,name,fno,useage,qtyrejected*useage AS rejected FROM (SELECT d12.date,d11.operation,name,prcno,d12.pnum,qtyrejected,fno FROM `d12` LEFT JOIN d11 ON d12.prcno=d11.rcno LEFT JOIN m15 ON d11.operation=m15.prev WHERE d12.date>='$f' AND d12.date<='$tt' AND qtyrejected!='') AS REJ LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS bom ON REJ.pnum=bom.pnum) AS REJECTION GROUP BY name) AS T ON T.name=m15.name WHERE m15.type='OPERATION' ORDER BY m15.fno";
				$res_rejection = $conn->query($query);
				//REJECTION ON THAT PERIOD - E
				
				
				// ACTUAL STOCK DETAILS
				
				$query = "SELECT SUM(accepted)-sum(used) storelive FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<='$tt' GROUP BY inv) AS T ON receipt.grnnum=T.inv  WHERE date<='$tt' AND (closed>='$tt' OR closed='0000-00-00') GROUP BY grnnum) AS T";
				//echo "SELECT SUM(accepted)-sum(used) storelive FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<='$tt' GROUP BY inv) AS T ON receipt.grnnum=T.inv  WHERE date<='$tt' AND (closed>='$tt' OR closed='0000-00-00') GROUP BY grnnum) AS T";
				$res_livestores = $con->query($query);
				//STORE STOCK - ACTUAL 
				
				$query = "SELECT operation,stock FROM (SELECT operation,SUM(kg) as stock FROM (SELECT rcno,operation,OPENRC.pnum,issqty,received,rejected,used,notused,bom1,bom2,rate,per,IF(rcno LIKE 'A20%',notused,notused*IF(bom2 IS NULL,bom1/2,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$tt' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<='$tt' AND (d11.closedate>'$tt' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS T1 GROUP BY operation) AS OPERWISE LEFT JOIN (SELECT name,fno FROM `m15` WHERE type='OPERATION' ORDER BY fno) AS TAB ON TAB.name=OPERWISE.operation ORDER BY fno";
				//echo "SELECT operation,stock FROM (SELECT operation,SUM(kg) as stock FROM (SELECT rcno,operation,OPENRC.pnum,issqty,received,rejected,used,notused,bom1,bom2,rate,per,IF(rcno LIKE 'A20%',notused,notused*IF(bom2 IS NULL,bom1/2,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$tt' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<='$tt' AND (d11.closedate>'$tt' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT  invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum) AS T1 GROUP BY operation) AS OPERWISE LEFT JOIN (SELECT name,fno FROM `m15` WHERE type='OPERATION' ORDER BY fno) AS TAB ON TAB.name=OPERWISE.operation ORDER BY fno";
				$res_close_operstock = $conn->query($query);
				//CLOSE OPER STOCK IN KG AS ON DATE
				
				$query = "SELECT stkpt,livestock,fno FROM (SELECT stkpt,SUM(s*bom) AS livestock FROM (SELECT pnum,stkpt,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno WHERE d12.date<='$tt' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt LIKE '%%' ORDER BY days DESC) STOCK LEFT JOIN (SELECT DISTINCT pnum,useage AS bom FROM m13) AS BOM ON STOCK.pnum=BOM.pnum GROUP BY stkpt) AS STOCK LEFT JOIN m15 ON STOCK.stkpt=m15.name ORDER BY fno";
				//echo "SELECT stkpt,livestock,fno FROM (SELECT stkpt,SUM(s*bom) AS livestock FROM (SELECT pnum,stkpt,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno WHERE d12.date<='$tt' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt LIKE '%%' ORDER BY days DESC) STOCK LEFT JOIN (SELECT DISTINCT pnum,useage AS bom FROM m13) AS BOM ON STOCK.pnum=BOM.pnum GROUP BY stkpt) AS STOCK LEFT JOIN m15 ON STOCK.stkpt=m15.name ORDER BY fno";
				$res_close_spstock = $conn->query($query);
				// CLOSE STOCK OFSTKPT ON AS ON DATE
				
				$c= array_fill(1, 13 , 0);
				echo'<table id="testTable" align="center">
					  <tr>
						<th> STOCK SUMMARY </th><th> STORES (KG) </th>
						<th> CNC/SHEARING (KG) </th><th> MANAUL 1 (KG) </th><th> MANAUL 2 (KG) </th><th> S/C (KG) </th><th> MANAUL (KG) </th><th> ALFA (KG) </th>
						<th> SF 1 (KG) </th><th> SF 2 (KG) </th><th> To S/C (KG) </th><th> From S/C (KG) </th><th> FG For S/C (KG) </th><th> FG For Invoicing (KG) </th>
					  </tr>';
						echo '<tr><th>OPENING STOCK</th>';
							while($row = mysqli_fetch_array($res_openstores))
							{
								echo '<td>'.round($row['storeopen']).'</td>';
								$c[1]=$c[1]+round($row['storeopen']);
							}
							$t=2;
							while($row = mysqli_fetch_array($res_open_operstock))
							{
								echo '<td>'.round($row['stock']).'</td>';
								$c[$t] = $c[$t] + round($row['stock']);
								$t++;
							}
							while($row = mysqli_fetch_array($res_open_spstock))
							{
								echo '<td>'.round($row['livestock']).'</td>';
								$c[$t] = $c[$t] + round($row['livestock']);
								$t++;
							}
						echo '</tr>';
						
						
						$t=1;
						echo '<tr><th> RECEIVING STOCK</th>';
						
							while($row = mysqli_fetch_array($res_storesreceive))
							{
								echo '<td>'.round($row['received']).'</td>';
								$c[$t] = $c[$t] + round($row['received']);
								$t++;
							}
							while($row = mysqli_fetch_array($res_operreceipt))
							{
								echo '<td>'.round($row['stock']).'</td>';
								$c[$t] = $c[$t] + round($row['stock']);
								$t++;
							}
							while($row = mysqli_fetch_array($res_stkptreceipt))
							{
								echo '<td>'.round($row['stock']).'</td>';
								$c[$t] = $c[$t] + round($row['stock']);
								$t++;
							}
						echo '</tr>';
						
						$t=1;
						echo '<tr><th> ISSUE / HANDEDOVER STOCK</th>';
							while($row = mysqli_fetch_array($res_storesissue))
							{
								echo '<td>'.round($row['issued']).'</td>';
								$c[$t] = $c[$t] - round($row['issued']);
								$t++;
							}
							while($row = mysqli_fetch_array($res_oper_handover))
							{
								echo '<td>'.round($row['stock']).'</td>';
								$c[$t] = $c[$t] - round($row['stock']);
								$t++;
							}
							while($row = mysqli_fetch_array($res_stkptissue))
							{
								if($row['fno']!='3')
								{
									echo '<td>'.round($row['stock']).'</td>';
									$c[$t] = $c[$t] - round($row['stock']);
									$t++;
								}
							}
							echo '<td>'.round($ss).'</td>';
							$c[$t] = $c[$t] - round($ss);
						echo '</tr>';
						
						echo '<tr><th> REJECTED STOCK </th><td>0</td>';
							$t=2;
							while($row = mysqli_fetch_array($res_rejection))
							{
								echo '<td>'.round($row['rj']).'</td>';
								$c[$t] = $c[$t] - round($row['rj']);
								$t++;
							}
							echo '<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>';
						echo '</tr>';
						
						
				echo '<tr>
						<th>SHOULD BE CLOSING STOCK</th>
						<td>'.$c[1].'</td>
						<td>'.$c[2].'</td>
						<td>'.$c[3].'</td>
						<td>'.$c[4].'</td>
						<td>'.$c[5].'</td>
						<td>'.$c[6].'</td>
						<td>'.$c[7].'</td>
						<td>'.$c[8].'</td>
						<td>'.$c[9].'</td>
						<td>'.$c[10].'</td>
						<td>'.$c[11].'</td>
						<td>'.$c[12].'</td>
						<td>'.$c[13].'</td>
					 </tr>';
					 
					 
						echo '<tr><th> ACTUAL STOCK</th>';
							$t=1;
							while($row = mysqli_fetch_array($res_livestores))
							{
								echo '<td>'.round($row['storelive']).'</td>';
								$c[$t] = round($row['storelive']) - $c[$t];
								$t++;
							}
							while($row = mysqli_fetch_array($res_close_operstock))
							{
								echo '<td>'.round($row['stock']).'</td>';
								$c[$t] = round($row['stock']) - $c[$t];
								$t++;
							}
							while($row = mysqli_fetch_array($res_close_spstock))
							{
								echo '<td>'.round($row['livestock']).'</td>';
								$c[$t] = round($row['livestock']) - $c[$t];
								$t++;
							}
						echo '</tr>';
				
				echo '<tr>
						<th>VARIATION</th>
						<td>'.$c[1].'</td>
						<td>'.$c[2].'</td>
						<td>'.$c[3].'</td>
						<td>'.$c[4].'</td>
						<td>'.$c[5].'</td>
						<td>'.$c[6].'</td>
						<td>'.$c[7].'</td>
						<td>'.$c[8].'</td>
						<td>'.$c[9].'</td>
						<td>'.$c[10].'</td>
						<td>'.$c[11].'</td>
						<td>'.$c[12].'</td>
						<td>'.$c[13].'</td>
					 </tr>';
			?>
		</div>
</body>
</html>
</html>