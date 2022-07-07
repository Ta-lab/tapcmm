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
	if($_SESSION['access']=="From S/C" || $_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C" || $_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="Semifinished1" || $_SESSION['access']=="Semifinished2" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="RECEIVING ENTRY";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
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
	<h4 style="text-align:center"><label>PARTS RECEIPT & ROUTE CARD CLOSURE</label></h4>
	<div class="divclass">
	<script>
	function preventback()
	{
		window.history.forward();
	}
	setTimeout("preventback()",0);
	window.onunload = function(){ null };
</script>
	<?php
	if(isset($_GET['rat']) && $_GET['rat']!="")
	{
		$rat=$_GET['rat'];
		$r=substr($rat,1,3);
		if($r=="201" || $r=="20-")
		{
			$td=date('Y-m-d',strtotime('-1 days'));
			$result = mysqli_query($con,"SELECT  COUNT(*) AS c FROM `d21` WHERE rcno='$rat' AND date>'$td'");
			$r=$result->num_rows;
			$row = mysqli_fetch_array($result);
			if($row['c']==0)
			{
				//header("location: inputlink.php?msg=24");
			}
		}
	}
	?>
	
	
	<?php
	
	//FINAL APPROVAL LOCK
	if(isset($_GET['mat']) && isset($_GET['cat']))
	{
		$tdy=date('Y-m-d',strtotime('-1 days'));
		$stockingpoint = $_GET['cat'];
		$pnum = $_GET['mat'];
		if($stockingpoint == "FG For Invoicing" || $stockingpoint == "FG For S/C")
		{
			//$query3 = "SELECT * FROM `f_insp` WHERE apby='' AND status='F' AND pnum='$pnum' AND date<='$tdy'";
			$query3 = "SELECT * FROM `f_insp` WHERE apby='' AND status='F' AND pnum='$pnum' AND date<='$tdy'";
			$result3 = $con->query($query3);
			$row3 = mysqli_fetch_array($result3);
			if($row3['pnum']!='')
			{
				//header("location: inputlink.php?msg=35");
			}
		}		
	}
	?>
	
	
	<?php
	//FG LOCK
		if(isset($_GET['cat']))
		{
			$stockingpoint = $_GET['cat'];
			$pnum = $_GET['mat'];
			if($stockingpoint == "FG For Invoicing")
			{
				$query3 = "SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno WHERE d11.pnum='$pnum' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt='FG For Invoicing' HAVING days>='30'";
				$result3 = $con->query($query3);
				$row3 = mysqli_fetch_array($result3);
				if($row3['pnum']!='')
				{
					//header("location: inputlink.php?msg=36");
				}
			}	
		}
	?>	
	
	
	<?php
	
	//REWORK DISABLED
	if(isset($_GET['cat']))
	{
		$stockingpoint = $_GET['cat'];
		if($stockingpoint == "Rework")
		{
			//header("location: inputlink.php?msg=38");
		}		
	}
	?>
	
	
		<form method="POST" action='i131.php'>
			<div>
				<label>DATE</label>
					<input type="date" id="tdate" readonly name="tdate" value="<?php
					if(isset($_GET['tdate']))
					{
						echo $_GET['tdate'];
					}
					else
					{
						echo date('Y-m-d');
					}
					?>"/>
			</div>
			<?php
				date_default_timezone_set("Asia/Kolkata");
					if(isset($_GET['cat']))
					{
						if((date("H")>=23 || date("H")<0) && ($_GET['cat']=='FG For Invoicing' || $_GET['cat']=='FG For S/C'))
						{
							//header("location: inputlink.php");
						}
					}
			?>
			<div>
			<br>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" required name ="partnumber" id="pnum" onchange="reload0(this.form)" value="<?php
				if(isset($_GET['mat']))
				{
					echo $_GET['mat'];
				}
				?>"/>
				<?php					
					$result = mysqli_query($con,"SELECT DISTINCT pnum FROM m13");
					echo "";
					echo"<datalist id='combo-options'>";
						while($row = mysqli_fetch_array($result))
							{
								echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
							}	
					echo"</datalist>";
				?>
		</div>
		<script>
			function reload0(form)
			{
				var p1 = document.getElementById("tdate").value;
				var p2 = document.getElementById("pnum").value;
				self.location=<?php echo"'i13.php?tdate='"?>+p1+'&mat='+p2;
				
				<?php
					if(isset($_GET['mat'])){
						$pnum=$_GET['mat'];
						$query = "SELECT * FROM `opbom` WHERE pnum='$pnum' ";
						$result = $con->query($query);
						$row2 = mysqli_fetch_array($result);
						if($row2['inputbom']=='0.0000000' || $row2['outputbom']=='0.0000000')
						{
							//header("location: inputlink.php?msg=29");
						}
					}
				?>
				
			}
		</script>
			<br>
			<div>
				<label>STOCKING POINT</label>
				<select  name ="operation" id="operation" required onchange="reload(this.form)">
					<option value=''>Choose the Stocking Point</option>
					<?php 	 
							if(isset($_GET['mat']))
							{
								$mat=$_GET['mat'];
								$result = $con->query("SELECT * FROM `m12` where m12.pnum='$mat' and operation='FG For S/C'");
								$c = $result->num_rows;
								if($c==1)
								{
									$result = $con->query("SELECT distinct m12.operation FROM `m12` join m11 on m12.operation=m11.operation where m12.pnum='$mat' and m11.opertype='STOCKING POINT' and m12.operation!='FG For Invoicing'");
								}
								else
								{
									$result = $con->query("SELECT distinct m12.operation FROM `m12` join m11 on m12.operation=m11.operation where m12.pnum='$mat' and m11.opertype='STOCKING POINT'");
								}
								while($row = mysqli_fetch_array($result))
								{
									if($_GET['cat']==$row['operation'])
										echo "<option selected value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
									else
										echo "<option value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
								}
								if($_GET['cat']=="Rework")
								{
									echo "<option selected value='Rework'>Rework</option>";
								}
								else
								{
									echo "<option value='Rework'>Rework</option>";
								}
							}
					?>
				</select>
			</div>
			<script>
				function reload(form)
				{
					var val=form.operation.options[form.operation.options.selectedIndex].value; 
					var s = document.getElementById("tdate");
					var p = document.getElementById("pnum");
					window.location='i13.php?tdate='+s.value+'&mat='+p.value+'&cat='+val;
				}
			</script>
			
			<?php 
				$userid = $_SESSION['user'];
				if($userid == "105") { ?>
			
			<div>
			<br>
			<label>PREVIOUS RC</label>
			<input type="text" list="rcno-combo-options" required name ="prcno" id="prcno" onchange="reload2(this.form)" value="<?php
				if(isset($_GET['rat']))
				{
					echo $_GET['rat'];
				}
				?>"/>
				<?php		
					if(isset($_GET['cat'])) 
					{
						$mat=$_GET['mat'];
						$cat=$_GET['cat'];
						$date="0000-00-00";
                    		
						if($cat=="Semifinished1" || $cat=="Semifinished2")
							{
								$qfrc = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.rcno like 'A20%'";
								$rfrc = $con->query($qfrc);
							}
							else if($cat=="To S/C")
							{
								//old working query
								//$query2 = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and (d11.rcno like 'A20%' or d11.rcno like 'B20%' or d11.rcno like 'C20%' or d11.rcno like 'E20%' or d11.rcno like 'DC%' or d11.rcno like 'F%' )";
								
								$query2 = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and (d11.rcno like 'A20%' or d11.rcno like 'B20%' or d11.rcno like 'C20%' or d11.rcno like 'E20%' or d11.rcno like 'DC%' or d11.rcno like 'F%' )";
								//echo "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and (d11.rcno like 'A20%' or d11.rcno like 'B20%' or d11.rcno like 'C20%' or d11.rcno like 'E20%' or d11.rcno like 'DC%')";
								$rfrc = $con->query($query2);
							}
							else if($cat=="From S/C")
							{								
								$query2 = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.operation like 'To S/C'";
								//echo "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.operation like 'To S/C'";
								$rfrc = $con->query($query2);
							}
							else if($cat=="FG For S/C")
							{
								$query2 = "SELECT DISTINCT d11.rcno FROM d11 WHERE d11.pnum='$mat' and d11.rcno!='' and d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='0000-00-00'";
								$rfrc = $con->query($query2);
							}
							else if($cat=="FG For Invoicing")
							{
								//old working query
								//$query2 = "SELECT DISTINCT d11.rcno from d11 WHERE d11.pnum='$mat' and d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='0000-00-00'";
								
								$query2 = "SELECT DISTINCT d11.rcno from d11 WHERE d11.pnum='$mat' and d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.operation!='To S/C' AND d11.closedate='0000-00-00'";
								$rfrc = $con->query($query2);
							}
							else
							{
								$qfrc = "select DISTINCT d11.rcno from d11 where d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.rcno like '_20%'";
								$rfrc = $con->query($qfrc);
							}
							
						echo "";
						echo"<datalist id='rcno-combo-options'>";
							while($row = mysqli_fetch_array($rfrc))
							{
								echo "<option value='" . $row['rcno'] . "'>" . $row['rcno'] ."</option>";
							}	
						echo"</datalist>";
					}
				?>
			</div>
			<?php } ?>
			
			<br>
			
			<?php 
				$userid = $_SESSION['user'];
				if($userid != "105") { ?>
			<div>
				<label>PREVOIUS RCNO</label>
				<select name ='prcno' id='prcno' required onchange='reload1(this.form)'>
					<option value=''>Select one</option>
					<?php
						if(isset($_GET['cat'])) 
						{
							$mat=$_GET['mat'];
							$cat=$_GET['cat'];
							$date="0000-00-00";
                    		if($cat=="Semifinished1" || $cat=="Semifinished2")
							{
								$qfrc = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.rcno like 'A20%'";
								$rfrc = $con->query($qfrc);
							}
							else if($cat=="To S/C")
							{
								//old working query
								//$query2 = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and (d11.rcno like 'A20%' or d11.rcno like 'B20%' or d11.rcno like 'C20%' or d11.rcno like 'E20%' or d11.rcno like 'DC%' or d11.rcno like 'F%' )";
								
								$query2 = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and (d11.rcno like 'A20%' or d11.rcno like 'B20%' or d11.rcno like 'C20%' or d11.rcno like 'E20%' or d11.rcno like 'DC%' or d11.rcno like 'F%' )";
								//echo "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and (d11.rcno like 'A20%' or d11.rcno like 'B20%' or d11.rcno like 'C20%' or d11.rcno like 'E20%' or d11.rcno like 'DC%')";
								$rfrc = $con->query($query2);
							}
							else if($cat=="From S/C")
							{								
								$query2 = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.operation like 'To S/C'";
								//echo "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.operation like 'To S/C'";
								$rfrc = $con->query($query2);
							}
							else if($cat=="FG For S/C")
							{
								$query2 = "SELECT DISTINCT d11.rcno FROM d11 WHERE d11.pnum='$mat' and d11.rcno!='' and d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='0000-00-00'";
								$rfrc = $con->query($query2);
							}
							else if($cat=="FG For Invoicing")
							{
								//old working query
								//$query2 = "SELECT DISTINCT d11.rcno from d11 WHERE d11.pnum='$mat' and d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='0000-00-00'";
								
								$query2 = "SELECT DISTINCT d11.rcno from d11 WHERE d11.pnum='$mat' and d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.operation!='To S/C' AND d11.closedate='0000-00-00'";
								$rfrc = $con->query($query2);
							}
							else
							{
								$qfrc = "select DISTINCT d11.rcno from d11 where d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.rcno like '_20%'";
								$rfrc = $con->query($qfrc);
							}
							while ($row2 = mysqli_fetch_array($rfrc)) 
							{			
								if(isset($_GET['rat']) && $_GET['rat']==$row2['rcno'])
								{
									echo "<option selected value='".$row2['rcno']."'>".$row2['rcno']."</option>";
								}
								else
								{
									echo "<option value='".$row2['rcno']."'>".$row2['rcno']."</option>";
								}
							}
							
						}
					?>
				</select>
			</div>
			<br>
			<?php } ?>
			
			<?php
				if(isset($_GET['cat']) && ($_GET['cat']=="Rework"))
				{
					echo '<div><label>SELECT AREA</label><select required name ="area" id="area"  onchange="reloa(this.form)"><option value="">Select Area</option>';
					$query2 = "SELECT stkpt,oper FROM `m14` WHERE stkpt NOT LIKE 'FG%'";
					$result2 = $con->query($query2);
					while ($row2 = mysqli_fetch_array($result2)) 
					{
						echo "<option value='" . $row2['stkpt'] . "'>" . $row2['oper'] ."</option>";
					}
					echo "</select></h1></div></br>";
				}
				?>
			
				<script>
					function reload1(form)
						{
							var val=form.prcno.options[form.prcno.options.selectedIndex].value;
							self.location=<?php if(isset($_GET['cat']))echo"'i13.php?tdate=".$_GET['tdate']."&mat=".$_GET['mat']."&cat=".$_GET['cat']."&rat='";?> + val ;
						}
						
					function reload2(form)
						{
							var p1 = document.getElementById("tdate").value;
							var p2 = document.getElementById("pnum").value;
							var p3 = document.getElementById("operation").value;
							var p4 = document.getElementById("prcno").value;
							self.location=<?php echo"'i13.php?tdate='"?>+p1+'&mat='+p2+'&cat='+p3+'&rat='+p4;
						}
					
				</script>
			<div>
				<label>RECEIPT QTY</label>
				<input type="text" id="rcpt" onpaste="return false;" onkeypress=" return isNumber(event)" name="rcpt" required onKeyUp="edValueKeyPress2()" placeholder="Enter the Reciept Quantity"/>
			</div>
			
			<script>
				function isNumber(evt)
				{
				evt = (evt) ? evt : window.event;
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode > 31 && (charCode < 48 || charCode > 57)) {
					return false;
				}
				return true; }
			</script>
			
			
			<script>
				function edValueKeyPress2()
				{
					var s3 = document.getElementById("3");
					if (s3 === null)
					{
						var tmp;
						var s = document.getElementById("rcpt");
						var r = document.getElementById("total");
						var r1 = document.getElementById("total1");
						var i = document.getElementById("issqty");
						var rcno = <?php
						$res= $con->query("SELECT useage FROM m13 where pnum='".$_GET['mat']."'");
						$temp4=mysqli_fetch_array($res);
						$bom=$temp4[0];
						if($bom==0 || $bom=="")
						{
							$res= $con->query("SELECT SUM(useage) as bom FROM `m13` WHERE pnum IN (SELECT pnum FROM pn_st WHERE invpnum='".$_GET['mat']."') AND m13.pnum!='".$_GET['mat']."'");
							//echo "SELECT DISTINCT useage FROM m13 where pnum='$part'";
							$temp4=mysqli_fetch_array($res);
							$bom=$temp4['bom'];
						}
						$r=substr($_GET['rat'],0,1);
						if($r=="A")
						{
							echo "tmp = $bom"?> * +s.value;<?php
							echo "Math.round(r.value = +r1.value - tmp)"?>
							<?php
						}
						else
						{
							echo "r.value = +r1.value - +s.value"?>;
							<?php
						}?>;
					}
					else
					{
						var s = document.getElementById("rcpt");
						var p = document.getElementById("pnum");
						var r = document.getElementById("total");
						var r1 = document.getElementById("total1");
						var i = document.getElementById("issqty");
						var s1 = document.getElementById("1").innerHTML;
						var s2 = document.getElementById("2").innerHTML;
						var s3 = document.getElementById("3").innerHTML;
						var temp = +s3 / (+s1 + +s2);
						var rcno = <?php
						$res= $con->query("SELECT useage FROM m13 where pnum='".$_GET['mat']."'");
						$temp4=mysqli_fetch_array($res);
						$bom=$temp4[0];
						if($bom==0 || $bom=="")
						{
							$res= $con->query("SELECT SUM(useage) as bom FROM `m13` WHERE pnum IN (SELECT pnum FROM pn_st WHERE invpnum='".$_GET['mat']."') AND m13.pnum!='".$_GET['mat']."'");
							//echo "SELECT DISTINCT useage FROM m13 where pnum='$part'";
							$temp4=mysqli_fetch_array($res);
							$bom=$temp4['bom'];
						}
						$r=substr($_GET['rat'],0,1);
						if($r=="A")
						{
							echo "Math.round(r.value = r1.value - $bom *"?>  ((+s.value)));
							<?php
						}
						else
						{
							echo "r.value = "?> r1.value -  +s.value;
							<?php
						}?>;
					}
					var r = document.getElementById("total");
					var i = document.getElementById("issqty");
					var iss = document.getElementById("issue");
					var v=0;
					v= (r.value * 100) / i.value;
					
					var stockingpoint = "<?php echo"$cat"?>";
					
					if (v > 1)
					{
						document.getElementById('cls').style.display = 'none';
					}
					else if(stockingpoint=='FG For S/C' || stockingpoint=='FG For Invoicing')
					{
						if(v < -0.001)
						{
							document.getElementById('cls').style.display = 'none';
							alert('Excess Received Not Allowed...');
							window.location.replace("inputlink.php");
						}
						else
						{
							document.getElementById('cls').style.display = 'block';
						}
					}
					else if(v < -0.001)
					{
						document.getElementById('cls').style.display = 'none';
						alert('Excess Receive Not Allowed...');
						window.location.replace("inputlink.php");
					}
					else
					{
						document.getElementById('cls').style.display = 'block';
					}
				}
			</script>
			<br>				
			<div>
				<label>ROUTE CARD / DC QTY</label>
				<input type="text" readonly="readonly" id="issqty" name="issdate" value="<?php
					if(isset($_GET['rat']))
					{
						$rat=$_GET['rat'];
						$mat=$_GET['mat'];
						$r=substr($rat,0,1);
						if($r=="A")
						{
							$query2 = "SELECT rmissqty FROM d12 where rcno='".$rat."'";
							$result2 = $con->query($query2);
							$temp1=mysqli_fetch_array($result2);
							$na=$temp1['rmissqty'];
						}
						else
						{
							$query2 = "SELECT sum(partissued) as pi FROM d12 where rcno='".$rat."' ";
							$result2 = $con->query($query2);
							$temp1=mysqli_fetch_array($result2);
							$na=$temp1['pi'];
						}
					}
					echo $na;
				?>"/>
			</div>
			<br>
			
			<table border="1" style=  "margin: 0 auto;" >
				 <thead>
					<tr>
						
						<th>PART NUMBER </th>
						<th>RECEIPT QTY</th>
						<th>QTY REJECTED</th>
						<th>QTY UNDER FINAL INSPECTION APPROVAL</th>
						<th>TOTAL USAGE</th>
						<th>UOM</th>
					</tr>
				</thead>
				<tbody>
			
						
			<?php			
				if(isset($_GET['rat'])) 
				{
					$tm=0;
					$rat=$_GET['rat'];
					$query = "SELECT DISTINCT pnum FROM d11 where rcno='$rat'";
					$ret = mysqli_query($con,$query);
					$avl = 0;
					while($temp=mysqli_fetch_array($ret))
					{
						$t=$temp[0];
						echo"<tr>";
							echo"<td>";
								echo $t;
							echo "</td>";
							echo"<td id='1'>";
							//echo"<td id='$t'>";
								if(isset($_GET['rat']))
								{
									$rat=$_GET['rat'];
									$result1 = $con->query("SELECT sum(partreceived) as rec,sum(qtyrejected) as rej FROM d12 where prcno='$rat'");
									$temp2=mysqli_fetch_array($result1);
									if($temp2['rec']!='')
									{
										echo $temp2['rec'];
									}
									else
									{
										echo "0";
									}
									echo "</td>";
									echo"<td id='2'>";
									if($temp2['rej']!='')
									{
										echo $temp2['rej'];
									}
									else
									{
										echo "0";
									}
									
									//displays qty under final inspection approval 
									$result3 = $con->query("SELECT SUM(qty) AS fi_qty FROM `f_insp` WHERE prcno='$rat' AND status='F' AND apby=''");
									$temp3=mysqli_fetch_array($result3);
									echo"<td>";
									if($temp3['fi_qty']!='')
									{
										echo $temp3['fi_qty'];
									}
									else
									{
										echo "0";
									}
									echo"</td>";
									
								}
							echo"</td>";
							echo"<td id='3'>";
								if(isset($_GET['rat'])) 
								{
									$rat=$_GET['rat'];
									$r=substr($rat,0,1);
									if($r=="A")
									{
										$avl =(($temp2['rec']+$temp2['rej']+$temp3['fi_qty'])*$bom);
										$tm =  $tm + $avl;
										echo round($avl,2);
									}
									else
									{
										$avl =$avl+($temp2['rec']+$temp2['rej']+$temp3['fi_qty']);
										$tm =  $tm + $avl;
										echo round($avl,2);
									}
								}
							echo"</td>";
							
							echo"<td>";
								if(isset($_GET['mat'])) 
								{
									echo $bom;					
								}
							echo"</td>";
						echo"</tr>";
					}
				}	
			?>
			</tbody>
		</table>
		<br>
		<div>
			<label>TOTAL VARIANCE</label>
				<input type="text" id="total" readonly="readonly" name="total" value="<?php
				if(isset($_GET['rat']))
				{
					echo round(($na - $tm),2);				
				}
				?>"/>
	</div>
	<div style="display:none">
			<label>TOTAL VARIANCE</label>
				<input type="text" id="total1" readonly="readonly" name="total1" value="<?php
				if(isset($_GET['rat']))
				{
					echo round(($na - $tm),2);				
				}
				?>"/>
	</div>
		<br>
		<div>
				<label>REMARK (If closed)</label>
					<input type="text" id="rmk" name="rmk" placeholder="Enter the Remarks"/>
			</div>
			<div>
				<label name='option'>CLOSE RC / DC </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label>
					<br>
					<input type="radio" id="cls" name="close" value="<?php echo date("Y-m-d", strtotime($_GET['tdate']));?>"><label>Yes</label></input>&nbsp;&nbsp;&nbsp;&nbsp;
					<br><input type="radio" id="cls" name="close" value="<?php echo date('0000-00-00');?>"><label>No</label></input>
			</div>
			
			<div>
				<input type="Submit" onclick="this.style.display = 'none';" id="submit" name="submit" value="SUBMIT"/>
			</div>
		</form>
	</div>
</body>
</html>