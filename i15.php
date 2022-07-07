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
	if($_SESSION['access']=="Quality" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="QUALITY UPDATION";
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
		<h4 style="text-align:center"><label>QUALITY UPDATION</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action='i151.php'>	
			</br>
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
			<br>
			<div>
			<label>RCNO</label>
			<input type="text" list="combo-options" name ="rcno" id="rcno" onchange="reload(this.form)" value="<?php
				if(isset($_GET['rat']))
				{
					echo $_GET['rat'];
				}
				?>"/>
			<?php					
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$query = "select distinct(d11.rcno) from d11 join d12 on d11.rcno=d12.rcno where d11.rcno NOT LIKE '%L20%' AND closedate='0000-00-00' and d12.rcno!='' and d12.rcno like '_20__0%'";
					$result = $con->query($query); 
					echo "";
					echo"<datalist id='combo-options'>";
						while ($row = mysqli_fetch_array($result))
							{
								echo "<option value='".$row['rcno']."'>".$row['rcno']."</option>";
							}
					echo"</datalist>";
				?>
		</div>
		<script>
		function reload(form)
		{	
			var val=document.getElementById("rcno").value;
			var s = document.getElementById("tdate").value;
			self.location='i15.php?tdate='+s+'&rat='+val;
		}
		</script>
			<br>
			<div>
				<label>PART NUMBER</label>
					<select name ="partnumber" id="pnum" onchange="reload2(this.form)">
					<option value=''>Select one</option>
						<?php			
							if(isset($_GET['rat'])) 
							{	 
								$rat=$_GET['rat'];
								$query2 = "SELECT DISTINCT pnum FROM d11 where rcno = '$rat'";
								$result2 = $con->query($query2);
								while ($row2 = mysqli_fetch_array($result2)) 
								{
									 if($_GET['mat']==$row2['pnum'])
										echo "<option selected value='" . $row2['pnum'] . "'>" . $row2['pnum'] ."</option>";
									else
										echo "<option value='" . $row2['pnum'] . "'>" . $row2['pnum'] ."</option>";
								}
                    		}
						echo "</select></h1>";
						?>
						<script>
						function reload2(form)
						{	
							var val=form.partnumber.options[form.partnumber.options.selectedIndex].value; 
							self.location=<?php if(isset($_GET['rat']))echo"'i15.php?tdate=".$_GET['tdate']."&rat=".$_GET['rat']."&mat='";?> + val ;
						}
						</script>
			</div>
			<br>
			<div>
				<label>OPERATION</label>
					<select name ="operation" required id="prcno">
						<?php			
							if(isset($_GET['mat'])) 
							{	
								$mat=$_GET['mat'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								$query1 = "SELECT DISTINCT m12.operation from m12 join m11 on m12.operation=m11.operation where m12.pnum='$mat' and m11.opertype!='stocking point'";
								$result1 = $con->query($query1);  
								echo "<option value=''>Select one</option>";
								while ($row2 = mysqli_fetch_array($result1)) 
								{
									if($_GET['cat']==$row2['operation'])
										echo "<option selected value='" . $row2['operation'] . "'>" . $row2['operation'] ."</option>";
									else
										echo "<option value='" . $row2['operation'] . "'>" . $row2['operation'] ."</option>";
								}
							}
						?>
					</select>
			</div>
			<br>
			<div>
				<label>WORK CENTRE</label>
					<input type="text" name="workcentre" required placeholder="Enter Work Centre"/>
			</div>
			<br>
			<div>
				<label>QTY REJECTED IN KG</label>
					<input type="text" id="rcpt" name="rcpt" required onKeyUp="edValueKeyPress2()" placeholder="Enter Reciept Quantity">
			</div>
			</br>
			<div>
				<label>REASON </label>
					<input type="text" name="rsn" required placeholder="Enter the Reason for rejection"/>
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
							echo "Math.round(r.value = r1.value - "?>  ((+s.value)));
							<?php
						}
						else
						{
							echo "r.value = "?> r1.value - +s.value;
							<?php
						}?>;
					}
					var r = document.getElementById("total");
					var i = document.getElementById("issqty");
					var iss = document.getElementById("issue");
					var v=0;
					v= (r.value * 100) / i.value;
					
					
					if (v > 0.0001)
					{
						document.getElementById('cls').style.display = 'none';
					}
					else if(v < -0.0001)
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
			</script>
			
			<br>				
			
			<div>
				<label>ROUTE CARD / DC QTY IN KG</label>
				<input type="text" readonly="readonly" id="issqty" name="issdate" value="<?php
					if(isset($_GET['rat']) && isset($_GET['mat']))
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
							//$na=$temp1['pi'];
							$na=$temp1['pi']*$bom;
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
						<th>RECEIPT QTY(KG)</th>
						<th>QTY REJECTED(KG)</th>
						<th>TOTAL USAGE(KG)</th>
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
										//echo $temp2['rec'];
										echo $temp2['rec']*$bom;
									}
									else
									{
										echo "0";
									}
									echo "</td>";
									echo"<td id='2'>";
									if($temp2['rej']!='')
									{
										//echo $temp2['rej'];
										echo $temp2['rej']*$bom;
									}
									else
									{
										echo "0";
									}
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
										$tm =  $tm + $avl;
										echo round($avl,2);
									}
									else
									{
										$avl =$avl+(($temp2['rec']+$temp2['rej'])*$bom);
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
		<div style="display:none">
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
		<div style="display:none">
		<label>REMARK (If closed)</label>
			<input type="text" id="rmk" name="rmk" placeholder="Enter the Remarks"/>
		</div>
		<div style="display:none">
			<label name='option'>CLOSE RC / DC </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<label>
				<br>
					<input type="radio" id="cls" name="close" value="<?php echo date("Y-m-d", strtotime($_GET['tdate']));?>"><label>Yes</label></input>&nbsp;&nbsp;&nbsp;&nbsp;
					<br><input type="radio" id="cls" name="close" value="<?php echo date('0000-00-00');?>"><label>No</label></input>
		</div>
			
		<br>
		<div>
			<input type="Submit"  name="submit" id="submit"  value="SUBMIT" onclick="this.style.display = 'none';"/>
		</div>
		</form>
	</div>
</body>
</html>