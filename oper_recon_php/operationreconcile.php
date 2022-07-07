<?php
session_start();
if(isset($_SESSION['user']))
{
	if(($_SESSION['access']=="ALL" && $_SESSION['user']=="123") || ($_SESSION['user']=="100"))
	//if(($_SESSION['access']=="ALL" && $_SESSION['user']=="123"))
	{
		//header("location: inputlink.php?msg=8");
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
	<link rel="stylesheet" type="text/css" href="des.css">

</head>
<body>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
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
	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h5 style="text-align:center"><label>OPERATION RECONCILIATION</label></h5>
	<div>
			
		<div style="float:left">
			<a href="stock_reconcile_menu.php"><label>Back</label></a>
		</div>
		<br/>
		<script>
			function reload(form)
			{
				var p2 = document.getElementById("p4").value;
				self.location=<?php echo"'operationreconcile.php?stkpt='"?>+p2;
			}
			function reload1(form)
			{
				var p2 = document.getElementById("p4").value;
				var p3 = document.getElementById("p5").value;
				self.location=<?php echo"'operationreconcile.php?stkpt='"?>+p2+'&pnum='+p3;
			}
		</script>
	<div class="divclass">
		<form method="POST" action='oprecdb.php'>	
			</br>
			<datalist id="splist" >
					<?php
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
						if(!$con)
							echo "connection failed";
						$t=$_GET['cname'];
						$t1=$_GET['partnumber'];
						$result1 = $con->query("select * from m14");
						while ($row = mysqli_fetch_array($result1)) 
						{
							echo "<option value='".$row['oper']."'>'".$row['oper']."'</option>";
						}
					?>
				</datalist>
			<div class="find">
				<label>SELECT OPERATION</label>
				<input type="text" style="width:50%; background-color:white;" onchange=reload(this.form) id="p4" name="stkpt" list="splist" value="<?php if(isset($_GET['stkpt'])){echo $_GET['stkpt'];}?>"/>
			</div>
			<div class="find1">
				<datalist id="partlist" >
						<?php
							if(isset($_GET['stkpt']))
							{
								$stkpt=$_GET['stkpt'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$result1 = $con->query("select * from m14 where oper='$stkpt'");
								$row = mysqli_fetch_array($result1);
								$stkpt1=$row['stkpt'];
								//$query = "SELECT distinct pnum from m12 where operation='$stkpt1'";
								if($stkpt == "CNC_SHEARING"){
									$query = "SELECT distinct pnum from m13";
								}else{
									$query = "SELECT distinct pnum from m12 where operation='$stkpt1'";
								}
										$result = $con->query($query);
										echo"<option value=''>Select one</option>";
										while ($row = mysqli_fetch_array($result)) 
										{
											echo "<option value='".$row['pnum']."'>".$row['pnum']."</option>";
										}
							}
						?>
						</datalist>
					<label>PART NUMBER</label>
					<input type="text" style="width: 60%; background-color:white;" 	onchange=reload1(this.form) id="p5" name="partnumber" list="partlist" value="<?php if(isset($_GET['pnum'])){echo $_GET['pnum'];}?>"/>
			</div>
		</div>
			<?php
			if(isset($_GET['stkpt']))
			{
				if(isset($_GET['pnum']) && $_GET['pnum']!="")
				{
					$pnum=$_GET['pnum'];
				}
				else
				{
					$pnum="%%";
				}
				$stkpt=$_GET['stkpt'];
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>S.No</th>
						<th>OPERATION</th>
						<th>DATE</th>
						
						<th>PART NUMBER</th>
						<th>RC NUMBER</th>
						<th>ERP STOCK</th>
						<th>ACTUAL STOCK</th>
					  </tr>';
				//good working query
				//$query = "SELECT date,rcno,rm,if(used is null,issqty,issqty-used) as stock,days FROM (SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T1.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT distinct(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN  d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like '$stkpt1' order by date,rcno) AS TABLE1 where rm like '$pnum' order by rm";
				
				if($stkpt == "CNC_SHEARING")
				{
					$query = "SELECT DISTINCT date,rcno,rm,if(used is null,issqty,issqty-used) as stock,days FROM (SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T1.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.pnum, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT distinct(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.pnum) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN  d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like '$stkpt1' order by date,rcno) AS TABLE1 where rm like '$pnum' order by rm";
				}
				else{
					$query = "SELECT DISTINCT date,rcno,rm,if(used is null,issqty,issqty-used) as stock,days FROM (SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T1.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT distinct(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN  d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like '$stkpt1' order by date,rcno) AS TABLE1 where rm like '$pnum' order by rm";
				}
				
				
				//echo "SELECT date,rcno,rm,if(used is null,issqty,issqty-used) as stock,days FROM (SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T1.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT distinct(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN  d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like '$stkpt1' order by date,rcno) AS TABLE1 where rm like '$pnum' order by rm";
				//echo "SELECT date,rcno,rm,if(used is null,issqty,issqty-used) as stock,days FROM (SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T1.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT distinct(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN  d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like '$stkpt1' order by date,rcno) AS TABLE1 where rm like '$pnum' order by rm";
				
				//if($stkpt == "SUBCONTRACT" || $stkpt== "ALFA N-IND PRIM")
				//{
				
				/*	$query = "SELECT date,scn,rcno,rm,if(used is null,issqty,issqty-used) as stock,days FROM (SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T1.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT distinct(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like '$stkpt1' order by date,rcno) AS TABLE1 

						LEFT JOIN(SELECT dcnum,scn FROM dc_det) AS TDCDET ON TABLE1.rcno=CONCAT('DC-',TDCDET.dcnum)

						where rm like '$pnum' order by rm";
				*/
				
				//}
				//else
				//{
				//	$query = "SELECT date,rcno,rm,if(used is null,issqty,issqty-used) as stock,days FROM (SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T1.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT distinct(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN  d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like '$stkpt1' order by date,rcno) AS TABLE1 where rm like '$pnum' order by rm";	
				//}
				
				$result2 = $conn->query($query);
				$i=0;
				$stk=0;
				$n1 = mysqli_num_rows($result2);
				if($n1>0)
				{	
					while($row1 = mysqli_fetch_array($result2))
					{
						$i=$i+1;
						if(substr($row1['rcno'],0,1)=="A")
						{
							$u="Kg";
						}
						else
						{
							$u="Nos";
						}
						echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text'  readonly='readonly' value='$i'</td>";
						echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text'  readonly='readonly' name='op[]' value='$stkpt'</td>";
						echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text'  name='dt[]' readonly='readonly' value='".$row1['date']."'</td>";
						//echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text'  name='dt[]' readonly='readonly' value='".$row1['scn']."'</td>";
						echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text'  name='pn[]' readonly='readonly' value='".$row1['rm']."'</td>";
						echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text'  name='pr[]' readonly='readonly' value='".$row1['rcno']."'</td>";
						echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text'  name='eq[]' readonly='readonly' value='".round($row1['stock'],2)."'</td>";
						//$stk=$stk+round($row1['stock'],2);
						//echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text' readonly='readonly' value='".$stk."'</td>";
						echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text'  name='aq[]' value='".round($row1['stock'],2)."'</td>";
						//echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text'  name='um[]' readonly='readonly' value='".$u."'</td>";
						//echo"<td>".$row1['days']."</td>";
						echo"</tr>";
					}
				}
				else if(isset($_GET['pnum']))
				{
					$t=$_GET['stkpt'];
					$t1=$_GET['pnum'];
					$query = "SELECT * from m14 where oper='$t'";
					$result2 = $conn->query($query);
					$row1 = mysqli_fetch_array($result2);
					$t=$row1['stkpt'];
					$query = "SELECT * from d12 where stkpt='$t' and pnum='$t1' and partissued!='' limit 1";
					$result2 = $conn->query($query);
					$n1 = mysqli_num_rows($result2);
					if($n1==1)
					{
						$row1 = mysqli_fetch_array($result2);
						$rd=$row1['rcno'];
						//mysqli_query($con,"update d12 set partissued=partissued+1000 where rcno='$rd' limit 1");
						//mysqli_query($con,"update d11 set closedate='0000-00-00' where rcno='$rd'");
					}
				}
			}
			?>
			<div>
				<input type="SUBMIT" style="right-align:50px" name="submit" value="RECONCILE"/>
			</div>
			<br><br>
		</form>
	</body>
</html>
		