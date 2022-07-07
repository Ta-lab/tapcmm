<?php
$max=1;$r="";
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="DC RAISING";
		date_default_timezone_set('Asia/Kolkata');
		$maxime=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$maxime' where userid='$id'");
		
		$q = "SELECT * from d19";
		$r = $con->query($q);
		$row=$r->fetch_assoc();
		date_default_timezone_set("Asia/Kolkata");
		if(date("H:i:s")>=$row['dc'] || date("H:i:s")<0)
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
	
	<h4 style="text-align:center"><label> DC ENTRY </label></h4>
	
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
		
		function preload(form)
		{
			var p0 = document.getElementById("p0").value;
			var p1 = document.getElementById("p1").value;
			var p = document.getElementById("p").value;
			self.location=<?php echo"'alfatest.php?dc='"?>+p0+'&tdate='+p1+'&iss='+p;
		}
		function reload(form)
		{
			var p = document.getElementById("p").value;
			var p0 = document.getElementById("p0").value;
			var p1 = document.getElementById("p1").value;
			var p2 = document.getElementById("p2").value;
			self.location=<?php echo"'alfatest.php?dc='"?>+p0+'&tdate='+p1+'&sccode='+p2+'&iss='+p;
		}
		function reload0(form)
		{
			var p = document.getElementById("p").value;
			var p0 = document.getElementById("p0").value;
			var p1 = document.getElementById("p1").value;
			var p2 = document.getElementById("p2").value;
			var p3 = document.getElementById("p3").value;
			self.location=<?php echo"'alfatest.php?tdate='"?>+p1+'&dc='+p0+'&sccode='+p2+'&partnumber='+p3+'&iss='+p;
		
			<?php
					if(isset($_GET['partnumber'])){
					
					$pno=$_GET['partnumber'];
					
					$query = "SELECT DISTINCT pn FROM dcmaster WHERE pn='$pno'";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					
					if($row['pn']!=$pno)
					{
						header("location: inputlink.php?msg=32");
					}
					
				}
			?>
			
		}
		
	</script>
		
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
	

	<?php
		/*
		if($_GET['iss']=="FG For S/C" && $_GET['sccode']=="VSS U-2"){
			
			
			$dt=date('Y-m-d',strtotime('-15 days'));
								
			$query5 = "SELECT COUNT(*) AS DCCOUNT FROM(SELECT * FROM `dc_det` WHERE dcdate<='$dt' AND scn='VSS U-2') AS TDC
			LEFT JOIN(SELECT * FROM `d11`) AS TD11 ON TD11.rcno=CONCAT('DC-',TDC.dcnum) WHERE TD11.closedate='0000-00-00' AND TD11.operation='FG For S/C'";
			
			$result5 = $con->query($query5);
			$row5 = mysqli_fetch_array($result5);
			$dccount = $row5['DCCOUNT'];
			if($dccount > 0)
			{
				$query6 = "SELECT * FROM `lock_mechanism` WHERE lock_area='DC LOCK U2' ";
				$result6 = $con->query($query6);
				$row6 = mysqli_fetch_array($result6);
				$lock_date = $row6['lock_date'];
				$todaydt = date("Y-m-d");
				
				if($lock_date != $todaydt){
					header("location: reportmail_dclock_unit2.php");
				}else{
					header("location: inputlink.php?msg=40");
				}
			}
		}
		*/
	?>

	
	<div class="divclass">
		<form action="parentchild.php" method="post" enctype="multipart/form-data">
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
								$q1 = "SELECT distinct status from admin1 where status='2' OR status='9' ";
								//$q1 = "SELECT distinct status from admin1 where status='2'";
								$r1 = $con->query($q1);
								$row1=$r1->fetch_assoc();
								//if($row1['status']=="2")
								if($row1['status']=="2" || $row1['status']=="9")
								{
									header("location: inputlink.php?msg=3");
								}
								else
								{
									$id=$_SESSION['user'];
									mysqli_query($con,"UPDATE admin1 set status='2' where userid='$id'");
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
				
				<datalist id="list" >
					<?php
						echo"<option value=''>Select one</option>";
						echo "<option value='To S/C'>TO S/C</option>";
						echo "<option value='FG For S/C'>FG FOR S/C</option>";
					?>
				</datalist>
				
				<div class="column">
					<label>ISSUANCE TYPE&nbsp;</label>
						<input type="text" style="width: 60%; background-color:white;" class='s' required onchange=preload(this.form) name ='dctype' id='p' onchange='' list="list" value="<?php if(isset($_GET['iss'])){
							echo $_GET['iss'];
							
							//to be remove
							if($_GET['iss']=="FG For S/C"){
								//header("location: inputlink.php?msg=12");
							}
							
							//DC LOCK with Mail
							if($_GET['iss']=="To S/C")
							{
								$dt=date('Y-m-d',strtotime('-30 days'));
								
								$query5 = "SELECT COUNT(*) AS DCCOUNT FROM(SELECT * FROM `dc_det` WHERE dcdate<='$dt') AS TDC
								LEFT JOIN(SELECT * FROM `d11`) AS TD11 ON TD11.rcno=CONCAT('DC-',TDC.dcnum) WHERE TD11.closedate='0000-00-00' AND TD11.operation='To S/C'";
								
								$result5 = $con->query($query5);
								$row5 = mysqli_fetch_array($result5);
								$dccount = $row5['DCCOUNT'];
								if($dccount > 0)
								{
									$query6 = "SELECT * FROM `lock_mechanism` WHERE lock_area='DC LOCK' ";
									$result6 = $con->query($query6);
									$row6 = mysqli_fetch_array($result6);
									$lock_date = $row6['lock_date'];
									$todaydt = date("Y-m-d");
									
									if($lock_date != $todaydt){
										header("location: reportmail_dclock.php");
									}else{
										header("location: inputlink.php?msg=37");
									}
								}
							}
							
							
						}?>"/>
				</div>
				
				
				<datalist id="clist" >
					<?php
						if(isset($_GET['iss']))
						{
							$max=$_GET['iss'];
							$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
							$query = "SELECT distinct sccode FROM dcmaster where sp='$max'";
							$result = $con->query($query);
							echo"<option value=''>Select one</option>";
							while ($row = mysqli_fetch_array($result)) 
							{
								if($_GET['sccode']==$row['sccode'])
									echo "<option selected value='".$row['sccode']."'>".$row['sccode']."</option>";
								else
									echo "<option value='".$row['sccode']."'>".$row['sccode']."</option>";
							}
						}
					?>
				</datalist>
				
				<div class="column">
					<label>S/C CODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="text" style="width: 60%; background-color:white;" class='s' required onchange=reload(this.form) id="p2" name="sc" list="clist" value="<?php if(isset($_GET['sccode'])){
						echo $_GET['sccode'];
					}?>"/>
				</div>
				
				
				<br><br>
				
				<div class="column">
					<label>&nbsp;DC DATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input style="width: 60%; background-color:white;" type="date" id="p1" readonly name="tdate" value="<?php
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
				
				<datalist id="partlist" >
					<?php
						if(isset($_GET['sccode']))
						{
							$max1=$_GET['sccode'];
							if($_GET['iss']=="To S/C")
							{
								$query = "SELECT DISTINCT pn FROM dcmaster where sccode='$max1'";
							}
							else
							{
								$query = "SELECT distinct pn_st.invpnum as pn FROM `dcmaster` JOIN pn_st ON dcmaster.pn=pn_st.invpnum where sccode='$max1'";
							}
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
				
				<div class="column">
					<label>PART NUMBER&nbsp;&nbsp;&nbsp;</label>
						<input type="text" style="width: 60%; background-color:white;" class='s' onchange=reload0(this.form) id="p3" name="pn" list="partlist" value="<?php if(isset($_GET['partnumber'])){echo $_GET['partnumber'];}?>"/>
				</div>
				
				<?php
					$cnt=0;
					if(isset($_GET['partnumber']) && $_GET['iss']=="To S/C")
					{
						$mat=$_GET['partnumber'];
						$query = "SELECT COUNT(*) AS c FROM `pn_st` WHERE stkpt='To S/C' AND invpnum='$mat'";
						//echo "SELECT COUNT(*) AS c FROM `pn_st` WHERE stkpt='To S/C' AND invpnum='$mat'";
						$result = $con->query($query);
						$row = mysqli_fetch_array($result);
						$cnt=$row['c'];
					}
					if(isset($_GET['partnumber']) && $_GET['iss']=="To S/C" && $cnt=='0')
					{
						$cat=$_GET['iss'];
						$mat=$_GET['partnumber'];
						echo '<div class="column"><label>Previous RCNO&nbsp;</label><select name ="prcno" id="prcno" REQUIRED onchange="reload2(this.form)">';
						echo"<option value=''>Select one</option>";
						$con = mysqli_connect("localhost","root","Tamil","mypcm");
						$query1 = "SELECT operation,opertype,issue from M11 where opertype='stocking point' order by issue";
						$result1 = $con->query($query1);
						$row1 = mysqli_fetch_array($result1);
						$r="A";
						$query2 = "select prcno as rcno,sum(partreceived)-sum(partissued)-sum(qtyrejected) as qty from d12 where stkpt='$cat' and d12.pnum='$mat' and prcno in (select DISTINCT d11.rcno from d11 join d12 on d11.rcno=d12.prcno join m13 on d12.pnum=m13.pnum where d12.pnum='$mat' and d11.rcno!='') group by prcno HAVING qty>0";
						$result2 = $con->query($query2);
						//$query3 = "select d12.rcno,sum(partissued) as qty from d12 join d11 on d11.rcno=d12.rcno where closedate='0000-00-00' and stkpt='From S/C' and d12.pnum='$mat' AND d12.rcno!='' GROUP BY d11.rcno";
						//$result3 = $con->query($query3);
						while ($row2 = mysqli_fetch_array($result2)) 
						{
							if($_GET["mat"]==$row2["rcno"])
								echo "<option selected value='" . $row2['rcno'] . "'>" . $row2["rcno"] ."</option>";
							else
								echo "<option value='" . $row2['rcno'] . "'>" . $row2["rcno"] ."</option>";
						}
						/*while ($row3 = mysqli_fetch_array($result3)) 
						{
							if($_GET["mat"]==$row3["rcno"])
								echo "<option selected value='" . $row3['rcno'] . "'>" . $row3["rcno"] ."</option>";
							else
								echo "<option value='" . $row3['rcno'] . "'>" . $row3["rcno"] ."</option>";
						}*/
						echo'</select></div>';
						//echo "select prcno as rcno,sum(partreceived)-sum(partissued)-sum(qtyrejected) as qty from d12 where stkpt='$cat' and d12.pnum='$mat' and prcno in (select DISTINCT d11.rcno from d11 join d12 on d11.rcno=d12.prcno join m13 on d12.pnum=m13.pnum where d12.pnum='$mat' and d11.rcno!='') group by prcno HAVING qty>0";
						
						echo '<br><br><div class="column">
								<label>&nbsp;Dispatch Mode</label>
								<input type="text" style="width: 60%; background-color:white;" id="p4" name="mot" value="">
							</div>';
						
						if(isset($_GET['mat']) && $_GET['mat']!="") 
						{
							$mat=$_GET['mat'];
							$rat=$_GET['partnumber'];
							$cat=$_GET['iss'];
							$query = "Select sum(partreceived) from d12 where prcno='$mat' and pnum='$rat' and stkpt='$cat'";
							$result = $con->query($query);
							$row = mysqli_fetch_row($result);
							$sopr=$row[0];
							$query1 = "Select sum(partissued) from d12 where prcno='$mat' and pnum='$rat' and stkpt='$cat'";
							$result1 = $con->query($query1);
							$row1 = mysqli_fetch_row($result1);
							$sopi=$row1[0];
							$avlqty = $sopr - $sopi;
							$max=$avlqty;
							echo '<div class="column"><label>AVALABLE QTY&nbsp;&nbsp;</label><input type="text" readonly="readonly" name="available" value="'.$avlqty.'"></div>';							
						}
					}
					else
					{
						echo '<div class="column">
								<label>Dispatch	Mode&nbsp;</label>
								<input type="text" style="width: 60%; background-color:white;" id="p4" name="mot" value="">
							</div><br><br>';
					}
				?>
				
				<script>
					function reload2(form)
					{
						var val=form.prcno.options[form.prcno.options.selectedIndex].value;
						var p = document.getElementById("p").value;
						var p0 = document.getElementById("p0").value;
						var p1 = document.getElementById("p1").value;
						var p2 = document.getElementById("p2").value;
						var p3 = document.getElementById("p3").value;
						self.location=<?php echo"'alfatest.php?tdate='"?>+p1+'&dc='+p0+'&sccode='+p2+'&partnumber='+p3+'&iss='+p+'&mat='+val;
					}
				</script>
				
				<?php				
					if(isset($_GET['iss']) && $_GET['iss']=='FG For S/C' && isset($_GET['partnumber']) && $_GET['partnumber']!="" || $cnt>0)
					{
						$rat = $_GET['partnumber'];
						$stkpt='FG For Invoicing';
						IF($cnt>0)
						{
							$stkpt='To S/C';
						}
						$result = $con->query("SELECT pnum FROM `pn_st` WHERE invpnum='$rat' and stkpt='$stkpt' AND n_iter='1'");
						//echo "SELECT pnum FROM `pn_st` WHERE invpnum='$rat' and stkpt='$stkpt' AND n_iter='1'";
						$c = $result->num_rows;
						if($c>0)
						{
							$count=1;
						}
						else
						{
							$c=0;
						}
						if($c==0)
						{
							$r="NON TRACEABILITY PART";
							$max=1;
						}
						else
						{
							$stkpt='FG For S/C';
							IF($cnt>0)
							{
								$stkpt='To S/C';
							}
							$row = mysqli_fetch_array($result);
							$pnum=$row['pnum'];
							$result1 = $con->query("SELECT * FROM m12 WHERE pnum='$pnum' and operation='$stkpt'");
							//echo "SELECT * FROM m12 WHERE pnum='$pnum' and operation='$stkpt'";
							$c = $result1->num_rows;
							if($c==1 || $cnt>0)
							{
								do{
								$pnum=$row['pnum'];
								$result2 = $con->query("SELECT SUM(s) AS stock FROM (SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt='$stkpt' AND pnum='$pnum') AS T");
										//echo "SELECT SUM(s) AS stock FROM (SELECT pnum,prc,date,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt='$stkpt' AND pnum='$pnum') AS T";
								$row = mysqli_fetch_array($result2);
								if($max==0 || $max<$row['stock'])
								{
									$max=$row['stock'];
								}
								}while($row = mysqli_fetch_array($result));
							}
							else
							{
								$max=1;
							}
						}
					}
					if($max=="" || $max==0)
					{
						$max=1;
					}
				?>
				
				<?php
					if(isset($_GET['iss']) && $_GET['iss']=='FG For S/C' && isset($_GET['partnumber']) && $_GET['partnumber']!="" || $cnt>0)
					{
						if($max==1){$t="NOT AVAILABLE";}else{$t=$max;}
						echo '<div class="column"><label>&nbsp;AVL QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><input type="text" readonly="readonly" name="available" value="'.$t.'"></div>';
					}
				?>
			
				<div class="column">
					<label>&nbsp;DC QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="number" style="width: 60%; background-color:white;" min="1" max="<?php if($max<1){ echo '" readonly="readonly';}else{echo $max;}?>" required id="p5" name="tiqty">
				</div>
				
				<div class="column">
					<label>&nbsp;WEIGHT(KG)&nbsp;&nbsp;&nbsp;</label>
						<input type="text" style="width: 60%; background-color:white;" id="weight" name="weight">
				</div>
				
				<br><br>
				
				<div class="column">
					<label>&nbsp;Remarks&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" style="width: 60%; background-color:white;" id="remarks" name="remarks">
				</div>
				
				<!--New Add-->
				
				<div class="column">
					<label>&nbsp;VEHICLE NO&nbsp;&nbsp;&nbsp;</label>
					<input type="text" 
					<?php
						echo 'required maxlength="13" pattern="^[A-Z]{2}-\d{2}-[A-Z]{1}.-\d{4}$" onkeyup="format()" placeholder="TN-99-AA-1111"';
					?> id="vno" name="vno">
				</div>
				
				
				
				<?php
					if($max>=1)
					{
						echo "<br><br><br><div><input type='submit' onclick='this.style.display = none;' name='alfaok' value='ENTER'></div>";
					}
				?>
				
			</div>
			
		</form>
		
	</div>
	
</body>

</html>