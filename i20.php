<?php
session_start();
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	header("Location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="INVOICE PART UPDATION";
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
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<script src = "js\excelreport.js"></script>
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
</head>
<body>
<style>
.column
{
    float: left;
    width: 33%;
}
.column1
{
    float: left;
    width: 33%;
	display: none;
}
</style>
	<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label>INVOICE MASTER PART UPDATION [ I20 ]</label></h4>
	<div>
			
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
		<script>
			function reload(form)
			{
				var p2 = document.getElementById("p2").value;
				self.location=<?php echo"'i20.php?partnumber='"?>+p2;
			}
			function reload1(form)
			{
				var p2 = document.getElementById("p2").value;
				var p4 = document.getElementById("p4").value;
				self.location=<?php echo"'i20.php?partnumber='"?>+p2+'&code='+p4;
			}
		</script>
	<div class="divclass">
		<form id="form" name="form" method="post" action="i20db.php">
			</br>
			<div class="find">
				<datalist id="partlist" >
						<?php
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT distinct pn FROM invmaster";
										$result = $con->query($query);
										echo"<option value=''>Select one</option>";
										while ($row = mysqli_fetch_array($result)) 
										{
											if($_GET['pn']==$row['pn'])
												echo "<option selected value='".$row['pn']."'>".$row['pn']."</option>";
											else
												echo "<option value='".$row['pn']."'>".$row['pn']."</option>";
										}
						?>
						</datalist>
					<label>PART NUMBER</label>
					<input type="text" style="width: 60%; background-color:white;" class='s' onchange=reload(this.form) id="p2" name="partnumber" list="partlist" value="<?php if(isset($_GET['partnumber'])){echo $_GET['partnumber'];}?>">
			</div>
			<datalist id="codelist" >
					<?php
						if(isset($_GET['partnumber'])) 
						{	
							$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
							$t=$_GET['partnumber'];
							$result1 = $con->query("select distinct ccode from invmaster where pn='$t'");
							echo"<option value=''>Select one</option>";
							while ($row1 = mysqli_fetch_array($result1)) 
							{
								if(isset($_GET['ccode'])==$row1['ccode'])
									echo "<option selected value='".$row1['ccode']."'>".$row1['ccode']."</option>";
								else
									echo "<option value='".$row1['ccode']."'>".$row1['ccode']."</option>";
							}
						}
					?>
				</datalist>
			<div class="find1">
				<label>CUSTOMER CODE</label>
				<input type="text" style="width:50%; background-color:white;" class='s' onchange=reload1(this.form) id="p4" name="cc" list="codelist" value=<?php if(isset($_GET['code'])){echo $_GET['code'];}?>>
			</div>
			<br>
			<br>
			<?php
				$con = mysqli_connect('localhost','root','Tamil','mypcm');
				if(!$con)
					echo "connection failed";
				if(isset($_GET['partnumber']) && isset($_GET['code']) && $_GET['partnumber']!='' && $_GET['code']!='')
					{
						$t1=$_GET['partnumber'];
						$t2=$_GET['code'];
						$result1 = $con->query("select * from invmaster where pn='$t1' and ccode='$t2'");
						if($result1->num_rows==0)
						{
							echo '<script language="javascript">';
							echo 'alert("Master Not Found")';
							echo '</script>';
						}
						else if($result1->num_rows>2)
						{
							echo '<script language="javascript">';
							echo 'alert("More than one rows are there in MASTER")';
							echo '</script>';
						}
						else
						{
							$row = mysqli_fetch_array($result1);
							echo '<h4 style="color:white;text-align:center"><label>MASTER DATA FOR THE ABOVE CUSTOMER INFO</label></h4>
							<div id="stylized" class="myform">
								<div class="column">
									<label>Customer Name: </label>
									<input type="text" name="cname" readonly id="cname" value="'.$row['cname'].'"/>
								</div>
								<div class="column">
									<label>Delivery Name: </label>
									<input type="text" name="dtname" readonly	 id="dtname" value="'.$row['dtname'].'"/>
								</div>
								<div class="column">
									<label>Part Number&nbsp;&nbsp;: </label>
									<input type="text" name="pn"  readonly id="pn" value="'.$row['pn'].'"/>
								</div>
								<div class="column">
									<label>Cust Address1: </label>
									<input type="text" name="cadd1" id="cadd1"  readonly value="'.$row['cadd1'].'"/>
								</div>
								<div class="column">
									<label>Deli Address1: </label>
									<input type="text" name="dtadd1" id="dtadd1"  readonly value="'.$row['dtadd1'].'"/>
								</div>
								<div class="column">
									<label>Part Descrip&nbsp;: </label>
									<input type="text" name="pd" id="pd" value="'.$row['pd'].'"/>
								</div>
								<div class="column">
									<label>Cust Address2: </label>
									<input type="text" name="cadd2" id="cadd2" readonly value="'.$row['cadd2'].'"/>
								</div>
								<div class="column">
									<label>Deli Address2: </label>
									<input type="text" name="dtadd2" id="dtadd2" readonly value="'.$row['dtadd2'].'"/>
								</div>
								<div class="column">
									<label>Vendor Code&nbsp;&nbsp;: </label>
									<input type="text" name="vc" id="vc"  readonly value="'.$row['vc'].'"/>
								</div>
								<div class="column">
									<label>Cust Address3: </label>
									<input type="text" name="cadd3" id="cadd3" readonly value="'.$row['cadd3'].'"/>
								</div>
								<div class="column">
									<label>Deli Address3: </label>
									<input type="text" name="dtadd3" id="dtadd3" readonly value="'.$row['dtadd3'].'"/>
								</div>
								<div class="column">
									<label>HSN Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="hsnc" id="hsnc" value="'.$row['hsnc'].'"/>
								</div>
								<div class="column">
									<label>Cust GSTno&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="cgstno" id="cgstno" readonly value="'.$row['cgstno'].'"/>
								</div>
								<div class="column">
									<label>Deli GSTno&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="dtgstno" id="dtgstno" readonly value="'.$row['dtgstno'].'"/>
								</div>
								<div class="column">
									<label>Cust PO No&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="cpono" id="cpono" value="'.$row['cpono'].'"/>
								</div>
								<div class="column">
									<label>Cust PO Date&nbsp;: </label>
									<input type="text" name="cpodt" id="cpodt" value="'.$row['cpodt'].'"/>
								</div>	
								<div class="column">
									<label>PO ITEM No&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="poino" id="poino" value="'.$row['poino'].'"/>
								</div>
								<div class="column">
									<label>Part Rate&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="rate" id="rate" value="'.$row['rate'].'"/>
								</div>
								<div class="column">
									<label>Rate Per Qty&nbsp;: </label>
									<input type="text" name="per" id="per" value="'.$row['per'].'"/>
								</div>
								<div class="column">
									<label>UOM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="uom" id="uom" value="'.$row['uom'].'"/>
								</div>
								<div class="column">
									<label>Packing Chrge: </label>
									<input type="text" name="pc" id="pc" value="'.$row['pc'].'"/>
								</div>
								<div class="column">
									<label>CGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="cgst" id="cgst" value="'.$row['cgst'].'"/>
								</div>
								<div class="column">
									<label>SGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="sgst" id="sgst" value="'.$row['sgst'].'"/>
								</div>
								<div class="column">
									<label>IGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="igst" id="igst" value="'.$row['igst'].'"/>
								</div>
								<div class="column">
									<label>E-WAY NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" readonly="readonly" name="ebn" id="ebn"/>
								</div>
								<div class="column">
									<label>DESPATCH MODE: </label>
									<input type="text" name="dm" id="dm" value="'.$row['despatch'].'"/>
								</div>
								<div class="column">
									<label>CCODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="ccode" id="ccode" readonly value="'.$row['ccode'].'"/>
								</div>
								<div class="column">
									<label>REMARKLINE1&nbsp;&nbsp;: </label>
									<input type="text" name="r1" id="r1" value="'.$row['remark1'].'"/>
								</div>
								<div class="column">
									<label>REMARKLINE2&nbsp;&nbsp;: </label>
									<input type="text" name="r2" id="r2" value="'.$row['remark2'].'"/>
								</div>
								<div class="column">
									<label>REMARKLINE3&nbsp;&nbsp;: </label>
									<input type="text" name="r3" id="r3" value="'.$row['remark3'].'"/>
								</div>
								<div class="column">
									<label>REMARKLINE4&nbsp;&nbsp;: </label>
									<input type="text" name="r4" id="r4" value="'.$row['remark4'].'"/>
								</div>
								<div class="column">
									<label>REMARKLINE5&nbsp;&nbsp;: </label>
									<input type="text" name="r5" id="r5" value="'.$row['remark5'].'"/>
								</div>
								<div class="column">
									<label>MODE OF TRANS: </label>
									<input type="text" name="tm" id="tm" readonly value="'.$row['transmode'].'"/>
								</div>
								<div class="column">
									<label>DISTANCE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="dis" id="dis" readonly value="'.$row['distance'].'"/>
								</div>
								<div>
									<input style="left:15%;" type="submit" name="submit" value="UPDATE">
								</div>
								<div class="column1">
								</form>
							</div>';
						}
					}
			?>	
	</body>
</html>
		