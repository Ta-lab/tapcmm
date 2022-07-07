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
	if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="INVOICE PART OR CUST INSERT";
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
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
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
	<h4 style="text-align:center"><label>INVOICE MASTER UPDATION [ I22 ]</label></h4>
	<div>
			
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
		<script>
			function reload(form)
			{
				var p4 = document.getElementById("p4").value;
				self.location=<?php echo"'i22.php?code='"?>+p4;
			}
		</script>
	<div class="divclass">
		<form id="form" name="form" method="post" action="i22db.php">
			</br>
			<datalist id="codelist" >
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
			<div class="find">
				<label>CUSTOMER CODE</label>
				<input type="text" style="width:50%; background-color:white;" class='s' onchange=reload(this.form) id="p4" name="cc" list="codelist" value="<?php if(isset($_GET['code'])){echo $_GET['code'];}?>">
			</div>
			<br>
			<br>
			<?php
				$con = mysqli_connect('localhost','root','Tamil','mypcm');
				if(!$con)
					echo "connection failed";
					if(isset($_GET['code']) && $_GET['code']!='')
					{
						$t1=$_GET['code'];
						$result1 = $con->query("select distinct cname,cadd1,cadd2,cadd3,cgstno,dtname,dtadd1,dtadd2,dtadd3,dtgstno,vc,ccode,transmode,distance,supplytypecode,city,pincode,state from invmaster where ccode='$t1'");
						if($result1->num_rows==0)
						{
							echo '<script language="javascript">';
							echo 'alert("Master Not Found")';
							echo '</script>';
						}
						else if($result1->num_rows>1)
						{
							echo '<script language="javascript">';
							echo 'alert("More than one rows are there in MASTER")';
							echo '</script>';
						}
						else
						{
							$row = mysqli_fetch_array($result1);
							
							$cname = $row['cname'];
							$get_customer_pan = $con->query("SELECT * FROM `tcs` WHERE cname='$cname'");
							$pan_no = mysqli_fetch_array($get_customer_pan);
							
							echo '<h4 style="color:white;text-align:center"><label>MASTER DATA FOR THE ABOVE CUSTOMER INFO</label></h4>
							<div id="stylized" class="myform">
								<div class="column">
									<label>Customer Name: </label>
									<input type="text" name="cname" id="cname" value="'.$row['cname'].'"/>
								</div>
								<div class="column">
									<label>Delivery Name: </label>
									<input type="text" name="dtname" id="dtname" value="'.$row['dtname'].'"/>
								</div>
								<div class="column">
									<label>CCODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="ccode" id="ccode" value="'.$row['ccode'].'"/>
								</div>
								<div class="column">
									<label>Cust Address1: </label>
									<input type="text" name="cadd1" id="cadd1" value="'.$row['cadd1'].'"/>
								</div>
								<div class="column">
									<label>Deli Address1: </label>
									<input type="text" name="dtadd1" id="dtadd1" value="'.$row['dtadd1'].'"/>
								</div>
								<div class="column">
									<label>Cust GSTno&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="cgstno" id="cgstno" value="'.$row['cgstno'].'"/>
								</div>
								<div class="column">
									<label>Cust Address2: </label>
									<input type="text" name="cadd2" id="cadd2" value="'.$row['cadd2'].'"/>
								</div>
								<div class="column">
									<label>Deli Address2: </label>
									<input type="text" name="dtadd2" id="dtadd2" value="'.$row['dtadd2'].'"/>
								</div>
								<div class="column">
									<label>Deli GSTno&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="dtgstno" id="dtgstno" value="'.$row['dtgstno'].'"/>
								</div>
								<div class="column">
									<label>Cust Address3: </label>
									<input type="text" name="cadd3" id="cadd3" value="'.$row['cadd3'].'"/>
								</div>
								<div class="column">
									<label>Deli Address3: </label>
									<input type="text" name="dtadd3" id="dtadd3" value="'.$row['dtadd3'].'"/>
								</div>
								<div class="column">
									<label>Vendor Code&nbsp;&nbsp;: </label>
									<input type="text" name="vc" id="vc" value="'.$row['vc'].'"/>
								</div>
								<div class="column">
									<label>MODE OF TRANS: </label>
									<input type="text" name="tm" id="tm" value="'.$row['transmode'].'"/>
								</div>
								<div class="column">
									<label>DISTANCE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="dis" id="dis" value="'.$row['distance'].'"/>
								</div>
								<div class="column">
									<label>PAN NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
									<input type="text" name="pan_no" id="pan_no" value="'.$pan_no['pan_no'].'"/>
								</div>
								<div class="column">
									<label>SUPPLY TYPE CODE&nbsp;&nbsp;&nbsp; : </label>
									<input type="text" name="supplytypecode" id="supplytypecode" value="'.$row['supplytypecode'].'"/>
								</div>
								<div class="column">
									<label>LOCATION&nbsp;&nbsp;&nbsp;&nbsp; :</label>
									<input type="text" name="city" id="city" value="'.$row['city'].'"/>
								</div>
								<div class="column">
									<label>PINCODE &nbsp;&nbsp;&nbsp;&nbsp; :</label>
									<input type="text" name="pincode" id="pincode" value="'.$row['pincode'].'"/>
								</div>
								<div class="column">
									<label>STATE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : </label>
									<input type="text" name="state" id="state" value="'.$row['state'].'"/>
								</div>
								<br><br><br><br><br><br><br><br><br><br><br>
								<div>
									<input type="submit" name="submit" value="UPDATE">
								</div>
								<div class="column1">
								</form>
							</div>';
						}
					}
			?>	
	</body>
</html>
		