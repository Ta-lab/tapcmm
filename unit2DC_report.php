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
		<h4 style="text-align:center"><label> VSS UNIT - 2 DC REPORT </label></h4>
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
					<th>DC NUMBER</th>
					<th>DC DATE</th>
					<th>SUBCONTRACTOR</th>
					<th>GST NUMBER</th>
					<th>PART NUMBER</th>
					<th>HSN CODE</th>
					<th>QUANTITY</th>
					<th>RATE</th>
					<th>PER</th>
					<th>UOM</th>
					<th>BASIC VALUE</th>
					<th>CGST %</th>
					<th>SGST %</th>
					<th>IGST %</th>
					<th>CGST AMOUNT</th>
					<th>SGST AMOUNT</th>
					<th>IGST AMOUNT</th>
					<th>TOTAL VALUE</th>
					<th>VEHICLE NUMBER</th>	
				</tr>';
				
				
				
				
				$query = "SELECT * FROM `unit2_dc_det`";
				
				$result = $conn->query($query);
				
				while($row = mysqli_fetch_array($result))
				{	
					$pn = $row['pn'];
					$scn = $row['scn'];
					$query1 = "SELECT gst,hsnc FROM dcmaster where pn='$pn' AND sccode='$scn' ";
					$result1 = $conn->query($query1);
					$row1 = mysqli_fetch_array($result1);
					
					echo" <tr>";
					echo"<td>".$row['dcnum']."</td>";
					echo"<td>".$row['dcdate']."</td>";
					echo"<td>"."VSS U-1"."</td>";
					echo"<td>"."33AACCV3065F1ZL"."</td>";
					echo"<td>".$row['pn']."</td>";
					echo"<td>".$row1['hsnc']."</td>";
					echo"<td>".$row['qty']."</td>";
					echo"<td>".($row['rate']*$row['value_percentage']/100)."</td>";
					echo"<td>".$row['per']."</td>";
					echo"<td>".$row['uom']."</td>";
					echo"<td>".$row['basicvalue']."</td>";
					echo"<td>".$row['cgst']."</td>";
					echo"<td>".$row['sgst']."</td>";
					echo"<td>".$row['igst']."</td>";
					echo"<td>".$row['cgstamount']."</td>";
					echo"<td>".$row['sgstamount']."</td>";
					echo"<td>".$row['igstamount']."</td>";
					echo"<td>".$row['totalvalue']."</td>";
					echo"<td>".$row['vehiclenumber']."</td>";
						
					
					echo"</tr>";
					
				}
			?>
		</div>
		
</body>
</html>

