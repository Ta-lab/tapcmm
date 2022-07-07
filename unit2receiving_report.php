<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="SC INV REPORT";
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
		<h4 style="text-align:center"><label> VSS UNIT - 2 INVENTRY REPORT </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'U2')" value="Export to Excel">
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
					<th>DC DATE</th>
					<th>PART NUMBER</th>
					<th>SUB CONTRACTOR</th>
					<th>DC NUMBER</th>
					<th>DC QTY</th>
					<th>UNIT 2 RECEIVED QTY</th>
					<th>NOT RECEIVED QTY</th>
					
					<th>S/C VIRTUAL DC CLOSING </th>
					<th>REJECTED QTY </th>
					
					<th>AVAILABLE STOCK</th>
				</tr>';
				
				
				$query2 = "SELECT * FROM `unit2receiveentry`";
				
				$result2 = $conn->query($query2);
				
				while($row2 = mysqli_fetch_array($result2))
				{	
					$dcnum = $row2['dcnum'];
					$query3 = "SELECT * FROM `subcondb` WHERE date>='2022-04-26' AND dcnum='$dcnum' ";
					$result3 = $conn->query($query3);
					$row3 = mysqli_fetch_array($result3);
					
					echo"<tr><td>".$row2['date']."</td>";
					echo"<td>".$row2['pnum']."</td>";
					echo"<td>".$row2['scn']."</td>";
					echo"<td>".$row2['dcnum']."</td>";
					
					echo"<td>".$row2['total_dc_qty']."</td>";
					
					//echo"<td>".$row2['total_rec_qty']."</td>";
					
					echo"<td>".$row2['total_rec_qty']."</td>";
					
					$notreceived = $row2['total_dc_qty'] - $row2['total_rec_qty'];
					
					echo"<td>".$notreceived."</td>";
					
					echo"<td>".$row3['total_rec_qty']."</td>";
					
					//Rej
					$query4 = "SELECT SUM(qtyrejected) AS rej FROM `d12` WHERE operation='alfa-n-ind-prim' AND prcno='$dcnum' ";
					$result4 = $conn->query($query4);
					$row4 = mysqli_fetch_array($result4);
					
					echo"<td>".$row4['rej']."</td>";
					
					
					$stock = $row2['total_rec_qty'] - ( $row4['rej'] + $row3['total_rec_qty'] ); 
					
					echo"<td>".$stock."</td>";
					
					echo"</tr>";
					
				}
			?>
		</div>
		
</body>
</html>

