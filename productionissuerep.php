<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="PRODUCTION ISSUE REPORT";
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
		<h4 style="text-align:center"><label>PRODUCTION ISSUE REPORT</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'PRODUCT ISSUE REPORT')" value="Export to Excel">
	</div>
	<br><br>
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
						<th>RC NO</th>
						<th>PART NO</th>
						<th>GRN NUMBER</th>
						<th>RAW MATERIAL</th>
						<th>RM ISSUED QTY</th>
					  </tr>';
				$query2 = "SELECT date,rm,rmissqty,pnum,rcno,inv FROM `d12` WHERE inv!='' AND date>='2020-01-01' AND date<='2020-01-31' ";
				$result2 = $conn->query($query2);
				while($row = mysqli_fetch_array($result2))
				{
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['rcno']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['inv']."</td>";
					echo"<td>".$row['rm']."</td>";
					echo"<td>".$row['rmissqty']."</td>";
					echo"</tr>";
				}
				
			?>
		</div>
		
</body>
</html>