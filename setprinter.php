<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="PRINTER SELECTION";
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
<body>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
	<div align="right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>PRINTER SETTING</label></h4>
		<div class="divclass">
	<form action="setprinterdb.php"  method="post">		
	<br><br><br><br><br>
	<div>
	<label>PRINT TYPE</label>
		<?php					
			echo "<select name ='print'>";
			echo "<option value='1'>ONE BY ONE</option>";
			echo "<option value='1'>PRINT SERIALLY</option>";
			echo "</select>";
		?>
	</div>
	<br>
	<label>PRINT NAME</label>
		<?php
			echo "<select name ='port'>";
			echo "<option value='LPT3'>TVS MSP (IP:46)</option>";
			echo "<option value='LPT3'>TVS MSP (IP:55)</option>";
			echo "</select>";
		?>
	</div>
	<br><br><br><br><br><br><br><br><br><br><br>
	<br>
		<input type="Submit" name="submit" value="ENTER"/>
	
</div>
</body>
</html>