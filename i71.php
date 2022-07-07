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
		$activity="PO PLACEMENT";
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
<?php
$con=mysqli_connect('localhost','root','Tamil','purchasedb');
if(isset($_POST['add']))
{
	$tdate = $_POST['tdate'];$scode = $_POST['scode'];$ponum = $_POST['ponum'];$sname = $_POST['sname'];$pack = $_POST['pack'];
	$payterm = $_POST['payterm'];$sg = $_POST['sg'];$dmode = $_POST['dmode'];$cg = $_POST['cg'];$remark = $_POST['remark'];$ig = $_POST['ig'];
	$sadd1=$_POST['sadd1'];$sadd2=$_POST['sadd2'];$sadd3=$_POST['sadd3'];$sgst=$_POST['sgst'];$indent=$_POST['indent'];$indentdate=$_POST['indentdate'];
	$quot=$_POST['quot'];$qdate=$_POST['qdate'];$ptype=$_POST['ptype'];$email=$_POST['email'];$sl1=$_POST['sl1'];
	$sl2=$_POST['sl2'];$sl3=$_POST['sl3'];$sl4=$_POST['sl4'];
	$result = mysqli_query($con,"SELECT count(*) as c FROM poinfo where ponum='$ponum'");
	$row = mysqli_fetch_array($result);
	if($row['c']=='0')
	{
		mysqli_query($con,"INSERT INTO `poinfo` (`ponum`, `podate`, `scode` , `sname`, `sadd1`, `sadd2`, `sadd3`, `sgstno`, `indentno`, `indentdate`, `qno`, `qdate`, `ptype`, `pterm`, `cpname`, `phone`, `packing`, `dmode`, `cg`, `sg`, `ig`, `remark` , `sl1` , `sl2` , `sl3` , `sl4` , `status` , `print` ) VALUES ('$ponum', '$tdate', '$scode' , '$sname', '$sadd1', '$sadd2', '$sadd3', '$sgst', '$indent', '$indentdate', '$quot', '$qdate', '$ptype', '$payterm', '', '$email', '$pack', '$dmode', '$cg', '$sg', '$ig', '$remark' , '$sl1' , '$sl2', '$sl3', '$sl4' , 'F' , 'F')");
		//echo "INSERT INTO `poinfo` (`ponum`, `podate`, `scode` , `sname`, `sadd1`, `sadd2`, `sadd3`, `sgstno`, `indentno`, `indentdate`, `qno`, `qdate`, `ptype`, `pterm`, `cpname`, `phone`, `packing`, `dmode`, `cg`, `sg`, `ig`, `remark` , `sl1` , `sl2` , `sl3` , `sl4` , `status` , `print` ) VALUES ('$ponum', '$tdate', '$scode' , '$sname', '$sadd1', '$sadd2', '$sadd3', '$sgst', '$indent', '$indentdate', '$quot', '$qdate', '$ptype', '$payterm', '', '$email', '$pack', '$dmode', '$cg', '$sg', '$ig', '$remark' , '$sl1' , '$sl2', '$sl3', '$sl4' , 'F' , 'F')";
	}
	else
	{
		mysqli_query($con,"DELETE FROM poinfo where ponum='$ponum'");
		mysqli_query($con,"INSERT INTO `poinfo` (`ponum`, `podate`, `scode` , `sname`, `sadd1`, `sadd2`, `sadd3`, `sgstno`, `indentno`, `indentdate`, `qno`, `qdate`, `ptype`, `pterm`, `cpname`, `phone`, `packing`, `dmode`, `cg`, `sg`, `ig`, `remark` , `sl1` , `sl2` , `sl3` , `sl4` , `status` , `print` ) VALUES ('$ponum', '$tdate', '$scode' , '$sname', '$sadd1', '$sadd2', '$sadd3', '$sgst', '$indent', '$indentdate', '$quot', '$qdate', '$ptype', '$payterm', '', '$email', '$pack', '$dmode', '$cg', '$sg', '$ig', '$remark' , '$sl1' , '$sl2', '$sl3', '$sl4' , 'F' , 'F')");
	}
	header("location: i71add.php?ponum=$ponum&scode=$scode");
}
if(isset($_POST['place']))
{
	$ponum = $_POST['ponum'];
	header("location: i71db.php?place=1&ponum=$ponum");
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
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
<style>
.column
{
	float: left;
	width: 33%;
}
.column1
{
	float: left;
	width: 90%;
}
</style>
<script>
			function reload0(form)
			{
				var p1 = document.getElementById("scode").value;
				self.location=<?php echo"'i71.php?scode='"?>+p1;
			}
		</script>
</head>
<body>
	<div class="container-fluid">
	<div style="float:right">
			<a href="index.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
			<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>PURCHASE ORDER PREPERATION</label></h4><br>
		<?php
			
			$result = mysqli_query($con,"SELECT DISTINCT scode FROM supplier");
			echo "";
			echo"<datalist id='combo-code'>";
				while($row = mysqli_fetch_array($result))
					{
						echo "<option value='" . $row['scode'] . "'>" . $row['scode'] ."</option>";
					}
			echo"</datalist>";
			if(isset($_GET['ponum']))
			{
				$t=$_GET['ponum'];
				$result = mysqli_query($con,"SELECT * FROM poinfo WHERE ponum='$t'");
				$row = mysqli_fetch_array($result);
				$scode=$row['scode'];
				$sname=$row['sname'];
				$sadd1=$row['sadd1'];
				$sadd2=$row['sadd2'];
				$sadd3=$row['sadd3'];
				$sgst=$row['sgstno'];
				$ino=$row['indentno'];
				$idt=$row['indentdate'];
				$qno=$row['qno'];
				$qdt=$row['qdate'];
				$ptype=$row['ptype'];
				$pterm=$row['pterm'];
				$phone=$row['phone'];
				$packing=$row['packing'];
				$dmode=$row['dmode'];
				$cg=$row['cg'];
				$sg=$row['sg'];
				$ig=$row['ig'];
				$remark=$row['remark'];
				$sl1=$row['sl1'];
				$sl2=$row['sl2'];
				$sl3=$row['sl3'];
				$sl4=$row['sl4'];
			}
			else if((isset($_GET['scode']) && $_GET['scode']!=""))
			{
				mysqli_query($con,"DELETE FROM poinfo where status='F'");
				mysqli_query($con,"DELETE FROM pogoodsinfo where status='F'");
				$t=$_GET['scode'];
				$result = mysqli_query($con,"SELECT sname,sadd1,sadd2,sadd3,sgst,packing,dmode,pterm,cg,sg,ig,remark,ptype FROM supplier WHERE scode='$t'");
				$row = mysqli_fetch_array($result);
				$sname=$row['sname'];$sadd1=$row['sadd1'];$sadd2=$row['sadd2'];$sadd3=$row['sadd3'];$sgst=$row['sgst'];$packing=$row['packing'];
				$dmode=$row['dmode'];$pterm=$row['pterm'];$cg=$row['cg'];$sg=$row['sg'];$ig=$row['ig'];$remark=$row['remark'];$scode=$_GET['scode'];$ptype=$row['ptype'];
			}
			else if((isset($_GET['add'])))
			{
				mysqli_query($con,"DELETE FROM poinfo where status='F'");
				mysqli_query($con,"DELETE FROM pogoodsinfo where status='F'");
			}
		?>
		<form action="i71.php"  method="post" enctype="multipart/form-data">
			<div id="stylized" class="myform">
				
				<div class="column">
					<label>PO DATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="date" readonly  id="tdate" name="tdate" value="<?php
							if(isset($_GET['tdate']))
							{
								echo $_GET['tdate'];
							}
							else
							{
								echo date('Y-m-d');
							}
						?>"/>	
				</div>
				
				<div class="column">
					<label>PURCHASE ORDER NO:</label>
						<?php
							$con=mysqli_connect('localhost','root','Tamil','purchasedb');
							if(!$con)
							{
								die(mysqli_error());				
							}
							if(isset($_GET['ponum']) && $_GET['ponum']!="")
							{
								$query = mysqli_query($con,"SELECT ponum FROM poinfo where status='F' ORDER BY ponum DESC"); 
								$row=mysqli_fetch_row($query);
								$str=$row[0];
								if($str==$_GET['ponum'])
								{
									$fstr2=$_GET['ponum'];
								}
								else
								{
									header("location: logout.php");
								}
							}
							else
							{
								$query = mysqli_query($con,"SELECT ponum FROM poinfo ORDER BY ponum DESC"); 
								$row=mysqli_fetch_row($query);
								$str=$row[0];
								$fstr2=$str+1;
							}
							echo '<input type="text"  id="ponum" name="ponum" readonly="readonly"  value="'. $fstr2;
						?>">						
				</div>
				

				<div class="column">
					<label>SUPPLIER CODE&nbsp;&nbsp;:</label>
						<input type="text"  id="scode" onchange=reload0(this.form) name="scode" list="combo-code" value="<?php
						if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
						{
							echo $scode;
						}
						?>"/>	
				</div>	
				<br><br>
				<div class="column">
					<label>SUPLIER NAME&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  name ="sname" readonly id="sname" value="<?php
							if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $sname;
							}
						?>"/>
				</div>
				
				<div class="column">
					<label>INDENT NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="indent" name="indent" value="<?php
							if((isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $ino;
							}
						?>"/>
				</div>
				
				<div class="column">
					<label>INDENT DATE&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="date"  id="indentdate" name="indentdate" value="<?php
							if((isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $idt;
							}
						?>"/>
				</div>
				<br><br>
				<div class="column">
					<label>SUPPLIER ADDRESS 1:</label>
						<input type="text"  id="sadd1" readonly name="sadd1"
							<?php
							if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo 'value="'.$sadd1.'"';
							}
							?>/>	
				</div>
				
				<div class="column">
					<label>QUOTATION NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="quot" name="quot" value="<?php
							if((isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $qno;
							}
						?>"/>
				</div>
				
				
				<div class="column">
					<label>QUOTATION DATE&nbsp;:</label>
						<input type="date"  id="qdate" name="qdate" value="<?php
							if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $qdt;
							}
						?>"/>
				</div>
				<br><br>
				<div class="column">
					<label>SUPPLIER ADDRESS 2:</label>
						<input type="text"  id="sadd2" readonly name="sadd2"
						<?php
							if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo 'value="'.$sadd2.'"';
							}
						?>/>					
				</div>
				
				<div class="column">
					<label>PURCHASE TYPE&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="ptype" name="ptype" readonly  value="<?php
							if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $ptype;
							}
						?>"/>
				</div>
				
				<div class="column">
					<label>MOB No:/ E-mail:</label>
						<input type="text"  id="email" name="email" value="<?php
							if((isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $phone;
							}
						?>"/>
				</div>
				<br><br>
				
				<div class="column">
					<label>SUPPLIER ADDRESS 3:</label>
						<input type="text"  id="sadd3" readonly name="sadd3"
							<?php
								if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
								{
									echo 'value="'.$sadd3.'"';
								}
							?>/>	
				</div>
								
				<div class="column">
					<label>PAYMENT TERM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="payterm" name="payterm" value="<?php
						if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
						{
							echo $pterm;
						}
						?>"/>
				</div>
				
				<div class="column">
					<label>SHED LINE 1&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="sl1" maxlength="43" name="sl1" value="<?php
							if((isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $sl1;
							}
						?>"/>
				</div>
				<br><br>
								
				<div class="column">
					<label>SUP_GST NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="sgst" readonly name="sgst"
						<?php
							if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo 'value="'.$sgst.'"';
							}
						?>/>	
				</div>
				
				<div class="column">
					<label>SGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  id="sg" name="sg" value="<?php
						if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
						{
							echo $sg;
						}
					?>"/>
				</div>
				
				<div class="column">
					<label>SHED LINE 2&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="sl2" maxlength="43" name="sl2" value="<?php
							if((isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $sl2;
							}
						?>"/>
				</div>
				<br><br>
				<div class="column">
					<label>PACKING&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="pack" name="pack" value="<?php
						if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
						{
							echo $packing;
						}
						?>"/>
				</div>
				
				<div class="column">
					<label>CGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  id="cg" name="cg" value="<?php
						if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
						{
							echo $cg;
						}
					?>"/>
				</div>
				
				<div class="column">
					<label>SHED LINE 3&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="sl3" maxlength="43" name="sl3" value="<?php
							if((isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $sl3;
							}
						?>"/>
				</div>
				<br><br>
				<div class="column">
					<label>DISPACH MODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="dmode" name="dmode" value="<?php
							if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $dmode;
							}
						?>"/>						
				</div>
				
				
				<div class="column">
					<label>IGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  id="ig" name="ig" value="<?php
						if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
						{
							echo $ig;
						}
					?>"/>
				</div>
				
				
				<div class="column">
					<label>SHED LINE 4&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  id="sl4" maxlength="43" name="sl4" value="<?php
							if((isset($_GET['ponum']) && $_GET['ponum']!=""))
							{
								echo $sl4;
							}
						?>"/>
				</div>
				
				<br><br>
				<div class="column">
					<label>REMARK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  id="remark" maxlength="43" name="remark" value="<?php
						if((isset($_GET['scode']) && $_GET['scode']!="") || (isset($_GET['ponum']) && $_GET['ponum']!=""))
						{
							echo $remark;
						}
					?>"/>
				</div>
				
			</div>
			<br><br><br>
		<section>
			<div id="wrapper">
<table align="center" cellspacing=2 cellpadding=5  id="data_table" border=1>
<tr>
<th>MATERIAL CODE</th>
<th>MATERIAL DESCRIPTION</th>
<th>MATERIAL DESCRIPTION - PRINT</th>
<th>HSN CODE</th>
<th>UOM</th>
<th>DUE DATE</th>
<th>QUANTITY</th>
<th>UNIT RATE</th>
<th>AMOUNT</th>
<th>ADD/DELETE_ROW</th>
</tr>
<?php
$r=0;$tt=0;
if(isset($_GET['ponum']) && $_GET['ponum']!="")
{
	$t=$_GET['ponum'];
	$result = mysqli_query($con,"SELECT * FROM pogoodsinfo WHERE ponum='$t' order by sno");
	$r=$result->num_rows;
	while($row = mysqli_fetch_array($result))
	{
		echo "<tr><td>".$row['mcode']."</td><td>".$row['description']."</td><td>".$row['description2']."</td><td>".$row['hsnc']."</td><td>".$row['uom']."</td><td>".$row['duedate']."</td><td>".$row['quantity']."</td><td>".$row['rate']."</td><td>".$row['total']."</td><td><a href='i71del.php?ponum=$_GET[ponum]&sno=".$row['sno']."'>DELETE</a></td></tr>";
		$tt+=$row['total'];
	}
}
?>
<?php
if($r>0)
{
	echo '<tr><td colspan="7"></td><td>TOTAL : </td><td>'.$tt.'</td><td><button type="submit" name="add"><a>ADD ROWS<a></button></td></tr>';
	echo '</table><br>';
	echo'<div class="form-group"><button type="submit"  name="place" style="margin-left:45%">PLACE ORDER</button></div>';
}
else
{
	echo '<tr><td colspan="9">ATLEAST ONE PARTICULAR MUST BE NEEDED</td><td><button type="submit" name="add"><a>ADD ROWS<a></button></td></tr></table><br>';
}
?>
</div>
</section>
</div>
<br>
</form>
</br>