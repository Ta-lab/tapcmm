<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['user']=="123")
	{
		$id=$_SESSION['user'];
		$activity="ADMIN";
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
?><!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<script src = "js\excelreport.js"></script>
	<link rel="stylesheet" type="text/css" href="design1.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
		<h4 style="text-align:center"><label>ADMIN LOG</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<br><br>
	<div class="divclass">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
			
			echo'<table id="testTable" align="center">
				<tr>
					<th>USER ID</th>
					<th>USER NAME</th>
					<th>LOGIN STATUS</th>
					<th>LOGIN STATUS</th>
					<th>ACTIVITY</th>
					<th>LAST SEEN</th>
					<th>IP</th>
					<th></th>
				</tr>';
				$query = "SELECT * FROM `admin1`";
				$result = $conn->query($query);
				$total=0;
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr>";
					//echo"<td><a href='specific_user_log.php?userid=".$row['userid']."'>".$row['userid']."</a></td>";
					echo"<td>".$row['userid']."</td>";
					echo"<td>".$row['username']."</td>";
					if($row['status']=='0'){
						echo"<td>OUT</td>";
					}else{
						echo"<td>IN</td>";
						//echo"<td>".$row['status']."</td>";
					}
					echo"<td><a href='admin_login_db.php?userid=".$row['userid']."'>LOGOUT</a></td>";
					echo"<td>".$row['activity']."</td>";
					echo"<td>".$row['lastact']."</td>";
					echo"<td>".$row['ip']."</td>";
					echo"</tr>";
				}
				
				
			?>
		</div>
		
</body>
</html>

