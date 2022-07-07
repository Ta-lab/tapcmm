<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="PRODUCTION REPORT";
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
	<script src="js\excelreport.js"></script>
	<link rel="stylesheet" type="text/css" href="design1.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>PRODUCTION REPORT [ O11 ]</label></h4>
		<div>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable1','Table1')" value="Export to Excel">
	</div>
		<br/></br>
	
	<div class="divclass">
		<form method="GET">	
			</br>
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
							<script>
						function reload(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='o11tab.php?f='+s0+'&tt='+s1;
						}
					</script>
			</div>
			
			<div class="find1">
				<label>TILL DATE</label>
							<input type="date" id="tt" name="tt"  onchange="reload0(this.form)" value="<?php
							if(isset($_GET['tt']))
							{
								echo $_GET['tt'];
							}
							else
							{
								echo date('Y-m-d');
							}
							?>"/>
							<script>
						function reload0(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='o11tab.php?f='+s0+'&tt='+s1;
						}
					</script>
			</div>
			
			<br>
			</form>
			<br><br>
			<?php
				if(isset($_GET["f"]) && isset($_GET["tt"]))
				{
					$f = $_GET["f"];
					$tt = $_GET["tt"];
				}
				else
				{
					$f = date('Y-m-d',strtotime('-7 days'));
					$tt = date('Y-m-d');
				}
				echo'
							<table id="testTable1" align="center">
							  <tr>
								<th>DATE</th>
								<th>OPERATION</th>
								<th>RCNO</th>
								<th>PART NUMBER</th>
								<th>QUANTITY OK</th>
								<th>QTY REJECTED</th>
							  </tr>';
				$query1 = "SELECT date,operation,pnum,prcno,SUM(partreceived) as rec,SUM(qtyrejected) as rej FROM d12 WHERE date<='$tt' AND date >='$f' GROUP BY date,prcno";
				$result1 = $con->query($query1);
				while($r=mysqli_fetch_array($result1)){
						echo"<tr>
							<td>".$r['date']."</td>
							<td>".$r['operation']."</td>
							<td>".$r['prcno']."</td>
							<td>".$r['pnum']."</td>
							<td>".$r['rec']."</td>
							<td>".$r['rej']."</td>
						  </tr>";
							
						
				}
			?>
		</div>
		
</body>
</html>