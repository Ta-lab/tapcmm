<?php
$rm="";
$pnum="";
$bom="";
$na="";
session_start();
$inactive = 60000000; 
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
	<h4 style="text-align:center"><label>RAW MATERIAL RECEIPT[STORES]</label></h4>
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
			
		
		<form method="POST" action='i13_rm_receipt_db.php'>
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
			<label>RAW MATERIAL</label>
			<input type="text" list="combo-options" required name ="rawmaterial" id="rawmaterial" onchange="reload0(this.form)" value="<?php
				if(isset($_GET['raw_mat']))
				{
					echo $_GET['raw_mat'];
				}
				?>"/>
				<?php					
					$result = mysqli_query($con,"SELECT DISTINCT rmdesc FROM m13");
					echo "";
					echo"<datalist id='combo-options'>";
						while($row = mysqli_fetch_array($result))
							{
								echo "<option value='" . $row['rmdesc'] . "'>" . $row['rmdesc'] ."</option>";
							}	
					echo"</datalist>";
				?>
				
				
			</div>
			
			
			
			<div>
			<br>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options-rm" required name ="partnumber" id="pnum" onchange="reload0(this.form)" value="<?php
				if(isset($_GET['mat']))
				{
					echo $_GET['mat'];
				}
				?>"/>
				<?php					
					$rmdesc = $_GET['raw_mat'];
					//echo $rmdesc;
					$result = mysqli_query($con,"SELECT DISTINCT pnum FROM m13 where rmdesc='$rmdesc'");
					//echo "SELECT DISTINCT pnum FROM m13 where rmdesc='$rmdesc'";
					echo "";
					echo"<datalist id='combo-options-rm'>";
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
				var p2 = document.getElementById("rawmaterial").value;
				var p3 = document.getElementById("pnum").value;
				self.location=<?php echo"'i13_rm_receipt.php?tdate='"?>+p1+'&raw_mat='+p2+'&mat='+p3;
				
				
			}
			</script>
			
			
			<br>
			<div>
				<label>STOCKING POINT</label>
				<select  name ="operation" id ="operation" required onchange="reload(this.form)">
					<option value=''>Choose the Stocking Point</option>
					<?php 	 
							if(isset($_GET['mat']))
							{
								$mat=$_GET['mat'];
								//$result = $con->query("SELECT * FROM `m12` where m12.pnum='$mat' and operation='From S/C'");
								$result = $con->query("SELECT DISTINCT operation FROM `m12` where operation='From S/C'");
								$c = $result->num_rows;
								if($c==1)
								{
									$result = $con->query("SELECT DISTINCT operation FROM `m12` where operation='From S/C'");
								}
								else
								{
									$result = $con->query("SELECT DISTINCT operation FROM `m12` where operation='From S/C'");
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
									//echo "<option selected value='Rework'>Rework</option>";
								}
								else
								{
									//echo "<option value='Rework'>Rework</option>";
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
					var rm = document.getElementById("rawmaterial");
					var p = document.getElementById("pnum");
					window.location='i13_rm_receipt.php?tdate='+s.value+'&raw_mat='+rm.value+'&mat='+p.value+'&cat='+val;
					
				}
			
			</script>
			
			<br>
			<div>
				<label>PREVOIUS DCNO</label>
				<select name ='prcno' id='prcno' required onchange='reload1(this.form)'>
					<option value=''>Select one</option>
					<?php
						if(isset($_GET['cat']))
						{
							$mat=$_GET['mat'];
							$cat=$_GET['cat'];
							
							$rm = $_GET['raw_mat'];
							
							$date="0000-00-00";
                    		if($cat=="Semifinished1" || $cat=="Semifinished2")
							{
								$qfrc = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.rcno like 'A20%'";
								$rfrc = $con->query($qfrc);
							}
							else if($cat=="To S/C")
							{
								$query2 = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and (d11.rcno like 'A20%' or d11.rcno like 'B20%' or d11.rcno like 'C20%' or d11.rcno like 'E20%' or d11.rcno like 'DC%' or d11.rcno like 'F%' )";
								//echo "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and (d11.rcno like 'A20%' or d11.rcno like 'B20%' or d11.rcno like 'C20%' or d11.rcno like 'E20%' or d11.rcno like 'DC%')";
								$rfrc = $con->query($query2);
							}
							else if($cat=="From S/C")
							{								
								$query2 = "SELECT dcnum AS rcno FROM `rmdc` WHERE rm='$rm'";
								echo "SELECT * FROM `rmdc` WHERE rm='$rm'";
								//$query2 = "select DISTINCT d11.rcno from d11 where d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.operation like 'To S/C'";
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
								$query2 = "SELECT DISTINCT d11.rcno from d11 WHERE d11.pnum='$mat' and d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='0000-00-00'";
								$rfrc = $con->query($query2);
							}
							else
							{
								
								//echo "select DISTINCT d11.rcno from d11 where d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.rcno like '_20%'";
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
							self.location=<?php if(isset($_GET['cat']))echo"'i13_rm_receipt.php?tdate=".$_GET['tdate']."&raw_mat=".$_GET['raw_mat']."&mat=".$_GET['mat']."&cat=".$_GET['cat']."&rat='";?> + val ;
						}
				</script>
				
				
				<script>
					function reload2(form)
						{
							var p1 = document.getElementById("tdate").value;
							var p2 = document.getElementById("pnum").value;
							var p3 = document.getElementById("operation").value;
							var p4 = document.getElementById("prcno").value;
							self.location=<?php echo"'i13_rm_receipt.php?tdate='"?>+p1+'&mat='+p2+'&cat='+p3+'&rat='+p4;
						}
				</script>
				
			<div>
				<label>RECEIPT QTY</label>
				<input type="text" id="rcpt" name="rcpt" required onKeyUp="edValueKeyPress2()" placeholder="Enter the Reciept Quantity"/>
			</div>
			
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
					
					if (v > 0.1)
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
				<label>DC QTY (KG)</label>
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
							//$query2 = "SELECT sum(partissued) as pi FROM d12 where rcno='".$rat."' ";
							$query2 = "SELECT qty AS pi FROM `rmdc` WHERE dcnum='".$rat."' ";
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
						
						<th>RAW MATERIAL </th>
						<th>PART NUMBER </th>
						<th>RECEIPT QTY</th>
						
						<th style="display:none"> QTY REJECTED </th>
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
					//$query = "SELECT DISTINCT pnum FROM d11 where rcno='$rat'";
					$query = "SELECT rm FROM `rmdc` WHERE dcnum='".$rat."' ";
					//echo "SELECT rm FROM `rmdc` WHERE dcnum='".$rat."' ";
					$ret = mysqli_query($con,$query);
					$avl = 0;
					while($temp=mysqli_fetch_array($ret))
					{
						$t=$temp[0];
						echo $t;
						echo"<tr>";
							echo"<td>";
								echo $t;
							echo "</td>";
							echo"<td id='1'>";
							//echo"<td id='$t'>";
								
								if(isset($_GET['rat']))
								{
									
									if($_GET['mat']!='')
									{
										echo $_GET['mat'];
									}
									else
									{
										echo "0";
									}
									
									echo"<td id='2'>";
									$rat=$_GET['rat'];
									$result1 = $con->query("SELECT sum(partreceived) as rec,sum(qtyrejected) as rej FROM d12 where prcno='DC-$rat'");
									//echo "SELECT sum(partreceived) as rec,sum(qtyrejected) as rej FROM d12 where prcno='$rat'";
									
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
									
									
								}
							echo"</td>";
							echo"<td id='3'>";
								if(isset($_GET['rat'])) 
								{
									$rat=$_GET['rat'];
									$r=substr($rat,0,1);
									if($r=="A")
									{
										$avl =(($temp2['rec']+$temp2['rej'])*$bom);
										$tm = $tm + $avl;
										echo round($avl,2);
									}
									else
									{
										$avl =$avl+(($temp2['rec']+$temp2['rej'])*$bom);
										$tm = $tm + $avl;
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
					echo round(($na - $tm)/$bom,2);				
				}
				?>"/>
		</div>
	
		<div style="display:none">
			<label>TOTAL VARIANCE</label>
				<input type="text" id="total1" readonly="readonly" name="total1" value="<?php
				if(isset($_GET['rat']))
				{
					echo round(($na - $tm)/$bom,2);				
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