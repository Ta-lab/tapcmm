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
		<h4 style="text-align:center"><label>OUTPUT BOM </label></h4>
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
				$conn = new mysqli($servername, $username, $password, "mypcm");
			
			echo'<table id="testTable" align="center">
				<tr>
					<th>PNUM</th>
					<th>USEAGE</th>
					<th></th>
				</tr>';
				/*$query = "SELECT DISTINCT pnum,useage FROM `m13`";
				$result = $conn->query($query);
				$total=0;
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['pnum']."</td>";
					echo"<td>".$row['useage']."</td>";
					echo"</tr>";
				}
				*/
				
				$query = "SELECT invpnum,SUM(obom) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum";
				$result = $conn->query($query);
				$total=0;
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['invpnum']."</td>";
					echo"<td>".$row['tot']."</td>";
					echo"</tr>";
				}
				
			?>
		</div>
		
</body>
</html>

