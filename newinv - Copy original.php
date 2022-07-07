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
	if($_SESSION['access']=="FG For Invoicing")
	{
		$id=$_SESSION['user'];
		$activity="INVOICE PART OR CUST INSERT";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
		
		/*
		$query = "SELECT MAX(days) c FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt='FG For Invoicing'";
		$result = $con->query($query);
		$row1 = mysqli_fetch_array($result);
		if($row1['c']>30)
		{
			//header("location: inputlink.php?msg=30");
		}
		*/
		
		$q = "SELECT * from d19";
		$r = $con->query($q);
		$row=$r->fetch_assoc();
		date_default_timezone_set("Asia/Kolkata");
		if(date("H:i:s")>=$row['invoice'] || date("H:i:s")<0)
		{
			header("location: inputlink.php?msg=12");
		}	
		
		
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
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
</head>
<body>
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
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		<label style="color:yellow";><p align="center" id="msg"></p></label>
	</div>
	<h4 style="text-align:center"><label>INVOICE PREPERATION </label></h4>
	<div>
<script>
var i = 0;
var txt = <?php
        echo "' Message : Non-Traceability Part Cannot Be Invoiced';";
?>
var speed = 75;
window.onload = typeWriter();
function typeWriter() {
  if (i < txt.length) {
    document.getElementById("msg").innerHTML += txt.charAt(i);
    i++;
    setTimeout(typeWriter, speed);
  }
}
</script>
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<script>
			function format() {
				var x = document.getElementById("vno");
				x.value=x.value.toUpperCase();
				if(event.keyCode!=8)
				{
					if(x.value.toString().length==2)
					{
						x.value=x.value+'-';
					}
					if(x.value.toString().length==5)
					{
						x.value=x.value+'-';
					}
					if(x.value.toString().length==8)
					{
						x.value=x.value+'-';
					}
				}
			}
			function reload0(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				self.location=<?php echo"'newinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p;
			}
			function reload(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				self.location=<?php echo"'newinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2;
				
				<?php
					if(isset($_GET['partnumber'])){
					
					$pno=$_GET['partnumber'];
					
					$query = "SELECT pnum FROM orderbook WHERE pnum='$pno' UNION SELECT pnum FROM demandmaster where pnum='$pno'";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					
					if($row['pnum']!=$pno)
					{
						header("location: inputlink.php?msg=28");
					}
					
					$query1 = "SELECT * FROM `npdparts` WHERE pnum='$pno'";	
					$result1 = $con->query($query1);
					$row1 = mysqli_fetch_array($result1);
					if($row1['pnum']==$pno){						
						$query2 = "SELECT * FROM npd_invoicing WHERE pnum='$pno'";
						$result2 = $con->query($query2);
						$row2 = mysqli_fetch_array($result2);
						if($row1['pnum']==$pno){
							header("location: npd_inv_app.php?msg=1");
						}						
					}
					
				}
				?>
				
				
			}
			function reloadpn(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p3 = document.getElementById("pn").value;
				self.location=<?php echo"'newinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&pn='+pn.value;
			}
			function reloadsc(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p4 = document.getElementById("sc").value;
				if (document.getElementById("pn") !=null) {
					var p3 = document.getElementById("pn").value;
					self.location=<?php echo"'newinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&pn='+p3+'&sc='+p4;
				}
				else
				{
					self.location=<?php echo"'newinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&sc='+p4;
				}
			}
			function reload1(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p3=form.cname.options[form.cname.options.selectedIndex].value; 
				self.location=<?php echo"'newinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&cname='+p3;
			}
			function reload2(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p3=form.cname.options[form.cname.options.selectedIndex].value; 
				var p4 = document.getElementById("p4").value;
				self.location=<?php echo"'newinv.php?tdate='"?>+p1+'&inv='+p0+'&ccode='+p+'&partnumber='+p2+'&cname='+p3+'&cpo='+p4;
			}
			function preventback()
			{
				window.history.forward();
			}
			setTimeout("preventback()",0);
			window.onunload = function(){ null };
		</script>
		<br/>
		<?php
			$q = "SELECT * from d19";
			$r = $con->query($q);
			$row=$r->fetch_assoc();
			date_default_timezone_set("Asia/Kolkata");
			if(date("H:i:s")>=$row['invoice'] || date("H:i:s")<0)
			{
				header("location: inputlink.php?msg=12");
			}
		?>
		<form action="inventry.php" method="post" enctype="multipart/form-data">
			<div id="stylized" class="myform">
				<br><div class="column">
				<label>INVOICE NUMBER&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="text" readonly="readonly" id="p0" name="inum" value="<?php
					if(isset($_GET['inv']))
					{
						$q = "select invno as gen from inv_det ORDER BY invno DESC LIMIT 1";
						$r = $con->query($q);
						$fch=$r->fetch_assoc();
						if($fch['gen']=="")
						{
							echo 1;
						}
						else
						{
							$c=substr($fch['gen'],0,7).str_pad((substr($fch['gen'],7,5)+1),5,"0",STR_PAD_LEFT);
							if($_GET['inv']==$c && $_GET['tdate']==date('Y-m-d'))
							{
								echo $c;
							}
							else
							{
								//echo $_GET['inv'];
								header('location: logout.php');
							}
						}
					}
					else
					{
						$q1 = "SELECT distinct status from admin1 where status='3'";
						$r1 = $con->query($q1);
						$row1=$r1->fetch_assoc();
						if($row1['status']=="3")
						{
							header('location: inputlink.php?msg=6');
						}
						else
						{
							$id=$_SESSION['user'];
							mysqli_query($con,"UPDATE admin1 set status='3' where userid='$id'");
						}
						mysqli_query($con,"DELETE from inv_det where ok='F'");
						$q = "select invno as gen from inv_det ORDER BY invno DESC LIMIT 1";
						$r = $con->query($q);
						$fch=$r->fetch_assoc();
						if(substr($fch['gen'],2,2)!=date("y") && date("m")==4)
						{
							$unit="U1";
							$y=date("y").(date('y')+1);
							$digit="-00001";
							echo $unit.$y.$digit;
						}
						else{
							echo substr($fch['gen'],0,7).str_pad((substr($fch['gen'],7,5)+1),5,"0",STR_PAD_LEFT);
						}
					}
					?>"/>	
				</div>
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
		<?php
		if(isset($_GET['partnumber']) && isset($_GET['ccode']))
			{
				$t1=$_GET['partnumber'];
				$t=$_GET['ccode'];
				$result1 = $con->query("select distinct cname,cpono,despatch,transmode,distance from invmaster where pn='$t1' and ccode='$t'");
				$row1 = mysqli_fetch_array($result1);
				$t1= $row1['cname'];
				$t2=$row1['cpono'];
				$t3=$row1['despatch'];
				$t4=$row1['transmode'];
				$t5=$row1['distance'];
			}
			else
			{
				$t1="";$t2="";$t3="";$t4="";$t5="";
			}
		?>
				<div class="column">
					<label>INVOICE DATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input readonly="readonly" type="date" id="p1" name="tdate" value="<?php
					if(isset($_GET[	'tdate']))
					{
						echo $_GET['tdate'];
					}
					else
					{
						echo date('Y-m-d');
					}
					?>"/>	
				</div>
				<div class="column">
						<label>CUSTOMER CODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" class='s' required onchange=reload0(this.form) id="p" name="cc" list="codelist" value="<?php if(isset($_GET['ccode'])){
							echo $_GET['ccode'];
							}?>"/>
				</div><br><br>
				<div class="column">
						<label>PART NUMBER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text"  class='s' required onchange=reload(this.form) id="p2" name="pn" list="partlist" value="<?php if(isset($_GET['partnumber'])){echo $_GET['partnumber'];} ?>"/>
				</div>
					<div class="column">
						<label>CUSTOMER NAME&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" class='s' readonly='readonly' required  id="p3" name="cname" value="<?php echo $t1;?>"/>
						</div>
					<div class="column">
						<label>CUSTOMER PO NO&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text"  readonly='readonly' id="p4" name="cpono"  <?php if(isset($_GET['cpo'])){echo "value=".$_GET['cpo'];}else{echo "value='$t2'";}?>>
					</div><br><br>
					<?php
						$br="";$br1="<br><br>";$dir=0;$dir1=0;
						if(isset($_GET['partnumber']) && $_GET['partnumber']!=""){
							$rat = $_GET['partnumber'];
							$res = $con->query("SELECT pnum FROM `pn_st` WHERE invpnum='$rat' and stkpt='FG For Invoicing' and n_iter=1");
							$c = $res->num_rows;
							if($c>1)
							{
								$dir=1;
								$br="<br><br>";
								$br1="";
								echo '<datalist id="pnlist">';
								echo"<option value=''>Select one</option>";
								while ($row = mysqli_fetch_array($res))
								{
									if($_GET['partnumber']==$row['pnum'])
										echo "<option selected value='".$row['pnum']."'>".$row['pnum']."</option>";
									else
										echo "<option value='".$row['pnum']."'>".$row['pnum']."</option>";
								}
								echo '</datalist>';
								if(isset($_GET['pn']))
								{
									$tmp=$_GET['pn'];
								}
								else{$tmp="";}
								echo '<div class="column">
									<label>PARTS FROM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
									<input type="text" id="pn" list="pnlist" required onchange="reloadpn(this.form)" name="pn1"  value="'.$tmp.'"/></div>';
							}
						}
						if(isset($_GET['partnumber']) && $_GET['partnumber']!="")
						{
							$cpn=$_GET['partnumber'];
							if(isset($_GET['pn']) && $_GET['pn']!="")
							{
								$cpn=$_GET['pn'];
							}
							$res = $con->query("SELECT pn,sccode FROM dcmaster WHERE pn='$cpn' AND sp='FG For S/C' GROUP BY pn,sccode ORDER BY pn");
							$c = $res->num_rows;
							if($c>0)
							{
								$dir1=1;
								$br="<br><br>";
								$br1="";
								echo '<datalist id="sclist">';
								echo"<option value=''>Select one</option>";
								while ($row = mysqli_fetch_array($res))
								{
									if($_GET['sccode']==$row['sccode'])
										echo "<option selected value='".$row['sccode']."'>".$row['sccode']."</option>";
									else
										echo "<option value='".$row['sccode']."'>".$row['sccode']."</option>";
								}
								echo '</datalist>';
								if(isset($_GET['sc']))
								{
									$tmp1=$_GET['sc'];
								}
								else{$tmp1="";}
								echo '<div class="column">
									<label>FROM UNIT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
									<input type="text" id="sc" list="sclist" required onchange="reloadsc(this.form)" name="sc"  value="'.$tmp1.'"/></div>';
							}
						}
						
					?>
					<div class="column">
						<label>AVAILABLE QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" required readonly='readonly' id="p7" name="avlqty" min="1"  value="<?php 
							$t="";
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
									//echo "SELECT * FROM m12 WHERE pnum='$pnum' and operation='FG For S/C'";
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
									else
									{
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
									}
								}
								if($t=="" && $t!=0)
								{
									echo "0";
								}
							}
						?>">
					</div>
					<div class="column">
						<label>ORDER BAL QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" required readonly='readonly' id="cmt" name="cmt" min="1"  value="<?php 
								if(isset($_GET['partnumber']) && $_GET['partnumber']!=""){
								$pnum = $_GET['partnumber'];
								if(isset($_GET['pn']) && $_GET['pn']!="")
								{
									$pnum = $_GET['pn'];
								}
								$result1 = $con->query("SELECT COUNT(*) AS c FROM `orderbook` WHERE pnum='$pnum'");
								$row = mysqli_fetch_array($result1);
								$c=$row['c'];
								if($c>0)
								{
									//$result2 = $con->query("SELECT pnum,obqty--IF(invoiced IS NULL,0,invoiced) AS bal FROM (SELECT pnum,SUM(qty) AS obqty FROM `orderbook` WHERE pnum='$pnum') AS T LEFT JOIN (SELECT pn,SUM(inv_det.qty) AS invoiced FROM inv_det WHERE  pn='$pnum' AND inv_det.invdt>'2019-08-07') AS T1 ON T.pnum=T1.pn");
									//$result2 = $con->query("SELECT pnum,SUM(qty)-SUM(invoiced_qty) AS bal FROM `orderbook` WHERE pnum='$pnum'");
									$from=date('Y-m-01');
									$to=date('Y-m-31');
				
									$result2 = $con->query("SELECT pnum,obqty,IF(invoiced_qty IS NULL,0,invoiced_qty) AS invoiced_qty FROM(SELECT pnum,SUM(qty) AS obqty FROM `orderbook` WHERE pnum='$pnum') AS ob LEFT JOIN(SELECT pn,SUM(qty) AS invoiced_qty FROM inv_det WHERE pn='$pnum' AND invdt>='$from' AND invdt<='$to') AS invdet ON ob.pnum=invdet.pn");
									
									$row = mysqli_fetch_array($result2);
									$cmt=$row['obqty']-$row['invoiced_qty'];
									if($cmt=="")
									{
										$cmt=0;
									}
									echo $cmt;
									if($cmt<$t)
									{
										$t=$cmt;
									}
								}
								else
								{
									echo "KANBAN PART";
								}
							}
						?>">
					</div>
					<?php echo $br; ?>
					<div class="column">
						<label>INVOICE QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="number" required <?php if($t==""){echo "readonly";} ?> id="p5" min="<?php if($t==""){echo 0;}else{echo 1;} ?>" max="<?php if($t==""){echo 0;}else{echo $t;} ?>" required name="tiqty">
					</div>
					
					<div class="column">
						<label>Mode Of Dispatch&nbsp;&nbsp;</label>
						<input type="text" id="p6" name="mod" readonly value="<?php echo $t3; ?>"/>
					</div>
					<?php echo $br1; ?>
					<div class="column">
						<label>MODE OF TRANSPORT&nbsp;</label>
						<input type="text"  id="tm" name="tm" required value="<?php echo $t4; ?>"/>
					</div>
					<?php echo $br; ?>
					<div class="column">
						<label>DISTANCE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text"  id="dis" name="dis"  min="1" value="<?php
						echo $t5;
						?>">
					</div>
					<?php echo $br1; ?>
					<div class="column">
						<label>VEHICLE NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" <?php
						if($t3=="COURIER")
						{
							echo 'required maxlength="13" pattern="^[A-Z]{2}-\d{2}-[A-Z]{1}.-\d{4}$" onkeyup="format()" placeholder="TN-99-AA-1111"';
							//echo 'value="COURIER" readonly';
						}
						else
						{
							echo 'required maxlength="13" pattern="^[A-Z]{2}-\d{2}-[A-Z]{1}.-\d{4}$" onkeyup="format()" placeholder="TN-99-AA-1111"';
						}
						?> id="vno" name="vno">
					</div>
				</div>
				<br><br>
				<div class="column1">
					<input type="submit" name="submit" value="CREATE INVOICE">
				</div>
				</form>
			</div>
	</body>
</html>
		