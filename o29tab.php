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
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="100" || $_SESSION['user']=="115" || $_SESSION['user']=="117")
	{
		$id=$_SESSION['user'];
		$activity="RETURN REWORK REPORT";
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
$con=mysqli_connect('localhost','root','Tamil','storedb');
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
		<h4 style="text-align:center"><label>RETURNED MATERIAL FROM CUSTOMER </label></h4><br>
		<form action="weeklycommitadd.php"  method="post" enctype="multipart/form-data">
			<br>
		<section>
			<div id="wrapper">
<table align="center" cellspacing=2 cellpadding=5  id="data_table" border=1>
<tr>
<th>RIN NUMBER</th>
<th>DATE</th>
<th>PART NUMBER</th>
<th>CUSTOMER</th>
<th>OUR INVOICE</th>
<th>QTY</th>
<th>IN STORES</th>
</tr>
<?php
$r=0;$tt=0;
$query = "SELECT week FROM `d19`";
$result = $con->query($query);
$row = mysqli_fetch_array($result);
$d=$row['week'];
	$result = mysqli_query($con,"SELECT * FROM `rin_receipt` WHERE rin_status='0000-00-00'");
	$r=$result->num_rows;
	while($row = mysqli_fetch_array($result))
	{
		echo "<tr><td>".$row['week']."</td><td>".$row['pnum']."</td><td>".$row['foremac']."</td><td>".$row['qty']."</td><td>".$row['issuedqty']."</td><td><a href='weeklycommitadd.php?rid=".$row['rid']."'>UPDATE</a></td><td><a href='wcdel.php?rid=".$row['rid']."'>DELETE</a></td></tr>";
	}
?>
<?php
if($r>0)
{
	echo '<tr><td colspan="4"></td><td>TOTAL ACHIVED : </td><td>';
	echo'</td><td><button type="submit" name="add"><a>ADD ROWS<a></button></td></tr>';
	echo '</table><br>';
	echo'<div class="form-group"><button type="submit"  name="place" style="margin-left:45%">UPDATE</button></div>';
}
else
{
	echo '<tr><td colspan="5">PLEASE UPDATE WEEKLY COMMIT</td><td><button type="submit" name="add"><a>ADD ROWS<a></button></td></tr></table><br>';
}
?>
</div>
</section>
</div>
<br>
</form>
</br>