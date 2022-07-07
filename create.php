<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="CREATING USER ACCOUNT";
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
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Home</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>CREATE USER ACCOUNT [CUA]</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action="createdb.php">
			</br>
			<br>
				<label> Account Type </label>
				<select id="type" name="type" onchange="reload(this.form)">
					<?php
					if(isset($_GET['type']))
					{
						if($_GET['type']==1)
						{
							echo "<option selected value='1'> TEMPORARY USER (ONE DAY) </option>";
							echo "<option selected value='0'> PERMANENT USER </option>";
							echo "<option value='2'> TEMPORARY USER (ONE TIME) </option>";
						}
						else if($_GET['type']==2)
						{
							echo "<option selected value='2'> TEMPORARY USER (ONE TIME) </option>";
							echo "<option selected value='0'> PERMANENT USER </option>";
							echo "<option value='1'> TEMPORARY USER (ONE DAY) </option>";
						}
						else
						{
							echo "<option selected value='0'> PERMANENT USER </option>";
							echo "<option value='1'> TEMPORARY USER (ONE DAY) </option>";
							echo "<option value='2'> TEMPORARY USER (ONE TIME) </option>";
						}
					}
					else
					{
						echo"<option value=''>Select Account Type</option>";
						echo "<option selected value='0'> PERMANENT USER </option>";
						echo "<option value='1'> TEMPORARY USER (ONE DAY) </option>";
						echo "<option value='2'> TEMPORARY USER (ONE TIME) </option>";
					}
					?>
				</select>
																					
																																											
				<script>
					function reload(form)
					{	
						var val=form.type.options[form.type.options.selectedIndex].value; 							
						self.location='create.php?type='+val;
					}
				</script>
			</br></br>
			<div>
				<label>USER ID NUMBER</label>
					<input type="text" readonly id="idn" name="idn" value="<?php
					if(isset($_GET['type']))
					{
						$t=$_GET['type'];
						$con=mysqli_connect('localhost','root','Tamil','mypcm');
						if(!$con)
							die(mysqli_error());
						$result = mysqli_query($con,"SELECT max(userid) as user FROM admin1 where temp='$t'");
						$row = mysqli_fetch_array($result);
						if($row['user']==0)
						{
							echo 200;
						}
						else
						{
							echo "124";
						}
					}
					?>">
			</div>
			<br/>
			<div>
				<label>USER NAME</label>
			<input type="text" placeholder="Enter user name" name ="name" id="name" autocomplete="off" required />
			</div>
			</br>
			<div>
				<label>PASSWORD</label>
					<input type="password" placeholder="Enter password" autocomplete="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required name ="psw" id="psw" />	
			</div>
			</br>
			<div>
				<label>ACCESS AREA</label>
				<input type="text" list="combo-options" placeholder="select user area" name ="acc" id="acc" autocomplete="off" required />
			</div>
			<?php
			echo"</datalist>";
			$con = mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				echo "connection failed";
			$query = "select distinct operation from m11 where opertype='STOCKING POINT'";
			$result = $con->query($query);
			echo"<datalist id='combo-options'>";
			echo"<option value='NONE'>OUTPUT ONLY</option>";
			echo"<option value='Quality'>Quality</option>";
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
