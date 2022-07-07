<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="RECONCILE REPORT";
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
		<h4 style="text-align:center"><label>RECONCILATION REPORT</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<br>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'RECONCILE REPORT')" value="Export to Excel">
	</div>
	
		
	
	<br><br>
	
	<div class="divclass">
	
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
				<label>TO DATE</label>
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
			<script>
			function reload(form)
			{
				var s0 = document.getElementById("f").value;
				var s1 = document.getElementById("tt").value;
				var s3 = document.getElementById("status").value;
				self.location='reconcile_report_dum.php?f='+s0+'&tt='+s1+'&status='+s3;
			}
			</script>
		
			
			<div class="find3">
			<label>STATUS</label>
			<select name ="status" id="status" onchange="reload(this.form)">
				<option value="ALL">ALL</option>";	
				<option value="APPROVED">APPROVED</option>";	
				<option value="REJECTED">REJECTED</option>";	
				<option value="PENDING">PENDING</option>";
				
				<?php
				//if(isset($_GET['status']) && $_GET['status']=='APPROVED')
				if(isset($_GET['status']))
					echo "<option selected value='" . $_GET['status'] . "'>" . $_GET['status'] ."</option>";
				?>
				
			</select>
			
				
			</div></form><br>
			
			
			<br><br>
	
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				
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
				
				
				
			echo'<table id="testTable" align="center">
				<tr>
					
					<th>DATE</th>
					<th>ERP VALUE</th>
					<th>ACTUAL VALUE</th>
					<th>DIFFERENCE VALUE</th>
					<th>STATUS</th>
					<th>REASON</th>
					
					
					
					
					<th></th>
				</tr>';
				
				
				//$query = "SELECT *,dif*rp*v/100 AS val FROM(SELECT sp,dt,pn AS rcpn,pr,eq,aq,eq-aq AS dif FROM `d17` WHERE dt>='2021-04-01') AS rc LEFT JOIN(SELECT stkpt,pnum,invpnum FROM pn_st WHERE stkpt LIKE '%FG For Invoicing%') AS pnst ON rc.rcpn=pnst.pnum LEFT JOIN(SELECT DISTINCT pn,rate,per,rate/per AS rp FROM invmaster) AS invm ON rc.rcpn=invm.pn OR invm.pn=pnst.invpnum LEFT JOIN(SELECT stkpt,oper,value AS v FROM m14) AS Tm14 ON rc.sp=Tm14.stkpt OR rc.sp=Tm14.oper GROUP BY pr,eq,aq ORDER BY sp";
				/*$query = "SELECT * FROM(SELECT dt,sp,pr,pn,eq,aq,app_by,app_status FROM `d17` WHERE app_status='F' AND rej_status='' UNION 
						SELECT date,area,genrcno,mc_pn,0 AS eq,qty,app_by,app_status FROM `stockinitialize` WHERE app_status='F' AND rej_status='' ) AS FT 
						LEFT JOIN(SELECT stkpt,pnum,invpnum FROM pn_st WHERE stkpt LIKE '%FG For Invoicing%') AS pnst ON FT.pn=pnst.pnum LEFT JOIN(SELECT DISTINCT pn,rate,per,rate/per AS rp FROM invmaster) AS invm ON FT.pn=invm.pn OR invm.pn=pnst.invpnum
						LEFT JOIN(SELECT * FROM `d11`) AS TD11 ON FT.pr=TD11.rcno
						LEFT JOIN(SELECT stkpt,oper,value AS v FROM m14) AS Tm14 ON FT.sp=Tm14.stkpt OR TD11.operation=Tm14.stkpt GROUP BY pr,eq";
				*/
				
				/*$query = "SELECT * FROM(SELECT dt,sp,pr,pn,eq,aq,app_by,app_status FROM `d17` WHERE app_status='F' AND rej_status='' UNION 
						SELECT date,area,genrcno,mc_pn,0 AS eq,qty,app_by,app_status FROM `stockinitialize` WHERE app_status='F' AND rej_status='' ) AS FT 
						LEFT JOIN(SELECT stkpt,pnum,invpnum FROM pn_st WHERE stkpt LIKE '%FG For Invoicing%') AS pnst ON FT.pn=pnst.pnum LEFT JOIN(SELECT DISTINCT pn,rate,per,rate/per AS rp FROM invmaster) AS invm ON FT.pn=invm.pn OR invm.pn=pnst.invpnum
						LEFT JOIN(SELECT * FROM `reconciledbd11`) AS TD11 ON FT.pr=TD11.rcno
						LEFT JOIN(SELECT * FROM `d11`) AS FTD11 ON FT.pr=FTD11.rcno
						LEFT JOIN(SELECT stkpt,oper,value AS v FROM m14) AS Tm14 ON FT.sp=Tm14.stkpt OR TD11.operation=Tm14.stkpt OR FTD11.operation=Tm14.stkpt GROUP BY pr,eq";
				*/
				if($_GET['status'] == "APPROVED"){
					$query = "SELECT dt,SUM(eqval) AS eval,SUM(aqval) AS aval,SUM(difval) AS dval,app_status,rej_status,rej_reason FROM(SELECT dt,(eq*rp*v/100) AS eqval,(aq*rp*v/100) AS aqval,((aq-eq)*rp*v/100) AS difval,app_status,rej_status,rej_reason FROM(SELECT dt,sp,pr,pn,eq,aq,app_by,app_status,rej_status,rej_reason FROM `d17` WHERE app_status='T' AND dt>='$f' AND dt<='$tt' UNION 
						SELECT date,area,genrcno,mc_pn,0 AS eq,qty,app_by,app_status,rej_status,rej_reason FROM `stockinitialize` WHERE app_status='T' AND date>='$f' AND date<='$tt' ) AS FT 
						LEFT JOIN(SELECT * FROM `d11`) AS FTFD11 ON FT.pr=FTFD11.rcno
						LEFT JOIN(SELECT stkpt,pnum,invpnum FROM pn_st WHERE stkpt LIKE '%FG For Invoicing%') AS pnst ON FT.pn=pnst.pnum LEFT JOIN(SELECT DISTINCT pn,rate,per,rate/per AS rp FROM invmaster) AS invm ON FT.pn=invm.pn OR invm.pn=pnst.invpnum OR FTFD11.pnum=invm.pn
						LEFT JOIN(SELECT * FROM `reconciledbd11`) AS TD11 ON FT.pr=TD11.rcno
						LEFT JOIN(SELECT * FROM `d11`) AS FTD11 ON FT.pr=FTD11.rcno
						LEFT JOIN(SELECT stkpt,oper,value AS v FROM m14) AS Tm14 ON FT.sp=Tm14.stkpt OR TD11.operation=Tm14.stkpt OR FTD11.operation=Tm14.stkpt GROUP BY pr,eq,aq) AS FINAL group BY app_status,rej_status,dt";	
				}
				else if($_GET['status'] == "REJECTED"){
					$query = "SELECT dt,SUM(eqval) AS eval,SUM(aqval) AS aval,SUM(difval) AS dval,app_status,rej_status,rej_reason FROM(SELECT dt,(eq*rp*v/100) AS eqval,(aq*rp*v/100) AS aqval,((aq-eq)*rp*v/100) AS difval,app_status,rej_status,rej_reason FROM(SELECT dt,sp,pr,pn,eq,aq,app_by,app_status,rej_status,rej_reason FROM `d17` WHERE rej_status='T' AND dt>='$f' AND dt<='$tt' UNION 
						SELECT date,area,genrcno,mc_pn,0 AS eq,qty,app_by,app_status,rej_status,rej_reason FROM `stockinitialize` WHERE rej_status='T' AND date>='$f' AND date<='$tt' ) AS FT 
						LEFT JOIN(SELECT * FROM `d11`) AS FTFD11 ON FT.pr=FTFD11.rcno
						LEFT JOIN(SELECT stkpt,pnum,invpnum FROM pn_st WHERE stkpt LIKE '%FG For Invoicing%') AS pnst ON FT.pn=pnst.pnum LEFT JOIN(SELECT DISTINCT pn,rate,per,rate/per AS rp FROM invmaster) AS invm ON FT.pn=invm.pn OR invm.pn=pnst.invpnum OR FTFD11.pnum=invm.pn
						LEFT JOIN(SELECT * FROM `reconciledbd11`) AS TD11 ON FT.pr=TD11.rcno
						LEFT JOIN(SELECT * FROM `d11`) AS FTD11 ON FT.pr=FTD11.rcno
						LEFT JOIN(SELECT stkpt,oper,value AS v FROM m14) AS Tm14 ON FT.sp=Tm14.stkpt OR TD11.operation=Tm14.stkpt OR FTD11.operation=Tm14.stkpt GROUP BY pr,eq,aq) AS FINAL group BY app_status,rej_status,dt";	
				}
				else if($_GET['status'] == "PENDING"){
					$query = "SELECT dt,SUM(eqval) AS eval,SUM(aqval) AS aval,SUM(difval) AS dval,app_status,rej_status,rej_reason FROM(SELECT dt,(eq*rp*v/100) AS eqval,(aq*rp*v/100) AS aqval,((aq-eq)*rp*v/100) AS difval,app_status,rej_status,rej_reason FROM(SELECT dt,sp,pr,pn,eq,aq,app_by,app_status,rej_status,rej_reason FROM `d17` WHERE app_status='F' AND rej_status='' AND dt>='$f' AND dt<='$tt' UNION 
						SELECT date,area,genrcno,mc_pn,0 AS eq,qty,app_by,app_status,rej_status,rej_reason FROM `stockinitialize` WHERE app_status='F' AND rej_status='' AND date>='$f' AND date<='$tt' ) AS FT 
						LEFT JOIN(SELECT * FROM `d11`) AS FTFD11 ON FT.pr=FTFD11.rcno
						LEFT JOIN(SELECT stkpt,pnum,invpnum FROM pn_st WHERE stkpt LIKE '%FG For Invoicing%') AS pnst ON FT.pn=pnst.pnum LEFT JOIN(SELECT DISTINCT pn,rate,per,rate/per AS rp FROM invmaster) AS invm ON FT.pn=invm.pn OR invm.pn=pnst.invpnum OR FTFD11.pnum=invm.pn
						LEFT JOIN(SELECT * FROM `reconciledbd11`) AS TD11 ON FT.pr=TD11.rcno
						LEFT JOIN(SELECT * FROM `d11`) AS FTD11 ON FT.pr=FTD11.rcno
						LEFT JOIN(SELECT stkpt,oper,value AS v FROM m14) AS Tm14 ON FT.sp=Tm14.stkpt OR TD11.operation=Tm14.stkpt OR FTD11.operation=Tm14.stkpt GROUP BY pr,eq,aq) AS FINAL group BY app_status,rej_status,dt";	
				}
				else
				{
					$query = "SELECT dt,SUM(eqval) AS eval,SUM(aqval) AS aval,SUM(difval) AS dval,app_status,rej_status,rej_reason FROM(SELECT dt,(eq*rp*v/100) AS eqval,(aq*rp*v/100) AS aqval,((aq-eq)*rp*v/100) AS difval,app_status,rej_status,rej_reason FROM(SELECT dt,sp,pr,pn,eq,aq,app_by,app_status,rej_status,rej_reason FROM `d17` WHERE dt>='$f' AND dt<='$tt' UNION 
						SELECT date,area,genrcno,mc_pn,0 AS eq,qty,app_by,app_status,rej_status,rej_reason FROM `stockinitialize` WHERE date>='$f' AND date<='$tt' ) AS FT 
						LEFT JOIN(SELECT * FROM `d11`) AS FTFD11 ON FT.pr=FTFD11.rcno
						LEFT JOIN(SELECT stkpt,pnum,invpnum FROM pn_st WHERE stkpt LIKE '%FG For Invoicing%') AS pnst ON FT.pn=pnst.pnum LEFT JOIN(SELECT DISTINCT pn,rate,per,rate/per AS rp FROM invmaster) AS invm ON FT.pn=invm.pn OR invm.pn=pnst.invpnum OR FTFD11.pnum=invm.pn
						LEFT JOIN(SELECT * FROM `reconciledbd11`) AS TD11 ON FT.pr=TD11.rcno
						LEFT JOIN(SELECT * FROM `d11`) AS FTD11 ON FT.pr=FTD11.rcno
						LEFT JOIN(SELECT stkpt,oper,value AS v FROM m14) AS Tm14 ON FT.sp=Tm14.stkpt OR TD11.operation=Tm14.stkpt OR FTD11.operation=Tm14.stkpt GROUP BY pr,eq,aq) AS FINAL group BY app_status,rej_status,dt";
				}
				$result = $conn->query($query);
				
				$total=0;
				$diftotal =0;
				$eval = 0;
				$aval = 0;
				$dval = 0;
				
				while($row = mysqli_fetch_array($result))
				{
					echo"<tr>";
					echo"<td>".$row['dt']."</td>";
					/*echo"<td>".$row['sp']."</td>";
					//echo"<td>".$row['pn']."</td>";
					//echo"<td>".$row['pr']."</td>";
					echo"<td>".$row['eq']."</td>";
					echo"<td>".$row['aq']."</td>";
					
					$dif = $row['aq'] - $row['eq'];
					echo"<td>".$dif."</td>";
					
					$rp = round($row['rp'],2);
					echo"<td>".$rp."</td>";
					
					echo"<td>".$row['v']."%"."</td>";
					
					$value = $dif*$rp*$row['v']/100;
					echo"<td>".round($value,2)."</td>";
					
					$total = $total+$value;
					*/
					//$eqval = $eqval+$row['eqval'];
					echo"<td>".round($row['eval'],2)."</td>";
					
					//$aqval = $aqval+$row['aqval'];
					echo"<td>".round($row['aval'],2)."</td>";
					
					//$difval = $difval+$row['difval'];
					echo"<td>".round($row['dval'],2,)."</td>";
					
					//echo"<td><a href='reconcile_report_final.php?f=".$row['dt']."&tt=".$row['dt']."'>DOWNLOAD DETAIL REPORT</a></td>";
					
					if($row['app_status']=='F' && $row['rej_status']==''){
						echo"<td>"."PENDING"."</td>";
						echo"<td>"."PENDING"."</td>";
					}else if($row['app_status']=='T'){
						echo"<td>"."APPROVED"."</td>";
						echo"<td>"."APPROVED"."</td>";
					}else if($row['rej_status']=='T'){
						echo"<td>"."REJECTED"."</td>";
						echo"<td>".$row['rej_reason']."</td>";
					}
					
					
					//$eqtotal = $eqtotal+$row['eval'];
					
					//$aqtotal = $aqtotal+$row['aval'];
					
					$diftotal = $diftotal+$row['dval'];
					
					echo"</tr>";
				
				}
				
				
				
				echo"<tr>
					<td colspan='3'><h4 style='color : orange;'> TOTAL </h4></td>
					<td colspan='1'><h4>".round($diftotal,2)."</h4></td>
					<td colspan='2'></td>";
				echo"</tr>";
				
				
				echo "</table>";
				
				echo "<br><br>";
				
				/*echo '<div>
					<input style="float:right;" type="button" onclick="confirmButtonClick()" name="approve" value="APPROVE"><br><br>
					
					<input style="float:right;" type="button" onclick="onButtonClick()" value="REJECT"><br><br>
					
						
				</div>';
				
				echo '<div> 
					<input style="float:right;" class="hide" type="textarea" name="reason" placeholder="REASON FOR REJECT" id="textInput" value="" /><br><br>
					<input class="hide" type="submit" id="rejectbutton" name="reject" value="CONFIRM TO REJECT">
					<input class="hide" type="submit" id="approvebutton" name="approve" value="CONFIRM TO APPROVE">
				</div>';
				*/
			?>
		</div>
		
		
		<script>
			function onButtonClick(){
				document.getElementById('textInput').className="show";
				document.getElementById('rejectbutton').className="show";
			}
			
			function confirmButtonClick(){
				document.getElementById('approvebutton').className="show";
			}
			
			function myFunction() {
				if (confirm("ARE YOU SURE REJECT THE RECONCILATION APPROVAL...!")){
					window.location.href = 'reconcile_approval_db.php';
				}
				
			}
		</script>
		

<br><br>
		
		
		
</body>
</html>

