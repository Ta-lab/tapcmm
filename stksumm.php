<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="STORES STOCK REPORT";
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
?>
<!DOCTYPE html>
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
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<body>
	<div style="float:right">
			<a href="index.html"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
		<h4 style="text-align:center"><label>STORES STOCK REPORT  [ O23 ]</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
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
				$con = new mysqli($servername, $username, $password, "storedb");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>GRN NUMBER</th>
						<th>DATE OF INWARD</th>
						<th>MATERIAL CODE</th>
						<th>RM DESCRIPTION</th>
						<th>SUPPLIER NAME</th>
						<th>QTY INWARDED</th>
						<th>QTY ACCEPTED</th>
						<th>QTY REJECTED</th>
						<th>QTY UNDER INSPECTION</th>
						<th>USED</th>
						<th>STOCK IN STORES</th>
						<th>STATUS</th>
					  </tr>';
				$query2 = "SELECT * FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' GROUP BY inv) AS T ON receipt.grnnum=T.inv  GROUP BY grnnum) AS T WHERE closed='0000-00-00' AND date>='2018-04-01' AND date<='2019-03-31'";
				$result2 = $con->query($query2);
				$tot=0;
				$total=0;
				while($row1 = mysqli_fetch_array($result2))
				{
					if(($row1['inwarded']-($row1['accepted']+$row1['rejected']))>0 && $row1['age']>3)
					{
						$color="style='color : yellow;'";
					}
					else
					{
						$color="";
					}
					echo"<tr><td><h4 $color><a href='i27.php?grnnum=".$row1['grnnum']."'/>".$row1['grnnum']."</h4></td><td><h4 $color>".$row1['date']."</h4></td><td><h4 $color>".$row1['part_number']."</h4></td><td><h4 $color>".$row1['rmdesc']."</h4></td>";
					echo"<td><h4 $color>".$row1['sname']."</h4></td><td><h4 $color>".$row1['inwarded']."</h4></td><td><h4 $color>".$row1['accepted']."</h4></td><td><h4 $color>".$row1['rejected']."</h4></td>";
					echo"<td><h4 $color>".($row1['inwarded']-($row1['accepted']+$row1['rejected']))."</h4></td>
					<td><h4 $color>".$row1['used']."</h4></td>
					<td><h4 $color>".($row1['accepted']-$row1['used'])."</h4></td>";
					$total=$total+$row1['accepted']-$row1['used'];
					//$total=$total+$tot;
					if($_SESSION['access']=="ALL" || $_SESSION['access']=="Stores")
					{
						echo "<td><h4 $color><a href='closegrn.php?grn=".$row1['grnnum']."'>CLOSE</a></h4></td>";
					}
					else
					{
						echo "<td></td>";
					}
					echo"</tr>";
				}
				echo"<tr><td>$total</td></tr>";
				
			?>
		</div>
		
</body>
</html>