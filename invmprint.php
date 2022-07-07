<?php
$i=0;
$con=mysqli_connect('localhost','root','Tamil','mypcm');
$from = $_POST['from'];
$to = $_POST['to'];
$ccode = $_POST['ccode'];
$printtype = $_POST['printtype'];
$invdate = $_POST['invdt'];
if($from!="" && $to!="" && $printtype=="STATIONARY")
{
	header("location: printphp.php?invno=$from&n=$to");
}
else if($from!="" && $to!="" && $printtype=="PDF" && $invdate>='2021-07-01')
{
	header("location: fpdf\\digprint\inv.php?invno=$from");
}
else if($from!="" && $to!="" && $printtype=="PDF" && $invdate<='2021-06-30')
{
	header("location: fpdf\\digprint\invreprint.php?invno=$from");
}
else
{
	header("location: mprint.php");
}
?>