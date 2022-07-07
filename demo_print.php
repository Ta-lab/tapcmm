<?php
session_start();
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	header("Location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	if($_SESSION['user']=="105" || $_SESSION['user']=="123")
	{
		$id=$_SESSION['user'];
		$activity="PACKING PRINTING";
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
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<link rel="stylesheet" type="text/css" href="design.css">
	<link rel="stylesheet" type="text/css" href="print.css">
</head>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<body>
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div align="right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>PACKINFO PRINT</label></h4>
		<div class="divclass">
	<form action="inventory_packslip.php"  method="post">		
	<br>
	<?php
		$ccode="";$fin="";$pnum="";$rcno="";
		$con = mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		$result = $con->query("SELECT invno from inv_det order by invno");
		echo"<datalist id='combo-options'>";
		while($row = mysqli_fetch_array($result))
		{
			echo "<option value='" . $row['invno'] . "'>" . $row['invno'] ."</option>";
		}
		echo"</datalist>";
		if(isset($_GET['invno']) && $_GET['invno']!="")
		{
			$inv=$_GET['invno'];
			$result = $con->query("SELECT DISTINCT pn,ccode,qty from inv_det where invno='$inv'");
			//echo "SELECT DISTINCT pn,ccode from inv_det where invno='$inv'";
			$row = mysqli_fetch_array($result);
			$pnum=$row['pn'];
			$ccode=$row['ccode'];
			$qty=$row['qty'];
			
			
			$temprc="";
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
							$query4 = "select DISTINCT heat from d12 where rcno='$temp'";
							$result4 = $con->query($query4);
							$row4 = mysqli_fetch_array($result4);
							$tempgrn=$row4['heat'];
							$temp=$row2['rcno'];
							if (strpos($GLOBALS['temprc'], $tempgrn ) !== false) {
								
							}
							else
							{
								if($GLOBALS['temprc']=="")
								{
									$GLOBALS['temprc']=$tempgrn;
								}
								else
								{
									$GLOBALS['temprc']=$GLOBALS['temprc'].','.$tempgrn;
								}
							}
						}
						track($row2['prcno']);
				}while($row2 = $ret->fetch_assoc());
			}
			track($inv);
			$batch=$temprc;
		}
	?>
	<script>
			function reload(form)
			{
				var p4 = document.getElementById("from").value;
				self.location=<?php echo"'pack_slip_print.php?invno='"?>+p4;
			}
	</script>
	<div>
		<label>INVOICE NUMBER</label>
		<input type="text" required name="from" id="from" onchange=reload(this.form) list="combo-options" value="<?php if(isset($_GET['invno'])){echo $_GET['invno'];} ?>"/>
	</div>
	<br>
	<div>
		<label>PART NUMBER</label>
		<input type="text" required name="pn" id="pn" readonly value="<?php if(isset($_GET['invno'])){echo $pnum;} ?>"/>
	</div>
	<br>
	<div>
		<label>CUSTOMER CODE</label>
		<input type="text" required readonly name="cc" value="<?php if(isset($_GET['invno'])){echo $ccode;} ?>"/>
	</div>
	<br>
	<div>
		<label>BATCH CODE</label>
		<input type="text" required readonly name="bat" value="<?php if(isset($_GET['invno'])){echo $batch;} ?>"/>
	</div>
	<br>
	<div>
		<label>ISSUE NUMBER</label>
		<input type="text" required readonly name="in" value="<?php if(isset($_GET['invno'])){echo $qty;} ?>"/>
	</div>
	<br>
	<div>
		<label>QUANTITY</label>
		<input type="text" required readonly name="qty" value="<?php if(isset($_GET['invno'])){echo $qty;} ?>"/>
	</div>
	<br>
	<div>
		<label>QUANTITY</label>
		<input type="text" required name="qty" value="<?php if(isset($_GET['invno'])){echo "";} ?>"/>
	</div>
	<br>
	<div>
		<label>No.Of.Box</label>
		<input type="text" required name="box" value="<?php if(isset($_GET['invno'])){echo "";}else{echo "";} ?>"/>
	</div>
	<br>
	<br>
		<input type="Submit" name="submit" value="PRINT"/>
	
</div>
</body>
</html>