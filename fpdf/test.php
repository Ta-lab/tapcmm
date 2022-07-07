<?php
require('fpdf.php');

$pdf = new FPDF();
$pdf->AddFont('Calligrapher','','calligra.php');
$pdf->AddPage();
$pdf->SetFont('Calligrapher','',35);
$pdf->Write(10,'Enjoy new fonts with FPDF!');
$pdf->Output();
?>