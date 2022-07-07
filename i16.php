<?php
session_start();

if(isset($_SESSION['user']))
{
	
	{
		$id=$_SESSION['user'];
		$activity="RM RETURN REPORT";
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
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
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
	<h4 style="text-align:center"><label>STORES RECEIPT UPDATION</label></h4>
	<div class="divclass">
	<?php
	if(isset($_POST['submit'])){
			$tdate = $_POST['tdate'];
			$type = $_POST['type'];
			$rmdesc = $_POST['rmdesc'];
			$inum = $_POST['inum'];
			$idate = $_POST['idate'];
			$gnum = $_POST['gnum'];
			$gdate = $_POST['gdate'];
			$snam = $_POST['snam'];
			$rnum = $_POST['rnum'];
			$mnam = $_POST['mnam'];
			$hno = $_POST['hno'];
			$lno = $_POST['lno'];
			$cno = $_POST['cno'];
			header("location: i161.php?tdate=$tdate&type=$type&rmdesc=$rmdesc&inum=$inum&idate=$idate&gnum=$gnum&gdate=$gdate&snam=$snam&rnum=$rnum&mnam=$mnam&hno=$hno&lno=$lno&cno=$cno");
		}
		?>
		<?php
		if(isset($_POST['sub']))
		{
			$tdate = $_POST['tdate'];
			$type = $_POST['type'];
			$rmdesc = $_POST['rmdesc'];
			$inum = $_POST['inum'];
			header("location: i16.php?tdate=$tdate&rat=$type&rmdesc=$rmdesc&inum=$inum");
		}
		?>
		<form method="POST" action="i16.php">			
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
				<label>RM TYPE</label>
				<select name ='type' id='type' onchange="reload1(this.form)">
					<option value=''>Choose the Operation</option>
						<?php
						if($_GET['rat']=='wire')
							echo"<option selected value='wire'>Wire</option><option value='other'>Others</option>";
						else
							echo"<option value='wire'>Wire</option><option selected value='other'>Others</option>";
						?>
				</select>
			</div>
			<br/>
			<script>
				function reload1(form)
				{
					var val=form.type.options[form.type.options.selectedIndex].value; 
					var s = document.getElementById("tdate").value;
					self.location='i16.php?tdate='+s+'&rat=' + val ;
					
				}
			</script>
			
			<div>
				<label>RAW MATERIAL</label>
				<select name ="rmdesc" id="rmdesc">
					<?php			
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
						if(!$con)
						echo "connection failed";
						$query = "select distinct rmdesc from m13";
					    $result = $con->query($query);
						echo"<option value=''>Select one</option>";
				        while ($row = mysqli_fetch_array($result)) 
						{
							if($_GET['rmdesc']==$row['rmdesc'])
								echo "<option selected value='" . $row['rmdesc'] . "'>" . $row['rmdesc'] ."</option>";							
							else								
								echo "<option value='" . $row['rmdesc'] . "'>" . $row['rmdesc'] ."</option>";          
						}	
					?>
				</select>
			</div>
			</br>
			<div>
				<label>INVOICE NO</label>
				<input type="text" id="inum" name="inum" placeholder="Enter Invoice Number" value="<?php
					if(isset($_GET['inum']))
					{
						echo $_GET['inum'];
					}
					?>"/>
			</div>
			</br>
			<div>
				<label>INVOICE DATE</label>
				<input type="date" id="idate" name="idate" placeholder="Enter Invoice Date"/>
			</div>
			</br>
			<div>
				<label>GIN NO</label>
				<input type="text" id="gnum" name="gnum" placeholder="Enter GIN Number"/>
			</div>
			</br>
			<div>
				<label>GIN DATE</label>
				<input type="date" id="gdate" name="gdate" placeholder="Enter Invoice Date"/>
			</div>
			</br>
			<div>
				<label>SUPPLIER NAME</label>
				<input type="text" id="snam" name="snam" placeholder="Enter SUPPLIER NAME"/>
			</div>
			</br>
			<div>
				<label>RMTC NO</label>
				<input type="text" id="rnum" name="rnum" placeholder="Enter RMTC Number"/>
			</div>
			</br>
			<div>
				<label>MANUFACTURER</label>
				<input type="text" id="mnam" name="mnam" placeholder="Enter MANUFACTURER NAME"/>
			</div>
			<br>
				<?php
				if(isset($_GET['rat'])) 
				{	 
					$rat=$_GET['rat'];
					if($rat=='wire')
					{
						echo'<div>
							<label>HEAT NO</label>
								<input type="text" id="hno" name="hno" placeholder="Enter Heat No"/>
						</div>
						</br>
						<div>
							<label>LOT NO</label>
								<input type="text" id="lno" name="lno" placeholder="Enter Lot No "/>
						</div>
						<br>
						<div>
							<label>COIL NO</label>
								<input type="text" id="cno" name="cno" placeholder="Enter coil No"/>
						</div>
						</br>';
					}
				}
				?>
			<div>
				<input type="Submit" name="submit" id="submit"  value="SUBMIT"  onclick="myFunction()">
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