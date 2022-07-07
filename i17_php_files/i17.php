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
	if($_SESSION['access']=="Stores" || $_SESSION['access']=="Semifinished1" || $_SESSION['access']=="Semifinished2" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="TAKING RETURN MATERIAL";
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
	<link rel="stylesheet" type="text/css" href="mystyle1.css">
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
		<h4 style="text-align:center"><label>RETURN OF RAW MATERIAL</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action='i171.php'>	
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
				<label>TO</label>
					<select name ="to" required id="to" onchange="reload3(this.form)">
					<option value="">Select one</option>";	
						<?php			
								if($_GET['to']=="A")
								{
									echo "<option selected value='" .$_GET['to']. "'>Stores</option>";
									echo "<option value=B>Semifinished1</option>
									<option value='C'>Semifinished2</option>";
								}
								else if($_GET['to']=="B")
								{
									echo "<option value=A>Stores</option>";
									echo "<option selected value='" .$_GET['to']. "'>Semifinished1</option>
									<option value='C'>Semifinished2</option>";
								}
								else if($_GET['to']=="C")
								{
									echo "<option value=A>Stores</option>
									<option value='B'>Semifinished1</option>";
									echo "<option selected value='" .$_GET['to']. "'>Semifinished2</option>";
								}
								else
								{
									echo "<option value=A>Stores</option>";
									echo "<option value=B>Semifinished1</option>
									<option value='C'>Semifinished2</option>";
								}
							echo "</select></h1>";
						?>
						<script>
						function reload3(form)
						{
							var val=form.to.options[form.to.options.selectedIndex].value; 
							var s = document.getElementById("tdate").value;
							self.location='i17.php?tdate='+s+'&to=' + val ;
					}
					</script>
			</div>
			<br>
			<div>
				<label>RCNO</label>
					<select name ="rcno" id="rcno" required onchange="reload1(this.form)">
					<option value="">Select one</option>";	
						<?php
							if($_GET['to'])
							{
								$date=0000-00-00;
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								if($_GET['to']=="A")
								{
									//select rcno from d11 where d11.rcno like 'A%' and closedate='0000-00-00' AND d11.rcno NOT IN (select d11.rcno from d11 JOIN d12 ON d12.prcno=d11.rcno where d11.rcno like 'A%' and closedate='0000-00-00')
									$query = "select rcno from d11 where rcno like 'A20%' and closedate='0000-00-00'";
									$result = $con->query($query);
								}
								if($_GET['to']=="B")
								{
									$query = "select rcno from d11 where rcno like 'B20%' and closedate='0000-00-00'";
									$result = $con->query($query);
								} 
								if($_GET['to']=="C")
								{
									$query = "select rcno from d11 where rcno like 'C20%' and closedate='0000-00-00'";
									$result = $con->query($query);
								} 
								while ($row = mysqli_fetch_array($result)) 
								{
									if($_GET['rat']==$row['rcno'])
										echo "<option selected value='" . $row['rcno'] . "'>" . $row['rcno'] ."</option>";
									else
										echo "<option value='" . $row['rcno'] . "'>" . $row['rcno'] ."</option>";              
								}
							}
						?>
						</select></h1>
						<script>
						function reload1(form)
						{
							var val=form.rcno.options[form.rcno.options.selectedIndex].value; 
							var s = document.getElementById("tdate").value;
							var to = document.getElementById("to").value;
							self.location='i17.php?tdate='+s+'&to='+to+'&rat=' + val ;
					}
					</script>
			</div>
			<br>
			<div>
				<label>PART NUMBER</label>
					<input type="text" id="pnum" readonly='readonly' name="pnum" value="<?php			
						$na="";
							if(isset($_GET['rat'])) 
							{
								$rat=$_GET['rat'];
								$query = "SELECT pnum FROM d11 where rcno='".$rat."'";
								$result = mysqli_query($con,$query);
								$temp1=mysqli_fetch_array($result);
								$np=$temp1['pnum'];
								echo $temp1['pnum'];
							}
						?>"/>
			</div>
			<br>
			<div>
				<label>RAW MATERIAL</label>
					<input type="text" id="rm" readonly='readonly' name="rm" value="<?php			
						$na="";
							if(isset($_GET['rat'])) 
							{
								$rat=$_GET['rat'];
								$query = "SELECT rm FROM d12 where rcno='".$rat."'";
								$result = mysqli_query($con,$query);
								$temp1=mysqli_fetch_array($result);
								$na=$temp1['rm'];
								echo $temp1['rm'];
							}
						?>
					"/>
			</div>
			<br>
			<div>
				<label>AVAILABLE QTY</label>
					<input type="text" id="avl" readonly='readonly' name="avl" value="<?php			
							$na='';$iq=0;
							if(isset($_GET['rat'])) 
							{
								$rat=$_GET['rat'];
								if($_GET['to']=="A")
								{
									$query = "SELECT rm,pnum,rmissqty FROM d12 where rcno='".$rat."'";
									$result = mysqli_query($con,$query);
									$temp1=mysqli_fetch_array($result);
									$na=$temp1['rm'];
									$an=$temp1['pnum'];
									$q=$temp1['rmissqty'];
									$iq=$temp1['rmissqty'];
									$query = "SELECT useage FROM m13 where pnum='".$an."' and rmdesc='".$na."'";
									$result = mysqli_query($con,$query);
									$temp1=mysqli_fetch_array($result);
									$u=$temp1['useage'];
									$query = "SELECT sum(partreceived)+sum(qtyrejected) as qty FROM d12 where pnum='".$an."' and prcno='".$rat."'";
									$result = mysqli_query($con,$query);
									$temp1=mysqli_fetch_array($result);
									$r=$temp1['qty'];
									$q=$q-($r*$u);
									echo $q;
									$s=$q;
								}
								else
								{
									$query = "SELECT sum(partissued) as i FROM d12 where rcno='".$rat."'";
									$result = mysqli_query($con,$query);
									$temp1=mysqli_fetch_array($result);
									$i=$temp1['i'];
									$iq=$temp1['i'];
									$query = "SELECT sum(partreceived)+sum(qtyrejected) as r FROM d12 where prcno='".$rat."'";
									$result = mysqli_query($con,$query);
									$temp1=mysqli_fetch_array($result);
									$r=$temp1['r'];
									echo $i-$r;
								}
							}
						?>"/>
			</div>
			<br>
			
			<div>
			<label>ROUTE CARD QTY</label>
				<input type="text" id="tw" readonly="readonly" name="tw" value="<?php
				if(isset($_GET['rat']))
				{
					echo round($iq,2);				
				}
				else
				{
					echo round(0,2);				
				}
				?>"/>
			</div>
			<br>
			<div>
				<label>UOM</label>
					<input type="text" id="uom" readonly='readonly' name="uom" value="<?php
							if(isset($_GET['rat']))
							{
								$query = "SELECT rm FROM d12 where rcno='".$rat."' and rm!=''";
								$result = mysqli_query($con,$query);
								$temp1=mysqli_fetch_array($result);
								$na=$temp1['rm'];
								$query1 = "SELECT uom FROM m13 where rmdesc='".$na."'";
								$result6 = mysqli_query($con,$query1);
								$temp3=mysqli_fetch_array($result6);
								echo $temp3['uom'];
							}	
						?>
					"/>
			</div>
			<br>
			<div>
				<label>RETURNED QTY</label>
					<input type="text" required min="1" max="2000" onKeyUp="edValueKeyPress2()" id="ret" name="ret" placeholder="Enter the Quantity RETURN"/>
			</div>
			<script>
				function edValueKeyPress2()
				{
					var r = document.getElementById("ret");
					var i = document.getElementById("avl");
					var iq = document.getElementById("tw");
					var rmk = document.getElementById("rmk");
					var v=0;
					v= ((i.value-r.value) * 100) / iq.value;
					//rmk.value = v;
					if (v > 5)
					{
						document.getElementById('cls').style.display = 'none';
					}
					else if(v < -5)
					{
						document.getElementById('cls').style.display = 'none';
					}
					else
					{	
						document.getElementById('cls').style.display = 'block';
					}
				}
			</script>
			<br>
		<div>
				<label>REMARK </label>
					<input type="text" id="rmk" required name="rmk" placeholder="Enter the Remarks"/>
			</div>
		<br>
			<div>
				<label name='option'>CLOSURE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>		
					<input type="radio" id="cls"  name="close" value="<?php echo date("Y-m-d", strtotime($_GET['tdate']));?>"><label>Yes</label></input>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;<input type="radio" id="cls1"  name="close" value = "<?php echo date('0000-00-00');?>"><label>No</label></input>
			</div>
			
			
			</br>
			<div>
					<input type="Submit" name="submit" id="submit"  value="SUBMIT" onclick="myFunction()"/>
			</div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
</script>
		</form>
	</div>
	</div>
			

</body>
</html>