<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="Stores" ||$_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="TRACEABILITY REPORT";
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
	<div align="right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>TRACABILITY REPORT</label></h4>
		<div class="divclass">
	<form action="getinvoice.php"  method="post">
	<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
	<br><br><br><br><br>
	<div>
		<label>ENTER INVOICE</label>
		<input type="text" name="inv" value=""/>
		<input type="Submit" name="submit" value="ENTER"/>
		<?php
		if(isset($_POST['submit'])){
			$inv = $_POST['inv'];			
			header("Location: o15tab.php?inv=$inv");  
		}
		?>
		
	
	
	
</div>
</body>
</html>