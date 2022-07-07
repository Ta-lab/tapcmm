<?php
$conn=mysqli_connect('localhost','root','Tamil','storedb');
$con=mysqli_connect('localhost','root','Tamil','mypcm');
require_once('PHPExcel.php');

$d=date("Y-m-d");
if(isset($_GET['f']) && isset($_GET['tt']) && $_GET['f']!='' && $_GET['tt'])
{
	$f=$_GET['f'];
	$tt=$_GET['tt'];
}
else
{
	
}
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
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $d);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:E1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' STORES OPENING STOCK AS ON '.$f);
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'GRN NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'SUPPLIER NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'RM DESCRIPTION');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'QUANTITY IN STORES');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UOM');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'G'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "SELECT receipt.date,receipt.grnnum,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed,SUM(inspdb.quantityaccepted)-IF(T.qty IS NULL,0,T.qty) AS stock FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<'$f' GROUP BY inv) AS T ON receipt.grnnum=T.inv WHERE date<'$f' AND (closed>='$f' OR closed='0000-00-00') GROUP BY grnnum HAVING accepted>0";
	//echo "SELECT receipt.date,receipt.grnnum,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed,SUM(inspdb.quantityaccepted)-IF(T.qty IS NULL,0,T.qty) AS stock FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<'$f' GROUP BY inv) AS T ON receipt.grnnum=T.inv WHERE date<'$f' AND (closed>='$f' OR closed='0000-00-00') GROUP BY grnnum	HAVING accepted>0";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['grnnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['sname']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['rmdesc']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['stock']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Kgs');
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("STORES OPEN ON $f");
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(1);
	$objPHPExcel->setActiveSheetIndex(1);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $f);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' STORES RECEIPT BETWEEN '.$f.' TO '.$tt);
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'GRN NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'SUPPLIER NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'RM DESCRIPTION');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'RECEIPT IN STORES');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UOM');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'O'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "SELECT inspdate,receipt.grnnum,quantityaccepted,rmdesc,sname FROM `inspdb` LEFT JOIN receipt ON inspdb.grnnum=receipt.grnnum WHERE  inspdate>='$f' AND inspdate<='$tt'";
	//echo "SELECT inspdate,receipt.grnnum,quantityaccepted,rmdesc,sname FROM `inspdb` LEFT JOIN receipt ON inspdb.grnnum=receipt.grnnum WHERE  inspdate>='$f' AND inspdate<='$tt'";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['inspdate']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['grnnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['sname']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['rmdesc']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['quantityaccepted']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Kgs');
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K".$rowCount.":L".$rowCount.":M".$rowCount.":N")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("STORES RECEIPT");
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(2);
	$objPHPExcel->setActiveSheetIndex(2);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $f);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' STORES ISSUANCE BETWEEN '.$f.' TO '.$tt);
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'RCNO');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'RMDESC');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'GRN NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'UOM');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'H'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "SELECT date,rm,pnum,rcno,rmissqty,inv FROM `d12` WHERE date>='$f' AND date<='$tt' AND rmissqty!=''";
	//echo "SELECT date,rm,pnum,rcno,rmissqty,inv FROM `d12` WHERE date>='$f' AND date<='$tt' AND rmissqty!=''";
	$result = $con->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['rcno']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['rm']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['inv']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['rmissqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, 'Kgs');
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K".$rowCount.":L".$rowCount.":M".$rowCount.":N")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("STORES ISSUANCE");
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(3);
	$objPHPExcel->setActiveSheetIndex(3);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $f);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' STORES CLOSING STOCK ON '.$tt);
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'GRN NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'SUPPLIER NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'RM DESCRIPTION');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'QUANTITY IN STORES');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UOM');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'H'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "SELECT receipt.date,receipt.grnnum,part_number,receipt.rmdesc,sname,quantity_received as inwarded,SUM(inspdb.quantityaccepted) as accepted,SUM(inspdb.quantityrejected) as rejected,T.qty as used,datediff(NOW(),receipt.date) as age,closed,SUM(inspdb.quantityaccepted)-IF(T.qty IS NULL,0,T.qty) AS stock FROM `receipt` LEFT JOIN inspdb ON inspdb.grnnum=receipt.grnnum LEFT JOIN (SELECT inv,SUM(mypcm.d12.rmissqty) as qty FROM mypcm.d12 WHERE inv!='' AND date<='$tt' GROUP BY inv) AS T ON receipt.grnnum=T.inv WHERE date<='$tt' AND (closed>='$tt' OR closed='0000-00-00') GROUP BY grnnum HAVING accepted>0";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['grnnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['sname']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['rmdesc']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['stock']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Kgs');
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K".$rowCount.":L".$rowCount.":M".$rowCount.":N")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("STORES CLOSING STOCK");
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$objWriter->save('\\\server\Share Documents\Narayanan\S_REPORT\Stores-'.date("Y-m-d").'.xlsx');
	$objWriter->save('D:\TEST\STORES-'.date("Y-m-d").'.xlsx');
	
	// STORES COMPLETED
	
	// OPERATION WISE STOCK SUMMARY DETAILS..
	$operq = "SELECT name FROM `m15` WHERE type='OPERATION' ORDER BY fno";
	$operr = $con->query($operq);
	while($oper = mysqli_fetch_array($operr))
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet(0);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $d);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:E1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', $oper['name'].'OPENING STOCK ON '.$f);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'OPERATION NAME');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ROUTE CARD NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'STOCK QTY');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'STOCK IN KG');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'G'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		$query = "SELECT rcno,operation,OPENRC.pnum,issqty,received,rejected,used,notused,bom1,bom2,rate,per,IF(rcno LIKE 'A20%',notused,notused*IF(bom2 IS NULL,bom1/2,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<'2018-10-17' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<'2018-10-17' AND (d11.closedate>='2018-10-17' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum WHERE operation='$oper[name]'";
		//echo "SELECT rcno,operation,OPENRC.pnum,issqty,received,rejected,used,notused,bom1,bom2,rate,per,IF(rcno LIKE 'A20%',notused,notused*IF(bom2 IS NULL,bom1/2,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<'2018-10-17' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<'2018-10-17' AND (d11.closedate>='2018-10-17' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum WHERE operation='$oper[name]'";
		$result = $con->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['notused']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['kg']);
			$rowCount++;
		}
		
		
		$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->setTitle($oper['name']." ON $f");
		/********************************************************************************************************************************************************************************************************************************/
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $d);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:E1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', $oper['name'].' RECEIVED STOCK BETWEEN '.$f.' TO '.$tt);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ROUTE CARD NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ISSUED QTY');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'ISSUED QTY IN KG');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'G'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		$query = "SELECT date,name,rcno,OPENRC.pnum,qty,bom1,bom2,IF(rcno LIKE 'A20%',qty,qty*IF(bom2 IS NULL,bom1 / 2,bom2)) AS kg,fno FROM (SELECT date,name,rcno,pnum,qty,fno FROM (SELECT ISSUE.date,stkpt,ISSUE.rcno,IF(stkpt='Stores',ISSUE.pnum,d11.pnum) AS pnum,qty FROM (SELECT date,stkpt,rcno,prcno,pnum,rm,rmissqty+partissued AS qty FROM `d12` WHERE date>='$f' AND date<='$tt' AND (partissued!='' OR rmissqty!='') AND stkpt!='FG For Invoicing') AS ISSUE LEFT JOIN d11 ON ISSUE.prcno=d11.rcno) AS DB LEFT JOIN m15 ON DB.stkpt=m15.prev) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum WHERE name='$oper[name]'";
		//echo "SELECT date,name,rcno,OPENRC.pnum,qty,bom1,bom2,IF(rcno LIKE 'A20%',qty,qty*IF(bom2 IS NULL,bom1 / 2,bom2)) AS kg,fno FROM (SELECT date,name,rcno,pnum,qty,fno FROM (SELECT ISSUE.date,stkpt,ISSUE.rcno,IF(stkpt='Stores',ISSUE.pnum,d11.pnum) AS pnum,qty FROM (SELECT date,stkpt,rcno,prcno,pnum,rm,rmissqty+partissued AS qty FROM `d12` WHERE date>='$f' AND date<='$tt' AND (partissued!='' OR rmissqty!='') AND stkpt!='FG For Invoicing') AS ISSUE LEFT JOIN d11 ON ISSUE.prcno=d11.rcno) AS DB LEFT JOIN m15 ON DB.stkpt=m15.prev) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum WHERE name='$oper[name]'";
		$result = $con->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['qty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['kg']);
			$rowCount++;
		}
		
		
		$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->setTitle($oper['name']." RECEIVED");
		/********************************************************************************************************************************************************************************************************************************/
		$objPHPExcel->createSheet(2);
		$objPHPExcel->setActiveSheetIndex(2);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $d);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:E1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', $oper['name'].'REJECTION STOCK BETWEEN '.$f.' TO '.$tt);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION NAME');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ROUTE CARD NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'QTY REJCTED');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'BOM');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'REJ IN KG');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'G'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		$query = "SELECT REJ.date,prcno,REJ.pnum,qtyrejected,name,fno,useage,qtyrejected*useage AS rejected FROM (SELECT d12.date,d11.operation,name,prcno,d12.pnum,qtyrejected,fno FROM `d12` LEFT JOIN d11 ON d12.prcno=d11.rcno LEFT JOIN m15 ON d11.operation=m15.prev WHERE d12.date>='$f' AND d12.date<='$tt' AND qtyrejected!='') AS REJ LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS bom ON REJ.pnum=bom.pnum WHERE name='$oper[name]'";
		//echo "SELECT REJ.date,prcno,REJ.pnum,qtyrejected,name,fno,useage,qtyrejected*useage AS rejected FROM (SELECT d12.date,d11.operation,name,prcno,d12.pnum,qtyrejected,fno FROM `d12` LEFT JOIN d11 ON d12.prcno=d11.rcno LEFT JOIN m15 ON d11.operation=m15.prev WHERE d12.date>='$f' AND d12.date<='$tt' AND qtyrejected!='') AS REJ LEFT JOIN (SELECT DISTINCT pnum,useage FROM m13) AS bom ON REJ.pnum=bom.pnum WHERE name='$oper[name]'";
		
		$result = $con->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['prcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['qtyrejected']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['useage']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['rejected']);
			$rowCount++;
		}
		
		
		$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->setTitle($oper['name']." REJ ");
		/********************************************************************************************************************************************************************************************************************************/
		
		$objPHPExcel->createSheet(3);
		$objPHPExcel->setActiveSheetIndex(3);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $d);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:E1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', $oper['name'].' ISSUANCE BETWEEN '.$f.' TO '.$tt);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'RCNO');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'HANDED OVER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'BOM');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'HANDED OVER IN KG');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'G'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		$query = "SELECT date,stkpt,STST.pnum,prcno,partreceived,bom1,bom2,IF(bom2 IS NULL,bom1/2,bom2) AS bom,partreceived*IF(bom2 IS NULL,bom1/2,bom2) AS stock,fno FROM (SELECT d12.date,m15.name AS stkpt,d11.operation,d12.pnum,prcno,partreceived,fno FROM `d12` LEFT JOIN d11 ON d12.prcno=d11.rcno LEFT JOIN m15 ON d11.operation=m15.prev WHERE d12.date>='2018-10-17' AND d12.date<='2018-10-24' AND partreceived!='') AS STST LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON STST.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON STST.pnum=BOM2.pnum WHERE stkpt='$oper[name]'";
		//echo  "SELECT date,stkpt,STST.pnum,prcno,partreceived,bom1,bom2,IF(bom2 IS NULL,bom1/2,bom2) AS bom,partreceived*IF(bom2 IS NULL,bom1/2,bom2) AS stock,fno FROM (SELECT d12.date,m15.name AS stkpt,d11.operation,d12.pnum,prcno,partreceived,fno FROM `d12` LEFT JOIN d11 ON d12.prcno=d11.rcno LEFT JOIN m15 ON d11.operation=m15.prev WHERE d12.date>='2018-10-17' AND d12.date<='2018-10-24' AND partreceived!='') AS STST LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON STST.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON STST.pnum=BOM2.pnum WHERE stkpt='$oper[name]'";
		$result = $con->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['prcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['partreceived']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['bom']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['stock']);
			$rowCount++;
		}
		
		
		$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->setTitle($oper['name']." ISSUED");
		/********************************************************************************************************************************************************************************************************************************/
		$objPHPExcel->createSheet(4);
		$objPHPExcel->setActiveSheetIndex(4);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $d);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:E1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', $oper['name'].'OPENING STOCK ON '.$f);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'OPERATION NAME');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ROUTE CARD NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'STOCK QTY');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'STOCK IN KG');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'G'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		$query = "SELECT rcno,operation,OPENRC.pnum,issqty,received,rejected,used,notused,bom1,bom2,rate,per,IF(rcno LIKE 'A20%',notused,notused*IF(bom2 IS NULL,bom1/2,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$tt' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<='$tt' AND (d11.closedate>'$tt' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum  WHERE operation='$oper[name]'";
		//echo "SELECT rcno,operation,OPENRC.pnum,issqty,received,rejected,used,notused,bom1,bom2,rate,per,IF(rcno LIKE 'A20%',notused,notused*IF(bom2 IS NULL,bom1/2,bom2)) AS kg FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$tt' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.date<='$tt' AND (d11.closedate>'$tt' OR d11.closedate='0000-00-00') AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) GROUP BY T2.rcno order by t2.date,t2.rcno) AS OPENRC LEFT JOIN (SELECT DISTINCT invpnum,SUM(useage) AS bom1 FROM (SELECT invpnum,useage FROM `m13` LEFT JOIN pn_st ON pn_st.pnum=m13.pnum AND pn_st.stkpt='FG For Invoicing' WHERE invpnum!='' GROUP BY invpnum,m13.pnum) AS T GROUP BY invpnum) AS BOM1 ON OPENRC.pnum=BOM1.invpnum LEFT JOIN (SELECT DISTINCT pnum,useage AS bom2 FROM m13) AS BOM2 ON OPENRC.pnum=BOM2.pnum  WHERE operation='$oper[name]'";
		$result = $con->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['notused']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['kg']);
			$rowCount++;
		}
		
		
		$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->setTitle($oper['name']." CLOSING");
		/********************************************************************************************************************************************************************************************************************************/
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('\\\server\Share Documents\Narayanan\S_REPORT\\'.$oper['name'].' -'.date("Y-m-d").'.xlsx');
		$objWriter->save('D:\TEST\\'.$oper['name'].' -'.date("Y-m-d").'.xlsx');
		echo 'D:\TEST\\'.$oper['name'].'- '.date("Y-m-d").'.xlsx';
	}	
	echo "Report generated";
	//header("location: inputlink.php");
?>