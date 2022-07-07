<?php
$temp="";
$temprc="";
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="TRACEBILITY REPORT";
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
	<script src = "js\excelreport1.js"></script>
	<link rel="stylesheet" type="text/css" href="design2.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>TRACEBILITY REPORT  [ O15 ]</label></h4>
		<div>
		
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<div style="float:right">
			<input type="button" onclick="tablesToExcel(['testTable', 'testTable1','testTable2','testTable3','testTable4'], ['TRACE','b','c','d','e'], 'myfile.xls')" value="Export to Excel">
		</div>
		<br/></br>
		<div>
		<?php
			$str="";
			$con=mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				die(mysqli_error());
			$inv = $_GET['inv'];
			$servername = "localhost";
			$username = "root";
			$password = "Tamil";
			$conn = new mysqli($servername, $username, $password, "mypcm");
			$queryq = "select pnum,sum(partissued) as pi from d12 where rcno='$inv'";
			$resultq = $conn->query($queryq);
			while($rowq = $resultq->fetch_assoc())
			{
				$part = $rowq['pnum'];
				$qnty = $rowq['pi'];
			}
			
			$rcno=$inv;
			$s="";
			track($rcno);
			function track($rcno)
			{
				$con=mysqli_connect('localhost','root','Tamil','mypcm');
				$ret = $con->query("select stkpt,prcno,d12.pnum,d12.rcno,partissued,rmissqty,closedate,d11.date from d12 join d11 on d11.rcno=d12.rcno where d12.rcno='$rcno' and (partissued!='' or rmissqty!='')");
				$c= $ret->num_rows;
				$row2 = mysqli_fetch_array($ret);
				do{
					if($c==0)
					{			
						break;
					}
					if(substr($row2['rcno'],0,1)=="A")
					{
						$q=$row2['rmissqty'];
						$u="Kgs";
					}
					else
					{
						$q=$row2['partissued'];
						$u="Nos";
					}
					$temp=$row2['rcno'];
					$ret1 = $con->query("select date,operation,SUM(qtyrejected) as qr FROM d12 where prcno='$temp' AND operation!=''");//QUALITY QUERY
					$c1= $ret1->num_rows;
					if($c1==0)
					{
						$d="";
						$o="";
						$rq="";
					}
					else
					{
						$row3 = mysqli_fetch_array($ret1);
						$d=$row3['date'];
						$o=$row3['operation'];
						$rq=$row3['qr'];
					}
					if($row2['stkpt']!="To S/C" && $row2['stkpt']!="From S/C")
					{
						
					}
						if(substr($row2['rcno'],0,1)=="A")
						{
							$query4 = "select DISTINCT inv from d12 where rcno='$temp'";
							$result4 = $con->query($query4);
							$row4 = mysqli_fetch_array($result4);
							$temp=$row2['rcno'];
							$tempgrn=$row4['inv'];
							if (strpos($GLOBALS['temprc'], $tempgrn ) !== false) {
								
							}
							else
							{
								$GLOBALS['temprc']=$GLOBALS['temprc'].','.$tempgrn;
							}
						}
						track($row2['prcno']);
				}while($row2 = $ret->fetch_assoc());
			}
			echo $temprc;
	?>
		</div>
		<br>
		<br>
</body>
</html>



