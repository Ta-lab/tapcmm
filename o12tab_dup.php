<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="OPEN ROUTE CARD STATUS";
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
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<script src = "js\excelreport.js"></script>
<link rel="stylesheet" type="text/css" href="design1.css">
</head>
<body>
<header class="fixeElement">
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
	<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label>OPEN ROUTE CARD STATUS REPORT [ O12 ]</label></h4>
	<div style="float:left">
		<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'OPEN RCNO')" value="Export to Excel">
	</div><br/></br>
	<div class="divclass">
		<form method="GET"></br>
			<div class="find3">
			<label>OPERATION</label>
			<select name ="prcno" id="prcno" onchange="reload1(this.form)">
			<option value="%%">ALL</option>";	
				<?php			
					$rat=$_GET['rat'];
					$con = mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						echo "connection failed";
					$query = "SELECT * from m14 where oper!=''";
					$result = $con->query($query);  
					while ($row = mysqli_fetch_array($result)) 
					{
						if(isset($_GET['rat']) && $_GET['rat']==$row['oper'])
							echo "<option selected value='" . $row['oper'] . "'>" . $row['oper'] ."</option>";
						else
							echo "<option value='" . $row['oper'] . "'>" . $row['oper'] ."</option>";              
					}
					echo "</select></h1>";
				?>
				<script>
				function reload1(form)
				{
					var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
					self.location='o12tab.php?&rat='+s2;
				}
			</script></div></form><br>
			</div><header>
	<?php
		$c=0;$r=1;
		$temp="";
		$conn = new mysqli("localhost", "root", "Tamil", "mypcm");
		echo'<table id="testTable" align="center"><tr><th> RCNO / DCNO / INV NO </th><th> OPERATION </th><th>ISSUE DATE</th><th>RAW MATERIAL</th>
			<th>ISSUED QUANTITY</th><th>UOM</th><th>PART NUMBER</th><th>OK QUANTITY</th><th>REJECTED QTY</th><th> USED QTY </th><th> QTY IN PROCESS </th>
			<th>UOM</th><th>NO.DAYS</th><th>FOREMAN</th></tr>';
		if(isset($_GET["rat"]))
		{
			$rat= $_GET['rat'];
			//$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE '$rat' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum  LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum GROUP BY T2.rcno order by t2.date,t2.rcno";
			$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE '$rat' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno";
			$result = $conn->query($query);
		}
		else
		{
			//$query = "SELECT DISTINCT d11.rcno,d11.date,d11.operation,IF(d11.operation LIKE 'FG%','Saravanan-Sales',IF(d11.operation LIKE 'Stores',op.operation,IF(d11.operation LIKE 'To%','Gurumoorthy',IF(bom.foreman IS NULL,foremanlist.foreman,bom.foreman)))) AS fforeman,d11.pnum,firstpart.rm,IF(d11.rcno LIKE 'A20%',firstpart.rmissqty,firstpart.partissued) AS issued,secondpart.rej,secondpart.rec,bom.useage,(secondpart.rej+secondpart.rec)*IF(d11.rcno LIKE 'A20%',bom.useage,1) AS used,bom.foreman,foremanlist.foreman,op.operation AS aoperation,datediff(NOW(),d11.date) as days,IF(d11.rcno LIKE 'A%','Kgs','Nos') AS uom FROM `d11` LEFT JOIN (SELECT rcno,rm,rmissqty,SUM(partissued) as partissued FROM `d12` WHERE rcno IN (SELECT rcno FROM d11 WHERE d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing') GROUP BY rcno) AS firstpart ON d11.rcno=firstpart.rcno LEFT JOIN (SELECT d12.prcno,SUM(qtyrejected) as rej,SUM(partreceived) as rec FROM `d12` WHERE prcno IN (SELECT rcno FROM d11 WHERE d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing') GROUP BY prcno) AS secondpart ON d11.rcno=secondpart.prcno LEFT JOIN (SELECT pnum,useage,foreman FROM `m13` GROUP BY pnum) AS bom ON d11.pnum=bom.pnum LEFT JOIN (SELECT pnum,foreman FROM (SELECT DISTINCT pnum,foreman FROM `m13` UNION (SELECT DISTINCT invpnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum)) AS foreman WHERE foreman!='') AS foremanlist ON d11.pnum=foremanlist.pnum LEFT JOIN (SELECT * FROM `m12` WHERE operation='Straitening/Shearing' OR operation='CNC Machine') AS op ON d11.pnum=op.pnum WHERE closedate='0000-00-00' AND d11.operation!='FG For Invoicing'";
			$query = "SELECT DISTINCT d11.rcno,d11.date,d11.operation,IF(d11.operation LIKE 'FG%','Saravanan-Sales',IF(d11.operation LIKE 'Stores',op.operation,IF(d11.operation LIKE 'To%','Gurumoorthy',IF(bom.foreman IS NULL,foremanlist.foreman,bom.foreman)))) AS fforeman,d11.pnum,firstpart.rm,IF(d11.rcno LIKE 'A20%',firstpart.rmissqty,firstpart.partissued) AS issued,secondpart.rej,secondpart.rec,bom.useage,(secondpart.rej+secondpart.rec)*IF(d11.rcno LIKE 'A20%',bom.useage,1) AS used,bom.foreman,foremanlist.foreman,op.operation AS aoperation,datediff(NOW(),d11.date) as days,IF(d11.rcno LIKE 'A%','Kgs','Nos') AS uom FROM `d11` LEFT JOIN (SELECT rcno,rm,rmissqty,SUM(partissued) as partissued FROM `d12` WHERE rcno IN (SELECT rcno FROM d11 WHERE d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing') GROUP BY rcno) AS firstpart ON d11.rcno=firstpart.rcno LEFT JOIN (SELECT d12.prcno,SUM(qtyrejected) as rej,SUM(partreceived) as rec FROM `d12` WHERE prcno IN (SELECT rcno FROM d11 WHERE d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing') GROUP BY prcno) AS secondpart ON d11.rcno=secondpart.prcno LEFT JOIN (SELECT pnum,useage,foreman FROM `m13` GROUP BY pnum) AS bom ON d11.pnum=bom.pnum LEFT JOIN (SELECT pnum,foreman FROM (SELECT DISTINCT pnum,foreman FROM `m13` UNION (SELECT DISTINCT invpnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum)) AS foreman WHERE foreman!='') AS foremanlist ON d11.pnum=foremanlist.pnum LEFT JOIN (SELECT pnum,operation FROM `m12` WHERE operation='Straitening/Shearing' OR operation='CNC Machine' UNION (SELECT DISTINCT invpnum,operation FROM `pn_st` LEFT JOIN m12 ON pn_st.pnum=m12.pnum WHERE m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine')) AS op ON d11.pnum=op.pnum WHERE closedate='0000-00-00' AND d11.operation!='FG For Invoicing' ORDER BY fforeman";
			$result = $conn->query($query);
		}
		$row = $result->fetch_assoc();
		do{
			$d = date("d-m-Y", strtotime($row['date']));
			echo"<tr>
			<td>".$row['rcno']."</td><td>".$row['operation']."</td><td>".$d."</td><td>".$row['rm']."</td><td>".round($row['issued'],2)."</td><td>".$row['uom']."</td>
			<td>".$row['pnum']."</td><td>".$row['rec']."</td><td>".$row['rej']."</td><td>".round($row['used'],2)."</td><td>".round((round($row['issued'],2)-round($row['used'],2)),2)."</td><td>".$row['uom']."</td><td>".$row['days']."</td><td>".$row['fforeman']."</td></tr>";
		}while($row = $result->fetch_assoc());
	?>
</div>
</body>
</html>