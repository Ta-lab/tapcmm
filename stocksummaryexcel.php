<?php
	if(1)
	{
		$dm=date("Y-m");
			date_default_timezone_set('Asia/Kolkata');
			$ym=date("Y-m",strtotime($dm));
			$my=date("m-Y",strtotime($dm));
			$ymdf=$ym."-01";
			$ymdl=$ym."-31";
			$servername = "localhost";
			$username = "root";
			$password = "Tamil";
			$conn = new mysqli($servername, $username, $password, "mypcm");
			require_once('PHPExcel.php');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			$sheet = $objPHPExcel->getActiveSheet();
			/*$style = array(
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
			$query = "select SUM(taxgoods) AS net,SUM(invtotal) AS gross from inv_det WHERE invdt LIKE '%".$my."%'";
			$result = $conn->query($query);
			$row = mysqli_fetch_array($result);
				$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', date("d-m-Y"));
				$objPHPExcel->getActiveSheet()->mergeCells('C1:E1');
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' MONTHLY SALES,INVENTORY AND RAW MATERIAL CONSUMPTION SUMMARY ');
				$objPHPExcel->getActiveSheet()->mergeCells('A2:B3');
				$objPHPExcel->getActiveSheet()->mergeCells('D2:E3');
				$objPHPExcel->getActiveSheet()->mergeCells('A6:B6');
				$objPHPExcel->getActiveSheet()->mergeCells('D6:E6');
				$objPHPExcel->getActiveSheet()->mergeCells('C2:C6');
				$objPHPExcel->getActiveSheet()->getStyle("A1:G1")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'SALES VALUE (GROSS) INR.');
				$objPHPExcel->getActiveSheet()->SetCellValue('B4', $row['gross']);
				$objPHPExcel->getActiveSheet()->SetCellValue('A5', 'SALES VALUE (NET) INR.');
				$objPHPExcel->getActiveSheet()->SetCellValue('B5', $row['net']);
			$query = "SELECT SUM(rmissqty) as iss FROM d12 WHERE date LIKE '%".$ym."%' ";
			$result = $conn->query($query);
			$row = mysqli_fetch_array($result);	
				$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'STORES ISSAUNCE VALUE IN Kg.');
				$objPHPExcel->getActiveSheet()->SetCellValue('E4', $row['iss']);
				//$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'RM CONSUMED VALUE');
				//$objPHPExcel->getActiveSheet()->SetCellValue('E5', $row['iss']);
				$objPHPExcel->getActiveSheet()->mergeCells('A7:A20');
				$objPHPExcel->getActiveSheet()->SetCellValue('A7', 'WIP VALUE');
				//$objPHPExcel->getActiveSheet()->getStyle("A7")->applyFromArray($sheet);
				$objPHPExcel->getActiveSheet()->SetCellValue('B7', 'OPERATION / STOCKING POINT ');
				$objPHPExcel->getActiveSheet()->SetCellValue('C7', 'AS ON -'.$ymdf);
				$objPHPExcel->getActiveSheet()->SetCellValue('D7', 'AS ON -'.date("Y-m-d"));
				$objPHPExcel->getActiveSheet()->SetCellValue('E7', 'INCREASE / DECREASE IN STOCK ');
				$objPHPExcel->getActiveSheet()->getStyle("A4:E4")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A5:E5")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A2:E2")->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getStyle("A2:E2")->getFont()->setBold(true);
				$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				for($col = 'A'; $col !== 'F'; $col++) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
				}
			  //$objPHPExcel->getActiveSheet()->freezePane('A3');
			$rowCount = 8;
			$query = "SELECT f.operation,fval,lval,(lval-fval) AS diff FROM (SELECT operation,SUM(value) as fval,v FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*((T7.useage/T7.tot)*rate))/per)*v)/100,(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per)*v)/100)  as value,v FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$ymdf' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND (d11.closedate='0000-00-00' OR d11.closedate>'$ymdf') AND d11.operation!='FG For Invoicing' AND d11.date<='$ymdf' AND d12.date<='$ymdf' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper GROUP BY T2.rcno HAVING notused>0 order by t2.operation) AS op GROUP BY operation) AS f LEFT JOIN (SELECT operation,SUM(value) as lval,v FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*((T7.useage/T7.tot)*rate))/per)*v)/100,(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per)*v)/100)  as value,v FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$ymdl' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND (d11.closedate='0000-00-00' OR d11.closedate>'$ymdl') AND d11.operation!='FG For Invoicing' AND d11.date<='$ymdl' AND d12.date<='$ymdl' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper GROUP BY T2.rcno HAVING notused>0 order by t2.operation) AS op GROUP BY operation) AS l ON f.operation=l.operation ";
			$result = $conn->query($query);
			$cfval=0;
			$clval=0;
			$cdiff=0;
			while($row = mysqli_fetch_array($result))
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['operation']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, round($row['fval'],2));
				$cfval=$cfval+$row['fval'];
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, round($row['lval'],2));
				$clval=$clval+$row['lval'];
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, round($row['diff'],2));
				$cdiff=$cdiff+$row['diff'];
				$rowCount++;
			}
			$query = "SELECT f.stkpt,fval,lval,(lval-fval) AS diff FROM (SELECT stkpt,v,SUM(value) as fval FROM (SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='$ymdf' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc) AS stk GROUP BY stkpt) f LEFT JOIN (SELECT stkpt,v,SUM(value) as lval FROM (SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='$ymdl' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc) AS stk GROUP BY stkpt) AS l ON f.stkpt=l.stkpt";
			$result = $conn->query($query);
			while($row = mysqli_fetch_array($result))
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['stkpt']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, round($row['fval'],2));
				$cfval=$cfval+$row['fval'];
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, round($row['lval'],2));
				$clval=$clval+$row['lval'];
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, round($row['diff'],2));
				$cdiff=$cdiff+$row['diff'];
				$rowCount++;
			}
			$rowCount++;
			$objPHPExcel->getActiveSheet()->getStyle("A20:E20")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'TOTAL' );
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, round($cfval,2));
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, round($clval,2));
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, round($cdiff,2));
			$objPHPExcel->getActiveSheet()->getStyle("B7:E7")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$rowCount)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->setTitle("SUMMARY");
			/***************************************************************************************************************************************************************************/
			//$objPHPExcel->createSheet(1);
			//$objPHPExcel->setActiveSheetIndex(1);
			$style = array(
				'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				)
			);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'VENKATESHWARA STEELS AND SPRINGS INDIA PVT LTD');
			$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'FY 2018-19');
			$objPHPExcel->getActiveSheet()->mergeCells('A2:K2');
			$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Quantitative Details for FY 2018-19');
			$objPHPExcel->getActiveSheet()->mergeCells('A3:K3');
			$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'PARTICULARS');
			$objPHPExcel->getActiveSheet()->mergeCells('A4:A5');
			$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'UNIT I');
			$objPHPExcel->getActiveSheet()->mergeCells('B4:C4');
			$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Qty in KGS');
			$objPHPExcel->getActiveSheet()->SetCellValue('C5', 'VALUE');
			$objPHPExcel->getActiveSheet()->SetCellValue('D4', 'UNIT II');
			$objPHPExcel->getActiveSheet()->mergeCells('D4:E4');
			$objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Qty in KGS');
			$objPHPExcel->getActiveSheet()->SetCellValue('E5', 'VALUE');
			$objPHPExcel->getActiveSheet()->SetCellValue('F4', 'UNIT III');
			$objPHPExcel->getActiveSheet()->mergeCells('F4:G4');
			$objPHPExcel->getActiveSheet()->SetCellValue('F5', 'Qty in KGS');
			$objPHPExcel->getActiveSheet()->SetCellValue('G5', 'VALUE');
			$objPHPExcel->getActiveSheet()->SetCellValue('H4', 'UNIT IV');
			$objPHPExcel->getActiveSheet()->mergeCells('H4:I4');
			$objPHPExcel->getActiveSheet()->SetCellValue('H5', 'Qty in KGS');
			$objPHPExcel->getActiveSheet()->SetCellValue('I5', 'VALUE');
			$objPHPExcel->getActiveSheet()->SetCellValue('J4', 'TOTAL');
			$objPHPExcel->getActiveSheet()->mergeCells('J4:K4');
			$objPHPExcel->getActiveSheet()->SetCellValue('J5', 'Qty in KGS');
			$objPHPExcel->getActiveSheet()->SetCellValue('K5', 'VALUE');
			
			/*opening balance
			$objPHPExcel->getActiveSheet()->SetCellValue('A7', 'Opening Balance(A)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A8', 'Raw Material(A1)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A19', 'Total(A1)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A21', 'Work In Progress');
			$objPHPExcel->getActiveSheet()->SetCellValue('A34', 'Total(A2)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A36', 'Finished goods');
			$objPHPExcel->getActiveSheet()->SetCellValue('A48', 'Total(A3)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A50', 'Total(A)');
			
			//purchase
			$objPHPExcel->getActiveSheet()->SetCellValue('A52', 'Purchase(B)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A53', 'Raw Material(B1)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A59', 'Total(B1)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A61', 'Total(B)');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A63', 'Goods Consumed(C)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A64', 'Raw Material(C1)');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A73', 'Work in Progress(C2)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A81', 'Total(C2)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A83', 'Total(C)');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A85', 'Goods Sold(D)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A86', 'Finished Goods(D1)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A94', 'Total(D1)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A96', 'Total(D)');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A98', 'Loss & Scraps(E)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A99', 'Raw Material(E1)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A107', 'Total(E1)');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A109', 'Work in Progress(E2)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A116', 'Total(E2)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A119', 'Total(E)');
			*/
			
			//closing stock
			$objPHPExcel->getActiveSheet()->SetCellValue('A7', 'Closing Stock(F)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A8', 'Raw Material(F1)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A19', 'Total(F1)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A21', 'Work In Progress(F2)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A34', 'Total(F2)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A36', 'Finished goods(F3)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A48', 'Total(F3)');
			$objPHPExcel->getActiveSheet()->SetCellValue('A50', 'Total(F)');
			//$objPHPExcel->getActiveSheet()->SetCellValue('A158', 'Total As Per Workings');
			//$objPHPExcel->getActiveSheet()->SetCellValue('A160', 'Difference');
			
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:K1")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:K1")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'L'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		$objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 163;
		/*$query = "select invno,invdt,pn,qty,taxgoods,invtotal,cname,cname1 from inv_det WHERE invdt LIKE '%".$my."%'";
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['invdt']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['invno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pn']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['qty']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['taxgoods']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['invtotal']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['cname'].$row['cname1']);
			$rowCount++;
		}
		*/
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("STOCK SUMMARY");
		//$objPHPExcel->getActiveSheet()->getStyle('A1:K'.$rowCount)->applyFromArray($styleArray);
		/***************************************************************************************************************************************************************************/
		
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K")->applyFromArray($style);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		//$objWriter->save('report\VALUATION-Report-O12-'.date("Y-m-d").'.xlsx');
		$objWriter->save('D:\REPORT\VALUATION-Report-SUMMARY-'.date("Y-m-d").'.xlsx');
		$objPHPExcel->setActiveSheetIndex(0);
		//header("location: dailyreport.php");
	}
?>