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
		$activity="NON-TRACE INVOICING";
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
    width: 90%;
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
		<label style="color:yellow";><p align="center" id="msg"></p></label>
	</div>
	<h4 style="text-align:center"><label>INVOICE PREPERATION - (NON-UNIT1 PARTS) </label></h4>
	<div>
<script>
var i = 0;
var txt = <?php
        echo "' Message : Non-Traceability Part Cannot Be Invoiced';";
?>
var speed = 75;
window.onload = typeWriter();
function typeWriter() {
  if (i < txt.length) {
    document.getElementById("msg").innerHTML += txt.charAt(i);
    i++;
    setTimeout(typeWriter, speed);
  }
}
</script>
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<script>
			function format() {
				var x = document.getElementById("vno");
				x.value=x.value.toUpperCase();
				if(event.keyCode!=8)
				{
					if(x.value.toString().length==2)
					{
						x.value=x.value+'-';
					}
					if(x.value.toString().length==5)
					{
						x.value=x.value+'-';
					}
					if(x.value.toString().length==8)
					{
						x.value=x.value+'-';
					}
				}
			}
			function reload0(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				self.location=<?php echo"'nontraceinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p;
			}
			function reload(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				self.location=<?php echo"'nontraceinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2;
			}
			function reloadpn(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p3 = document.getElementById("pn").value;
				self.location=<?php echo"'nontraceinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&pn='+pn.value;
			}
			function reloaddc(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p4 = document.getElementById("pdc").value;
				self.location=<?php echo"'nontraceinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&pdc='+p4;
			}
			function reload1(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p3=form.cname.options[form.cname.options.selectedIndex].value; 
				self.location=<?php echo"'nontraceinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&cname='+p3;
			}
			function reload2(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p3=form.cname.options[form.cname.options.selectedIndex].value; 
				var p4 = document.getElementById("p4").value;
				self.location=<?php echo"'nontraceinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&cname='+p3+'&cpo='+p4;
			}
			function preventback()
			{
				window.history.forward();
			}
			setTimeout("preventback()",0);
			window.onunload = function(){ null };
		</script>
		<br/>
		<?php
			$q = "SELECT * from d19";
			$r = $con->query($q);
			$row=$r->fetch_assoc();
			date_default_timezone_set("Asia/Kolkata");
			if(date("H:i:s")>=$row['invoice'] || date("H:i:s")<0)
			{
				header("location: inputlink.php?msg=12");
			}
		?>
		<form action="inventry.php" method="post" enctype="multipart/form-data">
			<div id="stylized" class="myform">
				<br><div class="column">
				<label>INVOICE NUMBER&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="text" readonly="readonly" id="p0" name="inum" value="<?php
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
								//echo $_GET['inv'];
								header('location: logout.php');
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
							//header('location: inputlink.php?msg=6');
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
						if(substr($fch['gen'],2,2)!=date("y") && date("m")==4)
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
				<datalist id="partlist" >
					<?php
						if(isset($_GET['ccode']))
						{
							$t=$_GET['ccode'];
							$query = "SELECT distinct pn FROM invmaster where ccode='$t' AND pn IN (SELECT DISTINCT pnum FROM m17)";
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
				
				
				<datalist id="dclist" >
					<?php
						if(isset($_GET['partnumber']))
						{
							$t=$_GET['partnumber'];
							$query = "SELECT DISTINCT dcnum FROM `dc_det` WHERE type='1' AND pn IN (SELECT DISTINCT rmdesc FROM m17 WHERE pnum='$t')";
							$result = $con->query($query);
							echo"<option value=''>Select one</option>";
							while ($row = mysqli_fetch_array($result)) 
							{
								if($_GET['pdc']==$row['dcnum'])
									echo "<option selected value='".$row['dcnum']."'>".$row['dcnum']."</option>";
								else
									echo "<option value='".$row['dcnum']."'>".$row['dcnum']."</option>";
							}
						}
					?>
				</datalist>
				
		<datalist id="codelist" >
		<?php
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
		<?php
		if(isset($_GET['partnumber']) && isset($_GET['ccode']) && isset($_GET['pdc']) && $_GET['pdc']!='')
			{
				$t1=$_GET['partnumber'];
				$t=$_GET['ccode'];
				$result1 = $con->query("select distinct cname,cpono,despatch,transmode,distance from invmaster where pn='$t1' and ccode='$t'");
				$row1 = mysqli_fetch_array($result1);
				$t1= $row1['cname'];
				$t2=$row1['cpono'];
				$t3=$row1['despatch'];
				$t4=$row1['transmode'];
				$t5=$row1['distance'];
			}
			else
			{
				$t1="";$t2="";$t3="";$t4="";$t5="";
			}
		?>
				<div class="column">
					<label>INVOICE DATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input readonly="readonly" type="date" id="p1" name="tdate" value="<?php
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
				<div class="column">
						<label>CUSTOMER CODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" class='s' required onchange=reload0(this.form) id="p" name="cc" list="codelist" value="<?php if(isset($_GET['ccode'])){
							echo $_GET['ccode'];
							}?>"/>
				</div><br><br>
				<div class="column">
						<label>PART NUMBER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text"  class='s' required onchange=reload(this.form) id="p2" name="pn" list="partlist" value="<?php if(isset($_GET['partnumber'])){echo $_GET['partnumber'];} ?>"/>
				</div>
				<div class="column">
						<label>SELECT DC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text"  class='s' required onchange=reloaddc(this.form) id="pdc" name="pdc" list="dclist" value="<?php if(isset($_GET['pdc'])){echo $_GET['pdc'];} ?>"/>
				</div>
					<div class="column">
						<label>CUSTOMER NAME&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" class='s' readonly='readonly' required  id="p3" name="cname" value="<?php echo $t1;?>"/>
						</div>
					<br><br>
					<div class="column">
						<label>CUSTOMER PO NO&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text"  readonly='readonly' id="p4" name="cpono"  <?php if(isset($_GET['cpo'])){echo "value=".$_GET['cpo'];}else{echo "value='$t2'";}?>>
					</div>
					<?php
						$br="";$br1="<br><br>";$dir=0;
						if(isset($_GET['partnumber']) && $_GET['partnumber']!=""){
						$rat = $_GET['partnumber'];
						$res = $con->query("SELECT pnum FROM `pn_st` WHERE invpnum='$rat' and stkpt='FG For Invoicing' and n_iter=1");
						$c = $res->num_rows;
							if($c>1)
							{
								$dir=1;
								$br="<br><br>";
								$br1="";
								echo '<datalist id="pnlist">';
								echo"<option value=''>Select one</option>";
								while ($row = mysqli_fetch_array($res))
								{
									if($_GET['partnumber']==$row['pnum'])
										echo "<option selected value='".$row['pnum']."'>".$row['pnum']."</option>";
									else
										echo "<option value='".$row['pnum']."'>".$row['pnum']."</option>";
								}
								echo '</datalist>';
								if(isset($_GET['pn']))
								{
									$tmp=$_GET['pn'];
								}
								else{$tmp="";}
								echo '<div class="column">
									<label>PARTS FROM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
									<input type="text" id="pn" list="pnlist" required onchange=reloadpn(this.form) name="pn1"  value="'.$tmp.'"/></div>';
							}
						}
					?>
					<div class="column">
						<label>AVAILABLE QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" required readonly='readonly' id="p7" name="avlqty" min="1"  value="<?php 
							$t="";
								if(isset($_GET['partnumber']) && isset($_GET['pdc']) && $_GET['partnumber']!="" && $_GET['pdc']!=""){
								$rat = $_GET['partnumber'];
								$rat1 = $_GET['pdc'];
								$query = "SELECT qty/bom AS avl FROM (SELECT ( SELECT qty-(SELECT SUM(w) as used FROM ( SELECT qty,bom,qty*bom AS w FROM `inv_det_dcinfo` LEFT JOIN m17 ON inv_det_dcinfo.pnum=m17.pnum WHERE dcno='$rat1') AS T) AS used FROM dc_det WHERE dc_det.dcnum='$rat1') AS qty,bom FROM m17 WHERE pnum='$rat') AS t";
								//echo "SELECT qty/bom AS avl FROM (SELECT ( SELECT qty-(SELECT SUM(w) as used FROM ( SELECT qty,bom,qty*bom AS w FROM `inv_det_dcinfo` LEFT JOIN m17 ON inv_det_dcinfo.pnum=m17.pnum WHERE dcno='$rat1') AS T) AS used FROM dc_det WHERE dc_det.dcnum='$rat1') AS qty,bom FROM m17 WHERE pnum='$rat') AS t";
								$result2 = $con->query($query);
								$row2 = mysqli_fetch_array($result2);
								$t=round($row2['avl']);
								echo $t;
								if($t=="" && $t!=0)
								{
									echo "0";
								}
							}
						?>">
					</div>
					<div class="column">
						<label>INVOICE QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="number" required <?php if($t==""){echo "readonly";} ?> id="p5" min="<?php if($t==""){echo 0;}else{echo 1;} ?>" max="<?php if($t==""){echo 0;}else{echo $t;} ?>" required name="tiqty">
					</div>
					<?php echo $br1; ?>
					<div class="column">
						<label>Mode Of Dispatch&nbsp;&nbsp;</label>
						<input type="text" id="p6" name="mod" readonly value="<?php echo $t3; ?>"/>
					</div>
					<?php echo $br; ?>
					<div class="column">
						<label>MODE OF TRANSPORT&nbsp;</label>
						<input type="text"  id="tm" name="tm" required value="<?php echo $t4; ?>"/>
					</div>
					<div class="column">
						<label>DISTANCE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text"  id="dis" name="dis"  min="1" value="<?php
						echo $t5;
						?>">
					</div>
					<?php echo $br1; ?>
					<div class="column">
						<label>VEHICLE NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" <?php
						if($t3=="COURIER")
						{
							echo 'required maxlength="13" pattern="^[A-Z]{2}-\d{2}-[A-Z]{1}.-\d{4}$" onkeyup="format()" placeholder="TN-99-AA-1111"';
							//echo 'value="COURIER" readonly';
						}
						else
						{
							echo 'required maxlength="13" pattern="^[A-Z]{2}-\d{2}-[A-Z]{1}.-\d{4}$" onkeyup="format()" placeholder="TN-99-AA-1111"';
						}
						?> id="vno" name="vno">
					</div>
				</div>
				<br><br>
				<div class="column1">
					<input type="submit" name="submit" value="CREATE INVOICE">
				</div>
				</form>
			</div>
	</body>
</html>
		