<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="101")
	{
		$id=$_SESSION['user'];
		$activity="PARENT CHILD PART UPDATION";
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
	<link rel="stylesheet" type="text/css" href="design.css">

</head>
<body>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label>RM MASTER UPDATION</label></h4>
	<div class="divclass">
	<?php
	$result = mysqli_query($con,"SELECT DISTINCT rmdesc FROM m13");
	echo"<datalist id='combo-options1'>";
	while($row = mysqli_fetch_array($result))
	{
		echo "<option value='" . $row['rmdesc'] . "'>" . $row['rmdesc'] ."</option>";
	}
	echo"</datalist>";
	?>
		<form method="POST" action='testdb.php'>
			<div>
				<label>RM TO BE CORRECTED</label>
				<input type="text" id="rm1"  list="combo-options1"  name="rm1"  placeholder="Enter RM">
			</div>
			<br>
			<div>
				<label>2.PARTS PICK FROM</label>
				<input type="text" id="rm2"  list="combo-options1"  name="rm2" placeholder="Enter CORRECT RM">
			</div>
			<br>
			</br>
			<div>
					<input type="Submit" name="submit" value="SUBMIT"/>
			</div>
		</form>
	</div>
</body>
</html>