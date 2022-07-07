<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<script src = "js\excelreport.js"></script>
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">

</head>
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
    width: 33%;
	display: none;
}
</style>
<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label> FINAL INSPECTION MASTER INSERTION [ I23 ]</label></h4>
	<div>
			<script>
			function reload(form)
			{
				var p4 = document.getElementById("p4").value;
				self.location=<?php echo"'i23.php?code='"?>+p4;
			}
			function reload1(form)
			{
				var p4 = document.getElementById("p4").value;
				if(p4==='')
				{
					
				}
				else
				{
					var pn = document.getElementById("pn").value;
					window.location='i23.php?code='+p4+'&pn='+pn;
				}
			}
			function reloadpick(form)
			{
				var pn = document.getElementById("pn").value;
				var p4 = document.getElementById("p4").value;
				var p5 = document.getElementById("p5").value;
				if(pn==='' || p4==='')
				{
					
				}
				else
				{
					window.location='i23.php?code='+p4+'&pn='+pn+'&vcode='+p5;
				}
			}
		</script>
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
	<div class="divclass">
		<form method="POST" action='i141.php'>	
				<datalist id="partlist" >
					<?php
						if(isset($_GET['code']) && $_GET['code']!="")
						{
							$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
							$ccode=$_GET['code'];
							$query = "SELECT distinct pn FROM invmaster where ccode='$ccode'";
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
					<datalist id="list" >
					<?php
						if(isset($_GET['pn']) && $_GET['pn']!="")
						{
							$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
							$pn=$_GET['pn'];
							$query = "SELECT DISTINCT ccode from fi_detail WHERE pnum='$pn'";
									$result = $con->query($query);
									echo"<option value=''>Select one</option>";
									while ($row = mysqli_fetch_array($result)) 
									{
										if($_GET['vcode']==$row['vcode'])
											echo "<option selected value='".$row['ccode']."'>".$row['ccode']."</option>";
										else
											echo "<option value='".$row['ccode']."'>".$row['ccode']."</option>";
									}
						}
					?>
					</datalist>
					<datalist id="codelist">
					<?php
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
						$t=$_GET['partnumber'];
						$result1 = $con->query("select distinct ccode from invmaster");
						echo"<option value=''>Select one</option>";
						while ($row1 = mysqli_fetch_array($result1)) 
						{
							if(isset($_GET['ccode'])==$row1['ccode'])
								echo "<option selected value='".$row1['ccode']."'>".$row1['ccode']."</option>";
							else
								echo "<option value='".$row1['ccode']."'>".$row1['ccode']."</option>";
						}
					?>
				</datalist>
				<br>
			<div class="find">
				<label>CUSTOMER CODE</label>
				<input type="text"  style="width:50%; background-color:white;" class='s' onchange=reload(this.form) id="p4" name="custpo" list="codelist" value="<?php if(isset($_GET['code'])){echo $_GET['code'];}?>">
			</div>
			<div class="find1">
				<label>PICK FROM</label>
				<input type="text" style="width:50%; background-color:white;" class='s' onchange=reloadpick(this.form) id="p5" name="cc" list="list" value="<?php if(isset($_GET['vcode'])){echo $_GET['vcode'];}?>">
			</div>
			<?php
				$cname="";$cadd1="";$cadd2="";$cadd3="";$cgstno="";$dtname="";$dtadd1="";$dtadd2="";$dtadd3="";$dtgstno="";$vc="";$ccode="";$s="";$p="readonly";$ss="readonly";$pn="";$ino="";
				for($i=0;$i<=50;$i++)
				{
					$c[$i]="";$d[$i]="";$m[$i]="";$u[$i]="";$l[$i]="";$ut[$i]="";
				}
				if(isset($_GET['code']) && $_GET['code']!='' && isset($_GET['pn']) && $_GET['pn']!="")
				{
					$ss="";
					$ccd=$_GET['code'];
					$pn=$_GET['pn'];
					if(isset($_GET['vcode']) && $_GET['vcode']!='')
					{
						$ccd=$_GET['vcode'];
					}
					$result1 = $con->query("select * from fi_detail where ccode='$ccd' and pnum='$pn' ORDER BY `s.no`");
					$i=0;
					while($row1 = mysqli_fetch_array($result1))
					{
						$i++;
						$c[$i]=$row1['chars'];$d[$i]=iconv('CP1250','UTF-8',$row1['drawspec']);$m[$i]=$row1['method'];$u[$i]=$row1['usl'];$l[$i]=$row1['lsl'];$ut[$i]=$row1['unit'];
					}
				}
				if(isset($_GET['code']) && $_GET['code']!='')
				{
					$t=$_GET['code'];
					if(!isset($_GET['pn']) || $_GET['pn']="")
					{
						$result1 = $con->query("select * from invmaster where ccode='$t' LIMIT 1");
						$row1 = mysqli_fetch_array($result1);
						if($result1->num_rows==1)
						{
							$cname=$row1['cname'];$cadd1=$row1['cadd1'];$cadd2=$row1['cadd2'];$cadd3=$row1['cadd3'];
							$vc=$row1['vc'];$ccode=$row1['ccode'];$p="";
						}
					}
					else
					{
						$result1 = $con->query("select * from invmaster where ccode='$t' and pn='$pn' LIMIT 1");
						$row1 = mysqli_fetch_array($result1);
						if($result1->num_rows==1)
						{
							$cname=$row1['cname'];$cadd1=$row1['cadd1'];$cadd2=$row1['cadd2'];$cadd3=$row1['cadd3'];
							$vc=$row1['vc'];$ccode=$row1['ccode'];$pd=$row1['pd'];$rmk=$row1['firmk'];$p="";$ino=$row1['ino'];
						}
						else
						{
							$result1 = $con->query("select * from invmaster where ccode='$t' LIMIT 1");
							$row1 = mysqli_fetch_array($result1);
							if($result1->num_rows==1)
							{
								$cname=$row1['cname'];$cadd1=$row1['cadd1'];$cadd2=$row1['cadd2'];$cadd3=$row1['cadd3'];
								$vc=$row1['vc'];$ccode=$row1['ccode'];$p="";$pd="";$rmk="";$p="";$pd=$row1['pd'];$ino=$row1['ino'];
							}
						}
					}			
				}
			?>
			<br><br>
			</form>
				<div id="stylized" class="myform">
				<form id="form" name="form" method="post" action="i23db.php">
					<div style="display:none">
						<label>TOTAL VARIANCE</label>
							<input type="text" id="oldccode" readonly="readonly" name="oldccode" value="<?php
							if(isset($_GET['code']))
							{
								echo $_GET['code'];
							}
						?>"/>
					</div>
					<div class="find2">
						<label>ISSUE NUMBER</label>
						<input type="text" style="width:50%; background-color:white;" class='s' id="issue" name="issue" value="<?php echo $ino;?>">
					</div>
					<br><br>
					<div class="column">
						<label>Customer Name: </label>
						<input type="text"  name="cname" readonly id="cname" value="<?php echo $cname; ?>"/>
					</div>
					<div class="column">
						<label>Cust Address1: </label>
						<input type="text"  name="cadd1" id="cadd1" readonly value="<?php echo $cadd1; ?>"/>
					</div>
					<div class="column">
						<label>Part Number&nbsp;&nbsp;: </label>
						<input type="text"  name="pn" list="partlist" onchange=reload1(this.form) required id="pn" value="<?php echo $pn;?>"/>
					</div>
					<div class="column">
						<label>CCODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="ccode" id="ccode"  readonly value="<?php echo $ccode; ?>"/>
					</div>
					<div class="column">
						<label>Cust Address2: </label>
						<input type="text"  name="cadd2" id="cadd2"  readonly value="<?php echo $cadd2; ?>"/>
					</div>
					<div class="column">
						<label>Part Descrip&nbsp;: </label>
						<input type="text"  name="pd" id="pd"  readonly value="<?php if(isset($_GET['pn'])){echo $pd;} ?>"/>
					</div>
					<div class="column">
						<label>Remark&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
						<input type="text"  name="rmk" id="rmk" value="<?php if(isset($_GET['pn'])){echo $rmk;} ?>" <?php echo $p;?>/>
					</div>
					<div class="column">
						<label>Cust Address3: </label>
						<input type="text"  name="cadd3" id="cadd3"  readonly value="<?php echo $cadd3; ?>"/>
					</div>
					<div class="column">
						<label>Vendor Code&nbsp;&nbsp;: </label>
						<input type="text"  name="vc" id="vc"  readonly value="<?php echo $vc; ?>"/>
					</div><br>
					<?php
						echo '<h5 class="h5" ><label class="h5l">S.No</label><label style="padding-left:5%;color:#33ff99;" >CHARACTERISTICS</label><label style="padding-left:10%;color:#33ff99;">DRAWING SPECIFICATION</label><label style="padding-left:10%;color:#33ff99;" >METHOD OF CHECKING</label><label style="padding-left:9%;color:#33ff99;" >LSL</label><label style="padding-left:10%;color:#33ff99;" >USL</label><label style="padding-left:8%;color:#33ff99;" >UNIT</label></h5>';
						for($i=1;$i<10;$i++)
						{
							echo '<h5 class="h5"><label class="h5l" >'.$i.'</label><label class="h5lll" ><input style="width: 160%;" type="text" maxlength="150" name="c'.$i.'" id="c'.$i.'" value="'.$c[$i].'" '.$ss.'/></label><label style="padding-left:8%;color:#33ff99;"><input type="text" maxlength="150"style="width: 160%;"  name="d'.$i.'" id="d'.$i.'"  value="'.iconv('UTF-8','CP1250',$d[$i]).'" '.$ss.'/></label><label style="padding-left:8%;color:#33ff99;" ><input type="text" maxlength="100" style="width: 160%;"  name="m'.$i.'" id="m'.$i.'"  value="'.$m[$i].'" '.$ss.'/></label>       <label style="padding-left:8%;color:#33ff99;" ><input type="text" maxlength="50" style="width: 80%;"  name="l'.$i.'" id="l'.$i.'"  value="'.$l[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;"><input type="text" maxlength="50" style="width: 80%;"  name="u'.$i.'" id="u'.$i.'"  value="'.$u[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;"><input type="text" maxlength="50" style="width: 50%;"  name="ut'.$i.'" id="ut'.$i.'"  value="'.$ut[$i].'" '.$ss.'/></label></h5>';
						}
						for($i=10;$i<=50;$i++)
						{
							echo '<h5 class="h5"><label class="h5l" >'.$i.'</label><label class="h5ll" ><input style="width: 160%;" type="text" maxlength="150" name="c'.$i.'" id="c'.$i.'"  value="'.$c[$i].'" '.$ss.'/></label><label style="padding-left:8%;color:#33ff99;"><input type="text" maxlength="150"style="width: 160%;"  name="d'.$i.'" id="d'.$i.'"  value="'.iconv('UTF-8','CP1250',$d[$i]).'" '.$ss.'/></label><label style="padding-left:8%;color:#33ff99;" ><input type="text" maxlength="100"style="width: 160%;"  name="m'.$i.'" id="m'.$i.'"  value="'.$m[$i].'" '.$ss.'/></label>        <label style="padding-left:8%;color:#33ff99;" ><input type="text" maxlength="50" style="width: 80%;"  name="l'.$i.'" id="l'.$i.'"  value="'.$l[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;"><input type="text" maxlength="50" style="width: 80%;"  name="u'.$i.'" id="u'.$i.'"  value="'.$u[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;"><input type="text" maxlength="50" style="width: 50%;"  name="ut'.$i.'" id="ut'.$i.'"  value="'.$ut[$i].'" '.$ss.'/></label></h5>';
						}
					?>
					<div>
						<input type="submit" name="submit" value="ADD DATA">
					</div>
					<div class="column1">
				</form>
	</body>
</html>
		