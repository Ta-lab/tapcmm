<!DOCTYPE html>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<script src = "js\excelreport.js"></script>
	<link rel="stylesheet" type="text/css" href="design1.css">
</head>
<body>
<header class="fixeElement">
	<div style="float:right">
			<a href="index.html"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
	<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label>OPEN ROUTE CARD STATUS REPORT [ O12 ]</label></h4>
	<div style="float:left">
		<a href="outputlink.php"><label>Back to report</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'OPEN RCNO')" value="Export to Excel">
	</div><br/></br>
	<div class="divclass">
		<form method="GET"></br>
			<div class="find3">
			<label>OPERATION</label>
			<select name ="prcno" id="prcno" onchange="reload1(this.form)">
			<option value="%_%">ALL</option>";	
				<?php			
					$rat=$_GET['rat'];
					$con = mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						echo "connection failed";
					$query = "SELECT * from m14";
					$result = $con->query($query);  
					if(isset($_GET['rat'])){
						while ($row = mysqli_fetch_array($result)) 
							{
								if($_GET['rat']==$row['stkpt'])
									echo "<option selected value='" . $row['stkpt'] . "'>" . $row['oper'] ."</option>";
								else
									echo "<option value='" . $row['stkpt'] . "'>" . $row['oper'] ."</option>";              
							}
					}
					else{
							echo "<option value='" . $row['stkpt'] . "'>" . $row['oper'] ."</option>";
							$row = mysqli_fetch_array($result);
							echo "<option value='" . $row['stkpt'] . "'>" . $row['oper'] ."</option>";
							$row = mysqli_fetch_array($result);
							echo "<option value='" . $row['stkpt'] . "'>" . $row['oper'] ."</option>";
							$row = mysqli_fetch_array($result);
							echo "<option value='" . $row['stkpt'] . "'>" . $row['oper'] ."</option>";
							$row = mysqli_fetch_array($result);
							echo "<option value='" . $row['stkpt'] . "'>" . $row['oper'] ."</option>";
							$row = mysqli_fetch_array($result);
							echo "<option value='" . $row['stkpt'] . "'>" . $row['oper'] ."</option>";
					}
					echo "</select></h1>";
				?>
				<script>
				function reload1(form)
				{
					var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
					self.location='o12tab.php?&rat='+s2;
				}
			</script></div></form><br>
			</div><header>
	<?php
		$c=0;$r=1;
		$temp="";
		$conn = new mysqli("localhost", "root", "Tamil", "mypcm");
		echo'<table id="testTable" align="center"><tr><th> RCNO / DCNO / INV NO </th><th> STOCKING POINT</th><th>ISSUE DATE</th><th>RAW MATERIAL</th>
			<th>ISSUED QUANTITY</th><th>UOM</th><th>PART NUMBER</th><th>QUANTITY OK</th><th>QTY REJECTED</th><th> CUMM TOTAL USAGE</th><th>AVAIL_QUANTITY</th>
			<th>UOM</th><th>NO.DAYS</th><th>FOREMAN</th></tr>';
		if(isset($_GET["rat"]))
		{
			$rat= $_GET['rat'];
			//for one A'rc->mul part ->SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T1.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno,d12.pnum) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty,pnum FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT distinct(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT pnum) as cnt FROM `d12` JOIN  d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like 'Stores'  HAVING cnt>1 order by date,rcno
			$query = "SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T2.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 RIGHT JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation='$rat' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT pnum) as cnt FROM `d12` JOIN  d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno LEFT JOIN (SELECT DISTINCT(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) order by t2.date,t2.rcno";
			$result = $conn->query($query);
		}
		else
		{
			$query = "SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T2.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 RIGHT JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT pnum) as cnt FROM `d12` JOIN  d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno LEFT JOIN (SELECT DISTINCT(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) order by t2.date,t2.rcno";
			$result = $conn->query($query);
		}
		$row = $result->fetch_assoc();
		do{
			$d = date("d-m-Y", strtotime($row['date']));
			if($row['rcno']!=$temp)
			{
				$c=0;$r=0;
			}
			$used=round($row['used'],2);
			$iss=round($row['issqty'],2);
			$c=$c+$row['used'];
			$r=$r+1;
			echo"<tr>
			<td>".$row['rcno']."</td><td>".$row['operation']."</td><td>".$d."</td><td>".$row['rm']."</td><td>".round($row['issqty'],2)."</td><td>".$row['uom']."</td>
			<td>".$row['pnum']."</td><td>".$row['received']."</td><td>".$row['rejected']."</td><td>".$used."</td>";
			if($row['cnt']==$r)
			{
				echo"<td>".round(($row['issqty']-$c),2)."</td><td>".$row['uom']."</td><td>".$row['days']."</td><td>".$row['foreman']."</td></tr>";
			}
			else
			{
				echo"<td>".round(($row['issqty']-$c),2)."</td><td>".$row['uom']."</td><td>".$row['days']."</td><td>".$row['foreman']."</td></tr>";
			}
			$temp=$row['rcno'];
		}while($row = $result->fetch_assoc());
	?>
</div>
</body>
</html>