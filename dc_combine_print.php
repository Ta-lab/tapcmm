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
		<h4 style="text-align:center"><label>DC PRINT</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<!--<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'EWAY BILL DC')" value="Export to Excel">
		</div>-->
		<br/></br>
		
		<div class="divclass">
		<form method="POST" action="dc_combine_print_db.php">	
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
								//$query = "SELECT distinct sccode FROM dcmaster where sccode="" OR ";
								$query = "SELECT distinct sccode FROM dcmaster where sccode='VSS U-2' OR sccode='CLE' OR sccode='BISCO' OR sccode='PRIMECH' OR sccode='N-IND' ";
										$result = $con->query($query);
										echo"<option value=''>Select one</option>";
										while ($row = mysqli_fetch_array($result)) 
										{
											if($_GET['sccode']==$row['sccode'])
												echo "<option selected value='".$row['sccode']."'>".$row['sccode']."</option>";
											else
												echo "<option value='".$row['sccode']."'>".$row['sccode']."</option>";
										}
						?>
						</datalist>
						
			<div class="column">
				
				<label>DC SNO&nbsp;&nbsp;&nbsp;</label>
				<input type="text" readonly name ="dc_sno" id="dc_sno" value="<?php
					$result1 = mysqli_query($con,"SELECT DISTINCT dc_sno from `dc_print` ORDER BY `dc_sno` DESC LIMIT 1");
					$row1 = mysqli_fetch_array($result1);
					echo $row1['dc_sno']+1;
				?>">
			
			
				<label>&nbsp;&nbsp;&nbsp;SUB CONTRACTOR</label>
							<input type="text" id="sccode" name="sccode" list="partlist" onchange="reload1(this.form)" value="<?php
							if(isset($_GET['sccode']))
							{
								echo $_GET['sccode'];
							}
							else
							{
								echo "";
							}
							?>"/>
							<script>
								function reload1(form)
								{
									var s0 = document.getElementById("sccode").value;
									self.location='dc_combine_print.php?sccode='+s0;
								}
							</script>
					
		
				<label><div style="color:green"><input type="Submit" name="submit" id="submit" value="PRINT"/></div></label>	
					
			</div>
				
			
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
				
				
			if(isset($_GET['sccode']) && $_GET['sccode']=="VSS U-2" || isset($_GET['sccode']) && $_GET['sccode']=="BISCO" || isset($_GET['sccode']) && $_GET['sccode']=="CLE" 
			|| isset($_GET['sccode']) && $_GET['sccode']=="PRIMECH" || isset($_GET['sccode']) && $_GET['sccode']=="N-IND"
			)
			{
					
				if(isset($_GET['sccode']) && $_GET['sccode']!="")
				{
					$sccode = $_GET['sccode'];
					$query = "SELECT * FROM dc_det where dcdate='$todaydate' AND scn='$sccode'";
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
				
				
					echo"<tr>
						<td colspan='19'><h4 style='color : orange;'>  </h4></td>
						<td><h4 style='color : orange;'> $final_total </h4></td><td colspan='3'></td>";
						
					echo"</tr>";
				}
			
			}else{
				
			}
				
				
				
			?>
		</div>
		<br>
		
</form>
</body>
</html>

<script>

$('input[type=checkbox]').change(function(e){
   if ($('input[type=checkbox]:checked').length > 10) {
        $(this).prop('checked', false)
        alert("allowed only 10");
   }
})

</script>
