<?php
$rm="";
$pnum="";
$bom="";
$na="";
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
	//if($_SESSION['access']=="From S/C" || $_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C" || $_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="Semifinished1" || $_SESSION['access']=="Semifinished2" || $_SESSION['access']=="ALL")
	//if($_SESSION['user']=="123" || $_SESSION['user']=="102" || $_SESSION['user']=="100" || $_SESSION['user']=="105")
	if($_SESSION['user']!="")
	{
		$id=$_SESSION['user'];
		$activity="BOM VIEW";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
	}
	else
	{
		header("location: inputlink.php");
		//header("location: logout.php");
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
	<h4 style="text-align:center"><label>BOM CALCULATOR</label></h4>
	
	<div class="divclass">
	
	<script>
	function preventback()
	{
		window.history.forward();
	}
	setTimeout("preventback()",0);
	window.onunload = function(){ null };
	</script>
	
		<form method="POST" action=''>
		<div>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" required name ="partnumber" id="pnum" onchange="reload0(this.form)" value="<?php
			if(isset($_GET['mat']))
			{
				echo $_GET['mat'];
			}
			?>"/>
			<?php					
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
		
		
		<!--<div>
			<label>RAW MATERIAL</label>
			<input type="text" list="rm-combo-options" required name ="rm" id="rm" onchange="reload0(this.form)" value="<?php
			/*if(isset($_GET['rmdesc']))
			{
				echo $_GET['rmdesc'];
			}*/
			?>"/>
			<?php					
				/*$result = mysqli_query($con,"SELECT DISTINCT rmdesc FROM m13 where pnum='".$_GET['mat']."'");
				echo "";
				echo"<datalist id='rm-combo-options'>";
					while($row = mysqli_fetch_array($result))
						{
							echo "<option value='" . $row['rmdesc'] . "'>" . $row['rmdesc'] ."</option>";
						}	
				echo"</datalist>";*/
			?>
		</div>-->
		
		
		<script>
			function reload0(form)
			{
				var p1 = document.getElementById("pnum").value;
				self.location=<?php echo"'bom_calc.php?mat='"?>+p1;
			}
		</script>
		<br>
		
		<?php
			if(isset($_GET['mat']))
			{
				//$res= $con->query("SELECT useage FROM m13 where pnum='".$_GET['mat']."' AND rmdesc='".$_GET['rmdesc']."' ");
				$res= $con->query("SELECT useage FROM m13 where pnum='".$_GET['mat']."' ");
				$temp4=mysqli_fetch_array($res);
				$bom=$temp4[0];
				if($bom==0 || $bom=="")
				{
					$res= $con->query("SELECT SUM(useage) as bom FROM `m13` WHERE pnum IN (SELECT pnum FROM pn_st WHERE invpnum='".$_GET['mat']."') AND m13.pnum!='".$_GET['mat']."'");
					$temp4=mysqli_fetch_array($res);
					$bom=$temp4['bom'];
				}
			}
		?>

		<div>
			<label>BOM</label>
				<input type="text" id="bom" readonly="readonly" name="bom" value="<?php
				if(isset($_GET['mat']))
				{
					echo $bom;
				}
				?>"/>
		</div>
		
		<br>
		
		<div>
			<label>ENTER QUANTITY (KG)</label>
			<input type="text" id="rcpt" name="rcpt" required onKeyUp="edValueKeyPress2()" placeholder="Enter Quantity"/>
		</div>
		
		
		<script>
			function edValueKeyPress2()
			{
				var rcpt = document.getElementById("rcpt").value;
				var bom = document.getElementById("bom").value;
				var nos = rcpt/bom;
				
				var r = document.getElementById('total');
				r.value = Math.round(nos);
				
				
			}
		</script>
		
		<br>			
		
		<div>
			<label>QUANTITY IN NOS</label>
				<input type="text" id="total" readonly="readonly" name="total" value="<?php
				?>"/>
		</div>
		
		
		
		</form>
	</div>
</body>
</html>