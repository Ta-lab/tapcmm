<?php
include "qr/qrlib.php";    
 $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';
	if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
     
// (c) Xavier Nicolay
// Exemple de génération de devis/facture PDF

require('invoice1.php');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//DATA FROM MYSQL
$con = mysqli_connect('localhost','root','Tamil','mypcm');
$inv="U11819-18906";
$query1 = "select * from inv_det where invno='$inv'";
$result1 = $con->query($query1);
$fch = mysqli_fetch_array($result1);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
for($i=0;$i<1;$i++)
{
	$pdf->AddPage();
	$pdf->SetXY( 10, 19 );
	$pdf->SetXY( 110, 15 );
	$filename = $PNG_TEMP_DIR.'test.png';
	$filename = $PNG_TEMP_DIR.'test'.md5("12345".'|'."Q".'|'."1").'.png';
	QRcode::png("100283:29666514:33652145:11:U11819-18906:180", $filename, "Q", "1", 2);   
	$pdf->Image($PNG_WEB_DIR.basename($filename), $pdf->GetX(), $pdf->GetY(), 33.78);
	$pdf->SetXY( 16, 15 );
	$pdf->DELIVERYADDRESS( "PART NUMBER : 29666514",
					  "HEAT NUMBER : 33652145"."\n" .
					  "ISSUE NUMBER : 11"."\n".
					  "INVOICE NUMBER : U11819-18906"."\n".
					  "QUANTITY : 180"."\n");
	//$pdf->addClientAdresse("Ste\nM. XXXX\n3ème étage\n33, rue d'ailleurs\n75000 PARIS");
}
$pdf->Output();
?>