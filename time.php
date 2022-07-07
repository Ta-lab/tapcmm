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
	if($_SESSION['access']=="ALL" && $_SESSION['user']=="101")
	{
		$id=$_SESSION['user'];
		$activity="INVOICE/DC TIMING MASTER";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
	}
	else if($_SESSION['access']=="ALL")
	{
		header("location: inputlink.php?msg=13");
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
?><!DOCTYPE html>
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
		<h4 style="text-align:center"><label>INV-DC TIMING</label></h4>
		<div class="divclass">
	<form action="timedb.php"  method="post">		
	<br><br><br><br><br>
	<div>
		<label>INV CLOSING TIME :</label>
		<input type="time" name="inv" value=""/>
	</div>
	<br>
	<div>
		<label>DC CLOSING TIME&nbsp; :</label>
		<input type="time" name="dc" value=""/>
	</div>
	<br>
	<div>
		<label>PASSWORD&nbsp; :</label>
		<input type="text" name="pw" value=""/>
	</div>
	<br>
		<input type="Submit" name="submit" value="ENTER"/>
	</form>
</div>
</body>
</html>