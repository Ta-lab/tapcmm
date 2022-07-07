<?php
	$f=date("Y-m-d");
	$fdate=date("Y-m-d");
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
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $fdate);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' OPEN ROUTE CARD STATUS REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ROUTE CARD NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ISSUED DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'RAW MATERIAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ISSUED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'RECEIVED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'REJECTED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'USED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'QTY IN PROCESS');
		$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'AGE');
		$objPHPExcel->getActiveSheet()->SetCellValue('N2', 'FOREMAN');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:N2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'O'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	//$query = "SELECT OPENRC.*,m12.operation as op FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE 'CNC_SHEARING' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per FROM m13  LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS OPENRC LEFT JOIN m12 ON OPENRC.pnum=m12.pnum WHERE m12.operation='CNC Machine' HAVING days>5 ORDER BY m12.operation,days DESC";
	$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%',T9.scn,IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T2.operation LIKE 'Returned','Karthi',IF(T5.foreman IS NULL,T6.foreman,T5.foreman))))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN (SELECT rcno,dcnum,scn FROM `d11` LEFT JOIN (SELECT dcnum,scn FROM `dc_det`) AS T8 ON d11.rcno=CONCAT('DC-',T8.dcnum) WHERE operation='FG For S/C' AND closedate='0000-00-00') AS T9 ON T2.rcno=T9.rcno GROUP BY T2.rcno HAVING days>5 order by t2.date,t2.rcno";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['rcno']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['rm']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['issqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['pnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['received']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['used']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['notused']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['days']);
		
		if($row['foreman']=="")
		{
			$f=$row['foreman1'];
			if($f=="")
				{
					$pnum=$row['pnum'];
					$query1 = "SELECT DISTINCT pnum,foreman FROM m13 WHERE pnum LIKE '%$pnum%'";
					$result1 = $conn->query($query1);
					$row1 = mysqli_fetch_array($result1);
					$f=$row1['foreman'];
				}
			
		}else
		{
			$f=$row['foreman'];
		}
		
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $f);
		
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K".$rowCount.":L".$rowCount.":M".$rowCount.":N")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("OPEN RC");
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(1);
	$objPHPExcel->setActiveSheetIndex(1);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $fdate);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' SHEET CUTTING ROUTE CARD STATUS REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ROUTE CARD NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ISSUED DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'RAW MATERIAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ISSUED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'RECEIVED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'REJECTED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Used Quantity');
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'QTY IN PROCESS');
		$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'AGE');
		//$objPHPExcel->getActiveSheet()->SetCellValue('N2', 'FOREMAN');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:N2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'O'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "SELECT OPENRC.*,m12.operation as op FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE 'CNC_SHEARING' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per FROM m13  LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS OPENRC LEFT JOIN m12 ON OPENRC.pnum=m12.pnum WHERE m12.operation='Straitening/Shearing' HAVING days>5 ORDER BY m12.operation,days DESC";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['rcno']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['op']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['rm']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['issqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['pnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['received']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['used']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['notused']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['days']);
		//$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $row['foreman']);
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K".$rowCount.":L".$rowCount.":M".$rowCount.":N")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("SHEET CUTTING");
	/************************************************************************/
	
	$objPHPExcel->createSheet(2);
	$objPHPExcel->setActiveSheetIndex(2);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $fdate);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' CNC ROUTE CARD STATUS REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ROUTE CARD NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ISSUED DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'RAW MATERIAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ISSUED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'RECEIVED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'REJECTED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Used Quantity');
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'QTY IN PROCESS');
		$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'AGE');
		//$objPHPExcel->getActiveSheet()->SetCellValue('N2', 'FOREMAN');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:N2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'O'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "SELECT OPENRC.*,m12.operation as op FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE 'CNC_SHEARING' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per FROM m13  LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS OPENRC LEFT JOIN m12 ON OPENRC.pnum=m12.pnum WHERE m12.operation='CNC Machine' HAVING days>5 ORDER BY m12.operation,days DESC";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['rcno']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['op']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['rm']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['issqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['pnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['received']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['used']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['notused']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['days']);
		//$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $row['foreman']);
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K".$rowCount.":L".$rowCount.":M".$rowCount.":N")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("CNC");
	
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(3);
	$objPHPExcel->setActiveSheetIndex(3);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $fdate);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' SUB-CONTRACT REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DC NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ISSUED DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'SUB-CONTRACTOR NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ISSUED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'RECEIVED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'REJECTED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Used Quantity');
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'QTY IN PROCESS');
		$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'AGE');
		$objPHPExcel->getActiveSheet()->SetCellValue('N2', 'FOREMAN');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:N2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'O'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "SELECT date,scn,pnum,rcno,issqty,received,rejected,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,T1.received,T1.rejected,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE 'Subcontract' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) HAVING days>30 ORDER BY days DESC";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['rcno']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "SUB-Contract");
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['scn']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['issqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "Nos");
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['pnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['received']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['used']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['notused']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, "Nos");
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['days']);
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $row['foreman']);
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K".$rowCount.":L".$rowCount.":M".$rowCount.":N")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("SUB-CONTRACTOR");
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(4);
	$objPHPExcel->setActiveSheetIndex(4);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $fdate);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' FINISHED GOODS STOCK REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ROUTE CARD NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'STOCKING POINT');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'QUANTITY');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'AGE');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'H'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "select distinct(PNUM) as pnum,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt='FG For Invoicing' GROUP BY prcno HAVING (sum(partreceived)-sum(partissued))>0 AND days>5 ORDER BY days DESC";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['prc']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "FG FOR INVOICING");
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['s']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "Nos");
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['days']);
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("FG FOR INVOICING STOCK");
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(5);
	$objPHPExcel->setActiveSheetIndex(5);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $fdate);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' ALFA STOCK REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ROUTE CARD NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'SUB-CONTRACTOR NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'ISSUED QUANTITY');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'INVOICED');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'AVAILABLE QUANTITY');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'AGE');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:J2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:J2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'K'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "SELECT * FROM (SELECT T2.rcno,T2.date,T2.issqty,T2.pnum,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE 'ALFA N-IND PRIM' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) HAVING days>5 and notused>0 order by t2.date,t2.rcno) AS T LEFT JOIN (SELECT dcnum,scn FROM dc_det) AS TT ON (T.rcno=CONCAT('DC-',TT.dcnum))";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['rcno']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "FG FOR S/C");
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['scn']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['issqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['used']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['notused']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, "Nos");
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['days']);
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("ALFA + N-IND + PRIM STOCK");
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(6);
	$objPHPExcel->setActiveSheetIndex(6);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $fdate);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' CLOSED ROUTE CARD STATUS REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ROUTE CARD NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ISSUED DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'CLOSED DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'RAW MATERIAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'ISSUED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'RECEIVED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'REJECTED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'Used Quantity');
		$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'SHORTAGE QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('N2', 'VARIANCE %');
		$objPHPExcel->getActiveSheet()->SetCellValue('O2', 'Reason');
		$objPHPExcel->getActiveSheet()->SetCellValue('P2', 'AGE');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:P2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'P'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$dt=date('Y-m-d',strtotime('-1 days'));
	$query = "SELECT T2.rcno,T2.rmk,T2.closedate,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))*100)/T2.issqty) AS variance FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,d11.rmk,d11.closedate,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='$dt' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per FROM m13  LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) having notused!=0 order by variance,t2.date,t2.rcno";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['rcno']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['closedate']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['rm']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['issqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['pnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['received']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['rejected']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['used']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $row['notused']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, round($row['variance'],1)." %");
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $row['rmk']);
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $row['days']);
		$rowCount++;
	}
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("CLOSED ROUTE CARD REPORT");
	/********************************************************************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(7);
	$objPHPExcel->setActiveSheetIndex(7);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $fdate);
		$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' MANUAL AREA STATUS REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ROUTE CARD NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'ISSUED DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'ISSUED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'RECEIVED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'REJECTED QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'Used Quantity');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'AVAILABLE QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'UOM');
		$objPHPExcel->getActiveSheet()->SetCellValue('l2', 'AGE');
		$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'FOREMAN');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:M2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A2:M2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'N'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per FROM m13  LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) WHERE T2.operation LIKE '%MANUAL%' HAVING days>5 order by T5.foreman,t2.date,t2.rcno";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['rcno']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['issqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['pnum']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['received']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['rejected']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['used']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['notused']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['unit']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $row['days']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['foreman']);
		$rowCount++;
	}
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("MANUAL AREA STATUS REPORT");
	/************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(8);
	$objPHPExcel->setActiveSheetIndex(8);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', date("d-m-Y"));
		$objPHPExcel->getActiveSheet()->mergeCells('C1:K1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' STORES ISSUANCE REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE OF ISSUANCE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'ROUTE CARD NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'GIN NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'SUPPLIER NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'MATERIAL CODE');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'MATERIAL DESCRIPTION');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'QUANTITY');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'UOM');
		//$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'NET VALUE (Rs)');
		//$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'GROSS VALUE (Rs)');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:K2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A1:K2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'L'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	
	$dm=date("Y-m");
	date_default_timezone_set('Asia/Kolkata');
	$ym=date("Y-m",strtotime($dm));
		
	$query = "SELECT d12.date,rm,rmissqty,rcno,inv,pnum,sname,part_number FROM d12 LEFT JOIN storedb.receipt ON d12.inv=storedb.receipt.grnnum WHERE stkpt='Stores' AND rcno LIKE '%A20%' AND d12.date LIKE '%".$ym."%'";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['inv']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['sname']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['part_number']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['rm']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['rmissqty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, 'Kg');
			//$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['pnum']);
			//$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['sname']);
			$rowCount++;
	}
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle(" STORES ISSUANCE ");
	/************************************************************************************************************************************************************************/
	$objPHPExcel->createSheet(9);
	$objPHPExcel->setActiveSheetIndex(9);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', date("d-m-Y"));
		$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' INVOICE CORRECTION REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'INVOICE NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CUSTOMER CODE');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'QUANTITY');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'REASON');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A1:F2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'G'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	
	$dm=date("Y-m");
	date_default_timezone_set('Asia/Kolkata');
	$ym=date("Y-m",strtotime($dm));
		
	$query = "SELECT * FROM inv_correction WHERE invdt LIKE '%".$ym."%'";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['invno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['invdt']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['ccode']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pn']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['qty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['reason']);
			$rowCount++;
	}
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle(" INVOICE CORRECTION ");
	/************************************************************************************************************************************************************************/
	$conn_erpdb = new mysqli($servername, $username, $password, "erpdb");
	$objPHPExcel->createSheet(10);
	$objPHPExcel->setActiveSheetIndex(10);
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', date("d-m-Y"));
		$objPHPExcel->getActiveSheet()->mergeCells('C1:D1');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' PO CORRECTION REPORT ');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'PO NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'PO DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'SUPPLIER NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'REASON');
		
		$objPHPExcel->getActiveSheet()->getStyle("A2:D2")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A1:D2")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'E'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	  $objPHPExcel->getActiveSheet()->freezePane('A3');
	$rowCount = 3;
	
	$dm=date("Y-m");
	date_default_timezone_set('Asia/Kolkata');
	$ym=date("Y-m",strtotime($dm));
		
	$query = "SELECT * FROM po_correction_updation WHERE podate LIKE '%".$ym."%'";
	$result = $conn_erpdb->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['ponumber']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['podate']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['sname']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['reason']);
			$rowCount++;
	}
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle(" PO CORRECTION ");
	
	
	
	
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	//$objWriter->save('report\Report-O12-'.date("Y-m-d").'.xlsx');
	//$objWriter->save('D:\REPORT\Report-O12-'.date("Y-m-d").'.xlsx');
	$time=date("Y-m-d");
	
	//mysqli_query($conn,"UPDATE autoreport SET daily='$time'");
	//echo "UPDATE autoreport SET daily='$time'";
	//echo "Report generated";
	//header("location: reportmail.php");
	header("location: inputlink.php");
	//header("location: rmwiseopeningstk_excel.php");
?>