<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['user']=="123")
	{
		$id=$_SESSION['user'];
		$activity="RECONCILIATION";
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
		<h4 style="text-align:center"><label>RECONCILIATION IMPROVAL</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	
	<br><br>
	
	<div class="divclass">
	<form id="form" name="form" method="post" action="reconciliation_improval_db.php">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
			
			echo'<table id="testTable" align="center">
				<tr>
					<th>STOCKINGPOINT</th>
					<th>DATE</th>
					<th>PARTNUMBER</th>
					<th>PRCNO</th>
					<th>ERP QTY</th>
					<th>ACTUAL QTY</th>
					<th></th>
				</tr>';
				$query = "SELECT * FROM `reconciliation_improval`";
				$result = $conn->query($query);
				$total=0;
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['stockingpoint']."</td>";
					echo"<td>".$row['date']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['prcno']."</td>";
					echo"<td>".$row['erpqty']."</td>";
					echo"<td>".$row['actualqty']."</td>";
					echo"</tr>";
				}
			
		
		echo'<div>
			<input type="submit" name="approve" value="APPROVE">	
		</div>';
			
				
				
			?>
		</div>
		
		
		
</body>
</html>

