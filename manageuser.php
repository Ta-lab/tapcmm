<?php
session_start();
if(isset($_SESSION['user']) && $_SESSION['access']=="ALL")
{
	$id=$_SESSION['user'];
	$activity="MANAGE USER ACCOUNT";
	date_default_timezone_set('Asia/Kolkata');
	$time=date("Y-m-d g:i:s a");
	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
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
		<h4 style="text-align:center"><label>MANAGE ACCOUNT - <?php echo $_SESSION['username']; ?> </label></h4>
		<div class="divclass">
		<form method="POST" action="manageuserdb.php">
			</br>
			<div>
				<label>USER ID NUMBER</label>
					<input type="text" list="combo-options" id="idn" name="idn" onchange="reload0(this.form)" value="<?php
					if(isset($_GET['uid']) && $_GET['uid']!="")
					{
						echo $_GET['uid'];
					}
					else
					{
						echo $id;
					}
					?>">
			</div>
			<?php			
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$result = mysqli_query($con,"SELECT userid FROM admin1");
					echo "";
					echo"<datalist id='combo-options'>";
						while($row = mysqli_fetch_array($result))
							{
								echo "<option value='" . $row['userid'] . "'>" . $row['userid'] ."</option>";
							}
					echo"</datalist>";
					if(isset($_GET['uid']) && $_GET['uid']!="")
					{
						$t=$_GET['uid'];
					}
					else
					{
						$t=$_SESSION['user'];
					}
					$result1 = mysqli_query($con,"SELECT username,password,access,status FROM admin1 where userid='$t'");
					$row1 = mysqli_fetch_array($result1);
					$name=$row1['username'];
					$area=$row1['access'];
				?>
			<script>
			function reload0(form)
			{
				var p1 = document.getElementById("idn").value;
				self.location=<?php echo"'manageuser.php?uid='"?>+p1;
			}
		</script>
			<br/>
			<div>
				<label>USER NAME</label>
			<input type="text" placeholder="Enter user name" name ="name" id="name" readonly autocomplete="off" required value="<?php echo $name;?>"/>
			</div>
			<br>
			<div>
				<label>NEW PASSWORD</label>
					<input type="password" placeholder="Enter new password" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"  name ="npsw" id="npsw" />	
			</div>
			</br>
			<div>
				<label>AREA ACCESSABLE</label>
				<input type="text" list="combo-options1" placeholder="select user area" name ="acc" id="acc" autocomplete="off" required value="<?php echo $area;?>"/>
			</div>
			<br>
			<div>
				<label>STATUS</label>
				<input type="text" placeholder="select user area" name ="stat" id="stat" min="0" max="1" autocomplete="off" required value="<?php if($row1['status']=="0"){echo "0";}else{echo "1";} ?>"/>
			</div>
			<?php
			echo"</datalist>";
			$con = mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				echo "connection failed";
			$query = "select distinct operation from m11";
			$result = $con->query($query);
			echo"<datalist id='combo-options1'>";
			echo"<option value='NONE'>OUTPUT ONLY</option>";
			while ($row = mysqli_fetch_array($result))
			{
				echo "<option value='".$row['operation']."'>".$row['operation']."</option>";
			}
			?>
			</datalist>
			</br>
			<div>
					<input type="Submit" name="submit" id="submit"  value="SUBMIT"  onclick="myFunction()"/>
			</div>
		</form>
	</div>
</body>
</html>
