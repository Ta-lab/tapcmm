<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="PRODUCT MIX ANALYSIS";
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
		<h4 style="text-align:center"><label>PRODUCT MIX ANALYSIS</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'PRODUCT MIX ANALYSIS')" value="Export to Excel">
	</div>
	<br><br>
	<br><br>
	<div class="divclass">
	
			<div class="find">
				<label>FROM DATE</label>
					<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
					if(isset($_GET['f']))
					{
						echo $_GET['f'];
					}
					else
					{
						echo date('Y-m-d',strtotime('-1 days'));
					}
					?>"/>
			</div>
			
			<div class="find1">
				<label>To DATE</label>
					<input type="date" id="tt" name="tt"  onchange="reload(this.form)" value="<?php
					if(isset($_GET['tt']))
					{
						echo $_GET['tt'];
					}
					else
					{
						echo date('Y-m-d');
					}
					?>"/>
			</div>
			
			<script>
			function reload(form)
			{
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				self.location='pma.php?f='+s0+'&tt='+s1;
			}
			</script>
		
			
			
			<br><br><br>
			<?php
				
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-1 days'));
					$tt = date('Y-m-d');
				}
				else
				{
					$f = $_GET['f'];
					$tt = $_GET['tt'];
				}
				
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>INVPNUM</th>
						<th>PART NUMBER</th>
						<th>INVOICED QTY</th>
						<th>RATE PER</th>
						<th>RMDESC</th>
						<th>BOM</th>
					  </tr>';
				//$query2 = "SELECT pn,IF(pnum IS NULL,pn,pnum) AS pnum,invpnum,invoicedqty,IF(per LIKE '%EACH%',rate,rate/per) AS rateper FROM (SELECT pn,SUM(qty) AS invoicedqty,rate,per FROM inv_det WHERE invdt>='2019-04-01' AND invdt<='2019-12-31' GROUP BY pn) AS invdet LEFT JOIN(SELECT * FROM pn_st WHERE stkpt!='FG For Invoicing') AS pnst ON invdet.pn=pnst.pnum OR invdet.pn=pnst.invpnum ";
				$query2 = "SELECT DISTINCT pn,pnum,invpnum,invoicedqty,rateper,m13pnum,rmdesc,useage FROM(SELECT pn,SUM(qty) AS invoicedqty,IF(per LIKE '%EACH%',rate,rate/per) AS rateper FROM inv_det WHERE invdt>='$f' AND invdt<='$tt' GROUP BY pn) AS invdet LEFT JOIN(SELECT * FROM pn_st) AS pnst ON invdet.pn=pnst.invpnum OR invdet.pn=pnst.pnum LEFT JOIN(SELECT pnum AS m13pnum,rmdesc,useage FROM m13) AS rmdes ON pnst.pnum=rmdes.m13pnum order by pn";
				$result2 = $conn->query($query2);
				while($row = mysqli_fetch_array($result2))
				{
					if($row['rmdesc']!="")
					{
						echo"<tr>";
						echo"<td>".$row['pn']."</td>";
						echo"<td>".$row['pnum']."</td>";
						echo"<td>".$row['invoicedqty']."</td>";
						echo"<td>".$row['rateper']."</td>";
						echo"<td>".$row['rmdesc']."</td>";
						echo"<td>".$row['useage']."</td>";
						echo"</tr>";
					}
				
				}
				
				
			?>
		</div>
		
</body>
</html>