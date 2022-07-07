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
		<h4 style="text-align:center"><label> VARIABLE PAY PERFORMANCE SCORE CARD </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
		<form method="GET">			
						<script>
						function reload1(form)
						{
							var s1 = document.getElementById("start").value;
							var s2 = document.getElementById("end").value;
							self.location='o24.php?start='+s1+'&end='+s2;
						}
						</script>
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
			<div class="find">
				<label>SELECT WEEK ( START )</label>
					<input type="text" required style="width:50%; background-color:white;" onchange=reload1(this.form) id="start" name="start" list="weeklist" value="<?php if(isset($_GET['start'])){echo $_GET['start'];}?>">
			</div>
			<div class="find1">
				<label>SELECT WEEK ( END )</label>
					<input type="text" required style="width:50%; background-color:white;" onchange=reload1(this.form) id="end" name="end" list="weeklist" value="<?php if(isset($_GET['end'])){echo $_GET['end'];}?>">
			</div>
			<br><br>
		</form>
	</div>
			<?php
			if(isset($_GET['start']) && $_GET['start']!="" && isset($_GET['end']) && $_GET['end']!="")
			{
				$start=$_GET['start'];
				$end=$_GET['end'];
				echo'<table id="testTable" align="center">
					  <tr>
						<th>WEEK NUMBER</th>
						<th>TOTAL PRODUCTION COMMIT (%)</th>
						<th>NO OF CUSTOMER COMPLAINT ( Nos ) </th>
						<th>SCORE (%)</th>
						<th>CNC COMMIT ACHIVED (%)</th>
						<th>MANUAL COMMIT ACHIVED (%)</th>
						<th>NPD COMMIT ACHIVED (%)</th>
						<th>CNC REJ (%)</th>
						<th>OTHER AREA REJ (%)</th>
						<th>TOTAL W.Hrs</th> 
						<th>MACHINE DOWN TIME ( In Hrs )</th>
					  </tr>';
					  $s=substr($start,7,2);
					  $ss=$s;
					  $e=substr($end,7,2);
					  $nfw=$s-$e;
					  if($s<=$e)
					  {
						  do{
							  $week="2018 - ".$s;
							  echo "<tr><td>".$week."</td>";
							  $query = "SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,SUM(qty) AS QTY from commit where week='$week' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%' OR prcno LIKE 'A20%')AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT";
							  $result = $con->query($query);
							  $row = mysqli_fetch_array($result);
							  echo "<td>".round($row['percent'],2)." %</td>";
							  $query = "SELECT COUNT(*) AS c,IF(COUNT(*)=0,'100',IF(COUNT(*)=1,'75',IF(COUNT(*)=2,'50','0'))) AS v FROM capalog WHERE week='$week'";
							  $result = $con->query($query);
							  $row = mysqli_fetch_array($result);
							  echo "<td>".$row['c']."</td>";
							  echo "<td>".$row['v']." %</td>";
							  $query = "SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,qty from commit where week='$week' and foremac='CNC_SHEARING'  and npd='0') AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE prcno LIKE 'A20%' AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week' and foremac='CNC_SHEARING' and npd='0') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT";
							  $result = $con->query($query);
							  $row = mysqli_fetch_array($result);
							  echo "<td>".round($row['percent'],2)." %</td>";
							  $query = "SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,qty from commit where week='$week' and foremac='MANUAL' and npd='0') AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%')AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week' and foremac='MANUAL' and npd='0') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT";
							  $result = $con->query($query);
							  $row = mysqli_fetch_array($result);
							  echo "<td>".round($row['percent'],2)." %</td>";
							  //NPD
							  $query = "SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,qty from commit where week='$week' and npd='1') AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%')AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week'  and npd='1') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT";
							  $result = $con->query($query);
							  $row = mysqli_fetch_array($result);
							  echo "<td>".round($row['percent'],2)." %</td>";
							  $query = "SELECT (rejected*100)/(rejected+received) as rej FROM (SELECT prcno,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM d12 WHERE rowid>(SELECT start FROM weekinfo WHERE week='$week') AND  rowid<(SELECT end FROM weekinfo WHERE week='$week') AND rcno='' and prcno LIKE 'A%') as T";
							  $result = $con->query($query);
							  $row = mysqli_fetch_array($result);
							  echo "<td>".round($row['rej'],2)." %</td>";
							  $query = "SELECT (rejected*100)/(rejected+received) as rej FROM (SELECT prcno,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM d12 WHERE rowid>(SELECT start FROM weekinfo WHERE week='$week') AND  rowid<(SELECT end FROM weekinfo WHERE week='$week') AND rcno='' and (prcno LIKE 'B%' OR prcno LIKE 'C%')) as T";
							  $result = $con->query($query);
							  $row = mysqli_fetch_array($result);
							  echo "<td>".round($row['rej'],2)." %</td>";
							  echo "<td>4032</td>";
							  $query = "SELECT SUM(time_to_sec(timediff(CONCAT(tdate,' ',ttime),CONCAT(fdate,' ',ftime))))/60/60 AS sec FROM downtime WHERE week='$week'";
							  $result = $con->query($query);
							  $row = mysqli_fetch_array($result);
							  echo "<td>".round($row['sec'],2)."</td>";
							  $s++;
						  }while($s<=$e);
					  }
				//echo"<td><h4 style='color : yellow;'>".$d."</h4></td><td colspan='1'>TOTAL COMMIT</td><td><h4 style='color : yellow;'>".$c."</h4></td>
				//<td colspan='2'>TOTAL ACHIEVED</td><td><h4 style='color : yellow;'>".$a."</h4></td><td><h4 style='color : yellow;'>".round(($a*100)/$c)." %</h4></td>";
				?>
		</table>
	</div>
	
	<div>
		<table id="scoreTable" align="center">
		  <tr>
			<th>WEEK NUMBER</th>
			<th>EMPLOYEE NAME</th>
			<th>TOTAL PRODUCTION SCORE</th>
			<th>CUSTOMER COMPLAINT SCORE</th>
			<th>MANUAL COMMIT SCORE</th>
			<th>CNC COMMIT SCORE</th>
			<th>NPD COMMIT SCORE</th>
			<th>CNC REJ SCORE</th>
			<th>OTHER AREA REJ SCORE</th>
			<th>MACHINE DOWN TIME SCORE</th>
			<th>TOTAL WEEKLY SCORE</th>
		  </tr>';
		<?php
			$start=$_GET['start'];
			$end=$_GET['end'];
			$s=substr($start,7,2);
			$ss=$s;
			$e=substr($end,7,2);
			$nfw=$s-$e;
			if($s<=$e)
			{
				do{
					$week="2018 - ".$ss;
					$query = "SELECT name,totalproduction*(SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,SUM(qty) AS QTY from commit where week='$week' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%' OR prcno LIKE 'A20%')AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT) AS TT_PROD,cust_complaint*(SELECT IF(COUNT(*)=0,'100',IF(COUNT(*)=1,'75',IF(COUNT(*)=2,'50','0'))) AS v FROM capalog WHERE week='$week') AS CUST_COMP,manual_commit*(SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,qty from commit where week='$week' and foremac='MANUAL'  and npd='0') AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%')AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week' and foremac='MANUAL'  and npd='0') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT) MANUAL_COMMIT,cnc_commit*(SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,qty from commit where week='$week' and foremac='CNC_SHEARING' and npd='0') AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE prcno LIKE 'A20%' AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week' and foremac='CNC_SHEARING'  and npd='0') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT) AS CNC_COMMIT,npd_commit*(SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,qty from commit where week='$week' and npd='1') AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%')AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week'  and npd='1') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT) AS NPD_COMMIT,cnc_rej*(SELECT (rejected*100)/(rejected+received) as rej FROM (SELECT prcno,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM d12 WHERE rowid>(SELECT start FROM weekinfo WHERE week='$week') AND  rowid<(SELECT end FROM weekinfo WHERE week='$week') AND rcno='' and prcno LIKE 'A%') as T) AS CNC_REJ,other_rej*(SELECT (rejected*100)/(rejected+received) as rej FROM (SELECT prcno,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM d12 WHERE rowid>(SELECT start FROM weekinfo WHERE week='$week') AND  rowid<(SELECT end FROM weekinfo WHERE week='$week') AND rcno='' and (prcno LIKE 'B%' OR prcno LIKE 'C%')) as T) AS OTHER_REJ,machine_down*(SELECT (100-IF((SUM(time_to_sec(timediff(CONCAT(tdate,' ',ttime),CONCAT(fdate,' ',ftime))))/60/60/4032)*100 IS NULL,0,(SUM(time_to_sec(timediff(CONCAT(tdate,' ',ttime),CONCAT(fdate,' ',ftime))))/60/60/4032)*100)) AS sec FROM downtime WHERE week='$week') AS MC_DOWN FROM `variablepay`";
					$result = $con->query($query);
					echo "<tr><td rowspan='11'>".$week."</td>";
					while($row = mysqli_fetch_array($result))
					{
						$s=0;$c=0;
						echo "<td>".$row['name']."</td>";
						if(round($row['TT_PROD'],2)>0){echo "<td>".round($row['TT_PROD'],2)." %</td>";$s=$s+round($row['TT_PROD'],2);$c++;}else{echo "<td></td>";}
						if(round($row['CUST_COMP'],2)>=0){echo "<td>".round($row['CUST_COMP'],2)." %</td>";$s=$s+round($row['CUST_COMP'],2);$c++;}else{echo "<td></td>";}
						if(round($row['MANUAL_COMMIT'],2)>0){echo "<td>".round($row['MANUAL_COMMIT'],2)." %</td>";$s=$s+round($row['MANUAL_COMMIT'],2);$c++;}else{echo "<td></td>";}
						if(round($row['CNC_COMMIT'],2)>0){echo "<td>".round($row['CNC_COMMIT'],2)." %</td>";$s=$s+round($row['CNC_COMMIT'],2);$c++;}else{echo "<td></td>";}
						if(round($row['NPD_COMMIT'],2)>0){echo "<td>".round($row['NPD_COMMIT'],2)." %</td>";$s=$s+round($row['NPD_COMMIT'],2);$c++;}else{echo "<td></td>";}
						if(round($row['CNC_REJ'],2)>0){echo "<td>".round($row['CNC_REJ'],2)." %</td>";$s=$s+round($row['CNC_REJ'],2);$c++;}else{echo "<td></td>";}
						if(round($row['OTHER_REJ'],2)>0){echo "<td>".round($row['OTHER_REJ'],2)." %</td>";$s=$s+round($row['OTHER_REJ'],2);$c++;}else{echo "<td></td>";}
						if(round($row['MC_DOWN'],2)>0){echo "<td>".round($row['MC_DOWN'],2)." %</td>";$s=$s+round($row['MC_DOWN'],2);$c++;}else{echo "<td></td>";}
						echo "<td>".round($s/$c,2)." %</td></tr>";
					}
					$ss++;
				}while($ss<=$e);
			}
	}
		?>	  
		</table>
	</div>
	<br>
	<div>
	<table id="scoreTable" align="center">
		  <tr>
			<th>WEEK</th>
			<?php
			$query = "SELECT name FROM `variablepay`";
			$result = $con->query($query);
			while($row = mysqli_fetch_array($result))
			{
				echo "<th>".$row['name']."</th>";
			}
			?></tr>
			<?php
			if(isset($_GET['start']) && $_GET['start']!="" && isset($_GET['end']) && $_GET['end']!="")
			{
				$start=$_GET['start'];
				$end=$_GET['end'];
				$s=substr($start,7,2);
				$ss=$s;
				$e=substr($end,7,2);
				$nfw=$s-$e;
				$query = "SELECT name FROM `variablepay`";
				$result = $con->query($query);
				while($row1 = mysqli_fetch_array($result))
				{
					${'EMP'.$row1['name']}=0;
				}
				$i=0;
				if($s<=$e)
				{
					do{
						$week="2018 - ".$ss;
						echo "<td>".$week."</td>";
						$query = "SELECT name,totalproduction*(SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,SUM(qty) AS QTY from commit where week='$week' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%' OR prcno LIKE 'A20%')AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT) AS TT_PROD,cust_complaint*(SELECT IF(COUNT(*)=0,'100',IF(COUNT(*)=1,'75',IF(COUNT(*)=2,'50','0'))) AS v FROM capalog WHERE week='$week') AS CUST_COMP,manual_commit*(SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,qty from commit where week='$week' and foremac='MANUAL'  and npd='0') AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%')AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week' and foremac='MANUAL'  and npd='0') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT) MANUAL_COMMIT,cnc_commit*(SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,qty from commit where week='$week' and foremac='CNC_SHEARING'  and npd='0') AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE prcno LIKE 'A20%' AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week' and foremac='CNC_SHEARING'  and npd='0') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT) AS CNC_COMMIT,npd_commit*(SELECT (SUM(actual)*100)/sum(qty) as percent FROM (SELECT T1.pnum,qty,IF(act IS NULL,0,act) as actual FROM (select pnum,qty from commit where week='$week' and npd='1') AS T1 LEFT JOIN (SELECT pnum,SUM(partreceived) as act FROM d12 WHERE (prcno LIKE 'B20%' OR prcno LIKE 'C20%')AND  rowid>(SELECT start FROM weekinfo WHERE week='$week') AND rowid<(SELECT IF(end IS NULL,'1000000',end) as end FROM weekinfo WHERE week='$week') AND pnum IN (select DISTINCT pnum from commit where week='$week'  and npd='1') GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum) AS TT) AS NPD_COMMIT,cnc_rej*(SELECT (rejected*100)/(rejected+received) as rej FROM (SELECT prcno,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM d12 WHERE rowid>(SELECT start FROM weekinfo WHERE week='$week') AND  rowid<(SELECT end FROM weekinfo WHERE week='$week') AND rcno='' and prcno LIKE 'A%') as T) AS CNC_REJ,other_rej*(SELECT (rejected*100)/(rejected+received) as rej FROM (SELECT prcno,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM d12 WHERE rowid>(SELECT start FROM weekinfo WHERE week='$week') AND  rowid<(SELECT end FROM weekinfo WHERE week='$week') AND rcno='' and (prcno LIKE 'B%' OR prcno LIKE 'C%')) as T) AS OTHER_REJ,machine_down*(SELECT (100-IF((SUM(time_to_sec(timediff(CONCAT(tdate,' ',ttime),CONCAT(fdate,' ',ftime))))/60/60/4032)*100 IS NULL,0,(SUM(time_to_sec(timediff(CONCAT(tdate,' ',ttime),CONCAT(fdate,' ',ftime))))/60/60/4032)*100)) AS sec FROM downtime WHERE week='$week') AS MC_DOWN FROM `variablepay`";
						$result = $con->query($query);
						while($row = mysqli_fetch_array($result))
						{
							$s=0;$c=0;
							if(round($row['TT_PROD'],2)>0){$s=$s+round($row['TT_PROD'],2);$c++;}
							if(round($row['CUST_COMP'],2)>=0){$s=$s+round($row['CUST_COMP'],2);$c++;}
							if(round($row['MANUAL_COMMIT'],2)>0){$s=$s+round($row['MANUAL_COMMIT'],2);$c++;}
							if(round($row['CNC_COMMIT'],2)>0){$s=$s+round($row['CNC_COMMIT'],2);$c++;}
							if(round($row['NPD_COMMIT'],2)>0){$s=$s+round($row['NPD_COMMIT'],2);$c++;}
							if(round($row['CNC_REJ'],2)>0){$s=$s+round($row['CNC_REJ'],2);$c++;}
							if(round($row['OTHER_REJ'],2)>0){$s=$s+round($row['OTHER_REJ'],2);$c++;}
							if(round($row['MC_DOWN'],2)>0){$s=$s+round($row['MC_DOWN'],2);$c++;}
							echo "<td>".round($s/$c,2)." %</td>";
							${'EMP'.$row['name']}=${'EMP'.$row['name']}+round($s/$c,2);
						}
						$ss++;
						echo "</tr>";
						$i++;
					}while($ss<=$e);
				}
			?>
	<tr>
		<td>AVERAGE SCORE OF ABOVE PERIOD</td>
		<?php
			$query = "SELECT name FROM `variablepay`";
			$result = $con->query($query);
			while($row1 = mysqli_fetch_array($result))
			{
				echo "<td>".round((${'EMP'.$row1['name']}/$i),2)." %</td>";
			}
		}
		?>
	</tr>
	</table>
	</div>
	
</body>
</html>