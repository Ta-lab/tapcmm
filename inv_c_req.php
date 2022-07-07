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
		$activity="INVOICE CORRECTION REQUEST";
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
	<h4 style="text-align:center"><label> INVOICE CORRECTION REQUEST </label></h4>
	<div>
			<script>
			function reload(form)
			{
				var p4 = document.getElementById("invno").value;
				self.location=<?php echo"'inv_c_req.php?invno='"?>+p4;
			}
		</script>
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
				<br><br>
			<?php
				$invno="";$ccode="";$pn="";$qty="";$invdt="";$s="";
				if(isset($_GET['invno']) && $_GET['invno']!='')
				{
					$s="";
					$t=$_GET['invno'];
					$result1 = $con->query("select invno,invdt,ccode,pn,qty from inv_det where invno='$t'");
					$row1 = mysqli_fetch_array($result1);
					if($result1->num_rows==1)
					{
						$invno=$row1['invno'];$ccode=$row1['ccode'];$pn=$row1['pn'];$qty=$row1['qty'];$invdt=$row1['invdt'];
					}
				}
			?>
			</form>
				<div id="stylized" class="myform">
				<form id="form" name="form" method="post" action="inv_c_req_db.php">
					<div class="column">
						<label>INVOICE NUMBER: </label>
						<input type="text" required name="invno" id="invno"  onchange=reload(this.form) value="<?php echo $invno; ?>"/>
					</div>
					<div class="column">
						<label>INVOICE DATE : </label>
						<input type="text" required name="invdt" readonly id="invdt" value="<?php echo $invdt; ?>"/>
					</div>
					<div class="column">
						<label>Part Number&nbsp;&nbsp;: </label>
						<input type="text" required name="pn" readonly id="pn" value="<?php echo $pn; ?>"/>
					</div>
					<br><br>
					<div class="column">
						<label>QUANTITY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="qty" readonly id="qty" value="<?php echo $qty; ?>"/>
					</div>
					<div class="column">
						<label>CCODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="ccode" id="ccode" <?php echo $s; ?> value="<?php echo $ccode; ?>"/>
					</div>
					<div class="column">
						<label>REASON&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text" required name="rsn" placeholder="For Returned Invoice TYPE 'MATERIAL RETURNED'" id="rsn"/>
					</div>
					<div>
						<input style="left:70%;" type="submit" name="submit" value="SUBMIT">
					</div>
					<div class="column1">
				</form>
	</body>
</html>
		