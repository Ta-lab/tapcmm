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
	if($_SESSION['access']=="ALL" || $_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C")
	{
		$id=$_SESSION['user'];
		$activity="DC MASTER CUSTOMER UPDATION";
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
	<h4 style="text-align:center"><label> NEW DC MASTER INSERTION & UPDATION [ I28 ]</label></h4>
	<div>
			<script>
			function reload(form)
			{
				var p4 = document.getElementById("p4").value;
				self.location=<?php echo"'i28.php?code='"?>+p4;
			}
			function reload0(form)
			{
				var p4 = document.getElementById("p4").value;
				var sp=form.sp.options[form.sp.options.selectedIndex].value; 
				self.location=<?php echo"'i28.php?code='"?>+p4+'&sp='+sp;
			}
			function reload1(form)
			{
				var p4 = document.getElementById("p4").value;
				var sp=form.sp.options[form.sp.options.selectedIndex].value; 
				var pn = document.getElementById("pn").value;
				self.location=<?php echo"'i28.php?code='"?>+p4+'&sp='+sp+'&pn='+pn;
			}
		</script>
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
	<div class="divclass">
		<form method="POST">	
				<datalist id="partlist" >
						<?php
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
						if(!$con)
							echo "connection failed";
						if(isset($_GET['code']) && $_GET['code']!='' && isset($_GET['sp']) && $_GET['sp']!='')
						{
							$code=$_GET['code'];
							$sp=$_GET['sp'];
							$query = "SELECT distinct pn FROM dcmaster where sccode='$code' and sp='$sp'";
							$result = $con->query($query);
							echo"<option value=''>Select one</option>";
							while ($row = mysqli_fetch_array($result)) 
							{
								if($_GET['pn']==$row['pn'])
									echo "<option selected value='".$row['pn']."'>".$row['pn']."</option>";
								else
									echo "<option value='".$row['pn']."'>".$row['pn']."</option>";
							}
						}
						?>
					</datalist>
					<datalist id="codelist" >
					<?php
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
						$t=$_GET['partnumber'];
						$result1 = $con->query("select distinct sccode from dcmaster");
						echo"<option value=''>Select one</option>";
						while ($row1 = mysqli_fetch_array($result1)) 
						{
							if(isset($_GET['sccode'])==$row1['sccode'])
								echo "<option selected value='".$row1['sccode']."'>".$row1['sccode']."</option>";
							else
								echo "<option value='".$row1['sccode']."'>".$row1['sccode']."</option>";
						}
					?>
				</datalist>
				<br>
			<div class="find">
				<label>SUB-CONTRACTOR CODE</label>
				<input type="text" required style="width:50%; background-color:white;" class='s' onchange=reload(this.form) id="p4" name="custpo" list="codelist" value="<?php if(isset($_GET['code'])){echo $_GET['code'];}?>">
			</div>
			<br><br><br>
			<?php
				$scn="";$sca1="";$sca2="";$sca3="";$gst="";$sccode="";$sc="";$pn="";$pd="";$sp="";$od="";$hsnc="";$uom="";$mot="";$state="";
				$s1="required";$s2="required";$s3="required";
				$cgst="";$sgst="";$igst="";
				if(isset($_GET['code']) && $_GET['code']!='')
				{
					$s1="required";
					$s2="";
					$s3="readonly";
					$t=$_GET['code'];
					$result1 = $con->query("select distinct scn,sca1,sca2,sca3,gst,sccode,mot,sc,state,cgst,sgst,igst from dcmaster where sccode='$t' LIMIT 1");
					$row1 = mysqli_fetch_array($result1);
					if($result1->num_rows==1)
					{
						$scn=$row1['scn'];$sca1=$row1['sca1'];$sca2=$row1['sca2'];$sca3=$row1['sca3'];$gst=$row1['gst'];$sccode=$row1['sccode'];$mot=$row1['mot'];$sc=$row1['sc'];
						$state=$row1['state'];
						$cgst=$row1['cgst']; $sgst=$row1['sgst']; $igst=$row1['igst'];
					}
				}
				if(isset($_GET['sp']) && $_GET['sp']!='')
				{
					$sp=$_GET['sp'];
					$s3="required";
				}
				if(isset($_GET['pn']) && $_GET['pn']!='' && isset($_GET['sp']) && $_GET['sp']!='')
				{
					$result1 = $con->query("select distinct scn,sca1,sca2,sca3,gst,sccode,mot,sc,state,cgst,sgst,igst from dcmaster where sccode='$t' LIMIT 1");
					$row1 = mysqli_fetch_array($result1);
					if($result1->num_rows!=0)
					{
						$s1="readonly";
						$s2="readonly";
					}
					else
					{
						$s1="";
						$s2="";
					}
					$t=$_GET['code'];
					$pn=$_GET['pn'];
					$sp=$_GET['sp'];
					$result1 = $con->query("select pn,pd,sp,operdesc,uom,hsnc,cgst,sgst,igst from dcmaster where sp='$sp' AND pn='$pn' And sccode='$t' LIMIT 1");
					$row1 = mysqli_fetch_array($result1);
					if($result1->num_rows==1)
					{
						$pn=$row1['pn'];$pd=$row1['pd'];$sp=$row1['sp'];$od=$row1['operdesc'];$uom=$row1['uom'];$hsnc=$row1['hsnc'];
						$cgst=$row1['cgst']; $sgst=$row1['sgst']; $igst=$row1['igst'];
					}
				}
				
			?>
			</form>
				<div id="stylized" class="myform">
				<form id="form" name="form" method="post" action="i28db.php">
					<div class="column">
						<label>SUB-CONTRACTOR NAME: </label>
						<input type="text" name="scn" <?php echo $s1; ?> id="scn" value="<?php echo $scn; ?>"/>
					</div>
					<div class="column">
						<label>Stocking Point&nbsp: </label>
						<select  name="sp" <?php if(isset($_GET['code'])){ echo "onchange=reload0(this.form)";}?> id="sp" <?php echo $s2; ?> value="<?php echo $sp;?>">
						<option value=''>Select one</option>
						<?php
						if($sp=="To S/C"){
							echo "<option selected value='" . $sp . "'>" . $sp ."</option>";
							echo "<option value='FG For S/C'>FG For S/C</option>";
						}
						else if($sp=="FG For S/C"){
							echo "<option value='To S/C'>To S/C</option>";
							echo "<option selected value='" . $sp . "'>" . $sp ."</option>";
						}
						else{
							echo "<option value='To S/C'>To S/C</option>";
							echo "<option value='FG For S/C'>FG For S/C</option>";
						}
						?>
						</select>
					</div>
					<div class="column">
						<label>Part Number&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="pn" <?php if(isset($_GET['code'])){ echo "onchange=reload1(this.form)";}?>  <?php echo $s3; ?> list="partlist" value="<?php echo $pn;?>" id="pn"/>
					</div>
					<div class="column">
						<label>Cust Address1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" name="sca1" id="sca1" <?php echo $s1; ?> value="<?php echo $sca1; ?>"/>
					</div>
					<div class="column">
						<label>SCCODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="sccode" id="sccode" <?php echo $s1; ?> value="<?php echo $sccode; ?>"/>
					</div>
					<div class="column">
						<label>Part Descrip&nbsp;&nbsp;: </label>
						<input type="text"  name="pd" <?php echo $s3; ?> id="pd" value="<?php echo $pd; ?>"/>
					</div>
					<div class="column">
						<label>Cust Address2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="sca2" id="sca2" <?php echo $s1; ?> value="<?php echo $sca2; ?>"/>
					</div>
					<div class="column">
						<label>STATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="state" <?php echo $s1; ?> id="state" value="<?php echo $state; ?>"/>
					</div>
					<div class="column">
						<label>HSN Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="hsnc" <?php echo $s3; ?> id="hsnc" value="<?php echo $hsnc; ?>"/>
					</div>
					<div class="column">
						<label>Cust Address3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="sca3" id="sca3" <?php echo $s1; ?> value="<?php echo $sca3; ?>"/>
					</div>
					<div class="column">
						<label>State Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="sc" <?php echo $s1; ?> value="<?php echo $sc; ?>" id="sc"/>
					</div>
					<div class="column">
						<label>UOM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="uom" <?php echo $s3; ?> id="uom" value="<?php echo $uom; ?>"/>
					</div>
					<div class="column">
						<label>Cust GSTno&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="gst" id="gst" <?php echo $s1; ?> value="<?php echo $gst; ?>"/>
					</div>
					<div class="column">
						<label>DESPATCH MODE&nbsp;&nbsp;: </label>
						<input type="text"  name="dm" <?php echo $s1; ?> id="dm" value="<?php echo $mot; ?>"/>
					</div>
					<div class="column">
						<label>Operation Desc: </label>
						<input type="text"  name="od" <?php echo $s3; ?> id="od" value="<?php echo $od; ?>"/>
					</div>
					<div class="column">
						<label>CGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="cgst" <?php echo $cgst; ?> id="cgst" value="<?php echo $cgst; ?>"/>
					</div>
					<div class="column">
						<label>SGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="sgst" <?php echo $sgst; ?> id="sgst" value="<?php echo $sgst; ?>"/>
					</div>
					<div class="column">
						<label>IGST&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="igst" <?php echo $igst; ?> id="igst" value="<?php echo $igst; ?>"/>
					</div>
					
					<div>
						<input style="left:60%;" type="submit" value="<?php
						if(isset($_GET['code']) && $_GET['code']!="" && isset($_GET['pn']) && $_GET['pn']!="")
						{
							echo "UPDATE PART DETAIL";
						}
						else if(isset($_GET['code']) && $_GET['code']!="")
						{
							echo "UPDATE S/C DETAIL";
						}
						else
						{
							echo "INSERT MASTER DATA";
						}
						?>" name="<?php
						if(isset($_GET['code']) && $_GET['code']!="" && !isset($_GET['pn']))
						{
							echo "sub";
						}
						else
						{
							echo "submit";
						}
						?>">
					</div>
				</form>
	</body>
</html>
			