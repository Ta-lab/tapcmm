<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="Accounts" || $_SESSION['access']!="ALL")
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
							self.location='o26tab.php?f='+s0+'&tt='+s1+'&pn='+s2;
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
							self.location='o26tab.php?f='+s0+'&tt='+s1+'&pn='+s2;
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
							self.location='o26tab.php?f='+s0+'&tt='+s1+'&pn='+s2;
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
										<th>INVOICE NO</th>
										<th>CUST_CODE</th>
										<th>PART NUMBER</th>
										<th>PART DESCRIPTION</th>
										<th>RATE/per</th>
										<th>BOM</th>
										<th>QUANTITY</th>
										<th>WEIGHT (Kgs)</th>
									  </tr>';
				if(isset($_GET['pn']) && $_GET['pn']!="")
				{
					$p=$_GET['pn'];
					$query = "SELECT * FROM (SELECT date,operation,d12.pnum,rmdesc,prcno,qtyrejected,rsn,useage*1000 AS bom,useage*qtyrejected AS weight FROM `d12` LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE qtyrejected!='' AND date<='$tt' AND date>='$f') AS T1 LEFT JOIN (SELECT DISTINCT pnum,invpnum,foreman,rate,per FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T GROUP BY pnum) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum)  WHERE T1.pnum='$p' order by date";
					//  echo "SELECT * FROM (SELECT date,operation,d12.pnum,rmdesc,prcno,qtyrejected,rsn,useage*1000 AS bom,useage*qtyrejected AS weight FROM `d12` LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE qtyrejected!='' AND date<='$tt' AND date>='$f') AS T1 LEFT JOIN (SELECT DISTINCT pnum,invpnum,foreman,rate,per FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T GROUP BY pnum) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum)  WHERE T1.pnum='$p'";
				}
				else
				{
					$query = "SELECT DISTINCT invno,sup,invdt,ccode,pn,pd,rate,per,qty,SUM(bom) AS bom,SUM(kgs) FROM (SELECT DISTINCT invno,sup,invdt,ccode,pn,pd,rate,per,qty,(useage*1000) AS bom,(useage*qty) AS Kgs FROM `inv_det` LEFT JOIN pn_st ON inv_det.pn=pn_st.invpnum LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE sup='0' AND (invdt LIKE '%04-2018' OR invdt LIKE '%05-2018') ORDER BY invno) AS T GROUP BY invno";
				}
				$result = $conn->query($query);
				$q=0;$sg=0;$cig=0;$b=0;$t=0;
				$q1=0;$sg1=0;$cig1=0;$b1=0;$t1=0;
				while($row = $result->fetch_assoc()){
						echo" <tr>
							<td>".$row['invdt']."</td>
							<td>".$row['invno']."</td>
							<td>".$row['ccode']."</td>
							<td>".$row['pn']."</td>
							<td>".$row['pd']."</td>
							<td>".$row['rate']." / ".$row['per']."</td>
							<td>".$row['bom']."</td>
							<td>".$row['qty']."</td>
							<td>".round(($row['bom']*$row['qty'])/1000,2)."</td>";
						echo"</tr>";
				}
			?>
		</div>
</body>
</html>
</html>