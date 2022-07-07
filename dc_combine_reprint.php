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
		<h4 style="text-align:center"><label>DC REPRINT</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		
		<br/></br>
		
		<div class="divclass">
		<form method="POST" action="dc_combine_reprint_pdf.php">	
			</br>
			
			<datalist id="dclist" >
						<?php
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT DISTINCT dc_sno from `dc_print`";
										$result = $con->query($query);
										echo"<option value=''>Select one</option>";
										while ($row = mysqli_fetch_array($result)) 
										{
											if($_GET['dc_sno']==$row['dc_sno'])
												echo "<option selected value='".$row['dc_sno']."'>".$row['dc_sno']."</option>";
											else
												echo "<option value='".$row['dc_sno']."'>".$row['dc_sno']."</option>";
										}
						?>
			</datalist>			

			
			<div class="column">
				
				
			
			<label>&nbsp;&nbsp;&nbsp;DC SNO</label>
							<input type="text" id="dc_sno" name="dc_sno" list="dclist" onchange="reload1(this.form)" value="<?php
							if(isset($_GET['dc_sno']))
							{
								echo $_GET['dc_sno'];
							}
							else
							{
								echo "";
							}
							?>"/>
							<script>
								function reload1(form)
								{
									var s0 = document.getElementById("dc_sno").value;
									self.location='dc_combine_reprint.php?dc_sno='+s0;
								}
							</script>
					
		
			
					
		
				<label><div style="color:green"><input type="Submit" name="submit" id="submit" value="PRINT"/></div></label>	
					
			</div>
				
			
			<?php
				
				$todaydate = date('Y-m-d');
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
					echo'
									<table id="testTable" align="center">
									  <tr>
										<th>SELECT DC TO PRINT</th>
										<th>DC PRINT STATUS</th>
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
				
				
			if(isset($_GET['dc_sno']) && $_GET['dc_sno']!="")
			{
					$dc_sno = $_GET['dc_sno'];
					$query9 = "SELECT * FROM dc_print where dc_sno='$dc_sno' ";
					$result9 = $conn->query($query9);
					while($row9 = mysqli_fetch_array($result9))
					{
						$dcnumber = $row9['dcnum'];
						$query = "SELECT * FROM dc_det where dcnum='$dcnumber'";
						$result = $conn->query($query);
						$i=0;
						$final_total=0;
				
						while($row = $result->fetch_assoc())
						{
							$i=$i+1;
							
							$dcnum = $row['dcnum'];
							$pn = $row['pn'];
							$scn = $row['scn'];
							$query1 = "SELECT gst,hsnc FROM dcmaster where pn='$pn' AND sccode='$scn' ";
							$result1 = $conn->query($query1);
							$row1 = mysqli_fetch_array($result1);
							
							$query2 = "SELECT * FROM dc_print where dcnum='$dcnum' ";
							$result2 = $conn->query($query2);
							$row2 = mysqli_fetch_array($result2);
							
							
							echo"<tr>";
							if($row2['dcnum']!=""){
								echo"<td>PRINTED</td>";
							}else{
								echo"<td><input type='checkbox' name='dcnum[]' value='$dcnum'/></td>";
							}
							if($row2['dcnum']!=""){
								echo"<td>PRINTED</td>";
							}
							else{
								echo"<td>NOT PRINTED</td>";
							}
							
							
							
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
							
							$final_total = $final_total + $row['totalvalue'];
							
							echo"</tr>";
						}
					}
				
					echo"<tr>";
						echo"<td colspan='19'><h4 style='color : orange;'>  </h4></td>";
						echo"<td><h4 style='color : orange;'> $final_total </h4></td><td colspan='3'></td>";
							
					echo"</tr>";
			}
			else
			{
				
			}
				
			?>
		</div>
		<br>
</form>
</body>
</html>
