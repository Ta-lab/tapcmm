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
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="100" || $_SESSION['access']=="FG For Invoicing")
	{
		$id=$_SESSION['user'];
		$activity="INVOICE CORRECTION APPROVAL";
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
		<h4 style="text-align:center"><label>INVOICE CORRECTION APPROVAL</label></h4><br>
		<form action="inv_c_app_db.php"  method="post" enctype="multipart/form-data">
			<br>
		<section>
			<div id="wrapper">
<table align="center" cellspacing=2 cellpadding=5  id="data_table" border=1>
<tr>
<th>INVOICE NO</th>
<th>INVOICE DATE</th>
<th>C_CODE</th>
<th>PART NUMBER</th>
<th>QUANITY</th>
<th>REASON</th>
<th>APPROVAL STATUS</th>
<th>APPROVED BY</th>
</tr>
<?php
$result = mysqli_query($con,"SELECT * FROM `inv_correction` WHERE status='F'");
$r=$result->num_rows;
while($row = mysqli_fetch_array($result))
{
	echo "<tr><td>".$row['invno']."</td><td>".$row['invdt']."</td><td>".$row['ccode']."</td><td>".$row['pn']."</td><td>".$row['qty']."</td><td>".$row['reason']."</td><td>";
	if($row['apby']!="")
	{
		if($_SESSION['access']=="ALL" || $_SESSION['user']=="100")
		{
			echo "<a href='inv_c_app_db.php?invno=".$row['invno']."&stat=0'>APPROVED</a></td>";
		}
		else
		{
			echo "APPROVED</td>";
		}
	}
	else
	{
		if($_SESSION['access']=="ALL" || $_SESSION['user']=="100")
		{
			if($row['reason']=="MATERIAL RETURNED")
			{
				echo "<a href='inv_c_app_db.php?invno=".$row['invno']."&stat=1&ret=1'>APPROVE</a></td>";
			}
			else
			{
				echo "<a href='inv_c_app_db.php?invno=".$row['invno']."&stat=1'>APPROVE</a></td>";
			}
		}
		else
		{
			echo "NOT APPROVED</td>";
		}
	}
	echo "<td>".$row['apby']."</td></tr>";
}
?>
</table>
</section>
</div>
<br>
<div class="form-group"><button type="submit"  name="submit" style="margin-left:65%">UPDATE</button></div>
</form>
</br>