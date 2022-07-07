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
	if($_SESSION['user']=="108" || $_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C" ||  $_SESSION['access']=="ALL" || $_SESSION['access']=="ACCOUNTS")
	{
		$id=$_SESSION['user'];
		$activity="DC PRINTING";
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
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div align="right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>DC PRINTING</label></h4>
		<div class="divclass">
	<form action="inventory1_rm.php"  method="post">		
	<br><br><br><br><br>
	
	<?php 
	$query = "SELECT * FROM rmdc";
	$result = $con->query($query); 
	echo "";
		echo"<datalist id='combo-parts'>";
		while ($row = mysqli_fetch_array($result))
		{
			echo "<option value='".trim($row['dcnum'],"DC-")."'>".trim($row['dcnum'],"DC-")."</option>";
		}
		echo"</datalist>";
	?>
	<div>
		<label>ENTER DC NUMBER:</label>
		<input type="text" list="combo-parts" name="dc" value=""/>
	</div>
	<br>
	<br>
		<input type="Submit" name="submit" value="ENTER"/>
	
</div>
</body>
</html>