<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="DC RAISING";
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
<!DOCTYPE html>-
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script type="text/javascript" src="table_script.js"></script>
	<script src = "js\bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="des1.css">
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
		<script>
			function preload(form)
			{
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p = document.getElementById("p").value;
				self.location=<?php echo"'alfaissuance.php?dc='"?>+p0+'&tdate='+p1+'&iss='+p;
			}
			function reload(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				self.location=<?php echo"'alfaissuance.php?dc='"?>+p0+'&tdate='+p1+'&sccode='+p2+'&iss='+p;
			}
			function reload0(form)
			{
				var p = document.getElementById("p").value;
				var p0 = document.getElementById("p0").value;
				var p1 = document.getElementById("p1").value;
				var p2 = document.getElementById("p2").value;
				var p3 = document.getElementById("p3").value;
				self.location=<?php echo"'alfaissuance.php?tdate='"?>+p1+'&dc='+p0+'&sccode='+p2+'&partnumber='+p3+'&iss='+p;
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
		<div class="divclass">
		<form action="parentchild.php" method="post" enctype="multipart/form-data">
		<br><br>
			<div >
				<label>DC NUMBER</label>
				<input style="width: 60%; background-color:white;" type="text" id="p0" name="dc" readonly value="<?php
				if(isset($_GET[	'dc']))
				{
					echo $_GET['dc'];
				}
				else
				{
					$con = mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						echo "connection failed";
					$q1 = "SELECT distinct status from admin1 where status='2'";
					$r1 = $con->query($q1);
					$row1=$r1->fetch_assoc();
					if($row1['status']=="2")
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
			<br>
			<div >
				<label>DC DATE</label>
				<input style="width: 60%; background-color:white;" type="date" id="p1" name="tdate" value="<?php
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
			<div>
					<datalist id="list" >
						<?php
							echo"<option value=''>Select one</option>";
							echo "<option value='To S/C'>TO S/C</option>";
							echo "<option value='FG For S/C'>FG FOR S/C</option>";
						?>
						</datalist>
					<br>
					<label>ISSUANCE TYPE</label>
					<input type="text" style="width: 60%; background-color:white;" class='s' required onchange=preload(this.form) name ='dctype' id='p' onchange='' list="list" value="<?php if(isset($_GET['iss'])){
						echo $_GET['iss'];
						}?>"/>
				</div>
			
			<div>
					<datalist id="clist" >
						<?php
							if(isset($_GET['iss']))
							{
								$t=$_GET['iss'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT distinct sccode FROM dcmaster where sp='$t'";
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
					<br>
					<label>S/C CODE</label>
					<input type="text" style="width: 60%; background-color:white;" class='s' required onchange=reload(this.form) id="p2" name="sc" list="clist" value="<?php if(isset($_GET['sccode'])){
						echo $_GET['sccode'];
						}?>"/>
				</div>
			<div>
					<datalist id="partlist" >
						<?php
							if(isset($_GET['sccode']))
							{
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$t1=$_GET['sccode'];
								if($_GET['iss']=="To S/C")
								{
									$query = "SELECT DISTINCT pn FROM dcmaster where sccode='$t1'";
								}
								else
								{
									$query = "SELECT distinct pn_st.invpnum as pn FROM `dcmaster` JOIN pn_st ON dcmaster.pn=pn_st.pnum where sccode='$t1'";
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
					<br>
					<label>PART NUMBER</label>
					<input type="text" style="width: 60%; background-color:white;" class='s' onchange=reload0(this.form) id="p3" name="pn" list="partlist" value="<?php if(isset($_GET['partnumber'])){echo $_GET['partnumber'];}?>"/>
				</div>
				<br>
				<?php
				if(isset($_GET['partnumber']) && $_GET['iss']=="To S/C")
				{
					$cat=$_GET['iss'];
					$mat=$_GET['partnumber'];
					echo '<div>
							<label>Previous RCNO</label>
							<select name ="prcno" id="prcno" REQUIRED onchange="reload2(this.form)">';
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
					echo'</select>
					</div>';
					//echo "select prcno as rcno,sum(partreceived)-sum(partissued)-sum(qtyrejected) as qty from d12 where stkpt='$cat' and d12.pnum='$mat' and prcno in (select DISTINCT d11.rcno from d11 join d12 on d11.rcno=d12.prcno join m13 on d12.pnum=m13.pnum where d12.pnum='$mat' and d11.rcno!='') group by prcno HAVING qty>0";
					if(isset($_GET['mat'])) 
					{	
						$mat=$_GET['mat'];
						$rat=$_GET['partnumber'];
						$cat=$_GET['iss'];
						if(substr($_GET['mat'],0,1)=="E")
						{
							$query = "Select sum(partissued) from d12 where rcno='$mat' and pnum='$rat' and stkpt='From S/C'";
							$result = $con->query($query);
							$row = mysqli_fetch_row($result);
							echo '<br><div><label>AVALABLE QTY</label><input type="text" readonly="readonly" name="available" value="'.$row[0].'"><br>';
						}
						else
						{
							$query = "Select sum(partreceived) from d12 where prcno='$mat' and pnum='$rat' and stkpt='$cat'";
							$result = $con->query($query);
							$row = mysqli_fetch_row($result);
							$sopr=$row[0];
							$query1 = "Select sum(partissued) from d12 where prcno='$mat' and pnum='$rat' and stkpt='$cat'";
							$result1 = $con->query($query1);
							$row1 = mysqli_fetch_row($result1);
							$sopi=$row1[0];
							$avlqty = $sopr - $sopi;
							echo '<br><div><label>AVALABLE QTY</label><input type="text" readonly="readonly" name="available" value="'.$avlqty.'"><br>';
						}
						
					}
				}
										
									?>
									<br>
											<script>
												function reload2(form)
												{
													var val=form.prcno.options[form.prcno.options.selectedIndex].value;
													var p = document.getElementById("p").value;
													var p0 = document.getElementById("p0").value;
													var p1 = document.getElementById("p1").value;
													var p2 = document.getElementById("p2").value;
													var p3 = document.getElementById("p3").value;
													self.location=<?php echo"'alfaissuance.php?tdate='"?>+p1+'&dc='+p0+'&sccode='+p2+'&partnumber='+p3+'&iss='+p+'&mat='+val;
												}
											</script>
										<div>
				<div>
					<label>Mode Of Dispatch</label>
					<input type="text" style="width: 60%; background-color:white;" id="p4" name="mot" value="">
				</div>
				<br>
				<div>
					<label>DC QTY</label>
					<input type="number" style="width: 60%; background-color:white;" required id="p5" name="tiqty">
				</div>
				<br>
			</div>
<div id="wrapper">
<br>
<div>
<input type="submit" name="alfaok" value="ENTER">
</div>
</div>
</form>
</body>
</html>