<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="SELECTING RC FOR INVOICE";
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
<body onload="hidebtn()">
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>PART ISSUANCE & ROUTE CARD GENERATION</label></h4>
		<div class="divclass">
		<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		if(isset($_POST['submit'])){
			$tdate = $_POST['tdate'];
			$prcno = $_POST['prcno'];
			$stkpt = $_POST['operation'];
			$ircn = $_POST['ircn'];
			$pnum = $_POST['pnum'];
			$iss = $_POST['iss'];
			$ino = $_POST['ino'];
			$i = $_POST['iter'];
			$tiqty = $_POST['tiqty'];
			header("Location: i12_2db.php?tdate=$tdate&prcno=$prcno&operation=$stkpt&ircn=$ircn&pnum=$pnum&iss=$iss&ino=$ino&i=$i&tiqty=$tiqty");
		}
		?>
		<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		if(isset($_POST['sub1'])){
			$ircn = $_POST['ircn'];
			mysqli_query($con,"DELETE FROM inv_det WHERE invno=$ircn");
			mysqli_query($con,"DELETE FROM d11 WHERE rcno=$ircn");
			mysqli_query($con,"DELETE FROM d12 WHERE rcno=$ircn");
			mysqli_query($con,"DELETE FROM d13 WHERE rcno=$ircn");
			header("Location: inputlink.php");
		}
		?>
		<?php
		if(isset($_POST['sub']))
		{
			$con=mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				die(mysqli_error());
			$tdate = $_POST['tdate'];
			$prcno = $_POST['prcno'];
			$stkpt = $_POST['operation'];
			$ircn = $_POST['ircn'];
			$pnum = $_POST['pnum'];
			$iss = $_POST['iss'];
			$tiqty = $_POST['tiqty'];
			$rem = $_POST['rem'];
			$ino = $_POST['ino'];
			$i = $_POST['iter'];
			$result = $con->query("SELECT * FROM `pn_st` WHERE pnum='$pnum'");
			$row = mysqli_fetch_array($result);
			$p=$row['invpnum'];
			mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('$stkpt','$tdate','$p','$ircn')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$tdate','$stkpt','$p','$prcno','$ircn','$iss','$u','$ip')");
			mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$ircn','$prcno')");
			header("location: i12_3.php?tdate=$tdate&mat=$prcno&cat=$stkpt&rat=$pnum&dc=$ircn&tiqty=$tiqty&rem=$rem&ino=$ino&i=$i");
		}
		?>
		<form method="POST" action='i12_3.php'>	
			</br>
			<div>
				<label>DATE</label>
					<input type="date" id="tdate" name="tdate" value="<?php
					if(isset($_GET['tdate']))
					{
						echo date('Y-m-d',strtotime($_GET['tdate']));
					}
					else
					{
						echo date('d-m-Y');
					}
					?>"/>
			</div>
			<br/>
			<div>
				<label for="operation">STOCKING POINT</label>
					<?php
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
						if(!$con)
							echo "connection failed";
						$result = $con->query("SELECT operation FROM m11 where opertype='stocking point' and operation != 'Stores' and operation='FG For Invoicing'");
						echo "<select name ='operation'>";
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
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
				<script>
					function reload1(form)
					{	
						var val=form.pnum.options[form.pnum.options.selectedIndex].value;
						self.location=<?php if(isset($_GET['cat']))echo"'i12_3.php?tdate=".$_GET['tdate']."&cat=".$_GET['cat']."&rat='";?> + val ;//page name
						
					}
				</script>
			
			</br>
			<div>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" name ="pnum" id="pnum" readonly="readonly" value="<?php
				if(isset($_GET['rat']))
				{
					echo $_GET['rat'];
				}
				?>"/>
		</div>
			<br>
			<div>
				<label>Previous RCNO</label>
				<select name ="prcno" id="prcno"  onchange="reload2(this.form)">
						<?php	
							if(isset($_GET['rat'])) 
							{
								echo"<option value=''>Select one</option>";
								$cat=$_GET['cat'];
								$mat=$_GET['rat'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								$query1 = "SELECT operation,opertype,issue from M11 where opertype='stocking point' order by issue";
								$result1 = $con->query($query1);
								$row1 = mysqli_fetch_array($result1);
								$r="A";
								$query2 = "SELECT prcno as rcno,stkpt,qty FROM (SELECT d12.date,prcno,stkpt,SUM(partreceived)-SUM(partissued) as qty FROM d12 WHERE prcno IN (SELECT rcno FROM d11 WHERE pnum='$mat' AND rcno LIKE '_20%') GROUP BY prcno,stkpt HAVING qty>0) AS T WHERE T.stkpt='FG For Invoicing'";
																			
								$result2 = $con->query($query2);
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
						self.location=<?php if(isset($_GET['cat']))echo"'i12_3.php?ino=".$_GET['ino']."&i=".$_GET['i']."&tdate=".$_GET['tdate']."&cat=".$_GET['cat']."&rat=".$_GET['rat']."&tiqty=".$_GET['tiqty']."&rem=".$_GET['rem']."&mat='";?>+val+'&dc='+s; ;
					}
				</script>
			<div>
			<label>AVALABLE QTY</label> 
					<input type="text" name="available"  required readonly="readonly" value="<?php 
						if(isset($_GET['mat']) && $_GET['mat']!="") 
						{
							$mat=$_GET['mat'];
							$rat=$_GET['rat'];
							$cat=$_GET['cat'];
							$query = "Select sum(partreceived)-sum(partissued) as qty from d12 where prcno='$mat' and stkpt='$cat' having qty>0";
							$result = $con->query($query);
							$row = mysqli_fetch_row($result);
							$sopr=$row[0];
							echo "$sopr";
						}
						else
						{
							$sopr=0;
							echo "$sopr";
						}
					?>"/>
			</div>
			<br>
			<div>
			<?php
			
			if(isset($_GET['cat'])) 
			{
				$cat=$_GET['cat'];
				if($cat=="FG For Invoicing")
					{
						echo '<label>INVOICE NUMBER</label>';
						echo '<input type="text" id="ircn" name="ircn" placeholder="Enter Invoice No" value="';
						if(isset($_GET['dc'])){
							echo $_GET['dc'];
						}
						else
						{
							$con = mysqli_connect('localhost','root','Tamil','mypcm');
							if(!$con)
								echo "connection failed";
							$q = "select max(inv)+1 as gen from invgen";
							$r = $con->query($q);
							$fch=$r->fetch_assoc();
							echo $fch['gen'];
						}
						echo'"><br><br>';
						
						echo '<label>INVOICED QTY</label>';
						echo '<input type="text" id="tiqty" name="tiqty" readonly="readonly" value="';
						if(isset($_GET['tiqty'])){
							echo $_GET['tiqty'];
						}
						echo'"><br><br>';
						
						echo '<label>VARIANCE</label>';
						echo '<input type="text" id="rem" name="rem" readonly="readonly" value="';
						if(isset($_GET['rem'])){
							echo $_GET['rem'];
						}
						echo'">';
					}
				else if($cat=="To S/C" || $cat=="FG For S/C")
					{
						echo '<label>DC NUMBER</label>';
						echo '<input type="text" id="ircn" name="ircn" placeholder="Enter Input DC NO">';			
					}
				else
					{
						echo"<label>ISSUANCE RCNO</label>";
							$query = "Select rcno from d11 where operation='$cat' order by rcno desc";
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
							echo '<input type="text" id="ircn" name="ircn" value="'. $fstr2.'">';
					}
			}					
			?>
			</div>
			<div style="display:none">
			<label>TOTAL VARIANCE</label>
				<input type="text" id="total1" readonly="readonly" name="total1" value="<?php
				if(isset($_GET['rem']))
				{
					echo $_GET['rem'];				
				}
				/*
				
					*/
				?>
				
				"/>
	</div>
			</br>
			<script>
				function edValueKeyPress2()
				{
						var s = document.getElementById("iss");
						var r = document.getElementById("rem");
						var r1 = document.getElementById("total1");
						r.value = +r1.value - +s.value
						if(r.value>0)
						{
							document.getElementById('submit').style.visibility = 'hidden';
							document.getElementById('sub').style.visibility = 'visible';
						}
						else
						{
							document.getElementById('submit').style.visibility = 'visible';
							document.getElementById('sub').style.visibility = 'hidden';
						}
				}
			</script>
			<div>
				<label>QTY PART ISSUED</label>
					<input type="number" id="iss"  required name="iss" min="1" <?php
					if($sopr==0)
					{
						echo 'readonly';
					}
					?>" max="<?php
					if($sopr>$_GET['rem'])
					{
						echo $_GET['rem'];
					}
					else
					{
						echo $sopr;
					}
					?>" onKeyUp="edValueKeyPress2()" placeholder="Enter the quantity of part issued">
			</div>
			<br>
			<div>
			<?php			
				if(isset($_GET['cat']) && $_GET['cat']=="FG For Invoicing")
				{	
					echo'<input type="Submit" name="sub" id="sub" onclick="myFunction()" value="NEXT ENTRY"/>';
				}
				?>
			</div>
			<div>
			<?php			
				if(isset($_GET['cat']) && $_GET['cat']=="FG For Invoicing") 
				{	
					$inum=$_GET['dc'];
					echo'<br><input type="button" name="sub1" id="sub1"  onclick=window.location="invabort.php?inum='.$inum.'" value="ABORT INVOICE"/>';
				}
				?>
			</div>
			<div style="display:none">
			<label>qty</label>
				<input type="text" id="quty" readonly="readonly" name="quty" value="<?php
				if(isset($_GET['q']))
				{
					echo $_GET['q'];			
				}
				else
				{
					echo 0;
				}
				?>"/>
			</div>
			<div style="display:none">
			<label>ino</label>
				<input type="text" id="ino" readonly="readonly" name="ino" value="<?php
				if(isset($_GET['ino']))
				{
					echo $_GET['ino'];			
				}
				?>"/>
			</div>
			<div style="display:none">
			<label>i</label>
				<input type="text" id="iter" readonly="readonly" name="iter" value="<?php
				if(isset($_GET['i']))
				{
					echo $_GET['i'];			
				}
				?>"/>
			</div>
			</br>
			<div >
					<input type="Submit" name="submit" id="submit" value="EVERYTHING DONE"  onclick="myFunction()"/>
			</div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
			document.getElementById('sub').style.visibility = 'hidden';
        }
}
function hidebtn() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
</script>
		</form>
	</div>
</body>
</html>
