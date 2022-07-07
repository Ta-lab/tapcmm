<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="VALUATION OPERATION";
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
		<h4 style="text-align:center"><label> VALUATION OPERATION</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'REJECTION')" value="Export to Excel">
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
					<th>DATE</th>
					<th>OPERATION</th>
					<th>RC NO</th>
					<th>PART NUMBER</th>
					<th>QTY</th>
					<th>UNIT</th>
					<th>VALUE</th>
					<th>BOM</th>
					<th>WEIGHT IN KG</th>
					<th></th>
				</tr>';
				$query = "SELECT DISTINCT * FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*((T7.useage/T7.tot)*rate))/per)*v)/100,(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per)*v)/100)  as value,v FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='2019-03-31' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND (d11.closedate='0000-00-00' OR d11.closedate!='0000-00-00') AND d11.operation!='FG For Invoicing' AND d11.date<='2019-03-31' AND d12.date<='2019-03-31' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper GROUP BY T2.rcno HAVING notused>0 order by t2.operation) AS valop LEFT JOIN (SELECT DISTINCT pnum AS pnst_pnum,useage FROM m13) AS billom ON billom.pnst_pnum=valop.pnum order by operation";
				$result = $conn->query($query);
				$total=0;
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['operation']."</td>";
					echo"<td>".$row['rcno']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['notused']."</td>";
					echo"<td>".$row['unit']."</td>";
					echo"<td>".round($row['value'],2)."</td>";
					echo"<td>".$row['bom']."</td>";
					echo"<td>".$row['notused']*$row['bom']."</td>";
					echo"</tr>";
					$total=$total+$row['notused']*$row['bom'];
				}
				
				echo" <tr>
					<td colspan='8'><h4>TOTAL WEIGHT</h4></td>
					<td><h4>".$total."</h4></td>";
				echo"</tr>";
				
				
			?>
		</div>
		
</body>
</html>

