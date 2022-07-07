<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="OPEN & CLOSE ROUTE CARD STATUS";
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
	<h4 style="text-align:center"><label>OPEN & CLOSE ROUTE CARD REPORT </label></h4>
	<div style="float:left">
		<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'OPEN CLOSE RC')" value="Export to Excel">
	</div><br/></br>
	<br>
	<div class="divclass">
		
		<div class="find">
		
		<form method="POST" action="">	
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
							
							self.location='o12tab_open_close.php?f='+s0+'&tt='+s1;
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
							
							self.location='o12tab_open_close.php?f='+s0+'&tt='+s1;
						}
					</script>
			</div>
		
	</div>
	<br><br>
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

		$c=0;$r=1;
		$temp="";
		$conn = new mysqli("localhost", "root","Tamil", "mypcm");
		echo'<table id="testTable" align="center">
		<tr>
			<th>RCNO / DCNO </th>
			<th>OPERATION </th>
			<th>RAW MATERIAL</th>
			<th>ISSUE DATE</th>
			<th>CLOSE DATE</th>
			<th>ISSUED QUANTITY</th>
			<th>UOM</th>
			<th>PART NUMBER</th>
			<th>RECEIVED QUANTITY (Nos)</th>
			<th>REJECTED QTY (Nos)</th>
			<th>USED QTY</th>
			
			
			<th>SHORTAGE QTY(RC Closed with Shortage)</th>
			<th>SHORTAGE PERCENTAGE</th>
			
			<th>QTY IN PROCESS(Qty Under Process)</th>
			
			<th>UOM</th>
			
			<th>VALUE (FOR RC CLOSED WITH SHORTAGE) (Rs) </th>
			
			<th>VALUE (FOR QTY UNDER PROCESS) (Rs) </th>
			
			<th>FOREMAN</th>
		</tr>';
			
		//$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%',T9.scn,IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,d11.closedate,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date>='$f' AND d11.date<='$tt' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN (SELECT rcno,dcnum,scn FROM `d11` LEFT JOIN (SELECT dcnum,scn FROM `dc_det`) AS T8 ON d11.rcno=CONCAT('DC-',T8.dcnum) WHERE operation='FG For S/C' AND closedate='0000-00-00') AS T9 ON T2.rcno=T9.rcno GROUP BY T2.rcno order by t2.date,t2.rcno";
		//$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%',T9.scn,IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T2.operation LIKE 'CLE UNIT 2','VSS U-2',IF(T5.foreman IS NULL,T6.foreman,T5.foreman))))) as foreman,T6.foreman AS foreman1,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,d11.closedate,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.date>='$f' AND d11.date<='$tt' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN (SELECT rcno,dcnum,scn FROM `d11` LEFT JOIN (SELECT dcnum,scn FROM `dc_det`) AS T8 ON d11.rcno=CONCAT('DC-',T8.dcnum) WHERE operation='FG For S/C' AND closedate='0000-00-00') AS T9 ON T2.rcno=T9.rcno GROUP BY T2.rcno order by t2.date,t2.rcno";
		
		$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%',T9.scn,IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T2.operation LIKE 'CLE UNIT 2','VSS U-2',IF(T2.operation LIKE 'Returned','Karthi',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))))) as foreman,T6.foreman AS foreman1,T2.date,T2.closedate,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,d11.closedate,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.date>='$f' AND d11.date<='$tt' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN (SELECT rcno,dcnum,scn FROM `d11` LEFT JOIN (SELECT dcnum,scn FROM `dc_det`) AS T8 ON d11.rcno=CONCAT('DC-',T8.dcnum) WHERE operation='FG For S/C' AND closedate='0000-00-00') AS T9 ON T2.rcno=T9.rcno GROUP BY T2.rcno order by t2.date,t2.rcno";
		$result = $conn->query($query);
		
		$row = $result->fetch_assoc();
		do{
			//$d = date("d-m-Y", strtotime($row['date']));
			$d = $row['date'];
			if($row['foreman']=="")
			{
				$f=$row['foreman1'];
				if($f=="")
				{
					$pnum=$row['pnum'];
					$query1 = "SELECT DISTINCT pnum,foreman FROM m13 WHERE pnum LIKE '%$pnum%'";
					$result1 = $conn->query($query1);
					$row1 = mysqli_fetch_array($result1);
					$f=$row1['foreman'];
				}
			}
			else
			{
				$f=$row['foreman'];
			}
			
			if($row['closedate']=="0000-00-00"){
				$closedate = "OPEN";
			}else{
				$closedate = $row['closedate'];
			}
			
				
			
			echo"<tr>";
			
			echo"<td>".$row['rcno']."</td>";
			echo"<td>".$row['operation']."</td>";
			echo"<td>".$row['rm']."</td>";
			echo"<td>".$d."</td>";
			
			echo"<td>".$closedate."</td>";
			
			echo"<td>".round($row['issqty'],2)."</td>";
			echo"<td>".$row['unit']."</td>";
			echo"<td>".$row['pnum']."</td>";
			echo"<td>".$row['received']."</td>";
			echo"<td>".$row['rejected']."</td>";
			
			echo"<td>".round($row['used'],2)."</td>";
			
			if($row['closedate']=="0000-00-00"){
				echo"<td>".""."</td>";
				echo"<td>".""."</td>";
				echo"<td>".round($row['notused'],2)."</td>";
			}
			else{
				echo"<td>".round($row['notused'],2)."</td>";
				echo"<td>".round( (($row['issqty']-$row['used'])*100)/$row['issqty'] , 2)."</td>";
				echo"<td>".""."</td>";
			}
			
			
			echo"<td>".$row['unit']."</td>";
			
			if($row['closedate']=="0000-00-00"){
				echo"<td>".""."</td>";
				echo"<td>".round($row['value'],2)."</td>";
			}
			else{
				echo"<td>".round($row['value'],2)."</td>";
				echo"<td>".""."</td>";
			}
			
			//echo"<td>".round($row['value'],2)."</td>";
			
			echo"<td>".$f."</td>";
			
			
		}while($row = $result->fetch_assoc());
	?>
</div>
</body>
</html>