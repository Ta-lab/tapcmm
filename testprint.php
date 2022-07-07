<?php
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
/* Open the printer; this will change depending on how it is connected */
$con = mysqli_connect('localhost','root','Tamil','mypcm');
$result1 = $con->query("SELECT * from invprinter");
$row = mysqli_fetch_array($result1);
system("NET USE LPT2 /d");
system("NET USE LPT2 \\\\192.168.1.184\\TVS /PERSISTENT:YES");
$port=$row['fip'];
$connector = new FilePrintConnector($port);

$printer = new Printer($connector);
$printer -> setEmphasis(true);
$printer -> text("Hello world...");

$printer->setTextSize(5,6);
$printer -> setEmphasis(true);
$printer -> text("Hello world...\n");

$img = EscposImage::load("E:\data.png");
$printer -> graphics($img);

$printer -> close();
?>