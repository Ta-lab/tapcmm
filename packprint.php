<?php

if(isset($_POST['submit']))
{
	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	$box=$_POST['box'];
	$inv=$_POST['from'];
	$batch=$_POST['bat'];
	$result = mysqli_query($con,"SELECT invno,invdt,pn,pd,ccode,vc,qty FROM `inv_det` WHERE invno='$inv'");
	$r=$result->num_rows;
	$row = mysqli_fetch_array($result);
	$invdt=$row['invdt'];
	$invno=$row['invno'];
	$pn=$row['pn'];
	$pd=$row['pd'];
	$ccode=$row['ccode'];
	$vc=$row['vc'];
	$qty=$row['qty'];
	$bqty=$qty/$box;
	$result1 = mysqli_query($con,"SELECT ino FROM `invmaster` where ccode='$ccode' and pn='$pn'");
	$r=$result1->num_rows;
	$row1 = mysqli_fetch_array($result1);
	$iss=$row1['ino'];
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
TEXT 576,328,"ROMAN.TTF",180,1,8,"Supplier Code"
TEXT 576,300,"ROMAN.TTF",180,1,8,"Part Name"
TEXT 576,273,"ROMAN.TTF",180,1,8,"Part No"
TEXT 576,245,"ROMAN.TTF",180,1,8,"Issue No"
TEXT 576,218,"ROMAN.TTF",180,1,8,"Invoice No"
TEXT 576,191,"ROMAN.TTF",180,1,8,"Inv Quantity"
TEXT 576,163,"ROMAN.TTF",180,1,8,"Box Qty"
TEXT 576,136,"ROMAN.TTF",180,1,8,"No of Box / Cover"
TEXT 576,108,"ROMAN.TTF",180,1,8,"Mfg. Date"
TEXT 576,81,"ROMAN.TTF",180,1,8,"Batch ID "
TEXT 549,363,"ROMAN.TTF",180,1,9,"Venkateswara Steels & Springs (India) Pvt. Ltd."
TEXT 355,328,"ROMAN.TTF",180,1,8,"'.$vc.'"
TEXT 353,300,"ROMAN.TTF",180,1,8,"'.$pd.'"
TEXT 356,273,"ROMAN.TTF",180,1,8,"'.$pn.'"
TEXT 350,245,"ROMAN.TTF",180,1,8,"'.$iss.'"
TEXT 352,218,"ROMAN.TTF",180,1,8,"'.$invno.'"
TEXT 347,191,"ROMAN.TTF",180,1,8,"'.$qty.'"
TEXT 347,163,"ROMAN.TTF",180,1,8,"'.$bqty.'"
TEXT 344,136,"ROMAN.TTF",180,1,8,"'.$i.' / '.$box.'"
TEXT 359,108,"ROMAN.TTF",180,1,8,"'.$invdt.'"
TEXT 346,81,"ROMAN.TTF",180,1,8,"'.$batch.'"
TEXT 401,136,"ROMAN.TTF",180,1,8,":"
TEXT 401,109,"0",180,8,9,":"
TEXT 401,81,"ROMAN.TTF",180,1,8,":"
TEXT 401,163,"ROMAN.TTF",180,1,8,":"
TEXT 401,191,"ROMAN.TTF",180,1,8,":"
TEXT 401,218,"ROMAN.TTF",180,1,8,":"
TEXT 401,245,"ROMAN.TTF",180,1,8,":"
TEXT 401,273,"ROMAN.TTF",180,1,8,":"
TEXT 401,300,"ROMAN.TTF",180,1,8,":"
TEXT 401,328,"ROMAN.TTF",180,1,8,":"
TEXT 600,39,"ROMAN.TTF",180,1,9,"'.$vc.' : '.$pn.' : '.$iss.' : '.$batch.' : '.$qty.' : '.$invno.'"
QRCODE 219,276,L,6,A,180,M2,S7,"'.$vc.' : '.$pn.' : '.$iss.' : '.$batch.' : '.$qty.' : '.$invno.'"
BOX 24,6,600,374,3
BAR 25, 43, 571, 3
PRINT 1,1
';
fwrite($myfile, $txt);
fclose($myfile);
//system("COPY D:\\\\qr.prn \\\\192.168.1.248\\TSC");
system("COPY D:\\\\qr.prn \\\\192.168.1.121\\TSCTTPU");
}

}
header("location: inputlink.php");

//system("NET USE $port /d");
//system("COPY D:\\\\qr.prn \\\\192.168.1.248\\TTP");
?>