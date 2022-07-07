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
	if($_SESSION['access']=="FI" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="FI REPORT PRINTING";
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
	<link rel="stylesheet" type="text/css" href="design.css">
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
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div align="right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>FINAL INSPECTION REPORT PRINTING</label></h4>
		<div class="divclass">
	<form action="fip.php"  method="post">		
	<br>
	<?php
		$ccode="";$fin="";$pnum="";$rcno="";
		$con = mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		$result = $con->query("SELECT invno from inv_det order by invno");
		echo"<datalist id='combo-options'>";
		while($row = mysqli_fetch_array($result))
		{
			echo "<option value='" . $row['invno'] . "'>" . $row['invno'] ."</option>";
		}
		echo"</datalist>";
		if(isset($_GET['invno']) && $_GET['invno']!="")
		{
			$inv=$_GET['invno'];
			$result = $con->query("SELECT DISTINCT pn,ccode from inv_det where invno='$inv'");
			//echo "SELECT DISTINCT pn,ccode from inv_det where invno='$inv'";
			$row = mysqli_fetch_array($result);
			$pnum=$row['pn'];
			$ccode=$row['ccode'];
		}
	?>
	<script>
			function reload(form)
			{
				var p4 = document.getElementById("from").value;
				self.location=<?php echo"'fireport.php?invno='"?>+p4;
			}
	</script>
	<div>
		<label>INVOICE NUMBER</label>
		<input type="text" required name="from" id="from" onchange=reload(this.form) list="combo-options" value="<?php if(isset($_GET['invno'])){echo $_GET['invno'];} ?>"/>
	</div>
	<br>
	<div>
		<label>PART NUMBER</label>
		<input type="text" required name="pn" id="pn" readonly value="<?php if(isset($_GET['invno'])){echo $pnum;} ?>"/>
	</div>
	<br>
	<div>
		<label>CUSTOMER CODE</label>
		<input type="text" required readonly name="cc" value="<?php if(isset($_GET['invno'])){echo $ccode;} ?>"/>
	</div>
	<br>
	<div>
		<label>ISSUE NUMBER</label>
		<input type="text" required name="in" />
	</div>
	<br>
	<br>
		<input type="Submit" name="submit" value="PRINT"/>
	
</div>
</body>
</html>