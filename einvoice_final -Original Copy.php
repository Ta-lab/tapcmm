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
		<h4 style="text-align:center"><label>E - INVOICE (GOVT PORTAL FORMAT)</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'EINVOICE')" value="Export to Excel">
		</div>
		<br/></br>
		
		<div class="divclass">
		<form method="POST" action="">	
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
								$query = "SELECT distinct sccode FROM dcmaster";
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
						
			<!--<div class="column">
				
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
					
			</div>-->
				
			
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
									<th>SUPPLY TYPE CODE</th>
									<th>REVERSE CHARGE</th>
									<th>e-Comm GSTIN</th>
									<th>Igst On Intra</th>
									<th>DOCUMENT TYPE</th>
									<th>DOCUMENT NUMBER</th>
									<th>DOCUMENT DATE</th>
									<th>BUYER GSTIN</th>
									<th>BUYER LEGAL NAME</th>
									<th>BUYER TRADE NAME</th>
									<th>BUYER PLACE OF SUPPLY</th>
									<th>BUYER ADDRESS 1</th>
									<th>BUYER ADDRESS 2</th>
									
									<th>BUYER LOCATION</th>
									<th>BUYER PIN CODE</th>
									<th>BUYER STATE</th>
									<th>BUYER PHONE NUMBER</th>
									<th>BUYER Email ID</th>
									<th>DISPATCH NAME</th>
									<th>DISPATCH ADDRESS 1</th>
									<th>DISPATCH ADDRESS 2</th>
									<th>DISPATCH LOCATION</th>
									<th>DISPATCH PIN CODE</th>
									<th>DISPATCH STATE</th>
									<th>SHIPPING GSTIN</th>
									<th>SHIPPING LEGAL NAME</th>
									<th>SHIPPING TRADE NAME</th>
									<th>SHIPPING ADDRESS 1</th>
									<th>SHIPPING ADDRESS 2</th>
									
									<th>SHIPPING LOCATION</th>
									<th>SHIPPING PINCODE</th>
									<th>SHIPPING STATE</th>
									<th>Sl.No</th>
									<th>PRODUCT DESCRIPTION</th>
									<th>IS SERVICE</th>
									<th>HSN CODE</th>
									<th>BAR CODE</th>
									<th>QUANTITY</th>
									<th>FREE QUANTITY</th>
									<th>UNIT</th>
									<th>UNIT PRICE</th>
									<th>GROSS AMOUNT</th>
									<th>DISCOUNT</th>
									<th>PRE TAX VALUE</th>
									<th>TAXABLE VALUE</th>
									<th>GST (%)</th>
									<th>SGST AMOUNT</th>
									<th>CGST AMOUNT</th>
									<th>IGST AMOUNT</th>
									<th>CESS RATE (%)</th>
									<th>CESS AMOUNT ADVAL</th>
									<th>CESS NON ADVAL AMOUNT</th>
									<th>STATE CESS RATE (%)</th>
									<th>STATE CESS ADVAL AMOUNT</th>
									<th>STATE CESS NON-ADVAL AMOUNT</th>
									<th>OTHER CHARGES</th>
									<th>ITEM TOTAL</th>
									<th>BATCH NAME</th>
									<th>BATCH EXPIRY DATE</th>
									<th>WARRANTY DATE</th>
									<th>TOTAL TAXABLE VALUE</th>
									<th>SGST AMOUNT</th>
									<th>CGST AMOUNT</th>
									<th>IGST AMOUNT</th>
									<th>CESS AMOUNT</th>
									<th>STATE CESS AMOUNT</th>
									<th>DISCOUNT</th>
									<th>OTHER CHARGES</th>
									<th>ROUND OFF</th>
									<th>TOTAL INVOICE VALUE</th>
									<th>SHIPPING BILL NO</th>
									<th>SHIPPING BILL DATE</th>
									<th>PORT</th>
									<th>REFUND CLAIM</th>
									<th>FOREIGN CURRENCY</th>
									<th>COUNTRY CODE</th>
									<th>EXPORT DUTY AMOUNT</th>
									<th>TRANSPORT ID</th>
									<th>TRANSPORT NAME</th>
									<th>TRANSPORT MODE</th>
									<th>DISTANCE</th>
									<th>TRANSPORT DOC NUMBER</th>
									<th>TRANSPORT DOC DATE</th>
									<th>VEHICLE NUMBER</th>
									<th>VEHICLE TYPE</th>
								</tr>';
					
					
					$query = "SELECT * FROM `inv_det` LEFT JOIN (SELECT invpnum,IF(SUM(useage) IS NULL,0,SUM(useage)) as bom FROM (SELECT invpnum,SUM(useage) as useage FROM `pn_st` LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt='FG For Invoicing' GROUP BY pn_st.invpnum,n_iter) AS T GROUP BY invpnum) AS BOM ON inv_det.pn=BOM.invpnum WHERE invdt='$todaydate'";
					$result = $conn->query($query);
					
					while($row = $result->fetch_assoc())
					{
						
						$ccode = $row['ccode'];
						$pn = $row['pn'];
						$query2 = "SELECT * FROM invmaster where ccode='$ccode' AND pn='$pn'";
						$result2 = $conn->query($query2);
						$row2 = mysqli_fetch_array($result2);
						
						$city = $row2['city'];
						$state = $row2['state'];
						$pincode = $row2['pincode'];
						
						$packing_percentage = $row['pc'];
						
						
						$d = date("d/m/Y", strtotime($row['invdt']));
						$sno = 1;
						
						echo"<td>B2B</td>";
						echo"<td>No</td>";
						//echo"<td>No</td>";
						echo"<td></td>";
						
						echo"<td>No</td>";
						/*if($row['cori']=="IGST"){
							echo"<td>Yes</td>";
						}else{
							echo"<td>No</td>";
						}*/
						
						echo"<td>Tax Invoice</td>";
						echo"<td>".$row['invno']."</td>";
						echo"<td>".$d."</td>";
						
						echo"<td>".trim($row['cgstno'],'GSTIN :')."</td>";
						
						echo"<td>".$row['cname'].$row['cname1']."</td>";
						echo"<td>".$row['cname'].$row['cname1']."</td>";
						echo"<td>".$state."</td>";
						echo"<td>".$row['cadd1']."</td>";
						echo"<td>".$row['cadd2']." ".$row['cadd3']."</td>";
						//echo"<td>".$row['cadd3']."</td>";
						echo"<td>".$city."</td>";
						echo"<td>".$pincode."</td>";
						echo"<td>".$state."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".trim($row['cgstno'],'GSTIN :')."</td>";
						echo"<td>".$row['dtname']."</td>";
						echo"<td>".$row['dtname']."</td>";
						echo"<td>".$row['dtadd1']."</td>";
						echo"<td>".$row['dtadd2']." ".$row['dtadd3']."</td>";
						//echo"<td>".$row['dtadd3']."</td>";
						
						echo"<td>".$city."</td>";
						echo"<td>".$pincode."</td>";
						echo"<td>".$state."</td>";
						echo"<td>".$sno."</td>";
						echo"<td>".$row['pn']." ".$row['pd']."</td>";
						echo"<td>".''."</td>";
						echo"<td>".$row['hsnc']."</td>";
						echo"<td>".''."</td>";
						
						if($row['per']=="EACH"){
							$per = 1;
						}
						else{
							$per = $row['per'];
						}
						
						$rate_per = $row['rate']/$per;
						
						echo"<td>".$row['qty']."</td>";
						echo"<td>".''."</td>";
						//echo"<td>".$row['uom']."</td>";
						echo"<td>NUMBERS</td>";
						echo"<td>".$rate_per."</td>";
						
						$gross_value = $row['qty'] * $rate_per ;
						
						echo"<td>".$gross_value."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						
						echo"<td>".$gross_value."</td>";
						
						if($row['cori']=="CGST"){
							$cgst = trim($row['cigst'],' %');
							$sgst = trim($row['sgst'],' %');
							
							$gst = $cgst + $sgst;
							//$gst =  trim($row['sgst'],'%');
							echo"<td>".$gst."</td>";
						}else{
							echo"<td>".trim($row['cigst'],' %')."</td>";
						}
						
						//echo"<td>".$row['sgst']."</td>";
						
						if($row['cori']=="IGST"){
							$cgstamt = 0;
							$sgstamt = 0;
							echo"<td>".$cgstamt."</td>";
							echo"<td>".$sgstamt."</td>";
							//echo"<td>".$row['cigstamt']."</td>";
							$igstamt = number_format((float)($gross_value*$gst/100),2,'.','');
							echo"<td>".$igstamt."</td>";
						}
						else{
							$igstamt = 0;
							$sgstamount = number_format((float)($gross_value*$gst/100/2),2,'.','');
							$cgstamount = number_format((float)($gross_value*$gst/100/2),2,'.','');
							echo"<td>".$sgstamount."</td>";
							echo"<td>".$cgstamount."</td>";
							
							//echo"<td>".$row['sgstamt']."</td>";
							//echo"<td>".$row['cigstamt']."</td>";
							echo"<td>".$igstamt."</td>";
						}
						//echo"<td>".$row['cigstamt']."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						
						echo"<td>".''."</td>";
						
						//other charges
						//echo"<td>".$row['pcamt']."</td>";
						/*if($row['pcamt']==""){
							$othercharges = 0;
						}else{
							$othercharges = $row['pcamt'];
						}	
						*/
						
						//$itemtotal = $row['invtotal'] - $othercharges;
						$itemtotal = $row['invtotal'];
						//$itemtotal = $gross_value+$sgstamount+$cgstamount+$igstamt;
						echo"<td>".$itemtotal."</td>";
						
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						
						echo"<td>".$gross_value."</td>";						
						
						if($row['cori']=="IGST"){
							$cgstamt = 0;
							$sgstamt = 0;
							echo"<td>".$cgstamt."</td>";
							echo"<td>".$sgstamt."</td>";
							
							$igstamt = number_format((float)($gross_value*$gst/100),2,'.','');
							echo"<td>".$igstamt."</td>";
							
							//echo"<td>".$row['cigstamt']."</td>";
							//echo"<td>".number_format((float)($gross_value*$gst/100),2,'.','')."</td>";
							//$vegt=number_format((float)(($six*$egt)/100),2, '.', '');
						}
						else{
							$igstamt = 0;
							//echo"<td>".$row['sgstamt']."</td>";
							//echo"<td>".$row['cigstamt']."</td>";
							
							$sgstamount = number_format((float)($gross_value*$gst/100/2),2,'.','');
							$cgstamount = number_format((float)($gross_value*$gst/100/2),2,'.','');
							echo"<td>".$sgstamount."</td>";
							echo"<td>".$cgstamount."</td>";
							
							echo"<td>".$igstamt."</td>";
						}
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						//echo"<td>".''."</td>";
						//echo"<td>".$row['pcamt']."</td>";
						
						
						if($row['pcamt'] == ""){
							$othercharges = 0;
							//echo"<td>".$othercharges."</td>";
						}else{
							$othercharges = ((($gross_value+$cgstamount+$sgstamount+$igstamt)) * ($packing_percentage/100));
							//echo"<td>".$othercharges."</td>";
						}
						
						echo"<td>".number_format((float)($othercharges),2,'.','')."</td>";
						
						//echo"<td>".$othercharges."</td>";
						
						echo"<td>".''."</td>";
						
						//echo"<td>".$row['invtotal']."</td>";
						//$invtotal = $gross_value+$cgstamount+$sgstamount+$othercharges;
						$invtotal = $row['invtotal'];
						echo"<td>".$invtotal."</td>";
						
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
						echo"<td>".''."</td>";
							
	
						
						
						echo"</tr>";
						
							
						/*CESS AMOUNT DISABLED
							cessrate = 1;
							cess amount = taxable_amount * cessrate;
							state_cess_rate = 1;
							state cess amount = taxable amount * state_cess_rate;
						*/
						
						
						
						
						
					}	
					
					
					
				
			?>
		</div>
		<br>
		
</form>
</body>
</html>
</html>