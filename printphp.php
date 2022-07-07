<?php
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
require "numtostr.php";
/* Open the printer; this will change depending on how it is connected */
$con = mysqli_connect('localhost','root','Tamil','mypcm');
$result1 = $con->query("SELECT * from invprinter");
$row = mysqli_fetch_array($result1);
$port=$row['pname'];
system("NET USE $port /d");
system("NET USE $port \\\\192.168.1.103\\EPSONFX /PERSISTENT:YES");
$connector = new FilePrintConnector($port);
$printer = new Printer($connector);

$date = "Monday 6th of April 2015 02:56:25 PM";
$s=1;$t1=0;$t2=0;$t3=34;$t4=68;$t5=26;$t6=26;$t7=7;$t8=15;$t9=4;$t10=4;$t11=4;$t12=9;$t13=10;$t14=10;$t15=10;$t16=13;$t17=11;$t18=11;$t19=2;$t20=15;$t21=1;$t22=1;$t23=10;

//$printer -> setJustification(Printer::JUSTIFY_CENTER);
$invno=$_GET['invno'];
$iter=$_GET['n'];
$inv="";
for($i=0;$i<$iter;$i++)
{
	if(strlen($invno)>8)
	{
		$inv=substr($invno,0,7).str_pad((substr($invno,7,5)+$i),5,"0",STR_PAD_LEFT);
	}
	else
	{
		$inv=$invno+$i;
	}
	mysqli_query($con,"UPDATE inv_det set print='T',type='1' where invno='$inv'");
	$query1 = "select * from inv_det where invno='$inv'";
	$result1 = $con->query($query1);
	$fch = mysqli_fetch_array($result1);
	if(strlen($fch['invno'])>8)
	{
		$s="\n";
		$printer -> text($s);
		//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
		$printer -> setEmphasis(true);
		$s=fspace($fch['invno'],$t4-5)."\n";
		$printer -> text($s);
	}
	else
	{
		$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
		$printer -> setEmphasis(true);
		$s=fspace($fch['invno'],34)."\n\n";
		$printer -> text($s);
	}
	$printer -> selectPrintMode();
	$printer -> setEmphasis(true);
	$s=fspace($fch['invdt'],$t4)."\n\n";
	$printer -> text($s);
	$s=fspace($fch['invt'],$t4)."\n";
	$printer -> text($s);
	$s=addlines("",5);
	$printer -> text($s);
	$s=leftalign($fch['cname'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtname'],$t6);
	$printer -> text($s);
	$s=fspace($fch['cpono'],$t7-5)."\n";
	$printer -> text($s);
	$s=leftalign($fch['cname1'],$t5);
	$printer -> text($s);
	$s=leftalign($fch['dtname1'],$t5);
	$printer -> text($s);
	$s=addlines("",1);
	$printer -> text($s);
	$s=leftalign($fch['cadd1'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtadd1'],$t6);
	$printer -> text($s);
	$s=fspace($fch['cpodt'],$t7+9)."\n";
	$printer -> text($s);
	$s=leftalign($fch['cadd2'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtadd2'],$t6)."\n";
	$printer -> text($s);
	$s=leftalign($fch['cadd3'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtadd3'],$t6)."\n";
	$printer -> text($s);
	$s=leftalign($fch['cgstno'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtgstno'],$t6);
	$printer -> text($s);
	$s=fspace($fch['mot'],$t7)."\n\n";
	$printer -> text($s);
	$s=fspace($fch['vc'],$t4)."\n";
	$printer -> text($s);
	$s=addlines("",3);
	$printer -> text($s);
	$s=fspace($fch['cori'],59)."\n";
	$printer -> text($s);
	$s=addlines("",3);
	$printer -> text($s);
	if($fch['sup']=="1")
	{
		$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
		$printer -> setEmphasis(true);
		$s=fspace("SUPPLIMENTARY INVOICE",$t12)."\n";
		$printer -> text($s);
		$printer -> selectPrintMode();
		$printer -> setEmphasis(true);
	}
	$s=leftalign("1 ",$t11);
	$printer -> text($s);
	$s=$fch['pn']." ".$fch['pd']."\n";
	$printer -> text($s);
	$s=fspace("",$t11);
	$printer -> text($s);
	$s=leftalign($fch['hsnc'],$t12);
	$printer -> text($s);
	$s=rightalign($fch['rate'],$t13);
	$printer -> text($s);
	$s=rightalign($fch['qty'],$t14);
	$printer -> text($s);
	$s=rightalign($fch['pc'],$t15);
	$printer -> text($s);
	$s=rightalign($fch['taxgoods'],$t16);
	$printer -> text($s);
	$s=rightalign($fch['cigst'],$t17);
	$printer -> text($s);
	$s=rightalign($fch['sgst'],$t18)."\n";
	$printer -> text($s);
	$s=fspace("",$t11);
	$printer -> text($s);
	$s=leftalign($fch['poino'],$t12);
	$printer -> text($s);
	$s=rightalign($fch['per'],$t13);
	$printer -> text($s);
	$s=rightalign($fch['uom'],$t14);
	$printer -> text($s);
	$s=rightalign($fch['pcamt'],$t15);
	$printer -> text($s);
	$s=fspace("",$t16);
	$printer -> text($s);
	$s=rightalign($fch['cigstamt'],$t17);
	$printer -> text($s);
	$s=rightalign($fch['sgstamt'],$t18)."\n";
	$printer -> text($s);
	$s=addlines("",5);
	$printer -> text($s);
	$s=fspace($fch['r1'],$t12)."\n";
	$printer -> text($s);
	$s=fspace($fch['r2'],$t12)."\n";
	$printer -> text($s);
	$s=fspace($fch['r3'],$t12)."\n";
	$printer -> text($s);
	$s=fspace($fch['r4'],$t12)."\n";
	$printer -> text($s);
	if($fch['sup']=="0")
	{
		$s=fspace($fch['r5'],$t12)."\n";
		$printer -> text($s);
	}
	
	$s=addlines("",13);
	$printer -> text($s);
	$s=fspace(rightalign($fch['taxgoods'],$t16),44);
	$printer -> text($s);
	$s=rightalign($fch['totcigstamt'],$t17);
	$printer -> text($s);
	$s=rightalign($fch['totsgstamt'],$t18)."\n";
	$printer -> text($s);
	$s=addlines("",1);
	$printer -> text($s);
	$s=fspace(rightalign($fch['invtotal'],$t16),44)."\n";
	$printer -> text($s);
	$s=addlines("",1);
	$printer -> text($s);
	$s=fspace(strtoupper($fch['inwords']),$t23)."\n";
	$printer -> text($s);

	$s=fspace(strtoupper($fch['inwords1']),$t23)."\n";
	$printer -> text($s);
	$printer -> feed();
	$s=addlines("",14);
	$printer -> text($s);
	$s=addlines("",1);
	$printer -> setLineSpacing(6);
	$printer -> text($s);
	$printer -> text($s);
	$printer -> setLineSpacing(7);
	$printer -> text($s);
	$printer -> setLineSpacing(null);
	$inv=1;
}

$printer -> close();
header("location: inputlink.php");