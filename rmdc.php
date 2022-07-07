<?php
$max=1;$r="";
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="Stores" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="DC RAISING FOR RM";
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
	
	<h4 style="text-align:center"><label> DC ISSAUNCE FOR RAW MATERIAL </label></h4>
	
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
		function preload(form)
		{
			var p0 = document.getElementById("p0").value;
			var p1 = document.getElementById("p1").value;
			var p = document.getElementById("p").value;
			self.location=<?php echo"'rmdc.php?dc='"?>+p0+'&tdate='+p1+'&iss='+p;
		}
		function reload(form)
		{
			var p = document.getElementById("p").value;
			var p0 = document.getElementById("p0").value;
			var p1 = document.getElementById("p1").value;
			var p2 = document.getElementById("p2").value;
			self.location=<?php echo"'rmdc.php?dc='"?>+p0+'&tdate='+p1+'&sccode='+p2+'&iss='+p;
		}
		function reload0(form)
		{
			var p = document.getElementById("p").value;
			var p0 = document.getElementById("p0").value;
			var p1 = document.getElementById("p1").value;
			var p2 = document.getElementById("p2").value;
			var p3 = document.getElementById("p3").value;
			self.location=<?php echo"'rmdc.php?tdate='"?>+p1+'&dc='+p0+'&sccode='+p2+'&partnumber='+p3+'&iss='+p;
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
		<form action="rmdcdb.php" method="post" enctype="multipart/form-data">
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
				
				<datalist id="list" >
					<?php
						echo"<option value=''>Select one</option>";
						echo "<option value='To S/C'>TO S/C</option>";
					?>
				</datalist>
				
				<div class="column">
					<label>ISSUANCE TYPE&nbsp;</label>
						<input type="text" style="width: 60%; background-color:white;" class='s' required onchange=preload(this.form) name ='dctype' id='p' onchange='' list="list" value="<?php if(isset($_GET['iss'])){
							echo $_GET['iss'];
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
				
				<datalist id="partlist" >
					<?php
						if(isset($_GET['sccode']))
						{
							$max1=$_GET['sccode'];
							$query = "SELECT DISTINCT rmdesc FROM m13";
							$result = $con->query($query);
							echo"<option value=''>Select one</option>";
							while ($row = mysqli_fetch_array($result)) 
							{
								if($_GET['rmdesc']==$row['rmdesc'])
									echo "<option selected value='".$row['rmdesc']."'>".$row['rmdesc']."</option>";
								else
									echo "<option value='".$row['rmdesc']."'>".$row['rmdesc']."</option>";
							}
						}
					?>
				</datalist>
				
				<div class="column">
					<label>RAW MATERIAL&nbsp;&nbsp;</label>
						<input type="text" style="width: 60%; background-color:white;" class='s' onchange=reload0(this.form) id="p3" name="pn" list="partlist" value="<?php if(isset($_GET['partnumber'])){echo $_GET['partnumber'];}?>"/>
				</div>
				
				<?php
					if(isset($_GET['partnumber']) && $_GET['iss']=="To S/C")
					{
						$cat=$_GET['iss'];
						$mat=$_GET['partnumber'];
						echo '<div class="column"><label>GRN NUMBER&nbsp;&nbsp;&nbsp;&nbsp;</label><select name ="prcno" id="prcno" REQUIRED onchange="reload2(this.form)">';
						echo"<option value=''>Select one</option>";
						$con = mysqli_connect("localhost","root","Tamil","storedb");
						$query2 = "SELECT DISTINCT grnnum FROM `receipt` WHERE rmdesc='$mat' AND closed='0000-00-00'";
						$result2 = $con->query($query2);
						//$query3 = "select d12.rcno,sum(partissued) as qty from d12 join d11 on d11.rcno=d12.rcno where closedate='0000-00-00' and stkpt='From S/C' and d12.pnum='$mat' AND d12.rcno!='' GROUP BY d11.rcno";
						//$result3 = $con->query($query3);
						while ($row2 = mysqli_fetch_array($result2)) 
						{
							if($_GET["mat"]==$row2["grnnum"])
								echo "<option selected value='" . $row2['grnnum'] . "'>" . $row2["grnnum"] ."</option>";
							else
								echo "<option value='" . $row2['grnnum'] . "'>" . $row2["grnnum"] ."</option>";
						}
						echo'</select></div>';
						
						echo '<br><br><div class="column">
								<label>&nbsp;Dispatch Mode</label>
								<input type="text" style="width: 60%; background-color:white;" id="p4" name="mot" value="">
							</div>';
						
						if(isset($_GET['mat']) && $_GET['mat']!="") 
						{
							$con = mysqli_connect("localhost","root","Tamil","mypcm");
							$conn = mysqli_connect("localhost","root","Tamil","storedb");
							$mat=$_GET['mat'];
							$rat=$_GET['partnumber'];
							$query = "SELECT quantityaccepted FROM `inspdb` WHERE grnnum='$mat'";
							$result = $conn->query($query);
							$row = mysqli_fetch_row($result);
							$sopr=$row[0];
							
							$query1 = "Select SUM(rmissqty) AS iq from d12 where inv='$mat'";
							$result1 = $con->query($query1);
							$row1 = mysqli_fetch_row($result1);
							$sopi=$row1[0];
							
							$query = "SELECT sent_thr_dc FROM `receipt` WHERE grnnum='$mat'";
							$result = $conn->query($query);
							$row = mysqli_fetch_row($result);
							$sopq=$row[0];
							
							$avlqty = $sopr - ($sopi+$sopq);
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
						self.location=<?php echo"'rmdc.php?tdate='"?>+p1+'&dc='+p0+'&sccode='+p2+'&partnumber='+p3+'&iss='+p+'&mat='+val;
					}
				</script>
				
				
				<?php
					if(isset($_GET['iss']) && $_GET['iss']=='FG For S/C' && isset($_GET['partnumber']) && $_GET['partnumber']!="")
					{
						if($max==1){$t="NOT AVAILABLE";}else{$t=$max;}
						echo '<div class="column"><label>&nbsp;AVL QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><input type="text" readonly="readonly" name="available" value="'.$t.'"></div>';
					}
				?>
			
				<div class="column">
					<label>&nbsp;DC QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="number" style="width: 60%; background-color:white;" min="1" max="<?php if($max==1){ echo '" readonly="readonly';}else{echo $max+10;}?>" required id="p5" name="tiqty">
				</div>
				
				<!--New Add-->
				<br><br>
				<div class="column">
					<label>&nbsp;VEHICLE NO&nbsp;&nbsp;&nbsp;</label>
					<input type="text" 
					<?php
						echo 'required maxlength="13" pattern="^[A-Z]{2}-\d{2}-[A-Z]{1}.-\d{4}$" onkeyup="format()" placeholder="TN-99-AA-1111"';
					?> id="vno" name="vno">
				</div>
				
				
				<?php
					if($max+10>1)
					{
						echo '<br><br><div><input type="submit" name="rmdcok" value="ENTER"></div>';
					}
				?>
				
			</div>
			
		</form>
		
	</div>
	
</body>

</html>