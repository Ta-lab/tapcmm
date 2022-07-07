<?php
if(isset($_POST['submit']))
{
	$t=$_POST['code'];
	$f=date("Y-m-d");
	$dt='0000-00-00';
	$servername = "localhost";
	$username = "root";
	$password = "Tamil";
	$conn = new mysqli($servername, $username, $password, "mypcm");
	require_once('PHPExcel.php');
	$objPHPExcel = new PHPExcel();
	
	$objPHPExcel->setActiveSheetIndex(0);
	$sheet = $objPHPExcel->getActiveSheet();
	//$objWorkSheet = $objPHPExcel->createSheet("OPEN ROUTE CARDS IN CNC AREA");
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $f);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' E WAY BILL REPORT FOR '.$t);
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'SUPPLY TYPE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'SUB TYPE');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'DOC TYPE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'DOC NO');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'DOC DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'OTHER PARTY NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'GST NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'ADDRESS 1');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'ADDRESS 2');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'ADDRESS 3');
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'PIN CODE');
		$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'STATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'PRODUCT');
		$objPHPExcel->getActiveSheet()->SetCellValue('N2', 'DESCRIPTION');
		$objPHPExcel->getActiveSheet()->SetCellValue('O2', 'HSN');
		$objPHPExcel->getActiveSheet()->SetCellValue('P2', 'UNIT');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q2', 'QUANTITY');
		$objPHPExcel->getActiveSheet()->SetCellValue('R2', 'ACCESSABLE VALUE');
		$objPHPExcel->getActiveSheet()->SetCellValue('S2', 'TAX RATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('T2', 'CGST AMOUNT');
		$objPHPExcel->getActiveSheet()->SetCellValue('U2', 'SGST AMOUNT');
		$objPHPExcel->getActiveSheet()->SetCellValue('V2', 'IGST AMOUNT');
		$objPHPExcel->getActiveSheet()->SetCellValue('W2', 'TOTAL AMOUNT');
		$objPHPExcel->getActiveSheet()->SetCellValue('X2', 'TRANS MODE');
		$objPHPExcel->getActiveSheet()->SetCellValue('Y2', 'DISTANCE LEVEL');
		$objPHPExcel->getActiveSheet()->SetCellValue('Z2', 'TRANS NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('AA2', 'TRANS ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('AB2', 'TRANS DOC NO');
		$objPHPExcel->getActiveSheet()->SetCellValue('AC2', 'TRANS DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('AD2', 'VEHICLE NO');
		$objPHPExcel->getActiveSheet()->getStyle("A2:AC2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:AC2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'AD'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "select * from inv_det where invdt='$f' and vehicle='$t'";
	echo "select * from inv_det where invdt='$f' and vehicle='$t'";
	$result = $conn->query($query);
	$tt = date('d-m-Y');
	//$tt = date('d-m-Y',strtotime('-1 days'));
	while($row = $result->fetch_assoc())
	{
		$d = date("d-m-Y", strtotime($row['invdt']));
		if($d==$tt && $row['vehicle']==$t)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "OUTWARD");
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "SUPPLY");
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "INVOICE");
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['invno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['invdt']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['cname'].$row['cname1']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['cgstno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['cadd1']);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['cadd2']);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['cadd3']);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, "");
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, "");
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['pn']);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $row['pd']);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $row['hsnc']);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, "NUMBER");
			$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $row['qty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $row['taxgoods']);
			$tmp=substr($row['cigst'],0,strlen($row['cigst'])-2);
			$tmp1=substr($row['cigst'],0,strlen($row['sgst'])-2);
			$tmp=$tmp+$tmp1;
			$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $tmp);
			$tax=0;
			if($row['sgstamt']=="")
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, "0");
				$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, "0");
				$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $row['cigstamt']);
				$tax=$row['cigstamt'];
			}
			else
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $row['cigstamt']);
				$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $row['sgstamt']);
				$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, "0");
				$tax=$row['cigstamt']+$row['sgstamt'];
			}
			$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, ($row['taxgoods']+$tax));
			$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $row['transmode']);
			$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $row['distance']);
			$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $row['mot']);
			$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, "");
			$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, "");
			$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $row['invdt']);
			$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount, $row['vehicle']);
			$rowCount++;
		}
	}
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K".$rowCount.":L".$rowCount.":M".$rowCount.":N")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("E WAY");
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$objWriter->save('D:\REPORT\Eway-'.$t.' @ '.date("Y-m-d").'.xlsx');
	//copy('D:\REPORT\Eway-'.$t.' @ '.date("Y-m-d").'.xlsx', '\\\server\D\VSS E WAY\Eway-'.$t.' @ '.date("Y-m-d").'.xlsx');
	copy('D:\REPORT\Eway-'.$t.' @ '.date("Y-m-d").'.xlsx', '\\\192.168.1.103\c\VSS E WAY\Eway-'.$t.' @ '.date("Y-m-d").'.xlsx');
	header("location: inputlink.php");
}
?>