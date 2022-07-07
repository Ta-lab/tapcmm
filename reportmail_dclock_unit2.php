<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
//require_once "phpmailer/class.phpmailer.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$con=mysqli_connect('localhost','root','Tamil','mypcm');

$message = 'DC Raising is Locked for VSS UNIT 2.';

$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

$dt=date('Y-m-d',strtotime('-15 days'));

$query = "SELECT COUNT(*) AS DCCOUNT FROM(SELECT * FROM `dc_det` WHERE dcdate<='$dt' AND scn='VSS U-2') AS TDC
			LEFT JOIN(SELECT * FROM `d11`) AS TD11 ON TD11.rcno=CONCAT('DC-',TDC.dcnum) WHERE TD11.closedate='0000-00-00' AND TD11.operation='FG For S/C'";	

//$query = "SELECT COUNT(*) AS c FROM (SELECT date,scn,pnum,rcno,issqty,used,notused,days,foreman FROM (SELECT * FROM `dc_det`) AS T1 JOIN (SELECT T2.rcno,T2.operation,IF(T2.rcno LIKE 'A20%','CNC/SHEAR..',IF(T2.operation LIKE 'A%','Saravanan',IF(T2.operation LIKE 'SUB%','GURUMOORTHY',T5.foreman))) as foreman,T2.date,T2.issqty,T2.pnum,IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,T2.issqty-IF(((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) IS NULL,'0',(T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as notused,datediff(NOW(),T2.date) as days,IF(T3.useage IS NULL,'1',T3.useage) as bom FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE m14.oper LIKE 'Subcontract' AND d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno  LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T2.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT DISTINCT(m13.pnum),foreman FROM m13 GROUP BY pnum) AS T5 ON (T2.pnum=T5.pnum) order by t2.date,t2.rcno) AS T2 ON T2.rcno=CONCAT('DC-',T1.dcnum) HAVING days>15) AS C";
$result = $con->query($query);
$row = mysqli_fetch_array($result);
$sc="OK";
if($row['DCCOUNT']>0)
{
	$sc="NOT OK";
}

$message .= '<table style="text-align:center" rules="all" width="740px">';
$message .= "<br><br><tr style='background: #463B85; color:white;'><td colspan='5' align='center'><b> VSS UNIT 2 - STATUS </b></td></tr>";
$message .= '<tr><th> AREA </th><th> INCHARGE </th><th> CRITERIA </th><th> NO OF DC </th><th> STATUS </th></tr><tr>';
$message .= '<tr><td> VSS UNIT 2 </td><td> SURENDAR & SARAVANAN </td><td> DC > 15 Days Must Be Zero </td><td>'.$row['DCCOUNT'].'</td><td>'.$sc.'</td></tr>';
$message .= "</table>";

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
$mail->SMTPSecure = 'ssl';

// sets GMAIL as the SMTP server
$mail->Host = 'smtp.gmail.com';

// set the SMTP port for the GMAIL server
$mail->Port = 465;

// your gmail address
//$mail->Username = 'srinivasanganesan@yahoo.com';
//$mail->Username = 'enterprisesol88@gmail.com';
$mail->Username = 'erpinfoautoreport@gmail.com';


// your password must be enclosed in single quotes
$mail->Password = 'Enterprise@88';

// add a subject line
$mail->Subject = 'DC RAISING LOCKED';

// Sender email address and name
$mail->SetFrom('erpinfoautoreport@gmail.com', 'ERP');

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
//mysqli_query($con,"UPDATE autoreport SET daily='$time'");
mysqli_query($con,"UPDATE `lock_mechanism` SET lock_date='$time' WHERE lock_area='DC LOCK U2'");
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
  alert('DC Raising is Locked Due to DC Having More than 15 days...Please Click Ok to Continue...');
  window.location.href = 'inputlink.php';
 </script>
 <?php
 }else { ?>
  <script language="javascript" type="text/javascript">
   alert('Message failed...Please Contact ERP Team');
   window.location.href = 'inputlink.php';
  </script>
 <?php } ?>