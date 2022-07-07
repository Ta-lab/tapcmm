<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="VALUATION REPORT";
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
		<h4 style="text-align:center"><label>PART NUMBER SUMMARY REPORT  [ O20tab ]</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
		<br/>
		<script>
			function reload(form)
			{
				var p = document.getElementById("p").value;
				self.location=<?php echo"'o20tab.php?pnum='"?>+p;
			}
		</script>
	<div class="divclass">
		<form id="form" name="form" method="post" action="o20tab.php">
			</br>
			<datalist id="partlist" >
					<?php
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
						$result1 = $con->query("select distinct pn as pnum from invmaster");
						echo"<option value=''>Select one</option>";
						while ($row1 = mysqli_fetch_array($result1))
						{
							if(isset($_GET['pnum'])==$row1['pnum'])
								echo "<option selected value='".$row1['pnum']."'>".$row1['pnum']."</option>";
							else
								echo "<option value='".$row1['pnum']."'>".$row1['pnum']."</option>";
						}
					?>
				</datalist>
			<div class="find">
				<label>PART NUMBER</label>
				<input type="text" style="width:50%; background-color:white;" class='s' onchange=reload(this.form) id="p" name="pn" list="partlist" value="<?php if(isset($_GET['pnum'])){echo $_GET['pnum'];}?>">
			</div>
	<?php
	if(isset($_GET['pnum']))
	{
		$tot="style='color : yellow;'";
		$s=0;$g=0;
		$st="";
		echo'<br><br><table id="testTable" align="center">
			  <tr>
				<th>STOCKING POINT / OPERATION</th>
				<th>SUBCONTRACT NAME</th>
				<th>PART NUMBER</th>
				<th>ROUTE CARD NUMBER</th>
				<th>QUANTITY  IN  STOCK</th>
				<th> UNIT </th>				
			  </tr>';
		$t="%%";
		$servername = "localhost";
		$username = "root";
		$password = "Tamil";
		$pnum=$_GET['pnum'];
		$con = new mysqli($servername, $username, $password, "mypcm");
		$query = "SELECT pnum from pn_st where stkpt='FG For Invoicing' and invpnum='$pnum' and n_iter>=1";		
		$result = $con->query($query);
		$c = $result->num_rows;
		
		if($c>1)
		{
			$cnt=0;
			//below query with ALFA stock
			//$query2 = "SELECT T2.rcno,T2.pnum,T3.useage,T2.operation,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,IF(uom IS NULL,'Nos',uom) as unit,Tdcdet.dcnum,Tdcdet.scn FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN(SELECT dcnum,scn FROM dc_det) AS Tdcdet ON T2.rcno=CONCAT('DC-',Tdcdet.dcnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper WHERE T2.pnum='$pnum' GROUP BY T2.rcno HAVING notused>0 order by t2.operation";
			
			//below query without alfa stock
			$query2 = "SELECT T2.rcno,T2.pnum,T3.useage,T2.operation,IF(scn LIKE 'ALFA',0, T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1))) as notused,IF(uom IS NULL,'Nos',uom) as unit,Tdcdet.dcnum,Tdcdet.scn FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN(SELECT dcnum,scn FROM dc_det) AS Tdcdet ON T2.rcno=CONCAT('DC-',Tdcdet.dcnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper WHERE T2.pnum='$pnum' GROUP BY T2.rcno HAVING notused>0 order by t2.operation";
			$result2 = $con->query($query2);
			$query3 = "SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T1.pnum LIKE '%-T' OR T1.pnum LIKE '%-C1' OR T1.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T1.pnum LIKE '%-S' OR T1.pnum LIKE '%-T' OR T1.pnum LIKE '%-C1' OR T1.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt WHERE T1.pnum='$pnum' GROUP BY stkpt,prc ORDER BY stkpt,prc";		
			$result3 = $con->query($query3);
			while($row1 = mysqli_fetch_array($result2))
			{	$scn = $row1['scn'];
				echo"<tr><td $st>".$row1['operation']."</td>";
				echo"<td $st>".$scn."</td>";
				echo"<td $st>".$row1['pnum']."</td>";
				echo"<td $st>".$row1['rcno']."</td>";
				if(substr($row1['rcno'],0,1)=="A")
				{
					$ktn=round($row1['notused'],2)/$row1['useage'];
					$s=$s+$ktn;$g=$g+$s;
					$ktn=" ( ".round($ktn)." Nos )";
				}
				else
				{
					$ktn="";
					$s=$s+round($row1['notused'],2);$g=$g+$s;
				}
				echo"<td $st>".round($row1['notused'],2).$ktn."</td>";
				echo"<td $st>".$row1['unit']."</td>";
				echo"</tr>";
			}
			while($row2 = mysqli_fetch_array($result3))
			{
				echo"<tr><td $st>".$row2['stkpt']."</td>";
				echo"<td $st>".""."</td>";
				echo"<td $st>".$row2['pnum']."</td>";
				echo"<td $st>".$row2['prc']."</td>";
				echo"<td $st>".round($row2['s'],2)."</td>";
				echo"<td $st>Nos</td>";
				echo"</tr>";
			}
			if($s>0)
			{
				echo"<tr><td></td><td></td><td></td><td $tot>SUB TOTAL</td>";
				echo"<td $tot>".$s."</td>";
				$g=$g+$s;
				echo"<td $tot>Nos</td>";
				echo"</tr>";
			}
		}
		$color=0;
		while($r = mysqli_fetch_array($result))
		{
			
			if($color==0)
			{
				$st="";
				$color=1;
			}
			else
			{
				$st="style='color : pink;'";
				$color=0;
			}
			$pnum=$r['pnum'];
			//$query2 = "SELECT T2.rcno,T2.pnum,T3.useage,T2.operation,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,IF(uom IS NULL,'Nos',uom) as unit,Tdcdet.dcnum,Tdcdet.scn FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN(SELECT dcnum,scn FROM dc_det) AS Tdcdet ON T2.rcno=CONCAT('DC-',Tdcdet.dcnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper WHERE T2.pnum='$pnum' GROUP BY T2.rcno HAVING notused>0 order by t2.operation";
			$query2 = "SELECT T2.rcno,T2.pnum,T3.useage,T2.operation,IF(scn LIKE 'ALFA',0,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1))) as notused,IF(uom IS NULL,'Nos',uom) as unit,Tdcdet.dcnum,Tdcdet.scn FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN(SELECT dcnum,scn FROM dc_det) AS Tdcdet ON T2.rcno=CONCAT('DC-',Tdcdet.dcnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper WHERE T2.pnum='$pnum' GROUP BY T2.rcno HAVING notused>0 order by t2.operation";
			$result2 = $con->query($query2);
			$query3 = "SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T1.pnum LIKE '%-T' OR T1.pnum LIKE '%-C1' OR T1.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T1.pnum LIKE '%-S' OR T1.pnum LIKE '%-T' OR T1.pnum LIKE '%-C1' OR T1.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt WHERE T1.pnum='$pnum' GROUP BY stkpt,prc ORDER BY stkpt,prc";
			$result3 = $con->query($query3);
			$i="";$s=0;
			while($row1 = mysqli_fetch_array($result2))
			{
				$scn = $row1['scn'];
				if($i=="")
				{
					$i=$row1['operation'];
				}
				if($i!=$row1['operation'])
				{
					if($s>0)
					{
						echo"<tr><td></td><td></td><td></td><td $tot>SUB TOTAL</td>";
						echo"<td $tot>".$s."</td>";
						$g=$g+$s;
						echo"<td $tot>Nos</td>";
						echo"</tr>";
					}
					$s=0;
					$i=$row1['operation'];
				}
				echo"<tr><td $st>".$row1['operation']."</td>";
				echo"<td $st>".$scn."</td>";
				echo"<td $st>".$row1['pnum']."</td>";
				echo"<td $st>".$row1['rcno']."</td>";
				if(substr($row1['rcno'],0,1)=="A")
				{
					$ktn=round($row1['notused'],2)/$row1['useage'];
					$s=$s+round($ktn);
					$ktn=" ( ".round($ktn)." Nos )";
				}
				else
				{
					$ktn="";
					$s=$s+round($row1['notused'],2);
				}
				echo"<td $st>".round($row1['notused'],2).$ktn."</td>";
				echo"<td $st>".$row1['unit']."</td>";
				echo"</tr>";
			}
			if($s>0)
			{
				echo"<tr><td></td><td></td><td></td><td $tot>SUB TOTAL</td>";
				echo"<td $tot>".$s."</td>";
				$g=$g+$s;
				echo"<td $tot>Nos</td>";
				echo"</tr>";
			}
			$i="";$s=0;
			while($row2 = mysqli_fetch_array($result3))
			{
				if($i=="")
				{
					$i=$row2['stkpt'];
				}
				if($i!=$row2['stkpt'])
				{
					echo"<tr><td></td><td></td><td></td><td $tot>SUB TOTAL</td>";
					echo"<td $tot>".$s."</td>";
					$g=$g+$s;
					echo"<td $tot>Nos</td>";
					echo"</tr>";
					$s=0;
					$i=$row2['stkpt'];
				}
				echo"<tr><td $st>".$row2['stkpt']."</td>";
				echo"<td $st>".""."</td>";
				echo"<td $st>".$row2['pnum']."</td>";
				echo"<td $st>".$row2['prc']."</td>";
				echo"<td $st>".round($row2['s'],2)."</td>";
				$s=$s+round($row2['s'],2);
				echo"<td $st>Nos</td>";
				echo"</tr>";
			}
			if($s>0)
			{
				echo"<tr><td></td><td></td><td></td><td $tot>SUB TOTAL</td>";
				echo"<td $tot>".$s."</td>";
				$g=$g+$s;
				echo"<td $tot>Nos</td>";
				echo"</tr>";
			}
			if($g>0)
			{
				echo"<tr><td></td><td></td><td></td><td style='color : orange;'> TOTAL</td>";
				echo"<td style='color : orange;'>".$g."</td>";
				echo"<td style='color : orange;'>Nos</td>";
				echo"</tr>";
			}
			$g=0;
		}
		echo"</table><br>";
	}
	?>
	</div>
		
</body>
</html>