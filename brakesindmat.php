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
				self.location='opclunion3.php?f='+s0+'&tt='+s1;
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
					<th>DATE</th>
					<th>RM</th>
					<th>RM QTY</th>
					<th>PARTNUMBER</th>
					<th>RCNO</th>
					<th>GRN</th>
					<th>HEAT</th>
					<th>LOT</th>
					<th>COIL</th>
					
					<th>DATE</th>
					<th>STKPT</th>
					<th>PARTNUMBER</th>
					<th>RCNO</th>
					<th>PREVIOUS RCNO</th>
					<th>PART ISSUED</th>
					
					<th>DATE</th>
					<th>STKPT</th>
					<th>PARTNUMBER</th>
					<th>RCNO</th>
					<th>PREVIOUS RCNO</th>
					<th>PART ISSUED</th>
					
					<th>DATE</th>
					<th>OPERATION</th>
					<th>WORKCENTRE</th>
					<th>PARTNUMBER</th>
					<th>PREVIOUS RCNO</th>
					<th>PART REJECTED</th>
					
					<th>INVOICE NO</th>
					<th>INVOICE DATE</th>
					<th>INVOICE QTY(NOS)</th>
					
					<th></th>
				</tr>';
				
								
				
				/*$query = "SELECT DISTINCT * FROM(SELECT date,rm,rmissqty,pnum,rcno,inv,heat,lot,coil FROM d12 WHERE rm LIKE '%BRAKES INDIA MATERIAL%') AS Td12 
						LEFT JOIN (SELECT date,stkpt,operation,workcentre,pnum,rcno,prcno,partissued,qtyrejected,partreceived,rsn FROM d12 WHERE rcno!='') AS Td12_1 ON Td12.rcno=Td12_1.prcno LEFT JOIN (SELECT date,stkpt,operation,workcentre,pnum,rcno,prcno,partissued,qtyrejected,partreceived,rsn FROM d12 WHERE rcno!='') AS Td12_2 ON Td12_1.rcno=Td12_2.prcno 
						LEFT JOIN(SELECT invno,invdt,qty FROM `inv_det`) AS Tinv ON Tinv.invno=Td12_2.rcno ORDER BY Td12.rcno,Td12.date";
				*/
				
				//working query
				/*$query = "SELECT DISTINCT * FROM(SELECT date AS Adate,rm,rmissqty,pnum AS Apnum,rcno AS Arcno,inv,heat,lot,coil FROM d12 WHERE rm LIKE '%BRAKES INDIA MATERIAL%') AS Td12 
						LEFT JOIN (SELECT date AS Bdate,stkpt AS Bstkpt,pnum AS Bpnum,rcno AS Brcno,prcno AS Bprcno,partissued AS Bpartissued,qtyrejected AS Bqtyrejected,partreceived AS Bpartreceived FROM d12 WHERE rcno!='') AS Td12_1 ON Td12.Arcno=Td12_1.Bprcno
						LEFT JOIN (SELECT date AS Cdate,stkpt AS Cstkpt,pnum AS Cpnum,rcno AS Crcno,prcno AS Cprcno,partissued AS Cpartissued,qtyrejected AS Cqtyrejected,partreceived AS Cpartreceived FROM d12 WHERE rcno!='') AS Td12_2 ON Td12_1.Brcno=Td12_2.Cprcno
						LEFT JOIN(SELECT invno,invdt,qty FROM `inv_det`) AS Tinv ON Tinv.invno=Td12_2.Crcno 
						ORDER BY Td12.Arcno,Td12_1.Bprcno,Td12_2.Cprcno";
				*/
				
				$query="SELECT DISTINCT * FROM(SELECT date AS Adate,rm,rmissqty,pnum AS Apnum,rcno AS Arcno,inv,heat,lot,coil FROM d12 WHERE rm LIKE '%BRAKES INDIA MATERIAL%' and date>='2022-03-01') AS Td12 
				LEFT JOIN (SELECT date AS Bdate,stkpt AS Bstkpt,pnum AS Bpnum,rcno AS Brcno,prcno AS Bprcno,partissued AS Bpartissued,qtyrejected AS Bqtyrejected,partreceived AS Bpartreceived FROM d12 WHERE rcno!='') AS Td12_1 ON Td12.Arcno=Td12_1.Bprcno
				LEFT JOIN (SELECT date AS Cdate,stkpt AS Cstkpt,pnum AS Cpnum,rcno AS Crcno,prcno AS Cprcno,partissued AS Cpartissued,qtyrejected AS Cqtyrejected,partreceived AS Cpartreceived FROM d12 WHERE rcno!='') AS Td12_2 ON Td12_1.Brcno=Td12_2.Cprcno
				LEFT JOIN (SELECT date AS Ddate,operation AS Doperation,workcentre AS Dworkcentre,pnum AS Dpnum,prcno AS Dprcno,qtyrejected AS Dqtyrejected FROM d12 WHERE qtyrejected!='') AS Td12_3 ON Td12_3.Dprcno=Td12.Arcno OR Td12_3.Dprcno=Td12_1.Brcno

				LEFT JOIN(SELECT invno,invdt,qty FROM `inv_det`) AS Tinv ON Tinv.invno=Td12_2.Crcno

				ORDER BY Td12.Arcno,Td12_1.Bprcno,Td12_2.Cprcno,Tinv.invno";
				
				$result = $conn->query($query);
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['Adate']."</td>";
					echo"<td>".$row['rm']."</td>";
					echo"<td>".$row['rmissqty']."</td>";
					echo"<td>".$row['Apnum']."</td>";
					echo"<td>".$row['Arcno']."</td>";
					echo"<td>".$row['inv']."</td>";
					echo"<td>".$row['heat']."</td>";
					echo"<td>".$row['lot']."</td>";
					echo"<td>".$row['coil']."</td>";
					
					echo"<td>".$row['Bdate']."</td>";
					echo"<td>".$row['Bstkpt']."</td>";
					echo"<td>".$row['Bpnum']."</td>";
					echo"<td>".$row['Brcno']."</td>";
					echo"<td>".$row['Bprcno']."</td>";
					echo"<td>".$row['Bpartissued']."</td>";
					
					echo"<td>".$row['Cdate']."</td>";
					echo"<td>".$row['Cstkpt']."</td>";
					echo"<td>".$row['Cpnum']."</td>";
					echo"<td>".$row['Crcno']."</td>";
					echo"<td>".$row['Cprcno']."</td>";
					echo"<td>".$row['Cpartissued']."</td>";
					
					echo"<td>".$row['Ddate']."</td>";
					echo"<td>".$row['Doperation']."</td>";
					echo"<td>".$row['Dworkcentre']."</td>";
					echo"<td>".$row['Dpnum']."</td>";
					echo"<td>".$row['Dprcno']."</td>";
					echo"<td>".$row['Dqtyrejected']."</td>";
					
					echo"<td>".$row['invno']."</td>";
					echo"<td>".$row['invdt']."</td>";
					echo"<td>".$row['qty']."</td>";
					
					echo"</tr>";
				}
				
				/*echo" <tr>
					<td colspan='4'><h4>TOTAL</h4></td>
					<td>".$rmtot."</td>
					<td>".$invtot."</td>";
				echo"</tr>";
				*/
				
			?>
		</div>
		
</body>
</html>
