<?php
session_start();
if(isset($_SESSION['user']))
{
	$id=$_SESSION['user'];
	$activity="MANAGE ACCOUNT";
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
		<form method="POST" action="managedb.php">
			</br>
			<div>
				<label>USER ID NUMBER</label>
					<input type="text" id="idn" name="idn" readonly value="<?php
					echo $_SESSION['user'];
					?>">
			</div>
			<br/>
			<div>
				<label>USER NAME</label>
			<input type="text" placeholder="Enter user name" name ="name" id="name" readonly autocomplete="off" required value="<?php echo $_SESSION['username'];?>"/>
			</div>
			</br>
			<div>
				<label>PASSWORD</label>
					<input type="password" placeholder="Enter old password" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required name ="psw" id="psw" />	
			</div>
			<br>
			<div>
				<label>NEW PASSWORD</label>
					<input type="password" placeholder="Enter new password" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required name ="npsw" id="npsw" />	
			</div>
			</br>
			<div>
				<label>AREA ACCESSABLE</label>
				<input type="text" list="combo-options" placeholder="select user area" name ="acc" id="acc" autocomplete="off" required <?php if($_SESSION['access']=="All"){echo $_SESSION['access'];}else{echo "readonly value='".$_SESSION['access']."'";} ?>/>
			</div>
			<br>
			<div>
				<label>YOUR IP ADDRESS</label>
				<input type="text" placeholder="select user area" name ="ip" id="ip" autocomplete="off" required <?php if($_SESSION['access']=="All"){echo $_SESSION['access'];}else{echo "readonly value='".$_SESSION['ip']."'";} ?>/>
			</div>
			<?php
			echo"</datalist>";
			$con = mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				echo "connection failed";
			$query = "select distinct operation from m11";
			$result = $con->query($query);
			echo"<datalist id='combo-options'>";
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
