<?php
$servername = "localhost";
$username = "root";
$password = "Tamil";

$conn = new mysqli($servername, $username, $password, "mypcm");

require_once('PHPExcel.php');
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$style = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);

$query = "SELECT * from d13";
$result = $conn->query($query);

	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'RCNO');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'PRCNO');
	$objPHPExcel->getActiveSheet()->getStyle("A1:B1")->applyFromArray($style);
	
$rowCount = 2;
while($row = $result->fetch_assoc()){
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['rcno']);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['prcno']);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount)->applyFromArray($style);
    $rowCount++;
}


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('some_excel_file'.date("Y-m-d").'.xlsx');

echo "Report generated";
?>