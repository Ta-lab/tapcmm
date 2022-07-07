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
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="100" || $_SESSION['user']=="115" || $_SESSION['user']=="117" || $_SESSION['user']=="102" || $_SESSION['user']=="108")
	{
		$id=$_SESSION['user'];
		$activity="WEEKLY COMMIT UPATION";
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
		<h4 style="text-align:center"><label>WEEKLY COMMIT DETAIL</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<datalist id='alist'>
<option value='MANUAL'>MANUAL</option>
<option value='CNC_SHEARING'>CNC_SHEARING</option>
</datalist>
<?php
$query = "SELECT DISTINCT pnum from m13";
$result = $con->query($query);
echo"<datalist id='plist'>";
while($row = mysqli_fetch_array($result))
{
	echo "<option value='".$row['pnum']."'>".$row['pnum']."</option>";
}
echo"</datalist>";
?>
	<form method="POST" action='weeklycommitdb.php'>	
		<div id="stylized" class="myform">
			<div class="column">
				<label>WEEK NUMBER&nbsp;:</label>
					<input type="text" readonly name ="week" id="week" value="<?php
					$query = "SELECT week FROM `d19`";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					echo $row['week'];
					?>"/>
			</div>
			<?php
				$area="";$part="";$commit="";$s="";$taken="";
				if(isset($_GET['rid']) && $_GET['rid']!="")
				{
					$t=$_GET['rid'];
					$query = "SELECT * FROM `commit` where rid='$t'";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					$s="readonly";$area=$row['foremac'];$part=$row['pnum'];$commit=$row['qty'];$taken=$row['issuedqty'];
				}
			?>
			<div class="column">
				<label> AREA NAME &nbsp;&nbsp;:</label>
					<input type="text"  name ="area" id="area"  list="alist" value="<?php echo $area.'" '.$s;?>/>
			</div>
			
			<div class="column">
				<label> PART NUMBER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  name ="pnum" id="pnum" list="plist" value="<?php echo $part.'" '.$s;?>/>
			</div>
			<br><br>
			
			<div class="column">
				<label>COMMIT QTY&nbsp;&nbsp;:</label>
					<input type="number" name ="cqty" required id="cqty" value="<?php echo $commit.'" '.$s;?>/>
			</div>
			<?php
			if(isset($_GET['rid']) && $_GET['rid']!="")
			{
				echo'<div class="column">
					<label>TAKEN QTY &nbsp;&nbsp;:</label>
						<input type="number" name ="qty" required id="qty" value="'.$taken.'"/>
				</div>
				<div class="column">
					<label>UPDATE CMT QTY&nbsp;&nbsp;:</label>
						<input type="number" name ="ucqty" required id="ucqty"/>
				</div>';
			}
			?>
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