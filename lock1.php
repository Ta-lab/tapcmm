<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<link rel="stylesheet" type="text/css" href="designlink.css">
	<link rel="stylesheet" type="text/css" href="design.css">
	<link rel="icon" href="./img/fav_icon.png" type="image/png" sizes="16x16">
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


<?php
		$s="";
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		$time=date("Y-m-d");
		mysqli_query($con,"DELETE FROM admin1 where temp='1' and date!='$time'");
		$querydwm = "SELECT daily from autoreport";
		$resultdwm = $con->query($querydwm);
		$row = mysqli_fetch_array($resultdwm);
		if($row['daily']!=$time)
		{
			$t=date("Y-m");
			header("location: info.php?dm=$t");
		}
		session_start();
		if(isset($_SESSION['user']))
		{
			header("location: inputlink.php");
		}
?>
<script>
function myFunction() {
    window.alert("Enter the Valid User Name and Password");
}
</script>


	<div align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br>
	
	<label style="color:yellow";><p align="center" id="msg"></p></label>
	
	<div><button onclick="window.location.href='morethan30daysstk.php'">Click To View</button></div>
	</div>
	
	
	
	<div>
		<form class="formcenter" action="link.php" method="post">
			<label>USER ID : </label>
			<input type="text" name="user" placeholder="Enter User ID" <?php echo $s ?> required title="If you are not registered please contact Mr.prabahar" autocomplete="off"/><br><br>
			<label>PASSWORD :</label>
			<input type="password" name="pass" placeholder="Enter Password" <?php echo $s ?> autocomplete="off"/><br><br>
			<input type="submit" name="sub" value="Login"/><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label style="color:yellow;"><p align="center" id="msg"></p></label>
		</form>
	</div>
	
	

	
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><marquee scrollamount="10"  onmouseover="this.stop();" onmouseout="this.start();"><h3><label style="color:pink; ";><p id="status"></p></label></h3></marquee>
<script>
var txt1 = <?php
	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	if(!$con)
		die(mysqli_error());
	$query = "SELECT m12.operation,COUNT(*) as c FROM `d11` LEFT JOIN m12 ON d11.pnum=m12.pnum WHERE d11.operation='Stores' AND closedate='0000-00-00' AND (m12.operation='CNC Machine' || m12.operation='Straitening/Shearing') GROUP BY m12.operation";
	$result = $con->query($query);
	$row = mysqli_fetch_array($result);
	if($row['operation']=="CNC Machine")
	{
		$cnc=$row['c'];
	}
	$row = mysqli_fetch_array($result);
	if($row['operation']=="Straitening/Shearing")
	{
		$sas=$row['c'];
	}
	//echo "'NUMBER OF A ROUTE CARD IN CNC + SHEET CUTTING AREA  : '+".$cnc."+' *** '+";
	//echo "'NUMBER OF A ROUTE CARD IN CNC AREA IS : '+".$cnc."+' & SHEET CUTTING AREA IS '+".$sas."+' *** '+";
	$dt=date('Y-m-d',strtotime('-8 days'));
	$result1 = mysqli_query($con,"SELECT T1.foreman,IF(b IS NULL,0,b) as BRC,IF(c IS NULL,0,c) as CRC,IF(e IS NULL,0,e) as ERC FROM (SELECT foreman,COUNT(*) AS b FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE d11.rcno LIKE 'B20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman) AS T1 LEFT JOIN (SELECT foreman,COUNT(*) AS c FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE d11.rcno LIKE 'C20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman) AS T2 ON T1.foreman=T2.foreman LEFT JOIN (SELECT foreman,COUNT(*) AS e FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` JOIN m13 ON d11.pnum=m13.pnum WHERE d11.rcno LIKE 'E20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman) AS T3 ON T1.foreman=T3.foreman");
	//echo "SELECT T1.foreman,IF(b IS NULL,0,b) as BRC,IF(c IS NULL,0,c) as CRC,IF(e IS NULL,0,e) as ERC FROM (SELECT foreman,COUNT(*) AS b FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE d11.rcno LIKE 'B20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman) AS T1 LEFT JOIN (SELECT foreman,COUNT(*) AS c FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN m13 ON d11.pnum=m13.pnum WHERE d11.rcno LIKE 'C20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman) AS T2 ON T1.foreman=T2.foreman LEFT JOIN (SELECT foreman,COUNT(*) AS e FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` JOIN m13 ON d11.pnum=m13.pnum WHERE d11.rcno LIKE 'E20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman) AS T3 ON T1.foreman=T3.foreman";
	//echo "' NUMBER OF ROUTE CARDS IN MANUAL AREA MORE THAN 7 DAYS ... '+";
	while($row = mysqli_fetch_array($result1))
	{
		$f=$row['foreman'];
		$b=$row['BRC'];
		$c=$row['CRC'];
		$e=$row['ERC'];
		echo "'$f { B_RC($b),C_RC($c),E_RC($e) } *** '+";
	}
	echo "'';";
?>
document.getElementById("status").innerHTML = txt1;
var i = 0;
var txt = <?php
date_default_timezone_set("Asia/Kolkata");
if(isset($_GET['err1']))
{
	switch($_GET['err1']){
	case "1":
        echo "' Message : User name Or Password Entered Invalid';";
        break;
    case "2":
        echo "' Message : Old Password that you entered is wrong. Try again';";
        break;
    case "3":
         echo "' Message : Logout Successfully Done... @ ".date("H:i")." . . THANK  YOU . . ';";
        break;
	case "4":
		$ip=$_GET['ip'];
        echo "' Message : Your Account is Active @ MACHINE : $ip... Or You were not properly logged out.';";
        break;
	case "5":
         echo "' Message : Password Successfully Changed...';";
        break;
	case "6":
         echo "' Message : ERP LOCKED';";
        break;
    case "7":
        echo "' Message : SOME ROUTE CARD HAVING MORE THAN 30 DAYS IN STOCKINGPOINT...PLEASE CLEAR THE ROUTE CARD...';";
		//echo "' <div><a href='logout.php'><label>CLICK TO VIEW</label></div>';";
        break;
    default:
        echo "' Message : WELCOME - Date-Time is ".date("d-m-Y / H:i")."...';";
	}
}
else
{
	//echo "'Please contact Mr.Prabhahar to register your User Account...';";
	echo "' Message : ROUTE CARD VARIATION 5% & ABOVE CANNOT BE CLOSED.';";
}
?>
var speed = 75;
window.onload = typeWriter();
function typeWriter() {
  if (i < txt.length) {
    document.getElementById("msg").innerHTML += txt.charAt(i);
    i++;
    setTimeout(typeWriter, speed);
  }
}
</script>
	</div>
	</div>
</body>
</html>
