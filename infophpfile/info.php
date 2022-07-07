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
			$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', date("d-m-Y"));
			$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' SALES DETAIL FOR THE PERIOD ');
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'DATE OF INVOICE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'INVOICE NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'QUANTITY');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'GROSS VALUE');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'NET VALUE');
			$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'CUSTOMER NAME');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:G2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'H'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		$query = "select invno,invdt,pn,qty,taxgoods,invtotal,cname,cname1 from inv_det WHERE invdt LIKE '%".$my."%'";
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
		
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("SALES");
		$objPHPExcel->getActiveSheet()->getStyle('A1:G'.$rowCount)->applyFromArray($styleArray);
		/***************************************************************************************************************************************************************************/
			$objPHPExcel->createSheet(2);
		$objPHPExcel->setActiveSheetIndex(2);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', date("d-m-Y"));
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' STOCK VALUATION ON '.$ymdf);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'OPERATION / STOCKING POINT');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'QUANTITY');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:F2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'G'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*((T7.useage/T7.tot)*rate))/per)*v)/100,(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per)*v)/100)  as value,v FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$ymdf' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND (d11.closedate='0000-00-00' OR d11.closedate>'$ymdf') AND d11.operation!='FG For Invoicing' AND d11.date<='$ymdf' AND d12.date<='$ymdf' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper GROUP BY T2.rcno HAVING notused>0 order by t2.operation";
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['notused']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		$query = "SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='$ymdf' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc";
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['stkpt']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['prc']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['s']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Nos');
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$rowCount)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("VALUATION ON ".$ymdf);
		/***************************************************************************************************************************************************************************/
		$objPHPExcel->createSheet(3);
		$objPHPExcel->setActiveSheetIndex(3);
		$style = array(
		  'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		  )
		);
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', date("d-m-Y"));
			$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', ' STOCK VALUATION ON '.$ymdf);
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'OPERATION / STOCKING POINT');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'ROUTE CARD / DC NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'PART NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'QUANTITY');
			$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'VALUE');
			
			$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getStyle("A1:F2")->getFont()->setBold(true);
			$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			for($col = 'A'; $col !== 'G'; $col++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			}
		  $objPHPExcel->getActiveSheet()->freezePane('A3');
		$rowCount = 3;
		$query = "SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*((T7.useage/T7.tot)*rate))/per)*v)/100,(((((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per)*v)/100)  as value,v FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' AND d12.date<='$ymdl' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND (d11.closedate='0000-00-00' OR d11.closedate='$ymdl') AND d11.operation!='FG For Invoicing' AND d11.date<='$ymdl' AND d12.date<='$ymdl' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT * FROM (SELECT pn_st.pnum,invpnum,foreman,rate,per FROM pn_st JOIN m13 on pn_st.pnum=m13.pnum left JOIN invmaster ON (pn_st.invpnum=invmaster.pn || pn_st.pnum=invmaster.pn)) AS T) AS T5 ON (T2.pnum=T5.pnum || T2.pnum=T5.invpnum)LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T2.pnum=T7.pnum LEFT JOIN (SELECT oper,value as v FROM m14) AS T6 ON T2.operation=T6.oper GROUP BY T2.rcno HAVING notused>0 order by t2.operation";
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['operation']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['rcno']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['notused']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		$query = "SELECT date,prc,T1.pnum,T1.stkpt,s,IF(T1.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate) as rate,per,(((s*(IF(T2.pnum LIKE '%-S' OR T2.pnum LIKE '%-T' OR T2.pnum LIKE '%-C1' OR T2.pnum LIKE '%-C2',(T7.useage/T7.tot)*rate,rate)))/per)*v)/100 AS value,v,days FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND d12.date<='$ymdl' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS T1 LEFT JOIN (SELECT pnum,invpnum,IF(rate IS NULL,0,rate) as rate,IF(per IS NULL,0,per) as per FROM pn_st JOIN invmaster ON (pn_st.pnum=invmaster.pn || pn_st.invpnum=invmaster.pn)) AS T2 ON (T1.pnum=T2.pnum || T1.pnum=T2.invpnum) LEFT JOIN (SELECT * FROM (SELECT pn_st.pnum,invpnum as invp,useage FROM pn_st LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T1 LEFT JOIN (SELECT invpnum,SUM(useage) AS tot FROM (SELECT DISTINCT pn_st.pnum,invpnum,useage FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum) AS T0 GROUP BY invpnum) AS T2 ON T1.invp=T2.invpnum) AS T7 ON T1.pnum=T7.pnum LEFT JOIN (SELECT stkpt,value as v FROM m14) AS T6 ON T1.stkpt=T6.stkpt GROUP BY stkpt,prc ORDER BY stkpt,prc";
		$result = $conn->query($query);
		while($row = mysqli_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['stkpt']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['prc']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pnum']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['s']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Nos');
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, round($row['value'],2));
			$rowCount++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setTitle("VALUATION ON ".$ymdl);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$rowCount)->applyFromArray($styleArray);
		
		/***************************************************************************************************************************************************************************/
		$objPHPExcel->createSheet(4);
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
		
		$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I")->applyFromArray($style);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('report\VALUATION-Report-O12-'.date("Y-m-d").'.xlsx');
		$objWriter->save('D:\REPORT\VALUATION-Report-O12-'.date("Y-m-d").'.xlsx');
		$objPHPExcel->setActiveSheetIndex(0);
		header("location: dailyreport.php");
	}
?>