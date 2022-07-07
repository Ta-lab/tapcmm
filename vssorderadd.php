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
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="127" || $_SESSION['user']=="111" || $_SESSION['user']=="117" || $_SESSION['user']=="109")
	{
		$id=$_SESSION['user'];
		$activity="DEMAND MASTER UPDATION";
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
		<h4 style="text-align:center"><label>VSS DEMAND MASTER UPDATION </label></h4>
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
$query = "SELECT DISTINCT pn from invmaster WHERE pn NOT IN (SELECT DISTINCT pnum FROM demandmaster)";
$result = $con->query($query);
echo"<datalist id='part'>";
while($row = mysqli_fetch_array($result))
{
	echo "<option value='".$row['pn']."'>".$row['pn']."</option>";
}
echo"</datalist>";
echo"<datalist id='plist'>";
	echo "<option value='Kanban'>Kanban</option>";
	//echo "<option value='Stranger'>Stranger</option>";
	//echo "<option value='Regular'>Regular</option>";
	//echo "<option value='NPD'>NPD</option>";
echo"</datalist>";
?>
	<form method="POST" action='vssorderdb.php'>	
		<div id="stylized" class="myform">
			<?php
				$type="";$pnum="";$mqty="";$s="";$vsqty="";$vfqty="";
				if(isset($_GET['rid']) && $_GET['rid']!="")
				{
					$t=$_GET['rid'];
					$query = "SELECT * FROM `demandmaster` where rno='$t'";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					$s="readonly";$pnum=$row['pnum'];$type=$row['type'];$monthly=$row['monthly'];$vmi_fg=$row['vmi_fg'];$sf=$row['sf'];
				}
			?>
			<div class="column">
				<label> TYPE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  name ="type" id="type"  list="plist" value="<?php echo $type;?>"/>
			</div>
			
			<div class="column">
				<label> PART NUMBER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text"  name ="pnum" id="pnum" list="part" value="<?php echo $pnum.'" '.$s;?>"/>
			</div>
			
			<div class="column">
				<label>MONTHLY&nbsp;&nbsp;:</label>
					<input type="number" name ="mqty" required id="mqty" onKeyUp="vmicalc()" value="<?php echo $monthly;?>"/>
			</div>
			<br><br>
			
			<script>
			function vmicalc(){
				var vmi_fg = document.getElementById("mqty").value * 5 / 26;
				var vmi_sf = document.getElementById("mqty").value * 5 / 26;
				
				document.getElementById("vfqty").value = Math.round(vmi_fg);
				document.getElementById("vsqty").value = Math.round(vmi_sf);
			}
			</script>
			
			<div class="column">
				<label>VMI IN FG&nbsp;&nbsp;:</label>
					<input type="number" name ="vfqty" required id="vfqty" value="<?php echo $vmi_fg;?>"/>
			</div>
			<div class="column">
				<label>VMI IN SF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="number" name ="vsqty" required id="vsqty" value="<?php echo $sf;?>"/>
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