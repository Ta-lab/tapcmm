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
	if($_SESSION['access']=="Quality" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="QUALITY UPDATION";
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
		<h4 style="text-align:center"><label>QUALITY UPDATION</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action='i151.php'>	
			</br>
			<div>
				<label>DATE</label>
					<input type="date" id="tdate" readonly name="tdate" value="<?php
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
			<label>RCNO</label>
			<input type="text" list="combo-options" name ="rcno" id="rcno" onchange="reload(this.form)" value="<?php
				if(isset($_GET['rat']))
				{
					echo $_GET['rat'];
				}
				?>"/>
			<?php					
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$query = "select distinct(d11.rcno) from d11 join d12 on d11.rcno=d12.rcno where closedate='0000-00-00' and d12.rcno!='' and d12.rcno like '_20__0%'";
					$result = $con->query($query); 
					echo "";
					echo"<datalist id='combo-options'>";
						while ($row = mysqli_fetch_array($result))
							{
								echo "<option value='".$row['rcno']."'>".$row['rcno']."</option>";
							}
					echo"</datalist>";
				?>
		</div>
		<script>
		function reload(form)
		{	
			var val=document.getElementById("rcno").value;
			var s = document.getElementById("tdate").value;
			self.location='i15.php?tdate='+s+'&rat='+val;
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
								$query2 = "SELECT DISTINCT pnum FROM d11 where rcno = '$rat'";
								$result2 = $con->query($query2);
								while ($row2 = mysqli_fetch_array($result2)) 
								{
									 if($_GET['mat']==$row2['pnum'])
										echo "<option selected value='" . $row2['pnum'] . "'>" . $row2['pnum'] ."</option>";
									else
										echo "<option value='" . $row2['pnum'] . "'>" . $row2['pnum'] ."</option>";
								}
                    		}
						echo "</select></h1>";
						?>
						<script>
						function reload2(form)
						{	
							var val=form.partnumber.options[form.partnumber.options.selectedIndex].value; 
							self.location=<?php if(isset($_GET['rat']))echo"'i15.php?tdate=".$_GET['tdate']."&rat=".$_GET['rat']."&mat='";?> + val ;
						}
						</script>
			</div>
			<br>
			<div>
				<label>OPERATION</label>
					<select name ="operation" required id="prcno">
						<?php			
							if(isset($_GET['mat'])) 
							{	
								$mat=$_GET['mat'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								$query1 = "SELECT DISTINCT m12.operation from m12 join m11 on m12.operation=m11.operation where m12.pnum='$mat' and m11.opertype!='stocking point'";
								$result1 = $con->query($query1);  
								echo "<option value=''>Select one</option>";
								while ($row2 = mysqli_fetch_array($result1)) 
								{
									if($_GET['cat']==$row2['operation'])
										echo "<option selected value='" . $row2['operation'] . "'>" . $row2['operation'] ."</option>";
									else
										echo "<option value='" . $row2['operation'] . "'>" . $row2['operation'] ."</option>";
								}
							}
						?>
					</select>
			</div>
			<br>
			<div>
				<label>WORK CENTRE</label>
					<input type="text" name="workcentre" required placeholder="Enter Work Centre"/>
			</div>
			<br>
			<div>
				<label>QTY REJECTED IN KG</label>
					<input type="text" id="rcpt" name="rcpt" required placeholder="Enter Reciept Quantity">
			</div>
			</br>
			<div>
				<label>REASON </label>
					<input type="text" name="rsn" required placeholder="Enter the Reason for rejection"/>
			</div>
			<br>
			<div>
					<input type="Submit"  name="submit" id="submit"  value="SUBMIT" onclick="this.style.display = 'none';"/>
			</div>
		</form>
	</div>
</body>
</html>