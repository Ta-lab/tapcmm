<?php
session_start();
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	//header("Location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="Stores" || $_SESSION['access']=="ALL" || $_SESSION['user']=="100")
	{
		$id=$_SESSION['user'];
		$activity="RE-PRINTING A ROUTE CARD";
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
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div align="right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>A - ROUTE CARD RE-PRINTING</label></h4>
		<div class="divclass">
		<?php
			if(isset($_POST['rcno']))
			{
				$r=substr($_POST['rcno'],0,1);
				if($r=="A")
				{
					header("location: re_vi12_1.php?rcno=$_POST[rcno]");
				}
				else
				{
					header("location: rep_vi12_1.php");
				}
			}
		?>
	<form action="rep_vi12_1.php"  method="post">		
	<br><br><br><br><br>
	<div>
		<label>A ROUTE CARD NUMBER</label>
		<input type="text" name="rcno" value=""/>
	</div>
	<br>
	<br>
		<input type="Submit" name="submit" value="ENTER"/>
	
</div>
</body>
</html>