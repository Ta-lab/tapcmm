<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL" || $_SESSION['user']=="109" || $_SESSION['access']=="To S/C" || $_SESSION['access']=="NONE")
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
		<h4 style="text-align:center"><label> OPEN DC AND SUB-CONTRACT STOCK REPORT  [ O19 ]</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
		<form method="GET">	
			</br>
			<datalist id="sclist" >
						<?php
								$scn="%%";$pn="%%";
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT DISTINCT sccode FROM `dcmaster`";
										$result = $con->query($query);
										echo"<option value=''>Select one</option>";
										while ($row = mysqli_fetch_array($result)) 
										{
											if($_GET['scc']==$row['sccode'])
												echo "<option selected value='".$row['sccode']."'>".$row['sccode']."</option>";
											else
												echo "<option value='".$row['sccode']."'>".$row['sccode']."</option>";
										}
						?>
						</datalist>
			<div class="find">
				<label>S/C CODE</label>
							<input type="text" id="scc" name="scc" list="sclist"  onchange="reload1(this.form)" value="<?php
							if(isset($_GET['scc']) && $_GET['scc']!="")
							{
								$scn=$_GET['scc'];;
								echo $_GET['scc'];
							}
							else
							{
								echo "";
							}
							?>"/>
							<script>
						function reload(form)
						{
							
							var s0 = document.getElementById("scc").value;
							var s1 = document.getElementById("pn").value;
							self.location='open_subcontract.php?scc='+s0+'&pn='+s1;
						}
					</script>
			</div>
			
			
			<datalist id="partlist" >
						<?php
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT distinct pn FROM dcmaster";
										$result = $con->query($query);
										echo"<option value=''>Select one</option>";
										while ($row = mysqli_fetch_array($result)) 
										{
											if($_GET['pn']==$row['pn'])
												echo "<option selected value='".$row['pn']."'>".$row['pn']."</option>";
											else
												echo "<option value='".$row['pn']."'>".$row['pn']."</option>";
										}
						?>
						</datalist>
			<div class="find1">
				<label>PART NUMBER</label>
							<input type="text" id="pn" name="pn" list="partlist"  onchange="reload1(this.form)" value="<?php
							if(isset($_GET['pn']) && $_GET['pn']!="")
							{
								$pn=$_GET['pn'];
								echo $_GET['pn'];
							}
							else
							{
								echo "";
							}
							?>"/>
							<script>
						function reload1(form)
						{
							var s0 = document.getElementById("scc").value;
							var s1 = document.getElementById("pn").value;
							self.location='open_subcontract.php?scc='+s0+'&pn='+s1;
						}
					</script>
			</div>
			
			<!--
			<div class="find2">
				<label>FROM DATE</label>
							<input type="date" id="f" name="f"  onchange="reload1(this.form)" value="<?php
							if(isset($_GET['f']))
							{
								echo $_GET['f'];
							}
							else
							{
								echo date('Y-m-d',strtotime('-1 days'));
							}
							?>"/>
			
				<label>TO DATE</label>
							<input type="date" id="tt" name="tt"  onchange="reload1(this.form)" value="<?php
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
			-->
			
			</form><br><br>
			<?php
			
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-1 days'));
					$tt = date('Y-m-d');
				}
				else
				{
					$f = $_GET['f'];
					$tt = $_GET['tt'];
				}
								
				$t="%%";
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>DATE OF ISSUANCE</th>
						<th>DC-NUMBER</th>
						<th>S/C NAME</th>
						<th>PART NUMBER</th>
						<th>ISSUED QTY</th>
						<th>RECEIVED QTY</th>
						<th>NOT RECEIVED</th>
						<th>AGE</th>
						<th>Foreman</th>
					  </tr>';
				
				if(isset($_GET['pn']) && $_GET['pn']!="" && isset($_GET['scc']) && $_GET['scc']!="")
				{
					$pn=$_GET['pn'];
					$scn=$_GET['scc'];
					if(isset($_GET['f']) && $_GET['f']!="" && isset($_GET['tt']) && $_GET['tt']!="")
					{
						//echo "if1";
						$f=$_GET['f'];
						$tt=$_GET['tt'];
						$query2 = "SELECT date,scn,pnum,rcno,issqty,received,rejected,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,T1.received,T1.rejected,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d11.pnum) as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE (m14.oper LIKE 'Subcontract' OR m14.oper LIKE 'ALFA N-IND PRIM') AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) WHERE scn LIKE '$scn' AND pnum LIKE '$pn' ORDER BY days DESC";
					}
					else
					{
						//echo "else1";
						$query2 = "SELECT date,scn,pnum,rcno,issqty,received,rejected,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,T1.received,T1.rejected,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d11.pnum) as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE (m14.oper LIKE 'Subcontract' OR m14.oper LIKE 'ALFA N-IND PRIM') AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) WHERE scn LIKE '$scn' AND pnum LIKE '$pn' ORDER BY days DESC";
					}
				
				}
				else{
					if(isset($_GET['f']) && $_GET['f']!="" && isset($_GET['tt']) && $_GET['tt']!="")
					{
						//echo "if2";
						$f=$_GET['f'];
						$tt=$_GET['tt'];
						$query2 = "SELECT date,scn,pnum,rcno,issqty,received,rejected,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,T1.received,T1.rejected,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d11.pnum) as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE (m14.oper LIKE 'Subcontract' OR m14.oper LIKE 'ALFA N-IND PRIM') AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) WHERE scn LIKE '$scn' AND pnum LIKE '$pn' ORDER BY days DESC";
					}
					//$query2 = "SELECT date,scn,pnum,rcno,issqty,received,rejected,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,T1.received,T1.rejected,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d11.pnum) as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE (m14.oper LIKE 'Subcontract' OR m14.oper LIKE 'ALFA N-IND PRIM') AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) ORDER BY days DESC";
					//$query2 = "SELECT date,scn,pnum,rcno,issqty,received,rejected,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,T1.received,T1.rejected,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d11.pnum) as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE (m14.oper LIKE 'Subcontract' OR m14.oper LIKE 'ALFA N-IND PRIM') AND d11.rcno!='' AND d11.closedate>='$f' AND d11.closedate<='$tt' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) WHERE scn LIKE '$scn' AND pnum LIKE '$pn'  ORDER BY days DESC";
					//echo "SELECT date,scn,pnum,rcno,issqty,received,rejected,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,T1.received,T1.rejected,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d11.pnum) as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE (m14.oper LIKE 'Subcontract' OR m14.oper LIKE 'ALFA N-IND PRIM') AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) WHERE scn LIKE '$scn' AND pnum LIKE '$pn'  ORDER BY days DESC";
					else{
						//echo "else2";
						$query2 = "SELECT date,scn,pnum,rcno,issqty,received,rejected,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,T1.received,T1.rejected,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d11.pnum) as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE (m14.oper LIKE 'Subcontract' OR m14.oper LIKE 'ALFA N-IND PRIM') AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) WHERE scn LIKE '$scn' AND pnum LIKE '$pn' ORDER BY days DESC";	
					}
				}
				$result2 = $conn->query($query2);
				$totqty=0;
				while($row1 = mysqli_fetch_array($result2))
				{	
					
					echo"<tr><td>".$row1['date']."</td>";
					echo"<td>".$row1['rcno']."</td>";
					echo"<td>".$row1['scn']."</td>";
					echo"<td>".$row1['pnum']."</td>";
					echo"<td>".round($row1['issqty'],2)."</td>";
					echo"<td>".round($row1['used'],2)."</td>";
					echo"<td>".round($row1['notused'],2)."</td>";
					echo"<td>".$row1['days']."</td>";
					echo"<td>".$row1['foreman']."</td>";
					echo"</tr>";
					$totqty=$totqty+$row1['notused'];
				}
				
				echo" <tr>
					<td colspan='6'><h4>TOTAL QTY</h4></td>
					<td><h4>".$totqty."</h4></td>";
				echo"</tr>";
				
				
			?>
		</div>
		
</body>
</html>
