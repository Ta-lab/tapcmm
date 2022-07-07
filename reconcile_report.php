<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="RECONCILE REPORT";
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
		<h4 style="text-align:center"><label>RECONCILATION REPORT</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'RECONCILE REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				
				$tdate=date('Y-m-d');
				$ydate = date('Y-m-d', strtotime('-1 days'));
			
				
				
			echo'<table id="testTable" align="center">
				<tr>
					
					<th>DATE</th>
					<th>STOCKING POINT</th>
					<th>PART NUMBER</th>
					<th>RC NO</th>
					<th>ERP QTY</th>
					<th>ACTUAL QTY</th>
					<th>DIFFERENCE</th>
					<th>RATE</th>
					<th>VALUE PERCENTAGE</th>
					<th>VALUE</th>
					
					<th></th>
				</tr>';
				
				$query = "SELECT *,dif*rp*v/100 AS val FROM(SELECT sp,dt,pn,pr,eq,aq,eq-aq AS dif FROM `d17` WHERE dt>='2020-02-29') AS rc LEFT JOIN(SELECT stkpt,pnum,invpnum FROM pn_st WHERE stkpt LIKE '%FG For Invoicing%') AS pnst ON rc.pn=pnst.pnum LEFT JOIN(SELECT DISTINCT pn,rate,per,rate/per AS rp FROM invmaster) AS invm ON rc.pn=invm.pn OR invm.pn=pnst.invpnum LEFT JOIN(SELECT stkpt,value AS v FROM m14) AS Tm14 ON rc.sp=Tm14.stkpt GROUP BY pr";
				$result = $conn->query($query);
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr>";
					echo"<td>".$row['dt']."</td>";
					echo"<td>".$row['sp']."</td>";
					echo"<td>".$row['pn']."</td>";
					echo"<td>".$row['pr']."</td>";
					echo"<td>".$row['eq']."</td>";
					echo"<td>".$row['aq']."</td>";
					echo"<td>".$row['dif']."</td>";
					echo"<td>".round($row['rp'],2)."</td>";
					echo"<td>".$row['v']."%"."</td>";
					echo"<td>".round($row['val'],2)."</td>";
					
					echo"</tr>";
				}
				
				
			?>
		</div>
		
</body>
</html>

