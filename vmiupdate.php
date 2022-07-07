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
		$activity="VMI UPDATION";
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
		<h4 style="text-align:center"><label>VMI UPDATION</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action='vmiupdatedb.php'>	
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
			<label>CCODE</label>
			<input type="text" list="combo-options" name ="ccode" id="ccode" onchange="reload(this.form)" value="<?php
				if(isset($_GET['rat']))
				{
					echo $_GET['rat'];
				}
				?>"/>
			<?php					
					$query = "select distinct ccode from invmaster";
					$result = $con->query($query); 
					echo "";
					echo"<datalist id='combo-options'>";
						while ($row = mysqli_fetch_array($result))
							{
								echo "<option value='".$row['ccode']."'>".$row['ccode']."</option>";
							}
					echo"</datalist>";
				?>
		</div>
		<script>
		function reload(form)
		{	
			var val=document.getElementById("ccode").value;
			var s = document.getElementById("tdate").value;
			self.location='vmiupdate.php?tdate='+s+'&rat='+val;
		}
		</script>
			<br>
			<div>
				<label>PART NUMBER</label>
					<select name ="partnumber" id="pnum" onchange="reload2(this.form)">
					<option value=''>Select one</option>
						<?php			
							if(isset($_GET['rat'])) 
							{	 
								$rat=$_GET['rat'];
								$query2 = "SELECT DISTINCT pn FROM invmaster where ccode= '$rat'";
								$result2 = $con->query($query2);
								while ($row2 = mysqli_fetch_array($result2)) 
								{
									 if($_GET['mat']==$row2['pn'])
										echo "<option selected value='" . $row2['pn'] . "'>" . $row2['pn'] ."</option>";
									else
										echo "<option value='" . $row2['pn'] . "'>" . $row2['pn'] ."</option>";
								}
                    		}
						echo "</select></h1>";
						?>
						<script>
						function reload2(form)
						{	
							var val=form.partnumber.options[form.partnumber.options.selectedIndex].value; 
							self.location=<?php if(isset($_GET['rat']))echo"'vmiupdate.php?tdate=".$_GET['tdate']."&rat=".$_GET['rat']."&mat='";?> + val ;
						}
						</script>
			</div>
			<br>
			<div>
				<label>VMI QUANITY</label>
					<input type="text" id="vmi" name="vmi" required placeholder="Enter VMI Quantity">
			</div>
			</br>
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