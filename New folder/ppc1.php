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
	<link rel="stylesheet" type="text/css" href="ppc.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
		<h4 style="text-align:center"><label> PRODUCTION PLANNING [ PPC ] </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:left;display:none;">
		<?php
			echo "<div id='stat'><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><progress id='myProgress' value='0' max='100'></div>";
		?>
	</div>
	<div style="float:right">
		<input type="button" onclick="savetabledata();" value="FREEZE">
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<script>
		$("number").keypress(function(e) {
			if (isNaN(String.fromCharCode(e.which))) e.preventDefault();
		});
		
		function savetabledata()
		{
			var n = document.getElementById("testTable").rows.length;
			//alert(n);
			var pnum="";
			var type="";
			var demand="";
			var vmifg="";
			var vmisf="";
			var stkc="";
			var stkm="";
			var w="";
			for(i=1;i<15;i++)
			{
				var x = document.getElementById("testTable").rows[i].cells;
				var url = "saveppctable.php";
				if(i%3==1)
				{
					w=i%3;
					pnum=x[0].innerHTML;
					type=x[1].innerHTML;
					demand=x[2].innerHTML;
					vmifg=x[3].innerHTML;
					vmisf=x[4].innerHTML;
					stkc=x[6].innerHTML;
					stkm=x[7].innerHTML;
					var params = "pnum="+x[0].innerHTML+"&type="+x[1].innerHTML+"&demand="+x[2].innerHTML+"&vmifg="+x[3].innerHTML+"&vmisf="+x[4].innerHTML+"&order="+x[5].innerHTML+"&stkc="+x[6].innerHTML+"&stkm="+x[7].innerHTML+"&cncp="+x[8].innerHTML+"&manp="+x[9].innerHTML+"&pri="+x[10].innerHTML+"&ppccnc="+(x[11].innerHTML).substring(0,(x[11].innerHTML).length-4)+"&ppcman="+(x[12].innerHTML).substring(0,(x[12].innerHTML).length-4)+"&revised="+(x[13].innerHTML).substring(0,(x[13].innerHTML).length-4)+"&week="+w;
				}
				else
				{
					w=i%3;
					if(w==0)
					{
						w=i%4;
					}
					var params = "pnum="+pnum+"&type="+type+"&demand="+demand+"&vmifg="+vmifg+"&vmisf="+vmisf+"&order="+x[0].innerHTML+"&stkc="+stkc+"&stkm="+stkm+"&cncp="+x[1].innerHTML+"&manp="+x[2].innerHTML+"&pri="+x[3].innerHTML+"&ppccnc="+(x[4].innerHTML).substring(0,(x[4].innerHTML).length-4)+"&ppcman="+(x[5].innerHTML).substring(0,(x[5].innerHTML).length-4)+"&revised="+(x[6].innerHTML).substring(0,(x[6].innerHTML).length-4)+"&week="+w;
					//var params = "order="+x[0].innerHTML+"&cncp="+x[1].innerHTML+"&manp="+x[2].innerHTML+"&pri="+x[3].innerHTML+"&ppccnc="+(x[4].innerHTML).substring(0,(x[4].innerHTML).length-4)+"&ppcman="+(x[5].innerHTML).substring(0,(x[5].innerHTML).length-4)+"&revised="+(x[6].innerHTML).substring(0,(x[6].innerHTML).length-4);
				}
				// AJAX FOR SAVING TABLE
					if (window.XMLHttpRequest) {
						// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
					} else {
						// code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							//alert('inside');
							check();
							var rt=this.responseText;
						}
					};
					alert(url+"?"+params);
					xmlhttp.open("GET", url+"?"+params, true);
					xmlhttp.send();
					
				// AJAX FOR SAVING TABLE
			}
		}		
	</script>
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
						<th>DEMAND</th>
						<th>VMI IN FG</th>
						<th>SF VMI</th>
						<th>ORD QTY</th>
						<th>STK C</th>
						<th>STK M</th>
						
						<th>WEEK NO</th>
						
						<th>CNC PLAN</th>
						
						<th>MANUAL PLAN</th>
						
						<th>PRIORITY</th>
						
						
						<th>ENTER CNC</th>
						<th>ENTER MANUAL</th>
						<th>REVISED DATE</th>
						
						<th>Order Date</th>
						<th>Order Qty</th>
						<th>Requested</th>
						<th>Commited</th>
					</tr>';
				
				$thirdweekend=date('Y-m-d',strtotime('+21 days'));
				$fourthweekend=date('Y-m-d',strtotime('+28 days'));
				$fifthweekend=date('Y-m-d',strtotime('+35 days'));
				
				//WORKING QUERY
				//$query2 = "SELECT *,IF(w1>I,w1-I,0) AS IW1,IF(I>w1,IF(w2>(I-w1),w2-(I-w1),0),w2) AS IW2,IF(I>(w1+w2),IF(w3>(I-(w1+w2)),w3-(I-(w1+w2)),0),w3) AS IW3 FROM (SELECT pn,CNC.part,type,monthly,vmi_fg,sf,stock,stock1,order_date,req_date,commit,IF(c IS NULL,0,c) AS c,IF(w1 IS NULL,0,w1) AS w1,IF(w2 IS NULL,0,w2) AS w2,IF(w3 IS NULL,0,w3) AS w3,IF(cqty IS NULL,0,cqty) AS cqty,IF(I IS NULL,0,I) AS I FROM (SELECT DISTINCT T.pnum AS pn,part,type,monthly,vmi_fg,sf,SUM(stock) AS stock FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY T.pnum) AS CNC LEFT JOIN (SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1 FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation LIKE 'FG For S/C' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt NOT LIKE 'Semi%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY pnum) AS MANUAL ON CNC.pn=MANUAL.pnum LEFT JOIN (SELECT * FROM (SELECT demandmaster.pnum,order_date,req_date,commit,c,qty AS cqty FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum LEFT JOIN (SELECT DISTINCT pnum,IF(COUNT(*) IS NULL,0,COUNT(*)) AS c FROM (SELECT demandmaster.pnum,order_date,req_date,commit FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum) AS C GROUP BY pnum) AS CC ON demandmaster.pnum=CC.pnum) AS C) AS CC ON CC.pnum=CNC.pn LEFT JOIN (SELECT T1.pnum,w1,w2,w3 FROM (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w1 FROM `orderbook` WHERE req_date<='$thirdweekend' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w2 FROM `orderbook` WHERE req_date>'$thirdweekend' AND req_date<='$fourthweekend' GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w3 FROM `orderbook` WHERE req_date>'$fourthweekend' AND req_date<='$fifthweekend' GROUP BY pnum) AS T3 ON T1.pnum=T3.pnum) AS OB ON CC.pnum=OB.pnum LEFT JOIN (SELECT DISTINCT pn AS invpnum,IF(sum(qty) IS NULL,0,SUM(qty)) AS I FROM `inv_det` WHERE invdt>='2019-08-07' GROUP BY pn) AS INV ON INV.invpnum=CC.pnum) AS PPC";
				//echo "SELECT *,IF(w1>I,w1-I,0) AS IW1,IF(I>w1,IF(w2>(I-w1),w2-(I-w1),0),w2) AS IW2,IF(I>(w1+w2),IF(w3>(I-(w1+w2)),w3-(I-(w1+w2)),0),w3) AS IW3 FROM (SELECT pn,CNC.part,type,monthly,vmi_fg,sf,stock,stock1,order_date,req_date,commit,IF(c IS NULL,0,c) AS c,IF(w1 IS NULL,0,w1) AS w1,IF(w2 IS NULL,0,w2) AS w2,IF(w3 IS NULL,0,w3) AS w3,IF(cqty IS NULL,0,cqty) AS cqty,IF(I IS NULL,0,I) AS I FROM (SELECT DISTINCT T.pnum AS pn,part,type,monthly,vmi_fg,sf,SUM(stock) AS stock FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY T.pnum) AS CNC LEFT JOIN (SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1 FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation LIKE 'FG For S/C' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt NOT LIKE 'Semi%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY pnum) AS MANUAL ON CNC.pn=MANUAL.pnum LEFT JOIN (SELECT * FROM (SELECT demandmaster.pnum,order_date,req_date,commit,c,qty AS cqty FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum LEFT JOIN (SELECT DISTINCT pnum,IF(COUNT(*) IS NULL,0,COUNT(*)) AS c FROM (SELECT demandmaster.pnum,order_date,req_date,commit FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum) AS C GROUP BY pnum) AS CC ON demandmaster.pnum=CC.pnum) AS C) AS CC ON CC.pnum=CNC.pn LEFT JOIN (SELECT T1.pnum,w1,w2,w3 FROM (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w1 FROM `orderbook` WHERE req_date<='$thirdweekend' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w2 FROM `orderbook` WHERE req_date>'$thirdweekend' AND req_date<='$fourthweekend' GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w3 FROM `orderbook` WHERE req_date>'$fourthweekend' AND req_date<='$fifthweekend' GROUP BY pnum) AS T3 ON T1.pnum=T3.pnum) AS OB ON CC.pnum=OB.pnum LEFT JOIN (SELECT DISTINCT pn AS invpnum,IF(sum(qty) IS NULL,0,SUM(qty)) AS I FROM `inv_det` WHERE invdt>='2019-08-07' GROUP BY pn) AS INV ON INV.invpnum=CC.pnum) AS PPC";
				
				//TESTING ONE
				$query2 = "SELECT *,IF(w1>I,w1-I,0) AS IW1,IF(I>w1,IF(w2>(I-w1),w2-(I-w1),0),w2) AS IW2,IF(I>(w1+w2),IF(w3>(I-(w1+w2)),w3-(I-(w1+w2)),0),w3) AS IW3,b FROM (SELECT pn,CNC.part,type,monthly,vmi_fg,sf,stock,stock1,order_date,req_date,commit,IF(c IS NULL,0,c) AS c,IF(w1c IS NULL,0,w1c) AS w1c,IF(w2c IS NULL,0,w2c) AS w2c,IF(w3c IS NULL,0,w3c) AS w3c,IF(w1 IS NULL,0,w1) AS w1,IF(w2 IS NULL,0,w2) AS w2,IF(w3 IS NULL,0,w3) AS w3,IF(cqty IS NULL,0,cqty) AS cqty,invoiced_qty,IF(I IS NULL,0,I) AS I,IF(b IS NULL,0,b) AS b FROM (SELECT DISTINCT T.pnum AS pn,part,type,monthly,vmi_fg,sf,SUM(stock) AS stock FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY T.pnum) AS CNC LEFT JOIN (SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1 FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation LIKE 'FG For S/C' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt NOT LIKE 'Semi%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY pnum) AS MANUAL ON CNC.pn=MANUAL.pnum LEFT JOIN (SELECT * FROM (SELECT demandmaster.pnum,order_date,req_date,commit,c,qty AS cqty,invoiced_qty,qty-invoiced_qty AS b FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum LEFT JOIN (SELECT DISTINCT pnum,IF(COUNT(*) IS NULL,0,COUNT(*)) AS c FROM (SELECT demandmaster.pnum,order_date,req_date,commit,qty-invoiced_qty AS b FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum HAVING b>0) AS C GROUP BY pnum) AS CC ON demandmaster.pnum=CC.pnum HAVING b>0) AS C) AS CC ON CC.pnum=CNC.pn  LEFT JOIN (SELECT T1.pnum,w1,w2,w3,w1c,w2c,w3c FROM (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w1,COUNT(*) AS w1c FROM `orderbook` WHERE req_date<='2019-10-04' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w2,COUNT(*) AS w2c FROM `orderbook` WHERE req_date>'2019-10-04' AND req_date<='2019-10-11' GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w3,COUNT(*) AS w3c FROM `orderbook` WHERE req_date>'2019-10-11' AND req_date<='2019-10-18' GROUP BY pnum) AS T3 ON T1.pnum=T3.pnum) AS OB ON CC.pnum=OB.pnum LEFT JOIN (SELECT DISTINCT pn AS invpnum,IF(sum(qty) IS NULL,0,SUM(qty)) AS I FROM `inv_det` WHERE invdt>='2019-08-07' GROUP BY pn) AS INV ON INV.invpnum=CC.pnum) AS PPC";
				//$query2 = "SELECT *,IF(w1>I,w1-I,0) AS IW1,IF(I>w1,IF(w2>(I-w1),w2-(I-w1),0),w2) AS IW2,IF(I>(w1+w2),IF(w3>(I-(w1+w2)),w3-(I-(w1+w2)),0),w3) AS IW3,b FROM (SELECT pn,CNC.part,type,monthly,vmi_fg,sf,stock,stock1,order_date,req_date,commit,IF(c IS NULL,0,c) AS c,IF(w1 IS NULL,0,w1) AS w1,IF(w2 IS NULL,0,w2) AS w2,IF(w3 IS NULL,0,w3) AS w3,IF(cqty IS NULL,0,cqty) AS cqty,invoiced_qty,IF(I IS NULL,0,I) AS I,IF(b IS NULL,0,b) AS b FROM (SELECT DISTINCT T.pnum AS pn,part,type,monthly,vmi_fg,sf,SUM(stock) AS stock FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY T.pnum) AS CNC LEFT JOIN (SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1 FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation LIKE 'FG For S/C' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt NOT LIKE 'Semi%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY pnum) AS MANUAL ON CNC.pn=MANUAL.pnum LEFT JOIN (SELECT * FROM (SELECT demandmaster.pnum,order_date,req_date,commit,c,qty AS cqty,invoiced_qty,qty-invoiced_qty AS b FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum LEFT JOIN (SELECT DISTINCT pnum,IF(COUNT(*) IS NULL,0,COUNT(*)) AS c FROM (SELECT demandmaster.pnum,order_date,req_date,commit,qty-invoiced_qty AS b FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum HAVING b>0) AS C GROUP BY pnum) AS CC ON demandmaster.pnum=CC.pnum HAVING b>0) AS C) AS CC ON CC.pnum=CNC.pn  LEFT JOIN (SELECT T1.pnum,w1,w2,w3 FROM (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w1 FROM `orderbook` WHERE req_date<='2019-10-04' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w2 FROM `orderbook` WHERE req_date>'2019-10-04' AND req_date<='2019-10-11' GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w3 FROM `orderbook` WHERE req_date>'2019-10-11' AND req_date<='2019-10-18' GROUP BY pnum) AS T3 ON T1.pnum=T3.pnum) AS OB ON CC.pnum=OB.pnum LEFT JOIN (SELECT DISTINCT pn AS invpnum,IF(sum(qty) IS NULL,0,SUM(qty)) AS I FROM `inv_det` WHERE invdt>='2019-08-07' GROUP BY pn) AS INV ON INV.invpnum=CC.pnum) AS PPC";
				//echo "SELECT *,IF(w1>I,w1-I,0) AS IW1,IF(I>w1,IF(w2>(I-w1),w2-(I-w1),0),w2) AS IW2,IF(I>(w1+w2),IF(w3>(I-(w1+w2)),w3-(I-(w1+w2)),0),w3) AS IW3,b FROM (SELECT pn,CNC.part,type,monthly,vmi_fg,sf,stock,stock1,order_date,req_date,commit,IF(c IS NULL,0,c) AS c,IF(w1 IS NULL,0,w1) AS w1,IF(w2 IS NULL,0,w2) AS w2,IF(w3 IS NULL,0,w3) AS w3,IF(cqty IS NULL,0,cqty) AS cqty,invoiced_qty,IF(I IS NULL,0,I) AS I,IF(b IS NULL,0,b) AS b FROM (SELECT DISTINCT T.pnum AS pn,part,type,monthly,vmi_fg,sf,SUM(stock) AS stock FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY T.pnum) AS CNC LEFT JOIN (SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1 FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation LIKE 'FG For S/C' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt NOT LIKE 'Semi%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY pnum) AS MANUAL ON CNC.pn=MANUAL.pnum LEFT JOIN (SELECT * FROM (SELECT demandmaster.pnum,order_date,req_date,commit,c,qty AS cqty,invoiced_qty,qty-invoiced_qty AS b FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum LEFT JOIN (SELECT DISTINCT pnum,IF(COUNT(*) IS NULL,0,COUNT(*)) AS c FROM (SELECT demandmaster.pnum,order_date,req_date,commit,qty-invoiced_qty AS b FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum HAVING b>0) AS C GROUP BY pnum) AS CC ON demandmaster.pnum=CC.pnum HAVING b>0) AS C) AS CC ON CC.pnum=CNC.pn  LEFT JOIN (SELECT T1.pnum,w1,w2,w3 FROM (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w1 FROM `orderbook` WHERE req_date<='2019-10-04' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w2 FROM `orderbook` WHERE req_date>'2019-10-04' AND req_date<='2019-10-11' GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w3 FROM `orderbook` WHERE req_date>'2019-10-11' AND req_date<='2019-10-18' GROUP BY pnum) AS T3 ON T1.pnum=T3.pnum) AS OB ON CC.pnum=OB.pnum LEFT JOIN (SELECT DISTINCT pn AS invpnum,IF(sum(qty) IS NULL,0,SUM(qty)) AS I FROM `inv_det` WHERE invdt>='2019-08-07' GROUP BY pn) AS INV ON INV.invpnum=CC.pnum) AS PPC"; 
				$result2 = $conn->query($query2);
				$pnum="";
				$rn=1;
				while($row1 = mysqli_fetch_array($result2))
				{
					$md=$row1['monthly'];
					$fg=$row1['vmi_fg'];
					$sf=$row1['sf'];
					$ob=$row1['IW1'];
					$md1=$row1['monthly'];
					$fg1=$row1['vmi_fg'];
					$sf1=$row1['sf'];
					$ob1=$row1['IW1'];
					$obw4=$row1['IW2'];
					$obw5=$row1['IW3'];
					$obw4_1=$row1['IW2'];
					$obw5_1=$row1['IW3'];
					$mw1=0;
					$mw2=0;
					$mw3=0;
					$cw1=0;
					$cw2=0;
					$cw3=0;
					// ROW SPAN FOR FIRST ORDER FILTER
					if($row1['c']<4)
					{
						$f=1;
					}
					else
					{
						$f=round($row1['c']/3,0);
					}
					
					
					$stock=$row1['stock'];
					$stock1=$row1['stock1'];
					
					
					$t2=0;
					if($row1['pn']==$pnum)
					{
						//echo "<script>alert('coming')</script>";
						$rn=$rn+1;
					}
					else
					{
						$rn=1;
						$pnum=$row1['pn'];
					}
					$rs1=$row1['c'];
					if($rs1<3)
					{
						$rs1=3;
					}
					else
					{
						
					}
					if($rn==1)
					{
						echo"<td rowspan='$rs1'>".$row1['pn']."</td>";
						echo"<td rowspan='$rs1'>".$row1['type']."</td>";
						echo"<td rowspan='$rs1'>".$row1['monthly']."</td>";
						echo"<td rowspan='$rs1'>".$row1['vmi_fg']."</td>";
						echo"<td rowspan='$rs1'>".$row1['sf']."</td>";
						echo"<td rowspan='$f'>".$row1['IW1']."</td>";
						echo"<td rowspan='$rs1'>".$stock."</td>";
						echo"<td rowspan='$rs1'>".$stock1."</td>";
					}
					
					// WEEK 1 CALCULATION
					
					$carry=0;
					$carry1=0;
					
					if($row1['type']=="Kanban")
					{
						//CNC
						$d=($fg+$sf+($md/4))-$stock;
						//MANUAL
						$d1=($fg1+$sf1+($md/4))-$stock1;
					}
					if($row1['type']=="Regular")
					{
						//CNC
						//$d=($ob+$fg+$sf)-$stock; FG AND SF VMI INCLUDED
						$d=($ob)-$stock;
						//MANUAL
						//$d1=($ob1+$fg1+$sf1)-$stock1; FG AND VMI INCLUDED
						$d1=($ob1)-$stock1;
					}
					if($row1['type']=="Stranger")
					{
						//CNC
						$d=($ob)-$stock;
						//MANUAL
						$d1=($ob1)-$stock1;
						//CNC
						if($ob==0)
						{
							$d=0;
						}
						//MANUAL
						if($ob1==0)
						{
							$d1=0;
						}
					}
					//CNC
					if($d<0)
					{
						//echo"<td>0</td>";
						//echo"<td>".$d."</td>";
					}
					else
					{
						$cw1=round($d,0);
						//echo"<td>".round($d,0)."</td>";
					}
					//MANUAL
					if($d1<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						//echo"<td>".round($d1,0)."</td>";
						$mw1=round($d1,0);
					}
					//CNC
					if($d<0)
					{
						$carry=$d;
					}
					else
					{
						$carry=0;
					}
					//MANUAL
					if($d1<0)
					{
						$carry1=$d1;
					}
					else
					{
						$carry1=0;
					}
					
					
					// WEEK 2 CALCULATION
					
					
					if($row1['type']=="Kanban")
					{
						//CNC
						$d=($md/4)+$carry;
						//MANUAL
						$d1=($md1/4)+$carry1;
					}
					if($row1['type']=="Regular")
					{
						//CNC
						$d=($md/4)+$carry;
						//MANUAL
						$d1=($md1/4)+$carry1;
					}
					if($row1['type']=="Stranger")
					{
						//CNC
						$d=$obw4-$carry;
						//MANUAL
						$d1=$obw4_1-$carry1;
						//CNC
						if($obw4==0)
						{
							$d=0;
						}
						//MANUAL
						if($obw4_1==0)
						{
							$d1=0;
						}
					}
					//CNC
					if($d<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						$cw2=round($d,0);
						//echo"<td>".round($d,0)."</td>";
					}
					//MANUAL
					if($d1<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						//echo"<td>".round($d1,0)."</td>";
						$mw2=round($d1,0);
					}
					
					//CNC
					if($d<0)
					{
						$carry=$d;
					}
					else
					{
						$carry=0;
					}
					
					//MANUAL
					if($d1<0)
					{
						$carry1=$d1;
					}
					else
					{
						$carry1=0;
					}
					
					
					// WEEK 3 CALCULATION
					
					
					if($row1['type']=="Kanban")
					{
						//CNC
						$d=($md/4)+$carry;
						//MANAUL
						$d1=($md1/4)+$carry1;
					}
					if($row1['type']=="Regular")
					{
						//CNC
						$d=($md/4)+$carry;
						//MANUAL
						$d1=($md1/4)+$carry1;
					}
					if($row1['type']=="Stranger")
					{
						//CNC
						$d=$obw5-$carry;
						//MANUAl
						$d1=$obw5_1-$carry1;
						//CNC
						if($obw5==0)
						{
							$d=0;
						}
						//MANUAL
						if($obw5_1==0)
						{
							$d1=0;
						}
					}
					//CNC
					if($d<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						$cw3=round($d,0);
						//echo"<td>".round($d,0)."</td>";
					}
					//MANUAL
					if($d1<0)
					{
						//echo"<td>0</td>";
					}
					else
					{
						//echo"<td>".round($d1,0)."</td>";
						$mw3=round($d1,0);
					}
					
					//CNC
					if($d<0)
					{
						$carry=$d;
					}
					else
					{
						$carry=0;
					}
					//MANUAL
					if($d1<0)
					{
						$carry1=$d1;
					}
					else
					{
						$carry1=0;
					}
					
					//PRIORITY SETTING FOR w2 & W3
					if($row1['type']=="Kanban")
					{
						$p2=2;
						$p3=5;
					}
					else if($row1['type']=="Regular")
					{
						$p2=3;
						$p3=6;
					}
					else
					{
						$p2=4;
						$p3=7;
					}
					
					
					
					if(($row1['c']<2 || $row1['c']=="") && $rn==1)
					{
						
						echo"<td>Week 1</td>";
						echo"<td>".$cw1."</td>";
						echo"<td>".$mw1."</td>";
						echo"<td>1</td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='date' contenteditable='TRUE'></td>";
						
						echo"<td>".$row1['order_date']."</td>";
						echo"<td>".$row1['b']."</td>";
						echo"<td>".$row1['req_date']."</td>";
						echo"<td>".$row1['commit']."</td></tr>";
						
						
						echo"<tr><td>".$row1['IW2']."</td>";
						echo"<td>Week 2</td>";
						echo"<td>".$cw2."</td>";
						echo"<td>".$mw2."</td>";
						echo"<td>".$p2."</td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='date' contenteditable='TRUE'></td>";
						
						
						echo "<td></td><td></td><td></td><td></td></tr>";
						
						echo"<tr><td>".$row1['IW3']."</td>";
						echo"<td>Week 3</td>";
						echo"<td>".$cw3."</td>";
						echo"<td>".$mw3."</td>";
						echo"<td>".$p3."</td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='number' contenteditable='TRUE'></td>";
						echo"<td class='date' contenteditable='TRUE'></td>";
						
						//
						echo "<td></td><td></td><td></td><td></td></tr>";
					}
					else if($row1['c']==2 && $rn==1)
					{
						$f=1;
						$s=2;
						echo"<td rowspan='$f'>Week 1</td>";
						echo"<td rowspan='$f'>".$cw1."</td>";
						echo"<td rowspan='$f'>".$mw1."</td>";
						echo"<td rowspan='$f'>1</td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$f' class='date' contenteditable='TRUE'></td>";
						
						
						echo"<td rowspan='$f'>".$row1['order_date']."</td>";
						echo"<td rowspan='$f'>".$row1['b']."</td>";
						echo"<td rowspan='$f'>".$row1['req_date']."</td>";
						echo"<td rowspan='$f'>".$row1['commit']."</td></tr>";
							
						$row1 = mysqli_fetch_array($result2);
						
						echo"<tr><td rowspan='$f'>".$row1['IW2']."</td>";
						echo"<td rowspan='$f'>week 2</td>";
						echo"<td rowspan='$f'>".$cw2."</td>";
						echo"<td rowspan='$f'>".$mw2."</td>";
						echo"<td rowspan='$f'>".$p2."</td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$f' class='date' contenteditable='TRUE'></td>";
						
						echo"<td rowspan='$s'>".$row1['order_date']."</td>";
						echo"<td rowspan='$s'>".$row1['b']."</td>";
						echo"<td rowspan='$s'>".$row1['req_date']."</td>";
						echo"<td rowspan='$s'>".$row1['commit']."</td></tr>";
						
						echo"<tr><td rowspan='$f'>".$row1['IW3']."</td>";
						echo"<td rowspan='$f'>week 3</td>";	
						echo"<td rowspan='$f'>".$cw3."</td>";	
						echo"<td rowspan='$f'>".$mw3."</td>";	
						echo"<td rowspan='$f'>".$p3."</td>";	
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";	
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";	
						echo"<td rowspan='$f' class='date' contenteditable='TRUE'></td></tr>";	
					
					}
					else
					{
						$f=round($row1['c']/3,0);
						$s=round($row1['c']/3,0);
						$t=$row1['c']-($f+$s);
						
						
						echo"<td rowspan='$f'>week 1</td>";
						echo"<td rowspan='$f'>".$cw1."</td>";
						echo"<td rowspan='$f'>".$mw1."</td>";
						echo"<td rowspan='$f'>1</td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$f' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$f' class='date' contenteditable='TRUE'></td>";
						do{
							echo"<td>".$row1['order_date']."</td>";
							echo"<td>".$row1['b']."</td>";
							echo"<td>".$row1['req_date']."</td>";
							echo"<td>".$row1['commit']."</td></tr>";
							if($f>1)
							{
								$row1 = mysqli_fetch_array($result2);
								echo "<tr>";
							}
							$f--;
						}while($f>0);
						
						$row1 = mysqli_fetch_array($result2);
						echo"<tr><td rowspan='$s'>".$row1['IW2']."</td>";
						echo"<td rowspan='$s'>week 2</td>";
						echo"<td rowspan='$s'>".$cw2."</td>";
						echo"<td rowspan='$s'>".$mw2."</td>";
						echo"<td rowspan='$s'>".$p2."</td>";
						echo"<td rowspan='$s' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$s' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$s' class='date' contenteditable='TRUE'></td>";
						do{
							echo"<td>".$row1['order_date']."</td>";
							echo"<td>".$row1['b']."</td>";
							echo"<td>".$row1['req_date']."</td>";
							echo"<td>".$row1['commit']."</td></tr>";
							if($s>1)
							{
								$row1 = mysqli_fetch_array($result2);
								echo "<tr>";
							}
							$s--;
						}while($s>0);
						
						$row1 = mysqli_fetch_array($result2);
						echo"<tr><td rowspan='$t'>".$row1['IW3']."</td>";
						echo"<td rowspan='$t'>week 3</td>";
						echo"<td rowspan='$t'>".$cw3."</td>";
						echo"<td rowspan='$t'>".$mw3."</td>";
						echo"<td rowspan='$t'>".$p3."</td>";
						echo"<td rowspan='$t' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$t' class='number' contenteditable='TRUE'></td>";
						echo"<td rowspan='$t' class='date' contenteditable='TRUE'></td>";
						do{
							echo"<td>".$row1['order_date']."</td>";
							echo"<td>".$row1['b']."</td>";
							echo"<td>".$row1['req_date']."</td>";
							echo"<td>".$row1['commit']."</td></tr>";
							if($t>1)
							{
								$row1 = mysqli_fetch_array($result2);
								echo "<tr>";
							}
							$t--;
						}while($t>0);
					}
				}
			?>
		</div>
		
</body>
</html>