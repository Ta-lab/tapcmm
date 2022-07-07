<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
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
		<h4 style="text-align:center"><label>DAILY MATERIAL RECONCILATION </label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'DMR')" value="Export to Excel">
		
		<input type="button" value="Export to PDF" id="btPrint" onclick="createPDF()" />
		
	</div>
	<br><br>
	<script>
			function reload(form)
			{
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				self.location='DailyMatReco.php?f='+s0+'&tt='+s1;
			}
	</script>
	
	
	<script>
		function createPDF() {
			var sTable = document.getElementById('tab').innerHTML;

			var style = "<style>";
			style = style + "table {width: 100%;font: 17px Calibri;}";
			style = style + "table, th, td {border: solid 1px #EDD; border-collapse: collapse;";
			style = style + "padding: 2px 3px;text-align: center;}";
			style = style + "</style>";

			// CREATE A WINDOW OBJECT.
			var win = window.open('', '', 'height=700,width=700');

			win.document.write('<html><head>');
			win.document.write('<title>Profile</title>');   // <title> FOR PDF HEADER.
			win.document.write(style);          // ADD STYLE INSIDE THE HEAD TAG.
			win.document.write('</head>');
			win.document.write('<body>');
			win.document.write(sTable);         // THE TABLE CONTENTS INSIDE THE BODY TAG.
			win.document.write('</body></html>');

			//win.document.close(); 	// CLOSE THE CURRENT WINDOW.

			//win.print();    // PRINT THE CONTENTS.
			_window.document.execCommand('SaveAs', true, fileName || fileURL)
			
		}
	</script>
	
	
	<div class="divclass">
		<form method="GET"></br>
		<div class="find3">
		<label>OPERATION</label>
			<select name ="prcno" id="prcno" onchange="reload1(this.form)">
			<option value="%%">ALL</option>";	
				<?php			
					$rat=$_GET['rat'];
					$con = mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						echo "connection failed";
					$query = "SELECT * from m14 where oper!='' AND oper!='Rework'";
					$result = $con->query($query);  
					while ($row = mysqli_fetch_array($result)) 
					{
						if(isset($_GET['rat']) && $_GET['rat']==$row['oper'])
							echo "<option selected value='" . $row['oper'] . "'>" . $row['oper'] ."</option>";
						else
							echo "<option value='" . $row['oper'] . "'>" . $row['oper'] ."</option>";              
					}
					echo "</select></h1>";
				?>
		</div></form>
		<script>
			function reload1(form)
			{
				var s2=form.prcno.options[form.prcno.options.selectedIndex].value;
				self.location='DailyMatReco.php?&rat='+s2;
			}
		</script>
		
		<div class="find">
			<label>OPENING DATE</label>
				<input type="date" id="f" name="f"  readonly onchange="reload(this.form)" value="<?php
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
				<input type="date" id="tt" name="tt" readonly onchange="reload(this.form)" value="<?php
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
			
			//$tdate = date('Y-m-d');
			//$ydate = date('Y-m-d', strtotime('-1 days'));
			
				
				
			echo'<div id="tab"><table id="testTable" align="center">
				<tr>
					
					<th>RC/DC NUMBER</th>
					<th>PART NUMBER</th>
					<th>OPERATION</th>
					<th>CATEGORY</th>
					<th>YESTERDAY OPENING STOCK</th>
					<th>RECEIVED</th>
					<th>CLOSED</th>
					<th>SCRAP</th>
					<th>RETURN QTY</th>
					<th>TODAY OPENING STOCK</th>
					<th>SHOULD BE OPENING</th>
					<th>VARIANCE</th>
					<th>RC STATUS</th>
					<th>FOREMAN</th>
					
					<th></th>
				</tr>';
				//$query = "SELECT DISTINCT date,operation,category,pnum,opstk.rcno,oqty,opisss.issued,opisss.received,opisss.used,opisss.scrap,nop.nqty FROM(SELECT edate,date,operation,category,pnum,rcno,unit,issued,received,used,scrap,qty FROM `opiss` UNION SELECT edate,date,operation,category,pnum,rcno,unit,0,0,0,0,qty FROM openingstock UNION SELECT edate,date,operation,category,pnum,rcno,unit,0,0,0,0,qty FROM nextdayopening ) AS dmr LEFT JOIN(SELECT nextdayopening.edate,rcno,qty AS nqty FROM nextdayopening WHERE nextdayopening.edate='2020-02-21') AS nop ON dmr.rcno=nop.rcno LEFT JOIN(SELECT openingstock.edate,rcno,qty AS oqty FROM openingstock WHERE openingstock.edate='2020-02-20') AS opstk ON dmr.rcno=opstk.rcno LEFT JOIN(SELECT opiss.edate,rcno,issued,received,used,scrap FROM opiss WHERE opiss.edate='2020-02-20') AS opisss ON dmr.rcno=opisss.rcno";
				//$query = "SELECT * FROM(SELECT rcno AS urcno FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno FROM opiss WHERE edate='2020-02-20') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-20') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno";
				
				//$query = "SELECT urcno,pnum,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT rcno AS urcno,pnum FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno,pnum FROM opiss WHERE edate='2020-02-21') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-21') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno";
				
				//workingquery
				//$query = "SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-02-20' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-02-21') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-20') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-21') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-21') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno";
				
				//$query = "SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,obom,IF(tot IS NULL,bom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-02-23' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-02-24') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-23') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-24') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-24') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum";
				
				//$query = "SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,obom,IF(tot IS NULL,bom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-02-24' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-02-25') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-24') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-25') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-25') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum";
				
				//working good with date 24 and 25
				//$query = "SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,fused,fscrap,ret,nqty,bom,ibom,obom,tot,tot1 FROM (SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,IF(ret IS NULL,0,ret) AS ret,nqty,bom,ibom,obom,tot,tot1,yreceived,yrejected,yyreceived,yyrejected,IF(yyreceived IS NULL,used,yyreceived) AS fused,IF(yyrejected IS NULL,scrap,yyrejected) AS fscrap FROM(SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,nqty,ibom,bom,obom,tot,tot1,yreceived,yrejected,IF(urcno LIKE '%A20%',yreceived*IF(ibom IS NULL,tot,ibom),yreceived) AS yyreceived,IF(urcno LIKE '%A20%',yrejected*IF(ibom IS NULL,tot,ibom),yrejected) AS yyrejected FROM(SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,ibom,obom,IF(tot IS NULL,ibom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-02-24' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-02-25') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-24') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-25') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-25') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS m13pnum,useage AS ibom FROM `m13`) AS m13bom ON pnum=m13bom.m13pnum LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum) AS DMR LEFT JOIN(SELECT DISTINCT prcno,d12.pnum AS yrecpnum,SUM(partreceived) as yreceived,SUM(qtyrejected) as yrejected FROM `d12` WHERE d12.date>='2020-02-24' AND d12.date<='2020-02-24' AND prcno!='' GROUP BY d12.prcno) AS yrec ON DMR.urcno=yrec.prcno) AS DMRF LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE date='2020-02-24') AS ret ON DMRF.urcno=ret.rcno ) AS DMRFI";
				
				//$query = "SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,fused,fscrap,ret,nqty,bom,ibom,obom,tot,tot1 FROM (SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,IF(ret IS NULL,0,ret) AS ret,nqty,bom,ibom,obom,tot,tot1,yreceived,yrejected,yyreceived,yyrejected,IF(yyreceived IS NULL,used,yyreceived) AS fused,IF(yyrejected IS NULL,scrap,yyrejected) AS fscrap FROM(SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,nqty,ibom,bom,obom,tot,tot1,yreceived,yrejected,IF(urcno LIKE '%A20%',yreceived*IF(ibom IS NULL,tot,ibom),yreceived) AS yyreceived,IF(urcno LIKE '%A20%',yrejected*IF(ibom IS NULL,tot,ibom),yrejected) AS yyrejected FROM(SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,ibom,obom,IF(tot IS NULL,ibom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-02-25' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-02-26') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-25') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-26') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-26') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS m13pnum,useage AS ibom FROM `m13`) AS m13bom ON pnum=m13bom.m13pnum LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum) AS DMR LEFT JOIN(SELECT DISTINCT prcno,d12.pnum AS yrecpnum,SUM(partreceived) as yreceived,SUM(qtyrejected) as yrejected FROM `d12` WHERE d12.date>='2020-02-25' AND d12.date<='2020-02-25' AND prcno!='' GROUP BY d12.prcno) AS yrec ON DMR.urcno=yrec.prcno) AS DMRF LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE date='2020-02-25') AS ret ON DMRF.urcno=ret.rcno ) AS DMRFI";
				
				//$query = "SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,fused,fscrap,ret,nqty,bom,ibom,obom,tot,tot1 FROM (SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,IF(ret IS NULL,0,ret) AS ret,nqty,bom,ibom,obom,tot,tot1,yreceived,yrejected,yyreceived,yyrejected,IF(yyreceived IS NULL,used,yyreceived) AS fused,IF(yyrejected IS NULL,scrap,yyrejected) AS fscrap FROM(SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,nqty,ibom,bom,obom,tot,tot1,yreceived,yrejected,IF(urcno LIKE '%A20%',yreceived*IF(ibom IS NULL,tot,ibom),yreceived) AS yyreceived,IF(urcno LIKE '%A20%',yrejected*IF(ibom IS NULL,tot,ibom),yrejected) AS yyrejected FROM(SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,ibom,obom,IF(tot IS NULL,ibom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-02-28' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-02-29') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-02-28') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-02-29') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-02-29') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS m13pnum,useage AS ibom FROM `m13`) AS m13bom ON pnum=m13bom.m13pnum LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum) AS DMR LEFT JOIN(SELECT DISTINCT prcno,d12.pnum AS yrecpnum,SUM(partreceived) as yreceived,SUM(qtyrejected) as yrejected FROM `d12` WHERE d12.date>='2020-02-28' AND d12.date<='2020-02-28' AND prcno!='' GROUP BY d12.prcno) AS yrec ON DMR.urcno=yrec.prcno) AS DMRF LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE date='2020-02-28') AS ret ON DMRF.urcno=ret.rcno ) AS DMRFI";
				
				//$query = "SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,fused,fscrap,ret,nqty,bom,ibom,obom,tot,tot1 FROM (SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,IF(ret IS NULL,0,ret) AS ret,nqty,bom,ibom,obom,tot,tot1,yreceived,yrejected,yyreceived,yyrejected,IF(yyreceived IS NULL,used,yyreceived) AS fused,IF(yyrejected IS NULL,scrap,yyrejected) AS fscrap FROM(SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,nqty,ibom,bom,obom,tot,tot1,yreceived,yrejected,IF(urcno LIKE '%A20%',yreceived*IF(ibom IS NULL,tot,ibom),yreceived) AS yyreceived,IF(urcno LIKE '%A20%',yrejected*IF(ibom IS NULL,tot,ibom),yrejected) AS yyrejected FROM(SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,ibom,obom,IF(tot IS NULL,ibom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-03-02' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-03-03') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-03-02') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-03-03') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-03-03') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS m13pnum,useage AS ibom FROM `m13`) AS m13bom ON pnum=m13bom.m13pnum LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum) AS DMR LEFT JOIN(SELECT DISTINCT prcno,d12.pnum AS yrecpnum,SUM(partreceived) as yreceived,SUM(qtyrejected) as yrejected FROM `d12` WHERE d12.date>='2020-03-02' AND d12.date<='2020-03-02' AND prcno!='' GROUP BY d12.prcno) AS yrec ON DMR.urcno=yrec.prcno) AS DMRF LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE date='2020-03-02') AS ret ON DMRF.urcno=ret.rcno ) AS DMRFI";
				
				//$query = "SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,fused,fscrap,ret,nqty,bom,ibom,obom,tot,tot1 FROM (SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,IF(ret IS NULL,0,ret) AS ret,nqty,bom,ibom,obom,tot,tot1,yreceived,yrejected,yyreceived,yyrejected,IF(yyreceived IS NULL,used,yyreceived) AS fused,IF(yyrejected IS NULL,scrap,yyrejected) AS fscrap FROM(SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,nqty,ibom,bom,obom,tot,tot1,yreceived,yrejected,IF(urcno LIKE '%A20%',yreceived*IF(ibom IS NULL,tot,ibom),yreceived) AS yyreceived,IF(urcno LIKE '%A20%',yrejected*IF(ibom IS NULL,tot,ibom),yrejected) AS yyrejected FROM(SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,ibom,obom,IF(tot IS NULL,ibom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='2020-03-02' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='2020-03-03') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='2020-03-02') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='2020-03-03') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='2020-03-03') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS m13pnum,useage AS ibom FROM `m13`) AS m13bom ON pnum=m13bom.m13pnum LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum) AS DMR LEFT JOIN(SELECT DISTINCT prcno,d12.pnum AS yrecpnum,SUM(partreceived) as yreceived,SUM(qtyrejected) as yrejected FROM `d12` WHERE d12.date>='2020-03-02' AND d12.date<='2020-03-02' AND stkpt!='FG For Scrap' AND prcno!='' GROUP BY d12.prcno) AS yrec ON DMR.urcno=yrec.prcno) AS DMRF LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE date='2020-03-02') AS ret ON DMRF.urcno=ret.rcno ) AS DMRFI";
				
				if(isset($_GET["rat"]))
				{
					$rat= $_GET['rat'];
					//$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE '$rat' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum  LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum GROUP BY T2.rcno order by t2.date,t2.rcno";
					$query = "SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,fused,fscrap,ret,nqty,bom,ibom,obom,tot,tot1 FROM (SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,IF(ret IS NULL,0,ret) AS ret,nqty,bom,ibom,obom,tot,tot1,yreceived,yrejected,yyreceived,yyrejected,IF(yyreceived IS NULL,used,yyreceived) AS fused,IF(yyrejected IS NULL,scrap,yyrejected) AS fscrap FROM(SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,nqty,ibom,bom,obom,tot,tot1,yreceived,yrejected,IF(urcno LIKE '%A20%',yreceived*IF(ibom IS NULL,tot,ibom),yreceived) AS yyreceived,IF(urcno LIKE '%A20%',yrejected*IF(ibom IS NULL,tot,ibom),yrejected) AS yyrejected FROM(SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,ibom,obom,IF(tot IS NULL,ibom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='$ydate' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='$tdate') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='$ydate') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='$tdate') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='$tdate') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS m13pnum,useage AS ibom FROM `m13`) AS m13bom ON pnum=m13bom.m13pnum LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum) AS DMR LEFT JOIN(SELECT DISTINCT prcno,d12.pnum AS yrecpnum,SUM(partreceived) as yreceived,SUM(qtyrejected) as yrejected FROM `d12` WHERE d12.date>='$ydate' AND d12.date<='$ydate' AND stkpt!='FG For Scrap' AND prcno!='' GROUP BY d12.prcno) AS yrec ON DMR.urcno=yrec.prcno) AS DMRF LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE date='$ydate') AS ret ON DMRF.urcno=ret.rcno ) AS DMRFI WHERE operation='$rat'";
					$result = $conn->query($query);
				}
				else
				{
				$query = "SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,fused,fscrap,ret,nqty,bom,ibom,obom,tot,tot1 FROM (SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,IF(ret IS NULL,0,ret) AS ret,nqty,bom,ibom,obom,tot,tot1,yreceived,yrejected,yyreceived,yyrejected,IF(yyreceived IS NULL,used,yyreceived) AS fused,IF(yyrejected IS NULL,scrap,yyrejected) AS fscrap FROM(SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,nqty,ibom,bom,obom,tot,tot1,yreceived,yrejected,IF(urcno LIKE '%A20%',yreceived*IF(ibom IS NULL,tot,ibom),yreceived) AS yyreceived,IF(urcno LIKE '%A20%',yrejected*IF(ibom IS NULL,tot,ibom),yrejected) AS yyrejected FROM(SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,ibom,obom,IF(tot IS NULL,ibom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='$ydate' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='$tdate') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='$ydate') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='$tdate') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='$tdate') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS m13pnum,useage AS ibom FROM `m13`) AS m13bom ON pnum=m13bom.m13pnum LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum) AS DMR LEFT JOIN(SELECT DISTINCT prcno,d12.pnum AS yrecpnum,SUM(partreceived) as yreceived,SUM(qtyrejected) as yrejected FROM `d12` WHERE d12.date>='$ydate' AND d12.date<='$ydate' AND stkpt!='FG For Scrap' AND prcno!='' GROUP BY d12.prcno) AS yrec ON DMR.urcno=yrec.prcno) AS DMRF LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE date='$ydate') AS ret ON DMRF.urcno=ret.rcno ) AS DMRFI";
				
				//echo "SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,fused,fscrap,ret,nqty,bom,ibom,obom,tot,tot1 FROM (SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,IF(ret IS NULL,0,ret) AS ret,nqty,bom,ibom,obom,tot,tot1,yreceived,yrejected,yyreceived,yyrejected,IF(yyreceived IS NULL,used,yyreceived) AS fused,IF(yyrejected IS NULL,scrap,yyrejected) AS fscrap FROM(SELECT DISTINCT urcno,operation,pnum,category,oqty,issued,received,used,scrap,nqty,ibom,bom,obom,tot,tot1,yreceived,yrejected,IF(urcno LIKE '%A20%',yreceived*IF(ibom IS NULL,tot,ibom),yreceived) AS yyreceived,IF(urcno LIKE '%A20%',yrejected*IF(ibom IS NULL,tot,ibom),yrejected) AS yyrejected FROM(SELECT urcno,pnum,operation,category,IF(oqty IS NULL,0,oqty) AS oqty,IF(issued IS NULL,0,issued) AS issued,IF(received IS NULL,0,received) AS received,IF(used IS NULL,0,used) AS used,iF(scrap IS NULL,0,scrap) AS scrap,IF(nqty IS NULL,0,nqty) AS nqty,bom,ibom,obom,IF(tot IS NULL,ibom,tot) AS tot,IF(tot1 IS NULL,obom,tot1) AS tot1 FROM(SELECT rcno AS urcno,pnum,operation,category FROM `openingstock` WHERE edate='$ydate' UNION SELECT rcno AS urcno,pnum,operation,category FROM opiss WHERE edate='$tdate') AS uniquerc LEFT JOIN(SELECT DISTINCT rcno,qty AS oqty FROM openingstock WHERE edate='$ydate') AS openstk ON uniquerc.urcno=openstk.rcno LEFT JOIN(SELECT DISTINCT rcno,issued,received,used,scrap FROM `opiss` WHERE edate='$tdate') AS opissued ON uniquerc.urcno=opissued.rcno LEFT JOIN(SELECT DISTINCT rcno,qty AS nqty FROM `nextdayopening` WHERE edate='$tdate') AS nxopenstk ON uniquerc.urcno=nxopenstk.rcno LEFT JOIN(SELECT DISTINCT pnum AS m13pnum,useage AS ibom FROM `m13`) AS m13bom ON pnum=m13bom.m13pnum LEFT JOIN(SELECT DISTINCT pnum AS rmpnum,bom,obom FROM `rmcategory`) AS bom ON pnum=bom.rmpnum LEFT JOIN(SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON pnum=ipbompnst.invpnum LEFT JOIN(SELECT invpnum,SUM(obom) AS tot1 FROM (SELECT DISTINCT pn_st.pnum,invpnum,obom FROM pn_st LEFT JOIN rmcategory ON rmcategory.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS opbompnst ON pnum=opbompnst.invpnum) AS DMR LEFT JOIN(SELECT DISTINCT prcno,d12.pnum AS yrecpnum,SUM(partreceived) as yreceived,SUM(qtyrejected) as yrejected FROM `d12` WHERE d12.date>='$ydate' AND d12.date<='$ydate' AND stkpt!='FG For Scrap' AND prcno!='' GROUP BY d12.prcno) AS yrec ON DMR.urcno=yrec.prcno) AS DMRF LEFT JOIN(SELECT rcno,ret FROM `d14` WHERE date='$ydate') AS ret ON DMRF.urcno=ret.rcno ) AS DMRFI";
			
				$result = $conn->query($query);
				}
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr>";
					//echo"<td>".$row['date']."</td>";
					//echo"<td>".$row['operation']."</td>";
					//echo"<td>".$row['category']."</td>";
					
					echo"<td>".$row['urcno']."</td>";
					
					echo"<td>".$row['pnum']."</td>";
					
					echo"<td>".$row['operation']."</td>";
					echo"<td>".$row['category']."</td>";
					echo"<td>".round($row['oqty'],2)."</td>";
					echo"<td>".round($row['issued'],2)."</td>";
					echo"<td>".round($row['fused'],2)."</td>";
					echo"<td>".round($row['fscrap'],2)."</td>";
					echo"<td>".round($row['ret'],2)."</td>";
					echo"<td>".round($row['nqty'],2)."</td>";
					
					$shouldbeclosing=$row['oqty']+$row['issued']-$row['fused']-$row['fscrap']-$row['ret'];
					echo"<td>".round($shouldbeclosing,2)."</td>";
					
					$variance=$row['nqty']-$shouldbeclosing;
					echo"<td>".round($variance,2)."</td>";
					
					$rcno=$row['urcno'];
					$query2 = "SELECT * FROM d11 WHERE rcno='$rcno'";
					$result2 = $conn->query($query2);
					$row2 = mysqli_fetch_array($result2);
					if($row2['closedate']=='0000-00-00'){
						echo"<td>OPEN</td>";
					}else{
						echo"<td>CLOSED</td>";
					}
					
					
					$pnum=$row['pnum'];
					$query1 = "SELECT DISTINCT pnum,foreman FROM m13 WHERE pnum LIKE '%$pnum%'";
					$result1 = $conn->query($query1);
					$row1 = mysqli_fetch_array($result1);
					if($row['operation']=="CNC_SHEARING"){
						echo"<td>CNC</td>";
					}else{
						echo"<td>".$row1['foreman']."</td>";
					}
					
					
					
					echo"</tr>";
				}
				
				
			?>
		</div>
		
</body>
</html>

