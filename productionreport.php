<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="PRODUCTIONREPORT";
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
		<h4 style="text-align:center"><label>WEEKLY PRODUCTION REPORT CNC & MANUAL</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'PRODUCTION REPORT')" value="Export to Excel">
	</div>
	<br><br>
	
	<script>
			function reload(form)
			{
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				self.location='productionreport.php?f='+s0+'&tt='+s1;
			}
	</script>
	
	<div class="divclass">
		<form method="GET"></br>
		<div class="find3">
			<label>SELECT</label>
			<select name ="prcno" id="prcno" onchange="reload1(this.form)">
			<option value="%%">ALL</option>";	
			<option value="CNC">CNC</option>";	
			<option value="MANUAL">MANUAL</option>";	
				<?php			
					$rat=$_GET['rat'];
					//$con = mysqli_connect('localhost','root','Tamil','mypcm');
					//if(!$con)
					//	echo "connection failed";
					//$query = "SELECT * from m14 where oper!=''";
					//$result = $con->query($query);  
					//while ($row = mysqli_fetch_array($result)) 
					//{
					if(isset($_GET['rat']) && $_GET['rat']=='CNC')
						echo "<option selected value='" . "CNC" . "'>" . "CNC" ."</option>";
					else if(isset($_GET['rat']) && $_GET['rat']=='MANUAL')
						echo "<option selected value='" . "MANUAL" . "'>" . "MANUAL" ."</option>";
					//else
					//	echo "<option selected value='" . "MANUAL" . "'>" . "MANUAL" ."</option>";
					//}
					echo "</select></h1>";
				?>
				<script>
				function reload1(form)
				{
					var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
					self.location='productionreport.php?&rat='+s2;
				}
			</script>
		</div>
		</form>
		
	
	<!--<div class="find">
				<label>OPENING DATE</label>
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
							}
							?>"/>
			</div>-->

			<br><br>
		
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
				
				$rectotal=0;
				$rejtotal=0;
				
				//$tdate=date('Y-m-d');
				//$ydate = date('Y-m-d', strtotime('-1 days'));
			
				
				
			echo'<table id="testTable" align="center">
				<tr>
					<th>DATE</th>
					<th>OPERATION</th>
					<th>PART NUMBER</th>
					<th>HANDOVER QTY</th>
					<th>REJECTED QTY</th>
					<th>FOREMAN</th>
					<th></th>
				</tr>';
				
				
				
				//good working query//$query = "SELECT date,closedate,operation,rcno,pnum,IF(m13pnum IS NULL,pnum,m13pnum) AS m13pnum,SUM(issqty) AS iss,SUM(received) AS rec,SUM(rejected) AS rej,SUM(used) AS usedd,SUM(notused) AS notusedd FROM(SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-06' AND d11.date<='2020-03-12' GROUP BY rcno,operation ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno) AS OPENRC LEFT JOIN(SELECT DISTINCT pnum AS m13pnum FROM m13) AS Tm13 ON OPENRC.pnum=Tm13.m13pnum GROUP BY m13pnum,operation ORDER BY operation,date";
				
				
				if(isset($_GET["rat"]) && $_GET['rat']=='CNC')
				{
					//$query = "SELECT date,closedate,operation,rcno,pnum,IF(m13pnum IS NULL,pnum,m13pnum) AS m13pnum,SUM(issqty) AS iss,SUM(received) AS rec,SUM(rejected) AS rej,SUM(used) AS usedd,SUM(notused) AS notusedd FROM(SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-07' AND d11.date<='2020-03-13' GROUP BY rcno,operation ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno) AS OPENRC LEFT JOIN(SELECT DISTINCT pnum AS m13pnum FROM m13) AS Tm13 ON OPENRC.pnum=Tm13.m13pnum WHERE operation='100% Checking' OR operation='CNC_SHEARING' GROUP BY m13pnum,operation ORDER BY operation,date";
					//$query = "SELECT date,closedate,operation,rcno,pnum,IF(m13pnum IS NULL,pnum,m13pnum) AS m13pnum,SUM(issqty) AS iss,SUM(received) AS rec,SUM(rejected) AS rej,SUM(used) AS usedd,SUM(notused) AS notusedd FROM(SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE date>='2020-03-07' AND date<='2020-03-13' AND prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-07' AND d11.date<='2020-03-13' GROUP BY rcno,operation ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno) AS OPENRC LEFT JOIN(SELECT DISTINCT pnum AS m13pnum FROM m13) AS Tm13 ON OPENRC.pnum=Tm13.m13pnum WHERE operation='100% Checking' OR operation='CNC_SHEARING' GROUP BY m13pnum,operation ORDER BY operation,date";
					$query = "SELECT date,closedate,operation,rcno,pnum,IF(m13pnum IS NULL,pnum,m13pnum) AS m13pnum,SUM(issqty) AS iss,SUM(received) AS rec,SUM(rejected) AS rej,SUM(used) AS usedd,SUM(notused) AS notusedd FROM(SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE date>='2020-03-14' AND date<='2020-03-20' AND prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-14' AND d11.date<='2020-03-20' GROUP BY rcno,operation ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno) AS OPENRC LEFT JOIN(SELECT DISTINCT pnum AS m13pnum FROM m13) AS Tm13 ON OPENRC.pnum=Tm13.m13pnum WHERE operation='100% Checking' OR operation='CNC_SHEARING' GROUP BY m13pnum,operation ORDER BY operation,date";
					$result = $con->query($query);
				}
				else if(isset($_GET["rat"]) && $_GET['rat']=='MANUAL')
				{
					//$query = "SELECT date,closedate,operation,rcno,pnum,IF(m13pnum IS NULL,pnum,m13pnum) AS m13pnum,SUM(issqty) AS iss,SUM(received) AS rec,SUM(rejected) AS rej,SUM(used) AS usedd,SUM(notused) AS notusedd FROM(SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-06' AND d11.date<='2020-03-12' GROUP BY rcno,operation ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno) AS OPENRC LEFT JOIN(SELECT DISTINCT pnum AS m13pnum FROM m13) AS Tm13 ON OPENRC.pnum=Tm13.m13pnum WHERE operation='MANUAL_AREA' OR operation='MANUAL_AREA-1' OR operation='MANUAL_AREA-2' GROUP BY m13pnum,operation ORDER BY operation,date";
					//$query = "SELECT date,closedate,operation,rcno,pnum,IF(m13pnum IS NULL,pnum,m13pnum) AS m13pnum,SUM(issqty) AS iss,SUM(received) AS rec,SUM(rejected) AS rej,SUM(used) AS usedd,SUM(notused) AS notusedd FROM(SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE date>='2020-03-06' AND date<='2020-03-12' AND prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-06' AND d11.date<='2020-03-12' GROUP BY rcno,operation ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno) AS OPENRC LEFT JOIN(SELECT DISTINCT pnum AS m13pnum FROM m13) AS Tm13 ON OPENRC.pnum=Tm13.m13pnum WHERE operation='MANUAL_AREA' OR operation='MANUAL_AREA-1' OR operation='MANUAL_AREA-2' GROUP BY m13pnum,operation ORDER BY operation,date";
					$query = "SELECT date,closedate,operation,rcno,pnum,IF(m13pnum IS NULL,pnum,m13pnum) AS m13pnum,SUM(issqty) AS iss,SUM(received) AS rec,SUM(rejected) AS rej,SUM(used) AS usedd,SUM(notused) AS notusedd FROM(SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE date>='2020-03-13' AND date<='2020-03-19' AND prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-13' AND d11.date<='2020-03-19' GROUP BY rcno,operation ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno) AS OPENRC LEFT JOIN(SELECT DISTINCT pnum AS m13pnum FROM m13) AS Tm13 ON OPENRC.pnum=Tm13.m13pnum WHERE operation='MANUAL_AREA' OR operation='MANUAL_AREA-1' OR operation='MANUAL_AREA-2' GROUP BY m13pnum,operation ORDER BY operation,date";
					$result = $con->query($query);
				}
				else
				{
					//1//weekly production query beetween date
					//1//$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE date>='2020-03-07' AND date<='2020-03-13' AND prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-07' AND d11.date<='2020-03-13' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno";
					
					//$query = "SELECT date,closedate,operation,rcno,pnum,IF(m13pnum IS NULL,pnum,m13pnum) AS m13pnum,SUM(issqty) AS iss,SUM(received) AS rec,SUM(rejected) AS rej,SUM(used) AS usedd,SUM(notused) AS notusedd FROM(SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-06' AND d11.date<='2020-03-12' GROUP BY rcno,operation ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno) AS OPENRC LEFT JOIN(SELECT DISTINCT pnum AS m13pnum FROM m13) AS Tm13 ON OPENRC.pnum=Tm13.m13pnum WHERE operation!='SUBCONTRACT' AND operation!='ALFA N-IND PRIM' GROUP BY m13pnum,operation ORDER BY operation,date";
					//$result = $con->query($query);
				}
				
				//$query = "SELECT date,closedate,operation,rcno,pnum,IF(m13pnum IS NULL,pnum,m13pnum) AS m13pnum,SUM(issqty) AS iss,SUM(received) AS rec,SUM(rejected) AS rej,SUM(used) AS usedd,SUM(notused) AS notusedd FROM(SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='2020-03-06' AND d11.date<='2020-03-12' GROUP BY rcno,operation ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum order by t2.date,t2.rcno) AS OPENRC LEFT JOIN(SELECT DISTINCT pnum AS m13pnum FROM m13) AS Tm13 ON OPENRC.pnum=Tm13.m13pnum GROUP BY m13pnum,operation ORDER BY operation,date";
				//$result = $con->query($query);
				
				while($row = mysqli_fetch_array($result))
				{
					//if($row['operation']=="SUBCONTRACT" || $row['operation']=="ALFA N-IND PRIM")
					//{
						
					//}
					//else{
						echo"<tr>";
						echo"<td>".$row['date']."</td>";
						echo"<td>".$row['operation']."</td>";
						echo"<td>".$row['pnum']."</td>";
						echo"<td>".round($row['rec'],2)."</td>";
						echo"<td>".round($row['rej'],2)."</td>";
						
						$pnum=$row['pnum'];
						$query1 = "SELECT DISTINCT pnum,foreman FROM m13 WHERE pnum LIKE '%$pnum%'";
						$result1 = $conn->query($query1);
						$row1 = mysqli_fetch_array($result1);
						
						if($row['operation']=="CNC_SHEARING")
						{
							echo"<td>CNC</td>";
						}
						else
						{
							echo"<td>".$row1['foreman']."</td>";
						}
						
						echo"</tr>";
						
						$rectotal=$rectotal+$row['rec'];
						$rejtotal=$rejtotal+$row['rej'];
						
						
					//}
				}
						
					echo" <tr>
						<td colspan='2'></td>
						<td><h4 style='color : orange;'> TOTAL QTY </h4></td>
						<td>$rectotal</td>
						<td>$rejtotal</td>
						<td></td>";
					echo"</tr>";
				
						
				
			?>
		</div>
		
</body>
</html>

