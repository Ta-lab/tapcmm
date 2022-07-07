<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
//require_once "phpmailer/class.phpmailer.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$con=mysqli_connect('localhost','root','Tamil','mypcm');

$message = 'CNC,Shearing & Sheetcutting is Locked';

$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

$query = "SELECT m12.operation,COUNT(*) as c FROM `d11` LEFT JOIN m12 ON d11.pnum=m12.pnum WHERE d11.operation='Stores' AND closedate='0000-00-00' AND (m12.operation='CNC Machine' || m12.operation='Straitening/Shearing') GROUP BY m12.operation";
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
$mail->Subject = 'CNC LOCKED';

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
mysqli_query($con,"UPDATE `lock_mechanism` SET lock_date='$time' WHERE lock_area='CNC'");
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
  alert('CNC/SHEARING is LOCKED...Please Click Ok to Continue...');
  window.location.href = 'inputlink.php';
 </script>
 <?php
 }else { ?>
  <script language="javascript" type="text/javascript">
   alert('Message failed...Please Contact ERP Team');
   window.location.href = 'inputlink.php';
  </script>
 <?php } ?>