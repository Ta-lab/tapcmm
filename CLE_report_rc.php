<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="CLE";
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
		<h4 style="text-align:center"><label>CLE UNIT 2 OPERATION TRANSACTION REPORT</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'CLE')" value="Export to Excel">
	</div>
	<br><br>
	
			<script>
				function reload(form)
				{
					var s0 = document.getElementById("f").value;
					var s1 = document.getElementById("tt").value;
					var s2 = document.getElementById("pn").value;
					
					self.location='CLE_report_rc.php?f='+s0+'&tt='+s1+'&pn='+s2;
				}
			</script>
		
	
	<div class="divclass">
		<form method="GET">			
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
			</div>
			
			<div class="find1">
				<label>TILL DATE</label>
						<input type="date" id="tt" name="tt"  onchange="reload(this.form)" value="<?php
						if(isset($_GET['tt']))
						{
							echo $_GET['tt'];
						}
						else
						{
							echo date('Y-m-d');
						}
						?>"/>
			</div>
			
			<div class="find2">
				<label>PART NUMBER</label>
						<input type="text" id="pn" name="pn" list="partlist"  onchange="reload(this.form)" value="<?php
						if(isset($_GET['pn']))
						{
							echo $_GET['pn'];
						}
						else
						{
							echo "";
						}
						?>"/>
			</div>
			
			
			<br><br><br>
			
			<datalist id="partlist" >
			<?php
			$con = mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				echo "connection failed";
			if(isset($_GET['cc']) && $_GET['cc']!="")
			{
				$c=$_GET['cc'];
				$query = "SELECT distinct pn FROM invmaster where ccode='$c'";
			}
			else
			{
				$query = "SELECT distinct pn FROM invmaster";
			}
			$result = $con->query($query);
			echo"<option value=''>Select one</option>";
			while ($row = mysqli_fetch_array($result)) 
			{
				if($_GET['pn']==$row['pn'])
					echo "<option selected value='".$row['pn']."'>".$row['pn']."</option>";
				else
					echo "<option value='".$row['pn']."'>".$row['pn']."</option>";
			}
			?>
			</datalist>
			
			
		</form>
			
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
			
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
			
			echo'<table id="testTable" align="center">
				<tr>
					<th>DATE</th>
					<th>RCNO</th>
					<th>PREVIOUS DCNO</th>
					<th>OPERATION</th>
					<th>TYPE</th>
					<th>PART NO</th>
					<th>ISSUED QTY</th>
					<th>RECEIVED QTY</th>
					<th>REJECTED QTY</th>
					<th>QTY UNDER PROCESS</th>
					<th></th>
				</tr>';
				//$query = "SELECT * FROM `f_insp` WHERE prcno LIKE '%L20%'";
				
					if(isset($_GET['f']) && $_GET['f']!="" && isset($_GET['tt']) && $_GET['tt']!="")
					{
						$query = "SELECT * FROM(SELECT date,stkpt,pnum,rcno,prcno AS dcno,rsn,SUM(partissued) AS issued_qty FROM `d12` WHERE rcno LIKE '%L20%' AND date>='$f' AND date<='$tt'  GROUP BY rcno) AS TD12
						LEFT JOIN(SELECT prcno,SUM(partreceived) AS received_qty FROM `d12` WHERE prcno LIKE '%L20%' AND date>='$f' AND date<='$tt'  GROUP BY prcno) AS TD121 ON TD12.rcno=TD121.prcno";
					}
					if(isset($_GET['pn']) && $_GET['pn']!="")
					{
						$p=$_GET['pn'];
						$query = "SELECT * FROM(SELECT date,stkpt,pnum,rcno,prcno AS dcno,rsn,SUM(partissued) AS issued_qty FROM `d12` WHERE rcno LIKE '%L20%' AND date>='$f' AND date<='$tt' AND pnum='$p' GROUP BY rcno) AS TD12
						LEFT JOIN(SELECT prcno,SUM(partreceived) AS received_qty,SUM(qtyrejected) AS rejected_qty FROM `d12` WHERE prcno LIKE '%L20%' AND date>='$f' AND date<='$tt' AND pnum='$p'  GROUP BY prcno) AS TD121 ON TD12.rcno=TD121.prcno";
					}
					else
					{
						$query = "SELECT * FROM(SELECT date,stkpt,pnum,rcno,prcno AS dcno,rsn,SUM(partissued) AS issued_qty FROM `d12` WHERE rcno LIKE '%L20%' AND date>='$f' AND date<='$tt' GROUP BY rcno) AS TD12
						LEFT JOIN(SELECT prcno,SUM(partreceived) AS received_qty,SUM(qtyrejected) AS rejected_qty FROM `d12` WHERE prcno LIKE '%L20%' AND date>='$f' AND date<='$tt' GROUP BY prcno) AS TD121 ON TD12.rcno=TD121.prcno";
					}
				
				/*$query = "SELECT * FROM(SELECT date,stkpt,pnum,rcno,rsn,SUM(partissued) AS issued_qty FROM `d12` WHERE rcno LIKE '%L20%') AS TD12
LEFT JOIN(SELECT prcno,SUM(partreceived) AS received_qty FROM `d12` WHERE prcno LIKE '%L20%') AS TD121 ON TD12.rcno=TD121.prcno";
				*/
				$result = $conn->query($query);
				$total=0;
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['rcno']."</td>";
					echo"<td>".$row['dcno']."</td>";
					echo"<td>".$row['stkpt']."</td>";
					echo"<td>".$row['rsn']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['issued_qty']."</td>";
					
					if($row['received_qty']==""){
						echo"<td>".'0'."</td>";
					}else{
						echo"<td>".$row['received_qty']."</td>";
					}
					
					if($row['rejected_qty']==""){
						echo"<td>".'0'."</td>";
					}else{
						echo"<td>".$row['rejected_qty']."</td>";
					}
					
					
					
					$bal = $row['issued_qty'] - ($row['received_qty'] + $row['rejected_qty']);
					
					echo"<td>".$bal."</td>";
					
					echo"</tr>";
				}
				
			?>
		</div>
		
</body>
</html>

