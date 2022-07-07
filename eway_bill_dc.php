<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		
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
		<h4 style="text-align:center"><label>EWAY BILL FOR DC DETAILS</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'EWAY BILL DC')" value="Export to Excel">
		</div>
		<br/></br>
		
		<div class="divclass">
		<form method="GET">	
			</br>
			<!--<div class="find">
				<label>FROM DATE</label>
							<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['f']))
							{
								//echo $_GET['f'];
							}
							else
							{
								//echo date('Y-m-d',strtotime('-1 days'));
							}
							?>"/>
							<script>
						function reload(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							var s2 = document.getElementById("pn").value;
							self.location='eway_bill_dc.php?f='+s0+'&tt='+s1+'&pn='+s2;
						}
					</script>
			</div>
			
			<div class="find1">
				<label>TILL DATE</label>
							<input type="date" id="tt" name="tt"  onchange="reload0(this.form)" value="<?php
							if(isset($_GET['tt']))
							{
								//echo $_GET['tt'];
							}
							else
							{
								//echo date('Y-m-d');
							}
							?>"/>
							<script>
						function reload0(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							var s2 = document.getElementById("pn").value;
							self.location='eway_bill_dc.php?f='+s0+'&tt='+s1+'&pn='+s2;
						}
					</script>
			</div>-->
			<datalist id="partlist" >
						<?php
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT distinct vehiclenumber FROM dc_det";
										$result = $con->query($query);
										echo"<option value=''>Select one</option>";
										while ($row = mysqli_fetch_array($result)) 
										{
											if($_GET['vehiclenumber']==$row['vehiclenumber'])
												echo "<option selected value='".$row['vehiclenumber']."'>".$row['vehiclenumber']."</option>";
											else
												echo "<option value='".$row['vehiclenumber']."'>".$row['vehiclenumber']."</option>";
										}
						?>
						</datalist>
			<div class="find">
				<label>VEHICLE NUMBER</label>
							<input type="text" id="vehiclenumber" name="vehiclenumber" list="partlist"  onchange="reload1(this.form)" value="<?php
							if(isset($_GET['vehiclenumber']))
							{
								echo $_GET['vehiclenumber'];
							}
							else
							{
								echo "";
							}
							?>"/>
							<script>
						function reload1(form)
						{
							var s0 = document.getElementById("vehiclenumber").value;
							self.location='eway_bill_dc.php?vehiclenumber='+s0;
						}
					</script>
			</div>
			<br>
			</form>
			<br>
			<?php
				/*if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-1 days'));
					$tt = date('Y-m-d');
				}
				else
				{
					$f = $_GET['f'];
					$tt = $_GET['tt'];
				}*/
				//$dt = "00-00-0000";
				$todaydate = date('Y-m-d');
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
					echo'
									<table id="testTable" align="center">
									  <tr>
										<th>DC NUMBER</th>
										<th>DC DATE</th>
										<th>SUBCONTRACTOR</th>
										<th>GST NUMBER</th>
										<th>PART NUMBER</th>
										<th>HSN CODE</th>
										<th>QUANTITY</th>
										<th>RATE</th>
										<th>PER</th>
										<th>UOM</th>
										<th>BASIC VALUE</th>
										<th>CGST %</th>
										<th>SGST %</th>
										<th>IGST %</th>
										<th>CGST AMOUNT</th>
										<th>SGST AMOUNT</th>
										<th>IGST AMOUNT</th>
										<th>TOTAL VALUE</th>
										<th>VEHICLE NUMBER</th>										
									  </tr>';
				if(isset($_GET['vehiclenumber']) && $_GET['vehiclenumber']!="")
				{
					$vehiclenumber = $_GET['vehiclenumber'];
					
					$query = "SELECT * FROM dc_det where dcdate='$todaydate' AND vehiclenumber='$vehiclenumber'";
				
				}
				else
				{
					$query = "SELECT * FROM dc_det where dcdate='$todaydate'";
				}
				
				$result = $conn->query($query);
				while($row = $result->fetch_assoc())
				{
						$pn = $row['pn'];
						$scn = $row['scn'];
						$query1 = "SELECT gst,hsnc FROM dcmaster where pn='$pn' AND sccode='$scn' ";
						$result1 = $conn->query($query1);
						$row1 = mysqli_fetch_array($result1);
						
						echo" <tr>";
						echo"<td>".$row['dcnum']."</td>";
						echo"<td>".$row['dcdate']."</td>";
						echo"<td>".$row['scn']."</td>";
						echo"<td>".$row1['gst']."</td>";
						echo"<td>".$row['pn']."</td>";
						echo"<td>".$row1['hsnc']."</td>";
						echo"<td>".$row['qty']."</td>";
						echo"<td>".($row['rate']*$row['value_percentage']/100)."</td>";
						echo"<td>".$row['per']."</td>";
						echo"<td>".$row['uom']."</td>";
						echo"<td>".$row['basicvalue']."</td>";
						echo"<td>".$row['cgst']."</td>";
						echo"<td>".$row['sgst']."</td>";
						echo"<td>".$row['igst']."</td>";
						echo"<td>".$row['cgstamount']."</td>";
						echo"<td>".$row['sgstamount']."</td>";
						echo"<td>".$row['igstamount']."</td>";
						echo"<td>".$row['totalvalue']."</td>";
						echo"<td>".$row['vehiclenumber']."</td>";
							
						
						echo"</tr>";
				}
			?>
		</div>
</body>
</html>
</html>