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
		<h4 style="text-align:center"><label>FG For Scrap</label></h4>
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
					<th>DATE</th>
					<th>PART NUMBER</th>
					<th>RC/DC NUMBER</th>
					<th>STOCKING POINT</th>
					<th>QTY</th>
					<th>DAYS</th>
					
					
				</tr>';
				
				$query = "SELECT DISTINCT date,stkpt,pnum,prc AS rcno,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt='FG For Invoicing' HAVING days>30 order by days";
				$result = $conn->query($query);
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr>";
					echo"<td>".$row['date']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['rcno']."</td>";
					echo"<td>".$row['stkpt']."</td>";
					echo"<td>".round($row['s'],2)."</td>";
					echo"<td>".$row['days']."</td>";
					echo"</tr>";
					
					$date=date("Y-m-d");
					$stkpt=$row['stkpt']; 
					$pnum=$row['pnum'];
					$rcno=$row['rcno'];
					$qty=$row['s'];
					
					mysqli_query($con,"INSERT INTO d12 (`date`, `stkpt`, `pnum`, `rcno`, `prcno`, `partissued`, `username`, `ip`)
								VALUES ('$date','FG For Invoicing','$pnum','$rcno','$rcno','$qty','$u','$ip')");

					mysqli_query($con,"INSERT INTO d12 (`date`, `stkpt`, `pnum`, `prcno`, `partreceived`, `username`, `ip`)
								VALUES ('$date','FG For Scrap','$pnum','$rcno','$qty','$u','$ip')");
	
				
				
				
				}
				
				
			?>
		</div>
		
</body>
</html>

