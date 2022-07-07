<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="FG For S/C" || $_SESSION['access']=="ALL")
	{
		
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
	<h4 style="text-align:center"><label>NON-TRACEABILITY PARTS RECEIPT</label></h4>
	<div class="divclass">
	<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action='finaldb.php'>
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
			<?php
				date_default_timezone_set("Asia/Kolkata");
					if(isset($_GET['cat']))
					{
						if((date("H")>=19 || date("H")<8) && ($_GET['cat']=='FG For Invoicing' || $_GET['cat']=='FG For S/C'))
						{
							header("location: inputlink.php");
						}
					}
			?>
			<div>
			<br>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" name ="partnumber" id="pnum" onchange="reload0(this.form)" value="<?php
				if(isset($_GET['mat']))
				{
					echo $_GET['mat'];
				}
				?>"/>
			<?php					
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$result = mysqli_query($con,"SELECT DISTINCT pn as pnum FROM invmaster where pn not in(select distinct invpnum from pn_st)");
					echo "";
					echo"<datalist id='combo-options'>";
						while($row = mysqli_fetch_array($result))
							{
								echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
							}
					echo"</datalist>";
				?>
		</div>
		<script>
			function reload0(form)
			{
				var p1 = document.getElementById("tdate").value;
				var p2 = document.getElementById("pnum").value;
				self.location=<?php echo"'final.php?tdate='"?>+p1+'&mat='+p2;
			}
		</script>
			<br>
			<div>
				<label>STOCKING POINT</label>
				<select  name ="operation" onchange="reload(this.form)">
					<option value=''>Choose the Stocking Point</option>
					<?php 	 
							$mat=$_GET['mat'];
							$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
							$result = $con->query("SELECT distinct m12.operation FROM `m12` where operation='FG For Invoicing'");
							while($row = mysqli_fetch_array($result))
							{	
								echo "<option selected value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
							}
					?>
				</select>
			</div>
			
			<script>
				function reload(form)
				{
					var val=form.operation.options[form.operation.options.selectedIndex].value; 
					var s = document.getElementById("tdate");
					var p = document.getElementById("pnum");
					window.location='final.php?tdate='+s.value+'&mat='+p.value+'&cat='+val;
				}
			</script>
			<br>
			<div>
				<label>RECEIPT QTY</label>
				<input type="text" id="rcpt" name="rcpt" placeholder="Enter the Reciept Quantity"/>
			</div>
			<br>
			<div>
				<label>LOCATION</label>
				<input type="text" id="lok" name="lok" placeholder="Enter the Location"/>
			</div>
			<br>
			<div>
					<input type="Submit" name="submit" value="SUBMIT"/>
			</div>
		</form>
	</div>

</body>
</html>