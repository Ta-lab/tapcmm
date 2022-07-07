<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	header("Location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="To S/C" || $_SESSION['access']=="From S/C" || $_SESSION['access']=="Semifinished1" || $_SESSION['access']=="Semifinished2" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="MANUAL RC ISSUANCE";
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
	<script src = "js\jquery-2.2.4.js"></script>
	<link rel="stylesheet" type="text/css" href="mystyle1.css">
	<link rel="stylesheet" type="text/css" href="design.css">
</head>
<body>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>PART ISSUANCE & ROUTE CARD GENERATION</label></h4>
		<div class="divclass">
		<?php
		if(isset($_POST['submit'])){
			$tdate = $_POST['tdate'];
			$prcno = $_POST['prcno'];
			$stkpt = $_POST['operation'];
			$ircn = $_POST['ircn'];
			$pnum = $_POST['pnum'];
			$iss = $_POST['iss'];
			if(isset($_POST['pn']) && $_POST['pn']!='')
			{
				$pnum=$_POST['pn'];
			}
			header("Location: i12_2db.php?tdate=$tdate&prcno=$prcno&operation=$stkpt&ircn=$ircn&pnum=$pnum&iss=$iss&pn=$pn");
		}
		if(isset($_GET['cat']) && $_GET['cat']!="From S/C" && $_GET['cat']!="Rework" && isset($_GET['rat']) && $_GET['rat']!="")
		{
			$query = "SELECT week FROM `d19`";
			$result = $con->query($query);
			$row = mysqli_fetch_array($result);
			$d=$row['week'];
			$t=$_GET['rat'];
			$query = "SELECT qty-issuedqty AS bal FROM `commit` WHERE foremac='MANUAL' AND week='$d' AND pnum='$t'";
			$result = $con->query($query);
			$row = mysqli_fetch_array($result);
			if($row['bal']>=1)
			{
				
			}
			else
			{
				header("location: inputlink.php?msg=18");
			}
		}
		?>
		
		<form method="POST" action='i12_2.php'>	
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
			<br/>
			<div>
				<label for="operation">STOCKING POINT</label>
					<?php
						//New Issue Occurs At a time B RC release from Multiple Users.So I add this here.
						
						if($_SESSION['user']=="104"){
							$result = $con->query("SELECT DISTINCT operation FROM m11 where opertype='stocking point' and operation != 'Stores' and operation!='FG For Invoicing' and operation!='FG For S/C' and operation!='TO S/C' and operation!='Semifinished1' and operation!='Semifinished2' and operation!='Semifinished3' ");
						}
						else{
							$result = $con->query("SELECT DISTINCT operation FROM m11 where opertype='stocking point' and operation != 'Stores' and operation!='FG For Invoicing' and operation!='FG For S/C' and operation!='TO S/C'");
						}
					
						//$result = $con->query("SELECT DISTINCT operation FROM m11 where opertype='stocking point' and operation != 'Stores' and operation!='FG For Invoicing' and operation!='FG For S/C' and operation!='TO S/C'");
						echo "<select name ='operation' onchange='reload(this.form)'>";
						echo "<option value=''>Choose the Stocking Point</option>";
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
			
				<script>
					function reload(form)
					{	
						var val=form.operation.options[form.operation.options.selectedIndex].value; 
						var s = document.getElementById("tdate").value;
						self.location='i12_2.php?tdate='+s+'&cat=' + val ;

					}
					function reload1(form)
					{	
						var val=$("#pnum").val();
						var obj=$("#combo-options").find("option[value='"+val+"']")
						if(obj !=null && obj.length>0)
						{
							var s = document.getElementById("pnum").value;
							self.location=<?php if(isset($_GET['cat']))echo"'i12_2.php?tdate=".$_GET['tdate']."&cat=".$_GET['cat']."&rat='";?> + s ;//page name
						}
						else
						{
							myFunction();
						}
					}
				</script>
			</br>
			<div>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" required name ="pnum" id="pnum" onchange="reload1(this.form)" value="<?php
				if(isset($_GET['rat']))
				{
					echo $_GET['rat'];
				}
				echo '"/>';
				if(isset($_GET['rat']) && isset($_GET['cat']) && $_GET['cat']!="Rework"  && $_GET['cat']!="From S/C")
				{
					$query = "SELECT manual FROM `d19`";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					$day=$row['manual'];
					$pnum=$_GET['rat'];
					
					//$query = "SELECT COUNT(*) AS c,foreman FROM (SELECT rcno,d11.pnum,date,datediff(NOW(),date) as age,m13.foreman FROM d11 LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE closedate='0000-00-00' AND rcno NOT LIKE 'F201____%' AND rcno LIKE '_201____%' AND operation!='Stores' AND foreman IN (SELECT foreman FROM m13 WHERE pnum='$pnum') HAVING age>$day) AS T";
					$query9 = "SELECT COUNT(*) AS c,foreman FROM (SELECT rcno,d11.pnum,date,datediff(NOW(),date) as age,m13.foreman FROM d11 LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE closedate='0000-00-00' AND foreman!='NPD' AND foreman!='Rosi' AND rcno NOT LIKE 'F20____%' AND rcno NOT LIKE 'E20____%' AND rcno LIKE '_20____%' AND operation!='Stores' AND foreman IN (SELECT foreman FROM m13 WHERE pnum='$pnum') HAVING age>$day) AS T";
					//echo    "SELECT COUNT(*) AS c,foreman FROM (SELECT rcno,d11.pnum,date,datediff(NOW(),date) as age,m13.foreman FROM d11 LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE closedate='0000-00-00' AND rcno LIKE '_20_____%' AND operation!='Stores' AND foreman IN (SELECT foreman FROM m13 WHERE pnum='$pnum') HAVING age>$day) AS T";
					$result9 = $con->query($query9);
					$row9 = mysqli_fetch_array($result9);
					if($row9['c']>0)
					{
						$t=$row9['foreman'];
						header("location: inputlink.php?msg=16&name=$t");	
					}
					
					//echo "SELECT COUNT(*) AS c FROM m13 where pnum='$pnum' and (foreman='' or foreman='PBR')";
					$query = "SELECT COUNT(*) AS c FROM m13 where pnum='$pnum' and (foreman='' or foreman='PBR')";
					$result = $con->query($query);
					$row1 = mysqli_fetch_array($result);
					
					if($row1['c']>0)
					{
						header("location: inputlink.php?msg=17");
					}
					
					/*//new add if input or putput bom is 0
					$query = "SELECT * FROM `rmcategory` WHERE pnum='$pnum' ";
					$result = $con->query($query);
					$row2 = mysqli_fetch_array($result);
					if($row2['bom']=='0.0000000' || $row2['obom']=='0.0000000')
					{
						//header("location: inputlink.php?msg=29");
					}
					*/
					
					
				}
				
				//NEW SF LOCK
				/*
				if(isset($_GET['rat']) && isset($_GET['cat']) && ($_GET['cat']=="Semifinished1" || $_GET['cat']=="Semifinished2" || $_GET['cat']=="Semifinished3") )
				{
					$cat=$_GET['cat'];
					$rat=$_GET['rat'];
					
					$query3 = "SELECT DISTINCT pnum FROM(SELECT DISTINCT pnum,datediff(NOW(),date) as days FROM `d11` WHERE operation='$cat' AND pnum='$rat' AND closedate='0000-00-00' HAVING days>10) AS T";
					//echo "SELECT DISTINCT pnum FROM(SELECT DISTINCT pnum,datediff(NOW(),date) as days FROM `d11` WHERE operation='$cat' AND pnum='$rat' AND closedate='0000-00-00' HAVING days>10) AS T";
					$result3 = $con->query($query3);
					$row3 = mysqli_fetch_array($result3);
					if($row3['pnum']!="")
					{
						//header("location: inputlink.php?msg=34");
					}
					
				}
				*/
				
				
				$cat="";
				if(isset($_GET['cat']))
				{
					$cat=$_GET['cat'];
					$dt=date('Y-m-d',strtotime('-4500 days'));
					//echo $dt;
					if($cat=="Semifinished1")
					{
						//$result = mysqli_query($con,"SELECT m12.pnum,m13.foreman FROM `m12` LEFT JOIN m13 ON m12.pnum=m13.pnum WHERE operation='Semifinished1' AND foreman NOT IN (SELECT DISTINCT foreman FROM `d11` JOIN m13 ON d11.pnum=m13.pnum WHERE (d11.rcno LIKE 'B20____%' OR d11.rcno LIKE 'C20____%') AND d11.date<='$dt' AND closedate='0000-00-00')");
						$result = mysqli_query($con,"SELECT m12.pnum,m13.foreman FROM `m12` LEFT JOIN m13 ON m12.pnum=m13.pnum WHERE operation='Semifinished1' AND foreman NOT IN (SELECT DISTINCT foreman FROM `d11` JOIN m13 ON d11.pnum=m13.pnum WHERE (d11.rcno LIKE 'B20____%' OR d11.rcno LIKE 'C20____%') AND d11.date<='$dt' AND closedate='0000-00-00' AND foreman!='NPD')");
						//echo "SELECT m12.pnum,m13.foreman FROM `m12` LEFT JOIN m13 ON m12.pnum=m13.pnum WHERE operation='Semifinished1' AND foreman NOT IN (SELECT DISTINCT foreman FROM `d11` JOIN m13 ON d11.pnum=m13.pnum WHERE (d11.rcno LIKE 'B20____%' OR d11.rcno LIKE 'C20____%') AND d11.date<='$dt' AND closedate='0000-00-00')";
					}
					if($cat=="Semifinished3")
					{
						$result = mysqli_query($con,"SELECT m12.pnum,m13.foreman FROM `m12` LEFT JOIN m13 ON m12.pnum=m13.pnum WHERE operation='Semifinished3' AND foreman NOT IN (SELECT DISTINCT foreman FROM `d11` JOIN m13 ON d11.pnum=m13.pnum WHERE (d11.rcno LIKE 'B20____%' OR d11.rcno LIKE 'C20____%') AND d11.date<='$dt' AND closedate='0000-00-00' AND foreman!='NPD')");
						//echo "SELECT m12.pnum,m13.foreman FROM `m12` LEFT JOIN m13 ON m12.pnum=m13.pnum WHERE operation='Semifinished3' AND foreman NOT IN (SELECT DISTINCT foreman FROM `d11` JOIN m13 ON d11.pnum=m13.pnum WHERE (d11.rcno LIKE 'B20____%' OR d11.rcno LIKE 'C20____%') AND d11.date<='$dt' AND closedate='0000-00-00')";
					}
					if($cat=="Semifinished2")
					{
						$result = mysqli_query($con,"SELECT m12.pnum,m13.foreman FROM `m12` LEFT JOIN m13 ON m12.pnum=m13.pnum WHERE operation='Semifinished2' AND foreman NOT IN (SELECT DISTINCT foreman FROM `d11` LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE (d11.rcno LIKE 'B20____%' OR d11.rcno LIKE 'C20____%') AND d11.date<='$dt' AND closedate='0000-00-00' AND foreman!='NPD')");
						//echo "SELECT DISTINCT m12.pnum,m13.foreman FROM m12 JOIN m13 ON m12.pnum=m13.pnum WHERE operation='$cat' AND m13.foreman NOT IN (SELECT foreman FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE d11.rcno LIKE 'C20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman HAVING COUNT(*)>1)";
					}
					if($cat=="From S/C")
					{
						$result = mysqli_query($con,"SELECT DISTINCT pnum From m12 where operation='From S/C'");
						//echo "SELECT DISTINCT m12.pnum,m13.foreman FROM m12 JOIN m13 ON m12.pnum=m13.pnum WHERE operation='$cat' AND m13.foreman NOT IN (SELECT foreman FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE d11.rcno LIKE 'E20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman HAVING COUNT(*)>10)";
					}
					if($cat=="Rework")
					{
						$result = mysqli_query($con,"SELECT DISTINCT pnum From m12");
						//echo "SELECT DISTINCT m12.pnum,m13.foreman FROM m12 JOIN m13 ON m12.pnum=m13.pnum WHERE operation='$cat' AND m13.foreman NOT IN (SELECT foreman FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE d11.rcno LIKE 'E20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman HAVING COUNT(*)>10)";
					}
					echo"<datalist id='combo-options'>";
					while($row = mysqli_fetch_array($result))
					{
						echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
					}
					echo"</datalist>";
				}
		?>
		</div>
			<br>
			<div>
				<label>Previous RCNO</label>
				<select name ="prcno" id="prcno" required onchange="reload2(this.form)">
						<?php	
							if(isset($_GET['rat'])) 
							{
								echo"<option value=''>Select one</option>";
								$cat=$_GET['cat'];
								$mat=$_GET['rat'];
								$query1 = "SELECT operation,opertype,issue from M11 where opertype='stocking point' order by issue";
								$result1 = $con->query($query1);
								$row1 = mysqli_fetch_array($result1);
								$r="A";
								if($cat=="Semifinished1" || $cat=="Semifinished2" || $cat=="Semifinished3")
								{
									$query2 = "SELECT prcno as rcno,SUM(partreceived)-SUM(partissued) FROM d12 WHERE  prcno IN (SELECT rcno FROM d11 WHERE pnum='$mat' AND operation='Stores') GROUP BY prcno HAVING SUM(partreceived)-SUM(partissued)>0";
									//echo "SELECT prcno as rcno,SUM(partreceived)-SUM(partissued) FROM d12 WHERE  prcno IN (SELECT rcno FROM d11 WHERE pnum='$mat' AND operation='Stores') GROUP BY prcno HAVING SUM(partreceived)-SUM(partissued)>0";
									$result2 = $con->query($query2);
								}
								else if($cat=="From S/C")
								{
									//$query2 = "select prcno as rcno,sum(partreceived)-sum(partissued)-sum(qtyrejected) as qty from d12 where stkpt='$cat' and pnum='$mat' and prcno in (select DISTINCT d11.rcno from d11 join d12 on d11.rcno=d12.prcno join m13 on d12.pnum=m13.pnum where d12.pnum='$mat' and d11.rcno!='') group by prcno HAVING qty>0";
									$query2 = "SELECT prcno as rcno,SUM(partreceived)-SUM(partissued) FROM d12 WHERE  prcno IN (SELECT rcno FROM d11 WHERE pnum='$mat' AND operation='To S/C') GROUP BY prcno HAVING SUM(partreceived)-SUM(partissued)>0";
									$result2 = $con->query($query2);
								}
								else if($cat=="Rework")
								{
									//$query2 = "select prcno as rcno,sum(partreceived)-sum(partissued)-sum(qtyrejected) as qty from d12 where stkpt='$cat' and pnum='$mat' and prcno in (select DISTINCT d11.rcno from d11 join d12 on d11.rcno=d12.prcno join m13 on d12.pnum=m13.pnum where d12.pnum='$mat' and d11.rcno!='') group by prcno HAVING qty>0";
									$query2 = "SELECT prcno as rcno,SUM(partreceived)-SUM(partissued) FROM d12 WHERE  prcno IN (SELECT rcno FROM d11 WHERE pnum='$mat' AND operation!='Stores') GROUP BY prcno HAVING SUM(partreceived)-SUM(partissued)>0";
									$result2 = $con->query($query2);
								}
								while ($row2 = mysqli_fetch_array($result2)) 
								{
									if($_GET['mat']==$row2['rcno'])
										echo "<option selected value='" . $row2['rcno'] . "'>" . $row2['rcno'] ."</option>";
									else
										echo "<option value='" . $row2['rcno'] . "'>" . $row2['rcno'] ."</option>";
								}
							}
						?>
						</select>
			</div>
			<br>
				<script>
					function reload2(form)
					{
						var val=form.prcno.options[form.prcno.options.selectedIndex].value;
						var s = document.getElementById("ircn").value;
						self.location=<?php if(isset($_GET['cat']))echo"'i12_2.php?tdate=".$_GET['tdate']."&cat=".$_GET['cat']."&rat=".$_GET['rat']."&mat='";?>+val+'&dc='+s; ;
					}
				</script>
			<div>
			<?php
				if(isset($_GET['rat']) && $_GET['rat']!=""){
					$rat = $_GET['rat'];
					$cat = $_GET['cat'];
					$res = $con->query("SELECT pnum FROM `pn_st` WHERE invpnum='$rat' and stkpt='$cat'");
					//echo "SELECT pnum FROM `pn_st` WHERE invpnum='$rat' and stkpt='$cat'";
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
						echo '<div>
							<label>CHANGE TO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
							<input type="text" id="pn" list="pnlist" required onchange=reloadpn(this.form) name="pn"  value="'.$tmp.'"/></div><br>';
					}
				}
			?>
			<div>
			<?php
			
			if(isset($_GET['cat'])) 
			{
				$cat=$_GET['cat'];
				if($cat=="FG For Invoicing")
					{
						echo '<label>INVOICE NUMBER</label>';
						echo '<input type="text" id="ircn" name="ircn" placeholder="Enter Invoice No" value="';if(isset($_GET['dc'])){echo $_GET['dc'];}echo'">';
					}
				else if($cat=="To S/C" || $cat=="FG For S/C")
					{
						echo '<label>DC NUMBER</label>';
						echo '<input type="text" id="ircn" name="ircn" placeholder="Enter Input DC NO">';			
					}
				else
					{
						echo"<label>ISSUANCE RCNO</label>";
							if($cat=="Semifinished3" || $cat=="Semifinished1")
							{
								$cat="Semifinished1' or operation='Semifinished3";
							}
							$query = "Select rcno from d11 where operation='$cat' order by rcno desc";
							//echo "Select rcno from d11 where operation='$cat' order by rcno desc";
							$result = $con->query($query);
							if(mysqli_num_rows($result) > 0)
							{
								$row = mysqli_fetch_row($result);
								$str=$row[0];							
								$str2=substr($str,1,4);
								$d=date('Y');
								if($str2==$d)
								{
									$str1=substr($str,5);
									$istr=(int)$str1+1;
									$sstr=(string)$istr;
									$slen=strlen($sstr);
									$slen=6;
									$fstr=str_pad($sstr,$slen,"0",STR_PAD_LEFT);
									$fstr1=substr($str,0,5);
									$fstr2=$fstr1.$fstr;
								}
								else
								{
									$query = "Select issue from m11 where operation='$cat'";
									$result = $con->query($query);
									$row = mysqli_fetch_row($result);
									$str=$row[0];
									$w1=date('Y');
									$w2 ="000001";
									$str1=$w1.$w2;
									$fstr2=$str.$str1;
								}
							}
							else
							{
								$query = "Select issue from m11 where operation='$cat'";
								$result = $con->query($query);
								$row = mysqli_fetch_row($result);
								$str=$row[0];
								$w1=date('Y');
								$w2 ="000001";
								$str1=$w1.$w2;
								$fstr2=$str.$str1;
							}	
							echo '<input type="text" id="ircn" readonly="" name="ircn" value="'. $fstr2.'">';
					}
			}					
			?>
			</div>
			<br>
			<div>
			<label>AVALABLE QTY</label> 
					<input type="text" readonly="readonly" required  name="available" value="<?php 
						if(isset($_GET['mat'])) 
						{	
							$mat=$_GET['mat'];
							$rat=$_GET['rat'];
							$cat=$_GET['cat'];
							$query = "Select sum(partreceived) from d12 where prcno='$mat' and stkpt='$cat'";
							$result = $con->query($query);
							$row = mysqli_fetch_row($result);
							$sopr=$row[0];
							$query1 = "Select sum(partissued) from d12 where prcno='$mat' and stkpt='$cat'";
							$result1 = $con->query($query1);
							$row1 = mysqli_fetch_row($result1);
							$sopi=$row1[0];
							$avlqty = $sopr - $sopi;
							echo "$avlqty";
						}
					?>"/>
			</div>
			</br>
			<div>
				<label>QTY PART ISSUED</label>
					<input type="number" id="iss" required name="iss" min="1" max="<?php echo $avlqty ?>" required placeholder="Enter the quantity of part issued" onclick="myfunctionbut()">
			</div>
			</br>
			<div>
					<input type="Submit" onclick="this.style.display = 'none';" name="submit" id="submit" value="EVERYTHING DONE"  onclick="myFunction()"/>
			</div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
function myfunctionbut(){
	document.getElementById('submit').style.visibility = 'true';
}
</script>
		</form>
	</div>
</body>
</html>
