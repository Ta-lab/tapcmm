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
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity=" WEEKLY CIMMIT";
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
	<script type="text/javascript" src="table_script.js"></script>
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<link rel="stylesheet" type="text/css" href="des.css">
	<style>
		.col-xs-2 {
		width: 25%;
		}
		.btn-success {
		margin-left: 70%;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<div  align="center"><br>
			<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<div style="float:right">
			<a href="index.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<h4 style="text-align:center"><label> WEEKLY PRODUCTION COMMIT </label></h4><br>
		<div class="divclass">
<form action="commitdb.php"  method="post" enctype="multipart/form-data">
</br>
<section class='intro3'>
	<div id="wrapper">
<table align="center"  id="data_table" border=1>
<tr>
<th>WEEK</th>
<th>PART NUMBER</th>
<th>FOREMAN</th>
<th>MACHINE</th>
<th>ALT MACHINE 1</th>
<th>ALT MACHINE 2</th>
<th>REQUIRED QUANTITY</th>
<th>COMMIT FOREMAN/MACHINE</th>
<th>COMMIT QUANTITY</th>
</tr>
<?php
$query = "select * from vmimaster left join m13 on vmimaster.pnum=m13.pnum group by vmimaster.pnum";
$result = $con->query($query);
$query1 = "select * from orderbook left join m13 on orderbook.pnum=m13.pnum group by orderbook.pnum";
$result1 = $con->query($query1);
while($row = mysqli_fetch_array($result))
{
	$f=0;$m=0;$c=0;
	$pnum=$row['pnum'];
	$query2 = "SELECT operation,SUM(notused) as s,unit FROM (SELECT T2.operation,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,IF(uom IS NULL,'Nos',uom) as unit FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper WHERE T2.pnum='$pnum' GROUP BY T2.rcno HAVING notused>0 order by t2.operation) AS oppart GROUP BY operation";
	$result2 = $con->query($query2);
	$query3 = "SELECT stkpt,SUM(s) AS s,'Nos' AS unit FROM (SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T1.pnum LIKE '%-T' OR T1.pnum LIKE '%-C1' OR T1.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T1.pnum LIKE '%-S' OR T1.pnum LIKE '%-T' OR T1.pnum LIKE '%-C1' OR T1.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt WHERE T1.pnum='$pnum' GROUP BY stkpt,prc ORDER BY stkpt,prc) AS stpart GROUP BY stkpt";		
	$result3 = $con->query($query3);
	while($row1 = mysqli_fetch_array($result2))
	{
		if($row1['operation']=="ALFA N-IND PRIM")
		{
			$f=$f+round($row1['s'],2);
		}
		else if ($row1['operation']!="CNC_SHEARING")
		{
			$m=$m+round($row1['s'],2);
		}
		else
		{
			$c=$c+round($row1['s'],2);
		}
	}
	while($row2 = mysqli_fetch_array($result3))
	{
		if($row2['stkpt']=="FG For S/C" || $row2['stkpt']=="FG For Invoicing")
		{
			$f=$f+round($row2['s'],2);
		}
		else
		{
			$c=$c+round($row2['s'],2);
		}
	}
	if($row['vmiqty']-$f>0)
	{
		$req=($row['vmiqty']-$f);
	}
	else
	{
		$req=0;
	}
	echo '<tr>
			<td><input type="text" readonly class="s" name ="w[]"  value="'.date('Y - W').'"></td>
			<td><input type="text" readonly class="s" name ="p[]"  value='.$row['pnum'].'></td>
			<td><input type="text" readonly class="s" name ="f[]"  value='.$row['foreman'].'></td>
			<td><input type="text" readonly class="s" name ="m[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="am1[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="am2[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="r[]"  value='.$req.'></td>
			<td><input type="text" required class="s" name ="fm[]"  value='.$row['foreman'].'></td>
			<td><input type="text" required class="s" max='.$req.' name ="c[]"  value=""/></td>
		</tr>';
	if(($row['vmiqty']-$f-$m-($c*$row['useage']))>0)
	{
		$req=($row['vmiqty']-$f-$m-($c*$row['useage']));
	}
	else
	{
		$req=0;
	}
	echo '<tr>
			<td><input type="text" readonly class="s" name ="w[]"  value="'.date('Y - W').'"></td>
			<td><input type="text" readonly class="s" name ="p[]"  value='.$row['pnum'].'></td>
			<td><input type="text" readonly class="s" name ="f[]"  value="CNC_SHEARING"></td>
			<td><input type="text" readonly class="s" name ="m[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="am1[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="am2[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="r[]"  value='.round($req).'></td>
			<td><input type="text" required class="s" name ="fm[]"  value="CNC_SHEARING"></td>
			<td><input type="text" required class="s" max='.round($req).' name ="c[]"  value=""/></td>
		</tr>';
}
while($row = mysqli_fetch_array($result1))
{
	$f=0;$m=0;$c=0;
	$pnum=$row['pnum'];
	$query2 = "SELECT operation,SUM(notused) as s,unit FROM (SELECT T2.operation,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,IF(uom IS NULL,'Nos',uom) as unit FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper WHERE T2.pnum='$pnum' GROUP BY T2.rcno HAVING notused>0 order by t2.operation) AS oppart GROUP BY operation";
	$result2 = $con->query($query2);
	$query3 = "SELECT stkpt,SUM(s) AS s,'Nos' AS unit FROM (SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T1.pnum LIKE '%-T' OR T1.pnum LIKE '%-C1' OR T1.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T1.pnum LIKE '%-S' OR T1.pnum LIKE '%-T' OR T1.pnum LIKE '%-C1' OR T1.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt WHERE T1.pnum='$pnum' GROUP BY stkpt,prc ORDER BY stkpt,prc) AS stpart GROUP BY stkpt";		
	$result3 = $con->query($query3);
	while($row1 = mysqli_fetch_array($result2))
	{
		if($row1['operation']=="ALFA N-IND PRIM")
		{
			$f=$f+round($row1['s'],2);
		}
		else if ($row1['operation']!="CNC_SHEARING")
		{
			$m=$m+round($row1['s'],2);
		}
		else
		{
			$c=$c+round($row1['s'],2);
		}
	}
	while($row2 = mysqli_fetch_array($result3))
	{
		if($row2['stkpt']=="FG For S/C" || $row2['stkpt']=="FG For Invoicing")
		{
			$f=$f+round($row2['s'],2);
		}
		else
		{
			$c=$c+round($row2['s'],2);
		}
	}
	if($row['orderqty']-$f>0)
	{
		$req=($row['orderqty']-$f);
	}
	else
	{
		$req=0;
	}
	echo '<tr>
			<td><input type="text" readonly class="s" name ="w[]"  value="'.date('Y - W').'"></td>
			<td><input type="text" readonly class="s" name ="p[]"  value='.$row['pnum'].'></td>
			<td><input type="text" readonly class="s" name ="f[]"  value='.$row['foreman'].'></td>
			<td><input type="text" readonly class="s" name ="m[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="am1[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="am2[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="r[]"  value='.$req.'></td>
			<td><input type="text" required class="s" name ="fm[]"  value='.$row['foreman'].'></td>
			<td><input type="text" required class="s" max='.$req.' name ="c[]"  value=""/></td>
		</tr>';
	if(($row['orderqty']-$f-$m-($c*$row['useage']))>0)
	{
		$req=($row['orderqty']-$f-$m-($c*$row['useage']));
	}
	else
	{
		$req=0;
	}
	echo '<tr>
			<td><input type="text" readonly class="s" name ="w[]"  value="'.date('Y - W').'"></td>
			<td><input type="text" readonly class="s" name ="p[]"  value='.$row['pnum'].'></td>
			<td><input type="text" readonly class="s" name ="f[]"  value="CNC_SHEARING"></td>
			<td><input type="text" readonly class="s" name ="m[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="am1[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="am2[]"  value=""/></td>
			<td><input type="text" readonly class="s" name ="r[]"  value='.round($req).'></td>
			<td><input type="text" required class="s" name ="fm[]"  value="CNC_SHEARING"></td>
			<td><input type="text" required class="s" max='.round($req).' name ="c[]"  value=""/></td>
		</tr>';
}
?>
</table>
<br>
<div class="form-group">
	<button type="submit"  class="btn btn-success" name="submit" style='margin-left:50%'>Submit</button>
</div>
</div>
</section>
</div>
<br>
</form>
</br>