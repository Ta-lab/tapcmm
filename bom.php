<?php
$c=0;
$uom="";$mc="";$f="";
session_start();
if(isset($_SESSION['user']))
{
	if(($_SESSION['access']=="ALL" && $_SESSION['user']=="123") || ($_SESSION['user']=="100"))
	{
		$id=$_SESSION['user'];
		$activity="BOM UPDATION";
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
	<link rel="stylesheet" type="text/css" href="design.css">
</head>
<body>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>BOM MASTER</label></h4>
		<div class="divclass">
		<form method="POST" action="bomdb.php">
			</br>
			<div>
				<label>PART NUMBER</label>
					<input type="text" list="combo-options0" id="pn" name="pn" placeholder="Enter Part Number" onchange="reload0(this.form)" value="<?php
				if(isset($_GET['part']))
				{
					echo $_GET['part'];
				}
				?>"/>
			</div>
			<script>
			function reload0(form)
			{
				var p2 = document.getElementById("pn").value;
				self.location=<?php echo"'bom.php?part='"?>+p2;
			}
			function reload1(form)
			{
				var p2 = document.getElementById("pn").value;
				var p3 = document.getElementById("rm").value;
				self.location=<?php echo"'bom.php?part='"?>+p2+'&mat='+p3;
			}
		</script>
			<br/>
			<div>
				<label>RAW MATERIAL</label>
			<input type="text" list="combo-options" placeholder="Choose Rm Description" name ="rm" id="rm" onchange="reload1(this.form)" value="<?php
				if(isset($_GET['mat']))
				{
					echo $_GET['mat'];
				}
				?>"/>
				<?php
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$result = mysqli_query($con,"SELECT DISTINCT pnum FROM m13");
					echo "";
					echo"<datalist id='combo-options0'>";
						while($row = mysqli_fetch_array($result))
							{
								echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
							}
					echo"</datalist>";
					if(isset($_GET['part']))
					{
						$con=mysqli_connect('localhost','root','Tamil','mypcm');
						if(!$con)
							die(mysqli_error());
						$result = mysqli_query($con,"SELECT DISTINCT rmdesc FROM m13");
						echo "";
						echo"<datalist id='combo-options'>";
							while($row = mysqli_fetch_array($result))
								{
									echo "<option value='" . $row['rmdesc'] . "'>" . $row['rmdesc'] ."</option>";
								}
						echo"</datalist>";
					}
					if(isset($_GET['part']) && isset($_GET['mat']))
					{
						$part=$_GET['part'];
						$mat=$_GET['mat'];
						$result = mysqli_query($con,"SELECT *,count(*) as c FROM m13 where pnum='$part' and rmdesc='$mat'");
						$row1 = mysqli_fetch_array($result);
						if($row1['c']>0)
						{
							$c=1;
							$uom=$row1['useage'];
							$mc=$row1['m_code'];
							$f=$row1['foreman'];
							$ce=$row1['cnc_excep'];
						}
						$result = mysqli_query($con,"SELECT *,count(*) as c FROM rmcategory where pnum='$part' and rm='$mat'");
						$row2 = mysqli_fetch_array($result);
						if($row2['c']>0)
						{
							$c=1;
							$obom=$row2['obom'];
							$rmcategory=$row2['category'];							
						}						
					}
			?>
			</div>
			</br>
			<div>
				<label>UOM</label>
					<?php					
						echo "<select name ='uom'>";
						echo "<option value='KG'>KG</option>";
						echo "<option value='METER'>METER</option>";
						echo "</select>";
					?>
			</div>
			</br>
			<div>
				<label>USAGE (In Kilo)</label>
					<input type="text" id="use" name="use" placeholder="Enter Usage" <?php if($c==1){ echo 'value="'.$uom.'"';}?>/>
			</div>
			</br>
			<div>
				<label>OUTPUT BOM (In Kilo)</label>
					<input type="text" id="ouse" name="ouse" placeholder="Enter Usage" <?php if($c==1){ echo 'value="'.$obom.'"';}?>/>
			</div>
			</br>
			<div>
				<label>MATERIAL CODE</label>
					<input type="text" id="mcode" name="mcode" placeholder="Enter M Code" <?php if($c==1){ echo 'value="'.$mc.'"';}?> >
			</div>
			<br>
			<div>
				<label>CNC RC EXCEPTION</label>
					<input type="text" id="crce" name="crce" placeholder="Exception Staus" <?php if($c==1){ echo 'value="'.$ce.'"';}?> >
			</div>
			<br>
			<div>
				<label>FOREMAN</label>
					
					<select id="fm" name="fm">
					<option value=''>Select one</option>
						<?php			
							if(isset($_GET['mat'])) 
							{	 
								$query2 = "SELECT DISTINCT foreman FROM m13 where foreman!=''";
								$result2 = $con->query($query2);
								while ($row2 = mysqli_fetch_array($result2)) 
								{
									if($f==$row2['foreman']){
										echo "<option selected value='" . $row2['foreman'] . "'>" . $row2['foreman'] ."</option>";
									}
									else{
										echo "<option value='" . $row2['foreman'] . "'>" . $row2['foreman'] ."</option>";
									}
								}
                    		}
						?>
			</select>
			</div>
			<br>
			
			<div>
				<label>RM CATEGORY</label>
					
					<select id="rmcategory" name="rmcategory">
					<option value=''>Select one</option>
						<?php			
							if(isset($_GET['mat'])) 
							{	 
								$query2 = "SELECT DISTINCT category FROM rmcategory where category!=''";
								$result2 = $con->query($query2);
								while ($row2 = mysqli_fetch_array($result2)) 
								{
									if($rmcategory==$row2['category']){
										echo "<option selected value='" . $row2['category'] . "'>" . $row2['category'] ."</option>";
									}
									else{
										echo "<option value='" . $row2['category'] . "'>" . $row2['category'] ."</option>";
									}
								}
                    		}
						?>
			</select>
			</div>
			
			<br>
			<div>
					<input type="Submit" name="submit" id="submit"  value="SUBMIT"  onclick="myFunction()"/>
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
