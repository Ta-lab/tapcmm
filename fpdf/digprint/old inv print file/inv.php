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
if(isset($_GET['invno']) && $_GET['invno']!="")
{
	$inv=$_GET['invno'];
}
mysqli_query($con,"UPDATE inv_det set print='T',type='2' where invno='$inv'");
$query1 = "select * from inv_det where invno='$inv'";
$result1 = $con->query($query1);
$fch = mysqli_fetch_array($result1);
$cc=$fch['ccode'];
$pn=$fch['pn'];
$querycp = "select DISTINCT ino from invmaster where ccode='$cc' AND pn='$pn'";
$resultcp = $con->query($querycp);
$fchcp = mysqli_fetch_array($resultcp);

$temprc="";
function track($rcno)
{
	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	$ret = $con->query("select stkpt,prcno,d12.pnum,d12.rcno,partissued,rmissqty,closedate,d11.date from d12 join d11 on d11.rcno=d12.rcno where d12.rcno='$rcno' and (partissued!='' or rmissqty!='')");
	$c= $ret->num_rows;
	$row2 = mysqli_fetch_array($ret);
	do{
		if($c==0)
		{			
			break;
		}
		if(substr($row2['rcno'],0,1)=="A")
		{
			$q=$row2['rmissqty'];
			$u="Kgs";
		}
		else
		{
			$q=$row2['partissued'];
			$u="Nos";
		}
		$temp=$row2['rcno'];
		$ret1 = $con->query("select date,operation,SUM(qtyrejected) as qr FROM d12 where prcno='$temp' AND operation!=''");//QUALITY QUERY
		$c1= $ret1->num_rows;
		if($c1==0)
		{
			$d="";
			$o="";
			$rq="";
		}
		else
		{
			$row3 = mysqli_fetch_array($ret1);
			$d=$row3['date'];
			$o=$row3['operation'];
			$rq=$row3['qr'];
		}
		if($row2['stkpt']!="To S/C" && $row2['stkpt']!="From S/C")
		{
			
		}
			if(substr($row2['rcno'],0,1)=="A")
			{
				$query4 = "select DISTINCT heat from d12 where rcno='$temp'";
				$result4 = $con->query($query4);
				$row4 = mysqli_fetch_array($result4);
				$tempgrn=$row4['heat'];
				$temp=$row2['rcno'];
				if ($tempgrn!="" && strpos($GLOBALS['temprc'], $tempgrn ) !== false) {
					
				}
				else
				{
					if($GLOBALS['temprc']=="")
					{
						$GLOBALS['temprc']=$tempgrn;
					}
					else
					{
						$GLOBALS['temprc']=$GLOBALS['temprc'].','.$tempgrn;
					}
				}
			}
			track($row2['prcno']);
	}while($row2 = $ret->fetch_assoc());
}
track($inv);
$batch=$temprc;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
for($i=0;$i<4;$i++)
{
	$pdf->AddPage();
	$pdf->SetXY( 10, 19 );
	$pdf->Image("logo1.png", $pdf->GetX(), $pdf->GetY(), 16 , 16);
	$pdf->COMPANYDETAIL( "VENKATESWARA STEELS & SPRINGS (INDIA) PVT LTD",
					  "1/89-6 Ravathur Pirivu, Kannampalayam, Sulur, Coimbatore-641402\n" .
					  "Tel : 0422-2680840 ; mail : info@venkateswarasteels.com\n".
					  "PAN : AACCV3065F ; GST : 33AACCV3065F1ZL\n");
	$pdf->fact_dev( "TAX ", "INVOICE" );
	//$pdf->temporaire( "V S S I P L" );
	//$pdf->addDate( $filename);
	$pdf->SetXY( 160, 15 );
	$filename = $PNG_TEMP_DIR.'test.png';
	$filename = $PNG_TEMP_DIR.'test'.md5("12345".'|'."Q".'|'."1").'.png';
	QRcode::png($fch['vc'].":".$fch['pn'].": ".$fchcp['ino']." : ".$batch." : ".$fch['qty'].":".$fch['invno'], $filename, "Q", "1", 2);   
	$pdf->Image($PNG_WEB_DIR.basename($filename), $pdf->GetX(), $pdf->GetY(), 33.78);
	//$pdf->addClient("CL01");
	//$pdf->addPageNumber("1");
	$pdf->PONUMBER($fch['cpono']);
	$pdf->PODATE($fch['cpodt']);
	$pdf->LINENO($fch['poino']);
	$pdf->INVOICENUMBER($fch['invno']);
	$pdf->INVOICEDATE($fch['invdt']." @ ".$fch['invt']);
	$pdf->VENDORCODE($fch['vc']);
	$pdf->EWAY("COPY ATTACHED");
	$pdf->DELIVERYADDRESS( $fch['cname'].$fch['cname1'],
					  $fch['cadd1']."\n" .
					  $fch['cadd2']."\n".
					  $fch['cadd3']."\n".
					  $fch['cgstno']."\n");
	//$pdf->addClientAdresse("Ste\nM. XXXX\n3ème étage\n33, rue d'ailleurs\n75000 PARIS");
	$pdf->SHIPPINGADDRESS( $fch['dtname'].$fch['dtname1'],
					  $fch['dtadd1']."\n" .
					  $fch['dtadd2']."\n".
					  $fch['dtadd3']."\n".
					  $fch['dtgstno']."\n");
	$cols=array( "S.No"    => 10,
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
}
$pdf->Output();
?>