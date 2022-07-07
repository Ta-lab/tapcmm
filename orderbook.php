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
	if($_SESSION['user']=="111" || $_SESSION['access']=="ALL" || $_SESSION['user']=="127"  || $_SESSION['user']=="109")
	{
		$id=$_SESSION['user'];
		$activity="ORDER BOOK UPDATION";
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
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>ORDER BOOK MASTER UPDATION</label></h4>
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
$query = "SELECT DISTINCT pn from invmaster";
$result = $con->query($query);
echo"<datalist id='part'>";
while($row = mysqli_fetch_array($result))
{
	echo "<option value='".$row['pn']."'>".$row['pn']."</option>";
}
echo"</datalist>";

?>
		<form method="POST" action='orderbookdb.php'>	
			</br>
			<div>
				<label>DATE</label>
					<input type="date" id="tdate" name="tdate" value="<?php
					if(isset($_GET['tdate']))
					{
						echo $_GET['tdate'];
					}
					else
					{
						echo date('Y-m-d');
					}
					?>"/>
			</div>
			<br>
		
			<div>
				<label>PART NUMBER</label>
					<input type="text" id="pnum" list="part" name="pnum" required placeholder="ENTER PART NUMBER">
			</div>
			<br>
			
			<div>
				<label>TYPE</label>
					<select name="parttype" id="parttype" required>
						<option value="">TYPE</option>
						<option value="Regular">Regular</option>
						<option value="Stranger">Stranger</option>
					</select>
			</div>			
			<br>			
			
			<div>
				<label>SCHEDULE DATE</label>
					<input type="date" id="orderdate" name="orderdate" required>
			</div>
			<br>
			<div>
				<label>SCHEDULE QUANITY</label>
					<input type="number" id="orderqty" name="orderqty" required placeholder="ENTER ORDER QUANTITY">
			</div>
			<br>
			<div>
				<label>SCHEDULE REFERENCE NO</label>
					<input type="text" id="orderref" name="orderref" required placeholder="ENTER ORDER REFERENCE NUMBER">
			</div>
			<br>
			<div>
				<label>SCHEDULE REQUESTED DATE</label>
					<input type="date" id="reqdate" name="reqdate" required >
			</div>
			<br>
			<div>
				<label>SCHEDULE COMMIT DATE</label>
					<input type="date" id="commitdate" name="commitdate" required >
			</div>
			<br>
			<div>
					<input type="Submit" name="submit" id="submit"  value="SUBMIT" onclick="myFunction()"/>
			</div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
</script>
		</form>
	</div>
</body>
</html>