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
	if($_SESSION['access']=="Stores" || $_SESSION['access']=="ALL" || $_SESSION['user']=="100")
	{
		$id=$_SESSION['user'];
		$activity="GRN TRANSACTION DETAILS";
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
<body>
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
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label>GRN NUMBER BASED ENTRY & STOCK VERIFICATION [ I27 ]</label></h4>
	<div>
			
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
		<script>
			function reload(form)
			{
				var p4 = document.getElementById("p").value;
				self.location=<?php echo"'i27.php?grnnum='"?>+p4;
			}
		</script>
	<div class="divclass">
		<form id="form" name="form" method="post">
			</br>
					<?php
					echo '<datalist id="grnlist" >';
						$con = mysqli_connect('localhost','root','Tamil','storedb');
							if(!$con)
								echo "connection failed";
						$result1 = $con->query("select distinct grnnum from receipt");
						echo"<option value=''>Select one</option>";
						while ($row1 = mysqli_fetch_array($result1)) 
						{
							if(isset($_GET['grnnum'])==$row1['grnnum'])
								echo "<option selected value='".$row1['grnnum']."'>".$row1['grnnum']."</option>";
							else
								echo "<option value='".$row1['grnnum']."'>".$row1['grnnum']."</option>";
						}
						echo '</datalist>';
						$result = mysqli_query($con,"SELECT DISTINCT pnum FROM m13");
						echo "";
						echo"<datalist id='parts'>";
							while($row = mysqli_fetch_array($result))
								{
									echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
								}
						echo"</datalist>";
					?>
				
			<div class="find">
				<label> &nbsp;GRN NUMBER </label>
				<input type="text" style="width:50%;border:2px,red;" class='s' onchange=reload(this.form) id="p" name="rc" list="grnlist" value="<?php if(isset($_GET['grnnum'])){echo $_GET['grnnum'];}?>">
			</div>
			<?php
			if(isset($_GET['grnnum']) && $_GET['grnnum']!="")
			{
				echo '<div class="find1">';
				$grnnum=$_GET['grnnum'];
				$result1 = $con->query("select receipt.grnnum,sname,rmdesc,quantity_received as inward,inspdb.quantityaccepted as ok,inspdb.quantityrejected as rej,reason from receipt LEFT JOIN inspdb ON receipt.grnnum=inspdb.grnnum where receipt.grnnum='$grnnum'");
				$row = mysqli_fetch_array($result1);
				if($row['sname']!="")
				{
					echo "<label>SUPPLIER NAME : $row[sname]</label></div>";
				}
				echo '<div class="find2"><label>RAW MATERIAL  : '.$row['rmdesc'].'</label></div><br><br>';
				echo '<div class="find"><label>&nbsp;TOTAL QUANTITY INWARDED  : '.$row['inward'].'</label></div>';
				echo '<div class="find1"><label>TOTAL QUANTITY RECEIVED  : '.$row['ok'].'</label></div>';
				echo '<div class="find2"><label>TOTAL QUANTITY REJECTED  : '.$row['rej'].'</label></div>';
				
				
			}
			?>
			
			<br>
			<?php
				$con = mysqli_connect('localhost','root','Tamil','mypcm');
				if(!$con)
				echo "connection failed";
				$p="";$r1=0;$r2=0;$r3=0;
				if(isset($_GET['grnnum']) && $_GET['grnnum']!="")
				{
					$grnnum=$_GET['grnnum'];
					echo'<br><table id="testTable" align="center">
					  <tr>
						<th>DATE</th>
						<th>RAW MATERIAL</th>
						<th>RM ISSUED QUANTITY (IN kgs)</th>
						<th>PART NUMBER</th>
						<th>ROUTE CARD</th>
						<th>REJECTED QUANTITY 	(IN Nos)</th>
						<th> OK QUANTITY (IN Nos)</th>
						<th>GIN NUMBER</th>
						<th>HEAT NUMBER</th>
						<th>LOT NUMBER</th>
						<th>COIL NUMBER</th>
						<th> ENTERED BY </th>
					</tr>';
					$query2 = "SELECT * FROM d12 where inv='$grnnum'";
					$result2 = $con->query($query2);
					while($row1 = mysqli_fetch_array($result2))
					{
						$r1=$r1+$row1['rmissqty'];
						echo"<tr><td><input style='width:100%' type='text' name=dt$row1[rowid] readonly value='".$row1['date']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rm$row1[rowid] readonly value='".$row1['rm']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rq$row1[rowid] readonly value='".$row1['rmissqty']."'></td>";
						echo"<td><input style='width:100%' type='text' name=pn$row1[rowid] readonly value='".$row1['pnum']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rc$row1[rowid] readonly value='".$row1['rcno']."'></td>";
						echo"<td><input style='width:100%' type='text' name=qr$row1[rowid] readonly value='".$row1['qtyrejected']."'></td>";
						echo"<td><input style='width:100%' type='text' name=pr$row1[rowid] readonly value='".$row1['partreceived']."'></td>";
						echo"<td><input style='width:100%' type='text' name=gn$row1[rowid] readonly value='".$row1['inv']."'></td>";
						echo"<td><input style='width:100%' type='text' name=hn$row1[rowid] readonly value='".$row1['heat']."'></td>";
						echo"<td><input style='width:100%' type='text' name=ln$row1[rowid] readonly value='".$row1['lot']."'></td>";
						echo"<td><input style='width:100%' type='text' name=cn$row1[rowid] readonly value='".$row1['coil']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rs$row1[rowid] readonly value='".$row1['username']."'></td>";
					}
					echo "</table>";
				}
				$u="Nos";
				require "KoolControls/KoolChart/koolchart.php";
				$chart = new KoolChart("chart");
				$chart->scriptFolder="KoolControls/KoolChart";
				$series = new PieSeries();
				if(isset($_GET['grnnum']) && $_GET['grnnum']!="")
				{
					$con = mysqli_connect('localhost','root','Tamil','storedb');
					$rec=0;
					$rej=0;
					$qi=0;
					$grnnum=$_GET['grnnum'];
					$query2 = "SELECT * FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,sent_thr_dc,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,IF(T.qty IS NULL,0,T.qty) as used FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' GROUP BY inv) AS T ON receipt.grnnum=T.inv  GROUP BY grnnum) AS T WHERE T.grnnum='$grnnum'";
					//echo "SELECT * FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' GROUP BY inv) AS T ON receipt.grnnum=T.inv  GROUP BY grnnum) AS T WHERE T.grnnum='$grnnum'";
					$result2 = $con->query($query2);
					$row1 = mysqli_fetch_array($result2);
					$r1=$row1['inwarded'];
					$rec=$row1['accepted'];
					$rej=$row1['rejected'];
					$sent_thr_dc = $row1['sent_thr_dc'];
					//$storestock=$row1['accepted']-$row1['used'];
					$storestock=$row1['accepted']-$row1['used']-$row1['sent_thr_dc'];
					$qi=$row1['inwarded']-($row1['accepted']+$row1['rejected']);
					$u="Kg";
					$chart->PlotArea->AddSeries($series);
					$series->AddItem(new PieItem(($storestock*100)/$r1,"STORES STOCK",null,false));
					$series->AddItem(new PieItem(($row1['used']*100)/$r1,"MATERIAL USED",null,false));
					$series->AddItem(new PieItem(($rej*100)/$r1,"REJECTED",null,false));
					$series->AddItem(new PieItem(($qi*100)/$r1,"UNDER INSPECTION",null,false));
					
					echo '</div>&nbsp;';
					echo '<div class="find2" style="float:right">'.$chart->Render().'</div>';
					echo "<div class='find' ><h4 style='text-align:center'><label >SUMMARY DETAIL </label></h4>";
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GRN NUMBER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$grnnum.'</label></div>';
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL RM INWARDED &nbsp; : '.$r1.' '.$u.'</label></div>';
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL RECEIVED AFTER INSP  : '.$rec.' '.$u.'</label></div>';
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL REJECTED MATERIAL  : '.$rej.' '.$u.'</label></div>';
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL MATERIAL SENT THROUGH DC : '.$sent_thr_dc.' '.$u.'</label></div>';
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MATERIAL USED SO FAR &nbsp;&nbsp;&nbsp; : '.round($row1['used'],2).' '.$u.'</label></div>';
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MATERIAL AVAILABLE IN STORES : '.$storestock.' '.$u.'</label></div>';
					
					
				}		
			?>
		</form>
	</body>
</html>
		