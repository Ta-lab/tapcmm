<?php

if(isset($_POST['submit']))
{
	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	$box=$_POST['box'];
	$inv=$_POST['from'];
	$batch=$_POST['bat'];
	$batch1=$_POST['bat1'];
	$batch2=$_POST['bat2'];
	$chk=$_POST['crack_check'];
	//$result = mysqli_query($con,"SELECT invno,invdt,pn,pd,ccode,vc,qty FROM `inv_det` WHERE invno='$inv'");
	//$r=$result->num_rows;
	//$row = mysqli_fetch_array($result);
	//$invdt=$row['invdt'];
	//$invno=$row['invno'];
	//$pn=$row['pn'];
	//$pd=$row['pd'];
	//$ccode=$row['ccode'];
	//$vc=$row['vc'];
	//$qty=$row['qty'];
	//$bqty=$qty/$box;
	//$result1 = mysqli_query($con,"SELECT ino FROM `invmaster` where ccode='$ccode' and pn='$pn'");
	//$r=$result1->num_rows;
	//$row1 = mysqli_fetch_array($result1);
	//$iss=$row1['ino'];
for($i=1;$i<=$box;$i++)
{
$myfile = fopen("D:\\qr.prn", "w") or die("Unable to open file!");
$txt = "";
$txt='SIZE 78.5 mm, 50 mm
DIRECTION 0,0
REFERENCE 0,0
OFFSET 0 mm
SET PEEL OFF
SET CUTTER OFF
SET PARTIAL_CUTTER OFF
SET TEAR ON
CLS
CODEPAGE 1252
TEXT 576,328,"ROMAN.TTF",180,1,15,"Heat No"
TEXT 576,253,"ROMAN.TTF",180,1,15,"Lot No"
TEXT 576,190,"ROMAN.TTF",180,1,15,"Coil No"
TEXT 576,108,"ROMAN.TTF",180,1,15,"Crack Check"
TEXT 355,328,"ROMAN.TTF",180,1,15,"'.$batch.'"
TEXT 353,253,"ROMAN.TTF",180,1,15,"'.$batch1.'"
TEXT 356,190,"ROMAN.TTF",180,1,15,"'.$batch2.'"
TEXT 350,108,"ROMAN.TTF",180,1,15,"'.$chk.'"
TEXT 410,108,"ROMAN.TTF",180,1,15,":"
TEXT 401,190,"ROMAN.TTF",180,1,15,":"
TEXT 401,253,"ROMAN.TTF",180,1,15,":"
TEXT 401,328,"ROMAN.TTF",180,1,15,":"
BOX 24,6,600,374,3
PRINT 1,1
';
fwrite($myfile, $txt);
fclose($myfile);
//system("COPY D:\\\\qr.prn \\\\192.168.1.248\\TSC");
system("COPY D:\\\\qr.prn \\\\192.168.1.143\\TSCTTP");
}

}
header("location: inputlink.php");

//system("NET USE $port /d");
//system("COPY D:\\\\qr.prn \\\\192.168.1.248\\TTP");
?>