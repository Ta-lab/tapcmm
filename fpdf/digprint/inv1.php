<?php
  include "qr/qrlib.php";    
 $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';
	if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
	$filename = $PNG_TEMP_DIR.'test'.md5("12345".'|'."Q".'|'."1").'.png';
    QRcode::png("123456", $filename, "Q", "1", 2);    
// (c) Xavier Nicolay
// Exemple de génération de devis/facture PDF

require('invoice.php');

$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->SetXY( 15, 17 );
//$pdf->Image("logo.png", $pdf->GetX(), $pdf->GetY(), 100 , 20);
$pdf->addSociete( "VENKATESWARA STEELS & SPRINGS (INDIA) PVT LTD",
                  "1/89-6 Ravathur Pirivu, Kannampalayam, Sulur, Coimbatore-641402\n" .
                  "Tel : 0422-XXXXXXX ; mail : ask@venkateswarasteels.com\n".
                  "PAN : XXXXXXXX ; GST : XXXXXXXXXXXXXXX\n");
$pdf->fact_dev( "TAX ", "INVOICE" );
//$pdf->temporaire( "V S S I P L" );
//$pdf->addDate( $filename);
$pdf->SetXY( 145, 25 );
$pdf->Image($PNG_WEB_DIR.basename($filename), $pdf->GetX(), $pdf->GetY(), 30);
//$pdf->addClient("CL01");
//$pdf->addPageNumber("1");
$pdf->addReglement("U11819-12345");
$pdf->addEcheance("03/12/2003 / 14:25");
$pdf->addNumTVA("FR888777666");
$pdf->addNumTVA1("03/12/2003");
$pdf->addNumTVAinv("FR888777666");
$pdf->addNumTVA1invd("03/12/2003");
$pdf->addSocieteforcustomeradd( "VENKATESWARA STEELS & SPRINGS (INDIA) PVT LTD",
                  "1/89-6 Ravathur Pirivu, Kannampalayam, Sulur, Coimbatore-641402\n" .
                  "Tel : 0422-XXXXXXX ; mail : ask@venkateswarasteels.com\n".
                  "PAN : XXXXXXXX ; GST : XXXXXXXXXXXXXXX\n");
//$pdf->addClientAdresse("Ste\nM. XXXX\n3ème étage\n33, rue d'ailleurs\n75000 PARIS");
$pdf->addSocietedeleveryadd( "VENKATESWARA STEELS & SPRINGS (INDIA) PVT LTD",
                  "1/89-6 Ravathur Pirivu, Kannampalayam, Sulur, Coimbatore-641402\n" .
                  "Tel : 0422-XXXXXXX ; mail : ask@venkateswarasteels.com\n".
                  "PAN : XXXXXXXX ; GST : XXXXXXXXXXXXXXX\n");
$cols=array( "S.No"    => 10,
             "PART NUMBER / NAME"  => 40,
             "PRICE / PER"  => 20,
             "QTY / UOM"     => 20,
             "PACK / VALUE"      => 26,
             "VALUE" => 30,
             "CGST / VALUE"          => 22,
			 "SGST / VALUE" => 22);
$pdf->addCols( $cols);
$cols=array( "S.No"    => "C",
             "PART NUMBER / NAME"  => "L",
             "PRICE / PER"  => "C",
             "QTY / UOM" => "C",
             "PACK / VALUE"=> "R",
             "VALUE" => "R",
             "CGST / VALUE" => "R",
             "SGST / VALUE"  => "C" );
$pdf->addLineFormat( $cols);
//$pdf->addLineFormat($cols);

$y    = 110;
$line = array( "S.No"    => "1",
               "PART NUMBER / NAME"  => "29192689\n" .
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
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;
$line = array( "S.No"    => "2",
               "PART NUMBER / NAME"  => "29192689\n" .
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
$size = $pdf->addLine( $y, $line );
//$y   += $size + 2;

/*$line = array( "S.No"    => "REF2",
               "PART NUMBER 1\n"."NAME"  => "Câble RS232",
               "PART NUMBER"  => "Câble RS232",
               "QUANTITE"     => "1",
               "P.U. HT"      => "10.00",
               "MONTANT H.T." => "60.00",
               "TVA"          => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;
*/
$pdf->addCadreTVAs();
        
// invoice = array( "px_unit" => value,
//                  "qte"     => qte,
//                  "tva"     => code_tva );
// tab_tva = array( "1"       => 19.6,
//                  "2"       => 5.5, ... );
// params  = array( "RemiseGlobale" => [0|1],
//                      "remise_tva"     => [1|2...],  // {la remise s'applique sur ce code TVA}
//                      "remise"         => value,     // {montant de la remise}
//                      "remise_percent" => percent,   // {pourcentage de remise sur ce montant de TVA}
//                  "FraisPort"     => [0|1],
//                      "portTTC"        => value,     // montant des frais de ports TTC
//                                                     // par defaut la TVA = 19.6 %
//                      "portHT"         => value,     // montant des frais de ports HT
//                      "portTVA"        => tva_value, // valeur de la TVA a appliquer sur le montant HT
//                  "AccompteExige" => [0|1],
//                      "accompte"         => value    // montant de l'acompte (TTC)
//                      "accompte_percent" => percent  // pourcentage d'acompte (TTC)
//                  "Remarque" => "texte"              // texte
$tot_prods = array( array ( "px_unit" => 600, "qte" => 1, "tva" => 1 ),
                    array ( "px_unit" =>  10, "qte" => 1, "tva" => 1 ));
$tab_tva = array( "1"       => 19.6,
                  "2"       => 5.5);
$params  = array( "RemiseGlobale" => 1,
                      "remise_tva"     => 1,       // {la remise s'applique sur ce code TVA}
                      "remise"         => 0,       // {montant de la remise}
                      "remise_percent" => 10,      // {pourcentage de remise sur ce montant de TVA}
                  "FraisPort"     => 1,
                      "portTTC"        => 10,      // montant des frais de ports TTC
                                                   // par defaut la TVA = 19.6 %
                      "portHT"         => 0,       // montant des frais de ports HT
                      "portTVA"        => 19.6,    // valeur de la TVA a appliquer sur le montant HT
                  "AccompteExige" => 1,
                      "accompte"         => 0,     // montant de l'acompte (TTC)
                      "accompte_percent" => 15,    // pourcentage d'acompte (TTC)
                  "Remarque" => "Avec un acompte, svp..." );
$pdf->addReglementb("");
$pdf->addEcheanceb("TN 66 - DF 1245");
$pdf->addNumTVAb("BD12465");
$pdf->addNumTVA1b("AYYANAR TRANSPORTS");
//$pdf->addTVAs( $params, $tab_tva, $tot_prods);
$pdf->addCadreEurosFrancs();
$pdf->Output();
//$filename="temp/test.pdf";
//$pdf->Output($filename,'F');
?>