<?php
$rm="";
$pnum="";
$bom="";
$na="";
$avlqty="";
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
	if($_SESSION['user']=="123")
	{
		$id=$_SESSION['user'];
		$activity="RECEIVING ENTRY";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
	}
	else
	{
		header("location: inputlink.php");
		//header("location: logout.php");
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
	<h4 style="text-align:center"><label>FG FOR SCRAP</label></h4>
	<div class="divclass">
	<script>
	function preventback()
	{
		window.history.forward();
	}
	setTimeout("preventback()",0);
	window.onunload = function(){ null };
</script>
	<?php
	if(isset($_GET['rat']) && $_GET['rat']!="")
	{
		$rat=$_GET['rat'];
		$r=substr($rat,1,3);
		if($r=="201" || $r=="20-")
		{
			$td=date('Y-m-d',strtotime('-1 days'));
			$result = mysqli_query($con,"SELECT  COUNT(*) AS c FROM `d21` WHERE rcno='$rat' AND date>'$td'");
			$r=$result->num_rows;
			$row = mysqli_fetch_array($result);
			if($row['c']==0)
			{
				//header("location: inputlink.php?msg=24");
			}
		}
	}
	?>
		<form method="POST" action='fgscrapdb.php'>
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
			<?php
				date_default_timezone_set("Asia/Kolkata");
					if(isset($_GET['cat']))
					{
						if((date("H")>=23 || date("H")<0) && ($_GET['cat']=='FG For Invoicing' || $_GET['cat']=='FG For S/C'))
						{
							//header("location: inputlink.php");
						}
					}
			?>
			<div>
			<br>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" required name ="partnumber" id="pnum" onchange="reload0(this.form)" value="<?php
				if(isset($_GET['mat']))
				{
					echo $_GET['mat'];
				}
				?>"/>
			<?php					
					$result = mysqli_query($con,"SELECT DISTINCT pnum FROM m13");
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
		
			var today = new Date().toISOString().split('T')[0];
			document.getElementsByName("tdate")[0].setAttribute('min', today);
	
			function reload0(form)
			{
				var p1 = document.getElementById("tdate").value;
				var p2 = document.getElementById("pnum").value;
				self.location=<?php echo"'fgscrap.php?tdate='"?>+p1+'&mat='+p2;
			}
		</script>
			<br>
			<div>
				<label>STOCKING POINT</label>
				<select  name ="operation" required onchange="reload(this.form)">
					<option value=''>Choose the Stocking Point</option>
					<?php 	 
						if(isset($_GET['mat']))
						{
							$mat=$_GET['mat'];
							$result = $con->query("SELECT * FROM `m12` where m12.pnum='$mat' and operation='FG For S/C'");
							$c = $result->num_rows;
							if($c==1)
							{
								$result = $con->query("SELECT distinct m12.operation FROM `m12` join m11 on m12.operation=m11.operation where m12.pnum='$mat' and m11.opertype='STOCKING POINT' and m12.operation!='FG For Invoicing'");
							}
							else
							{
								$result = $con->query("SELECT distinct m12.operation FROM `m12` join m11 on m12.operation=m11.operation where m12.pnum='$mat' and m11.opertype='STOCKING POINT' AND m11.operation='FG For Scrap'");
							}
							while($row = mysqli_fetch_array($result))
							{
								if($_GET['cat']==$row['operation'])
									echo "<option selected value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
								else
									echo "<option value='" . $row['operation'] . "'>" . $row['operation'] ."</option>";
							}
						}
					?>
				</select>
			</div>
			<script>
				function reload(form)
				{
					var val=form.operation.options[form.operation.options.selectedIndex].value; 
					var s = document.getElementById("tdate");
					var p = document.getElementById("pnum");
					window.location='fgscrap.php?tdate='+s.value+'&mat='+p.value+'&cat='+val;
				}
			</script>
			<br>
			<div>
				<label>PREVOIUS RCNO</label>
				<select name ='prcno' id='prcno' required onchange='reload1(this.form)'>
					<option value=''>Select one</option>
					<?php
						if(isset($_GET['cat'])) 
						{
							$mat=$_GET['mat'];
							$cat=$_GET['cat'];
							$date="0000-00-00";
                    		if($cat=="FG For Scrap")
							{
								$query2 = "SELECT DISTINCT pnum,prc AS rcno,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt='FG For Invoicing' AND pnum='$mat'";
								$rfrc = $con->query($query2);
							}
							else
							{
								$qfrc = "select DISTINCT d11.rcno from d11 where d11.operation!='FG For Invoicing' AND d11.operation!='FG For S/C' AND d11.closedate='$date' and pnum='$mat' and d11.rcno!='' and d11.rcno like '_20%'";
								$rfrc = $con->query($qfrc);
							}
							while ($row2 = mysqli_fetch_array($rfrc)) 
							{			
								if(isset($_GET['rat']) && $_GET['rat']==$row2['rcno'])
								{
									echo "<option selected value='".$row2['rcno']."'>".$row2['rcno']."</option>";
								}
								else
								{
									echo "<option value='".$row2['rcno']."'>".$row2['rcno']."</option>";
								}
							}
							
						}
					?>
				</select>
			</div>
			<br>
			<?php
				if(isset($_GET['cat']) && ($_GET['cat']=="Rework"))
				{
					echo '<div><label>SELECT AREA</label><select required name ="area" id="area"  onchange="reloa(this.form)"><option value="">Select Area</option>';
					$query2 = "SELECT stkpt,oper FROM `m14` WHERE stkpt NOT LIKE 'FG%'";
					$result2 = $con->query($query2);
					while ($row2 = mysqli_fetch_array($result2)) 
					{
						echo "<option value='" . $row2['stkpt'] . "'>" . $row2['oper'] ."</option>";
					}
					echo "</select></h1></div></br>";
				}
				?>
			
				<script>
					function reload1(form)
						{
							var val=form.prcno.options[form.prcno.options.selectedIndex].value;
							self.location=<?php if(isset($_GET['cat']))echo"'fgscrap.php?tdate=".$_GET['tdate']."&mat=".$_GET['mat']."&cat=".$_GET['cat']."&rat='";?> + val ;
						}
				</script>
			
			
			<div>
				<label>AVAILABLE QTY</label>
				<input type="text" readonly="readonly" id="issqty" name="issdate" value="<?php
					if(isset($_GET['rat']))
					{
						$rat=$_GET['rat'];
						$mat=$_GET['mat'];
						//$r=substr($rat,0,1);
						
						/*if($r=="A")
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
							$na=$temp1['pi'];
						}*/
						$query2 = "SELECT DISTINCT pnum,prc AS rcno,s,days FROM (select d11.pnum as pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0 ORDER BY d11.pnum) T where stkpt='FG For Invoicing' AND pnum='$mat' AND prc='$rat'";
						$result2 = $con->query($query2);
						$row2 = mysqli_fetch_array($result2);
						$avlqty = $row2['s'];
					}
					echo $avlqty;
					
				?>"/>
			</div>
		<br>
			<div>
				<label>RECEIPT QTY</label>
				<input type="number" id="rcpt" name="rcpt" max="<?php echo $row2['s'] ?>" required onKeyUp="edValueKeyPress2()" placeholder="Enter the Reciept Quantity"/>
			</div>
			
		<br>
		
		<div>
				<input type="Submit" onclick="this.style.display = 'none';" id="submit" name="submit" value="SUBMIT"/>
		</div>
		
		</form>
	</div>


</body>
</html>