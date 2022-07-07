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
	if($_SESSION['access']=="FI" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="PURCHASE ORDER PRINTING";
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
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<body>
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div align="right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>INSPECTION PRINTING</label></h4>
		<div class="divclass">
	<form action="fpdf/digprint/inspreport1.php" target="_blank" method="post">		
	<br><br>
	<?php			
		$result = mysqli_query($con,"SELECT DISTINCT pnum FROM fi_detail");
		echo "";
		echo"<datalist id='combo-options'>";
			while($row = mysqli_fetch_array($result))
				{
					echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
				}
		echo"</datalist>";
		
	?>
	<script>
		function reload0(form)
		{
			var p2 = document.getElementById("pnum").value;
			self.location=<?php echo"'ir_print1.php?pnum='"?>+p2;
		}
	</script>
	<div>
		<label>PART NUMBER :</label>
		<input type="text" name="pnum" id="pnum" list="combo-options" onchange="reload0(this.form)"
		<?php
		if(isset($_GET['pnum']) && $_GET['pnum']!='')
		{
			$p=$_GET['pnum'];
			echo "value='$p'";
		}
		?>/>
	</div>
	<br>
	<br>
		<input type="Submit" name="submit" value="ENTER"/>
	
</div>
</body>
</html>