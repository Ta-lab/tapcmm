<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="RM WISE RECON";
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
		<h4 style="text-align:center"><label> RM WISE RECONCILATION STKPT</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<br><br>
	<div class="divclass">
			<?php
				
				$date = date('Y-m-d', strtotime('-1 days'));
				//$date = date('Y-m-d');
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>DATE</th>
						<th>RM CATEGORY</th>
						<th>STOCKING POINT</th>
						<th>RC NO</th>
						<th>PART NUMBER</th>
						<th>PART ISSUED</th>
						<th>PART RECEIVED</th>
						
					  </tr>';
				
				//$query2 = "SELECT date,prc,T1.pnum,T1.stkpt,partreceived,partissued,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived) AS partreceived,sum(partissued) AS partissued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date>='2019-12-01' AND d12.date<='2019-12-31' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued)>0)) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc";
				
				//workingquery
				//$query2 = "SELECT date,prc,RMCAT.category,T1.pnum,T1.stkpt,partreceived,partissued,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived) AS partreceived,sum(partissued) AS partissued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date>='2019-12-01' AND d12.date<='2019-12-31' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued)>0)) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T1.pnum=RMCAT.pnum OR T1.pnum=RMCAT.invpnum GROUP BY stkpt,prc ORDER BY stkpt,prc";
				
				//workingquery1
				//$query2 = "SELECT date,prc,RMCAT.category,T1.pnum,T1.stkpt,partreceived,partissued,s,IF(RET.ret IS NULL,0,RET.ret) AS ret,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived) AS partreceived,sum(partissued) AS partissued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date>='2019-12-01' AND d12.date<='2019-12-31' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued)>0)) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T1.pnum=RMCAT.pnum OR T1.pnum=RMCAT.invpnum LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE d14.date>='2019-12-01' AND d14.date<='2019-12-31' GROUP BY d14.rcno) AS RET ON T1.prc=RET.rcno GROUP BY stkpt,prc ORDER BY stkpt,prc";
				
				//workingquery with pnst logic
				//$query2 = "SELECT date,prc,T11.pnum,T11.stkpt,partreceived,partissued,s,category,IF(RET.ret IS NULL,0,RET.ret) AS ret FROM(SELECT date,prc,T1.pnum,T1.stkpt,partreceived,partissued,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived) AS partreceived,sum(partissued) AS partissued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date>='2019-12-01' AND d12.date<='2019-12-31' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued)>0)) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc) AS T11 LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum) AS RMCAT ON T11.pnum=RMCAT.pnum OR T11.pnum=RMCAT.invpnum LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE d14.date>='2019-12-01' AND d14.date<='2019-12-31' GROUP BY d14.rcno) AS RET ON T11.prc=RET.rcno";
				
				//$query2 = "SELECT date,prc,T11.pnum,T11.stkpt,partreceived,partissued,s,category,IF(RET.ret IS NULL,0,RET.ret) AS ret FROM(SELECT date,prc,T1.pnum,T1.stkpt,partreceived,partissued,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived) AS partreceived,sum(partissued) AS partissued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date>='2020-02-13' AND d12.date<='2020-02-13' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued)>0)) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc) AS T11 LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum) AS RMCAT ON T11.pnum=RMCAT.pnum OR T11.pnum=RMCAT.invpnum LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE d14.date>='2020-02-13' AND d14.date<='2020-02-13' GROUP BY d14.rcno) AS RET ON T11.prc=RET.rcno";
				
				$query2 = "SELECT date,prc,T11.pnum,T11.stkpt,partreceived,partissued,s,category,IF(RET.ret IS NULL,0,RET.ret) AS ret FROM(SELECT date,prc,T1.pnum,T1.stkpt,partreceived,partissued,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived) AS partreceived,sum(partissued) AS partissued,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date>='$date' AND d12.date<='$date' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued)>0)) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc) AS T11 LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum) AS RMCAT ON T11.pnum=RMCAT.pnum OR T11.pnum=RMCAT.invpnum LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T11.prc=RET.rcno";
				
				$result2 = $conn->query($query2);
				while($row = mysqli_fetch_array($result2))
				{
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['category']."</td>";
					echo"<td>".$row['stkpt']."</td>";
					echo"<td>".$row['prc']."</td>";
					echo"<td>".$row['pnum']."</td>";
					echo"<td>".$row['partissued']."</td>";
					echo"<td>".$row['partreceived']."</td>";
					//echo"<td>".$row['ret']."</td>";
					echo"</tr>";
				}
				
				
				
				
			?>
		</div>
		
</body>
</html>