<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="111")
	{
		$id=$_SESSION['user'];
		$activity="REGULAR / STRANGER STATUS";
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
		<h4 style="text-align:center"><label> REGULAR / STRANGER STATUS </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
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
						<th>ORDER QTY</th>
						<th>REQUESTED</th>
						<th>INVOICED QTY</th>
						<th>BALANCE</th>
					  </tr>';
				$query2 = "SELECT * FROM `orderbook` LEFT JOIN (SELECT pn,SUM(inv_det.qty) AS invoiced FROM inv_det WHERE inv_det.invdt>'2019-08-07' GROUP BY pn) AS T1 ON orderbook.pnum=T1.pn";
				$result2 = $conn->query($query2);
				$p="";$q=0;$b=0;$c=0;
				while($row1 = mysqli_fetch_array($result2))
				{
					if($p!=$row1['pnum'])
					{
						$p=$row1['pnum'];
						$c=0;
						if($row1['qty']<$row1['invoiced'])
						{
							$q=$row1['qty'];
							$b=0;
							$c=0;
						}
						else
						{
							$q=round($row1['invoiced'],0);
							if($row1['qty']-$row1['invoiced']>0)
							{
								$b=$row1['qty']-$row1['invoiced'];
								$c=$b-$c;
							}
							else
							{
								$b=0;
								$c=0;
							}
						}
					}
					else
					{
						$b=0;
						if($row1['qty']<$row1['invoiced'])
						{
							$q=0;
							$b=$row1['qty'];
						}
						else
						{
							$q=0;
							$b=$row1['qty'];
						}
					}
					echo"<tr><td>".$row1['pnum']."</td>";
					echo"<td>".$row1['qty']."</td>";
					echo"<td>".date('d-m-Y', strtotime($row1['req_date']))."</td>";
					echo"<td>".$q."</td>";
					echo"<td>".$b."</td>";
					echo"</tr>";
					
				}
			?>
		</div>
		
</body>
</html>