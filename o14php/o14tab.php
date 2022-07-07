<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="STOCK REPORT";
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
		<h4 style="text-align:center"><label>STOCK REPORT  [ O14 ]</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
		<form method="GET">			
			<div class="find3">
				<label>STOCKING POINT</label>
					<select name ="prcno" id="prcno" onchange="reload1(this.form)">
					<option value="%%">ALL</option>";	
						<?php			
							$rat=$_GET['rat'];
							$date=0000-00-00;
					        $con = mysqli_connect('localhost','root','Tamil','mypcm');
				            if(!$con)
								echo "connection failed";
						    $query = "SELECT operation FROM m11 where opertype='STOCKING POINT' and operation!='Stores'";
						    $result = $con->query($query);  
							while ($row = mysqli_fetch_array($result)) 
						    {
								if($_GET['rat']==$row['operation'])
									echo "<option selected value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
								else
									echo "<option value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";              
						    }
							echo "</select></h1>";
						?>
						<script>
						function reload1(form)
						{
							var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
							if(s2=="%%")
							{
								self.location='o14tab.php';
							}
							else
							{
								self.location='o14tab.php?&rat='+s2;
							}
							
						}
						</script>
			</div>
			<br>
		</form>
	</div>
			<?php
			if(isset($_GET["rat"]))
			{
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>STOCKING POINT</th>
						<th>DATE</th>
						<th>PART NUMBER</th>
						<th>PREV  RC  NUMBER</th>
						<th>QUANTITY  IN  STOCK</th>
						<th>NO OF DAYS</th>
					  </tr>';
				$query2 = "SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt='$rat'";
				//echo "SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt='$rat'";
				$result2 = $conn->query($query2);
				while($row1 = mysqli_fetch_array($result2))
				{
					echo"<tr><td>$rat</td>";
					echo"<td>".$row1['date']."</td>";
					echo"<td>".$row1['pnum']."</td>";
					echo"<td>".$row1['prc']."</td>";
					echo"<td>".$row1['s']."</td>";
					echo"<td>".$row1['days']."</td>";
					echo"</tr>";
				}
			}
			else
			{
				$t="%%";
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>STOCKING POINT</th>
						<th>DATE</th>
						<th>PART NUMBER</th>
						<th>PREV  RC  NUMBER</th>
						<th>QUANTITY  IN  STOCK</th>
						<th>NO OF DAYS</th>
					  </tr>';
					  
				$query2 = "select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0";
				//echo "select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0";
				$result2 = $conn->query($query2);
				while($row1 = mysqli_fetch_array($result2))
				{
					echo"<tr><td>".$row1['stkpt']."</td>";
					echo"<td>".$row1['date']."</td>";
					echo"<td>".$row1['pnum']."</td>";
					echo"<td>".$row1['prc']."</td>";
					echo"<td>".$row1['s']."</td>";
					echo"<td>".$row1['days']."</td>";
					echo"</tr>";
				}
			}
			?>
		</div>
		
</body>
</html>