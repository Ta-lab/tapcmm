<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['user']=="123" || $_SESSION['user']=="100")
	{
		$id=$_SESSION['user'];
		$activity="BRK REPORT";
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
		<h4 style="text-align:center"><label> BRAKES INDIA MATERIAL </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'OPENING')" value="Export to Excel">
	</div>
	
	<br><br>
	
	<script>
			function reload(form)
			{
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				self.location='brakesindiamaterial.php?f='+s0+'&tt='+s1;
			}
	</script>
	
	<!--<div class="divclass">
	<div class="find">
				<label>FROM DATE</label>
							<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['f']))
							{
								echo $_GET['f'];
							}
							else
							{
								echo date('Y-m-d',strtotime('-7 days'));
							}
							?>"/>
			</div>
			
			<div class="find1">
				<label>TO DATE</label>
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
			</div>-->

	<br><br><br>
	
	
	<div class="divclass">
			<?php
			
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-7 days'));
					$tt = date('Y-m-d');
				}
				else
				{
					$f = $_GET['f'];
					$tt= $_GET['tt'];
				}
				
				
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
			
			echo'<table id="testTable" align="center">
				<tr>
					<th>GRN</th>
					<th>RAW MATERIAL</th>
					<th>PARTNUMBER</th>
					<th>INVOICE PARTNUMBER</th>
					<th>RM QTY(KG)</th>
					<th>INVOICED QTY(NOS)</th>
					
					<th></th>
				</tr>';
								
				
				$query = "SELECT * FROM (SELECT rm,pnum,SUM(rmissqty) AS rmiss,inv FROM `d12` WHERE rm LIKE '%__BRAKES INDIA MATERIAL%' GROUP BY pnum) AS Td12
							LEFT JOIN(SELECT * FROM pn_st) AS Tpnst ON Td12.pnum=Tpnst.pnum
							LEFT JOIN(SELECT pn,SUM(qty) AS invqty FROM `inv_det` WHERE invdt>='2020-08-01' GROUP BY pn) AS INV ON Td12.pnum=INV.pn OR Tpnst.invpnum=INV.pn";
				
				$result = $conn->query($query);
				$rmtot=0;
				$invtot=0;
				while($row = mysqli_fetch_array($result))
				{
					$rmtot=$rmtot+$row['rmiss'];
					$invtot=$invtot+$row['invqty'];
					
					echo"<tr><td>".$row['inv']."</td>";
					echo"<td>".$row['rm']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['invpnum']."</td>";
					echo"<td>".$row['rmiss']."</td>";
					echo"<td>".$row['invqty']."</td>";
					
					echo"</tr>";
				}
				
				echo" <tr>
					<td colspan='4'><h4>TOTAL</h4></td>
					<td>".$rmtot."</td>
					<td>".$invtot."</td>";
				echo"</tr>";
				
				
				
			?>
		</div>
		
</body>
</html>
