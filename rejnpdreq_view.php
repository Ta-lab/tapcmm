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


<!DOCTYPE html>-
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
</head>
<body style="background-image: url('img/6.jpg');">

	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	
	<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	
	<h4 style="text-align:center"><label> NPD PARTS INVOICE REJECTION REASON </label></h4>
	
	<script>
		function preventback()
		{
			window.history.forward();
		}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
	</script>
	
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
	
	<?php
		if(isset($_POST['submit']))
		{
			$con=mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				die(mysqli_error());
			
			$appno = $_POST['appno'];
			$rejreason = $_POST['rejreason'];
			
			header("location: rejnpdreq.php?appno=$appno&rejreason=$rejreason");
		}
		?>
	
	
	<?php
		$appno = $_GET['appno'];
	?>
	
	<div class="divclass">
		<form action="rejnpdreq_view.php" method="post" enctype="multipart/form-data">-->
		<div id="stylized" class="myform">
		<div class="column">
			<label>Approval No&nbsp;</label>
			<input type="text"  id="appno" name="appno" readonly required value="<?php echo $appno  ?>"/>
		</div>
		
		<div class="column">
			<label>REJECTION REASON&nbsp;</label>
			<input type="text"  id="rejreason" name="rejreason" required value=""/>
		</div>
		
		<br><br>
		
		<div class="column">
			<input type="submit" name="submit" id="submit" value="SUBMIT"/>
		</div>
		
		
		
		</form>
		</div>
	</div>
</body>
</html>	




