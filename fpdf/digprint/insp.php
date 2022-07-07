<?php
include "qr/qrlib.php";    
 $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';
	if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
     
// (c) Xavier Nicolay
// Exemple de génération de devis/facture PDF

require('inspstructure.php');

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
	$pdf->SetXY( 10, 18 );
	//$pdf->Image("logo1.png", $pdf->GetX(), $pdf->GetY(), 10 , 12);
	$pdf->COMPANYDETAIL( "VENKATESWARA STEELS & SPRINGS (INDIA) PVT LTD",
					  "\n");
	$pdf->fact_dev( "PACKING ", "INFO" );
	//$pdf->temporaire( "V S S I P L" );
	//$pdf->addDate( $filename);
	$pdf->SetXY( 70, 22 );
	$filename = $PNG_TEMP_DIR.'test.png';
	$filename = $PNG_TEMP_DIR.'test'.md5("12345".'|'."Q".'|'."1").'.png';
	QRcode::png("scode:inv:qty:mfg:part:issue:partname:ubef", $filename, "Q", "1", 2);   
	$pdf->Image($PNG_WEB_DIR.basename($filename), $pdf->GetX(), $pdf->GetY(), 33.78);
	//$pdf->addClient("CL01");
	//$pdf->addPageNumber("1");
	//$pdf->PONUMBER($fch['cpono']);
	//$pdf->PODATE($fch['cpodt']);
	//$pdf->LINENO($fch['poino']);
	//$pdf->INVOICENUMBER($fch['invno']);
	//$pdf->INVOICEDATE($fch['invdt']." @ ".$fch['invt']);
	//$pdf->VENDORCODE($fch['vc']);
	//$pdf->EWAY("N/A");
	$pdf->DELIVERYADDRESS( "SUPPLIER CODE : SCODE",
					  "INVOICE NO         : INV"."\n" .
					  "QUANTITY            : QTY"."\n".
					  "MFG DATE            : DATE"."\n");
	//$pdf->addClientAdresse("Ste\nM. XXXX\n3ème étage\n33, rue d'ailleurs\n75000 PARIS");
	$pdf->SHIPPINGADDRESS( "PART NO              : PART",
					  "ISSUE NO             : ISSUE"."\n" .
					  "PART NAME         : PART NAME"."\n".
					  "USE BEFORE       : UBEF"."\n");
	/*$cols=array( "S.No"    => 10,
				 "PART NO / NAME / HSNC"  => 40,
				 "PRICE / PER"  => 20,
				 "QTY / UOM"     => 20,
				 "PACK / VALUE"      => 26,
				 "VALUE" => 30,
				 "CGST / VALUE"          => 22,
				 "SGST / VALUE" => 22);
	$pdf->addCols( $cols);
	$cols=array( "S.No"    => "C",
				 "PART NO / NAME / HSNC"  => "L",
				 "PRICE / PER"  => "C",
				 "QTY / UOM" => "C",
				 "PACK / VALUE"=> "R",
				 "VALUE" => "R",
				 "CGST / VALUE" => "R",
				 "SGST / VALUE"  => "R" );
	$pdf->addLineFormat( $cols);
	//$pdf->addLineFormat($cols);

	$y    = 105;
	$line = array( "S.No"    => "1",
				   "PART NO / NAME / HSNC"  => $fch['pn']."\n" .
									 $fch['pd']."\n" .
									 $fch['hsnc'],
					"PRICE / PER"  => $fch['rate']."\n" .
									 $fch['per']."\n",
				   "QTY / UOM"     => $fch['qty']."\n" .
									 $fch['uom']."\n",
				   "PACK / VALUE"=> $fch['pc']."\n" .
									 $fch['pcamt']."\n",
				   "VALUE" => $fch['taxgoods'],
				   "CGST / VALUE" => $fch['cigst']."\n" .
									 $fch['cigstamt']."\n",
				   "SGST / VALUE"=> $fch['sgst']."\n" .
									 $fch['sgstamt']."\n", );
	$size = $pdf->addLine( $y, $line );
	$y   += $size + 2;
	$line = array( "S.No"    => "2",
				   "PART NO / NAME / HSNC"  => "29192689\n" .
									 "SPRING\n" .
									 "7320",
					"PRICE / PER"  => "23.62\n" .
									 "100\n",
				   "QTY / UOM"     => "5000\n" .
									 "Nos\n",
				   "PACK / VALUE"=> "1.5 %\n" .
									 "600.25\n",
				   "VALUE" => "1540.00",
				   "CGST / VALUE" => "9 %\n" .
									 "600.25\n",
				   "SGST / VALUE"=> "9 %\n" .
									 "600.25\n", );
	
	$pdf->TABLE2($fch['inwords'],$fch['inwords1'],$fch['taxtotal'],$fch['totcigstamt'],$fch['totsgstamt'],$fch['invtotal']);
	
	
	$pdf->TRANSMODE($fch['transmode']);
	$pdf->DISTANCE($fch['distance']);
	$pdf->TRANSPORTS($fch['mot']);
	$pdf->VEHICLE($fch['vehicle']);
	//$pdf->addTVAs( $params, $tab_tva, $tot_prods);
	$pdf->REMARK();
	$pdf->AUTHSIGN();
	*/
}
$pdf->Output();
?>