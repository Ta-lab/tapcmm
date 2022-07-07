<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['user']=="123" || $_SESSION['user']=="100")
	{
		$id=$_SESSION['user'];
		$activity="WM MP REPORT";
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
		<h4 style="text-align:center"><label> WEEKLY MATERIAL STOCK PROCESSING </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'WEEKLY MATERIAL PROCESSING')" value="Export to Excel">
	</div>
	
	<br><br>
	
	<script>
			function reload(form)
			{
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				self.location='weekly_mpreport.php?f='+s0+'&tt='+s1;
			}
	</script>
	
	<script>
		function reload1(form)
		{
			var s4 = document.getElementById("week").value;
			self.location='weekly_mpreport.php?week='+s4;
		}
	</script>
	
	
	<div class="divclass">
	<datalist id="weeklist" >
		<?php
			
			$servername = "localhost";
			$username = "root";
			$password = "Tamil";
			$conn = new mysqli($servername, $username, $password, "weekreport_db");
			
			
			$result1 = $conn->query("SELECT DISTINCT week FROM `mp_report_summary_category`");
			echo"<option value=''>Select one</option>";
			while ($row1 = mysqli_fetch_array($result1)) 
			{
				if(isset($_GET['week'])==$row1['week'])
					echo "<option selected value='".$row1['week']."'>".$row1['week']."</option>";
				else
					echo "<option value='".$row1['week']."'>".$row1['week']."</option>";
			}
		?>
	</datalist>
	<div class="find1">
		<label>SELECT WEEK</label>
		<input type="text" required style="width:50%; background-color:white;" onchange=reload1(this.form) id="week" name="week" list="weeklist" value="<?php if(isset($_GET['week'])){echo $_GET['week'];}?>">
	</div>
	

	<br><br><br>
	
	
	<div class="divclass">
			<?php
			
				/*if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-7 days'));
					$tt = date('Y-m-d');
				}
				else
				{
					$f = $_GET['f'];
					$tt= $_GET['tt'];
				}
				*/
				
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "weekreport_db");
				
				$d=$_GET['week'];
				$query1 = "SELECT DISTINCT date_ft FROM `mp_report` where week='$d'";
				$result1 = $conn->query($query1);
				$row1 = mysqli_fetch_array($result1);
				
				
			echo'<table id="testTable" align="center">
				<tr>
					<td colspan="15"><h4>WEEKLY/MONTHLY MATERIAL PROCESSING REPORT FROM '.$row1['date_ft'].' </h4>
					<td colspan="4"><h4> EFFICIENCY PARAMETERS </h4></td>
				</tr>
					
				<tr>
					<th>WEEK</th>
					<th>OPERATION</th>
					<th>PAINTSHOP/SUBCONTRACT</th>
					<th>RCNO</th>
					<th>PARTNUMBER</th>
					<th>FOREMAN</th>
					<th>CATEGORY</th>
					
					<th>OPENING STOCK(KG)</th>
					<th>RECEIVED QTY(KG)</th>
					<th>OK QUANTITY(KG)</th>
					<th>SCRAP QUANTITY(KG)</th>
					<th>RETURN QUANTITY(KG)</th>
					<th>CLOSING STOCK(KG)</th>
					<th>SHOULD BE CLOSING(KG)</th>
					<th>DIFFERENCE(KG)</th>
					
					<th>PROCESSED QTY / NET RECEIVED QTY %</th>
					<th>SCRAP / OK QTY %</th>
					<th>CLOSING STOCK / OK QTY %</th>
					<th>DIFFERENCE / OK QTY %</th>
					
					
					<th></th>
				</tr>';
								
				if(isset($_GET['week']) && $_GET['week']!="")
				{
					$d=$_GET['week'];
				}
				else
				{
					
				}
				
				$query = "SELECT * FROM `mp_report` where week='$d'";
				
				$result = $conn->query($query);
				while($row = mysqli_fetch_array($result))
				{
					
					echo"<tr><td>".$row['week']."</td>";
					echo"<td>".$row['operation']."</td>";
					echo"<td>".$row['scname']."</td>";
					echo"<td>".$row['rcno']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['foreman']."</td>";
					echo"<td>".$row['category']."</td>";
					
					echo"<td>".$row['openingstk']."</td>";
					echo"<td>".$row['receivedqty']."</td>";
					echo"<td>".$row['okqty']."</td>";
					echo"<td>".$row['scrapqty']."</td>";
					echo"<td>".$row['returnqty']."</td>";
					echo"<td>".$row['closingstk']."</td>";
					echo"<td>".$row['shouldbeclosing']."</td>";
					echo"<td>".$row['difference']."</td>";
					
					echo"<td>".$row['proqty_netrec_per']."</td>";
					echo"<td>".$row['scrap_ok_per']."</td>";
					echo"<td>".$row['closing_ok_per']."</td>";
					echo"<td>".$row['diff_ok_per']."</td>";
					
					
					echo"</tr>";
				}
				
				
				
			?>
		</div>
		
</body>
</html>
