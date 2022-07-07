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
	if($_SESSION['access']=="To S/C" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="REWORK ISSUANCE";
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
	<link rel="stylesheet" type="text/css" href="mystyle1.css">
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
		<h4 style="text-align:center"><label>SUB CONTRACT REWORK ISSUANCE ENTRY </label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action='i181.php'>	
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
			</br>
			
			<div>
				<label>RCNO</label>
					<select name ="rcno" id="rcno" onchange="reload1(this.form)">
					<option value="">Select one</option>";	
						<?php
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "select rcno from d11 where rcno like 'E%' and closedate='0000-00-00'";
								$result = $con->query($query);
								
								while ($row = mysqli_fetch_array($result)) 
								{
									if($_GET['rat']==$row['rcno'])
										echo "<option selected value='" . $row['rcno'] . "'>" . $row['rcno'] ."</option>";
									else
										echo "<option value='" . $row['rcno'] . "'>" . $row['rcno'] ."</option>";              
								}
								echo "</select></h1>";
							
						?>
						<script>
						function reload1(form)
						{
							var val=form.rcno.options[form.rcno.options.selectedIndex].value; 
							var s = document.getElementById("tdate").value;
							self.location='i18.php?tdate='+s+'&rat=' + val ;
					}
					</script>
			</div>
			<br>
			<div>
				<label>PART NUMBER</label>
					<input type="text" id="pnum"  readonly="readonly" name="pnum" value="<?php			
						$na="";
							if(isset($_GET['rat']) and $_GET['rat']!='') 
							{
								$rat=$_GET['rat'];
								$query = "SELECT pnum FROM d12 where rcno='".$rat."'";
								$result = mysqli_query($con,$query);
								$temp1=mysqli_fetch_array($result);
								$na=$temp1['pnum'];
								echo $temp1['pnum'];
							}
						?>"/>
			</div>
			<br>
			<div>
				<label>NEW DC</label>
				<input type="text" id="ndc" name="ndc" placeholder="Enter dc"/>
			</div>
			<br>
			<div>
				<label>Quantity</label>
				<input type="text" id="qty" name="qty" placeholder="Enter Qty"/>
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
	</div>
			

</body>
</html>