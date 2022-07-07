<?php
$t2=0;
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="ORDER BOOK REPORT CNC";
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
		<h4 style="text-align:center"><label>ORDER BOOK REPORT ( CNC ) [ o30 ]</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>PART NUMBER</th>
						<th>TYPE</th>
						<th>MONTHLY DEMAND</th>
						<th>VMI IN FG</th>
						<th>SEMIFINISHED VMI</th>
						<th>AVAILABLE STOCK</th>
						<th>STOCK TO BE PLAN</th>
						<th>WEEK 1</th>
						<th>WEEK 2</th>
						<th>WEEK 3</th>
						<th>WEEK 4</th>
					  </tr>';
				$query2 = "SELECT DISTINCT pnum,part,type,monthly,vmi_fg,sf,SUM(stock) AS stock FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT vssorder.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `vssorder` RIGHT JOIN pn_st ON vssorder.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY pnum";
				$result2 = $conn->query($query2);
				while($row1 = mysqli_fetch_array($result2))
				{
					$t2=0;
					echo"<tr><td>".$row1['pnum']."</td>";
					echo"<td>".$row1['type']."</td>";
					echo"<td>".$row1['monthly']."</td>";
					echo"<td>".$row1['vmi_fg']."</td>";
					echo"<td>".$row1['sf']."</td>";
					echo"<td>".$row1['stock']."</td>";
					$t=(($row1['monthly'])+$row1['vmi_fg']+$row1['sf']);
					$t1=$row1['stock'];
					$t2=$t-$t1;
					if($t2<0)
					{
						echo"<td>0</td>";
					}
					else
					{
						echo"<td>".$t2."</td>";
					}
					if($t1<$t)
					{
						$t2=$t-$t1;
						if($t2>=$row1['monthly']/4)
						{
							echo "<td>".($row1['monthly']/4)."</td>";
							$t1=$t1+($row1['monthly']/4);
						}
						else
						{
							echo "<td>".$t2."</td>";
							$t1=$t1+$t2;
						}
						if($t1<$t)
						{
							$t2=$t-$t1;
							if($t2>=$row1['monthly']/4)
							{
								echo "<td>".($row1['monthly']/4)."</td>";
								$t1=$t1+($row1['monthly']/4);
							}
							else
							{
								echo "<td>".$t2."</td>";
								$t1=$t1+$t2;
							}
							if($t1<$t)
							{
								$t2=$t-$t1;
								if($t2>=$row1['monthly']/4)
								{
									echo "<td>".($row1['monthly']/4)."</td>";
									$t1=$t1+($row1['monthly']/4);
								}
								else
								{
									echo "<td>".$t2."</td>";
									$t1=$t1+$t2;
								}
								if($t1<$t)
								{
									$t2=$t-$t1;
									if($t2>=$row1['monthly']/4)
									{
										echo "<td>".($row1['monthly']/4)."</td>";
										$t1=$t1+($row1['monthly']/4);
									}
									else
									{
										echo "<td>".$t2."</td>";
										$t1=$t1+$t2;
									}
								}
								else
								{
									echo "<td>0</td>";
								}
							}
							else
							{
								echo "<td>0</td>";
								echo "<td>0</td>";
							}
						}
						else
						{
							echo "<td>0</td>";
							echo "<td>0</td>";
							echo "<td>0</td>";
						}
					}
					else
					{
						echo "<td>0</td>";
						echo "<td>0</td>";
						echo "<td>0</td>";
						echo "<td>0</td>";
					}
					
					
					
					
					
					
					echo"</tr>";
				}
			?>
		</div>
		
</body>
</html>