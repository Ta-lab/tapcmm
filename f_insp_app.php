<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	header("Location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="100" || $_SESSION['access']=="FI")
	{
		$id=$_SESSION['user'];
		$activity="FINAL INSPECTION APPROVAL";
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
<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(isset($_POST['update']))
{
	header("location: inputlink.php");
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
<script>
			function reload0(form)
			{
				var p1 = document.getElementById("scode").value;
				self.location=<?php echo"'i71.php?scode='"?>+p1;
			}
		</script>
</head>
<body>
	<div class="container-fluid">
	<div style="float:right">
			<a href="index.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
			<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>FINAL INSPECTION APPROVAL</label></h4><br>
		<form action="f_insp_app_db.php"  method="post" enctype="multipart/form-data">
			<br>
		<section>
			<div id="wrapper">
<table align="center" cellspacing=2 cellpadding=5  id="data_table" border=1>
<tr>
<th>DATE</th>
<th>R C NUMBER </th>
<th>PART NUMBER</th>
<th>QUANTITY</th>
<th>APPROVAL</th>
<th>REJECTION</th>
<th>APPROVED BY</th>
</tr>
<?php
$result = mysqli_query($con,"SELECT * FROM `f_insp` WHERE apby=''");
$r=$result->num_rows;
while($row = mysqli_fetch_array($result))
{
	echo "<tr><td>".$row['date']."</td><td>".$row['prcno']."</td><td>".$row['pnum']."</td><td>".$row['qty']."</td>";
	echo "<td><a onclick='Document.getElementById(data_table).style.display=none;' href='f_insp_app_db.php?sno=".$row['sno']."&stat=T'>APPROVE</a></td>";
	echo "<td><a onclick='Document.getElementById(data_table).style.display=none;' href='f_insp_app_db.php?sno=".$row['sno']."&stat=F'>REJECT</a></td>";
	echo "<td>".$row['apby']."</td></tr>";
}
?>
</table>
</section>
</div>
</form>
</br>