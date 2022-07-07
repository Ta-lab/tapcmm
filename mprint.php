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
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL" || $_SESSION['user']=="127" || $_SESSION['user']=="111" || $_SESSION['user']=="124")
	{
		$id=$_SESSION['user'];
		$activity="INVOICE PRINTING";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
		//header("location: inputlink.php?msg=25");
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
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<body>
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div align="right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>INVOICE PRINTING</label></h4>
		<div class="divclass">
	<form action="invmprint.php"  method="post">		
	<br><br>
	<div>
		<label>FROM(INV NUMBER) :</label>
		<input type="text" id="from" name="from"  onchange="reload(this.form)" value="<?php
		$aa=0;$pt="";
		if(isset($_GET['inv']) && $_GET['inv']!="")
		{
			echo $_GET['inv'];
		}
		else
		{
			$con = mysqli_connect('localhost','root','Tamil','mypcm');
			$result1 = $con->query("SELECT * from inv_det where print='F' order by invno");
			$row = mysqli_fetch_array($result1);
			$c= $result1->num_rows;
			if($c>0)
			{
				$aa=1;
			}
			$inv=$row['invno'];
			echo $inv;
		}
		?>"/>
	</div>
	<br>
	<script>
		function reload(form)
		{
			var s = document.getElementById("from").value;
			self.location='mprint.php?inv='+s;
		}
	</script>
	<?php
	if(isset($_GET['inv']) && $_GET['inv']!="" || $aa==1)
	{
		if(isset($_GET['inv']) && $_GET['inv']!="")
		{
			$inv=$_GET['inv'];
		}
		$result1 = $con->query("SELECT ccode,invdt,print,type from inv_det where invno='$inv'");
		$row = mysqli_fetch_array($result1);
		$c= $result1->num_rows;
		if($c>0)
		{
			if (strpos($row['ccode'], 'BOSCH') !==false || strpos($row['ccode'], 'SEG')!==false)
			{
				$pt="PDF";
			}
			else
			{
				$pt="PDF";
			}
			echo '<div><label>CCODE :</label><input type="text" name="ccode"  readonly value='.$row['ccode'].'></div>';
			echo '<br><div><label>PRINT TYPE :</label><input type="text" name="printtype"  readonly value='.$pt.'></div>';
			echo '<br><div><label>INVOICE DATE :</label><input type="text" name="invdt"  readonly value='.$row['invdt'].'></div><br>';
		}
	}
	?>
	<div>
		<label>NO OF INVOICES (NEXT) :</label>
		<input type="text" name="to" <?php if($pt=="PDF"){ echo "readonly";}?> max="2" required value="1"/>
	</div>
	<br>
		<input type="Submit" name="submit" <?php if($pt=="PDF"){ echo "formtarget='_blank'";}?> value="ENTER"/>
	
</div>
</body>
</html>