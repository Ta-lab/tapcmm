<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="VALUATION";
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
		<h4 style="text-align:center"><label> STOCK</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'OPENING / CLOSING STOCK AS ON DATE')" value="Export to Excel">
	</div>
	<br><br>
	<div class="find">
		<label>DATE</label>
			<input type="date" id="t" name="t"  onchange="reload1(this.form)" value="<?php
				if(isset($_GET['t']))
				{
					echo $_GET['t'];
				}
				else
				{
					echo date('Y-m-d');
				}
			?>"/>
			
			<script>
				function reload1(form)
				{
					var s3 = document.getElementById("t").value;
					self.location='valuationfinalexcelreprm.php?t='+s3;
				}
			</script>	
	</div>
	<br><br>
	<div class="divclass">
			<?php
				
				if(!(isset($_GET['t']) && isset($_GET['f'])))
				{
					//$f = date('Y-m-d',strtotime('-30 days'));
					//$t = date('Y-m-d');
					$t = $_GET['t'];
					
				}
				else
				{
					//$f = $_GET['f'];
					$t = $_GET['t'];
				}
				
				/*$dm=date("Y-m");
				date_default_timezone_set('Asia/Kolkata');
				$ym=date("Y-m",strtotime($dm));
				$my=date("m-Y",strtotime($dm));
				$ymdf=$ym."-01";
				$ymdl=$ym."-31";*/
			
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
						<th>OPERATION/STKPT</th>
						<th>VALUE</th>
						<th>KG</th>
					  </tr>';
				
				$query2 = "SELECT DISTINCT *,IF(useage IS NULL,tot,useage) AS bomuseage FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*((T7.useage/T7.tot)*rate))/per)*v)/100,(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per)*v)/100)  as value,v FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$t' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND (d11.closedate='0000-00-00' OR d11.closedate='$t') AND d11.operation!='FG For Invoicing' AND d11.date<='$t' AND d12.date<='$t' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper GROUP BY T2.rcno HAVING notused>0 order by t2.operation) AS VALREP LEFT JOIN (SELECT DISTINCT pnum AS m13pnum,useage FROM `m13`) AS m13bom ON VALREP.pnum=m13bom.m13pnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS pnstbom ON pnstbom.invpnum=VALREP.pnum GROUP BY VALREP.rcno ORDER BY VALREP.operation";
				$result2 = $conn->query($query2);
				//$totalweight = 0;
				$checkingstock=0;
				$checkingstockkgsum=0;
				
				$alfa=0;
				$alfakg=0;
				
				$manual=0;
				$manualkg=0;
				
				$manual1=0;
				$manualkg1=0;
				
				$manual2=0;
				$manualkg2=0;
				
				$returned=0;
				$returnedkg=0;
				
				$subcontract=0;
				$subcontractkg=0;
				
				$cnc=0;
				$cnckg=0;
				
				$fgforinvoicing=0;
				$fgforinvoicingkg=0;
				
				$fgforsc=0;
				$fgforsckg=0;
				
				$fromsc=0;
				$fromsckg=0;
				
				$rework=0;
				$reworkkg=0;
				
				$semifinished1=0;
				$semifinished1kg=0;
				
				$semifinished2=0;
				$semifinished2kg=0;
				
				$semifinished3=0;
				$semifinished3kg=0;
				
				$stores=0;
				$storeskg=0;
				
				$tosc=0;
				$tosckg=0;
				
				$workinprogress=0;
				$workinprogresskg=0;
				
				
				
				while($row = mysqli_fetch_array($result2))
				{
					echo"<tr>";
					
					//100%checking
					if($row['operation']=="100% Checking"){
						$checkingstock = $checkingstock + $row['value'];
						$kg = $row['notused']*$row['bomuseage'];
						$checkingstockkgsum = $checkingstockkgsum + $kg;
					}
					
					//ALFA N-IND PRIM
					if($row['operation']=="ALFA N-IND PRIM"){
						$alfa = $alfa + $row['value'];
						$kg = $row['notused']*$row['bomuseage'];
						$alfakg = $alfakg + $kg;
					}
					
					//MANUAL_AREA
					if($row['operation']=="MANUAL_AREA"){
						$manual = $manual + $row['value'];
						$kg = $row['notused']*$row['bomuseage'];
						$manualkg = $manualkg + $kg;						
					}
					
					//MANUAL_AREA-1
					if($row['operation']=="MANUAL_AREA-1"){
						$manual1 = $manual1 + $row['value'];
						$kg = $row['notused']*$row['bomuseage'];
						$manualkg1 = $manualkg1 + $kg;						
					}
					
					//MANUAL_AREA-2
					if($row['operation']=="MANUAL_AREA-2"){
						$manual2 = $manual2 + $row['value'];
						$kg = $row['notused']*$row['bomuseage'];
						$manualkg2 = $manualkg2 + $kg;						
					}
					
					//Returned
					if($row['operation']=="Returned"){
						$returned = $returned + $row['value'];
						$kg = $row['notused']*$row['bomuseage'];
						$returnedkg = $returnedkg + $kg;						
					}
					
					//SUBCONTRACT
					if($row['operation']=="SUBCONTRACT"){
						$subcontract = $subcontract + $row['value'];
						$kg = $row['notused']*$row['bomuseage'];
						$subcontractkg = $subcontractkg + $kg;						
					}
					
					//CNC_SHEARING
					if($row['operation']=="CNC_SHEARING"){
						$row['bomuseage']=1;
						$cnc = $cnc + $row['value'];
						$kg = $row['notused']*$row['bomuseage'];
						$cnckg = $cnckg + $kg;						
					}
					
					
					
					echo"</tr>";
				}
					
					echo"<tr>";
						echo"<td>100% Checking</td>";
						echo"<td>".$checkingstock."</td>";
						echo"<td>".$checkingstockkgsum."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>ALFA N-IND PRIM</td>";
						echo"<td>".$alfa."</td>";
						echo"<td>".$alfakg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>MANUAL_AREA</td>";
						echo"<td>".$manual."</td>";
						echo"<td>".$manualkg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>MANUAL_AREA-1</td>";
						echo"<td>".$manual1."</td>";
						echo"<td>".$manualkg1."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>MANUAL_AREA-2</td>";
						echo"<td>".$manual2."</td>";
						echo"<td>".$manualkg2."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>Returned</td>";
						echo"<td>".$returned."</td>";
						echo"<td>".$returnedkg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>SUBCONTRACT</td>";
						echo"<td>".$subcontract."</td>";
						echo"<td>".$subcontractkg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>CNC_SHEARING</td>";
						echo"<td>".$cnc."</td>";
						echo"<td>".$cnckg."</td>";
					echo"</tr>";
					
					
				
				$query3 = "SELECT DISTINCT *,IF(useage IS NULL,tot,useage) AS bomuseage FROM (SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='$t' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc ) AS VALREP LEFT JOIN (SELECT DISTINCT pnum AS m13pnum,useage FROM `m13`) AS m13bom ON VALREP.pnum=m13bom.m13pnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS pnstbom ON pnstbom.invpnum=VALREP.pnum GROUP BY stkpt,prc ORDER BY stkpt,prc";
				$result3 = $conn->query($query3);
				while($row1 = mysqli_fetch_array($result3))
				{
					echo"<tr>";
					
					//FG For Invoicing
					if($row1['stkpt']=="FG For Invoicing"){
						$fgforinvoicing = $fgforinvoicing + $row1['value'];
						$kg = $row1['s']*$row1['bomuseage'];
						$fgforinvoicingkg = $fgforinvoicingkg + $kg;						
					}
					
					//FG For S/C
					if($row1['stkpt']=="FG For S/C"){
						$fgforsc = $fgforsc + $row1['value'];
						$kg = $row1['s']*$row1['bomuseage'];
						$fgforsckg = $fgforsckg + $kg;						
					}
					
					//From S/C
					if($row1['stkpt']=="From S/C"){
						$fromsc = $fromsc + $row1['value'];
						$kg = $row1['s']*$row1['bomuseage'];
						$fromsckg = $fromsckg + $kg;						
					}
					
					//Rework
					if($row1['stkpt']=="Rework"){
						$rework = $rework + $row1['value'];
						$kg = $row1['s']*$row1['bomuseage'];
						$reworkkg = $reworkkg + $kg;						
					}
					
					//Semifinished1
					if($row1['stkpt']=="Semifinished1"){
						$semifinished1 = $semifinished1 + $row1['value'];
						$kg = $row1['s']*$row1['bomuseage'];
						$semifinished1kg = $semifinished1kg + $kg;						
					}
					
					//Semifinished2
					if($row1['stkpt']=="Semifinished2"){
						$semifinished2 = $semifinished2 + $row1['value'];
						$kg = $row1['s']*$row1['bomuseage'];
						$semifinished2kg = $semifinished2kg + $kg;						
					}
					
					//Semifinished3
					if($row1['stkpt']=="Semifinished3"){
						$semifinished3 = $semifinished3 + $row1['value'];
						$kg = $row1['s']*$row1['bomuseage'];
						$semifinished3kg = $semifinished3kg + $kg;						
					}
					
					//Stores
					if($row1['stkpt']=="Stores"){
						$stores = $stores + $row1['value'];
						$kg = $row1['s']*$row1['bomuseage'];
						$storeskg = $storeskg + $kg;						
					}
					
					//To S/C
					if($row1['stkpt']=="To S/C"){
						$tosc = $tosc + $row1['value'];
						$kg = $row1['s']*$row1['bomuseage'];
						$tosckg = $tosckg + $kg;						
					}
					
					echo"</tr>";
				}
				
					echo"<tr>";
						echo"<td>FG For Invoicing</td>";
						echo"<td>".$fgforinvoicing."</td>";
						echo"<td>".$fgforinvoicingkg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>FG For S/C</td>";
						echo"<td>".$fgforsc."</td>";
						echo"<td>".$fgforsckg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>From S/C</td>";
						echo"<td>".$fromsc."</td>";
						echo"<td>".$fromsckg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>Rework</td>";
						echo"<td>".$rework."</td>";
						echo"<td>".$reworkkg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>Semifinished1</td>";
						echo"<td>".$semifinished1."</td>";
						echo"<td>".$semifinished1kg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>Semifinished2</td>";
						echo"<td>".$semifinished2."</td>";
						echo"<td>".$semifinished2kg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>Semifinished3</td>";
						echo"<td>".$semifinished3."</td>";
						echo"<td>".$semifinished3kg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>Stores</td>";
						echo"<td>".$stores."</td>";
						echo"<td>".$storeskg."</td>";
					echo"</tr>";
					
					echo"<tr>";
						echo"<td>To S/C</td>";
						echo"<td>".$tosc."</td>";
						echo"<td>".$tosckg."</td>";
					echo"</tr>";
				
				
					//format wise
					//FINISHED GOODS
					/*echo"<tr>";
						echo"<td>FINISHED GOODS</td>";
						echo"<td>".$alfa."</td>";
						echo"<td>".$alfakg."</td>";
					echo"</tr>";
					
					//WORK IN PROGRESS
					$workinprogress = $alfa+$manual+$manual1+$manual2+$subcontract+$cnc+$fgforsc+$fromsc+$rework+$semifinished1+$semifinished2+$semifinished3+$stores+$tosc;
					$workinprogresskg = $alfakg+$manualkg+$manualkg1+$manualkg2+$subcontractkg+$cnckg+$fgforsckg+$fromsckg+$reworkkg+$semifinished1kg+$semifinished2kg+$semifinished3kg+$storeskg+$tosckg;
					echo"<tr>";
						echo"<td>WORK IN PROGRESS</td>";
						echo"<td>".$workinprogress."</td>";
						echo"<td>".$workinprogresskg."</td>";
					echo"</tr>";
					*/
					
				
				
			?>
		</div>
		
</body>
</html>