<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['user']=="123")
	{
		$id=$_SESSION['user'];
		$activity="DMR";
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
		<h4 style="text-align:center"><label>DAILY MATERIAL RECONCILATION STOCKING POINT</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'DMR')" value="Export to Excel">
	</div>
	<br><br>
	
	<script>
			function reload(form)
			{
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				self.location='dmr_stkpt.php?f='+s0+'&tt='+s1;
			}
	</script>
	
	<div class="divclass">
	<div class="find">
				<label>OPENING DATE</label>
							<input type="date" id="f" name="f"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['f']))
							{
								echo $_GET['f'];
							}
							else
							{
								echo date('Y-m-d',strtotime('-1 days'));
							}
							?>"/>
			</div>
			
			<div class="find1">
				<label>CLOSING DATE</label>
							<input type="date" id="tt" name="tt"  onchange="reload(this.form)" value="<?php
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

			<br><br><br>
		
			<?php
			
			
				if(!(isset($_GET['tt']) && isset($_GET['f'])))
				{
					$f = date('Y-m-d',strtotime('-1 days'));
					$tt = date('Y-m-d');
					$ydate=$f;
					$tdate=$tt;
					
				}
				else
				{
					$f = $_GET['f'];
					$tt= $_GET['tt'];
					$ydate=$f;
					$tdate=$tt;
				}
				
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				
				//$tdate=date('Y-m-d');
				//$ydate = date('Y-m-d', strtotime('-1 days'));
			
				
				
			echo'<table id="testTable" align="center">
				<tr>
					
					<th>RC/DC NUMBER</th>
					<th>PART NUMBER</th>
					<th>STOCKING POINT</th>
					<th>YESTERDAY OPENING STOCK</th>
					<th>RECEIVED</th>
					<th>ISSUED</th>
					<th>TODAY OPENING STOCK</th>
					<th>SHOULD BE OPENING</th>
					<th>VARIANCE</th>
					<th>FOREMAN</th>
					
					<th></th>
				</tr>';
				
				$query = "SELECT prc AS uprc,Opening.stkpt,Opening.pnum,rec,iss,s AS openingstk,received,issued,stk,nrec,niss,ns FROM(SELECT date,prc,T1.pnum,T1.stkpt,s,iss,rec,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived) AS rec,sum(partissued) AS iss,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='$ydate' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc) AS Opening LEFT JOIN(select d11.pnum,stkpt,prcno as prco,d12.date,SUM(partreceived) AS received,SUM(partissued) AS issued,sum(partreceived)-sum(partissued) as stk ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' AND d12.date='$ydate' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))) AS Yrec ON Opening.prc=Yrec.prco AND Opening.stkpt=Yrec.stkpt LEFT JOIN(SELECT date,nprc,T1.pnum,T1.stkpt,ns,niss,nrec,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((ns*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as nprc,date,sum(partreceived) AS nrec,sum(partissued) AS niss,sum(partreceived)-sum(partissued) as ns ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='$tdate' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,nprc ORDER BY stkpt,nprc) AS NxtOpening ON Opening.prc=NxtOpening.nprc AND Opening.stkpt=NxtOpening.stkpt";
				$result = $conn->query($query);
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr>";
					echo"<td>".$row['uprc']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['stkpt']."</td>";
					
					$opening=$row['openingstk']+$row['issued']-$row['received'];
					echo"<td>".round($opening,2)."</td>";
					
					echo"<td>".round($row['received'],2)."</td>";
					echo"<td>".round($row['issued'],2)."</td>";
					echo"<td>".round($row['ns'],2)."</td>";
					
					$shouldbeclosing=$row['openingstk']+$row['received']-$row['issued']-$row['stk'];
					//$shouldbeclosing=$opening+$row['received']-$row['issued'];
					echo"<td>".round($shouldbeclosing,2)."</td>";
					
					$variance=$row['ns']-$shouldbeclosing;
					echo"<td>".round($variance,2)."</td>";
					
					$pnum=$row['pnum'];
					$query1 = "SELECT DISTINCT pnum,foreman FROM m13 WHERE pnum LIKE '%$pnum%'";
					$result1 = $conn->query($query1);
					$row1 = mysqli_fetch_array($result1);
					echo"<td>".$row1['foreman']."</td>";
					
					echo"</tr>";
				}
				
			?>
		</div>
		
</body>
</html>

