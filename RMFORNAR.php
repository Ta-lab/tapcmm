<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="Quality" || $_SESSION['access']!="ALL")
	{
		
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
		<h4 style="text-align:center"><label>RM</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'RM')" value="Export to Excel">
		</div>
		<br/></br>
		
		<div class="divclass">
		<form method="GET">	
			</br>
			
			<br>
			</form>
			<br>
			<?php
				
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				
				$conn1 = new mysqli($servername, $username, $password, "erpdb");
				
					echo'
						<table id="testTable" align="center">
						  <tr>
							<th>PNUM</th>
							<th>RAW MATERIAL</th>
							<th>FI RAW MATERIAL</th>
							<th>UOM</th>
							<th>BOM</th>
							<th>FOREMAN</th>
							<th>SUPPLIER</th>
							<th>FI SUPPLIER</th>
							<th>MCODE</th>
							<th>INTERNAL RM</th>
							<th>EXTERNAL RM</th>
							<th>RATE</th>
							
							
						  </tr>';
				
				
				$query = "SELECT * FROM(SELECT pnum,rmdesc,uom,useage,foreman FROM `m13`) AS Tm13 LEFT JOIN(SELECT scode,mcode,internal_rmdesc,external_rmdesc,uom,rate FROM erpdb.`m21_supplierpartdetail`) AS TSUP ON Tm13.rmdesc=TSUP.internal_rmdesc ORDER BY Tm13.rmdesc DESC";

				
				$result = $conn->query($query);
				while($row = $result->fetch_assoc()){
							
					echo"<tr><td>".$row['pnum']."</td>";
					echo"<td>".$row['rmdesc']."</td>";
					
					$sub_string = substr($row['rmdesc'], -4);
					if($sub_string=="- DH"){
						$str = $row['rmdesc'];
						$str = rtrim($str, $sub_string);
						echo"<td>".$str."</td>";
					}
					else if($sub_string=="- DM"){
						$str = $row['rmdesc'];
						$str = rtrim($str, $sub_string);
						echo"<td>".$str."</td>";
					}
					else
					{
						$str = $row['rmdesc'];
						echo"<td>".$str."</td>";
					}
					
					
					echo"<td>".$row['uom']."</td>";
					echo"<td>".$row['useage']."</td>";
					echo"<td>".$row['foreman']."</td>";
					echo"<td>".$row['scode']."</td>";
					
					$supcode=$row['scode'];
					
					$str1 = $row['rmdesc'].' - DM';
					$str2 = $row['rmdesc'].' - DH';
					
					if($row['scode']==""){
						$query2 = "SELECT scode,mcode,internal_rmdesc,external_rmdesc,uom,rate FROM `m21_supplierpartdetail` WHERE internal_rmdesc='$str' OR internal_rmdesc='$str1' OR internal_rmdesc='$str2' ";
						$result2 = $conn1->query($query2);
						$row2 = mysqli_fetch_array($result2);
						echo"<td>".$row2['scode']."</td>";
						echo"<td>".$row2['mcode']."</td>";
						echo"<td>".$row2['internal_rmdesc']."</td>";
						echo"<td>".$row2['external_rmdesc']."</td>";
						echo"<td>".$row2['rate']."</td>";
					
					}else{
						echo"<td>".$row['scode']."</td>";
					}
					
					echo"<td>".$row['mcode']."</td>";
					echo"<td>".$row['internal_rmdesc']."</td>";
					echo"<td>".$row['external_rmdesc']."</td>";
					echo"<td>".$row['rate']."</td>";
					
					
					
					
						
					echo"</tr>";
				}
			?>
		</div>
</body>
</html>
</html>