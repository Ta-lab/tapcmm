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
		$activity="PURCHASE ORDER PARTCULAR DETAIL";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
		$con=mysqli_connect('localhost','root','Tamil','purchasedb');
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
</head>
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
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>INSERT PURCHASE DETAIL</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
	<form method="POST" action='i71db.php'>	
		<div id="stylized" class="myform">
			<div class="column" style="display:none">
				<label>SUPPLIER CODE&nbsp;&nbsp;:</label>
					<input type="text" name ="scode" id="scode" readonly value="<?php
					if(isset($_GET['scode']))
					{
						echo $_GET['scode'];
					}
					?>"/>
			</div>
			
			<div class="column">
				<label>PO NUMBER &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  name ="ponum" id="ponum" readonly value="<?php
					if(isset($_GET['ponum']))
					{
						echo $_GET['ponum'];
					}
					?>"/>
			</div>
			
			<div class="column">
				<label>MATRL CODE:</label>
					<select name ="mcode" id="mcode" onchange="reload2(this.form)">
					<option value=''>Select one</option>
						<?php			
							if(isset($_GET['scode'])) 
							{	 
								$scode=$_GET['scode'];
								$query2 = "SELECT DISTINCT mcode FROM pomaster where scode='$scode'";
								$result2 = $con->query($query2);
								while ($row2 = mysqli_fetch_array($result2)) 
								{
									 if($_GET['mcode']==$row2['mcode'])
										echo "<option selected value='".$row2['mcode']."'>".$row2['mcode']."</option>";
									else
										echo "<option value='".$row2['mcode']."'>".$row2['mcode']."</option>";
								}
                    		}
						echo "</select></h1>";
						?>
						<script>
						function reload2(form)
						{	
							var val=form.mcode.options[form.mcode.options.selectedIndex].value; 
							self.location=<?php if(isset($_GET['scode']))echo"'i71add.php?ponum=".$_GET['ponum']."&scode=".$_GET['scode']."&mcode='";?> + val ;
						}
						</script>
			</div>
			<div class="column">
				<label>MDESC - PRINT:</label>
					<input type="text" name ="mdescp" readonly required id="mdescp" value="<?php
							$hsnc="";$rate="";$uom="";
							if(isset($_GET['mcode']))
							{	
								$mcode=$_GET['mcode'];
								$scode=$_GET['scode'];
								$query1 = "SELECT DISTINCT mdesc,mdesc2,hsnc,rate,uom from pomaster where mcode='$mcode' and scode='$scode'";
								$result1 = $con->query($query1);  
								$row2 = mysqli_fetch_array($result1);
								$hsnc=$row2['hsnc'];
								$rate=$row2['rate'];
								$uom=$row2['uom'];
								echo $row2['mdesc2'];		
							}
						?>"/>
			</div>
			<br><br>
			
			<div class="column">
				<label>MATERIAL DESC&nbsp;&nbsp;:</label>
					<input type="text" name ="mdesc" readonly required id="mdesc" value="<?php
							$hsnc="";$rate="";$uom="";
							if(isset($_GET['mcode']))
							{	
								$mcode=$_GET['mcode'];
								$scode=$_GET['scode'];
								$query1 = "SELECT DISTINCT mdesc,hsnc,rate,uom from pomaster where mcode='$mcode' and scode='$scode'";
								$result1 = $con->query($query1);  
								$row2 = mysqli_fetch_array($result1);
								$hsnc=$row2['hsnc'];
								$rate=$row2['rate'];
								$uom=$row2['uom'];
								echo $row2['mdesc'];		
							}
						?>"/>
			</div>
			
			<div class="column">
				<label>HSN CODE&nbsp;&nbsp;:</label>
					<input type="text" id="hsnc" name="hsnc" required readonly value="<?php echo $hsnc;?>">
			</div>
			
			<div class="column">
				<label>DUE DATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="date" id="dd" name="dd" onkeypress="enable()" required>
			</div>
			<br><br>
			<div class="column">
				<label>RATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text" id="rate" name="rate" required readonly value="<?php echo $rate;?>">
			</div>
			
			<div class="column">
				<label>U O M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text" name ="uom" id="uom" readonly  value="<?php echo $uom;?>"/>
			</div>
			
			<div class="column">
				<label>QUANTITY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text" name="qty" onkeypress="enable()" required placeholder="Enter Order Quantity"/>
			</div>
			<BR><BR>
			<div>
					<input type="Submit" name="submit" id="submit"  value="ADD PARTICULAR" onclick="myFunction()"/>
			</div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
function enable(){
	document.getElementById('submit').style.visibility = 'visible';
}
</script>
		</form>
	</div>
</body>
</html>