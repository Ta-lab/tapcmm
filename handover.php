<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="RECONCILE REPORT";
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
		<h4 style="text-align:center"><label>HANDOVER REPORT</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'HANDOVER REPORT')" value="Export to Excel">
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
					
					<th>DATE</th>
					<th>OPERATION</th>
					<th>PART NUMBER</th>
					<th>ISSUED QTY</th>
					<th>REJECTED QTY</th>
					
					<th></th>
				</tr>';
				
				$query = "SELECT date,stkpt,operation,workcentre,rmissqty,pnum,rcno,prcno,partissued,partreceived,qtyrejected,inv,heat,lot,coil,username,ip,created FROM `d12` WHERE date>='2020-03-06' AND date<='2020-03-12'";
				$result = $conn->query($query);
				foreach($result as $res){
					echo json_encode($res);
					echo "<tr>";
					echo"<td>".$res['date']."</td>";
					echo "</tr>";
				}
				
				/*while($row = mysqli_fetch_array($result))
				{
					echo"<tr>";
					echo"<td>".$row['date']."</td>";
					echo"<td>".$row['area']."</td>";
					echo"<td>".$row['mc_pn']."</td>";
					echo"<td>".$row['genrcno']."</td>";
					echo"<td>".$row['qty']."</td>";
					echo"<td>".round($row['rp'],2)."</td>";
					echo"<td>".$row['valper']."%"."</td>";
					
					$value=$row['qty']*$row['rp']*$row['valper']/100;
					echo"<td>".round($value,2)."</td>";
					
					echo"</tr>";
				}*/
				
				
			?>
		</div>
		
</body>
</html>

