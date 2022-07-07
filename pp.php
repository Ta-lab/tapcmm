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
$connector = new FilePrintConnector($port);
$printer = new Printer($connector);

/* Information for the receipt */
$items = array(
    new item("Example item #1", "4.00"),
    new item("Another thing", "3.50"),
    new item("Something else", "1.00"),
    new item("A final item", "4.45"),
);
$subtotal = new item('Subtotal', '12.95');
$tax = new item('A local tax', '1.30');
$total = new item('Total', '14.25', true);
/* Date is kept the same for testing */
// $date = date('l jS \of F Y h:i:s A');
$date = "Monday 6th of April 2015 02:56:25 PM";
$s=1;$t1=0;$t2=0;$t3=34;$t4=68;$t5=26;$t6=26;$t7=7;$t8=15;$t9=4;$t10=4;$t11=4;$t12=9;$t13=10;$t14=10;$t15=10;$t16=13;$t17=11;$t18=11;$t19=2;$t20=15;$t21=1;$t22=1;$t23=10;
/* Start the printer */
$printer = new Printer($connector);

/* Print top logo */
//$printer -> setJustification(Printer::JUSTIFY_CENTER);
$invno=$_GET['invno'];
$iter=$_GET['n'];
$inv="";
$itn=0;
for($inv=$invno;$inv<=($invno+$iter);$inv++)
{
	$itn=$itn+1;
	$q = "select * from inv_det where invno='$inv'";
	$r = $con->query($q);
	$fch=$r->fetch_assoc();
	/* Name of shop */
	$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
	$printer -> setEmphasis(true);
	$s=fspace($fch['invno'],$t3)."\n\n";
	$printer -> text($s);
	$printer -> selectPrintMode();
	$printer -> setEmphasis(true);
	$s=fspace($fch['invdt'],$t4)."\n";
	$printer -> text($s);
	$s=fspace($fch['invt'],$t4)."\n\n";
	$printer -> text($s);
	$s=addlines("",5);
	$printer -> text($s);
	$s=leftalign($fch['cname'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtname'],$t6);
	$printer -> text($s);
	$s=fspace($fch['cpono'],$t7)."\n";
	$printer -> text($s);
	$s=leftalign($fch['cname1'],$t5);
	$printer -> text($s);
	$s=addlines("",1);
	$printer -> text($s);
	$s=fspace($fch['cpodt'],$t4)."\n";
	$printer -> text($s);
	$s=leftalign($fch['cadd1'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtadd1'],$t6)."\n";
	$printer -> text($s);
	$s=leftalign($fch['cadd2'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtadd2'],$t6)."\n";
	$printer -> text($s);
	$s=leftalign($fch['cadd3'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtadd3'],$t6);
	$printer -> text($s);
	$s=fspace($fch['mot'],$t7)."\n";
	$printer -> text($s);
	$s=leftalign($fch['cgstno'],$t5);
	$printer -> text($s);
	$s=leftalign(" ".$fch['dtgstno'],$t6)."\n";
	$printer -> text($s);
	$s=fspace($fch['vc'],$t4)."\n";
	$printer -> text($s);
	$s=addlines("",3);
	$printer -> text($s);
	$s=fspace($fch['cori'],59)."\n";
	$printer -> text($s);
	$s=addlines("",3);
	$printer -> text($s);
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
	$s=fspace($fch['r5'],$t12)."\n";
	$printer -> text($s);
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
}
/* Cut the receipt and open the cash drawer */

$printer -> close();

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
header("location: inputlink.php");