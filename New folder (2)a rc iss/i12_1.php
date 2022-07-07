<?php
session_start();
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	header("location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="Stores" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="A RC ISSUANCE";
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
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
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
	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<?php
	if(date("D")=="Sat" && date("H")<11)
	{
		header("location: inputlink.php?msg=20");
	}
	$day=0;
	if(isset($_GET['rat']) && $_GET['rat']!="")
	{
		$t=$_GET['rat'];
		$queryd = "SELECT cnc_excep FROM `m13` WHERE pnum='$t'";
		//echo "SELECT cnc_excep FROM `m13` WHERE pnum='$t'";
		$resultd = $con->query($queryd);
		$rowd = mysqli_fetch_array($resultd);
		$queryd1 = "SELECT COUNT(*) AS c FROM `m13` WHERE pnum='$t' and m_code='BLA_XXXX'";
		//echo "SELECT COUNT(*) AS c FROM `m13` WHERE pnum='$t' and m_code='BLA_XXXX'";
		$resultd1 = $con->query($queryd1);
		$rowd1 = mysqli_fetch_array($resultd1);
		if($rowd['cnc_excep']==1 || $rowd1['c']>0)
		{
			$result = mysqli_query($con,"SELECT COUNT(*) AS c FROM `m13` RIGHT JOIN pn_st ON pn_st.pnum=m13.pnum RIGHT JOIN invmaster ON invmaster.pn=pn_st.invpnum WHERE m13.pnum='$t' GROUP BY m13.pnum");
			$row = mysqli_fetch_array($result);
			if($row['c']=="")
			{
				header("location: inputlink.php?msg=9");
			}
			
			$result = mysqli_query($con,"SELECT useage,foreman from m13 where pnum='$t'");
			$row = mysqli_fetch_array($result);
			if($row['useage']==0)
			{
				header("location: inputlink.php?msg=26");
			}
			if($row['foreman']=="")
			{
				header("location: inputlink.php?msg=27");
			}
		}
		else
		{
			$t=$_GET['rat'];
			$q = "SELECT ARC from status";
			$r = $con->query($q);
			$row = mysqli_fetch_array($r);
			if($row['ARC']==0)
			{
				header("location: inputlink.php?msg=15");
			}
			$query = "SELECT week,max FROM `d19`";
			$result = $con->query($query);
			$row = mysqli_fetch_array($result);
			$queryd = "SELECT IF(DATEDIFF(now(), returnd) is NULL,4,DATEDIFF(now(), returnd)) AS lastret FROM `m13` WHERE pnum='$t'";
			$resultd = $con->query($queryd);
			$rowd = mysqli_fetch_array($resultd);
			IF($rowd['lastret']<=3)
			{
				header("location: inputlink.php?msg=22");
			}
			$query = "SELECT week,max FROM `d19`";
			$result = $con->query($query);
			$row = mysqli_fetch_array($result);
			$dt=$row['week'];
			$day=$row['max'];
			$day1=$row['max']-4;
			
			$q = "SELECT qty-issuedqty as q FROM `commit` WHERE week='$dt' and foremac='CNC_SHEARING' and pnum='$t'";
			//echo "SELECT qty-issuedqty as q FROM `commit` WHERE week='$dt' and foremac='CNC_SHEARING' and pnum='$t'";
			$r = $con->query($q);
			$c = $r->num_rows;
			$row = mysqli_fetch_array($r);
			if($row['q']<=0 ||  $c==0)
			{
				header("location: inputlink.php?msg=14");
			}
			$query = "SELECT count(*) as c FROM `m12` WHERE pnum='$t' and operation='CNC Machine'";
			$result = $con->query($query);
			$row = mysqli_fetch_array($result);
			if($row['c']>0)
			{
				$query = "SELECT count(*) as ac FROM `d11` LEFT JOIN m12 ON d11.pnum=m12.pnum WHERE d11.operation='Stores' AND closedate='0000-00-00' AND m12.operation='CNC Machine'";
				$result = $con->query($query);
				$row = mysqli_fetch_array($result);
				$query1 = "SELECT COUNT(*) AS c FROM (SELECT datediff(NOW(),date) as days FROM `d11` LEFT JOIN m12 ON d11.pnum=m12.pnum WHERE d11.operation='Stores' AND closedate='0000-00-00' AND m12.operation='CNC Machine' HAVING days>$day) AS T";
				$result1 = $con->query($query1);
				$row1 = mysqli_fetch_array($result1);
				if($row['ac']>=125)
				{
					header("location: inputlink.php?msg=1");
				}
				if($row1['c']>0)
				{
					header("location: inputlink.php?msg=10");
				}
			}
			$query = "SELECT count(*) as c FROM `m12` WHERE pnum='$t' and operation='Straitening/Shearing'";
			$result = $con->query($query);
			$row = mysqli_fetch_array($result);
			if($row['c']>0)
			{
				$query = "SELECT count(*) as ac FROM `d11` LEFT JOIN m12 ON d11.pnum=m12.pnum WHERE d11.operation='Stores' AND closedate='0000-00-00' AND m12.operation='Straitening/Shearing'";
				$result = $con->query($query);
				$row = mysqli_fetch_array($result);
				$query1 = "SELECT COUNT(*) AS c FROM (SELECT datediff(NOW(),date) as days FROM `d11` LEFT JOIN m12 ON d11.pnum=m12.pnum WHERE d11.operation='Stores' AND closedate='0000-00-00' AND m12.operation='Straitening/Shearing' HAVING days>$day) AS T";
				$result1 = $con->query($query1);
				$row1 = mysqli_fetch_array($result1);
				if($row['ac']>=35)
				{
					header("location: inputlink.php?msg=2");
				}
				if($row1['c']>0)
				{
					header("location: inputlink.php?msg=11");
				}
			}
			
			$result = mysqli_query($con,"SELECT COUNT(*) AS c FROM `m13` RIGHT JOIN pn_st ON pn_st.pnum=m13.pnum RIGHT JOIN invmaster ON invmaster.pn=pn_st.invpnum WHERE m13.pnum='$t' GROUP BY m13.pnum");
			$row = mysqli_fetch_array($result);
			if($row['c']=="")
			{
				header("location: inputlink.php?msg=9");
			}
			
			$result = mysqli_query($con,"SELECT useage,foreman from m13 where pnum='$t'");
			$row = mysqli_fetch_array($result);
			if($row['useage']==0)
			{
				header("location: inputlink.php?msg=26");
			}
			if($row['foreman']=="")
			{
				header("location: inputlink.php?msg=27");
			}
		}
	}
	?>
	<h4 style="text-align:center"><label>RAW MATERIAL ISSUANCE & RC GENERATION [i12_1]</label></h4>
	<div class="divclass">
		<form method="POST" action="vi12_1.php">
		<div id="stylized" class="myform">
		<br>
			<div class="column">
					<label>DATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
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
			<div class="column">
				<label>STOCKING POINT&nbsp;&nbsp;</label>
				<input type="text" id="stockingpoint" name="stockingpoint" readonly="readonly" value="Stores">
			</div>
			<div class="column">
				<label>PART NUMBER&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" list="combo-options1" name ="pnum" id="pnum" required onchange="reload0(this.form)" value="<?php
							if(isset($_GET['rat']))
							{
								$rat=$_GET['rat'];
								echo $rat;
							}
							?>"/>
					<?php		
							$result = mysqli_query($con,"select distinct(pnum) from m13");
							echo"<datalist id='combo-options1'>";
								while($row = mysqli_fetch_array($result))
									{
										echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
									}
							echo"</datalist>";
					?>
		    </div>
			<br><br>
			<div class="column">
	<label>ISSUANCE RCNO&nbsp;</label>
	<?php
		$query = mysqli_query($con,"SELECT rcno FROM d11 WHERE operation='Stores' ORDER BY rcno DESC"); 
		$row=mysqli_fetch_row($query);
		$row_cnt = $query->num_rows;
		if($row_cnt==0)
		{
			$q1="A";
			$q2=date('Y');
			$q3="000000";
			$str=$q1.$q2.$q3;
			if($str=="")
			{
				$istr=(int)000000+000001;
			}
			else
			{
				$str1=substr($str,5);
				$istr=(int)$str1+1;
			}
			$sstr=(string)$istr;
			$slen=strlen($sstr);
			$slen=7-$slen;
			$fstr=str_pad($sstr,$slen,"0",STR_PAD_LEFT);
			$fstr1=substr($str,0,5);
			$fstr2=$fstr1.$fstr;
		}
		else
		{
			$str=$row[0];							
			$str2=substr($str,1,4);
			$d=date('Y');
			if($str2==$d)
			{
				if($str=="")
				{
					$istr=(int)000000+000001;
				}
				else
				{
					$str1=substr($str,5);
					$istr=(int)$str1+1;
				}
				$sstr=(string)$istr;
				$slen=strlen($sstr);
				$slen=6;
				$fstr=str_pad($sstr,$slen,"0",STR_PAD_LEFT);
				$fstr1=substr($str,0,5);
				$fstr2=$fstr1.$fstr;
			}
			else
			{
				$q1="A";
				$q2=date('Y');
				$q3="000000";
				$str=$q1.$q2.$q3;
				if($str=="")
				{
					$istr=(int)000000+000001;
				}
				else
				{
					$str1=substr($str,5);
					$istr=(int)$str1+1;
				}
				$sstr=(string)$istr;
				$slen=strlen($sstr);
				$slen=7-$slen;
				$fstr=str_pad($sstr,$slen,"0",STR_PAD_LEFT);
				$fstr1=substr($str,0,5);
				$fstr2=$fstr1.$fstr;
			}
		}
		echo '<input type="text" id="ircn" name="ircn" readonly="readonly"  value="'. $fstr2;
		
	?>"></h1>
</div>	
			<script>
			
					function reload(form)
					{	
						var val=$("#raw").val();
						var obj=$("#combo-options").find("option[value='"+val+"']")
						if(obj !=null && obj.length>0)
						{
							var val=document.getElementById("raw").value;
							var val1=document.getElementById("pnum").value;
							var s = document.getElementById("tdate").value;
							self.location='i12_1.php?tdate='+s+'&cat='+val+'&rat='+val1 ;
						}
						else
						{
							myFunction();
						}
					}
					
					/*function reload(form)
					{	
						var val=document.getElementById("raw").value;
						var val1=document.getElementById("pnum").value;
						var s = document.getElementById("tdate").value;
						self.location='i12_1.php?tdate='+s+'&cat='+val+'&rat='+val1 ;
					}*/

				</script>
				
				<div class="column">
					<label>UNIT OF MSUR&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type='text' readonly='readonly' name='uom' value='<?php
						if(isset($_GET['cat'])) 
							{
								$cat=$_GET['cat'];
								$query = "SELECT * FROM m13 where rmdesc='".$cat."'";
								$result = mysqli_query($con,$query);
								$temp1=mysqli_fetch_array($result);
								echo $temp1['uom'];
							}
					?>'/>
				</div>
				<script>
					function reload0(form)
					{	
						var val=$("#pnum").val();
						var obj=$("#combo-options1").find("option[value='"+val+"']")
						if(obj !=null && obj.length>0)
						{
							var s = document.getElementById("tdate").value;
							var v= document.getElementById("pnum").value;
							self.location='i12_1.php?tdate='+s+'&rat='+v ;
						}
						else
						{
							myFunction();
						}
					}
					function reload1(form)
					{	
						var grn=form.inum.options[form.inum.options.selectedIndex].value; 
						var val= document.getElementById("raw").value;
						var s = document.getElementById("tdate").value;
						var v= document.getElementById("pnum").value;
						self.location='i12_1.php?tdate='+s+'&cat='+val+'&rat='+v+'&grn='+grn ;
					}
				</script>
				<div class="column">
					<label>RAW MATERIAL&nbsp;&nbsp;&nbsp;</label>
					<input type="text" list="combo-options" name ="raw" id="raw" onchange="reload(this.form)" value="<?php
						if(isset($_GET['cat']))
						{
							echo $_GET['cat'];
						}
						?>"/>
					<?php
						if(isset($_GET['rat'])&& $_GET['rat']!="")
						{
							$pn=$_GET['rat'];
							$con=mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								die(mysqli_error());
							$result = mysqli_query($con,"SELECT DISTINCT rmdesc FROM m13 WHERE pnum='$pn'");
							echo "";
							echo"<datalist id='combo-options'>";
								while($row = mysqli_fetch_array($result))
									{
										echo "<option value='" . $row['rmdesc'] . "'>" . $row['rmdesc'] ."</option>";
									}
							echo"</datalist>";
						}
						?>
					</div>
				<br><br>
				<div class="column">
					<label>GRN NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<select name ="inum" id="inum" required onchange="reload1(this.form)">
					<option value=''>Select one</option>
						<?php
						    if(isset($_GET['cat']) && $_GET['cat']!='')
							{
								$con = mysqli_connect('localhost','root','Tamil','storedb');
								$t=$_GET['cat'];
								//echo "SELECT DISTINCT receipt.grnnum as inum FROM `inspdb` LEFT JOIN receipt ON inspdb.grnnum=receipt.grnnum WHERE receipt.rmdesc='$t'";
								$query = "SELECT DISTINCT receipt.grnnum as inum FROM `inspdb` LEFT JOIN receipt ON inspdb.grnnum=receipt.grnnum WHERE  closed='0000-00-00' AND receipt.rmdesc='$t'";
								$result = $con->query($query);
								while ($row = mysqli_fetch_array($result))
								{
									if($_GET['grn']==$row['inum'])
										echo "<option selected value='".$row['inum']."'>".$row['inum']."</option>";
									else
										echo "<option value='".$row['inum']."'>".$row['inum']."</option>";
								}
							}
						?>
					</select>
				</div>
				<?php
				if(isset($_GET['grn']) && $_GET['grn']!='')
				{
					$grn=$_GET['grn'];
					$query = "SELECT grnnum,quantityaccepted,SUM(mypcm.d12.rmissqty),quantityaccepted-IF(SUM(mypcm.d12.rmissqty) IS NULL,0,SUM(mypcm.d12.rmissqty)) AS available FROM `inspdb` LEFT JOIN mypcm.d12 ON inspdb.grnnum=mypcm.d12.inv WHERE grnnum='$grn'";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					$g=$row['available'];
				}
				else
				{
					$g=0;
				}
				?>
				<div class="column">
					<label>AVAILABLE RM&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="text" name ="arm" readonly id="arm" value="<?php echo round($g,2); ?>">
				</div>
				<div class="column">
					<label>RM ISSUANCE QTY</label>
					<input type="number" step="0.01" id="rmqty" onkeypress="myFun()" min="0.10" max="<?php echo (round($g)+10); ?>" name="rmqty" required placeholder="Enter Quantity">
				</div>
				<br><br>
			<div class="column">
			<label>HEAT NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<input type="text" list="combo-option" name ="hno" placeholder="Enter Heat number" id="hno" value="<?php
				if(isset($_GET['mat']))
				{
					echo $_GET['mat'];
				}
				?>"/>
			<?php					
					$con=mysqli_connect('localhost','root','Tamil','storedb');
					if(isset($_GET['inum']))
					{
						$inum=$_GET['inum'];
						$result = mysqli_query($con,"SELECT DISTINCT heatnum from heatnumber where grnnum='$inum' and heatnum!=''");
						echo "";
						echo"<datalist id='combo-option'>";
							while($row = mysqli_fetch_array($result))
								{
									echo "<option value='" . $row['heatnum'] . "'>" . $row['heatnum'] ."</option>";
								}
						echo"</datalist>";
					}
				?>
				</div>
				<div class="column">
					<label>LOT NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="text" name ="lno" id="lno" placeholder="Enter lot number">
				</div>
				<div class="column">
					<label>COIL NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="text" name ="cno" id="cno" placeholder="Enter coil number">
				</div>
			<div>
				<input type="Submit" name="submit" id="submit"  value="SUBMIT" onclick="this.style.display = 'none';">
			</div>
<script>
function myFun() {
			
			document.getElementById('submit').style.visibility = 'visible';
}
</script>
	</div>
	</form>
</body>
</html>
