<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="SELECTING RC FOR DC WITHOUT INVOICE";
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
	<h4 style="text-align:center"><label>DC WITHOUT INVOICE TO CUSTOMER</label></h4>

	<div class="divclass">
		
		<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		if(isset($_POST['submit'])){
			
			$iss = $_POST['iss'];
			//$ino = $_POST['ino'];
			//$i = $_POST['iter'];
			$dcdate = date("Y-m-d");
			$dcnum = $_POST['dcnum'];
			//$dt="DC-".$dcnum;
			$stkpt = $_POST['operation'];
			$ccode = $_POST['cc'];
			$pnum = $_POST['pnum'];
			$dcqty = $_POST['tiqty'];
			$mot = $_POST['mot'];
			$prcno = $_POST['prcno'];
			
			mysqli_query($con,"INSERT INTO d11(operation,date,rcno,pnum,issue,receive,reject,closedate) VALUES('$stkpt','$dcdate','$dcnum','$pnum','','','','$dcdate')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$dcdate','$stkpt','$pnum','$prcno','$dcnum','$iss','$u','$ip')");
			mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$dcnum','$prcno')");
		
			header("Location: inputlink.php");
		}
		?>
		
		<?php
		if(isset($_POST['sub']))
		{
			$con=mysqli_connect('localhost','root','Tamil','mypcm');
			if(!$con)
				die(mysqli_error());
			
			$iss = $_POST['iss'];
			//$ino = $_POST['ino'];
			//$i = $_POST['iter'];
			$dcdate = date("Y-m-d");
			$dcnum = $_POST['dcnum'];
			//$dt="DC-".$dcnum;
			$stkpt = $_POST['operation'];
			$ccode = $_POST['cc'];
			$pnum = $_POST['pnum'];
			$dcqty = $_POST['dcqty'];
			$mot = $_POST['mot'];
			$prcno = $_POST['prcno'];
			$rem = $_POST['rem'];
			
			$result = $con->query("SELECT * FROM `pn_st` WHERE pnum='$pnum'");
			$row = mysqli_fetch_array($result);
			$p=$row['invpnum'];
			mysqli_query($con,"INSERT INTO d11(operation,date,rcno,pnum,issue,receive,reject,closedate) VALUES('$stkpt','$dcdate','$dcnum','$pnum','','','','$dcdate')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$dcdate','$stkpt','$pnum','$prcno','$dcnum','$iss','$u','$ip')");
			mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$dcnum','$prcno')");
			
			header("location: newinv2dc_rc.php?dcdate=$dcdate&rcno=$prcno&stkpt=$stkpt&pnum=$pnum&dcnum=$dcnum&dcqty=$dcqty&rem=$rem");
		}
		?>
		
		
		<form method="POST" action='newinv2dc_rc.php'>	
			<div>
				<label for="operation">STOCKING POINT</label>
					<?php
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
						if(!$con)
							echo "connection failed";
						$result = $con->query("SELECT operation FROM m11 where opertype='stocking point' and operation != 'Stores' and operation='FG For Invoicing'");
						echo "<select name ='operation' id='operation'>";
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
			
			</br>
			
			<div>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" name ="pnum" id="pnum" readonly="readonly" value="<?php
				if(isset($_GET['pnum']))
				{
					echo $_GET['pnum'];
				}
				?>"/>
			</div>
			
			<br>
			
			<div>
			<label>DC NUMBER</label>
			<input type="text" list="combo-options" name ="dcnum" id="dcnum" readonly="readonly" value="<?php
				if(isset($_GET['dcnum']))
				{
					echo $_GET['dcnum'];
				}
				?>"/>
			</div>
			
			<br>
			
			<div>
				<label>Previous RCNO</label>
				<select name ="prcno" id="prcno"  onchange="reload2(this.form)">
						<?php	
							if(isset($_GET['pnum'])) 
							{
								echo"<option value=''>Select one</option>";
								$cat=$_GET['stkpt'];
								$mat=$_GET['pnum'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								$query1 = "SELECT operation,opertype,issue from M11 where opertype='stocking point' order by issue";
								$result1 = $con->query($query1);
								$row1 = mysqli_fetch_array($result1);
								$r="A";
								$query2 = "SELECT prcno as rcno,stkpt,qty FROM (SELECT d12.date,prcno,stkpt,SUM(partreceived)-SUM(partissued) as qty FROM d12 WHERE prcno IN (SELECT rcno FROM d11 WHERE pnum='$mat' AND rcno LIKE '_20%') GROUP BY prcno,stkpt HAVING qty>0) AS T WHERE T.stkpt='FG For Invoicing'";
																			
								$result2 = $con->query($query2);
								while ($row2 = mysqli_fetch_array($result2)) 
								{
									if($_GET['rcno']==$row2['rcno'])
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
						var stkpt = document.getElementById("operation").value;
						var pnum = document.getElementById("pnum").value;
						var dcnum = document.getElementById("dcnum").value;
						var prcno = document.getElementById("prcno").value;
						var dcqty = document.getElementById("dcqty").value;
						var rem = document.getElementById("rem").value;
						self.location=<?php echo"'newinv2dc_rc.php?stkpt='"?>+stkpt+'&pnum='+pnum+'&dcnum='+dcnum+'&rcno='+prcno+'&dcqty='+dcqty+'&rem='+rem;
					}
					
				</script>
			<div>
			<label>AVALABLE QTY</label> 
					<input type="text" name="available"  required readonly="readonly" value="<?php 
						if(isset($_GET['rcno']) && $_GET['rcno']!="") 
						{
							$mat=$_GET['rcno'];
							//$rat=$_GET['rat'];
							$cat=$_GET['stkpt'];
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
			<label>DC QTY</label>
			<input type="text" list="combo-options" name ="dcqty" id="dcqty" readonly="readonly" value="<?php
				if(isset($_GET['dcqty']))
				{
					echo $_GET['dcqty'];
				}
				?>"/>
			</div>
			
			<br>
			
			<div>
			<label>VARIANCE</label>';
				<input type="text" id="rem" name="rem" readonly="readonly" value="<?php
				if(isset($_GET['rem']))
				{
					echo $_GET['rem'];
				}
				?>"/>
			</div>
			
			<br>
			
			<div style="display:none">
			<label>TOTAL VARIANCE</label>
				<input type="text" id="total1" readonly="readonly" name="total1" value="<?php
				if(isset($_GET['rem']))
				{
					echo $_GET['rem'];				
				}
				?>
				
				"/>
			</div>
			
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
				<input type="Submit" name="sub" id="sub" onclick="myFunction()" value="NEXT ENTRY"/>
			</div>
			
			<br>
			
			<div>
				<?php 
				if(isset($_GET['dcnum']))
				{
					$dc = $_GET['dcnum'];
					//echo $dc;
				}
				?>
				<input type="button" name="but" id="but" value="ABORT DC" onclick="<?php echo "window.location='dcabort.php?dc=$dc'";?>">
			<div>
			
			<br>
			
			<div>
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
			
		
		
		
		
	</div>

</body>
</html>
