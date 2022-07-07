<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL" )
	{
		$id=$_SESSION['user'];
		$activity="PARENT CHILD PART UPDATION";
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
	<h4 style="text-align:center"><label>PARENT CHILD MASTER UPDATION</label></h4>
	<div class="divclass">
		<form method="POST" action='pcdb.php'>
			<div>
				<label>STOCKING POINT</label>
					<input type="text" list="combo-options2" id="stkpt" required name="stkpt" onchange="reload(this.form)" value="<?php
					if(isset($_GET['stkpt']))
					{
						echo $_GET['stkpt'];
					}
					?>"/>
			</div>
			<br>
			<div>
			<label>INVOICE PART</label>
			<input type="text" list="combo-options" name ="partnumber" required id="pnum" onchange="reload0(this.form)" value="<?php
				if(isset($_GET['mat']))
				{
					echo $_GET['mat'];
				}
				?>"/>
			<?php		
					if(isset($_GET['stkpt']) && $_GET['stkpt']!='')
					{
						if($_GET['stkpt']=="FG For Invoicing")
						{
							$result = mysqli_query($con,"SELECT DISTINCT pn FROM invmaster");
						}
						else
						{
							$result = mysqli_query($con,"SELECT DISTINCT pnum as pn FROM m13");
						}
						echo"<datalist id='combo-options'>";
						while($row = mysqli_fetch_array($result))
						{
							echo "<option value='" . $row['pn'] . "'>" . $row['pn'] ."</option>";
						}
						echo"</datalist>";			
					}
					if(isset($_GET['stkpt']) && $_GET['stkpt']!='' && isset($_GET['mat']) && $_GET['mat']!='')
					{
						$result = mysqli_query($con,"SELECT DISTINCT pnum FROM m13");
						echo"<datalist id='combo-options1'>";
						while($row = mysqli_fetch_array($result))
						{
							echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
						}
						echo"</datalist>";
					}
					$result = mysqli_query($con,"SELECT DISTINCT operation FROM m11 where opertype='STOCKING POINT' and operation!='Stores'");
					echo"<datalist id='combo-options2'>";
					while($row = mysqli_fetch_array($result))
					{
						echo "<option value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
					}
					echo"</datalist>";
				?>
		</div>
		<script>
			function reload0(form)
			{
				var p1 = document.getElementById("stkpt").value;
				var p2 = document.getElementById("pnum").value;
				self.location=<?php echo"'parentchildupdation.php?stkpt='"?>+p1+'&mat='+p2;
			}
			function reload(form)
			{
				var p1 = document.getElementById("stkpt").value;
				self.location=<?php echo"'parentchildupdation.php?stkpt='"?>+p1;
			}
		</script>
		<?php
		$s[0]="";$t[0]="";
		$s[1]="";$t[1]="";
		$s[2]="";$t[2]="";
		if(isset($_GET['stkpt']) && $_GET['stkpt']!='' && isset($_GET['mat']) && $_GET['mat']!='')
		{
			$t=$_GET['mat'];
			$t1=$_GET['stkpt'];
			$result = mysqli_query($con,"SELECT DISTINCT pnum,n_iter FROM pn_st where invpnum='$t' and stkpt='$t1'");
			$c=$result->num_rows;
			$i=0;
			while($row = mysqli_fetch_array($result))
			{
				$s[$i]=$row['pnum'];
				$t[$i]=$row['n_iter'];
				$i=$i+1;
			}
		}
		?>
			<br>
			<div>
				<label>1.PARTS PICK FROM</label>
				<input type="text" id="ia1"  list="combo-options1"  name="ia1"  placeholder="Enter Part Number" value="<?php echo $s[0];?>">
				<select name ="as1" id="as1" style="width:10%">
					<option value='1'>REGULAR</option>
				</select>
			</div>
			<br>
			<div>
				<label>2.PARTS PICK FROM</label>
				<input type="text" id="ia2"  list="combo-options1"  name="ia2" placeholder="Enter Part Number"value="<?php echo $s[1];?>">
				<select name ="as2" id="as2" style="width:10%">
				<?php
					if($t[1]==2)
					{
						echo"<option selected value='2'>REGULAR</option><option value='1'>ALTERNATE</option>";
					}
					else
					{
						echo"<option selected value='1'>ALTERNATE</option><option value='2'>REGULAR</option>";
					}
				?>
				</select>
			</div>
			<br>
			<div>
				<label>3.PARTS PICK FROM</label>
				<input type="text" id="ia3"  list="combo-options1"  name="ia3" placeholder="Enter Part Number"value="<?php echo $s[2];?>">
				<select name ="as3" id="as3" style="width:10%">
				<?php
					if($t[2]==3)
					{
						echo"<option selected value='3'>REGULAR</option><option value='1'>ALTERNATE</option>";
					}
					else
					{
						echo"<option selected value='1'>ALTERNATE</option><option value='3'>REGULAR</option>";
					}
				?>
				</select>
			</div>
			</br>
			<div>
					<input type="Submit" name="submit" value="SUBMIT"/>
			</div>
		</form>
	</div>
</body>
</html>