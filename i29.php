<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	header("Location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="Quality" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="CUSTOMER COMPLAINT";
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
	<link rel="stylesheet" type="text/css" href="design.css">
</head>
<body>
<div style="float:right">
	<a href="index.html"><label>Logout</label></a>
</div>
<div style="float:left">
	<a href="inputlink.php"><label>Back to Menu</label></a>
</div>
<div  align="center"><br>
	<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
</div>
	<h4 style="text-align:center"><label>CUSTOMER CAPA-LOG </label></h4>
<div class="divclass">
<form method="POST" action="i29db.php">			
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
<label>CUSTOMER NAME</label>
<select name ="names" id="names">
<?php			
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
echo "connection failed";
$query = "select distinct cname from invmaster";
$result = $con->query($query);
echo"<option value=''>Select Customer</option>";
while ($row = mysqli_fetch_array($result)) 
{
if($_GET['names']==$row['cname'])
echo "<option selected value='" . $row['cname'] . "'>" . $row['cname'] ."</option>";

else								
echo "<option value='" . $row['cname'] . "'>" . $row['cname'] ."</option>";


}	
?>
</select>
</div>
</br>
<div>
<label>PART NUMBER</label>
<datalist id="languages" >
<?php
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
echo "connection failed";
$query = "SELECT distinct pn as pnum FROM invmaster";
$result = $con->query($query);
echo"<option value=''>Select one</option>";
while ($row = mysqli_fetch_array($result)) 
{
if($_GET['pnum']==$row['pnum'])
echo "<option selected value='".$row['pnum']."'>".$row['pnum']."</option>";
else
echo "<option value='".$row['pnum']."'>".$row['pnum']."</option>";
}
?>
</datalist>
<input type="text" id="pnum" name="pnum" placeholder="Enter Part Number" list="languages" >
</div>
<br>
<div>
<label>DEFECT CATEGORY</label>
<select name ="categories" id="categories">
<?php			
$con = mysqli_connect('localhost','root','Tamil','myqc');
if(!$con)
echo "connection failed";
$query = "SELECT distinct defect FROM defect_cat where for_type='Customer'";
$result = $con->query($query);
echo"<option value=''>Select one</option>";
while ($row = mysqli_fetch_array($result)) 
{
echo "<option value='".$row['defect']."'>".$row['defect']."</option>";
}
?>
</select>
</div>
<br>
<div>
<label>CAPA-LOG NO</label>
<input type="text" id="capa" name="capa" placeholder="Enter CAPA-LOG no" autocomplete="off"/>
</div>
<br>
<div>
<label>DOC_REFERENCE NO</label>
<input type="text" id="doc_ref" name="doc_ref" placeholder="Enter Document reference no" autocomplete="off"/>
</div>
<br>
<div>
<label>PROBLEM DESCRIPTION</label>
<input type="text" id="prob_description" name="prob_description" placeholder="What is the problem?" autocomplete="off"/>
</div>
<br>
<div>
<label>QUANTITY REJECTED</label>
<input type="text" id="qtyrejected" name="qtyrejected" placeholder="How much?" autocomplete="off"/>
</div>
<br>
<div>
<label>REMARKS</label>
<input type="text" id="rmk" name="rmk" placeholder="Anything else?" autocomplete="off"/>
</div>
<br>
<br>			
<div>
<input type="Submit" name="submit" value="SUBMIT">
</div>
</form>
</div>
</body>
</html>