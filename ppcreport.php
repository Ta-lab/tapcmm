<?php
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
	$style = array(
	  'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	  )
	);
		
	
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'PART NUMBER');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'TYPE');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'DEMAND');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'VMI IN FG');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'SF VMI');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'ORDER QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'STOCK IN CNC');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'STOCK IN MANUAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'WEEK NO');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'CNC PLAN');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'MANUAL PLAN');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'PRIORITY');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'ENTER CNC');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'ENTER MANUAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'REVISED DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'ORDER DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'ORDER QTY');
		$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'REQUESTED DATE');
		$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'COMMITED');
		
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFont()->setBold(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		for($col = 'A'; $col !== 'O'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
	$objPHPExcel->getActiveSheet()->freezePane('A1');
	$rowCount = 3;
	
	$query = "SELECT *,IF(w1>I,w1-I,0) AS IW1,IF(I>w1,IF(w2>(I-w1),w2-(I-w1),0),w2) AS IW2,IF(I>(w1+w2),IF(w3>(I-(w1+w2)),w3-(I-(w1+w2)),0),w3) AS IW3,b FROM (SELECT pn,CNC.part,type,monthly,vmi_fg,sf,stock,stock1,order_date,req_date,commit,IF(c IS NULL,0,c) AS c,w1c,w2c,w3c,IF(w1 IS NULL,0,w1) AS w1,IF(w2 IS NULL,0,w2) AS w2,IF(w3 IS NULL,0,w3) AS w3,IF(cqty IS NULL,0,cqty) AS cqty,invoiced_qty,IF(I IS NULL,0,I) AS I,IF(b IS NULL,0,b) AS b FROM (SELECT DISTINCT T.pnum AS pn,part,type,monthly,vmi_fg,sf,SUM(stock) AS stock FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' AND d11.operation!='Stores' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY T.pnum) AS CNC LEFT JOIN (SELECT DISTINCT T.pnum,part,type AS type1,monthly AS monthly1,vmi_fg AS vmi_fg1 ,sf AS sf1,SUM(stock) AS stock1 FROM (SELECT part,T.pnum,type,monthly,vmi_fg,sf,IF(stk is NULL,0,stk) AS stock FROM (SELECT demandmaster.pnum AS part,pn_st.pnum,type,monthly,vmi_fg,sf FROM `demandmaster` RIGHT JOIN pn_st ON demandmaster.pnum=pn_st.invpnum WHERE pn_st.stkpt LIKE 'FG%') AS T LEFT JOIN (SELECT pnum,SUM(col) AS stk FROM (SELECT operation,pnum,SUM(notused) AS col FROM (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%',T5.arcf,IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOOTHY',IF(T5.foreman IS NULL,T6.foreman,T5.foreman)))) as foreman,T6.foreman AS foreman1,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%','Kgs','Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',IF(T3.useage IS NULL,T7.bom,T3.useage)) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',IF(T3.useage IS NULL,T7.bom,T3.useage),1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation LIKE 'FG For S/C' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per,m12.operation as arcf FROM m13 LEFT JOIN m12 ON m13.pnum=m12.pnum LEFT JOIN pn_st ON pn_st.pnum=m13.pnum left JOIN invmaster ON pn_st.invpnum=invmaster.pn WHERE (m12.operation='Straitening/Shearing' OR m12.operation='CNC Machine') GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) LEFT JOIN (SELECT invpnum,pn_st.pnum,foreman FROM `pn_st` LEFT JOIN m13 ON pn_st.pnum=m13.pnum) AS T6 ON T2.pnum=T6.invpnum LEFT JOIN (SELECT invpnum,SUM(useage) AS bom FROM pn_st LEFT JOIN m13 ON m13.pnum=pn_st.pnum AND m13.pnum!=pn_st.invpnum GROUP BY invpnum) AS T7 ON T7.invpnum=T2.pnum GROUP BY T2.rcno order by t2.date,t2.rcno) AS OP GROUP BY operation,pnum UNION SELECT stkpt ,pnum,SUM(s) FROM (select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0) AS ST WHERE stkpt NOT LIKE 'Semi%' GROUP BY stkpt,pnum) AS JT GROUP BY pnum) AS JTT ON (T.pnum=JTT.pnum OR T.part=JTT.pnum) WHERE part!='' GROUP BY T.pnum,stock ORDER BY part) AS T GROUP BY pnum) AS MANUAL ON CNC.pn=MANUAL.pnum LEFT JOIN (SELECT * FROM (SELECT demandmaster.pnum,order_date,req_date,commit,c,qty AS cqty,invoiced_qty,qty-invoiced_qty AS b FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum LEFT JOIN (SELECT DISTINCT pnum,IF(COUNT(*) IS NULL,0,COUNT(*)) AS c FROM (SELECT demandmaster.pnum,order_date,req_date,commit,qty-invoiced_qty AS b FROM `demandmaster` JOIN orderbook on demandmaster.pnum=orderbook.pnum HAVING b>0) AS C GROUP BY pnum) AS CC ON demandmaster.pnum=CC.pnum HAVING b>0) AS C) AS CC ON CC.pnum=CNC.pn  LEFT JOIN (SELECT T1.pnum,w1,w2,w3 FROM (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w1 FROM `orderbook` WHERE req_date<='2019-08-20' GROUP BY pnum) AS T1 LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w2 FROM `orderbook` WHERE req_date>'2019-08-20' AND req_date<='2019-08-30' GROUP BY pnum) AS T2 ON T1.pnum=T2.pnum LEFT JOIN (SELECT pnum,IF(SUM(qty) IS NULL,0,SUM(qty)) AS w3 FROM `orderbook` WHERE req_date>'2019-08-30' AND req_date<='2019-09-05' GROUP BY pnum) AS T3 ON T1.pnum=T3.pnum) AS OB ON CC.pnum=OB.pnum LEFT JOIN (SELECT orderbook.pnum,IF(w1c IS NULL,0,w1c) AS w1c,IF(w2c IS NULL,0,w2c) AS w2c,IF(w3c IS NULL,0,w3c) AS w3c FROM `orderbook` LEFT JOIN (SELECT DISTINCT pnum,COUNT(*) AS w1c FROM (SELECT * FROM `orderbook` WHERE req_date<='2019-08-20' HAVING qty-invoiced_qty>0) AS a GROUP BY pnum) AS t1 ON t1.pnum=orderbook.pnum  LEFT JOIN (SELECT DISTINCT pnum,COUNT(*) AS w2c FROM (SELECT * FROM `orderbook` WHERE req_date>'2019-08-20' AND req_date<='2019-08-30' HAVING qty-invoiced_qty>0) AS a GROUP BY pnum) AS T2 on T2.pnum=orderbook.pnum LEFT JOIN (SELECT DISTINCT pnum,COUNT(*) AS w3c FROM (SELECT * FROM `orderbook` WHERE req_date>'2019-08-30' AND req_date<='2019-09-05' HAVING qty-invoiced_qty>0) AS a GROUP BY pnum) AS T3 on T3.pnum=orderbook.pnum GROUP BY orderbook.pnum) AS WC ON WC.pnum=CC.pnum LEFT JOIN (SELECT DISTINCT pn AS invpnum,IF(sum(qty) IS NULL,0,SUM(qty)) AS I FROM `inv_det` WHERE invdt>='2019-08-07' GROUP BY pn) AS INV ON INV.invpnum=CC.pnum) AS PPC";
	$result = $conn->query($query);
	while($row = mysqli_fetch_array($result))
	{
		$md=$row['monthly'];
		$fg=$row['vmi_fg'];
		$sf=$row['sf'];
		$ob=$row['IW1'];
		$md1=$row['monthly'];
		$fg1=$row['vmi_fg'];
		$sf1=$row['sf'];
		$ob1=$row['IW1'];
		$obw4=$row['IW2'];
		$obw5=$row['IW3'];
		$obw4_1=$row['IW2'];
		$obw5_1=$row['IW3'];
		$cw1=0;
		$cw2=0;
		$cw3=0;
		$mw1=0;
		$mw2=0;
		$mw3=0;
		
		
		// WEEK 1 CALCULATION
					
		$carry=0;
		$carry1=0;
		
		if($row1['type']=="Kanban")
		{
		//CNC
			$d=($fg+$sf+($md/4))-$stock;
		//MANUAL
			$d1=($fg1+$sf1+($md/4))-$stock1;
		}
		if($row1['type']=="Regular")
		{
		//CNC
		//$d=($ob+$fg+$sf)-$stock; FG AND SF VMI INCLUDED
			$d=($ob)-$stock;
		//MANUAL
		//$d1=($ob1+$fg1+$sf1)-$stock1; FG AND VMI INCLUDED
			$d1=($ob1)-$stock1;
		}
		if($row1['type']=="Stranger")
		{
		//CNC
		$d=($ob)-$stock;
		//MANUAL
		$d1=($ob1)-$stock1;
		//CNC
		if($ob==0)
		{
		$d=0;
		}
		//MANUAL
		if($ob1==0)
		{
		$d1=0;
		}
		}
		//CNC
		if($d<0)
		{
		//echo"<td>0</td>";
		//echo"<td>".$d."</td>";
		}
		else
		{
			$cw1=round($d,0);
		//echo"<td>".round($d,0)."</td>";
		}
		//MANUAL
		if($d1<0)
		{
		//echo"<td>0</td>";
		}
		else
		{
		//echo"<td>".round($d1,0)."</td>";
			$mw1=round($d1,0);
		}
		//CNC
		if($d<0)
		{
			$carry=$d;
		}
		else
		{
		$carry=0;
		}
		//MANUAL
		if($d1<0)
		{
		$carry1=$d1;
		}
		else
		{
			$carry1=0;
		}
					
		// WEEK 2 CALCULATION
		
		if($row1['type']=="Kanban")
		{
		//CNC
		$d=($md/4)+$carry;
		//MANUAL
		$d1=($md1/4)+$carry1;
		}
		if($row1['type']=="Regular")
		{
		//CNC
		$d=($md/4)+$carry;
		//MANUAL
		$d1=($md1/4)+$carry1;
		}
		if($row1['type']=="Stranger")
		{
		//CNC
		$d=$obw4-$carry;
		//MANUAL
		$d1=$obw4_1-$carry1;
		//CNC
		if($obw4==0)
		{
			$d=0;
		}
			//MANUAL
			if($obw4_1==0)
			{
				$d1=0;
			}
		}
		//CNC
		if($d<0)
		{
			//echo"<td>0</td>";
		}
		else
		{
			$cw2=round($d,0);
			//echo"<td>".round($d,0)."</td>";
		}
		//MANUAL
		if($d1<0)
		{
			//echo"<td>0</td>";
		}
		else
		{
			//echo"<td>".round($d1,0)."</td>";
			$mw2=round($d1,0);
		}
		
		//CNC
		if($d<0)
		{
			$carry=$d;
		}
		else
		{
			$carry=0;
		}
		
		//MANUAL
		if($d1<0)
		{
			$carry1=$d1;
		}
		else
		{
			$carry1=0;
		}
		
		
		// WEEK 3 CALCULATION
		
		
		if($row1['type']=="Kanban")
		{
			//CNC
			$d=($md/4)+$carry;
			//MANAUL
			$d1=($md1/4)+$carry1;
		}
		if($row1['type']=="Regular")
		{
			//CNC
			$d=($md/4)+$carry;
			//MANUAL
			$d1=($md1/4)+$carry1;
		}
		if($row1['type']=="Stranger")
		{
			//CNC
			$d=$obw5-$carry;
			//MANUAl
			$d1=$obw5_1-$carry1;
			//CNC
			if($obw5==0)
			{
				$d=0;
			}
			//MANUAL
			if($obw5_1==0)
			{
				$d1=0;
			}
		}
		//CNC
		if($d<0)
		{
			//echo"<td>0</td>";
		}
		else
		{
			$cw3=round($d,0);
			//echo"<td>".round($d,0)."</td>";
		}
		//MANUAL
		if($d1<0)
		{
			//echo"<td>0</td>";
		}
		else
		{
			//echo"<td>".round($d1,0)."</td>";
			$mw3=round($d1,0);
		}
		
		//CNC
		if($d<0)
		{
			$carry=$d;
		}
		else
		{
			$carry=0;
		}
		//MANUAL
		if($d1<0)
		{
			$carry1=$d1;
		}
		else
		{
			$carry1=0;
		}
		
		//PRIORITY SETTING FOR w2 & W3 
		if($row1['type']=="Kanban")
		{
			$p2=2;
			$p3=5;
		}
		else if($row1['type']=="Regular")
		{
			$p2=3;
			$p3=6;
		}
		else
		{
			$p2=4;
			$p3=7;
		}
		


		
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['pn']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['type']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['monthly']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['vmi_fg']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['sf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['stock']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['stock1']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $row['']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['']);
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $row['']);
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $row['']);
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $row['order_date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $row['cqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $row['req_date']);
		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $row['commit']);
		
		$rowCount++;
	}
	
	
	$objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCount.":B".$rowCount.":C".$rowCount.":D".$rowCount.":E".$rowCount.":F".$rowCount.":G".$rowCount.":H".$rowCount.":I".$rowCount.":J".$rowCount.":K".$rowCount.":L".$rowCount.":M".$rowCount.":N")->applyFromArray($style);
	$objPHPExcel->getActiveSheet()->setTitle("PPC REPORT");
	/********************************************************************************************************************************************************************************************************************************/
	
	
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	//$objWriter->save('report\Report-O12-'.date("Y-m-d").'.xlsx');
	//$objWriter->save('D:\REPORT\Report-O12-'.date("Y-m-d").'.xlsx');
	//$time=date("Y-m-d");
	//mysqli_query($conn,"UPDATE autoreport SET daily='$time'");
	//echo "UPDATE autoreport SET daily='$time'";
	echo "Report generated";
	//header("location: inputlink.php");
?>