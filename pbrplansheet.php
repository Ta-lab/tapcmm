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
	if($_SESSION['user']=="123" || $_SESSION['user']=="100")
	{
		$id=$_SESSION['user'];
		$activity="PBR PLAN SHEET";
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
				self.location=<?php echo"'pbrplansheet.php?scode='"?>+p1;
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
		<h4 style="text-align:center"><label>PBR PLAN SHEET TEST PAGE</label></h4><br>
		<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'PBR SHEET')" value="Export to Excel">
		</div>
		<form action="pbrplansheet.php"  method="post" enctype="multipart/form-data">
			<br>
		<section>
			<div id="wrapper">
<table align="center" cellspacing=2 cellpadding=5  id="testTable" border=1>
<?php
$r=0;$tt=0;
$query = "SELECT week FROM `d19`";
$result = $con->query($query);
$row = mysqli_fetch_array($result);
$d=$row['week'];
$dateTime = new DateTime();
$dateTime->setISODate(substr($d,0,4),substr($d,5,2));
$dateTime->modify('-7 days');
$day1 = $dateTime->format('Y-m-d');
$sd=$day1;
//echo $day1;
$dayresult1 = mysqli_query($con,"SELECT week,part,commit,IF(pr IS NULL,0,pr) as pr FROM `planvsactual` LEFT JOIN (SELECT pnum,SUM(partreceived) AS pr FROM `d12` WHERE date='$day1' AND stkpt LIKE 'FG%' AND partreceived!='' AND username!='' AND pnum!='' GROUP BY pnum) AS T ON planvsactual.part=T.pnum WHERE week='$d'");
$dateTime->modify('+1 days')->format('Y-m-d');
$day2 = $dateTime->format('Y-m-d');
$dayresult2 = mysqli_query($con,"SELECT week,part,commit,IF(pr IS NULL,0,pr) as pr FROM `planvsactual` LEFT JOIN (SELECT pnum,SUM(partreceived) AS pr FROM `d12` WHERE date='$day2' AND stkpt LIKE 'FG%' AND partreceived!='' AND username!='' AND pnum!='' GROUP BY pnum) AS T ON planvsactual.part=T.pnum WHERE week='$d'");
$dateTime->modify('+1 days')->format('Y-m-d');
$day3 = $dateTime->format('Y-m-d');
$dayresult3 = mysqli_query($con,"SELECT week,part,commit,IF(pr IS NULL,0,pr) as pr FROM `planvsactual` LEFT JOIN (SELECT pnum,SUM(partreceived) AS pr FROM `d12` WHERE date='$day3' AND stkpt LIKE 'FG%' AND partreceived!='' AND username!='' AND pnum!='' GROUP BY pnum) AS T ON planvsactual.part=T.pnum WHERE week='$d'");
$dateTime->modify('+1 days')->format('Y-m-d');
$day4 = $dateTime->format('Y-m-d');
$dayresult4 = mysqli_query($con,"SELECT week,part,commit,IF(pr IS NULL,0,pr) as pr FROM `planvsactual` LEFT JOIN (SELECT pnum,SUM(partreceived) AS pr FROM `d12` WHERE date='$day4' AND stkpt LIKE 'FG%' AND partreceived!='' AND username!='' AND pnum!='' GROUP BY pnum) AS T ON planvsactual.part=T.pnum WHERE week='$d'");
$dateTime->modify('+1 days')->format('Y-m-d');
$day5 = $dateTime->format('Y-m-d');
$dayresult5 = mysqli_query($con,"SELECT week,part,commit,IF(pr IS NULL,0,pr) as pr FROM `planvsactual` LEFT JOIN (SELECT pnum,SUM(partreceived) AS pr FROM `d12` WHERE date='$day5' AND stkpt LIKE 'FG%' AND partreceived!='' AND username!='' AND pnum!='' GROUP BY pnum) AS T ON planvsactual.part=T.pnum WHERE week='$d'");
$dateTime->modify('+1 days')->format('Y-m-d');
$day6 = $dateTime->format('Y-m-d');
$dayresult6 = mysqli_query($con,"SELECT week,part,commit,IF(pr IS NULL,0,pr) as pr FROM `planvsactual` LEFT JOIN (SELECT pnum,SUM(partreceived) AS pr FROM `d12` WHERE date='$day6' AND stkpt LIKE 'FG%' AND partreceived!='' AND username!='' AND pnum!='' GROUP BY pnum) AS T ON planvsactual.part=T.pnum WHERE week='$d'");
$dateTime->modify('+1 days')->format('Y-m-d');
$day7 = $dateTime->format('Y-m-d');
$ed=$day7;
echo '<tr>
<th>WEEK</th>
<th>PART NUMBER</th>
<th>COMMIT QTY</th>
<th>'.$day1.'</th>
<th>'.$day2.'</th>
<th>'.$day3.'</th>
<th>'.$day4.'</th>
<th>'.$day5.'</th>
<th>'.$day6.'</th>
<th>'.$day7.'</th>
<th>TOTAL</th>
<th>PERCENT</th>
</tr>';
$dayresult7 = mysqli_query($con,"SELECT week,part,commit,IF(pr IS NULL,0,pr) as pr FROM `planvsactual` LEFT JOIN (SELECT pnum,SUM(partreceived) AS pr FROM `d12` WHERE date='$day7' AND stkpt LIKE 'FG%' AND partreceived!='' AND username!='' AND pnum!='' GROUP BY pnum) AS T ON planvsactual.part=T.pnum WHERE week='$d'");
$result = mysqli_query($con,"SELECT week,part,commit,IF(pr IS NULL,0,pr) as pr FROM `planvsactual` LEFT JOIN (SELECT pnum,SUM(partreceived) AS pr FROM `d12` WHERE date>='$sd' AND date<='$ed' AND stkpt LIKE 'FG%' AND partreceived!='' AND username!='' AND pnum!='' GROUP BY pnum) AS T ON planvsactual.part=T.pnum  WHERE week='$d'");
while($row1 = mysqli_fetch_array($dayresult1))
{
	$row2 = mysqli_fetch_array($dayresult2);
	$row3 = mysqli_fetch_array($dayresult3);
	$row4 = mysqli_fetch_array($dayresult4);
	$row5 = mysqli_fetch_array($dayresult5);
	$row6 = mysqli_fetch_array($dayresult6);
	$row7 = mysqli_fetch_array($dayresult7);
	$row = mysqli_fetch_array($result);
	echo "<tr><td>".$row1['week']."</td><td>".$row1['part']."</td><td>".$row1['commit']."</td><td>".$row1['pr']."</td><td>".$row2['pr']."</td><td>".$row3['pr']."</td><td>".$row4['pr']."</td><td>".$row5['pr']."</td><td>".$row6['pr']."</td><td>".$row7['pr']."</td><td>".$row['pr']."</td><td>".round(($row['pr']/$row['commit'])*100)." %</td></tr>";
}
?>
</div>
</section>
</div>
<br>
</form>
</br>