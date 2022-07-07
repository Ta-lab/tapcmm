<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];

if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="FG For Scrap";
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
		<h4 style="text-align:center"><label>FI DETAIL</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'DMR')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				
				//$tdate=date('Y-m-d');
				//$ydate = date('Y-m-d', strtotime('-1 days'));
			
				
				
			echo'<table id="testTable" align="center">
				<tr>
					<th>CCODE</th>
					<th>PART NUMBER</th>
					<th>SNO</th>
					<th>CHARS</th>
					<th>DRAWSPEC</th>
					<th>METHOD</th>
					<th>LSL</th>
					<th>USL</th>
					<th>UNIT</th>
					
					
				</tr>';
				
				$query = "SELECT * FROM fi_detail1";
				$result = $conn->query($query);
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr>";
					echo"<td>".$row['ccode']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['s.no']."</td>";
					echo"<td>".$row['chars']."</td>";
					echo"<td>".$row['drawspec']."</td>";
					echo"<td>".$row['method']."</td>";
					echo"<td>".$row['lsl']."</td>";
					echo"<td>".$row['usl']."</td>";
					echo"<td>".$row['unit']."</td>";
					echo"</tr>";
					
					//iconv('UTF-8', 'CP1250//TRANSLIT', $fch['drawspec'] ),
					$ccode=$row['ccode'];
					$pnum=$row['pnum'];
					$sno=$row['s.no'];
					$chars=$row['chars'];
					$drawspec=iconv('CP1250','UTF-8',$row['drawspec']);
					$method=$row['method'];
					$lsl=$row['lsl'];
					$usl=$row['usl'];
					$unit=$row['unit'];
					
					mysqli_query($con,"INSERT INTO fi_detail (`ccode`, `pnum`, `s.no`, `chars`, `drawspec`, `method`, `lsl`, `usl`, `unit`)
								VALUES ('$ccode','$pnum','$sno','$chars','$drawspec','$method','$lsl','$usl','$unit')");

					
				
				
				}
				
				
			?>
		</div>
		
</body>
</html>

