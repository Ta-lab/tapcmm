<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="BOM";
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
		<h4 style="text-align:center"><label>BOM </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'BOM')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "erpdb");
			
			echo'<table id="testTable" align="center">
				<tr>
					<th>SUPPLIER NAME</th>
					<th>SUPPLIER CODE</th>
					<th>INTERNAL RM DESCRIPTION/th>
					<th>EXTERNAL RM DESCRIPTION</th>
					<th>UOM</th>
					<th>RATE</th>
					<th>PURCHASE TYPE</th>
					<th></th>
				</tr>';
				$query = "SELECT * FROM(SELECT scode,internal_rmdesc,external_rmdesc,uom,rate FROM `m21_supplierpartdetail`) AS T1 LEFT JOIN(SELECT sname,scode,purchasetype FROM `m20_suppliermaster`) AS T2 ON T1.scode=T2.scode";
				$result = $conn->query($query);
				$total=0;
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['sname']."</td>";
					echo"<td>".$row['scode']."</td>";
					echo"<td>".$row['internal_rmdesc']."</td>";
					echo"<td>".$row['external_rmdesc']."</td>";
					echo"<td>".$row['uom']."</td>";
					echo"<td>".$row['rate']."</td>";
					echo"<td>".$row['purchasetype']."</td>";
					echo"</tr>";
				}
				
			?>
		</div>
		
</body>
</html>

