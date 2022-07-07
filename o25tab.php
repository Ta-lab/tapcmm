<?php
session_start();
if(isset($_SESSION['user']))
{
	//if($_SESSION['access']!="Quality" || $_SESSION['access']!="ALL")
	if($_SESSION['user']=="123")
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
		<h4 style="text-align:center"><label>REJECTION REPORT</label></h4>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tableToExcel('testTable', 'REJECTION')" value="Export to Excel">
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
							self.location='o25tab.php?f='+s0+'&tt='+s1+'&pn='+s2;
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
							self.location='o25tab.php?f='+s0+'&tt='+s1+'&pn='+s2;
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
							self.location='o25tab.php?f='+s0+'&tt='+s1+'&pn='+s2;
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
										<th>DATE</th>
										<th>RCNO</th>
										<th>RAW MATERIAL</th>
										<th>PART NUMBER</th>
										<th> RC QTY </th>
										<th>SECTION</th>
										<th>WORK CENTRE</th>
										<th>REJ QUANTITY</th>
										<th>BOM (grams)</th>
										<th>WEIGHT (Kgs) </th>
										<th>PERCENT OF REJ </th>
										<th>RATE/per</th>
										<th>COST</th>
										<th>REASON</th>
										<th>FOREMAN</th>
									  </tr>';
				if(isset($_GET['pn']) && $_GET['pn']!="")
				{
					$p=$_GET['pn'];
					$query = "SELECT *,(weight/IF(rcno LIKE 'A20%',issqty,(issqty*bom)/1000))*100 AS percent FROM (SELECT date,operation,workcentre,d12.pnum,rmdesc,prcno,qtyrejected,rsn,useage*1000 AS bom,useage*qtyrejected AS weight,foreman FROM `d12` LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE qtyrejected!='' AND date<='$tt' AND date>='$f') AS T1 LEFT JOIN (SELECT DISTINCT rcno,IF(rcno LIKE 'A20%',rmissqty,partissued) AS issqty FROM d12 WHERE rcno LIKE '_20%') AS T3 ON T1.prcno=T3.rcno LEFT JOIN (SELECT DISTINCT pnum,invpnum,foreman,rate,per FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T GROUP BY pnum) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum)  WHERE T1.pnum='$p' GROUP BY date,prcno,qtyrejected order by date";
					//echo "SELECT *,(weight/IF(rcno LIKE 'A20%',issqty,(issqty*bom)/1000))*100 AS percent FROM (SELECT date,operation,d12.pnum,rmdesc,prcno,qtyrejected,rsn,useage*1000 AS bom,useage*qtyrejected AS weight,foreman FROM `d12` LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE qtyrejected!='' AND date<='2018-12-14' AND date>='2018-12-13') AS T1 LEFT JOIN (SELECT DISTINCT rcno,IF(rcno LIKE 'A20%',rmissqty,partissued) AS issqty FROM d12 WHERE rcno LIKE '_20%') AS T3 ON T1.prcno=T3.rcno LEFT JOIN (SELECT DISTINCT pnum,invpnum,foreman,rate,per FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T GROUP BY pnum) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum)  WHERE T1.pnum='$p'";
				}
				else
				{
					$query = "SELECT date,rmdesc,prcno,TB1.pnum,issqty,operation,workcentre,qtyrejected,bom,weight,percent,rate,per,(issqty*rate)/per,rsn,foreman FROM (SELECT *,(weight/IF(rcno LIKE 'A20%',issqty,(issqty*bom)/1000))*100 AS percent FROM (SELECT date,operation,workcentre,d12.pnum,prcno,qtyrejected,rsn,useage*1000 AS bom,useage*qtyrejected AS weight,foreman FROM `d12` LEFT JOIN (SELECT DISTINCT pnum,rmdesc,useage,foreman FROM m13) AS TT ON d12.pnum=TT.pnum WHERE qtyrejected!='' AND date<='$tt' AND date>='$f') AS T1 LEFT JOIN (SELECT rcno,IF(rcno LIKE 'A20%',rmissqty,partissued) AS issqty FROM d12 WHERE rcno LIKE '_20%') AS T3 ON T1.prcno=T3.rcno) AS TB1 LEFT JOIN (SELECT DISTINCT T.pnum,invmaster.pn,useage,rmdesc,su,ROUND((useage*100)/su,2),rate,per FROM `pn_st` LEFT JOIN (SELECT DISTINCT pnum,useage,rmdesc FROM m13) AS T ON (T.pnum=pn_st.invpnum || T.pnum=pn_st.pnum) LEFT JOIN (SELECT invpnum,SUM(useage) AS su FROM `pn_st` LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS T ON (T.pnum=pn_st.invpnum || T.pnum=pn_st.pnum) GROUP BY invpnum) AS B ON B.invpnum=pn_st.invpnum LEFT JOIN invmaster ON B.invpnum=invmaster.pn WHERE (stkpt LIKE 'FG%' || stkpt LIKE 'TO%') AND T.pnum!='' GROUP BY T.pnum) AS TB2 ON TB1.pnum=TB2.pnum GROUP BY date,prcno,qtyrejected order by TB1.date";
					//$query = "SELECT *,(weight/IF(rcno LIKE 'A20%',issqty,(issqty*bom)/1000))*100 AS percent FROM (SELECT date,operation,d12.pnum,rmdesc,prcno,qtyrejected,rsn,useage*1000 AS bom,useage*qtyrejected AS weight,foreman FROM `d12` LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE qtyrejected!='' AND date<='$tt' AND date>='$f') AS T1 LEFT JOIN (SELECT DISTINCT rcno,IF(rcno LIKE 'A20%',rmissqty,partissued) AS issqty FROM d12 WHERE rcno LIKE '_20%') AS T3 ON T1.prcno=T3.rcno LEFT JOIN (SELECT DISTINCT pnum,invpnum,foreman,rate,per FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T GROUP BY pnum) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) GROUP BY date,prcno,qtyrejected order by date";
					//echo "SELECT *,(weight/IF(rcno LIKE 'A20%',issqty,(issqty*bom)/1000))*100 AS percent FROM (SELECT date,operation,d12.pnum,rmdesc,prcno,qtyrejected,rsn,useage*1000 AS bom,useage*qtyrejected AS weight,foreman FROM `d12` LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE qtyrejected!='' AND date<='$tt' AND date>='$f') AS T1 LEFT JOIN (SELECT DISTINCT rcno,IF(rcno LIKE 'A20%',rmissqty,partissued) AS issqty FROM d12 WHERE rcno LIKE '_20%') AS T3 ON T1.prcno=T3.rcno LEFT JOIN (SELECT DISTINCT pnum,invpnum,foreman,rate,per FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T GROUP BY pnum) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) GROUP BY date,prcno,qtyrejected order by date";
				}
				$result = $conn->query($query);
				$q=0;$sg=0;$cig=0;$b=0;$t=0;
				$q1=0;$sg1=0;$cig1=0;$b1=0;$t1=0;
				while($row = $result->fetch_assoc()){
						echo" <tr>
							<td>".$row['date']."</td>
							<td>".$row['prcno']."</td>
							<td>".$row['rmdesc']."</td>
							<td>".$row['pnum']."</td>
							<td>".round($row['issqty'],2)."</td>
							<td>".$row['operation']."</td>
							<td>".$row['workcentre']."</td>
							<td>".$row['qtyrejected']."</td>
							<td>".round($row['bom'],3)."</td>
							<td>".round($row['weight'],2)."</td>
							<td>".round($row['percent'],2)." % </td>
							<td>".$row['rate']." / ".$row['per']."</td>
							<td>".(($row['rate']*$row['qtyrejected'])/$row['per'])."</td>
							<td>".$row['rsn']."</td>
							<td>".$row['foreman']."</td>";
						echo"</tr>";
				}
			?>
		</div>
</body>
</html>
</html>