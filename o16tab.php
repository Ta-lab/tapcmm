<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="SUSPECT LOT REPORT";
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
		<h4 style="text-align:center"><label>SUSPECT REPORT  [ O16 ]</label></h4>
	<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
	</div>
	<div style="float:right">
		<input type="button" onclick="tableToExcel('testTable', 'STOCK REPORT')" value="Export to Excel">
	</div>
	<br><br>
	<div class="divclass">
		<form method="GET">			
			<div>
				<label>OPTION</label>
					<select name ="rat" id="data">
						<?php
							$f=0;
							if(isset($_GET["rat"]))
							{
								if($_GET["rat"]=="rcno")
								{
									echo "<option selectedIndex value='rcno'>Route Card Number</option>"; 
									echo "<option value='inv'>GRN number</option>"; 
									echo "<option value='heat'>HEAT number</option>"; 
									echo "<option value='lot'>LOT number</option>"; 
								}
								if($_GET["rat"]=="heat")
								{
									echo "<option selectedIndex  value='heat'>HEAT number</option>"; 
									echo "<option value='rcno'>Route Card Number</option>"; 
									echo "<option value='inv'>GRN number</option>"; 
									echo "<option value='lot'>LOT number</option>"; 
								}
								if($_GET["rat"]=="inv")
								{
									echo "<option selectedIndex  value='inv'>GRN number</option>"; 
									echo "<option value='rcno'>Route Card Number</option>"; 
									echo "<option value='heat'>HEAT number</option>"; 
									echo "<option value='lot'>LOT number</option>"; 
								}
								if($_GET["rat"]=="lot")
								{
									echo "<option value='heat'>HEAT number</option>"; 
									echo "<option value='rcno'>Route Card Number</option>"; 
									echo "<option value='inv'>GRN number</option>"; 
									echo "<option value='heat'>HEAT number</option>"; 
								}
							}
							else
							{
								echo "<option value='heat'>HEAT number</option>"; 
								echo "<option value='lot'>LOT number</option>"; 
								echo "<option value='inv'>GRN number</option>"; 
								echo "<option value='rcno'>Route Card Number</option>"; 
							}
								
							echo "</select></h1>";
						?>
						<script>
						function reload1(form)
						{
							var s2=form.rat.options[form.rat.options.selectedIndex].value;
							var p = document.getElementById("val");
							self.location='o16tab.php?rat='+s2+'&val='+p.value;
						}
						</script>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>DATA</label>
				<input type="text" name="val" id="val" value="<?php if(isset($_GET['val']))
					{
						echo $_GET['val'];
					} ?>"/>
				<input type="button" name="submit" value="ENTER" onclick="reload1(this.form)"/>
			</div>
			<div>
				
			</div>
			<br>
		</form>
	</div>
		
<?php
if(isset($_GET['val']))
{
$servername = "localhost";
$username = "root";
$password = "Tamil";
$con = new mysqli($servername, $username, $password, "mypcm");
$h=$_GET['val'];
$o=$_GET['rat'];
$strg1="";
$strg2="";
$query = "SELECT * FROM d12 where $o='$h'";
//echo "SELECT * FROM d12 where $o='$h'";
$result = mysqli_query($con,$query);
function find($result,$strg1,$strg2,$f)
	{
		$strg="";
		$servername = "localhost";
		$username = "root";
		$password = "Tamil";
		$con = new mysqli($servername, $username,$password, "mypcm");
		while($temp1=mysqli_fetch_array($result))
		{
				if($f==0)
				{
					$f=2;
				}
				else
				{
					if($f==1)
					{
						$strg = $strg.'';
						$strg1 = $strg1.' OR prcno=';
						$strg2 = $strg2.' OR d12.rcno=';
						$f=2;
					}
					else
					{
						$strg = $strg.' OR prcno=';
						$strg1 = $strg1.' OR prcno=';
						$strg2 = $strg2.' OR d12.rcno=';
					}
				}
				$strg=$strg."'".$temp1['rcno']."'";
				$strg1=$strg1."'".$temp1['rcno']."'";
				$strg2=$strg2."'".$temp1['rcno']."'";
		}
		//echo "<br>";
		//echo "<br>SELECT * FROM d12 where (prcno=$strg) and rcno!=''";
		$query = "SELECT * FROM d12 where (prcno=$strg) and rcno!=''";
		$result = mysqli_query($con,$query);
		$row_cnt = $result->num_rows;
		if($row_cnt==0)
		{
			echo "<br>";
			$query1="SELECT pnum,rcno,date,sum(partissued) as partissued FROM d12 where (prcno=$strg1) and rcno!='' and rcno not like '%F%' and stkpt='FG For Invoicing' group by rcno,pnum,date";
			//echo "SELECT pnum,rcno,date,sum(partissued) FROM d12 where (prcno=$strg1) and rcno!='' and stkpt='FG For Invoicing'";
			$result1 = mysqli_query($con,$query1);
			echo'<h5 style="text-align:center"><label>INVOICED MATERIAL DETAIL</label></h5>';
			echo'<table id="testTable" align="center">
					  <tr>
						<th>DATE</th>
						<th>INVOICE NUMBER</th>
						<th>PART NUMBER</th>
						<th>QUANTITY</th>
					  </tr>';
			while($temp1=mysqli_fetch_array($result1))
			{
				echo"<tr><td>".$temp1['date']."</td><td>".$temp1['rcno']."</td><td>".$temp1['pnum']."</td><td>".$temp1['partissued']."</td></tr>";				
			}
			echo "</table><br><br>";
			$query= "SELECT pnum,stkpt,prcno,date,sum(partissued),SUM(partreceived),SUM(partreceived)-sum(partissued) as stock FROM d12 where (prcno=$strg1) and stkpt!='FG For Invoicing' and qtyrejected='' GROUP BY prcno";
			//echo "SELECT pnum,stkpt,prcno,date,sum(partissued),SUM(partreceived),SUM(partreceived)-sum(partissued) as stock FROM d12 where (prcno=$strg1) and stkpt!='FG For Invoicing' and qtyrejected='' GROUP BY prcno";
			$result = mysqli_query($con,$query);
			echo'<h5 style="text-align:center"><label>MATERIAL IN STOCKING POINT</label></h5>';
			echo'<table id="testTable" align="center">
					  <tr>
						<th>DATE</th>
						<th>ROUTE CARD NUMBER</th>
						<th>STOCKING POINT</th>
						<th>PART NUMBER</th>
						<th>QUANTITY</th>
					  </tr>';
			while($temp=mysqli_fetch_array($result))
			{
				if($temp['stock']>0)
				{
					echo"<tr><td>".$temp['date']."</td><td>".$temp['prcno']."</td><td>".$temp['stkpt']."</td><td>".$temp['pnum']."</td><td>".$temp['stock']."</td></tr>";
				}
			}
			echo "</table><br><br>";
			$query= "SELECT T1.date,T4.oper,T1.rcno,T1.iss,IF(T2.recvd is NULL,0,T2.recvd),IF(T3.useage is NULL,1,T3.useage),IF(T2.recvd is NULL,0,T2.recvd)*IF(rcno LIKE 'A20%',IF(T3.useage is NULL,1,T3.useage),1) as mul,T1.iss-(IF(T2.recvd is NULL,0,T2.recvd)*IF(rcno LIKE 'A20%',IF(T3.useage is NULL,1,T3.useage),1)) as ans,T1.rmpn FROM (SELECT d12.date,d12.stkpt,d12.pnum,d12.rcno,IF(d12.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as iss,IF(d12.rcno LIKE 'A20%',d12.rm,d12.pnum) as rmpn FROM d12 JOIN d11 ON d12.rcno=d11.rcno where (d12.rcno=$strg2) and stkpt!='FG For Invoicing' and closedate='0000-00-00' GROUP BY rcno) as T1 LEFT JOIN (SELECT date,stkpt,pnum,prcno,(SUM(partreceived)+sum(qtyrejected)) as recvd FROM d12 WHERE (prcno=$strg1) AND stkpt!=''  GROUP BY prcno) AS T2 ON T1.rcno=T2.prcno LEFT JOIN (SELECT distinct pnum,useage FROM m13) AS T3 ON (T2.pnum=T3.pnum) left join (select stkpt,oper from m14) AS T4 on (T4.stkpt=T1.stkpt) order by T1.stkpt";
			//echo"SELECT T1.date,T4.oper,T1.rcno,T1.iss,IF(T2.recvd is NULL,0,T2.recvd),IF(T3.useage is NULL,1,T3.useage),IF(T2.recvd is NULL,0,T2.recvd)*IF(rcno LIKE 'A20%',IF(T3.useage is NULL,1,T3.useage),1) as mul,T1.iss-(IF(T2.recvd is NULL,0,T2.recvd)*IF(rcno LIKE 'A20%',IF(T3.useage is NULL,1,T3.useage),1)) as ans,T1.rmpn FROM (SELECT d12.date,d12.stkpt,d12.pnum,d12.rcno,IF(d12.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as iss,IF(d12.rcno LIKE 'A20%',d12.rm,d12.pnum) as rmpn FROM d12 JOIN d11 ON d12.rcno=d11.rcno where (d12.rcno=$strg2) and stkpt!='FG For Invoicing' GROUP BY rcno) as T1 LEFT JOIN (SELECT date,stkpt,pnum,prcno,(SUM(partreceived)+sum(qtyrejected)) as recvd FROM d12 WHERE (prcno=$strg1) AND stkpt!=''  GROUP BY prcno) AS T2 ON T1.rcno=T2.prcno LEFT JOIN (SELECT pnum,useage FROM m13) AS T3 ON (T2.pnum=T3.pnum) left join (select stkpt,oper from m14) AS T4 on (T4.stkpt=T1.stkpt) order by T1.stkpt";
			$result = mysqli_query($con,$query);
			if (!$result) {
				die('' . mysql_error());
			}
			echo'<h5 style="text-align:center"><label>MATERIAL IN OPERATION</label></h5>';
			echo'<table id="testTable" align="center">
					  <tr>
						<th>DATE</th>
						<th>ROUTE CARD / DC NUMBER</th>
						<th>OPERATION</th>
						<th>PART NUMBER/RM DESC</th>
						<th>QUANTITY</th>
					  </tr>';
			while($temp=mysqli_fetch_array($result))
			{
				if($temp['ans']>0)
				{
					echo"<tr><td>".$temp['date']."</td><td>".$temp['rcno']."</td><td>".$temp['oper']."</td><td>".$temp['rmpn']."</td><td>".round($temp['ans'],2)."</td></tr>";
				}
			}
			echo "</table><br><br>";
		}
		else
		{
			find($result,$strg1,$strg2,1);
		}
	}
	if($result->num_rows>0)
	{
		find($result,$strg1,$strg2,0);
	}
	else
	{
		echo '<script language="javascript">';
		echo 'alert("Entries Not Found")';
		echo '</script>';
	}
}
?>