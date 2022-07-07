<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="VALUATION";
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
		<h4 style="text-align:center"><label> OPENING / CLOSING STOCK AS ON DATE</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<div class="find">
		<label>DATE</label>
			<input type="date" id="t" name="t"  onchange="reload1(this.form)" value="<?php
				if(isset($_GET['t']))
				{
					echo $_GET['t'];
				}
				else
				{
					echo date('Y-m-d');
				}
			?>"/>
			
			<script>
				function reload1(form)
				{
					var s3 = document.getElementById("t").value;
					self.location='valuation_final.php?t='+s3;
				}
			</script>	
	</div>
	<br><br>
	<div class="divclass">
			<?php
				
				if(!(isset($_GET['t']) && isset($_GET['f'])))
				{
					//$f = date('Y-m-d',strtotime('-30 days'));
					$t = $_GET['t'];
				}
				else
				{
					$f = $_GET['f'];
					$t = $_GET['t'];
				}
				
				/*$dm=date("Y-m");
				date_default_timezone_set('Asia/Kolkata');
				$ym=date("Y-m",strtotime($dm));
				$my=date("m-Y",strtotime($dm));
				$ymdf=$ym."-01";
				$ymdl=$ym."-31";*/
			
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
						<th>VALUE</th>
						<th>BOM</th>
						<th>KG</th>
					  </tr>';
				$query2 = "SELECT *,IF(useage IS NULL,tot,useage) AS bomuseage FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*((T7.useage/T7.tot)*rate))/per)*v)/100,(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per)*v)/100)  as value,v FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$t' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND (d11.closedate='0000-00-00' OR d11.closedate>'$t') AND d11.operation!='FG For Invoicing' AND d11.date<='$t' AND d12.date<='$t' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper GROUP BY T2.rcno HAVING notused>0 order by t2.operation) AS VALREP LEFT JOIN (SELECT DISTINCT pnum AS m13pnum,useage FROM `m13`) AS m13bom ON VALREP.pnum=m13bom.m13pnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS pnstbom ON pnstbom.invpnum=VALREP.pnum";
				$result2 = $conn->query($query2);
				while($row = mysqli_fetch_array($result2))
				{
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['operation']."</td>";
					echo"<td>".$row['rcno']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['notused']."</td>";
					//echo"<td>".$row['unit']."</td>";
					echo"<td>".$row['value']."</td>";
					
					$bomuseage = $row['bomuseage'];
					if($row['unit']=="KG" || $row['unit']=="kg"){
						$bomuseage = 1;
						$kg_wt = $row['notused']*$bomuseage;
					}else{
						$kg_wt = $row['notused']*$bomuseage;
					}
					echo"<td>".$bomuseage."</td>";
					echo"<td>".$kg_wt."</td>";
					
					echo"</tr>";
				}
				
				$query3 = "SELECT *,IF(useage IS NULL,tot,useage) AS bomuseage FROM (SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='$t' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc ) AS VALREP LEFT JOIN (SELECT DISTINCT pnum AS m13pnum,useage FROM `m13`) AS m13bom ON VALREP.pnum=m13bom.m13pnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS pnstbom ON pnstbom.invpnum=VALREP.pnum ORDER BY date,stkpt";
				$result3 = $conn->query($query3);
				while($row1 = mysqli_fetch_array($result3))
				{
					echo"<tr><td>".$row1['date']."</td>";
					echo"<td>".$row1['stkpt']."</td>";
					echo"<td>".$row1['prc']."</td>";
					echo"<td>".$row1['pnum']."</td>";
					echo"<td>".$row1['s']."</td>";
					//echo"<td>".$row['unit']."</td>";
					echo"<td>".$row1['value']."</td>";
					echo"<td>".$row1['bomuseage']."</td>";
					echo"<td>".$row1['s']*$row1['bomuseage']."</td>";
					
					echo"</tr>";
				}
				
				
			?>
		</div>
		
</body>
</html>