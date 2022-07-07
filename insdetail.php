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
		$activity="FI REPORT UPDATION";
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
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
		<?php
				$ss="readonly";
				$pn=$_GET['partnumber'];
				$con = mysqli_connect('localhost','root','Tamil','mypcm');
				$result1 = $con->query("SELECT * from fi_detail where pnum='$pn' group by `s.no` ORDER BY `s.no`");
				$cnt=0;
				while($row=mysqli_fetch_array($result1))
				{
					$c[$cnt]=$row['chars'];$d[$cnt]=$row['drawspec'];$m[$cnt]=$row['method'];$u[$cnt]=$row['usl'];$l[$cnt]=$row['lsl'];$ut[$cnt]=$row['unit'];
					$cnt++;
				}
		?>
	<div class="divclass">
	<br>
		<div id="stylized" class="myform">
		<form id="form" name="form" method="post" action="insdetaildb.php">
			<div class="column">
				<label>INSPECTION NO: </label>
				<input type="text"  name="insno" readonly id="insno" value="<?php echo $_GET['fin']; ?>"/>
			</div>
			<div class="column">
				<label>ROUTE CARD NO: </label>
				<input type="text"  name="rcno" id="rcno" readonly value="<?php echo $_GET['prcno']; ?>"/>
			</div>
			<div class="column">
				<label>Part Number&nbsp;&nbsp;: </label>
				<input type="text"  name="pn" readonly id="pn" value="<?php echo $_GET['partnumber'];?>"/>
			</div><br><br>
			<?php
				echo '<h5 class="h5" ><label class="h5l">S.No</label><label style="padding-left:4%;color:#33ff99;" >CHARACTERISTICS</label><label style="padding-left:3%;color:#33ff99;">DRAWING SPECIFICATION</label><label style="padding-left:2%;color:#33ff99;" >METHOD OF CHECKING</label><label style="padding-left:5%;color:#33ff99;" >UNIT</label><label style="padding-left:6%;color:#33ff99;" >LSL</label><label style="padding-left:4%;color:#33ff99;" >USL</label><label style="padding-left:4%;color:#33ff99;" >SAMPLE 1</label><label style="padding-left:2%;color:#33ff99;" >SAMPLE 2</label><label style="padding-left:2%;color:#33ff99;" >SAMPLE 3</label><label style="padding-left:2%;color:#33ff99;" >SAMPLE 4</label><label style="padding-left:2%;color:#33ff99;" >SAMPLE 5</label></h5>';
				$i=0;
				while($cnt!=$i)
				{
					if($i<=9)
					{
						echo '<h5 class="h5"><label class="h5l" >'.$i.'</label><label class="h5lll" ><input style="width: 100%;" type="text"  name="c'.$i.'" id="c'.$i.'" value="'.$c[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;"><input type="text"  style="width: 100%;"  name="d'.$i.'" id="d'.$i.'"  value="'.$d[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;" ><input type="text" style="width: 100%;"  name="m'.$i.'" id="m'.$i.'"  value="'.$m[$i].'" '.$ss.'/></label><label style="padding-left:3%;color:#33ff99;" ><input type="text" style="width: 50%;" size="15"  name="ut'.$i.'" id="ut'.$i.'"  value="'.$ut[$i].'" '.$ss.'/></label>';
						if($l[$i]==0 && $u[$i]==0)
						{
							echo '<label style="padding-left:0%;color:#33ff99;"><input type="text" style="width: 200%;"  name="ta'.$i.'" id="ta'.$i.'"  required   value=""/></label></h5>';
						}
						else
						{
							if($u[$i]<10){$pl="2.5%";}else{$pl="2%";}
							echo '<label style="padding-left:0%;color:#33ff99;" ><input type="text" maxlength="50" style="width: 100%;" size="6"  name="l'.$i.'" id="l'.$i.'"  value="'.$l[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;"><input type="text" maxlength="50" style="width: 100%;" size="6"  name="u'.$i.'" id="u'.$i.'"  value="'.$u[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s1'.$i.'" id="s1'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"  value=""/></label><label style="padding-left:'.$pl.';color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s2'.$i.'" id="s2'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"  value=""/></label><label style="padding-left:'.$pl.';color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s3'.$i.'" id="s3'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"  value=""/></label><label style="padding-left:'.$pl.';color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s4'.$i.'" id="s4'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"  value=""/></label><label style="padding-left:'.$pl.';color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s5'.$i.'" id="s5'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"   value=""/></label></h5>';
						}
					}
					else
					{
						echo '<h5 class="h5"><label class="h5l" >'.$i.'</label><label class="h5ll" ><input style="width: 100%;" type="text"  name="c'.$i.'" id="c'.$i.'"  value="'.$c[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;"><input type="text"  style="width: 100%;"  name="d'.$i.'" id="d'.$i.'"  value="'.$d[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;" ><input type="text" style="width: 100%;"  name="m'.$i.'" id="m'.$i.'"  value="'.$m[$i].'" '.$ss.'/></label><label style="padding-left:3%;color:#33ff99;" ><input type="text" style="width: 50%;" size="15"  name="ut'.$i.'" id="ut'.$i.'"  value="'.$ut[$i].'" '.$ss.'/></label>';
						if($l[$i]==0 && $u[$i]==0)
						{
							echo '<label style="padding-left:0%;color:#33ff99;"><input type="text" style="width: 200%;"  name="ta'.$i.'" id="ta'.$i.'"  required   value=""/></label></h5>';
						}
						else
						{
							if($u[$i]<10){$pl="2.5%";}else{$pl="1%";}
							echo '<label style="padding-left:0%;color:#33ff99;" ><input type="text" maxlength="50" style="width: 100%;" size="6"  name="l'.$i.'" id="l'.$i.'"  value="'.$l[$i].'" '.$ss.'/></label><label style="padding-left:2%;color:#33ff99;"><input type="text" maxlength="50" style="width: 100%;" size="6"  name="u'.$i.'" id="u'.$i.'"  value="'.$u[$i].'" '.$ss.'/></label><label style="padding-left:1%;color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s1'.$i.'" id="s1'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"  value=""/></label><label style="padding-left:'.$pl.';color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s2'.$i.'" id="s2'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"  value=""/></label><label style="padding-left:'.$pl.';color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s3'.$i.'" id="s3'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"  value=""/></label><label style="padding-left:'.$pl.';color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s4'.$i.'" id="s4'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"  value=""/></label><label style="padding-left:'.$pl.';color:#33ff99;" ><input type="number" maxlength="50" style="width: 100%;" size="6"  name="s5'.$i.'" id="s5'.$i.'" step="00.01" required min="'.$l[$i].'" max="'.$u[$i].'"   value=""/></label></h5>';
						}
					}
					$i=$i+1;
				}
			?>
			<div>
				<input type="submit" name="submit" value="ADD DATA">
			</div>
		</form>
	<br>
	</body>
</html>
		