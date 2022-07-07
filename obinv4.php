<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="OB KANBAN INVOICED STATUS";
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
		<h4 style="text-align:center"><label> ORDER BOOK STATUS </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'OB STATUS')" value="Export to Excel">
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
					<th>INVPNUM</th> 
					<th>ORDER QTY</th>
					<th>INVOICED QTY</th>
					
				</tr>';
				//$query = "SELECT obdm.pnum,invpnum,orderqty,IF(invoicedqty IS NULL,invoicedqty1,IF(invoicedqty1 IS NULL,invoicedqty,invoicedqty1)) AS invoiced FROM (SELECT pnum,SUM(qty) AS orderqty FROM orderbook GROUP BY pnum UNION SELECT pnum,monthly AS orderqty FROM demandmaster ) AS obdm LEFT JOIN(SELECT pnum,invpnum,stkpt FROM pn_st WHERE stkpt LIKE 'FG For Invoicing') AS pnst ON pnst.pnum=obdm.pnum LEFT JOIN(SELECT pn,IF(SUM(qty) IS NULL,0,SUM(qty)) AS invoicedqty FROM inv_det WHERE invdt>='2019-12-02' AND invdt<='2019-12-31' GROUP BY pn) AS inv ON inv.pn=pnst.invpnum LEFT JOIN(SELECT pn,IF(SUM(qty) IS NULL,0,SUM(qty)) AS invoicedqty1 FROM inv_det WHERE invdt>='2019-12-02' AND invdt<='2019-12-31' GROUP BY pn) AS inv1 ON inv1.pn=obdm.pnum  ORDER BY obdm.pnum";
				$query = "SELECT obdm.pnum,invpnum,orderqty,invoicedqty,invoicedqty1 FROM (SELECT pnum,SUM(qty) AS orderqty FROM orderbook GROUP BY pnum UNION SELECT pnum,monthly AS orderqty FROM demandmaster ) AS obdm LEFT JOIN(SELECT pnum,invpnum,stkpt FROM pn_st WHERE stkpt LIKE 'FG For Invoicing') AS pnst ON pnst.pnum=obdm.pnum LEFT JOIN(SELECT pn,IF(SUM(qty) IS NULL,0,SUM(qty)) AS invoicedqty FROM inv_det WHERE invdt>='2019-12-02' AND invdt<='2019-12-31' GROUP BY pn) AS inv ON inv.pn=obdm.pnum LEFT JOIN(SELECT pn,IF(SUM(qty) IS NULL,0,SUM(qty)) AS invoicedqty1 FROM inv_det WHERE invdt>='2019-12-02' AND invdt<='2019-12-31' GROUP BY pn) AS inv1 ON inv1.pn=pnst.invpnum   ORDER BY obdm.pnum";
				$result = $conn->query($query);
				
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['pnum']."</td>";
					echo"<td>".$row['invpnum']."</td>";
					echo"<td>".$row['orderqty']."</td>";
					
					if($row['pnum']==$row['invpnum'] || $row['invpnum']==""){
						echo"<td>".$row['invoicedqty']."</td>";
					}else{
						echo"<td>".$row['invoicedqty1']."</td>";
					}
					
					echo"</tr>";
				
				}
			?>
		</div>
		
</body>
</html>
