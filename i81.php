<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="Stores" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="STORES STOCK RECEIPT";
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
//INCOMING INSPECTION LOCK
		$scon=mysqli_connect('localhost','root','Tamil','storedb');
		
		$result = mysqli_query($scon,"SELECT COUNT(*) AS c FROM(SELECT receipt.grnnum,IF(Tinspdb.igrnnum IS NULL,0,Tinspdb.igrnnum) AS igrnnum,receipt.date,datediff(NOW(),receipt.date) as age FROM `receipt` LEFT JOIN (SELECT inspdb.grnnum AS igrnnum FROM inspdb) AS Tinspdb ON Tinspdb.igrnnum=receipt.grnnum HAVING igrnnum='0' AND age>'3') AS FT");
		$row = mysqli_fetch_array($result);
		if($row['c']>0)
		{
			header("location: inputlink.php?msg=39");
		}

?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">	
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script type="text/javascript" src="table_script.js"></script>
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
	<style>
		.col-xs-2 {
		width: 25%;
		}
		.btn-success {
		margin-left: 70%;
		}
	</style>
</head>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<body>
	<div class="container-fluid">
		<div  align="center"><br>
			<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<div style="float:right">
			<a href="index.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		</br>
		<h4 style="text-align:center"><label>STORE RECEIVING (ONLY SUB-CONTRACTING ITEMS) [I81]</label></h4>
		<br>
		<form  method="POST" action="i81db.php">
		<section class="intro">
			<div class="container">
				<div class="row text-center" >
					<div class='col-md-12'>
						<div class="row text-center pad-row">
							<div class="col-md-6 col-sm-6 ">
								<div class='form-group'>
									<label class="control-label col-xs-2">RECEIPT DATE</label>
									<input type="date" class='form-control' id="tdate" name="tdate" value="<?php
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
								<div class='form-group'>
									<label class="control-label col-xs-2">MATERIAL_CODE</label>
									<datalist id="languages" >
										<?php
												$con = mysqli_connect('localhost','root','Tamil','mypcm');
												if(!$con)
													echo "connection failed";
												$query = "SELECT distinct m_code FROM m13";
														$result = $con->query($query);
														echo"<option value=''>Select one</option>";
														while ($row = mysqli_fetch_array($result)) 
														{
															if($_GET['m_code']==$row['m_code'])
																echo "<option selected value='".$row['m_code']."'>".$row['m_code']."</option>";
															else
																echo "<option value='".$row['m_code']."'>".$row['m_code']."</option>";
														}
										?>
										</datalist>
									<script>
										function reload(form)
										{	
											var val=document.getElementById("partnumber").value;
											self.location='i81.php?mcode='+val ;
										}
									</script>
									<input type="text" class='form-control' id="partnumber" name="partnumber" onchange="reload(this.form)" list="languages" value="<?php
									if(isset($_GET['mcode']) && $_GET['mcode']!="")
									{
										echo $_GET['mcode'];
									}
									?>"/>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">	
								<div class='form-group'>
									<label class="control-label col-xs-2">GRN NUMBER</label>
									<?php
										$con=mysqli_connect('localhost','root','Tamil','storedb');
										if(!$con)
										{
											die(mysqli_error());				
										}
										$query = mysqli_query($con,"SELECT grnnum FROM receipt ORDER BY grnnum DESC"); 
										$row=mysqli_fetch_array($query);
										$row_cnt = $query->num_rows;
										if($row_cnt==0)
										{
											$fstr2="G2018000001";
										}
										else
										{
											$str=$row[0];
											if($str=="")
											{
												$istr=(int)0000+0001;
											}
											else
											{
												$str1=substr($str,5);
												$istr=(int)$str1+1;
											}
											$sstr=(string)$istr;
											$slen=strlen($sstr);
											$slen=6;
											$fstr=str_pad($sstr,$slen,"0",STR_PAD_LEFT);
											$fstr1=substr($str,0,5);
											$fstr2=$fstr1.$fstr;
										}
										echo '<input type="text" class="form-control" id="gnum" name="gnum" readonly="readonly"  value="'. $fstr2;
									?>">						
								</div>	
								<div class='form-group'>
									<label class="control-label col-xs-2">PART/MATERIAL DESCRIPTION</label>
									<datalist id="languages1" >
									<?php
												$con = mysqli_connect('localhost','root','Tamil','mypcm');
												if(!$con)
													echo "connection failed";
												if(isset($_GET['mcode']) && $_GET['mcode']!="")
												{
													$t=$_GET['mcode'];
													$query = "SELECT distinct rmdesc from m13 where m_code='$t'";
												}
												else
												{
													$query = "SELECT distinct rmdesc from m13";
												}
												$result = $con->query($query);
												echo"<option value=''>Select one</option>";
												while ($row = mysqli_fetch_array($result)) 
												{
													if($_GET['rmdesc']==$row['rmdesc'])
														echo "<option selected value='".$row['rmdesc']."'>".$row['rmdesc']."</option>";
													else
														echo "<option value='".$row['rmdesc']."'>".$row['rmdesc']."</option>";
												}
										?>
										</datalist>
									<input type="text" class='form-control' id="partdesc" name="partdesc" list="languages1" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<section class="intro2">
			<div class="container">
				<div class="row text-center" >
					<div class='col-md-12'>
						<div class="row text-center pad-row">
							<div class="col-md-6 col-sm-6 ">
								<div class='form-group'>
									<label class="control-label col-xs-2">PO NUMBER</label>
									<input type="text" class='form-control'  required id="ponum" name="ponum" />
								</div>
								<div class='form-group'>
									<label class="control-label col-xs-2">DC NUMBER</label>
									<input type="text" class='form-control' required id="dcnum" name="dcnum" />
								</div>
								<div class='form-group'>
									<label class="control-label col-xs-2">DC DATE</label>
									<input type="date" class='form-control'  required id="dcdate" name="dcdate" />
								</div>
								<div class='form-group'>
									<label class="control-label col-xs-2">TC NUMBER</label>
									<input type="text" class='form-control'  required id="tcnum" name="tcnum" />
								</div>
							</div>
							<div class="col-md-6 col-sm-6">	
								<div class='form-group'>
									<label class="control-label col-xs-2">SUPPLIER_NAME</label>
									<input type="text" class="form-control" list="combo-options"  required name ="sname" id="sname" value="<?php
									if(isset($_GET['sname']))
									{
										echo $_GET['sname'];
									}
									?>"/>
									<?php					
										$con=mysqli_connect('localhost','root','Tamil','podb');
										if(!$con)
											die(mysqli_error());
										$result = mysqli_query($con,"SELECT DISTINCT sname FROM partmaster");
										echo "";
										echo"<datalist id='combo-options'>";
											while($row = mysqli_fetch_array($result))
												{
													echo "<option value='" . $row['sname'] . "'>" . $row['sname'] ."</option>";
												}
										echo"</datalist>";						
									?>							
								</div>	
								<div class='form-group'>
									<label class="control-label col-xs-2">INVOICE_NUMBER</label>
									<input type="text" class='form-control'  required id="invnum" name="invnum" />
								</div>
								<div class='form-group'>
									<label class="control-label col-xs-2">INVOICE DATE</label>
									<input type="date" class='form-control'  required id="invdate" name="invdate" />
								</div>
								<div class='form-group'>
									<label class="control-label col-xs-2">UOM</label>
									<input type="text" class='form-control'  required id="uom" name="uom" />
								</div>
								<div class='form-group'>
									<label class="control-label col-xs-2">RECEIVED QTY</label>
									<input type="text" class='form-control'  required id="qty" name="qty" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<table align="center" cellspacing=2 cellpadding=5  id="data_table" border=1>
<tr>
<th>HEAT NUMBER</th>
<th>ADD/DELETE_ROW</th>
</tr>
<td><input type="text" class="s" id="newheat"  name="h[]"></td>
<td><p onclick="add_row()">add_rows</td>
</tr>
</table>
<br><br>
		</section>
		</br>
<div class="form-group">
	<button type="Submit" class="btn btn-success" name="submit" style='margin-left:50%'>Submit</button>
</div>
</form>
</body>
</html>