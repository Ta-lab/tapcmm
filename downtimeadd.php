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
		$activity="DOWN TIME UPATION DETAIL";
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
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
</head>
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
		<h4 style="text-align:center"><label>INSERT DOWN TIME DETAIL</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<?php
$result = mysqli_query($con,"SELECT DISTINCT machinename FROM machinedb");
echo"<datalist id='mlist'>";
	while($row = mysqli_fetch_array($result))
		{
			echo "<option value='" . $row['machinename'] . "'>" . $row['machinename'] ."</option>";
		}
echo"</datalist>";
?>
	<form method="POST" action='downtimedb.php'>	
		<div id="stylized" class="myform">
			<div class="column">
				<label>AREA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text" name ="area" id="area" value="CNC_SHEARING" readonly/>
			</div>
			
			<div class="column">
				<label> FROM DATE :</label>
					<input type="date"  name ="fd" id="fd"  value="<?php echo date('Y-m-d');?>"/>
			</div>
			
			<div class="column">
				<label> FROM TIME :</label>
					<input type="time"  name ="ft" id="ft" />
			</div>
			<br><br>
			
			<div class="column">
				<label>MACHINE ID&nbsp;&nbsp;:</label>
					<input type="text" name ="mid" required id="mid" list="mlist"/>
			</div>
			
			<div class="column">
				<label> TO DATE &nbsp;&nbsp;:</label>
					<input type="date" id="td" name="td" value="<?php echo date('Y-m-d');?>" required>
			</div>
			
			<div class="column">
				<label> TO TIME &nbsp;&nbsp;:</label>
					<input type="time" id="tt" name="tt" required>
			</div>
			<BR><BR>
			<div>
					<input type="Submit" name="submit" id="submit"  value="ADD PARTICULAR" onclick="myFunction()"/>
			</div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
function enable(){
	document.getElementById('submit').style.visibility = 'visible';
}
</script>
		</form>
	</div>
</body>
</html>