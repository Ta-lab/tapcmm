<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="STOCK REPORT";
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
		<h4 style="text-align:center"><label> VARIABLE PAY MASTER UPDATE </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
	</div>
			<?php
				echo'<table id="testTable" align="center">
					  <tr>
						<th>FOREMAN NAME</th>
						<th>TOTAL PRODUCTION COMMIT (%)</th>
						<th>NO OF CUSTOMER COMPLAINT ( Nos ) </th>
						<th>SCORE (%)</th>
						<th>CNC COMMIT ACHIVED (%)</th>
						<th>MANUAL COMMIT ACHIVED (%)</th>
						<th>NPD COMMIT ACHIVED (%)</th>
						<th>CNC REJ (%)</th>
						<th>OTHER AREA REJ (%)</th>
						<th>TOTAL W.Hrs</th> 
						<th>MACHINE DOWN TIME ( In Hrs )</th>
					  </tr>';
				$query = "SELECT * from variablepay";
				$result = $con->query($query);
				while($row = mysqli_fetch_array($result))
				{
					echo "<td>".$row['NAME']."</td></tr>";
				}
					 
	?>
	</table>
	</div>
	
</body>
</html>