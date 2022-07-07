<?php
$rm="";
$pnum="";
$bom="";
$na="";
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
	//if($_SESSION['access']=="Stores" ||  $_SESSION['access']=="ALL")
	if($_SESSION['user']=="123")
	{
		$id=$_SESSION['user'];
		$activity="RECEIVING ENTRY";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		$con1=mysqli_connect('localhost','root','Tamil','storedb');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
	}
	else
	{
		header("location: inputlink.php");
		//header("location: logout.php");
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
	<link rel="stylesheet" type="text/css" href="design.css">

</head>
<body>
<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label>GRN RECONCILIATION</label></h4>
	<div class="divclass">
	<script>
	function preventback()
	{
		window.history.forward();
	}
	setTimeout("preventback()",0);
	window.onunload = function(){ null };
</script>

	<script>
		function reload(form)
		{
			var p4 = document.getElementById("p").value;
			self.location=<?php echo"'grn_reco.php?grnnum='"?>+p4;
		}
	</script>
	
		<form method="POST" action='grn_reco_db.php'>
			
			<div class="find">
				<label> &nbsp;GRN NUMBER </label>
				<input type="text" style="width:50%;border:2px,red;" class='s' onchange=reload(this.form) id="p" name="rc" list="grnlist" value="<?php if(isset($_GET['grnnum'])){echo $_GET['grnnum'];}?>">
			</div>
			<?php
			if(isset($_GET['grnnum']) && $_GET['grnnum']!="")
			{
				echo '<div class="find1">';
				$grnnum=$_GET['grnnum'];
				$result1 = $con1->query("select receipt.grnnum,sname,rmdesc,quantity_received as inward,inspdb.quantityaccepted as ok,inspdb.quantityrejected as rej,reason,sent_thr_dc from receipt LEFT JOIN inspdb ON receipt.grnnum=inspdb.grnnum where receipt.grnnum='$grnnum'");
				$row = mysqli_fetch_array($result1);
				if($row['sname']!="")
				{
					echo"<br><br>";
					echo "<label>SUPPLIER NAME : $row[sname]</label></div>";
				}
				echo '<div class="find2"><label>RAW MATERIAL  : '.$row['rmdesc'].'</label></div>';
				echo '<div class="find"><label>TOTAL QUANTITY INWARDED  : '.$row['inward'].'</label></div>';
				echo '<div class="find1"><label>TOTAL QUANTITY RECEIVED  : '.$row['ok'].'</label></div>';
				echo '<div class="find2"><label>TOTAL QUANTITY REJECTED  : '.$row['rej'].'</label></div>';
				echo '<div class="find"><label>TOTAL QUANTITY SEND THROUGH DC  : '.$row['sent_thr_dc'].'</label></div>';
				
				/*
				$r1=$row1['inwarded'];
				$rec=$row1['accepted'];
				$rej=$row1['rejected'];
					
				$sent_thr_dc = $row1['sent_thr_dc'];
				//$storestock=$row1['accepted']-$row1['used'];
				$storestock=$row1['accepted']-$row1['used']-$row1['sent_thr_dc'];
				*/
				
				
				$query2 = "SELECT * FROM (SELECT receipt.grnnum,receipt.date,part_number,receipt.rmdesc,sname,quantity_received as inwarded,sent_thr_dc,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,IF(T.qty IS NULL,0,T.qty) as used FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' GROUP BY inv) AS T ON receipt.grnnum=T.inv  GROUP BY grnnum) AS T WHERE T.grnnum='$grnnum'";
				$result2 = $con1->query($query2);
				$row2 = mysqli_fetch_array($result2);
					
				$storestock=$row2['accepted']-$row2['used']-$row2['sent_thr_dc'];
					
				echo '<div class="find"><label>MATERIAL USED SO FAR : '.round($row2['used'],2).' </label></div>';	
				echo '<div class="find"><label>MATERIAL AVAILABLE IN STORES : '.$storestock.' </label></div>';
				
				
			}
			?>
			
			<br><br><br>
			
			<div>
				<label> QUANTITY IN + OR - </label>
				<input type="text" id="qty" name="qty" >
			</div>
			
			<br><br><br>
			
			<div>
				<input type="Submit" onclick="this.style.display = 'none';" id="submit" name="submit" value="SUBMIT"/>
			</div>
		</form>
	</div>
</body>
</html>