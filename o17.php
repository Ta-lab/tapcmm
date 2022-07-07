<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="ROUTE CARD LIST TO DISPATCH";
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
		<h4 style="text-align:center"><label>DESPATCH ROUTE CARD LIST</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'OPEN RCNO')" value="Export to Excel">
		</div>
		<br/></br>
		<form method="POST" action='i141.php'>	
			</br>
			<div class="find">
				<label> DATE OF INVOICE </label>
							<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['f']))
							{
								echo $_GET['f'];
							}
							else
							{
								echo date('Y-m-d',strtotime('0 days'));
							}
							?>"/>
							<script>
						function reload(form)
						{
							var s0 = document.getElementById("f").value;
							self.location='o17.php?f='+s0;
						}
					</script>
			</div>
			<br>
			</form>
		<div class="divclass">
		
			<br>
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
					echo'
									<table id="testTable" align="center">
									  <tr>
										<th>INV NO</th>
										<th>INV_DATE</th>
										<th>PART NO</th>
										<th>PREV RC NUMBER</th>
										<th>QUANTITY</th>
									  </tr>';
				$d=date('Y-m-d',strtotime('0 days'));
				if(isset($_GET['f']) && $_GET['f']!='')
				{
					$d=$_GET['f'];
				}
				$query = "select * from d12 where date='$d' and prcno not like 'D%' and stkpt='FG For Invoicing' AND rcno!='' AND rcno NOT LIKE 'D%'";
				//echo "select * from d12 where date='$d' and prcno not like 'D%' and stkpt='FG For Invoicing' AND rcno!='' AND rcno NOT LIKE 'D%'";
				$result = $conn->query($query);
				while($row = $result->fetch_assoc()){
					$d = date("d-m-Y", strtotime($row['date']));
					echo" <tr>
						<td>".$row['rcno']."</td><td>".$d."</td><td>".$row['pnum']."</td><td>".$row['prcno']."</td><td>".$row['partissued']."</td>";
					echo"</tr>";
				}
			?>
		</div>
</body>
</html>