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
	if($_SESSION['access']=="PURCHASE" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="SUPPLIER PARTCULAR DETAIL";
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
		<h4 style="text-align:center"><label>INSERT PURCHASE DETAIL</label></h4>
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
	$result1 = $con->query("select distinct rmdesc from m13");
	echo"<datalist id='rmlist'><option value=''>Select one</option>";
	while ($row1 = mysqli_fetch_array($result1)) 
	{
		echo "<option value='".$row1['rmdesc']."'>".$row1['rmdesc']."</option>";
	}
	echo '</datalist>';
	$con=mysqli_connect('localhost','root','Tamil','purchasedb');
?>
	<form method="POST" action='i72db.php'>	
		<div id="stylized" class="myform">
			<div class="column">
				<label>SUPPLIER CODE&nbsp;&nbsp;:</label>
					<input type="text" name ="scode" id="scode" readonly value="<?php
					if(isset($_GET['scode']))
					{
						echo $_GET['scode'];
					}
					?>"/>
			</div>
			
			<div class="column">
				<label>MATERIAL DESC:</label>
					<input type="text" name ="mdesc" required id="mdesc" onchange="reload2(this.form)" list='rmlist' value="<?php
							if(isset($_GET['rm']))
							{	
								echo $_GET['rm'];		
							}
						?>"/>
				<script>
						function reload2(form)
						{	
							var val = document.getElementById("mdesc").value; 
							self.location=<?php if(isset($_GET['scode']))echo"'i72add.php?scode=".$_GET['scode']."&rm='";?> + val ;
						}
						</script>
			</div>
			<div class="column">
				<label>MATERIAL CODE:</label>
					<input type="text" name ="mcode"  id="mcode" value="<?php
					$mc="";$hsnc="";$rate="";$uom="";
					if(isset($_GET['rm']) && $_GET['rm']!="")
					{
						$con=mysqli_connect('localhost','root','Tamil','mypcm');
						$t=$_GET['rm'];
						$result1 = $con->query("select distinct m_code from m13 where rmdesc='$t'");
						$row1 = mysqli_fetch_array($result1);
						$mc=$row1['m_code'];
						echo trim($row1['m_code']);
					}?>"/>
			</div>
			<br><br>
			<div class="column">
				<label>MDESC - PRINT&nbsp;&nbsp;:</label>
					<input type="text" name ="mdescp" required id="mdescp"  value="<?php
							if(isset($_GET['rm']) && $_GET['rm']!="" && isset($_GET['scode']) && $_GET['scode']!="" && isset($_GET['mcode']) && $_GET['mcode']!="")
							{
								$t=$_GET['scode'];
								$rm=$_GET['rm'];
								$con=mysqli_connect('localhost','root','Tamil','purchasedb');
								$result1 = $con->query("select DISTINCT mdesc2 where scode='$t' and mcode='$mc'");
								echo "select DISTINCT mdesc2 where scode='$t' and mcode='$mc'";
								$c=$result1->num_rows;
								if($c>0)
								{
									$row1 = mysqli_fetch_array($result1);
									$mdesc2=$row1['mdesc2'];
									echo $mdesc2;
								}
							}
						?>"/>
			</div>
			<?php
			if(isset($_GET['rm']) && $_GET['rm']!="" && isset($_GET['scode']) && $_GET['scode']!="")
			{
				$t=$_GET['scode'];
				$con=mysqli_connect('localhost','root','Tamil','purchasedb');
				$result1 = $con->query("select hsnc,rate,uom from pomaster where scode='$t' and mcode='$mc'");
				//echo "select hsnc,rate,uom from pomaster where scode='$t' and mcode='$mc'";
				$c=$result1->num_rows;
				if($c>0)
				{
					$row1 = mysqli_fetch_array($result1);
					$hsnc=$row1['hsnc'];
					$rate=$row1['rate'];
					$uom=$row1['uom'];
				}
			}
			?>
			
			<div class="column">
				<label>HSN CODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text" id="hsnc" name="hsnc" required value="<?php echo $hsnc; ?>">
			</div>
		
			<div class="column">
				<label>RATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text" id="rate" name="rate" required value="<?php echo $rate; ?>">
			</div>
			<br><br>
			<div class="column">
				<label>U O M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label>
					<input type="text" name ="uom" id="uom" value="<?php echo $uom; ?>"/>
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