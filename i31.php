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
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="100")
	//if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="STOCK INITIALIZATION";
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
	<link rel="stylesheet" type="text/css" href="design.css">
</head>
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
		<h4 style="text-align:center"><label>DUMMY RC INITIALISATION</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action='i31db.php'>	
			</br>
			<div>
				<label>DATE</label>
					<input type="date" id="tdate" name="tdate" value="<?php
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
			<br>
			<div>
				<label>SELECT TYPE</label>
					<input type="text" list="combo-options" name ="type" id="type" onchange="reload(this.form)" value="<?php
					if(isset($_GET['type']))
					{
						echo $_GET['type'];
					}
					?>"/>
				<datalist id='combo-options'>
					<option value='STOCKING POINT'>STOCKING POINT</option>";
					<option value='OPERATION'>OPERATION</option>";
					<option value='OPEN PO'>OPEN PO</option>";
					<option value='Stores'>Stores</option>";
				</datalist>";
			</div>
		<script>
		function reload(form)
		{	
			var val=document.getElementById("type").value;
			var s = document.getElementById("tdate").value;
			self.location='i31.php?tdate='+s+'&type='+val;
		}
		</script>
			<br>
			
	<?php
	if(isset($_GET['type']) && ($_GET['type']=="STOCKING POINT" || $_GET['type']=="OPERATION"))
	{
		$rat=$_GET['type'];
		echo '<div><label>SELECT AREA</label><select name ="area" id="area" onchange="reload2(this.form)"><option value="">Select Area</option>';
		$query2 = "SELECT DISTINCT operation FROM m11 where opertype='$rat'";
		$result2 = $con->query($query2);
		while ($row2 = mysqli_fetch_array($result2)) 
		{
			 if($_GET['area']==$row2['operation'])
				echo "<option selected value='" . $row2['operation'] . "'>" . $row2['operation'] ."</option>";
			else
				echo "<option value='" . $row2['operation'] . "'>" . $row2['operation'] ."</option>";
		}
		echo "</select></h1></div><br>";
	}
	?>
<script>
function reload2(form)
{	
	var val=form.area.options[form.area.options.selectedIndex].value; 
	self.location=<?php if(isset($_GET['type']))echo"'i31.php?tdate=".$_GET['tdate']."&type=".$_GET['type']."&area='";?> + val ;
}
</script>
	<?php
		if(isset($_GET['type']) && ($_GET['type']=="STOCKING POINT" || $_GET['type']=="OPERATION") && isset($_GET['area']) && $_GET['area']!="")
		{
			$area=$_GET['area'];
				$query = "SELECT DISTINCT pnum from m12 where operation='$area'";
				$result = $con->query($query); 
				echo "";
				echo"<datalist id='combo-parts'>";
					while ($row = mysqli_fetch_array($result))
						{
							echo "<option value='".$row['pnum']."'>".$row['pnum']."</option>";
						}
				echo"</datalist>";
			echo'<div><label>PART NUMBER</label><input type="text" list="combo-parts" name ="pnum" id="pnum" value=""/></div>';
		}
		else
		{
			
			//MATRAIL CODE
			$query = "SELECT DISTINCT m_code as mcode from m13";
			$result = $con->query($query); 
			echo "";
			echo"<datalist id='combo-mcodes'>";
				while ($row = mysqli_fetch_array($result))
					{
						echo "<option value='".$row['mcode']."'>".$row['mcode']."</option>";
					}
			echo"</datalist>";
			echo'<div><label>MATERIAL CODE</label><input type="text" list="combo-mcodes" name ="mcodes" id="mcodes" placeholder="Type Material Code" value=""/></div><br>';
			
			//MATERIAL DESCRIPTION
			$query = "SELECT DISTINCT rmdesc from m13";
			$result = $con->query($query); 
			echo "";
			echo"<datalist id='combo-rmdesc'>";
				while ($row = mysqli_fetch_array($result))
					{
						echo "<option value='".$row['rmdesc']."'>".$row['rmdesc']."</option>";
					}
			echo"</datalist>";
			echo'<div><label>MATERIAL DESCRIPTION</label><input type="text" list="combo-rmdesc" name ="rmdesc" id="rmdesc" placeholder="Type RM Description"  value=""/></div><br>';
			
			
			//SUPPLIER CODE
			$con=mysqli_connect('localhost','root','Tamil','purchasedb');
			$query = "SELECT DISTINCT scode from supplier";
			$result = $con->query($query); 
			echo "";
			echo"<datalist id='combo-scode'>";
				while ($row = mysqli_fetch_array($result))
					{
						echo "<option value='".$row['scode']."'>".$row['scode']."</option>";
					}
			echo"</datalist>";
			echo'<div><label>SUPPLIER CODE</label><input type="text" list="combo-scode" name ="scode" id="scode" placeholder="Type Supplier Code"  value=""/></div>';
		}
		
		if(isset($_GET['area']) && ($_GET['area']=="CNC Machine" || $_GET['area']=="Straitening/Shearing"))
		{
			//MATERIAL DESCRIPTION IF A RC
			$query = "SELECT DISTINCT rmdesc from m13";
			$result = $con->query($query); 
			echo "";
			echo"<datalist id='combo-rmdesc'>";
				while ($row = mysqli_fetch_array($result))
					{
						echo "<option value='".$row['rmdesc']."'>".$row['rmdesc']."</option>";
					}
			echo"</datalist>";
			echo'<br><div><label>MATERIAL DESCRIPTION</label><input type="text" list="combo-rmdesc" name ="rmdesc" id="rmdesc" placeholder="Type RM Description"  value=""/></div>';
		}
	?>
	<br>
			<div>
				<label>QUANTITY</label>
					<input type="text" id="qty" name="qty" required placeholder="Enter Initialize Quantity">
			</div>
			<br>
			<div>
					<input type="Submit" name="submit" id="submit"  value="SUBMIT" onclick="myFunction()"/>
			</div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
</script>
		</form>
	</div>
</body>
</html>