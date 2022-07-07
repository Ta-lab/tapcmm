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
	if($_SESSION['access']=="PURCHASE" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="SUPPLIER DETAIL UPDATION";
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
$con=mysqli_connect('localhost','root','Tamil','purchasedb');
if(isset($_POST['add']) && $_POST['scode']!="")
{
	$scode = $_POST['scode'];$sname = $_POST['sname'];$pack = $_POST['pack'];$cg=$_POST['cg'];$sg=$_POST['sg'];
	$payterm = $_POST['payterm'];$dmode = $_POST['dmode'];$remark = $_POST['remark'];$ig=$_POST['ig'];
	$sadd1=$_POST['sadd1'];$sadd2=$_POST['sadd2'];$sadd3=$_POST['sadd3'];$sgst=$_POST['sgst'];$ptype=$_POST['ptype'];
	mysqli_query($con,"DELETE FROM supplier where scode='$scode'");
	mysqli_query($con,"INSERT INTO `supplier` (`scode`, `ptype`, `sname`, `sadd1`, `sadd2`, `sadd3`, `sgst`, `packing`, `pterm`, `dmode`, `cg`, `sg`, `ig`, `remark`) VALUES ('$scode', '$ptype', '$sname', '$sadd1', '$sadd2', '$sadd3', '$sgst', '$pack', '$payterm', '$dmode', '$cg', '$sg', '$ig', '$remark');");
	header("location: i72add.php?scode=$scode");
}
if(isset($_POST['sub']))
{
	$scode = $_POST['scode'];$sname = $_POST['sname'];$pack = $_POST['pack'];$cg=$_POST['cg'];$sg=$_POST['sg'];$ig=$_POST['ig'];
	$payterm = $_POST['payterm'];$dmode = $_POST['dmode'];$remark = $_POST['remark'];
	$sadd1=$_POST['sadd1'];$sadd2=$_POST['sadd2'];$sadd3=$_POST['sadd3'];$sgst=$_POST['sgst'];$ptype=$_POST['ptype'];
	mysqli_query($con,"DELETE FROM supplier where scode='$scode'");
	mysqli_query($con,"INSERT INTO `supplier` (`scode`, `ptype`, `sname`, `sadd1`, `sadd2`, `sadd3`, `sgst`, `packing`, `pterm`, `dmode`, `cg`, `sg`, `ig`, `remark`) VALUES ('$scode', '$ptype', '$sname', '$sadd1', '$sadd2', '$sadd3', '$sgst', '$pack', '$payterm', '$dmode', '$cg', '$sg', '$ig', '$remark');");
	header("location: i72db.php");
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
	<h4 style="text-align:center"><label> SUPPLIER MASTER UPDATION [ I72 ]</label></h4>
	<div>
			<script>
			function reload(form)
			{
				var p4 = document.getElementById("p4").value;
				self.location=<?php echo"'i72.php?code='"?>+p4;
			}
		</script>
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
	<div class="divclass">
		<form method="POST" action='i141.php'>	
					<datalist id="codelist" >
					<?php
						$con = mysqli_connect('localhost','root','Tamil','purchasedb');
							if(!$con)
								echo "connection failed";
						$t=$_GET['partnumber'];
						$result1 = $con->query("select distinct scode from supplier");
						echo"<option value=''>Select one</option>";
						while ($row1 = mysqli_fetch_array($result1)) 
						{
							if(isset($_GET['scode'])==$row1['scode'])
								echo "<option selected value='".$row1['scode']."'>".$row1['scode']."</option>";
							else
								echo "<option value='".$row1['scode']."'>".$row1['scode']."</option>";
						}
					?>
				</datalist>
				<br>
			<div class="find">
				<label>SUPPLIER CODE</label>
				<input type="text" required style="width:50%; background-color:white;" class='s' onchange=reload(this.form) id="p4" name="scode" list="codelist" value="<?php if(isset($_GET['code'])){echo $_GET['code'];}?>">
			</div>
			<br><br><br>
			<?php
				$sname="";$sadd1="";$sadd2="";$sadd3="";$sgst="";$ptype="";$pterm="";$packing="";$dmode="";$remark="";$scode="";$cg="";$sg="";$ig="";
				if(isset($_GET['code']) && $_GET['code']!='')
				{
					$s="";
					$t=$_GET['code'];
					$scode=$_GET['code'];
					$result1 = $con->query("select distinct sname,sadd1,sadd2,sadd3,sgst,ptype,pterm,packing,dmode,remark,cg,sg,ig from supplier where scode='$t' LIMIT 1");
					$row1 = mysqli_fetch_array($result1);
					if($result1->num_rows==1)
					{
						$sname=$row1['sname'];$sadd1=$row1['sadd1'];$sadd2=$row1['sadd2'];$sadd3=$row1['sadd3'];
						$sgst=$row1['sgst'];$ptype=$row1['ptype'];$pterm=$row1['pterm'];$cg=$row1['cg'];$sg=$row1['sg'];$ig=$row1['ig'];
						$packing=$row1['packing'];$dmode=$row1['dmode'];$remark=$row1['remark'];
					}
				}
			?>
			</form>
				<div id="stylized" class="myform">
				<form id="form" name="form" method="post" action="i72.php">
				<div class="column">
					<label>SUPLIER NAME&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  name ="sname"  id="sname"  value="<?php echo $sname;?>"/>
				</div>
				
				<div style="display:none">
					<label>SUPLIER CODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  name ="scode"  id="scode"  value="<?php echo $scode;?>"/>
				</div>
				
				<div class="column">
					<label>PURCHASE TYPE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="ptype" name="ptype"  value="<?php echo $ptype;?>"/>
				</div>
				
				<div class="column">
					<label>PAYMENT TERM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="payterm" name="payterm"  value="<?php echo $pterm;?>"/>
				</div>
				
				
				<div class="column">
					<label>SUPPLIER ADDRESS 1:</label>
						<input type="text"  id="sadd1"  name="sadd1"  value="<?php echo $sadd1;?>"/>
				</div>
				
				<div class="column">
					<label>PACKING&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="pack" name="pack" value="<?php echo $packing;?>"/>
				</div>
				
				<div class="column">
					<label>CGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  id="cg" name="cg" value="<?php echo $cg;?>"/>
				</div>
				
				
				<div class="column">
					<label>SUPPLIER ADDRESS 2:</label>
						<input type="text"  id="sadd2"  name="sadd2"  value="<?php echo $sadd2;?>"/>					
				</div>
				
				
				<div class="column">
					<label>DISPACH MODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="dmode" name="dmode"  value="<?php echo $dmode;?>"/>					
				</div>
				
				<div class="column">
					<label>SGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  id="sg" name="sg"  value="<?php echo $sg;?>"/>
				</div>
				
				
				<div class="column">
					<label>SUPPLIER ADDRESS 3:</label>
						<input type="text"  id="sadd3"  name="sadd3"  value="<?php echo $sadd3;?>"/>
				</div>
				
								
				<div class="column">
					<label>SUP_GST NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="sgst"  name="sgst"  value="<?php echo $sgst;?>"/>
				</div>
				
				<div class="column">
					<label>IGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  id="ig" name="ig"  value="<?php echo $ig;?>"/>
				</div>
				
				<div class="column">
					<label>REMARK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  id="remark" maxlength="43" name="remark" value="<?php echo $remark;?>"/>
				</div>
				
			<br><br><br><br><br><br><br>
				<section>
			<div id="wrapper">
<table align="center" cellspacing=2 cellpadding=5  id="data_table" border=1>
<tr>
<th>MATERIAL CODE</th>
<th>MATERIAL DESCRIPTION</th>
<th>MDESC - PRINT</th>
<th>HSN CODE</th>
<th>UOM</th>
<th>UNIT RATE</th>
<th>UPDATE</th>
<th>ADD/DELETE_ROW</th>
</tr>
	<?php
	$r=0;$tt=0;
	if(isset($_GET['code']) && $_GET['code']!="")
	{
		$t=$_GET['code'];
		$result = mysqli_query($con,"SELECT * FROM pomaster WHERE scode='$t'");
		$r=$result->num_rows;
		while($row = mysqli_fetch_array($result))
		{
			echo "<tr><td>".$row['mcode']."</td><td>".$row['mdesc']."</td><td>".$row['mdesc2']."</td><td>".$row['hsnc']."</td><td>".$row['uom']."</td><td>".$row['rate']."</td><td><a href='i72add.php?scode=$t&rm=".$row['mdesc']."'>UPDATE</a></td><td><a href='i72del.php?code=$t&mcode=".$row['mcode']."'>DELETE</a></td></tr>";
		}
	}
	?>
	<?php
	if($r>0)
	{
		echo '<tr><td colspan="7"></td><td><button type="submit" name="add"><a>ADD ROWS<a></button></td></tr>';
		echo '</table><br>';
		echo'<div class="form-group"><input style="left:40%;" type="submit" name="sub" value="UPDATE SUPPLIER MASTER"></div>';
	}
	else
	{
		echo '<tr><td colspan="7">ATLEAST ONE PARTICULAR MUST BE NEEDED</td><td><button type="submit" name="add"><a>ADD ROWS<a></button></td></tr></table><br>';
	}
	?>
</div>
</section>
				</form>
	</body>
</html>
		