<?php
if(isset($_POST['place']))
{
	header("location: inputlink.php");
}
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
		$activity="NPD PARTS UPATION";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
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
	<!--<link rel="stylesheet" type="text/css" href="designformasterinv.css">-->
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>ADD NPD PART NUMBER</label></h4>
		<div class="divclass">
		<script>
		function preventback()
		{
			window.history.forward();
		}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
		</script>

<form method="POST" action='npdpartsadddb.php'>	
	<div id="stylized" class="myform">
	
	<datalist id="partlist" >
		<?php
			$con = mysqli_connect('localhost','root','Tamil','mypcm');
				if(!$con)
					echo "connection failed";
			$result1 = $con->query("select distinct pn as pnum from invmaster");
			echo"<option value=''>Select one</option>";
			while ($row1 = mysqli_fetch_array($result1))
			{
				if(isset($_GET['pnum'])==$row1['pnum'])
					echo "<option selected value='".$row1['pnum']."'>".$row1['pnum']."</option>";
				else
					echo "<option value='".$row1['pnum']."'>".$row1['pnum']."</option>";
			}
		?>
	</datalist>
	<div class="find">
		<label>PART NUMBER</label>
		<input type="text" style="width:70%; background-color:white;" class='s' id="pnum" name="pnum" list="partlist" value="<?php if(isset($_GET['pnum'])){echo $_GET['pnum'];}?>">
	</div>
	
	<br><br>
	<div>
		<input type="Submit" name="submit" id="submit"  value="ADD"/>
	</div>
	
</form>
</div>
</body>
</html>	