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
$port=$row['fip'];
system("NET USE $port /d");
system("NET USE $port \\\\192.168.1.13\\EPSON /PERSISTENT:YES");
//$port=$row['pname'];
//system("NET USE $port /d");
//system("NET USE $port \\\\192.168.1.102\\TVSMSP24 /PERSISTENT:YES");
$t1=40;$t2=15;$t3=3;$t4=30;$t5=7;$t6=6;$t7=9;
$connector = new FilePrintConnector($port);
$printer = new Printer($connector);
$con = mysqli_connect('localhost','root','Tamil','purchasedb');
$po=$_POST['from'];
mysqli_query($con,"UPDATE poinfo set print='T' where ponum='$po'");
$result1 = $con->query("SELECT * from poinfo where ponum='$po'");
$row1 = mysqli_fetch_array($result1);
$result2 = $con->query("SELECT * from pogoodsinfo where ponum='$po' ORDER BY sno");

$printer -> selectPrintMode();
$printer -> setEmphasis(true);
$s=addlines("",8);
$printer -> text($s);

$s=leftalign("",$t1);
$printer -> text($s);
$s=leftalign($row1['ponum'],$t2);
$printer -> text($s);
$printer -> selectPrintMode();
$printer -> setEmphasis(true);
$s=fspace(date('d-m-Y', strtotime($row1['podate'])),$t5);
$printer -> text($s."\n");

$s=leftalign($row1['sname'],$t1);
$printer -> text($s."\n");
//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
//$printer -> setEmphasis(true);


$s=leftalign($row1['sadd1'],$t1);
$printer -> text($s);
$s=leftalign($row1['indentno'],$t2);
$printer -> text($s);
if($row1['indentdate']=='0000-00-00')
{
	$s="";
}
else
{
	$s=fspace(date('d-m-Y', strtotime($row1['indentdate'])),$t5);
}
$printer -> text($s."\n");
$s=leftalign($row1['sadd2'],$t1);
$printer -> text($s."\n");


$s=leftalign($row1['sadd3'],$t1);
$printer -> text($s."\n");
$s=leftalign($row1['sgstno'],$t1);
$printer -> text($s);
$s=leftalign($row1['qno'],$t2);
$printer -> text($s);
if($row1['qdate']=="0000-00-00")
{
	$s="";
}
else
{
	$s=fspace(date('d-m-Y', strtotime($row1['qdate'])),$t5);
}
$printer -> text($s."\n\n\n");

$s=leftalign("",$t1);
$printer -> text($s);
$s=leftalign($row1['ptype'],$t2);
$printer -> text($s);
$s=fspace($row1['pterm'],$t5-5);
$printer -> text($s."\n\n");
$s=leftalign("",$t1);
$printer -> text($s);
$s=leftalign($row1['cpname'],$t2);
$printer -> text($s);
$s=fspace($row1['phone'],$t5-5);
$printer -> text($s."\n");
$s=addlines("",4);
$printer -> text($s);
$temp=0;$total=0;
while($row2 = mysqli_fetch_array($result2))
{
	$s=leftalign($row2['sno'],$t3+1);
	$printer -> text($s);
	$s=leftalign($row2['description2'],$t4);
	$printer -> text($s);
	$s=leftalign($row2['hsnc'],$t5);
	$printer -> text($s);
	if($row2['duedate']=="0000-00-00")
	{
		$s=leftalign("",$t5+1);
	}
	else
	{
		$s=leftalign(date('d-m-y', strtotime($row2['duedate'])),$t5+1);
	}
	$printer -> text(" ".$s);
	$s=leftalign($row2['quantity'],$t6);
	$printer -> text(" ".$s);
	$s=leftalign($row2['uom'],$t3);
	$printer -> text(" ".$s);
	$s=rightalign($row2['rate'],$t6-1);
	$printer -> text(" ".$s);
	$s=rightalign($row2['total'],$t7+1);
	$printer -> text($s);
	$total=$total+$row2['total'];
	$temp=$temp+1;
	$s=addlines("",1);
	$printer -> text($s);
}
$temp=18-$temp;
$s=addlines("",$temp);
$printer -> text($s);

$whole = floor($total);
$fraction = $total - $whole;
$fraction=$fraction*100;
$rup=ROUND($fraction);
$pai=floor($total);
if(strtoupper(numtostrfn($rup)==""))
{
	$a=strtoupper(numtostrfn($pai))."ONLY";
	$amtstr=$a;
}
else
{
	$a=strtoupper(numtostrfn($pai))."AND PAISE ".strtoupper(numtostrfn($rup)."ONLY");
}

$t1="";
$a1="";
$a2="";
$a3="";
$c=0;	
for($i=0;$i<strlen($a);$i++)
{
	if($a[$i]==" ")
	{
		$c=$c+1;
		if($c==8)
		{
			$a1=$t1.$a[$i];
			$t1="";
		}
		if($c==16)
		{
			$a2=$t1.$a[$i];
			$t1="";
		}
	}
	$t1=$t1.$a[$i];
}

$s=leftalign("RUPEES :".$a1,48);
$printer -> text($s);

$s=rightalign(fspace(number_format((float)($total),2, '.', ''),19),10);
$printer -> text($s."\n");
$s=leftalign($t1,50);
$printer -> text($s);

$s=addlines("",2);
$printer -> text($s);

$s=leftalign("Terms :",14);
$printer -> text($s);
$s=leftalign("",18);
$printer -> text($s);
$s=leftalign("SHEDULE DETAILS :",25);
$printer -> text("  ".$s."\n");

$s=leftalign("Packing",14);
$printer -> text($s);
$s=leftalign($row1['packing'],18);
$printer -> text(": ".$s);
$s=leftalign($row1['sl1'],43);
$printer -> text($s."\n");

$s=leftalign("PAYMENT TERMS",14);
$printer -> text($s);
$s=leftalign($row1['pterm'],18);
$printer -> text(": ".$s);
$s=leftalign($row1['sl2'],43);
$printer -> text($s."\n");

$s=leftalign("DESPATCH MODE",14);
$printer -> text($s);
$s=leftalign($row1['dmode'],18);
$printer -> text(": ".$s);
$s=leftalign($row1['sl3'],43);
$printer -> text($s."\n");
if($row1['ig']=="" || $row1['ig']=="0")
{
	$s=leftalign("CGST",14);
	$printer -> text($s);
	$s=leftalign($row1['cg'],18);
	$printer -> text(": ".$s);
}
else
{
	$s=leftalign("IGST",14);
	$printer -> text($s);
	$s=leftalign($row1['ig'],18);
	$printer -> text(": ".$s);
}
$s=leftalign($row1['sl4'],43);
$printer -> text($s."\n");
if($row1['ig']=="" || $row1['ig']=="0")
{
	$s=leftalign("SGST",14);
	$printer -> text($s);
	$s=leftalign($row1['sg'],18);
	$printer -> text(": ".$s);
}
else
{
	$s=leftalign("",14);
	$printer -> text($s);
	$s=leftalign("",18);
	$printer -> text("  ".$s);
}
$s=leftalign($row1['remark'],43);
$printer -> text($s."\n");

$printer -> close();

header("location: inputlink.php");