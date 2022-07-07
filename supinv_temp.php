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
	if($_SESSION['access']=="FG For Invoicing")
	{
		$id=$_SESSION['user'];
		$activity="PREPARING INVOICE";
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
<!DOCTYPE html>-
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script type="text/javascript" src="table_script.js"></script>
	<script src = "js\bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="des1.css">
</head>
<body style="background-image: url('img/6.jpg');">
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>SUPPLIMENTARY INVOICING ENTRY </label></h4>
		<script>
			function reload0(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				self.location=<?php echo"'supinv_temp.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p;
			}
			function reload(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				self.location=<?php echo"'supinv_temp.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2;
			}
			function reload1(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p3=form.cname.options[form.cname.options.selectedIndex].value; 
				self.location=<?php echo"'supinv_temp.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&cname='+p3;
			}
			function reload2(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p3=form.cname.options[form.cname.options.selectedIndex].value; 
				var p4 = document.getElementById("p4").value;
				self.location=<?php echo"'supinv_temp.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&cname='+p3+'&cpo='+p4;
			}
		</script>
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<?php
			$con = mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				echo "connection failed";
			$q = "SELECT * from d19";
			$r = $con->query($q);
			$row=$r->fetch_assoc();
			date_default_timezone_set("Asia/Kolkata");
				if(date("H")>=$row['invoice'] || date("H")<0)
				{
					header("location: inputlink.php");
				}
		?>
		<div class="divclass">
		<form action="supinvdet_temp.php" method="post" enctype="multipart/form-data">
		<br><br>
			<div >
				<label>INVOICE NUMBER</label>
				<input style="width: 60%; background-color:white;" type="text" id="p0" name="inum" value="<?php
				if(isset($_GET['inv']))
					{
						$q = "select invno as gen from inv_det ORDER BY invno DESC LIMIT 1";
						$r = $con->query($q);
						$fch=$r->fetch_assoc();
						if($fch['gen']=="")
						{
							echo 1;
						}
						else
						{
							$c=substr($fch['gen'],0,7).str_pad((substr($fch['gen'],7,5)+1),5,"0",STR_PAD_LEFT);
							if($_GET['inv']==$c && $_GET['tdate']==date('Y-m-d'))
							{
								echo $c;
							}
							else
							{
								echo $_GET['inv'];
								//header('location: logout.php');
							}
						}
					}
					else
					{
						$q1 = "SELECT distinct status from admin1 where status='3'";
						$r1 = $con->query($q1);
						$row1=$r1->fetch_assoc();
						if($row1['status']=="3")
						{
							header('location: inputlink.php?msg=6');
						}
						else
						{
							$id=$_SESSION['user'];
							mysqli_query($con,"UPDATE admin1 set status='3' where userid='$id'");
						}
						mysqli_query($con,"DELETE from inv_det where ok='F'");
						$q = "select invno as gen from inv_det ORDER BY invno DESC LIMIT 1";
						$r = $con->query($q);
						$fch=$r->fetch_assoc();
						if(substr($fch['gen'],2,2)!=date("y") && date("m")==3)
						{
							$unit="U1";
							$y=date("y").(date('y')+1);
							$digit="-00001";
							echo $unit.$y.$digit;
						}
						else{
							echo substr($fch['gen'],0,7).str_pad((substr($fch['gen'],7,5)+1),5,"0",STR_PAD_LEFT);
						}
					}
				?>"/>	
			</div>
			<br>
			<div >
				<label>INVOICE DATE</label>
				<input style="width: 60%; background-color:white;" type="date" id="p1" name="tdate" value="<?php
				if(isset($_GET[	'tdate']))
				{
					echo $_GET['tdate'];
				}
				else
				{
					echo date('Y-m-d');
				}
				?>"/>	
			</div>
			<div>
					<datalist id="codelist" >
						<?php
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT distinct ccode FROM invmaster";
										$result = $con->query($query);
										echo"<option value=''>Select one</option>";
										while ($row = mysqli_fetch_array($result)) 
										{
											if($_GET['ccode']==$row['ccode'])
												echo "<option selected value='".$row['ccode']."'>".$row['ccode']."</option>";
											else
												echo "<option value='".$row['ccode']."'>".$row['ccode']."</option>";
										}
						?>
						</datalist>
					<br>
					<label>CUSTOMER CODE</label>
					<input type="text" style="width: 60%; background-color:white;" class='s' required onchange=reload0(this.form) id="p" name="cc" list="codelist" value="<?php if(isset($_GET['ccode'])){
						echo $_GET['ccode'];
						}?>"/>
				</div>
			<div>
					<datalist id="partlist" >
						<?php
							if(isset($_GET['ccode']))
							{
								$t=$_GET['ccode'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT distinct pn FROM invmaster where ccode='$t'";
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
					<br>
					<label>PART NUMBER</label>
					<input type="text" style="width: 60%; background-color:white;" class='s' required onchange=reload(this.form) id="p2" name="pn" list="partlist" value="<?php if(isset($_GET['partnumber'])){echo $_GET['partnumber'];}?>"/>
				</div>
				<div><br>
					<label>CUSTOMER NAME</label>
					<input type="text" class='s' readonly='readonly' required style="width: 60%; background-color:white;" id="p3" name="cname" value="<?php
					if(isset($_GET['partnumber']))
					{
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
						$t1=$_GET['partnumber'];
						$result1 = $con->query("select distinct cname,cpono,despatch from invmaster where pn='$t1' and ccode='$t'");
						$row1 = mysqli_fetch_array($result1);
						echo $row1['cname'];
						$t2=$row1['cpono'];
						$t3=$row1['despatch'];
					}
					else
					{
						$t2="";
						$t3="";
					}
					?>"/>
					</div>
				<br>
				<div>
					<label>CUSTOMER PO NO</label>
					<input type="text" style="width: 60%; background-color:white;" readonly='readonly' id="p4" name="cpono"  <?php if(isset($_GET['cpo'])){echo "value=".$_GET['cpo'];}else{echo "value='$t2'";}?>>
				</div>
				<br>
				<div>
					<label>INVOICE QTY</label>
					<input type="number" style="width: 60%; background-color:white;"  id="p5"  required name="tiqty">
				</div>
				<br>
				<div>
					<label>INVOICE RATE</label>
					<input type="number" style="width: 60%; background-color:white;" step="0.001" id="p5" required name="rate">
				</div>
				<br>
				<div>
					<label>Mode Of Dispatch</label>
					<input type="text" style="width: 60%; background-color:white;" id="p6" name="mod" value="<?php
					echo $t3;
					?>">
				</div>
				<br>
			</div>
			<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<div id="wrapper">
<br>
<div>
<input type="submit" name="submit" value="ENTER">
</div>
</div>
<br>
</form>
</body>
</html>