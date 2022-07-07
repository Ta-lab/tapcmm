<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['user']=="123" || $_SESSION['user']=="100" || $_SESSION['user']=="134")
	{
		$id=$_SESSION['user'];
		$activity="RECONCILIATION";
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
?><!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<script src = "js\excelreport.js"></script>
	<link rel="stylesheet" type="text/css" href="design1.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
		<h4 style="text-align:center"><label>RECONCILIATION MENU </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	
	<br><br>
	<div class="divclass">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
		
				if($_SESSION['user']=="100" || $_SESSION['user']=="123")
				{
					//echo'<div  align="center"> <a class="fontchange" href="reconcile.php" ><label> STOCKING POINT RECONCILIATION </label></a><br><br>';
					//echo'<a class="fontchange" href="operationreconcile.php" ><label> OPERATION RECONCILIATION </label></a><br><br>';
					
					echo'<div  align="center"> <a class="fontchange" href="i31.php" ><label> DUMMY RC INITIALIZATION </label></a><br><br>';
					
					//echo'<a class="fontchange" href="reconcile_report.php" ><label> RECONCILIATION REPORT </label></a><br><br>';
					//echo'<a class="fontchange" href="reconcile_approval.php" ><label> RECONCILIATION APPROVAL </label></a><br><br>';
					echo'<a class="fontchange" href="reconcile_report_dum.php" ><label> RECONCILIATION REPORT </label></a><br><br>';
					echo'<a class="fontchange" href="reconcile_report_fi.php" ><label>DETAIL RECONCILIATION REPORT </label></a><br><br>';
					
				}
				if($_SESSION['user']=="123" || $_SESSION['user']=="134")
				{
					echo'<div  align="center"> <a class="fontchange" href="reconcile_approval_dum.php" ><label> RECONCILIATION APPROVAL </label></a><br><br>';
					echo'<a class="fontchange" href="reconcile_report_dum.php" ><label> RECONCILIATION SUMMARY REPORT </label></a><br><br>';
					echo'<a class="fontchange" href="reconcile_report_fi.php" ><label>DETAIL RECONCILIATION REPORT </label></a><br><br>';
				}
		
				
			?>
	</div>
	
	
		<div>
		
		</div>
	
		
</body>
</html>

