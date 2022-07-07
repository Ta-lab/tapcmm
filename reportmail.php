<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
//require_once "phpmailer/class.phpmailer.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$con=mysqli_connect('localhost','root','Tamil','mypcm');

//$message = 'Open Route Card Report more than 5 days.please find the attachment.Thanks';

$message = '<html><body>';
$message .= '';
$message .= '<table rules="all" width="600px" style="border-color: #666;" cellpadding="10">';
$message .= '<tr style="background: #eee;"><td><h1></h1></td></tr>';
// add body 
$message .= "<tr style='background: #463B85; color:white;'><td align='center'><a href='http://192.168.1.184/tapcm/' target='_blank'><b>Venkateswara Steels & Springs Pvt Ltd.</b></a></td></tr><tr style='background: #9E9B93;'><td align='center'><b>Daily Report Summary</b></td></tr>";

$message .= "</table>";
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

/*$query = "SELECT m12.operation,COUNT(*) as c FROM `d11` LEFT JOIN m12 ON d11.pnum=m12.pnum WHERE d11.operation='Stores' AND closedate='0000-00-00' AND (m12.operation='CNC Machine' || m12.operation='Straitening/Shearing') GROUP BY m12.operation";
$result = $con->query($query);
$row = mysqli_fetch_array($result);
$query1 = "SELECT operation,COUNT(*) as tc FROM (SELECT m12.operation,d11.rcno,datediff(NOW(),d11.date) as age FROM `d11` LEFT JOIN m12 ON d11.pnum=m12.pnum WHERE d11.operation='Stores' AND closedate='0000-00-00' AND (m12.operation='CNC Machine' || m12.operation='Straitening/Shearing') HAVING age>10) AS T GROUP BY operation";
$result1 = $con->query($query1);
$row1 = mysqli_fetch_array($result1);
$cc=$row1['tc'];
if($row['operation']=="CNC Machine")
{
	$cnc=$row['c'];
	if($cnc>=125)
	{
		$cs="CLOSED";
	}
	else
	{
		$cs=" OK ";
	}
}
$row = mysqli_fetch_array($result);
$row1 = mysqli_fetch_array($result1);
$sc=$row1['tc'];
if($row['operation']=="Straitening/Shearing")
{
	$sas=$row['c'];
	if($sas>=25)
	{
		$scs="CLOSED";
	}
	else
	{
		$scs=" OK ";
	}
}
// add footer
$message .= '<table style="text-align:center" rules="all" width="740px">';
$message .= "<br><br><tr style='background: #463B85; color:white;'><td colspan='5' align='center'><b> CNC,SHEARING & SHEET CUTTING STATUS </b></td></tr>";
$message .= '<tr><th> AREA </th><th> CRITERIA </th><th> No Of ROUTE CARD </th><th> STATUS </th><th> ABOVE 10 days </th></tr><tr>';
$message .= '<tr><td> CNC AREA </td><td> MAXIMUM 125 </td><td>'.$cnc.'</td><td>'.$cs.'</td><td>'.$cc.'</td></tr>';
$message .= '<tr><td> SHEARING / SHEET CUTTING </td><td> MAXIMUM 25 </td><td>'.$sas.'</td><td>'.$scs.'</td><td>'.$sc.'</td></tr>';
$message .= "</table>";
$dt=date('Y-m-d',strtotime('-8 days'));
$result1 = mysqli_query($con,"SELECT T1.foreman,IF(b IS NULL,0,b) as BRC,IF(c IS NULL,0,c) as CRC,IF(e IS NULL,0,e) as ERC FROM (SELECT foreman,COUNT(*) AS b FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN d12 ON d11.rcno=d12.rcno LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE d11.rcno LIKE 'B20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman) AS T1 LEFT JOIN (SELECT foreman,COUNT(*) AS c FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN d12 ON d11.rcno=d12.rcno LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE d11.rcno LIKE 'C20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman) AS T2 ON T1.foreman=T2.foreman LEFT JOIN (SELECT foreman,COUNT(*) AS e FROM (SELECT DISTINCT d11.rcno,foreman FROM `d11` LEFT JOIN d12 ON d11.rcno=d12.rcno LEFT JOIN m13 ON d12.pnum=m13.pnum WHERE d11.rcno LIKE 'E20____%' AND d11.date<='$dt' AND closedate='0000-00-00') AS T1 WHERE foreman!='' GROUP BY foreman) AS T3 ON T1.foreman=T3.foreman");
$message .= '<table style="text-align:center" rules="all" width="740px">';
$message .= "<br><br><tr style='background: #463B85; color:white;'><td colspan='5' align='center'><b> MANUAL AREA STATUS </b></td></tr>";
$message .= '<tr><th> AREA </th><th> CRITERIA </th><th> B ROUTE CARD </th><th> C ROUTE CARD </th><th> E ROUTE CARD </th></tr><tr>';
while($row = mysqli_fetch_array($result1))
{
	$f=$row['foreman'];
	$b=$row['BRC'];
	$bs="";
	if($b>10){$bs="color:red";}
	$c=$row['CRC'];
	$cs="";
	if($c>10){$cs="color:red";}
	$e=$row['ERC'];
	$es="";
	if($e>10){$es="color:red";}
	$message .= '<tr><td>'.$f.'</td><td> ROUTE CARDS MORE THAN 7 DAYS </td><td style="'.$bs.'">'.$b.'</td><td style="'.$cs.'">'.$c.'</td><td style="'.$es.'">'.$e.'</td></tr>';
}
$message .= "</table>";
$query = "SELECT COUNT(*) AS c FROM (SELECT date,scn,pnum,rcno,issqty,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE 'Subcontract' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) HAVING days>15) AS C";
$result = $con->query($query);
$row = mysqli_fetch_array($result);
$query1 = "SELECT COUNT(*) AS c FROM (SELECT T2.rcno,T2.date,T2.issqty,T2.pnum,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE 'ALFA N-IND PRIM' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) HAVING days>15 order by t2.date,t2.rcno) AS C";
$result1 = $con->query($query1);
$row1 = mysqli_fetch_array($result1);
$sc="OK";
$dc="OK";
if($row['c']>0)
{
	$sc="NOT OK";
}
if($row1['c']>0)
{
	$dc="NOT OK";
}
$message .= '<table style="text-align:center" rules="all" width="740px">';
$message .= "<br><br><tr style='background: #463B85; color:white;'><td colspan='5' align='center'><b> SUB CONTRACT - STATUS </b></td></tr>";
$message .= '<tr><th> AREA </th><th> INCHARGE </th><th> CRITERIA </th><th> NO OF DC </th><th> STATUS </th></tr><tr>';
$message .= '<tr><td> SUB-CONTRACT </td><td> GURUMOORTHY </td><td> DC > 15 Days Must Be Zero </td><td>'.$row['c'].'</td><td>'.$sc.'</td></tr>';
$message .= '<tr><td> ALFA + N-IND + PRIM + UNIT 2</td><td> SARAVANAN </td><td> STOCK > 15 Days Must Be Zero </td><td>'.$row1['c'].'</td><td>'.$dc.'</td></tr>';
$message .= "</table>";

//select distinct(PNUM) as pnum,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt='FG For Invoicing' GROUP BY prcno HAVING (sum(partreceived)-sum(partissued))>0 AND days>5 ORDER BY days DESC
$query = "SELECT COUNT(*) AS c FROM (select distinct(PNUM) as pnum,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt='FG For Invoicing' GROUP BY prcno HAVING (sum(partreceived)-sum(partissued))>0 AND days>5) AS T";
$result = $con->query($query);
$row = mysqli_fetch_array($result);
$query1 = "SELECT COUNT(*) AS c FROM (select distinct(PNUM) as pnum,prcno as prc,date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt='FG For S/C' GROUP BY prcno HAVING (sum(partreceived)-sum(partissued))>0 AND days>2) AS T";
$result1 = $con->query($query1);
$row1 = mysqli_fetch_array($result1);
$inv="OK";
$dc="OK";
if($row['c']>0)
{
	$inv="NOT OK";
}
if($row1['c']>0)
{
	$dc="NOT OK";
}


$message .= '<table style="text-align:center" rules="all" width="740px">';
$message .= "<br><br><tr style='background: #463B85; color:white;'><td colspan='5' align='center'><b> FG FOR INVOICE (FINAL STOCK) - STATUS </b></td></tr>";
$message .= '<tr><th> AREA </th><th> CRITERIA </th><th> NO OF ROUTE CARD </th><th> STATUS </th></tr><tr>';
$message .= '<tr><td> FG FOR INVOICING </td><td> STOCK > 5 Days Must Be Zero </td><td>'.$row['c'].'</td><td>'.$inv.'</td></tr>';
$message .= '<tr><td> FG FOR S/C </td><td> 2 DAYS SHOULD BE ZERO </td><td>'.$row1['c'].'</td><td>'.$dc.'</td></tr>';
$message .= "</table>";
*/

//Final Inspection Approval
$dty=date('Y-m-d',strtotime('-1 days'));
$query = "SELECT COUNT(*) AS co FROM `f_insp` WHERE apby='' AND status='F' AND date<='$dty'";
$result = $con->query($query);
$row = mysqli_fetch_array($result);
$finalsc="OK";
if($row['co']>0)
{
	$finalsc="NOT OK";
	$finalinsp = "LOCKED";
}

$message .= '<table style="text-align:center" rules="all" width="740px">';
$message .= "<br><br><tr style='background: #463B85; color:white;'><td colspan='6' align='center'><b> FINAL INSPECTION APPORVAL PENDING </b></td></tr>";
$message .= '<tr><th> AREA </th><th> INCHARGE </th><th> CRITERIA </th><th> NO OF RC </th><th> STATUS </th> <th> AREA STATUS </th> </tr><tr>';
$message .= '<tr><td> FINAL QUALITY </td><td> TAMIL </td><td> RC > 1 Day Must Be Zero </td><td>'.$row['co'].'</td><td>'.$finalsc.'</td><td>'.$finalinsp.'</td></tr>';
$message .= "</table>";


$dt=date('Y-m-d',strtotime('-1 days'));
$query1 = "SELECT s,COUNT(*) as c FROM (SELECT variance,IF((variance>5 || variance<-5),'n','o') AS s FROM (SELECT T2.rcno,T2.rmk,T2.closedate,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(T1.received IS NULL,'0',T1.received) as received,IF(T1.rejected IS NULL,'0',T1.rejected) as rejected,IF(T3.rmdesc IS NULL,'',T3.rmdesc) as rm,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as unit,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom,rate,per,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))/IF(T3.useage IS NULL,'1',T3.useage))*rate)/per as value,(((T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)))*100)/T2.issqty) AS variance FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,d11.rmk,d11.closedate,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='$dt' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman,rate,per FROM m13  LEFT JOIN pn_st ON pn_st.pnum=m13.pnum  left JOIN invmaster ON pn_st.invpnum=invmaster.pn GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) having notused!=0 order by t2.date,t2.rcno) AS T) AS TT GROUP BY s";
$result1 = $con->query($query1);
$row1 = mysqli_fetch_array($result1);
if($row1['s']=="n"){$n=$row1['c'];}else{$o=$row1['c'];}
$row1 = mysqli_fetch_array($result1);
if($row1['s']=="n"){$n=$row1['c'];}else{$o=$row1['c'];}
$message .= '<table style="text-align:center" rules="all" width="740px">';
$message .= "<br><br><tr style='background: #463B85; color:white;'><td colspan='5' align='center'><b> ROUTE CARD / DC CLOSED REPORT ON ".$dt." </b></td></tr>";
$message .= '<tr><th> NUMBER OF ROUTE CARD / DC CLOSED </th><th> CRITERIA </th><th> OK </th><th> NOT OK </th></tr><tr>';
$message .= '<tr><td>'.($o+$n).'</td><td> MAXIMUM VARIANCE - 5.00 % </td><td>'.$o.'</td><td>'.$n.'</td></tr>';
$message .= "<tr style='background: #9E9B93;'><td colspan='5' align='center'><b> Thank You </b></td></tr></table>";

$message .= "</body></html>";

//end of table

// creating the phpmailer object
$mail = new PHPMailer(true);

// telling the class to use SMTP
$mail->IsSMTP();

// enables SMTP debug information (for testing) set 0 turn off debugging mode, 1 to show debug result
$mail->SMTPDebug = 2;
echo !extension_loaded('openssl')?"Not Available":"Available";
// enable SMTP authentication
$mail->SMTPAuth = true;

// sets the prefix to the server
//$mail->SMTPSecure = 'ssl';
$mail->SMTPSecure = 'tls';

// sets GMAIL as the SMTP server
//$mail->Host = 'smtp.gmail.com';
$mail->Host = 'smtp-mail.outlook.com';

// set the SMTP port for the GMAIL server
//$mail->Port = 465;
$mail->Port = 587;

// your gmail address
//$mail->Username = 'srinivasanganesan@yahoo.com';
//$mail->Username = 'enterprisesol88@gmail.com';

//$mail->Username = 'erpinfoautoreport@gmail.com';
$mail->Username = 'enterpriseww@hotmail.com';


// your password must be enclosed in single quotes
$mail->Password = 'Enterprise@88';

// add a subject line
$mail->Subject = 'OPEN ROUTE CARD REPORT MORE THAN 5 DAYS ON -'.date("D-M-Y ");

// Sender email address and name
$mail->SetFrom('enterpriseww@hotmail.com', 'ERP');

// reciever address, person you want to send
$mail->AddAddress('androph45@gmail.com');
/*
$mail->AddAddress('msv@venkateswarasteels.com');
$mail->AddAddress('msa@venkateswarasteels.com');
$mail->AddAddress('ld@venkateswarasteels.com');
$mail->AddAddress('pur@venkateswarasteels.com');
$mail->AddAddress('r.naveen@venkateswarasteels.com');
$mail->AddAddress('edp@venkateswarasteels.com');
*/


//$mail->AddAddress('gsrini40@gmail.com');
//$mail->AddAddress('ldcbe5@gmail.com');
//$mail->AddAddress('pur@venkateswarasteels.com');
//$mail->AddAddress('enterpriseexcel@yahoo.com');
//$mail->AddAddress('ldp@venkateswarasteels.com');
//$mail->AddAddress('msa@venkateswarasteels.com');
//$mail->AddAddress('msv@venkateswarasteels.com');
//$mail->AddAddress('accounts@venkateswarasteels.com');*/
// if your send to multiple person add this line again
//$mail->AddAddress('tosend@domain.com');

// if you want to send a carbon copy
//$mail->AddCC('tosend@domain.com');


// if you want to send a blind carbon copy
//$mail->AddBCC('tosend@domain.com');
// add message body

$time=date("Y-m-d");

//$mail->AddAttachment('C:\xampp\htdocs\tapcm\report\Report-O12-'.$time.'.xlsx');

//$mail->AddAttachment('C:\xampp\htdocs\tapcm\report\VALUATION-Report-O12-'.$time.'.xlsx');

$mail->MsgHTML($message);
mysqli_query($con,"UPDATE autoreport SET daily='$time'");
// add attachment if any

try {
    // send mail
    $mail->Send();
    $msg = "Mail send successfully";
} catch (phpmailerException $e) {
    //$msg = $e->getMessage();
} catch (Exception $e) {
    //$msg = $e->getMessage();
}

echo $message;

if ($mail) { ?>
 <script language="javascript" type="text/javascript">
  //alert('Thank You Starting ERP...Please Click Ok to Continue...');
  //window.location.href = 'inputlink.php';
 </script>
 <?php
 }else { ?>
  <script language="javascript" type="text/javascript">
   //alert('Message failed...Please Contact ERP Team');
   //window.location.href = 'inputlink.php';
  </script>
 <?php } ?>