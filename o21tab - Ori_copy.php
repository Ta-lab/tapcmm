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
		<h4 style="text-align:center"><label>DC REPORT</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'OPEN RCNO')" value="Export to Excel">
		</div>
		<br/></br>
		
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
							<script>
						function reload(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							var s2 = document.getElementById("pn").value;
							self.location='o21tab.php?f='+s0+'&tt='+s1+'&pn='+s2;
						}
					</script>
			</div>
			
			<div class="find1">
				<label>TILL DATE</label>
							<input type="date" id="tt" name="tt"  onchange="reload0(this.form)" value="<?php
							if(isset($_GET['tt']))
							{
								echo $_GET['tt'];
							}
							else
							{
								echo date('Y-m-d');
							}
							?>"/>
							<script>
						function reload0(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							var s2 = document.getElementById("pn").value;
							self.location='o21tab.php?f='+s0+'&tt='+s1+'&pn='+s2;
						}
					</script>
			</div>
			<datalist id="partlist" >
						<?php
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT distinct pn FROM dcmaster";
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
			<div class="find2">
				<label>PART NUMBER</label>
							<input type="text" id="pn" name="pn" list="partlist"  onchange="reload1(this.form)" value="<?php
							if(isset($_GET['pn']))
							{
								echo $_GET['pn'];
							}
							else
							{
								echo "";
							}
							?>"/>
							<script>
						function reload1(form)
						{
							
							var s0 = document.getElementById("f").value;
							var s1 = document.getElementById("tt").value;
							var s2 = document.getElementById("pn").value;
							self.location='o21tab.php?f='+s0+'&tt='+s1+'&pn='+s2;
						}
					</script>
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
				$dt = "00-00-0000";
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
					echo'
									<table id="testTable" align="center">
									  <tr>
										<th>DC NO</th>
										<th>DC_DATE</th>
										<th>PARTY NAME</th>
										<th>PART NUMBER TO</th>
										<th>QUANTITY(IN NOS)</th>
										<th>QUANTITY(IN KG)</th>
										<th>TOTAL AMOUNT</th>
									  </tr>';
				if(isset($_GET['pn']) && $_GET['pn']!="")
				{
					$p=$_GET['pn'];
					
					//old working query
					//$query = "select dc_det.dcnum,dc_det.dcdate,dc_det.scn,dc_det.pn,dc_det.qty,rate,per,useage,(qty*useage) as weight,(qty*rate/per) AS amount from dc_det LEFT JOIN pn_st ON dc_det.pn=pn_st.invpnum LEFT JOIN m13 ON pn_st.pnum=m13.pnum LEFT JOIN invmaster ON invmaster.pn=pn_st.pnum  where dc_det.pn='$p' and  dcdate>='$f' and dcdate<='$tt' group by dcnum";
					//echo "select dc_det.dcnum,dc_det.dcdate,dc_det.scn,dc_det.pn,dc_det.qty,rate,useage,(qty*useage) as weight,(qty*rate) AS amount from dc_det LEFT JOIN pn_st ON dc_det.pn=pn_st.invpnum LEFT JOIN m13 ON pn_st.pnum=m13.pnum LEFT JOIN invmaster ON invmaster.pn=pn_st.pnum  where dc_det.pn='$p' and  dcdate>='$f' and dcdate<='$tt' group by dcnum";
					
					$query = "SELECT dcnum,dcdate,scn,DCREPORT.pn,qty,rate,per,Tm13.useage,bom,IF(useage IS NULL,bom,useage) AS TBOM,(qty*IF(useage IS NULL,bom,useage)) AS weight,(qty*rate/per) AS amount FROM(select dc_det.dcnum,dc_det.dcdate,dc_det.scn,dc_det.pn,dc_det.qty from dc_det where dc_det.pn='$p' and dcdate>='$f' and dcdate<='$tt') AS DCREPORT

LEFT JOIN(SELECT DISTINCT pnum,invpnum FROM pn_st ) AS Tpnst ON DCREPORT.pn=Tpnst.pnum OR DCREPORT.pn=Tpnst.invpnum

LEFT JOIN(SELECT DISTINCT pnum,useage FROM m13 ) AS Tm13 ON DCREPORT.pn=Tm13.pnum

LEFT JOIN invmaster ON invmaster.pn=Tpnst.pnum OR invmaster.pn=Tpnst.invpnum 

LEFT JOIN(SELECT invpnum,IF(SUM(useage) IS NULL,0,SUM(useage)) as bom FROM (SELECT invpnum,SUM(useage) as useage FROM `pn_st` LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt='FG For Invoicing' GROUP BY pn_st.invpnum,n_iter) AS T GROUP BY invpnum) AS TTT ON TTT.invpnum=DCREPORT.pn

group by dcnum";
					
					
				}
				else
				{
					//$query = "select dc_det.dcnum,dc_det.dcdate,dc_det.scn,dc_det.pn,dc_det.qty,rate,useage,(qty*useage) as weight,(qty*rate) AS amount from dc_det LEFT JOIN pn_st ON dc_det.pn=pn_st.invpnum LEFT JOIN m13 ON pn_st.pnum=m13.pnum LEFT JOIN invmaster ON invmaster.pn=pn_st.pnum  where dcdate>='$f' and dcdate<='$tt' group by dcnum";
					//old working query
					//$query = "select dc_det.dcnum,dc_det.dcdate,dc_det.scn,dc_det.pn,dc_det.qty,rate,per,useage,(qty*useage) as weight,(qty*rate/per) AS amount from dc_det LEFT JOIN pn_st ON dc_det.pn=pn_st.invpnum LEFT JOIN m13 ON pn_st.pnum=m13.pnum LEFT JOIN invmaster ON invmaster.pn=pn_st.pnum  where dcdate>='$f' and dcdate<='$tt' group by dcnum";
					
					$query = "SELECT dcnum,dcdate,scn,DCREPORT.pn,qty,rate,per,Tm13.useage,bom,IF(useage IS NULL,bom,useage) AS TBOM,(qty*IF(useage IS NULL,bom,useage)) AS weight,(qty*rate/per) AS amount FROM(select dc_det.dcnum,dc_det.dcdate,dc_det.scn,dc_det.pn,dc_det.qty from dc_det where dcdate>='$f' and dcdate<='$tt') AS DCREPORT

LEFT JOIN(SELECT DISTINCT pnum,invpnum FROM pn_st ) AS Tpnst ON DCREPORT.pn=Tpnst.pnum OR DCREPORT.pn=Tpnst.invpnum

LEFT JOIN(SELECT DISTINCT pnum,useage FROM m13 ) AS Tm13 ON DCREPORT.pn=Tm13.pnum

LEFT JOIN invmaster ON invmaster.pn=Tpnst.pnum OR invmaster.pn=Tpnst.invpnum 

LEFT JOIN(SELECT invpnum,IF(SUM(useage) IS NULL,0,SUM(useage)) as bom FROM (SELECT invpnum,SUM(useage) as useage FROM `pn_st` LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt='FG For Invoicing' GROUP BY pn_st.invpnum,n_iter) AS T GROUP BY invpnum) AS TTT ON TTT.invpnum=DCREPORT.pn

group by dcnum";
					
				}
				$result = $conn->query($query);
				$q=0;$sg=0;$cig=0;$b=0;$t=0;
				$q1=0;$sg1=0;$cig1=0;$b1=0;$t1=0;
				while($row = $result->fetch_assoc()){
						echo" <tr>
							<td>".$row['dcnum']."</td>
							<td>".$row['dcdate']."</td>
							<td>".$row['scn']."</td>
							<td>".$row['pn']."</td>
							<td>".$row['qty']."</td>
							<td>".$row['weight']."</td>
							<td>".round($row['amount'],2)."</td>";
						echo"</tr>";
				}
			?>
		</div>
</body>
</html>
</html>