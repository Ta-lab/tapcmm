<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$user=$_SESSION['user'];
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	header("Location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="121" || $_SESSION['access']=="FG For Invoicing")
	{
		$id=$_SESSION['user'];
		$activity="NPD INVOICE APPROVAL";
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
		<h4 style="text-align:center"><label>NPD PART INVOICE APPROVAL STATUS</label></h4><br>
		
		<center><label style="color:yellow";><p align="center" id="msg"></p></label></center>
		
		<form action="inv_c_app_db.php"  method="post" enctype="multipart/form-data">
			<br>
		<section>
			<div id="wrapper">
<table align="center" cellspacing=2 cellpadding=5  id="data_table" border=1>
<tr>
<th>REQUEST DATE</th>
<th>PART NUMBER</th>
<th>QTY</th>
<th>TYPE</th>
<th>APPROVAL STATUS</th>
<th>APPROVED BY</th>

<?php
if($_SESSION['user']!="121" && $_SESSION['access']=="NONE" || $_SESSION['user']=="109"){
	echo '<th>INVOICE</th>';
	echo '<th>CANCEL REQUEST</th>';
}
?>

<script>
var i = 0;
var txt = <?php

if(isset($_GET['msg']))
{
	switch($_GET['msg']){
	case "1":
        echo "' Message : PLEASE RAISE NPD PART INVOICE REQUEST... ';";
        break;
	case "2":
        echo "' Message : PLEASE WAIT FOR APPROVAL...REQUEST IS PENDING... ';";
        break;
	default:
        echo "' Message : WELCOME - Date-Time is ".date("d-m-Y / H:i")."...';";
	}
}
?>
var speed = 75;
window.onload = typeWriter();
function typeWriter() {
  if (i < txt.length) {
    document.getElementById("msg").innerHTML += txt.charAt(i);
    i++;
    setTimeout(typeWriter, speed);
  }
}
</script>


</tr>
<?php
$result = mysqli_query($con,"SELECT * FROM npd_invoicing WHERE invno='' AND cancelrequest=''");
$r=$result->num_rows;
while($row = mysqli_fetch_array($result))
{
	echo "<tr><td>".$row['reqdate']."</td><td>".$row['pnum']."</td><td>".$row['reqqty']."</td><td>".$row['submissiontype']."</td><td>";
	if($row['approvedby']!="")
	{
		if($_SESSION['access']=="NONE" && $_SESSION['user']=="121" || $_SESSION['user']=="123")
		{
			echo "<a href='npd_inv_app_db.php?pnum=".$row['pnum']."&appno=".$row['appno']."&stat=0'>APPROVED</a></td>";
		}
		else
		{
			echo "APPROVED</td>";
		}
	}
	else
	{
		if($_SESSION['access']=="NONE" && $_SESSION['user']=="121" || $_SESSION['user']=="123")
		{
			echo "<a href='npd_inv_app_db.php?pnum=".$row['pnum']."&appno=".$row['appno']."&stat=1'>APPROVE</a></td>";
		}
		else
		{
			echo "NOT APPROVED</td>";
		}
	}
	echo "<td>".$row['approvedby']."</td>";

	if($_SESSION['access']=="NONE" && $_SESSION['user']!="121" || $_SESSION['user']=="109")
	{
		if($row['approvedby']!="")
		{
		
			$id=$_SESSION['user'];
			mysqli_query($con,"UPDATE admin1 set status='1' where userid='$id'");
			echo "<td><a href='newinv1.php?appno=".$row['appno']."'>INVOICE</a></td>";
		}
		else
		{
			echo "<td>APPROVAL PENDING</td>";
		}
	}
	
	
	if($_SESSION['access']=="NONE" && $_SESSION['user']!="121" || $_SESSION['user']=="109" )
	{
		if($row['approvedby']=="")
		{
			echo "<td><a href='cancelnpdreq.php?appno=".$row['appno']."'>CANCEL</a></td>";
		}
		else
		{
			echo "<td>ALREADY APPROVED</td></tr>";
		}
	}
	
	
	
	

}
?>
</table>
</section>
</div>

<br>
<div style="float:right">
<?php
	if($_SESSION['user']!="121" && $_SESSION['user']!="123" )
	{
		echo "<a href='npd_inv_req.php'><label>RAISE NPD INVOICE REQUEST</a></label>";
	}
?>	
</div>



<br>
</form>
</br>