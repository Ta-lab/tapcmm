<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="REJECTION STOCK";
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
		<h4 style="text-align:center"><label>SCRAP REJECTION STOCK </label></h4>
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
					<th>REJ QTY</th>
					<th>BOM</th>
					<th>WEIGHT IN KG</th>
					<th></th>
				</tr>';
				$query = "SELECT * FROM (SELECT d12.date,d11.operation,name,prcno,d12.pnum,qtyrejected,fno FROM `d12` LEFT JOIN d11 ON d12.prcno=d11.rcno LEFT JOIN m15 ON d11.operation=m15.prev WHERE d12.date>='2018-04-01' AND d12.date<='2019-03-31' AND qtyrejected!='') AS REJ LEFT JOIN (SELECT DISTINCT pnum AS bompnum,useage FROM m13) AS bom ON REJ.pnum=bom.bompnum ";
				$result = $conn->query($query);
				$total=0;
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['name']."</td>";
					echo"<td>".$row['prcno']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['qtyrejected']."</td>";
					echo"<td>".$row['useage']."</td>";
					echo"<td>".$row['qtyrejected']*$row['useage']."</td>";					
					$total=$total+$row['qtyrejected']*$row['useage'];
					echo"</tr>";
				}
				
				echo" <tr>
					<td colspan='6'><h4>TOTAL WEIGHT</h4></td>
					<td><h4>".$total."</h4></td>";
				echo"</tr>";
				
				//echo"<tr><td>$total</td></tr>";
			?>
		</div>
		
</body>
</html>

