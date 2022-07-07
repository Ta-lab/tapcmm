<?php
include "qr/qrlib.php";    
 $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';
	if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
     
// (c) Xavier Nicolay
// Exemple de génération de devis/facture PDF

require('inspreportst.php');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//DATA FROM MYSQL
$con = mysqli_connect('localhost','root','Tamil','mypcm');

$part=$_POST['pnum'];
//$query3 = "SELECT * FROM `invmaster` WHERE pn='$part'";
$query3 = "SELECT * FROM (SELECT * FROM `invmaster` WHERE pn='$part') AS INVMASTER LEFT JOIN (SELECT * FROM `fi_detail` WHERE pnum='$part') AS FIDETAIL ON INVMASTER.ccode=FIDETAIL.ccode";
$result3 = $con->query($query3);
$fch3 = mysqli_fetch_array($result3);

$ccode = $fch3['ccode'];


//$ccode=$_POST['ccode'];
$query1 = "SELECT * FROM `fi_detail` WHERE ccode='$ccode' AND pnum='$part'";
$result1 = $con->query($query1);
$query2 = "SELECT * FROM `invmaster` WHERE ccode='$ccode' AND pn='$part'";
$result2 = $con->query($query2);
$fch1 = mysqli_fetch_array($result2);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
for($i=0;$i<1;$i++)
{
	$pdf->AddPage();
	$pdf->SetXY( 10, 19 );
	//$pdf->Image("logo1.png", $pdf->GetX(), $pdf->GetY(), 16 , 16);
	$pdf->COMPANYDETAIL( "VENKATESWARA STEELS & SPRINGS (INDIA) PVT LTD",
					  "1/89-6 Ravathur Pirivu, Kannampalayam, Sulur, Coimbatore-641402\n" .
					  "Tel : 0422-2680840 ; mail : info@venkateswarasteels.com\n");
	$pdf->fact_dev( "FINAL INSPECTION", " REPORT " );
	$pdf->addformatno( "QA/DI/R/11" );
	//$pdf->temporaire( "V S S I P L" );
	//$pdf->addDate( $filename);
	$pdf->SetXY( 160, 15 );
	//$pdf->addClient("CL01");
	//$pdf->addPageNumber("1");
	$pdf->PARTYCODE($fch1['vc']);
	$pdf->RCNO("");
	$pdf->PARTNO($fch1['pn']." / ".$fch1['ino']);
	$pdf->DAT("");
	//$pdf->addClientAdresse("Ste\nM. XXXX\n3ème étage\n33, rue d'ailleurs\n75000 PARIS");
	/*$pdf->SHIPPINGADDRESS( $fch1['cname'],
					  $fch1['cadd1']."\n" .
					  $fch1['cadd2']."\n".
					  $fch1['cadd3']."\n");
	*/	
	//$pdf->SHIPPINGADDRESS( $fch1['cname']," "."\n");
	$cols=array( "S.No"    => 10,
				 "Characteristics"  => 35,
				 "Drawing Specification"  => 35,
				 "Method Of Checking"     => 30,
				 " 1 " => 14,
				 " 2 " => 14,
				 " 3 " => 14,
				 " 4 " => 14,
				 " 5 " => 14,
				 "Status" => 10);
	$pdf->addCols( $cols);
	$cols=array( "S.No"    => "C",
				 "Characteristics"  => "L",
				 "Drawing Specification"  => "L",
				 "Method Of Checking" => "L",
				 " 1 "=> "C",
				 " 2 "=> "C",
				 " 3 "=> "C",
				 " 4 "=> "C",
				 " 5 "=> "C",
				 "Status"  => "L" );
	$pdf->addLineFormat( $cols);
	//$pdf->addLineFormat($cols);

	$y    = 60;$t=0;
	while($fch = mysqli_fetch_array($result1))
	{
		if($y>60)
		{
			$pdf->drawlinedata($y-2);
		}
		
		$drawspec = str_replace("Â","",$fch['drawspec']);
		
		$line = array( "S.No"    => $fch['s.no'],
				   "Characteristics"  => $fch['chars'],
				   //"Drawing Specification"  => iconv('UTF-8', 'CP1250//TRANSLIT', $fch['drawspec']),
				   //"Drawing Specification"  => $fch['drawspec'],
				   "Drawing Specification"  => iconv('UTF-8', 'CP1250//TRANSLIT//IGNORE', $drawspec),
				   "Method Of Checking"     => $fch['method'],
				   " 1 "=> " ",
				   " 2 "=> " ",
				   " 3 "=> " ",
				   " 4 "=> " ",
				   " 5 "=> " ",
				   "Status"=> " ");
		$size = $pdf->addLine( $y, $line );
		$y   += $size + 3;
		$t=$fch['s.no'];
		if($y>240)
		{
			$query1 = "SELECT * FROM `fi_detail` WHERE ccode='$ccode' AND pnum='$part' and `s.no`>'$t'";
			//echo "SELECT * FROM `fi_detail` WHERE ccode='$ccode' AND pnum='$part' and s.no>'$t'";
			$result1 = $con->query($query1);
			// FOOTER
			$pdf->REMARK("",$fch1['pn']." / ".$fch1['ino'],$fch1['pd'],"","");
			$pdf->AUTHSIGN();
			
			//header
			$pdf->AddPage();
			$pdf->SetXY( 10, 19 );
			//$pdf->Image("logo1.png", $pdf->GetX(), $pdf->GetY(), 16 , 16);
			$pdf->COMPANYDETAIL( "VENKATESWARA STEELS & SPRINGS (INDIA) PVT LTD",
							  "1/89-6 Ravathur Pirivu, Kannampalayam, Sulur, Coimbatore-641402\n" .
							  "Tel : 0422-2680840 ; mail : info@venkateswarasteels.com\n");
			$pdf->fact_dev( "FINAL INSPECTION", " REPORT " );
			$pdf->addformatno( "QA/DI/R/11" );
			//$pdf->temporaire( "V S S I P L" );
			//$pdf->addDate( $filename);
			$pdf->SetXY( 160, 15 );
			//$pdf->addClient("CL01");
			//$pdf->addPageNumber("1");
			$pdf->PARTYCODE($fch1['vc']);
			$pdf->RCNO("");
			$pdf->PARTNO($fch1['pn']." / ".$fch1['ino']);
			$pdf->DAT("");
			//$pdf->addClientAdresse("Ste\nM. XXXX\n3ème étage\n33, rue d'ailleurs\n75000 PARIS");
			/*$pdf->SHIPPINGADDRESS( $fch1['cname'],
							  $fch1['cadd1']."\n" .
							  $fch1['cadd2']."\n".
							  $fch1['cadd3']."\n");
			*/
			//$pdf->SHIPPINGADDRESS( $fch1['cname']," "."\n");
			$cols=array( "S.No"    => 10,
						 "Characteristics"  => 35,
						 "Drawing Specification"  => 35,
						 "Method Of Checking"     => 30,
						 " 1 " => 14,
						 " 2 " => 14,
						 " 3 " => 14,
						 " 4 " => 14,
						 " 5 " => 14,
						 "Status" => 10);
			$pdf->addCols($cols);
			$cols=array( "S.No"    => "C",
						 "Characteristics"  => "L",
						 "Drawing Specification"  => "L",
						 "Method Of Checking" => "L",
						 " 1 "=> "C",
						 " 2 "=> "C",
						 " 3 "=> "C",
						 " 4 "=> "C",
						 " 5 "=> "C",
						 "Status"  => "L");
			$pdf->addLineFormat($cols);
			
			$y= 60;
			$t=0;
			while($fch = mysqli_fetch_array($result1))
			{
				if($y>60)
				{
					$pdf->drawlinedata($y-2);
				}
				
				$drawspec = str_replace("Â","",$fch['drawspec']);
				
				$line = array( "S.No"    => $fch['s.no'],
						   "Characteristics"  => $fch['chars'],
						   //"Drawing Specification"  => iconv('UTF-8', 'CP1250//TRANSLIT', $fch['drawspec']),
						   //"Drawing Specification"  => $fch['drawspec'],
						   "Drawing Specification"  => iconv('UTF-8', 'CP1250//TRANSLIT//IGNORE', $drawspec),
						   "Method Of Checking"     => $fch['method'],
						   " 1 "=> " ",
						   " 2 "=> " ",
						   " 3 "=> " ",
						   " 4 "=> " ",
						   " 5 "=> " ",
						   "Status"=> " ");
				$size = $pdf->addLine( $y, $line );
				$y   += $size + 3;
			}
			break;
		}
	}
	
	
	
	//$pdf->addTVAs( $params, $tab_tva, $tot_prods);
	$pdf->REMARK("",$fch1['pn']." / ".$fch1['ino'],$fch1['pd'],"","");
	$pdf->AUTHSIGN();
}
$pdf->Output();
?>