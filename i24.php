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
	if($_SESSION['access']=="FI" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="FINAL INSPECTION";
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
		<h4 style="text-align:center"><label>FINAL INSPECTION</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action='i24db.php'>	
			</br>
			<div>
				<label>DATE</label>
					<input type="date" id="tdate" name="tdate" value="<?php
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
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" name ="partnumber" id="pnum" onchange="reload0(this.form)" value="<?php
				if(isset($_GET['mat']))
				{
					echo $_GET['mat'];
				}
				?>"/>
			<?php					
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$result = mysqli_query($con,"SELECT DISTINCT pnum FROM `fi_detail` ");
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
				self.location=<?php echo"'i24.php?tdate='"?>+p1+'&mat='+p2;
			}
		</script>
		<?php
		if(isset($_GET['mat'])) 
		{
			$pn=$_GET['mat'];
			$result = $con->query("SELECT pnum FROM `pn_st` WHERE invpnum='$pn'");
			$count = $result->num_rows;
			if($count==0){
				$result = $con->query("SELECT pnum FROM `pn_st` WHERE pnum='$pn'");
				$count = $result->num_rows;
				if($count!=0){
					echo'<br><div>
					<label>PREVOIUS RCNO</label>
					<select name ="prcno" id="prcno" required onchange="reload1(this.form)">
					<option value="">Select one</option>';
					$mat=$_GET['mat'];
					$date="0000-00-00";
					$query2 = "SELECT DISTINCT d11.rcno from d11 WHERE d11.pnum='$mat' and d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='0000-00-00'";
					$rfrc = $con->query($query2);
					while ($row2 = mysqli_fetch_array($rfrc)) 
					{			
						if($_GET['rat']==$row2['rcno'])
						{
							echo "<option selected value='".$row2['rcno']."'>".$row2['rcno']."</option>";
						}
						else
						{
							echo "<option value='".$row2['rcno']."'>".$row2['rcno']."</option>";
						}
					}
					echo "</select>
					</div>";
				}
			}
			else
			{
				echo'<br><div>
				<label>PREVOIUS RCNO</label>
				<select name ="prcno" id="prcno" required onchange="reload1(this.form)">
				<option value="">Select one</option>';
				$mat=$_GET['mat'];
				$date="0000-00-00";
				$query2 = "SELECT DISTINCT d11.rcno from d11 WHERE d11.pnum='$mat' and d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='0000-00-00'";
				$rfrc = $con->query($query2);
				while ($row2 = mysqli_fetch_array($rfrc)) 
				{			
					if($_GET['rat']==$row2['rcno'])
					{
						echo "<option selected value='".$row2['rcno']."'>".$row2['rcno']."</option>";
					}
					else
					{
						echo "<option value='".$row2['rcno']."'>".$row2['rcno']."</option>";
					}
				}
				echo "</select>
				</div>";
			}				
		}
		?>
				<script>
					function reload1(form)
						{
							var val=form.prcno.options[form.prcno.options.selectedIndex].value;
							self.location=<?php if(isset($_GET['mat']))echo"'i24.php?tdate=".$_GET['tdate']."&mat=".$_GET['mat']."&rat='";?> + val ;
						}
				</script>
			<br>
			<div>
				<label>ISSUE LEVEL</label>
				<input type="text" id="iss" name="iss" required placeholder="ENTER LEVEL OF ISSUE" value="">
			</div>
				<script>
					function reload1(form)
						{
							var val=form.prcno.options[form.prcno.options.selectedIndex].value;
							self.location=<?php if(isset($_GET['mat']))echo"'i24.php?tdate=".$_GET['tdate']."&mat=".$_GET['mat']."&rat='";?> + val ;
						}
				</script>
			<br>
			<div>
				<label>FI REPORT NUMBER</label>
					<input type="text" id="fin" name="fin" readonly placeholder="AUTOMATIC GENERATION" value="<?php
					mysqli_query($con,"DELETE FROM `fi_rcno` WHERE ok =''");
					$query = "SELECT * FROM `fi_rcno` ORDER BY fi_id DESC";
					$result = $con->query($query);
					$row_cnt = $result->num_rows;
					if($row_cnt==0)
					{
						$q1="FI";
						$q2=date('Y');
						$q3="000000";
						$str=$q1.$q2.$q3;
						if($str=="")
						{
							$istr=(int)000000+000001;
						}
						else
						{
							$str1=substr($str,4);
							$istr=(int)$str1+1;
						}
						$sstr=(string)$istr;
						$slen=strlen($sstr);
						$slen=7-$slen;
						$fstr=str_pad($sstr,$slen,"0",STR_PAD_LEFT);
						$fstr1=substr($str,0,4);
						$fstr2=$fstr1.$fstr;
					}
					else
					{
						$row1 = mysqli_fetch_array($result);
						$str=$row1['fi_id'];	
						$str2=substr($str,2,4);
						$d=date('Y');
						if($str2==$d)
						{
							if($str=="")
							{
								$istr=(int)000000+000001;
							}
							else
							{
								$str1=substr($str,4);
								$istr=(int)$str1+1;
							}
							$sstr=(string)$istr;
							$slen=strlen($sstr);
							$slen=6;
							$fstr=str_pad($sstr,$slen,"0",STR_PAD_LEFT);
							$fstr1=substr($str,0,4);
							$fstr2=$fstr1.$fstr;
						}
						else
						{
							$q1="FI";
							$q2=date('Y');
							$q3="000000";
							$str=$q1.$q2.$q3;
							if($str=="")
							{
								$istr=(int)000000+000001;
							}
							else
							{
								$str1=substr($str,4);
								$istr=(int)$str1+1;
							}
							$sstr=(string)$istr;
							$slen=strlen($sstr);
							$slen=7-$slen;
							$fstr=str_pad($sstr,$slen,"0",STR_PAD_LEFT);
							$fstr1=substr($str,0,4);
							$fstr2=$fstr1.$fstr;
						}
					}
					echo $fstr2;
					?>">
			</div>
			<br>
			<div>
					<input type="Submit" name="submit" id="submit"  value="SUBMIT"/>
			</div>
		</form>
	</div>
</body>
</html>