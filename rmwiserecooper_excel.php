<?php
	if(1)
	{
			//$dm=date("Y-m");
			date_default_timezone_set('Asia/Kolkata');
			//$ym=date("Y-m",strtotime($dm));
			//$my=date("m-Y",strtotime($dm));
			//$ymdf=$ym."-01";
			//$ymdl=$ym."-31";
			
			//$date=date('Y-m-d');
			$date = date('Y-m-d', strtotime('-1 days'));
			$servername = "localhost";
			$username = "root";
			$password = "Tamil";
			$conn = new mysqli($servername, $username, $password, "mypcm");
			require_once('PHPExcel.php');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			$sheet = $objPHPExcel->getActiveSheet();
			$style = array(
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			  )
			);
			$styleArray = array(
			  'borders' => array(
				'allborders' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			  )
			);
			
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $date);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' OPERATION RECEIVED/CLOSED/SCRAP ON '.$date);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CATEGORY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'RECEIVED');
			$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'CLOSED');
			$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'SCRAP');
			//$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'NO OF DAYS');
			//$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:I2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:I2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'I'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		//runnig query
		//$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
		
		//CNC_SHEARING
		$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper='CNC_SHEARING' AND d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['category']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['issqty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['used'],2));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);
			
			//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['days']);
			//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		
		/*$query = "SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='$ymdl' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc";
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['stkpt']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['prc']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['s']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Nos');
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['days']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		*/
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("CNC_SHEARING");
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$rowCount)->applyFromArray($styleArray);
		
		
		//sheet 2
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $date);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' OPERATION RECEIVED/CLOSED/SCRAP ON '.$date);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CATEGORY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'RECEIVED');
			$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'CLOSED');
			$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'SCRAP');
			//$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'NO OF DAYS');
			//$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:I2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:I2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'I'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		
		//MANUAL_AREA
		$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper='MANUAL_AREA' AND d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
		
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{		
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['category']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['issqty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['used'],2));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);	
			//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['days']);
			//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("MANUAL_AREA");
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$rowCount)->applyFromArray($styleArray);
		
		
		//sheet 3
		$objPHPExcel->createSheet(2);
		$objPHPExcel->setActiveSheetIndex(2);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $date);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' OPERATION RECEIVED/CLOSED/SCRAP ON '.$date);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CATEGORY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'RECEIVED');
			$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'CLOSED');
			$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'SCRAP');
			//$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'NO OF DAYS');
			//$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:I2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:I2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'I'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		
		//MANUAL_AREA-1
		$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper='MANUAL_AREA-1' AND d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
		
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['category']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['issqty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['used'],2));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);	
			//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['days']);
			//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("MANUAL_AREA-1");
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$rowCount)->applyFromArray($styleArray);
		
		
		
		//sheet 4
		$objPHPExcel->createSheet(3);
		$objPHPExcel->setActiveSheetIndex(3);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $date);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' OPERATION RECEIVED/CLOSED/SCRAP ON '.$date);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CATEGORY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'RECEIVED');
			$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'CLOSED');
			$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'SCRAP');
			//$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'NO OF DAYS');
			//$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:I2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:I2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'I'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		
		//MANUAL_AREA-2
		$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper='MANUAL_AREA-2' AND d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
		
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['category']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['issqty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['used'],2));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);	
			//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['days']);
			//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("MANUAL_AREA-2");
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$rowCount)->applyFromArray($styleArray);
		
		
		
		//sheet 5
		$objPHPExcel->createSheet(4);
		$objPHPExcel->setActiveSheetIndex(4);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $date);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' OPERATION RECEIVED/CLOSED/SCRAP ON '.$date);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CATEGORY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'RECEIVED');
			$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'CLOSED');
			$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'SCRAP');
			//$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'NO OF DAYS');
			//$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:I2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:I2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'I'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		
		//ALFA N-IND PRIM
		$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper='ALFA N-IND PRIM' AND d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
		
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['category']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['issqty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['used'],2));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);	
			//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['days']);
			//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("ALFA N-IND PRIM");
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$rowCount)->applyFromArray($styleArray);
		
		
		
		//sheet 6
		$objPHPExcel->createSheet(5);
		$objPHPExcel->setActiveSheetIndex(5);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $date);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' OPERATION RECEIVED/CLOSED/SCRAP ON '.$date);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CATEGORY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'RECEIVED');
			$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'CLOSED');
			$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'SCRAP');
			//$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'NO OF DAYS');
			//$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:I2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:I2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'I'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		
		//SUBCONTRACT
		$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper='SUBCONTRACT' AND d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
		
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['category']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['issqty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['used'],2));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);	
			//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['days']);
			//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("SUBCONTRACT");
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$rowCount)->applyFromArray($styleArray);
		
		
		
		//sheet 7
		$objPHPExcel->createSheet(6);
		$objPHPExcel->setActiveSheetIndex(6);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $date);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' OPERATION RECEIVED/CLOSED/SCRAP ON '.$date);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CATEGORY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'RECEIVED');
			$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'CLOSED');
			$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'SCRAP');
			//$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'NO OF DAYS');
			//$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:I2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:I2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'I'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		
		//100% Checking
		$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper='100% Checking' AND d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
		
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['category']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['issqty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['used'],2));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);	
			//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['days']);
			//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("100% Checking");
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$rowCount)->applyFromArray($styleArray);
		
		
		
		//sheet 8
		$objPHPExcel->createSheet(7);
		$objPHPExcel->setActiveSheetIndex(7);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', $date);
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' OPERATION RECEIVED/CLOSED/SCRAP ON '.$date);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'OPERATION');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'CATEGORY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'RECEIVED');
			$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'CLOSED');
			$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'SCRAP');
			//$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'NO OF DAYS');
			//$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:I2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:I2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'I'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		
		//Returned
		$query = "SELECT DISTINCT T2.rcno,T2.operation,T2.date,T2.closedate,RET.ret,RMCAT.category,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,T2.rm as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,IF(T3.useage IS NULL,T7.bom,T3.useage),T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.closedate,d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, '') as rm,d11.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno LEFT JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper='Returned' AND d11.rcno!='' AND d11.pnum!='' AND d11.operation!='FG For Invoicing' AND d11.date>='$date' AND d11.date<='$date' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE  pn_st.stkpt LIKE 'FG%' AND (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum WHERE pn_st.stkpt LIKE 'FG%') AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%' GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum LEFT JOIN(SELECT * FROM `d14` WHERE d14.date>='$date' AND d14.date<='$date' GROUP BY d14.rcno) AS RET ON T2.rcno=RET.rcno LEFT JOIN(SELECT rmcategory.pnum,pnst.invpnum,rmcategory.category FROM(SELECT * FROM `rmcategory`) AS rmcategory LEFT JOIN(SELECT * FROM pn_st) AS pnst ON rmcategory.pnum=pnst.pnum OR rmcategory.pnum=pnst.invpnum GROUP BY rmcategory.pnum,pnst.invpnum) AS RMCAT ON T2.pnum=RMCAT.pnum OR T2.pnum=RMCAT.invpnum order by t2.date,t2.rcno";
		
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['date']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['category']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['issqty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['used'],2));
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['rejected']);	
			//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['days']);
			//$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("Returned");
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$rowCount)->applyFromArray($styleArray);
		
		
		
		
		/***************************************************************************************************************************************************************************/
		/*$objPHPExcel->createSheet(4);
		$objPHPExcel->setActiveSheetIndex(4);
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
			$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'NET VALUE (Rs)');
			$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'GROSS VALUE (Rs)');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:K2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:K2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'L'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		$query = "SELECT d12.date,rm,rmissqty,rcno,inv,pnum,sname,part_number FROM d12 LEFT JOIN storedb.receipt ON d12.inv=storedb.receipt.grnnum WHERE stkpt='Stores'  AND d12.date LIKE '%".$ym."%'";
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
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle(" STORES ISSUANCE ");
		$objPHPExcel->getActiveSheet()->getStyle('A1:K'.$rowCount)->applyFromArray($styleArray);
		*/
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I")->applyFromArray($style);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('report\MATERIALRECONCILATION\OPENING STOCK-'.date("Y-m-d").'.xlsx');
		$objWriter->save('D:\REPORT\MATERIALRECONCILATION\OPERATION RECEIVED-CLOSED-SCRAP-'.$date.'.xlsx');
		$objPHPExcel->setActiveSheetIndex(0);
		header("location: inputlink.php");
	}
?>