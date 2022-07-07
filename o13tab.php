<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="CLOSED ROUTE CARD REPORT";
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
	<h4 style="text-align:center"><label>ROUTE CARD CLOSURE & QTY VARIANCE REPORT  [ O13 ]</label></h4>
	<div>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'CLOSED RCNO')" value="Export to Excel">
		
		</div>
		<br/></br>
	<div class="divclass">
		<form method="POST" action='i141.php'>	
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
								echo date('Y-m-d',strtotime('-7 days'));
							}
							?>"/>
							<script>
						function reload(form)
						{
							var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
							var s3=form.pnum.options[form.pnum.options.selectedIndex].value;
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='o13tab.php?f='+s0+'&tt='+s1+'&rat='+s2+'&pnum='+s3;
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
							var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
							var s3=form.pnum.options[form.pnum.options.selectedIndex].value;
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='o13tab.php?f='+s0+'&tt='+s1+'&rat='+s2+'&pnum='+s3;
						}
					</script>
			</div>
			<div class="find2">
				<label>STOCKING POINT</label>
					<select name ="prcno" id="prcno" onchange="reload1(this.form)">
					<option value="%%">ALL</option>";	
						<?php			
							$rat=$_GET['rat'];
							$date=0000-00-00;
					        $con = mysqli_connect('localhost','root','Tamil','mypcm');
				            if(!$con)
								echo "connection failed";
						    $query = "SELECT * from m14 where oper!=''";
							$result = $con->query($query);  
							while ($row = mysqli_fetch_array($result)) 
							{
								if(isset($_GET['rat']) && $_GET['rat']==$row['oper'])
									echo "<option selected value='" . $row['oper'] . "'>" . $row['oper'] ."</option>";
								else
									echo "<option value='" . $row['oper'] . "'>" . $row['oper'] ."</option>";              
							}
							echo "</select></h1>";
						?>
						<script>
						function reload1(form)
						{
							var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
							var s3=form.pnum.options[form.pnum.options.selectedIndex].value;
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='o13tab.php?f='+s0+'&tt='+s1+'&rat='+s2+'&pnum='+s3;
						}
					</script>
			</div>
			<div class="find3">
				<label>PART NUMBER</label>
					<select name ="pnum" id="pnum" onchange="reload2(this.form)">
					<option value="%%">ALL</option>";	
						<?php			
							$rat=$_GET['rat'];
							$date=0000-00-00;
					        $con = mysqli_connect('localhost','root','Tamil','mypcm');
				            if(!$con)
								echo "connection failed";
						    $query = "SELECT DISTINCT pnum from m13 where PNUM!='' ORDER BY PNUM";
							$result = $con->query($query);
							while ($row = mysqli_fetch_array($result)) 
							{
								if(isset($_GET['pnum']) && $_GET['pnum']==$row['pnum'])
									echo "<option selected value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
								else
									echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";              
							}
							echo "</select></h1>";
						?>
						<script>
						function reload2(form)
						{
							var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
							var s3=form.pnum.options[form.pnum.options.selectedIndex].value;
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							self.location='o13tab.php?f='+s0+'&tt='+s1+'&rat='+s2+'&pnum='+s3;
						}
					</script>
			</div>
			</form>
			<br><br>
			<?php
				$c=0;$r=1;
				$temp="";
				$conn = new mysqli("localhost", "root", "Tamil", "mypcm");
				echo'<table id="testTable" align="center"><tr><th> RCNO / DCNO / INV NO </th><th>ISSUE DATE</th><th>CLOSED_DATE</th><th>RAW MATERIAL</th>
			<th>ISSUED QUANTITY</th><th>UOM</th><th>PART NUMBER</th><th>QUANTITY OK</th><th>QTY REJECTED</th><th> TOTAL USAGE</th><th>SHORTAGE</th>
			<th>UOM</th><th>NO.DAYS</th><th>REMARKS/REASON</th></tr>';
				if(isset($_GET["f"]) && isset($_GET["tt"]) && isset($_GET["rat"]))
				{
					$t='';
					$t1='';
					$f = $_GET['f'];
					$tt = $_GET['tt'];
					$rat= $_GET['rat'];
					$pnum= $_GET['pnum'];
					if($pnum=="")
					{
						$pnum="%%";
					}
					//$query = "SELECT T2.rcno,T2.rmk,T4.cnt,T2.date,T2.closedate,T2.rm,T2.issqty,T1.prcno,T1.pnum,T1.received,T1.rejected,T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(T2.closedate,T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno,d12.pnum) AS T1 RIGHT JOIN (SELECT d11.rcno,d11.operation,d11.date,d11.closedate,d11.rmk,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate!='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like '$rat' and T2.closedate>='$f' and T2.closedate<='$tt' order by date,rcno";
					$query = "SELECT T2.rcno,T2.operation,T2.date,T2.closedate,T2.rmk,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,d11.closedate,d11.rmk,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate!='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) where T2.operation like '$rat' AND T2.pnum like '$pnum' and T2.closedate>='$f' and T2.closedate<='$tt' GROUP BY T2.rcno order by t2.date,t2.rcno";
					
					$result = $conn->query($query);
				}
				else
				{
					$t='';
					$t1='';
					$f = date('Y-m-d',strtotime('-7 days'));
					$tt = date('Y-m-d');
					//$query = "SELECT T2.rcno,T2.rmk,T4.cnt,T2.date,T2.closedate,T2.rm,T2.issqty,T1.prcno,T1.pnum,T1.received,T1.rejected,T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(T2.closedate,T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno,d12.pnum) AS T1 RIGHT JOIN (SELECT d11.rcno,d11.operation,d11.date,d11.closedate,d11.rmk,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate!='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.closedate>='$f' and T2.closedate<='$tt' order by date,rcno";
					$query = "SELECT T2.rcno,T2.operation,T2.date,T2.closedate,T2.rmk,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,d11.closedate,d11.rmk,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate!='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) where T2.closedate>='$f' and T2.closedate<='$tt' GROUP BY T2.rcno order by t2.date,t2.rcno";
					$result = $conn->query($query);
				}
				while($row = $result->fetch_assoc()){
					$sd = date("d-m-Y", strtotime($row['date']));
					$cd = date("d-m-Y", strtotime($row['closedate']));
					$used=round($row['used'],2);
					$iss=round($row['issqty'],2);
					$c=$c+$row['used'];
					$r=$r+1;
					echo"<tr>
					<td>".$row['rcno']."</td><td>".$sd."</td><td>".$cd."</td><td>".$row['rm']."</td><td>".$row['issqty']."</td><td>".$row['unit']."</td>
					<td>".$row['pnum']."</td><td>".$row['received']."</td><td>".$row['rejected']."</td><td>".$used."</td>";
					echo"<td>".round(($row['notused']),2)."</td><td>".$row['unit']."</td><td>".$row['days']."</td><td>".$row['rmk']."</td></tr>";
				}
			?>
		</table>
	</div>
</body>
</html>