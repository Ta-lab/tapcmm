<?php
$max=1;$r="";
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="DC RAISING WITHOUT INVOICING";
		date_default_timezone_set('Asia/Kolkata');
		$maxime=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$maxime' where userid='$id'");
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
<!DOCTYPE html>-
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
</head>
<body style="background-image: url('img/6.jpg');">

	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	
	<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	
	<h4 style="text-align:center"><label> DC WITHOUT INVOICING </label></h4>
	
	<script>
		function preventback()
		{
			window.history.forward();
		}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
	</script>
	
	<style>
		.column
		{
			float: left;
			width: 33%;
		}
		.column1
		{
			float: left;
			width: 90%;
		}
	</style>

	

	<?php
		$con = mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			echo "connection failed";
		$q = "SELECT * from d19";
		$r = $con->query($q);
		$row=$r->fetch_assoc();
		date_default_timezone_set("Asia/Kolkata");
			if(date("H:i:s")>=$row['dc'] || date("H:i:s")<0)
			{
				header("location: inputlink.php?msg=12");
			}
	?>

	
	<div class="divclass">
		<form action="newinv2dc_db.php" method="post" enctype="multipart/form-data">
			<div id="stylized" class="myform"><br>
				<div class="column">
					<label>&nbsp;DC NUMBER&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input style="width: 60%; background-color:white;" type="text" id="p0" name="dc" readonly value="<?php
							if(isset($_GET[	'dc']))
							{
								echo $_GET['dc'];
							}
							else
							{
								$q1 = "SELECT distinct status from admin1 where status='2' ";
								$r1 = $con->query($q1);
								$row1=$r1->fetch_assoc();
								if($row1['status']=="2")
								{
									header("location: inputlink.php?msg=3");
								}
								else
								{
									$id=$_SESSION['user'];
									mysqli_query($con,"UPDATE admin1 set status='9' where userid='$id'");
								}
								$q = "select dcnum as gen from dc_det ORDER BY dcnum DESC LIMIT 1";
								$r = $con->query($q);
								$fch=$r->fetch_assoc();
								if(substr($fch['gen'],3,2)!=date("y") && date("m")==4)
								{
									$unit="U1D";
									$y=date("y-").(date('y')+1);
									$digit="00001";
									echo $unit.$y.$digit;
								}
								else{
									echo substr($fch['gen'],0,8).str_pad((substr($fch['gen'],8,5)+1),5,"0",STR_PAD_LEFT);
									//echo substr($fch['gen'],3,2).date("y");
								}
							}
						?>"/>	
				</div>

				
				<div class="column">
					<label for="operation">STOCKING POINT</label>
						<?php
							$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
							$result = $con->query("SELECT operation FROM m11 where opertype='stocking point' and operation != 'Stores' and operation='FG For Invoicing'");
							echo "<select name ='operation' id='p1'>";
							while($row = mysqli_fetch_array($result))
							{	
								if($_GET['cat']==$row['operation'])
									echo "<option selected value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
								else
									echo "<option value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
							}
							echo "</select>";
						?>
				</div>
								
				<datalist id="codelist" >
				<?php
						$query = "SELECT distinct ccode FROM invmaster";
						$result = $con->query($query);
						echo"<option value=''>Select one</option>";
						while ($row = mysqli_fetch_array($result)) 
						{
							if($_GET['ccode']==$row['ccode'])
								echo "<option selected value='".$row['ccode']."'>".$row['ccode']."</option>";
							else
								echo "<option value='".$row['ccode']."'>".$row['ccode']."</option>";
						}
				?>
				</datalist>
				
				<div class="column">
						<label>CUSTOMER CODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" class='s' required onchange=reload(this.form) id="p" name="cc" list="codelist" value="<?php if(isset($_GET['ccode'])){
							echo $_GET['ccode'];
							}?>"/>
				</div><br><br>
							
				<datalist id="partlist" >
				<?php
					if(isset($_GET['ccode']))
					{
						$t=$_GET['ccode'];
						$query = "SELECT distinct pn FROM invmaster where ccode='$t'";
						$result = $con->query($query);
						echo"<option value=''>Select one</option>";
						while ($row = mysqli_fetch_array($result)) 
						{
							if($_GET['pn']==$row['pn'])
								echo "<option selected value='".$row['pn']."'>".$row['pn']."</option>";
							else
								echo "<option value='".$row['pn']."'>".$row['pn']."</option>";
						}
					}
				?>
				</datalist>		
				
				<script>
				function reload(form)
				{
					var p0 = document.getElementById("p0").value;
					var p = document.getElementById("p").value;
					var p1 = document.getElementById("p1").value;
					self.location=<?php echo"'newinv2dc.php?dcnum='"?>+p0+'&ccode='+p+'&stockingpoint='+p1;
				}
				</script>
				
				
				<div class="column">
					<label>&nbsp;PART NUMBER&nbsp;&nbsp;</label>
						<input type="text"  class='s' required onchange=reload1(this.form) id="p2" name="pn" list="partlist" 
							value="<?php 
								if(isset($_GET['partnumber']))
								{	
									echo $_GET['partnumber'];
								} 
						?>"/>
				</div>
				
				<script>
				function reload1(form)
				{
					var p0 = document.getElementById("p0").value;
					var p = document.getElementById("p").value;
					var p1 = document.getElementById("p1").value;
					var p2 = document.getElementById("p2").value;
					self.location=<?php echo"'newinv2dc.php?dcnum='"?>+p0+'&ccode='+p+'&stockingpoint='+p1+'&partnumber='+p2;
				}
				</script>
				
				<div class="column">
						<label>AVAILABLE QTY&nbsp;</label>
						<input type="text" required readonly='readonly' id="p7" name="avlqty" min="1"  value="<?php 
							$t="";$dir=0;$dir1=0;
								if(isset($_GET['partnumber']) && $_GET['partnumber']!="" && ($dir==0 || isset($_GET['pn']) && $_GET['pn']!="") && ($dir1==0 || isset($_GET['sc']) && $_GET['sc']!="")){
								$rat = $_GET['partnumber'];
								if(isset($_GET['pn']) && $_GET['pn']!="")
								{
									$pnum = $_GET['pn'];
									$result = $con->query("SELECT pnum FROM `pn_st` WHERE invpnum='$rat' and stkpt='FG For Invoicing' and pnum='$pnum'");
									$count = 1;
								}
								else
								{
									$result = $con->query("SELECT pnum FROM `pn_st` WHERE invpnum='$rat' and stkpt='FG For Invoicing'");
									$count = $result->num_rows;
								}
								if($count==0){
									/*do{
										$pnum=$rat;
										$result2 = $con->query("SELECT SUM(s) as stock FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' GROUP BY prcno HAVING (sum(partreceived)-sum(partissued))>0) AS T1 WHERE T1.pnum='$pnum' and T1.stkpt='FG For Invoicing'");
										$row = mysqli_fetch_array($result2);
										if($t==0 || $t>$row['stock'])
										{
											$t=$row['stock'];
										}
										}while($row = mysqli_fetch_array($result));
										echo $t;
										*/
										$t="NON-Traceability Part";
										//$t="0";
										echo $t;
										//header("location: inputlink.php?msg=12");
								}
								else
								{
									$row = mysqli_fetch_array($result);
									$pnum=$row['pnum'];
									$result1 = $con->query("SELECT * FROM m12 WHERE pnum='$pnum' and operation='FG For S/C'");
									$c = $result1->num_rows;
									if($c==0)
									{
										do{
										$pnum=$row['pnum'];
										$result2 = $con->query("select SUM(stock) as stock FROM (SELECT SUM(partreceived)-SUM(partissued) as stock FROM d12 WHERE prcno IN (SELECT prcno FROM `d12` WHERE pnum='$pnum' AND prcno!='' AND stkpt='FG For Invoicing' GROUP BY prcno HAVING SUM(partreceived)-SUM(partissued)>0) GROUP BY prcno HAVING SUM(partreceived)-SUM(partissued)>0) AS T");
										$row = mysqli_fetch_array($result2);
										if($t==0 || $t>$row['stock'])
										{
											$t=$row['stock'];
										}
										}while($row = mysqli_fetch_array($result));
										echo $t;
									}
									/*else
									{echo "hi";
									
										$rrat="%%";
										$rat=$_GET['partnumber'];
										if(isset($_GET['pn']) && $_GET['pn']!="")
										{
											$rrat = $_GET['sc'];
										}
										if(isset($_GET['sc']) && $_GET['sc']!="")
										{
											$rrat = $_GET['sc'];
										}
										$query = "SELECT SUM(rem) as stock FROM (SELECT DISTINCT T2.rcno,T2.pnum,T2.date,T2.issqty-IF(T1.received IS NULL,'0',T1.received) as rem,datediff(NOW(),T2.date) as days,dc_det.scn FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,sum(d12.partissued) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation='FG For S/C' AND d11.pnum='$rat' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN dc_det ON T2.rcno=CONCAT('DC-',dc_det.dcnum) WHERE scn LIKE '$rrat'  GROUP BY T2.rcno order by t2.date,T2.rcno ASC) AS T";
										//echo "SELECT SUM(rem) as stock FROM (SELECT DISTINCT T2.rcno,T2.pnum,T2.date,T2.issqty-IF(T1.received IS NULL,'0',T1.received) as rem,datediff(NOW(),T2.date) as days,dc_det.scn FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,sum(d12.partissued) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation='FG For S/C' AND d11.pnum='$rat' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN dc_det ON T2.rcno=CONCAT('DC-',dc_det.dcnum) WHERE scn LIKE '$rrat'  GROUP BY T2.rcno order by t2.date,T2.rcno ASC) AS T";
										$result2 = $con->query($query);
										$row2 = mysqli_fetch_array($result2);
										$t=round($row2['stock']);
										echo $t;
									}*/
								}
								if($t=="" && $t!=0)
								{
									echo "0";
								}
							}
						?>">
					</div>
					
				
				<div class="column">
					<label>DC QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="number" required <?php if($t==""){echo "readonly";} ?> id="p5" min="<?php if($t==""){echo 0;}else{echo 1;} ?>" max="<?php if($t==""){echo 0;}else{echo $t;} ?>" required name="tiqty">
				</div>
				
				<br><br>
				
				<div class="column">
					<label>&nbsp;Dispatch Mode</label>
					<input type="text" style="width: 60%; background-color:white;" id="p4" name="mot" value="">
				</div>;
				
				
				<div class="column1">
					<input type="submit" name="submit" value="CREATE DC">
				</div>
				
				
	
	
	
	
			</div>	
		</form>
	</div>
</body>

</html>