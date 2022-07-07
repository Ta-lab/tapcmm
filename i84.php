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
	<h4 style="text-align:center"><label>RETURNED MATERIAL ISSUANCE & RC GENERATION [i84]</label></h4>
	<div class="divclass">
		<form method="POST" action="i84db.php">
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
						if(!isset($_GET['rat']))
						{
							$con=mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								die(mysqli_error());
							$result = mysqli_query($con,"select distinct(pnum) from m13");
							echo "";
							echo"<datalist id='combo-options1'>";
								while($row = mysqli_fetch_array($result))
									{
										echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
									}
							echo"</datalist>";
						}
					?>
		    </div>
			<br><br>
			<div class="column">
	<label>ISSUANCE RCNO&nbsp;</label>
	<?php
		$query = mysqli_query($con,"SELECT rcno FROM d11 WHERE operation='Returned' ORDER BY rcno DESC"); 
		$row=mysqli_fetch_row($query);
		$row_cnt = $query->num_rows;
		if($row_cnt==0)
		{
			$q1="F";
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
				$q1="F";
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
						var val=document.getElementById("raw").value;
						var s = document.getElementById("tdate").value;
						self.location='i84.php?tdate='+s+'&cat='+val ;
					}
				</script>
				
				<div class="column">
					<label>UNIT OF MSUR&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type='text' readonly='readonly' name='uom' value='Nos'/>
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
							self.location='i84.php?tdate='+s+'&rat='+v ;
						}
						else
						{
							var s = document.getElementById("tdate").value;
							var v= document.getElementById("pnum").value;
							self.location='i84.php?tdate='+s+'&rat='+v ;
							//myFunction();
						}
					}
					function reload1(form)
					{	
						var rin=form.rin.options[form.rin.options.selectedIndex].value; 
						var s = document.getElementById("tdate").value;
						var v= document.getElementById("pnum").value;
						self.location='i84.php?tdate='+s+'&rat='+v+'&rin='+rin ;
					}
				</script>
				<br><br>
				<div class="column">
					<label>RIN NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<select name ="rin" id="rin" required onchange="reload1(this.form)">
					<option value=''>Select one</option>
						<?php
						    if(isset($_GET['rat']) && $_GET['rat']!='')
							{
								$rat=$_GET['rat'];
								$conn = mysqli_connect('localhost','root','Tamil','storedb');
								$query = "SELECT DISTINCT rin FROM `rin_receipt` WHERE rin_status='0000-00-00' AND insp_status!='0000-00-00' AND pnum='$rat'";
								$result = $conn->query($query);
								while ($row = mysqli_fetch_array($result))
								{
									if($_GET['rin']==$row['rin'])
										echo "<option selected value='".$row['rin']."'>".$row['rin']."</option>";
									else
										echo "<option value='".$row['rin']."'>".$row['rin']."</option>";
								}
							}
						?>
					</select>
				</div>
				<?php
				if(isset($_GET['rin']) && $_GET['rin']!='' && isset($_GET['rat']) && $_GET['rat']!='')
				{
					$rin=$_GET['rin'];
					$part=$_GET['rat'];
					$query = "SELECT qty-issued AS qty FROM `rin_receipt` WHERE pnum='$part' AND rin='$rin'";
					$result = $conn->query($query);
					$row = mysqli_fetch_array($result);
					$g=$row['qty'];
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
					<input type="number" id="rmqty" onkeypress="myFun()" min="0" max="<?php echo round($g); ?>" name="rmqty" required placeholder="Enter Quantity">
				</div>
				<br><br>
		
			<div>
				<input type="Submit" name="submit" id="submit"  value="SUBMIT"  onclick="myFunction()">
			</div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
function myFun() {
			
			document.getElementById('submit').style.visibility = 'visible';
}
</script>
	</div>
	</form>
</body>
</html>
