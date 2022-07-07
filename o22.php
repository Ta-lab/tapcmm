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
		<h4 style="text-align:center"><label> COMMIT VS ACTUAL REPORT  [ O22 ]</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
		<form method="GET">			
			<div class="find">
				<label>SELECT OPERATION</label>
					<select name ="prcno" id="prcno" onchange="reload1(this.form)">
					<option value="%%">ALL</option>";	
						<?php			
							$rat=$_GET['rat'];
							$date=0000-00-00;
					        $con = mysqli_connect('localhost','root','Tamil','mypcm');
				            if(!$con)
								echo "connection failed";
						    $query = "SELECT operation FROM m11 where opertype='OPERATION' and receive='A'";
						    $result = $con->query($query);  
							while ($row = mysqli_fetch_array($result)) 
						    {
								if($_GET['rat']==$row['operation'])
									echo "<option selected value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
								else if ($_GET['rat']=="MANUAL")
									echo "<option selected value='MANUAL'>MANUAL</option>";     
								else
									echo "<option value='" . $row['operation'] . "'>" . $row['operation'] ."</option>"; 
						    }
							echo "<option value='MANUAL'>MANUAL</option>";
							echo "</select></h1>";
						?>
						<script>
						function reload1(form)
						{
							var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
							var s4 = document.getElementById("week").value;
							if(s2=="%%")
							{
								self.location='o22.php?week='+s4;
							}
							else
							{
								self.location='o22.php?rat='+s2+'&week='+s4;
							}
							
						}
						</script>
			</div>
			<datalist id="weeklist" >
					<?php
						$result1 = $con->query("select distinct week from commit");
						echo"<option value=''>Select one</option>";
						while ($row1 = mysqli_fetch_array($result1)) 
						{
							if(isset($_GET['week'])==$row1['week'])
								echo "<option selected value='".$row1['week']."'>".$row1['week']."</option>";
							else
								echo "<option value='".$row1['week']."'>".$row1['week']."</option>";
						}
					?>
				</datalist>
			<div class="find1">
				<label>SELECT WEEK</label>
					<input type="text" required style="width:50%; background-color:white;" onchange=reload1(this.form) id="week" name="week" list="weeklist" value="<?php if(isset($_GET['week'])){echo $_GET['week'];}?>">
			</div>
			<br><br>
		</form>
	</div>
			<?php
			$t="%%";
			if(isset($_GET['week']) && $_GET['week']!="")
			{
				$d=$_GET['week'];
			}
			else
			{
				$query = "SELECT week FROM `d19`";
				$result = $con->query($query);
				$row = mysqli_fetch_array($result);
				$d=$row['week'];
			}
			$week=substr($d,7,7);
			$year=substr($d,0,4);
			function Start_End_Date_of_a_week($week,$year)
			{
				$time = strtotime("1 January $year", time());
				$day = date('w', $time);
				$time += ((7*$week)+1-$day)*24*3600;
				$dates[0] = date('Y-n-j', $time);
				$time += 6*24*3600;
				$dates[1] = date('Y-n-j', $time);
				return $dates;
			}
			$week=$week-1;
			$query2 = "select * from commit where week='$d'";
			$result = Start_End_Date_of_a_week($week,$year);
			$df=$result[0];
			$dl=$result[1];
			$servername = "localhost";
			$username = "root";
			$password = "Tamil";
			$conn = new mysqli($servername, $username, $password, "mypcm");
			echo'<table id="testTable" align="center">
				  <tr>
					<th>WEEK</th>
					<th>AREA</th>
					<th>PART NUMBER</th>
					<th>FOREMAN</th>
					<th>COMMIT QTY</th>
					<th>MATERIAL TAKEN QTY</th>
					<th>ACTUAL QTY</th>
					<th>PERCENT</th>
				  </tr>';
			$c=0;$a=0;
			if(isset($_GET['rat']) && $_GET['rat']=="MANUAL")
			{
				$t=$_GET['rat'];
				$query2 = "select * from commit  LEFT JOIN (SELECT DISTINCT pnum,foreman FROM m13) AS T ON T.pnum=commit.pnum where week='$d' and foremac='$t'";
				//echo "select * from commit where week='$d' and foremac='$t'";
			}
			else if(isset($_GET['rat']) && $_GET['rat']!="MANUAL")
			{
				$t=$_GET['rat'];
				$query2 = "select * from commit  LEFT JOIN (SELECT DISTINCT pnum,foreman FROM m13) AS T ON T.pnum=commit.pnum where week='$d' and foremac='CNC_SHEARING' and  pnum IN (select distinct pnum from m12 where operation='$t')";
				//echo "select * from commit where week='$d' and foremac='CNC_SHEARING' and  pnum IN (select distinct pnum from m12 where operation='$t')";
			}
			else
			{
				$query2 ="select * from commit  LEFT JOIN (SELECT DISTINCT pnum,foreman FROM m13) AS T ON T.pnum=commit.pnum  where  week='$d'";
				//echo "select * from commit where week='$d'";
			}
			$result2 = $conn->query($query2);
			while($row1 = mysqli_fetch_array($result2))
			{
				$c=$c+$row1['qty'];
				$t=$row1['pnum'];
				if($row1['foremac']=="CNC_SHEARING")
				{
					$query = "SELECT SUM(partreceived) as act FROM d12 WHERE prcno LIKE 'A20%' AND date>='$df' AND date<='$dl' AND pnum='$t'";
				}
				else
				{
					$query = "SELECT SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%') AND date>='$df' AND date<='$dl' AND pnum='$t'";
				}
				$result = $conn->query($query);
				$row = mysqli_fetch_array($result);
				$a=$a+round($row['act']);
				echo"<tr><td>$d</td>";
				echo"<td>".$row1['foremac']."</td>";
				echo"<td>".$t."</td>";
				echo"<td>".$row1['foreman']."</td>";
				echo"<td>".$row1['qty']."</td>";
				echo"<td>".$row1['issuedqty']."</td>";
				echo"<td>".round($row['act'])."</td>";
				if($row1['qty']>0)
				{
					echo"<td>".round(($row['act']*100)/$row1['qty'])." %</td>";
				}
				else
				{
					echo"<td>0 %</td>";
				}
				echo"</tr>";
			}
			echo"<td><h4 style='color : yellow;'>".$d."</h4></td><td colspan='2'>TOTAL COMMIT</td><td><h4 style='color : yellow;'>".$c."</h4></td>
			<td colspan='1'>TOTAL ACHIEVED</td><td><h4 style='color : yellow;'>".$a."</h4></td><td><h4 style='color : yellow;'>".round(($a*100)/$c)." %</h4></td>";
			?>
		</div>
		
</body>
</html>