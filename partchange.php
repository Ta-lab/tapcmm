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
	if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="RC PART NUMBER CHANGE";
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
<body>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<script>
			function reload(form)
			{
				var p1 = document.getElementById("rcno").value;
				self.location=<?php echo"'partchange.php?rcno='"?>+p1;
			}
		</script>
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div align="right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>PART NUMBER - UPDATION</label></h4>
	<form action="addpart.php"  method="post">		
	<br><br>
	<div>
		<label>ROUTE CARD NUMBER</label>
		<input type="text" id="rcno" name="rcno"  onchange="reload(this.form)"  value="<?php if(isset($_GET['rcno'])){echo $_GET['rcno'];}?>"/>
	</div>
	<br>
	<div>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" name ="pnum" id="pnum" value="<?php
				if(isset($_GET['rcno']))
				{
					$rc=$_GET['rcno'];
					$result = mysqli_query($con,"SELECT DISTINCT pnum FROM d12 where rcno='$rc'");
					$row = mysqli_fetch_array($result);
					echo $row['pnum'];
				}
				?>"/>
			<?php					
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$result = mysqli_query($con,"SELECT DISTINCT pnum FROM m13");
					echo "";
					echo"<datalist id='combo-options'>";
						while($row = mysqli_fetch_array($result))
							{
								echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
							}
					echo"</datalist>";
				?>
		</div>
		<br>
		<input type="Submit" name="submit" value="ENTER"/>
	</form>
</div>
</body>
</html>