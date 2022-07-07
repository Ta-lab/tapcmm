<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="PROD ISS REP";
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
		<h4 style="text-align:center"><label>STORE RAW MATERIAL ISSUANCE TO PRODUCTION</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'PRODUCTION ISSUE')" value="Export to Excel">
	</div>
	<script>
		function reload(form)
		{
			var s0 = document.getElementById("f").value;
			var s1 = document.getElementById("tt").value;
			self.location='prod_iss3.php?f='+s0+'&tt='+s1;
		}
	</script>
	<br><br>
			<div class="find">
				<label>FROM DATE</label>
							<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['f']))
							{
								echo $_GET['f'];
							}
							else
							{
								echo date('Y-m-d',strtotime('-30 days'));
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
		
	<br><br><br>
	<div class="divclass">
			<?php
			
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-30 days'));
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
				$conne = new mysqli($servername, $username, $password, "storedb");
				$connec = new mysqli($servername, $username, $password, "erpdb");
				
				
			echo'<table id="testTable" align="center">
				<tr>
					<th>DATE</th>
					<th>RM</th>
					<th>RM ISSUED QTY</th>
					<th>PNUM</th>
					<th>RCNO</th>
					<th>GRN</th>
					<th>SUPPLIER NAME</th>
					<th>RM RATE</th>
					<th>VALUE</th>
					<th>RC CLOSED DATE</th>
					<th></th>
					
				</tr>';
				//$query = "SELECT * FROM (SELECT date,stkpt,rm,rmissqty,pnum,rcno,inv FROM `d12` WHERE stkpt LIKE '%Stores%' AND date>='2020-05-01') AS PROISS LEFT JOIN(SELECT grnnum,ponum,sname FROM storedb.`receipt`) AS RECP ON PROISS.inv=RECP.grnnum";
				$query = "SELECT * FROM (SELECT date,stkpt,rm,rmissqty,pnum,rcno,inv FROM `d12` WHERE stkpt LIKE '%Stores%' AND date>='$f' AND date<='$tt') AS PROISS LEFT JOIN(SELECT grnnum,ponum,sname,rmdesc FROM storedb.`receipt`) AS RECP ON PROISS.inv=RECP.grnnum LEFT JOIN(SELECT ponumber,scode,internal_description,rate FROM erpdb.`db_po_part_detail`) AS BYPO ON RECP.ponum=BYPO.ponumber AND PROISS.rm=BYPO.internal_description ORDER BY date";
				$result = $conn->query($query);
				$rmissqty=0;
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['rm']."</td>";
					echo"<td>".$row['rmissqty']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['rcno']."</td>";
					echo"<td>".$row['inv']."</td>";
					//echo"<td>".$row['ponum']."</td>";
					echo"<td>".$row['sname']."</td>";
					
					$sname=$row['sname'];
					$scode=$row['scode'];
					$rmissqty=$row['rmissqty'];
					
					if($scode!='')
					{
						//echo"<td>".$row['scode']."</td>";
						echo"<td>".$row['rate']."</td>";
						echo"<td>".$row['rate']*$rmissqty."</td>";
					}
					else
					{
						$query1 = "SELECT scode,sname FROM erpdb.`m20_suppliermaster` WHERE sname='$sname'";
						$result1 = $connec->query($query1);
						$row1 = mysqli_fetch_array($result1);
						//echo"<td>".$row1['scode']."</td>";
						
						$scode1=$row1['scode'];
						$rm=$row['rm'];
						$rmissqty=$row['rmissqty'];
						//$query2 = "SELECT scode,internal_rmdesc,rate FROM `m21_supplierpartdetail` WHERE scode='$scode1' AND internal_rmdesc LIKE '%$rm%'";
						
						$query2 = "SELECT scode,internal_description,rate FROM `db_po_part_detail` WHERE scode='$scode1' AND internal_description LIKE '%$rm%'";
						$result2 = $connec->query($query2);
						$row2 = mysqli_fetch_array($result2);
						//echo"<td>".$row2['rate']."</td>";
						$rate2=$row2['rate'];
					
						$query3 = "SELECT * FROM `rmprice` WHERE sname='$sname' AND rmdesc='$rm'";
						$result3 = $conn->query($query3);
						$row3 = mysqli_fetch_array($result3);
						$rate3=$row3['rate'];
						
						$query4 = "SELECT * FROM `rmprice` WHERE rmdesc='$rm'";
						$result4 = $conn->query($query4);
						$row4 = mysqli_fetch_array($result4);
						$rate4=$row4['rate'];
						
						
						if($row2['rate']!=''){
							echo"<td>".$row2['rate']."</td>";	
							
							echo"<td>".$rate2*$rmissqty."</td>";	
						}
						else if($row3['rate']!=''){
							echo"<td>".$row3['rate']."</td>";	
							
							echo"<td>".$rate3*$rmissqty."</td>";	
						}
						else{
							echo"<td>".$row4['rate']."</td>";
							
							echo"<td>".$rate4*$rmissqty."</td>";
						}
						
					
					}
					
						$rcno = $row['rcno'];
						$query5 = "SELECT * FROM `d11` WHERE rcno='$rcno'";
						$result5 = $conn->query($query5);
						$row5 = mysqli_fetch_array($result5);
						$rcstatus = $row5['closedate'];
						if($rcstatus=='0000-00-00'){
							echo"<td>OPEN</td>";
						}else{
							echo"<td>".$rcstatus."</td>";
						}
					//echo"<td>".$row['rate']."</td>";
					
					//echo"<td>".$row['rate']*$rmissqty."</td>";
					
					echo"</tr>";
					
				}
				
				
				
			?>
		</div>
		
</body>
</html>

