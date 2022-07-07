<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="OB REG/STR INVOICED STATUS";
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
		<h4 style="text-align:center"><label> ORDER BOOK REGULAR / STRANGER INVOICED STATUS </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'OB REG/STR INVOICED REPORT')" value="Export to Excel">
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
						<th>PART NUMBER</th>
						<th>TYPE</th>
						<th>ORDER QTY</th>
						<th>INVOICED QTY</th>
						<th>BALANCE</th>
					  </tr>';
				$query2 = "SELECT pnum,type,OBQTY,IF(invoicedqty IS NULL,0,invoicedqty) AS invoicedqty,IF(OBQTY-invoicedqty IS NULL,0,OBQTY-invoicedqty) AS BAL FROM (SELECT pnum,type,SUM(qty) AS OBQTY FROM `orderbook` GROUP BY pnum) AS OB LEFT JOIN(SELECT pn,SUM(qty) AS invoicedqty FROM inv_det WHERE invdt>='2019-10-01' GROUP BY pn) AS INV ON OB.pnum=INV.pn ";
				$result2 = $conn->query($query2);
				while($row1 = mysqli_fetch_array($result2))
				{
					echo"<tr><td>".$row1['pnum']."</td>";
					echo"<td>".$row1['type']."</td>";
					echo"<td>".$row1['OBQTY']."</td>";
					echo"<td>".$row1['invoicedqty']."</td>";
					echo"<td>".$row1['BAL']."</td>";			
					echo"</tr>";
				}
			?>
		</div>
		
</body>
</html>




















