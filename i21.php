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
		$activity="INVOICE CUSTOMER UPDATION";
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
	<h4 style="text-align:center"><label> NEW INVOICE MASTER INSERTION [ I20 ]</label></h4>
	<div>
			<script>
			function reload(form)
			{
				var p4 = document.getElementById("p4").value;
				self.location=<?php echo"'i21.php?code='"?>+p4;
			}
		</script>
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
	<div class="divclass">
		<form method="POST" action='i141.php'>	
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
					<datalist id="codelist" >
					<?php
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
						$t=$_GET['partnumber'];
						$result1 = $con->query("select distinct ccode from invmaster");
						echo"<option value=''>Select one</option>";
						while ($row1 = mysqli_fetch_array($result1)) 
						{
							if(isset($_GET['ccode'])==$row1['ccode'])
								echo "<option selected value='".$row1['ccode']."'>".$row1['ccode']."</option>";
							else
								echo "<option value='".$row1['ccode']."'>".$row1['ccode']."</option>";
						}
					?>
				</datalist>
				<br>
			<div class="find">
				<label>CUSTOMER CODE</label>
				<input type="text" required style="width:50%; background-color:white;" class='s' onchange=reload(this.form) id="p4" name="custpo" list="codelist" value="<?php if(isset($_GET['code'])){echo $_GET['code'];}?>">
			</div>
			<br><br><br>
			<?php
				$cname="";$cadd1="";$cadd2="";$cadd3="";$cgstno="";$dtname="";$dtadd1="";$dtadd2="";$dtadd3="";$dtgstno="";$vc="";$ccode="";$s="";$dist="";$mot="";
				if(isset($_GET['code']) && $_GET['code']!='')
				{
					$s="";
					$t=$_GET['code'];
					$result1 = $con->query("select distinct cname,cadd1,cadd2,cadd3,cgstno,dtname,dtadd1,dtadd2,dtadd3,dtgstno,vc,ccode,transmode,distance from invmaster where ccode='$t' LIMIT 1");
					$row1 = mysqli_fetch_array($result1);
					if($result1->num_rows==1)
					{
						$cname=$row1['cname'];$cadd1=$row1['cadd1'];$cadd2=$row1['cadd2'];$cadd3=$row1['cadd3'];
						$cgstno=$row1['cgstno'];$dtname=$row1['dtname'];$dtadd1=$row1['dtadd1'];$dtadd2=$row1['dtadd2'];
						$dtadd3=$row1['dtadd3'];$dtgstno=$row1['dtgstno'];$vc=$row1['vc'];$s="readonly";$ccode=$row1['ccode'];
						$mot=$row1['transmode'];$dist=$row1['distance'];
					}
				}
			?>
			</form>
				<div id="stylized" class="myform">
				<form id="form" name="form" method="post" action="i20db.php">
					<div class="column">
						<label>Customer Name: </label>
						<input type="text" required name="cname" <?php echo $s; ?> id="cname" value="<?php echo $cname; ?>"/>
					</div>
					<div class="column">
						<label>Delivery Name: </label>
						<input type="text" required name="dtname" id="dtname" <?php echo $s; ?> value="<?php echo $dtname; ?>"/>
					</div>
					<div class="column">
						<label>Part Number&nbsp;&nbsp;: </label>
						<input type="text" required name="pn" list="partlist" id="pn"/>
					</div>
					<div class="column">
						<label>Cust Address1: </label>
						<input type="text" required name="cadd1" id="cadd1" <?php echo $s; ?> value="<?php echo $cadd1; ?>"/>
					</div>
					<div class="column">
						<label>Deli Address1: </label>
						<input type="text" required name="dtadd1" id="dtadd1" <?php echo $s; ?> value="<?php echo $dtadd1; ?>"/>
					</div>
					<div class="column">
						<label>Part Descrip&nbsp;: </label>
						<input type="text" required name="pd" id="pd"/>
					</div>
					<div class="column">
						<label>Cust Address2: </label>
						<input type="text" required name="cadd2" id="cadd2" <?php echo $s; ?> value="<?php echo $cadd2; ?>"/>
					</div>
					<div class="column">
						<label>Deli Address2: </label>
						<input type="text" required name="dtadd2" id="dtadd2" <?php echo $s; ?> value="<?php echo $dtadd2; ?>"/>
					</div>
					<div class="column">
						<label>Vendor Code&nbsp;&nbsp;: </label>
						<input type="text" required name="vc" id="vc" <?php echo $s; ?> value="<?php echo $vc; ?>"/>
					</div>
					<div class="column">
						<label>Cust Address3: </label>
						<input type="text" required name="cadd3" id="cadd3" <?php echo $s; ?> value="<?php echo $cadd3; ?>"/>
					</div>
					<div class="column">
						<label>Deli Address3: </label>
						<input type="text" required name="dtadd3" id="dtadd3" <?php echo $s; ?> value="<?php echo $dtadd3;	 ?>"/>
					</div>
					<div class="column">
						<label>HSN Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="hsnc" id="hsnc"/>
					</div>
					<div class="column">
						<label>Cust GSTno&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="cgstno" id="cgstno" <?php echo $s; ?> value="<?php echo $cgstno; ?>"/>
					</div>
					<div class="column">
						<label>Deli GSTno&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="dtgstno" id="dtgstno" <?php echo $s; ?> value="<?php echo $dtgstno; ?>"/>
					</div>
					<div class="column">
						<label>Cust PO No&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="cpono" id="cpono"/>
					</div>
					<div class="column">
						<label>Cust PO Date&nbsp;: </label>
						<input type="text" required name="cpodt" id="cpodt"/>
					</div>	
					<div class="column">
						<label>PO ITEM No&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="poino" id="poino"/>
					</div>
					<div class="column">
						<label>Part Rate&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="rate" id="rate"/>
					</div>
					<div class="column">
						<label>Rate Per Qty&nbsp;: </label>
						<input type="text" required name="per" id="per"/>
					</div>
					<div class="column">
						<label>UOM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="uom" id="uom"/>
					</div>
					<div class="column">
						<label>Packing Chrge: </label>
						<input type="text" required name="pc" id="pc"/>
					</div>
					<div class="column">
						<label>CGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="cgst" id="cgst"/>
					</div>
					<div class="column">
						<label>SGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="sgst" id="sgst"/>
					</div>
					<div class="column">
						<label>IGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="igst" id="igst"/>
					</div>
					<div class="column">
						<label>E-WAY NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" readonly="readonly" name="ebn" id="ebn"/>
					</div>
					<div class="column">
						<label>DESPATCH MODE: </label>
						<input type="text"  name="dm" id="dm"/>
					</div>
					<div class="column">
						<label>CCODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="ccode" id="ccode" <?php echo $s; ?> value="<?php echo $ccode; ?>"/>
					</div>
					<div class="column">
						<label>REMARKLINE1&nbsp;&nbsp;: </label>
						<input type="text"  name="r1" id="r1"/>
					</div>
					<div class="column">
						<label>REMARKLINE2&nbsp;&nbsp;: </label>
						<input type="text" name="r2" id="r2"/>
					</div>
					<div class="column">
						<label>REMARKLINE3&nbsp;&nbsp;: </label>
						<input type="text" name="r3" id="r3"/>
					</div>
					<div class="column">
						<label>REMARKLINE4&nbsp;&nbsp;: </label>
						<input type="text"  name="r4" id="r4"/>
					</div>
					<div class="column">
						<label>REMARKLINE5&nbsp;&nbsp;: </label>
						<input type="text" name="r5" id="r5"/>
					</div>
					<div class="column">
						<label>MODE OF TRANS: </label>
						<input type="text" name="tm" readonly id="tm" value="<?php echo $mot; ?>"/>
					</div>
					<div class="column">
						<label>DISTANCE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" name="dis" readonly  id="dis" value="<?php echo $dist; ?>"/>
					</div>
					<div>
						<input style="left:15%;" type="submit" name="sub" value="ADD DATA">
					</div>
					<div class="column1">
				</form>
	</body>
</html>
		