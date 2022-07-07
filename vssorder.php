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
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="111" || $_SESSION['user']=="127" || $_SESSION['user']=="109")
	{
		$id=$_SESSION['user'];
		$activity="WEEKLY COMMIT UPDATION";
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
		<h4 style="text-align:center"><label>KANBAAN AND REGULAR PART UPDATION</label></h4><br>
		<form action="vssorderadd.php"  method="post" enctype="multipart/form-data">
			<br>
		<section>
			<div id="wrapper">
<table align="center" cellspacing=2 cellpadding=5  id="data_table" border=1>
<tr>
<th>PART NUMBER</th>
<th>TYPE</th>
<th>MONTHLY DEMAND</th>
<th> VMI / FINAL </th>
<th> SF STOCK </th>
<th>UPDATE</th>
<th>ADD/DELETE_ROW</th>
</tr>
<?php
$r=0;$tt=0;
	$result = mysqli_query($con,"SELECT * FROM demandmaster");
	while($row = mysqli_fetch_array($result))
	{
		echo "<tr><td>".$row['pnum']."</td><td>".$row['type']."</td><td>".$row['monthly']."</td><td>".$row['vmi_fg']."</td><td>".$row['sf']."</td><td><a href='vssorderadd.php?rid=".$row['rno']."'>UPDATE</a></td><td><a href='vodel.php?rid=".$row['rno']."'>DELETE</a></td></tr>";
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
	echo '<tr><td colspan="5">ADD MORE ROWS</td><td><button type="submit" name="add"><a>ADD ROWS<a></button></td></tr></table><br>';
}
?>
</div>
</section>
</div>
<br>
</form>
</br>