<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="VALUATION STOCKING";
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
		<h4 style="text-align:center"><label> VALUATION STOCKING POINT </label></h4>
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
					<th>STKPT</th>
					<th>RC NO</th>
					<th>PART NUMBER</th>
					<th>QTY</th>
					<th>UNIT</th>
					<th>VALUE</th>
					<th>BOM</th>
					<th>WEIGHT IN KG</th>
					<th></th>
				</tr>';
				$query = "SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days,T7.tot,useage FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='2019-03-31' AND d12.date>='2018-04-01' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc,date";
				$result = $conn->query($query);
				$total=0;
				$unit="NOS";
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['stkpt']."</td>";
					echo"<td>".$row['prc']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['s']."</td>";
					echo"<td>".$unit."</td>";
					echo"<td>".round($row['value'],2)."</td>";
					echo"<td>".$row['tot']."</td>";
					echo"<td>".$row['s']*$row['tot']."</td>";
					echo"</tr>";
					$total=$total+$row['s']*$row['tot'];
				}
				
				echo" <tr>
					<td colspan='8'><h4>TOTAL WEIGHT</h4></td>
					<td><h4>".$total."</h4></td>";
				echo"</tr>";
				
				//echo"<tr><td>$total</td></tr>";
			?>
		</div>
		
</body>
</html>

