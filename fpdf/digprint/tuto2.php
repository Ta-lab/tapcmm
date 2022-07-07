<?php
require('../fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
	// Logo
	//$this->Image('logo.png',10,5,40);
	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Move to the right
	$this->Cell(0.1);
	// Title
	$this->Cell(190,8, "Tax Invoice", '1', 0, 'C'); 
	$this->Ln();
}

//LOAD DATA 
function LoadData()
{
    // Read file lines
    //$lines = file($file);
    //$data = array();
    //foreach($lines as $line)
    //    $data[] = explode(';',trim($line));
    //return $data;
}

//TABLE
function ImprovedTable($header, $data)
{
    // Column widths
    $w = array(10, 20, 40, 15 ,15, 12, 12, 15 ,40, 35, 40, 45 ,40, 35, 40, 45 ,40, 35, 40, 45 );
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    /*foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
    }*/
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}


// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$header = array('S.No', 'Part Number Part Description', 'Part Name', 'HSN code' , 'Qty', 'Packing', 'Rate', 'Amount', 'Cgst / Rate', 'Cgst / Amount', 'Sgst / rate', 'Sgst / amount', 'Total');
// Data loading
$pdf = new PDF();
$data = $pdf->LoadData('countries.txt');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,8, "VENKATESWARA STEELS & SPRINGS (INDIA) PVT LTD", '0', 0, 'C');
$pdf->SetFont('Arial','',8);
$pdf->Ln();
$pdf->Cell(190,3, "1/89-6 Ravathur Pirivu, Kannampalayam, Sulur, Coimbatore-641402", '0', 0, 'C');
$pdf->Ln();
$pdf->Cell(190,4, "Tel : 0422-XXXXXXX ;  mail : ask@venkateswarasteels.com", '0', 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,4, "PAN : XXXXXXXX ; GST : XXXXXXXXXXXXXXX", '0', 0, 'C');
$pdf->SetFont('Arial','B',8);
$pdf->Ln();
// Line break
//$pdf->SetFont('Times','',12);
$pdf->ImprovedTable($header,$data);
$pdf->Output();
?>
