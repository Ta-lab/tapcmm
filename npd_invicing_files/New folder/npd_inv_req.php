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
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="NPD PARTS INVOICE REQUEST";
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
	<script src = "js\excelreport.js"></script>
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
</head>
<body>
<style>
.column
{
    float: left;
    width: 33%;
}
.column1
{
    float: left;
    width: 33%;
	display: none;
}
</style>
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label>RAISE NPD PARTS INVOICE REQUEST </label></h4>
	<div>
	
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		
		<br><br>
			
			<datalist id="partlist" >
			<?php
				$qty="";
				$type="";
				$query = "SELECT distinct pnum FROM npdparts";
				$result = $con->query($query);
				echo"<option value=''>Select one</option>";
				while ($row = mysqli_fetch_array($result)) 
				{
					if($_GET['pn']==$row['pnum'])
						echo "<option selected value='".$row['pnum']."'>".$row['pnum']."</option>";
					else
						echo "<option value='".$row['pnum']."'>".$row['pnum']."</option>";
				}			
			?>
			</datalist>
			
				<div id="stylized" class="myform">
				<form id="form" name="form" method="post" action="npd_inv_req_db.php">
					
					<div class="column">
						<label>PART NUMBER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
						<input type="text"  class='s' required id="p2" name="pn" list="partlist" 
								value="<?php 
									if(isset($_GET['partnumber']))
									{	
										echo $_GET['partnumber'];
									} 
								?>"/>
					</div>
					
					<div class="column">
						<label>QUANTITY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="qty" id="qty" value="<?php echo $qty; ?>"/>
					</div>
					
					
					<div class="column">
						<label>TYPE</label>
						<select name="type" id="type" required>
							<option value="">SELECT TYPE</option>
							<option value="TRIAL">TRIAL</option>
							<option value="SAMPLE">SAMPLE</option>
							<option value="PPAP">PPAP</option>
							<option value="LOT">LOT</option>
						</select>
					</div>
			
					
					
					<br><br>
					
					<div>
						<input style="left:50%;" type="submit" name="submit" value="SUBMIT">
					</div>
				
					
					
				</form>
	</body>
</html>
		