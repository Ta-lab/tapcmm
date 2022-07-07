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
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="ROUTE CARD TRANSACTION DETAILS";
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
	<h4 style="text-align:center"><label>ROUTE CARD BASED ENTRY VERIFICATION [ I26 ]</label></h4>
	<div>
			
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
		
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'RC')" value="Export to Excel">
		</div>
		
		<script>
			function reload(form)
			{
				var p4 = document.getElementById("p").value;
				self.location=<?php echo"'i26.php?rcno='"?>+p4;
			}
		</script>
	<div class="divclass">
		<form id="form" name="form" method="post" action="i25db.php">
			</br>
					<?php
					echo '<datalist id="rclist" >';
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
						$result1 = $con->query("select distinct rcno from d11 where operation!='FG For Invoicing'");
						echo"<option value=''>Select one</option>";
						while ($row1 = mysqli_fetch_array($result1)) 
						{
							if(isset($_GET['rcno'])==$row1['rcno'])
								echo "<option selected value='".$row1['rcno']."'>".$row1['rcno']."</option>";
							else
								echo "<option value='".$row1['rcno']."'>".$row1['rcno']."</option>";
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
				<label>ROUTE CARD NUMBER / DC NUMBER </label>
				<input type="text" style="width:50%;border:2px,red;" class='s' onchange=reload(this.form) id="p" name="rc" list="rclist" value="<?php if(isset($_GET['rcno'])){echo $_GET['rcno'];}?>">
			</div>
			<script>
				function edValueKeyPress2()
				{
					var rsn = document.getElementById("rsn");
					var rc = document.getElementById("p").value;
					if(document.form.rsn.value == "" || document.form.rsn.value.length<10)
					{
						document.getElementById("stat").innerHTML = "STATUS : NOT CLOSED";
						//alert("empty");
					}
					else
					{
						document.getElementById("stat").innerHTML = "STATUS : <a style='color:yellow;' href='delrcno.php?rc="+rc+"&status=1&rsn="+rsn.value+"'>NOT CLOSED (Click Here To Close)</a>";
					}
				}
			</script>	
			<?php
			if(isset($_GET['rcno']) && $_GET['rcno']!="")
			{
				echo '<br><div class="find1">';
				$rcno=$_GET['rcno'];
				$result1 = $con->query("select * from d11 where rcno='$rcno'");
				$row = mysqli_fetch_array($result1);
				if($row['closedate']=="0000-00-00")
				{
					echo "<label id='stat'>STATUS : NOT CLOSED</label>";
					if($_SESSION['user']=="100" || $_SESSION['user']=="123")
					{
						echo "<input type='text' id='rsn' onKeyUp='edValueKeyPress2()' name='rsn' placeholder='To Close RC type Reason Here'>";
					}
					echo "</div>";
					$s="to be received";
				}
				else
				{
					echo "<label>STATUS : CLOSED @ $row[closedate]</label></div>";
					$s="Shortage";
				}
				echo '<div class="find2"><label>PART NUMBER  : '.$row['pnum'].'</label></div>';
			}
			?>
			
			<br>
			<?php
				$con = mysqli_connect('localhost','root','Tamil','mypcm');
				if(!$con)
				echo "connection failed";
				$p="";$r1=0;$r2=0;$r3=0;
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
						<th> ENTERED BY </th>
					</tr>';
					$query2 = "SELECT * FROM d12 where rcno='$rcno' OR prcno='$rcno'";
					$result2 = $con->query($query2);
					while($row1 = mysqli_fetch_array($result2))
					{	
						if(substr($rcno,0,1)=="A" && $row1['rcno']==$rcno)
						{
							$p=$row1['pnum'];
							$r1=$r1+$row1['rmissqty'];
						}
						else if($row1['rcno']==$rcno)
						{
							$r1=$r1+$row1['partissued'];
						}
						
						$r2=$r2+$row1['partreceived'];
						$r3=$r3+$row1['qtyrejected'];
						echo"<tr><td><input style='width:80px' type='text' name=dt$row1[rowid] readonly value='".$row1['date']."'></td>";
						echo"<td><input style='width:100px' type='text' name=sp$row1[rowid] readonly value='".$row1['stkpt']."'></td>";
						echo"<td><input style='width:100%' type='text' name=op$row1[rowid] readonly value='".$row1['operation']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rm$row1[rowid] readonly value='".$row1['rm']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rq$row1[rowid] readonly value='".$row1['rmissqty']."'></td>";
						echo"<td><input style='width:90px' type='text' name=pn$row1[rowid] readonly value='".$row1['pnum']."'></td>";
						echo"<td><input style='width:90px' type='text' name=rc$row1[rowid] readonly value='".$row1['rcno']."'></td>";
						echo"<td><input style='width:90px' type='text' name=pc$row1[rowid] readonly value='".$row1['prcno']."'></td>";
						echo"<td><input style='width:100%' type='text' name=pi$row1[rowid] readonly value='".$row1['partissued']."'></td>";
						echo"<td><input style='width:100%' type='text' name=qr$row1[rowid] readonly value='".$row1['qtyrejected']."'></td>";
						echo"<td><input style='width:100%' type='text' name=pr$row1[rowid] readonly value='".$row1['partreceived']."'></td>";
						echo"<td><input style='width:100%' type='text' name=gn$row1[rowid] readonly value='".$row1['inv']."'></td>";
						echo"<td><input style='width:100%' type='text' name=hn$row1[rowid] readonly value='".$row1['heat']."'></td>";
						echo"<td><input style='width:100%' type='text' name=ln$row1[rowid] readonly value='".$row1['lot']."'></td>";
						echo"<td><input style='width:100%' type='text' name=cn$row1[rowid] readonly value='".$row1['coil']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rs$row1[rowid] readonly value='".$row1['rsn']."'></td>";
						echo"<td><input style='width:100%' type='text' name=rs$row1[rowid] readonly value='".$row1['username']."'></td>";
					}
					echo "</table>";
				}
				$u="Nos";
				
				//ADD FOR GET RM
				$query3 = "SELECT rm FROM d12 where rcno='$rcno'";
				$result3 = $con->query($query3);
				$row3 = mysqli_fetch_array($result3);
				$rmdes = $row3['rm'];
				//echo $rmdes;
				
				require "KoolControls/KoolChart/koolchart.php";
				$chart = new KoolChart("chart");
				$chart->scriptFolder="KoolControls/KoolChart";
				$series = new PieSeries();
				if(isset($_GET['rcno']) && $_GET['rcno']!="")
				{
					$rec=0;
					$rej=0;
					if(substr($rcno,0,1)=="A")
					{
						//$query2 = "SELECT useage as bom FROM m13 where pnum='$p' LIMIT 1";
						$query2 = "SELECT useage as bom FROM m13 where pnum='$p' AND rmdesc='$rmdes' LIMIT 1";
						$result2 = $con->query($query2);
						$row1 = mysqli_fetch_array($result2);
						$p=$row1['bom']*($r2+$r3);
						$rec=$row1['bom']*$r2;
						$rej=$row1['bom']*$r3;
						//$series->AddItem(new PieItem(($r1*100)/$r1-$p,"USED",null,false));
						$u="Kg";
					}
					else
					{
						$rec=$r2;
						$rej=$r3;
						$p=$r2+$r3;
						//$series->AddItem(new PieItem(($r1*100)/$r1-$p,"USED",null,false));
					}
					$chart->PlotArea->AddSeries($series);
					if($p>$r1)
					{
						if($r1!=0)
						{
							$series->AddItem(new PieItem(($rec*100)/$r1,"RECEIVED",null,false));
							$series->AddItem(new PieItem(($rej*100)/$r1,"REJECTED",null,false));
						}
					}
					else
					{
						if($r1!=0)
						{
							$series->AddItem(new PieItem(($rec*100)/$r1,"RECEIVED",null,false));
							$series->AddItem(new PieItem(($rej*100)/$r1,"REJECTED",null,false));
							$series->AddItem(new PieItem((($r1-$p)*100)/$r1,strtoupper($s),null,false));
						}

					}
					echo '</div>&nbsp;';
					$q = "SELECT * FROM correction where rcno='$rcno'";
					$r = $con->query($q);
					$i=0;
					echo '<div class="find"><h4><label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CORRECTIONS </label></h4>';
					while($rs = mysqli_fetch_array($r))
					{
						$i++;
						$t=$rs['date'];
						$t1=$rs['detail'];
						$t2=$rs['cby'];
						echo '&nbsp;&nbsp;&nbsp;&nbsp;<label>'.$i.'. '.$t1.' @ '.$t.' BY '.$t2.'</label><br>';
					}
					
					echo "</div>";
					echo '<div class="find2" style="float:right">'.$chart->Render().'</div>';
					echo "<div class='find1' ><h4 style='text-align:center'><label >SUMMARY DETAIL </label></h4>";
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RC / DC NUMBER  : '.$rcno.'</label></div>';
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL ISSUED &nbsp; : '.$r1.' '.$u.'</label></div>';
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL RECEIVED  : '.$r2.' Nos</label></div>';
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL REJECTED  : '.$r3.' Nos</label></div>';
					if(substr($rcno,0,1)=="A")
					{
						echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BOM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : '.($row1['bom']*1000).' Grams</label></div>';
					}
					if($p==$r1)
					{
						echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RESULT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : OK</label></div>';
					}
					else if($p>$r1)
					{
						echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RESULT  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : EXTRA '.ROUND(($p-$r1),2).' '.$u.'  Received</label></div>';
					}
					else
					{
						echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RESULT  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : '.($r1-$p).' '.$u.' '.$s.' ( '.round((($r1-$p)*100)/$r1,2).' % )</label></div>';
					}
					echo '<div><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REASON (If Any): '.$row['rmk'].'</label></div></div>';
				}
			?>
		</form>
		
	</body>
</html>
		