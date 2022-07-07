<?php
session_start();
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	header("Location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL" || $_SESSION['username']=="prabhahar")
	{
		$id=$_SESSION['user'];
		$activity="PPC PLANNING";
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
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
</head>
<body link="yellow">
<style>
.column
{
    float: left;
    width: 33%;
}
.column1
{
    float: left;
    width: 33%;
	display: none;
}
</style>
<div style="float:left">
		<a style='color:yellow;' href="inputlink.php"><label>Input</label></a>
	</div>
	<div style="float:right">
		<a style='color:yellow;' href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label> PPC PLANING [ IPP ]</label></h4>
	<div>
			
		<div style="float:left">
			<a style='color:yellow;' href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
		<?php
		
		
		
		
		?>
			
			<br>
			<?php
				$con = mysqli_connect('localhost','root','Tamil','mypcm');
				if(!$con)
				echo "connection failed";
				if(isset($_GET['rcno']) && $_GET['rcno']!="")
				{
					$rcno=$_GET['rcno'];
					echo'<br><br><table id="testTable" align="center">
					  <tr>
						<th>DATE</th>
						<th>STOCKING POINT (C)</th>
						<th>OPERATION</th>
						<th>RAW MATERIAL</th>
						<th>RM ISSUANCE (C)</th>
						<th>PART NUMBER</th>
						<th>ROUTE CARD</th>
						<th>PREVIOUS RC (C)</th>
						<th>PART ISSUED (C)</th>
						<th>PART REJECTED (C)</th>
						<th>PART RECEIVED (C)</th>
						<th>GIN NUMBER (C)</th>
						<th>HEAT NUMBER</th>
						<th>LOT NUMBER</th>
						<th>COIL NUMBER</th>
						<th> REASON (C)</th>
						<th>DELETE</th>
					</tr>';
					$query2 = "SELECT * FROM d12 where rcno='$rcno' OR prcno='$rcno'";
					$result2 = $con->query($query2);
					while($row1 = mysqli_fetch_array($result2))
					{
					echo"<tr><td><input style='width:100%' type='text' name=dt$row1[rowid] readonly   value='".$row1['date']."'></td>";
						echo"<td><input style='width:100%' type='text' name=sp$row1[rowid] value='".$row1['stkpt']."'></td>";
						echo"<td><input style='width:100%' type='text' name=op$row1[rowid] readonly  value='".$row1['operation']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rm$row1[rowid] readonly  value='".$row1['rm']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rq$row1[rowid] value='".$row1['rmissqty']."'></td>";
						echo"<td><input style='width:100%' type='text' name=pn$row1[rowid] readonly  value='".$row1['pnum']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rc$row1[rowid] readonly  value='".$row1['rcno']."'></td>";
						echo"<td><input style='width:100%' type='text' name=pc$row1[rowid] value='".$row1['prcno']."'></td>";
						echo"<td><input style='width:100%' type='text' name=pi$row1[rowid] value='".$row1['partissued']."'></td>";
						echo"<td><input style='width:100%' type='text' name=qr$row1[rowid] value='".$row1['qtyrejected']."'></td>";
						echo"<td><input style='width:100%' type='text' name=pr$row1[rowid] value='".$row1['partreceived']."'></td>";
						echo"<td><input style='width:100%' type='text' name=gn$row1[rowid] value='".$row1['inv']."'></td>";
						echo"<td><input style='width:100%' type='text' name=hn$row1[rowid] readonly  value='".$row1['heat']."'></td>";
						echo"<td><input style='width:100%' type='text' name=ln$row1[rowid] readonly  value='".$row1['lot']."'></td>";
						echo"<td><input style='width:100%' type='text' name=cn$row1[rowid] readonly  value='".$row1['coil']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rs$row1[rowid] value='".$row1['rsn']."'></td>";
						if($row1['rcno']=="")
						{
							echo"<td><a style='color:yellow;' href='delrcno.php?id=$row1[rowid]&rcno=$rcno'>DELETE</a></td>";
						}
						else
						{
							//echo"<td><a style='color:yellow;' href='delrcno.php?id=$row1[rowid]&rcno=$rcno'>DELETE</a></td>";
							echo"<td>DELETE</td>";
						}
						echo"</tr>";
					}
					echo "</table>";
					echo '<div>
						<input type="submit" name="submit" value="UPDATE">
					</div>';
				}
				
			?>
		</form>
	</body>
</html>
		