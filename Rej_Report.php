<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="Quality" || $_SESSION['access']!="ALL")
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
							self.location='Rej_Report.php?f='+s0+'&tt='+s1;
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
							self.location='Rej_Report.php?f='+s0+'&tt='+s1;
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
							<th>FI INV PNUM</th>
							
							<th>RMISSQTY</th>
							<th>PARTISSUED</th>
							<th>ISSUED</th>
							
							<th>SECTION</th>
							<th>WORK CENTRE</th>
							<th>REJ QUANTITY</th>
							
							<th>BOM(grams)</th>
							<th>WEIGHT(kgs)</th>
							<th>REJ PERCENTAGE % </th>
							
							<th>RATE</th>
							<th>PER</th>
							<th>RATE/PER</th>
							<th>VALUE</th>
							
							
							<th>REASON</th>
							<th>FOREMAN</th>
							
							
						  </tr>';
				if(isset($_GET['pn']) && $_GET['pn']!="")
				{
					//$p=$_GET['pn'];
					//$query = "SELECT *,(weight/IF(rcno LIKE 'A20%',issqty,(issqty*bom)/1000))*100 AS percent FROM (SELECT date,operation,workcentre,d12.pnum,rmdesc,prcno,qtyrejected,rsn,useage*1000 AS bom,useage*qtyrejected AS weight,foreman FROM `d12` LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE qtyrejected!='' AND date<='$tt' AND date>='$f') AS T1 LEFT JOIN (SELECT DISTINCT rcno,IF(rcno LIKE 'A20%',rmissqty,partissued) AS issqty FROM d12 WHERE rcno LIKE '_20%') AS T3 ON T1.prcno=T3.rcno LEFT JOIN (SELECT DISTINCT pnum,invpnum,foreman,rate,per FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T GROUP BY pnum) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum)  WHERE T1.pnum='$p' GROUP BY date,prcno,qtyrejected order by date";
				
				}
				else
				{
					$query = "SELECT date,operation,workcentre,REJ.pnum,prcno,qtyrejected,rsn,IF(useage IS NULL,tot,useage) AS bom,IF(rmdesc IS NULL,rmdesc1,rmdesc) AS firmdesc,IF(foreman IS NULL,pnstforeman,foreman) AS fiforeman,IF(invrate IS NULL,rate,invrate) AS firate,IF(invper IS NULL,per,invper) AS fiper FROM (SELECT date,operation,workcentre,pnum,prcno,qtyrejected,rsn FROM `d12` WHERE date>='$f' AND date<='$tt' AND qtyrejected!='') AS REJ

LEFT JOIN(SELECT pnum,rmdesc,useage,foreman FROM `m13`) AS TM13 ON REJ.pnum=TM13.pnum 

LEFT JOIN(SELECT pnstpnum,invpnum,SUM(useage) AS tot,pnstforeman,rmdesc1 FROM (SELECT DISTINCT pn_st.pnum AS pnstpnum,invpnum,useage,foreman AS pnstforeman,rmdesc AS rmdesc1 FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS ipbompnst ON REJ.pnum=ipbompnst.invpnum

LEFT JOIN(SELECT pn,rate AS invrate,per AS invper FROM invmaster) AS INV ON REJ.pnum=INV.pn

LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (REJ.pnum=T5.pnum || REJ.pnum=T5.invpnum)

GROUP BY qtyrejected,prcno,REJ.pnum,date,rsn ORDER BY date";
				
				}

				
				$result = $conn->query($query);
				while($row = $result->fetch_assoc()){
							
					echo"<tr><td>".$row['date']."</td>";
					echo"<td>".$row['prcno']."</td>";
					echo"<td>".$row['firmdesc']."</td>";
					echo"<td>".$row['pnum']."</td>";
					
					
					$sub_string = substr($row['pnum'], -2);
					
					if($sub_string=="-B"){
						$str = $row['pnum'];
						$str = rtrim($str, $sub_string);
						echo"<td>".$str."</td>";
					}
					else if($sub_string=="-R"){
						$str = $row['pnum'];
						$str = rtrim($str, $sub_string);
						echo"<td>".$str."</td>";
					}
					else if($sub_string=="-S"){
						$str = $row['pnum'];
						$str = rtrim($str, $sub_string);
						echo"<td>".$str."</td>";
					}
					else if($sub_string=="-T"){
						$str = $row['pnum'];
						$str = rtrim($str, $sub_string);
						echo"<td>".$str."</td>";
					}
					else if($sub_string=="-P"){
						$str = $row['pnum'];
						$str = rtrim($str, $sub_string);
						echo"<td>".$str."</td>";
					}
					else if($sub_string=="/p"){
						$str = $row['pnum'];
						$str = rtrim($str, $sub_string);
						echo"<td>".$str."</td>";
					}
					else{
						$str = $row['pnum'];
						echo"<td>".$str."</td>";
					}
					
					
					$rcno=$row['prcno'];
					$query2 = "SELECT rcno,rmissqty,partissued FROM `d12` WHERE rcno='$rcno'";
					$result2 = $conn->query($query2);
					$row2 = mysqli_fetch_array($result2);
					echo"<td>".$row2['rmissqty']."</td>";
					echo"<td>".$row2['partissued']."</td>";
					
					if($row2['rmissqty']=='0.0000')
					{
						echo"<td>".$row2['partissued']."</td>";
						$weight = $row['bom']*$row['qtyrejected'];
						//weight/IF(rcno LIKE 'A20%',issqty,(issqty*bom)/1000))*100
						$rej_percentage = $weight/($row2['partissued']*$row['bom'])*100;
						
					}
					else
					{
						echo"<td>".$row2['rmissqty']."</td>";
						$weight = $row['bom']*$row['qtyrejected'];
						$rej_percentage = $weight/$row2['rmissqty']*100;
					}
					
					
					
					echo"<td>".$row['operation']."</td>";
					echo"<td>".$row['workcentre']."</td>";
					echo"<td>".$row['qtyrejected']."</td>";
					
					//$bom=$row['bom']*1000;
					//BOM
					echo"<td>".$row['bom']."</td>";
					echo"<td>".$row['bom']*$row['qtyrejected']."</td>";
					
					//REJECTION PERCENTAGE
					echo"<td>".round($rej_percentage,2)."</td>";
					
					//RATE
					if($row['firate']=="") 
					{
						$query1 = "SELECT pn,rate,per FROM `invmaster` WHERE pn='$str'";
						$result1 = $conn->query($query1);
						$row1 = mysqli_fetch_array($result1);
						echo"<td>".$row1['rate']."</td>";
						echo"<td>".$row1['per']."</td>";
						echo"<td>".$row1['rate']/$row1['per']."</td>";
						echo"<td>".$row['qtyrejected']*$row1['rate']/$row1['per']."</td>";
					}
					else
					{
						echo"<td>".$row['firate']."</td>";
						echo"<td>".$row['fiper']."</td>";
						echo"<td>".$row['firate']/$row['fiper']."</td>";
						echo"<td>".$row['qtyrejected']*$row['firate']/$row['fiper']."</td>";
					}
					
					
					
					
					
					
					echo"<td>".$row['rsn']."</td>";
					echo"<td>".$row['fiforeman']."</td>";
					
						
					echo"</tr>";
				}
			?>
		</div>
</body>
</html>
</html>