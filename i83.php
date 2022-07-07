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
		<h4 style="text-align:center"><label>CUSTOMER REJECTION RECEIPT UPDATION [I83]</label></h4>
		<br>
		<form  method="POST" action="i83db.php">
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
									<label class="control-label col-xs-2">INVOICE NUMBER</label>
									<datalist id="languages" >
										<?php
												$con = mysqli_connect('localhost','root','Tamil','mypcm');
												if(!$con)
													echo "connection failed";
												$query = "SELECT distinct invno FROM inv_det where invno like 'U1%'";
														$result = $con->query($query);
														echo"<option value=''>Select one</option>";
														while ($row = mysqli_fetch_array($result)) 
														{
															if(isset($_GET['invno']) && $_GET['invno']==$row['invno'])
																echo "<option selected value='".$row['invno']."'>".$row['invno']."</option>";
															else
																echo "<option value='".$row['invno']."'>".$row['invno']."</option>";
														}
												
										?>
										</datalist>
									<script>
										function reload(form)
										{	
											var val=document.getElementById("invno").value;
											self.location='i83.php?invno='+val ;
										}
									</script>
									<input type="text" class='form-control' id="invno" name="invno" onchange="reload(this.form)" list="languages" value="<?php
									if(isset($_GET['invno']) && $_GET['invno']!="")
									{
										echo $_GET['invno'];
									}
									?>"/>
								</div>
								
							</div>
							<div class="col-md-6 col-sm-6">	
								<div class='form-group'>
									<label class="control-label col-xs-2">RIN NUMBER</label>
									<?php
										$con=mysqli_connect('localhost','root','Tamil','storedb');
										if(!$con)
										{
											die(mysqli_error());				
										}
										
										$query = mysqli_query($con,"SELECT rin FROM rin_receipt ORDER BY rin DESC"); 
										$row=mysqli_fetch_array($query);
										$row_cnt = $query->num_rows;
										if($row_cnt==0)
										{
											$fstr2="R2018000001";
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
										echo '<input type="text" class="form-control" id="rin" name="rin" readonly="readonly"  value="'. $fstr2;
									?>">						
								</div>	
								<div class='form-group'>
									<label class="control-label col-xs-2">PART NUMBER</label>
									<datalist id="languages1" >
									<?php
												$con = mysqli_connect('localhost','root','Tamil','mypcm');
												if(!$con)
													echo "connection failed";
												if(isset($_GET['invno']) && $_GET['invno']!="")
												{
													$t=$_GET['invno'];
													$query = "SELECT distinct pn FROM inv_det WHERE invno='$t'";
													$result = $con->query($query);
													echo"<option value=''>Select one</option>";
													while ($row = mysqli_fetch_array($result)) 
													{
														if(isset($_GET['pn']) && $_GET['pn']==$row['pn'])
															echo "<option selected value='".$row['pn']."'>".$row['pn']."</option>";
														else
															echo "<option value='".$row['pn']."'>".$row['pn']."</option>";
													}
												}
												else
												{
													
												}
										?>
										</datalist>
									<input type="text" class='form-control' id="part" name="part" list="languages1" />
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
									<label class="control-label col-xs-2">DOC REF NO</label>
									<input type="text" class='form-control'  required id="docno" name="docno" />
								</div>
								<div class='form-group'>
									<label class="control-label col-xs-2">UOM</label>
									<input type="text" class='form-control'  readonly id="uom" name="uom" VALUE='NOS' />
								</div>
							</div>
							<div class="col-md-6 col-sm-6">	
								<div class='form-group'>
									<label class="control-label col-xs-2">CUSTOMER NAME</label>
									<input type="text" class="form-control"  readonly name ="cname" id="cname" value="<?php
									$con = mysqli_connect('localhost','root','Tamil','mypcm');
										if(!$con)
											echo "connection failed";
										if(isset($_GET['invno']) && $_GET['invno']!="")
										{
											$t=$_GET['invno'];
											$query = "SELECT distinct cname,cname1 FROM inv_det WHERE invno='$t'";
											$result = $con->query($query);
											$row = mysqli_fetch_array($result);
											echo $row['cname'].$row['cname1'];
										}
										else
										{
											
										}
									?>">							
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
		</section>
		</br>
<div class="form-group">
	<button type="Submit" class="btn btn-success" name="submit" style='margin-left:70%'>Submit</button>
</div>
</form>
</body>
</html>