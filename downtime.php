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
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="100")
	{
		$id=$_SESSION['user'];
		$activity="DOWN TIME UPDATION";
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
		<h4 style="text-align:center"><label>DOWN TIME UPDATION</label></h4><br>
		<form action="downtimeadd.php"  method="post" enctype="multipart/form-data">
			<br>
		<section>
			<div id="wrapper">
<table align="center" cellspacing=2 cellpadding=5  id="data_table" border=1>
<tr>
<th>AREA</th>
<th>MACHINE ID</th>
<th>FROM DATE</th>
<th>FROM TIME</th>
<th>TO DATE</th>
<th>TO TIME</th>
<th>ADD/DELETE_ROW</th>
</tr>
<?php
$r=0;$tt=0;
	$result = mysqli_query($con,"SELECT * FROM downtime");
	$r=$result->num_rows;
	while($row = mysqli_fetch_array($result))
	{
		echo "<tr><td>".$row['area']."</td><td>".$row['machine']."</td><td>".$row['fdate']."</td><td>".$row['ftime']."</td><td>".$row['tdate']."</td><td>".$row['ttime']."</td><td><a href='dtdel.php?rid=".$row['rid']."'>DELETE</a></td></tr>";
		$datetime1 = $row['fdate'].' '.$row['ftime'];
		$datetime2 = $row['tdate'].' '.$row['ttime'];
		$dateDiff = intval((strtotime($datetime2)-strtotime($datetime1))/60);
		$tt+=$dateDiff;
		//echo $tt."-";
	}
?>
<?php
if($r>0)
{
	echo '<tr><td colspan="4"></td><td>TOTAL DOWN TIME : </td><td>';
	$days=floor(floor($tt/60)/24);
	$hrs=floor($tt/60)%24;
	$mins=($tt)%60;
	if($days>0)
	{
		echo $days.' days ';
	}
	if($hrs>0)
	{
		echo $hrs.' Hrs ';
	}
	if($mins>0)
	{
		echo $mins.' Mins';
	}
	echo '</td><td><button type="submit" name="add"><a>ADD ROWS<a></button></td></tr>';
	echo '</table><br>';
	echo'<div class="form-group"><button type="submit"  name="place" style="margin-left:45%">UPDATE</button></div>';
}
else
{
	echo '<tr><td colspan="6">UPDATE MACHINE DOWNTIME (IF ANY)</td><td><button type="submit" name="add"><a>ADD ROWS<a></button></td></tr></table><br>';
}
?>
</div>
</section>
</div>
<br>
</form>
</br>