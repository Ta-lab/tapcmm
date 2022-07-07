<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		
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
		<h4 style="text-align:center"><label>INVOICE REPORT</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'OPEN RCNO')" value="Export to Excel">
		</div>
		<br/></br>
		<script>
			function reload(form)
			{
				
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				var s2 = document.getElementById("pn").value;
				var s3 = document.getElementById("cc").value;
				self.location='invoice.php?f='+s0+'&tt='+s1+'&pn='+s2+'&cc='+s3;
			}
		</script>
		<div class="divclass">
		<form method="GET">	
			</br>
			<div class="find">
				<label>FROM DATE</label>
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
				<label>TILL DATE</label>
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
			<datalist id="partlist" >
			<?php
			$con = mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				echo "connection failed";
			if(isset($_GET['cc']) && $_GET['cc']!="")
			{
				$c=$_GET['cc'];
				$query = "SELECT distinct pn FROM invmaster where ccode='$c'";
			}
			else
			{
				$query = "SELECT distinct pn FROM invmaster";
			}
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
			<datalist id="clist" >
			<?php
			$con = mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				echo "connection failed";
			$query = "SELECT distinct ccode FROM invmaster";
			$result = $con->query($query);
			echo"<option value=''>Select one</option>";
			while ($row = mysqli_fetch_array($result)) 
			{
				if($_GET['cc']==$row['ccode'])
					echo "<option selected value='".$row['ccode']."'>".$row['ccode']."</option>";
				else
					echo "<option value='".$row['ccode']."'>".$row['ccode']."</option>";
			}
			?>
			</datalist>
			<div class="find2">
				<label>CUSTOMER CODE</label>
							<input type="text" id="cc" name="cc" list="clist"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['cc']))
							{
								echo $_GET['cc'];
							}
							else
							{
								echo "";
							}
							?>"/>
			</div><br><br>
			<div class="find">
				<label>PART NUMBER</label>
							<input type="text" id="pn" name="pn" list="partlist"  onchange="reload(this.form)" value="<?php
							if(isset($_GET['pn']))
							{
								echo $_GET['pn'];
							}
							else
							{
								echo "";
							}
							?>"/>
			</div>
			<br>
			</form>
			<br>
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
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				
				if($tt<='2021-03-31')
				{
					$tcspercentage = "0.075%";
				}
				else if($tt>='2021-04-01' && $tt<='2021-06-30'){
					$tcspercentage = "0.1%";
				}
				else{
					$tcspercentage = "";
				}
				
				$conn = new mysqli($servername, $username, $password, "mypcm");
					echo'<table id="testTable" align="center">
				  <tr>
					<th>C-CODE</th><th>INV NO</th><th>INV_DATE</th><th>PARTY NAME</th><th>PARTY ADD 1</th><th>PARTY ADD 2</th><th>PARTY ADD 3</th><th>PARTY GST NO</th>
					<th>CUSTOMER PAN NO</th>
					<th>DELIVERIED TO</th><th>PART NO</th><th>PART DESC</th>
					<th>HSN CODE</th><th>PO No</th><th>Line No</th><th>RATE</th>
					<th>PER</th><th>QTY</th><th>UOM</th><th>PC</th><th>SGST</th>
					<th>SGST AMT</th><th>CGST / IGST</th><th>CGST /IGST AMT</th>
					<th>BASIC AMT</th><th>TCS@ '.$tcspercentage.' AMT</th><th>TOTAL (RS)</th><th>TOTAL WEIGHT (KG)</th>
				  </tr>';
				if(isset($_GET['pn']) && $_GET['pn']!="" && isset($_GET['cc']) && $_GET['cc']!="")
				{
					$p=$_GET['pn'];
					$c=$_GET['cc'];
					if(isset($_GET['f']) && $_GET['f']!="" && isset($_GET['to']) && $_GET['to']!="")
					{
						
					}
					else
					{
						$query = "SELECT * FROM `inv_det` LEFT JOIN (SELECT invpnum,IF(SUM(useage) IS NULL,0,SUM(useage)) as bom FROM (SELECT invpnum,SUM(useage) as useage FROM `pn_st` LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt='FG For Invoicing' GROUP BY pn_st.invpnum,n_iter) AS T GROUP BY invpnum) AS BOM ON inv_det.pn=BOM.invpnum where pn='$p' and ccode='$c' AND invdt>='$f' AND invdt<='$tt'";
					}
				
				}
				else if(isset($_GET['pn']) && $_GET['pn']!="" && $_GET['cc']=="")
				{
					$p=$_GET['pn'];
					$query = "SELECT * FROM `inv_det` LEFT JOIN (SELECT invpnum,IF(SUM(useage) IS NULL,0,SUM(useage)) as bom FROM (SELECT invpnum,SUM(useage) as useage FROM `pn_st` LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt='FG For Invoicing' GROUP BY pn_st.invpnum,n_iter) AS T GROUP BY invpnum) AS BOM ON inv_det.pn=BOM.invpnum where pn='$p' AND invdt>='$f' AND invdt<='$tt'";
				}
				else if(isset($_GET['cc']) && $_GET['cc']!="" && $_GET['pn']=="")
				{
					$c=$_GET['cc'];
					$query = "SELECT * FROM `inv_det` LEFT JOIN (SELECT invpnum,IF(SUM(useage) IS NULL,0,SUM(useage)) as bom FROM (SELECT invpnum,SUM(useage) as useage FROM `pn_st` LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt='FG For Invoicing' GROUP BY pn_st.invpnum,n_iter) AS T GROUP BY invpnum) AS BOM ON inv_det.pn=BOM.invpnum where ccode='$c' AND invdt>='$f' AND invdt<='$tt'";
				}
				else
				{
					$query = "SELECT * FROM `inv_det` LEFT JOIN (SELECT invpnum,IF(SUM(useage) IS NULL,0,SUM(useage)) as bom FROM (SELECT invpnum,SUM(useage) as useage FROM `pn_st` LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt='FG For Invoicing' GROUP BY pn_st.invpnum,n_iter) AS T GROUP BY invpnum) AS BOM ON inv_det.pn=BOM.invpnum WHERE invdt>='$f' AND invdt<='$tt'";
					//echo "SELECT * FROM `inv_det` LEFT JOIN (SELECT invpnum,IF(SUM(useage) IS NULL,0,SUM(useage)) as bom FROM (SELECT invpnum,SUM(useage) as useage FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt='FG For Invoicing' GROUP BY pn_st.invpnum,n_iter) AS T GROUP BY invpnum) AS BOM ON inv_det.pn=BOM.invpnum WHERE invdt>='$f' AND invdt<='$tt'";
				}
				$result = $conn->query($query);
				$q=0;$sg=0;$cig=0;$b=0;$t=0;$tcs=0;
				$q1=0;$sg1=0;$cig1=0;$b1=0;$t1=0;$kg=0;$tcs1=0;
				while($row = $result->fetch_assoc()){
					$d = date("d-m-Y", strtotime($row['invdt']));
					$d1 = date("Y-m-d", strtotime($row['invdt']));
					if($d1>=$f && $d1<=$tt)
					{
						$st="";
						if($row['sup']==0)
						{
							$q=$q+$row['qty'];
							$sg=$sg+round($row['sgstamt'],2);
							$cig=$cig+round($row['cigstamt'],2);
							$b=$b+$row['taxgoods'];
							$tcs=(float)$tcs+(float)$row['tcs'];
							$t=$t+$row['invtotal'];
							$kg=$kg+($row['bom']*$row['qty']);
						}
						if($row['sup']==1)
						{
							$st="style='color : yellow;'";
							$q1=$q1+$row['qty'];
							$sg1=$sg1+$row['sgstamt'];
							$cig1=$cig1+$row['cigstamt'];
							$b1=$b1+$row['taxgoods'];
							$tcs1=(float)$tcs1+(float)$row['tcs'];
							$t1=$t1+$row['invtotal'];
						}
						echo" <tr>
							<td $st>".$row['ccode']."</td><td $st>".$row['invno']."</td><td $st>".$d."</td><td $st>".$row['cname'].$row['cname1']."</td><td $st>".$row['cadd1']."</td><td $st>".$row['cadd2']."</td><td $st>".$row['cadd3']."</td><td $st>".$row['cgstno']."</td><td $st>".$row['pan_no']."</td><td $st>".$row['dtname']."</td>
							<td $st>".$row['pn']."</td><td $st>".$row['pd']."</td><td $st>".$row['hsnc']."</td><td $st>".$row['cpono']."</td><td $st>".$row['poino']."</td><td $st>".$row['rate']."</td>
							<td $st>".$row['per']."</td><td $st>".$row['qty']."</td><td $st>".$row['uom']."</td><td $st>".$row['pc']."</td>
							<td $st>".$row['sgst']."</td><td $st>".$row['sgstamt']."</td><td $st>".$row['cigst']."</td><td $st>".$row['cigstamt']."</td>
							<td $st>".$row['taxgoods']."</td><td $st>".$row['tcs']."</td><td $st>".$row['invtotal']."</td><td $st>".round($row['bom']*$row['qty'],2)."</td>";
						echo"</tr>";
					}
				}
				if(isset($_GET['pn']) && $_GET['pn']!="")
				{
					$p=$_GET['pn'];
				}
				else
				{
					$p="ALL PARTS";
				}
				echo" <tr>
					<td colspan='12'><h4 style='color : orange;'> INVOICE SUMMARY OF $p BETWEEN ".date('d-m-Y',strtotime($f))." AND ".date('d-m-Y',strtotime($tt))."</h4></td>
					<td><h4 style='color : orange;'> TOTAL QTY </h4></td><td colspan='3'></td><td><h4 style='color : orange;'> SGST VALUE </h4></td><td></td><td><h4 style='color : orange;'> CGST / IGST VALUE </h4></td>
					<td><h4 style='color : orange;'> BASIC VALUE </h4></td><td><h4 style='color : orange;'> TCS VALUE </h4></td><td><h4 style='color : orange;'> INVOICED VALUE </h4></td><td><h4 style='color : orange;'> INVOICED WEIGHT </h4></td>";
				echo"</tr>";
				echo" <tr>
					<td colspan='12'><h4>GENERAL INVOICE SUMMARY</h4></td>
					<td><h4>".$q."</h4></td><td colspan='3'></td><td><h4>".$sg."</h4></td><td></td><td><h4>".$cig."</h4></td>
					<td><h4>".$b."</h4></td><td><h4>".$tcs."</h4></td><td><h4>".$t."</h4></td><td><h4>".round($kg,2)."</h4></td>";
				echo"</tr>";
				echo" <tr>
					<td colspan='12'><h4 style='color : yellow;'>SUPPLIMENTARY INVOICE SUMMARY (If Any)</h4></td>
					<td><h4 style='color : yellow;'>".$q1."</h4></td><td colspan='3'></td><td><h4 style='color : yellow;'>".$sg1."</h4></td><td></td><td><h4 style='color : yellow;'>".$cig1."</h4></td>
					<td><h4 style='color : yellow;'>".$b1."</h4></td><td><h4 style='color : yellow;'>".$tcs1."</h4></td><td><h4 style='color : yellow;'>".$t1."</h4></td>";
				echo"</tr>";
			?>
		</div>
</body>
</html>