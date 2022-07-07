<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="Stores" || $_SESSION['access']=="ALL" || $_SESSION['user']=="100")
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
		<h4 style="text-align:center"><label>MATERIAL RETURN REPORT</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'OPEN RCNO')" value="Export to Excel">
		</div>
		<br/></br>
		
		<div class="divclass">
		<form method="GET">	
			</br>
			<div class="find">
				<label>FROM DATE</label>
							<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['f']))
							{
								echo $_GET['f'];
							}
							else
							{
								echo date('Y-m-d',strtotime('-1 days'));
							}
							?>"/>
							<script>
						function reload(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='return.php?f='+s0+'&tt='+s1;
						}
					</script>
			</div>
			
			<div class="find1">
				<label>TILL DATE</label>
							<input type="date" id="tt" name="tt"  onchange="reload0(this.form)" value="<?php
							if(isset($_GET['tt']))
							{
								echo $_GET['tt'];
							}
							else
							{
								echo date('Y-m-d');
							}
							?>"/>
							<script>
						function reload0(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='return.php?f='+s0+'&tt='+s1;
						}
					</script>
			</div>
			
			<br>
			</form>
			<br>
			<?php
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-1 days'));
					$tt = date('Y-m-d');
				}
				else
				{
					$f = $_GET['f'];
					$tt = $_GET['tt'];
				}
				$dt = "00-00-0000";
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				$query = "select d14.date,d11.pnum,d14.rcno,qty,ret,closedate,rm,inv,heat,coil from d14 join d11 on d11.rcno=d14.rcno JOIN d12 ON d11.rcno=d12.rcno where d14.date>='$f' and d14.date<='$tt' ORDER BY d14.date";
				//echo "select d14.date,pnum,d14.rcno,qty,ret,closedate,rm,inv,heat,coil from d14 join d11 on d11.rcno=d14.rcno JOIN d12 ON d11.rcno=d12.rcno where d14.date>='$f' and d14.date<='$tt'";
				//echo "select d14.date,d14.rcno,qty,ret,closedate from d14  join d11 on d11.rcno=d14.rcno where d14.date>='$f' and d14.date<='$tt'";
				$result = $conn->query($query);
				echo'<table id="testTable" align="center">
									  <tr>
										<th>DATE</th>
										<th>RC NUMBER</th>
										<th>RAW MATERIAL</th>
										<th>PART NUMBER</th>
										<th>GIN NUMBER</th>
										<th>HEAT NUMBER</th>
										<th>COIL NUMBER</th>
										<th>QTY BEFORE RETURN</th>
										<th>RETURN QTY</th>
										<th>AFTER RETURN</th>
										<th>STATUS</th>
									  </tr>';
					while($row = mysqli_fetch_array($result)){
						$q=$row['qty'];
						$r=$row['ret'];
						$q=$q-$r;
						if($row['closedate']=="0000-00-00")
						{
							$stat="NOT CLOSED";
						}
						else
						{
							$stat="CLOSED";
						}
						echo "</tr><tr><td>".$row['date']."</td><td>".$row['rcno']."</td><td>".$row['rm']."</td><td>".$row['pnum']."</td><td>".$row['inv']."</td><td>".$row['heat']."</td><td>".$row['coil']."</td><td>".round($row['qty'],2)."</td><td>".round($row['ret'],2)."</td><td>".round($q,2)."</td><td>".$stat."</td></tr>";
					}
				?>
		</div>
</body>
</html>