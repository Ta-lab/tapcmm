<?php
$dt="";
$from = $_GET['from'];
$cc = $_GET['cc'];						
$con = mysqli_connect('localhost','root','Tamil','mypcm');
$invno=$_GET['from'];
$cc=$_GET['cc'];
$result1 = $con->query("SELECT cname,cname1,cadd1,cadd2,cadd3,vc,pn,pd,qty from inv_det where invno='$invno'");
$row = mysqli_fetch_array($result1);
$pnum=$row['pn'];
$result3 = $con->query("SELECT pnum from pn_st where invpnum='$pnum'");
$row3 = mysqli_fetch_array($result3);
$t=$row3['pnum'];
$result3 = $con->query("SELECT count(*) as c from m12 where pnum='$t' and operation='FG For S/C'");
$row3 = mysqli_fetch_array($result3);
$t=$row3['c'];
if($t==0)
{
	//fg for Invoicing
	$result3 = $con->query("SELECT prcno from d12 where rcno='$from'");
	$row3 = mysqli_fetch_array($result3);
	$t=$row3['prcno'];
	$result3 = $con->query("SELECT fi_id,date from fi_rcno where rcno='$t'");
	$row3 = mysqli_fetch_array($result3);
	$t1=$row3['fi_id'];
	$dt=$row3['date'];
	$result3 = $con->query("SELECT * FROM `fi_report` where insno='$t1' and rcno='$t'");
}
else
{
	// fg for s/c
	$result3 = $con->query("SELECT prcno from d12 where rcno='$from'");
	$row3 = mysqli_fetch_array($result3);
	$t=$row3['prcno'];
	$result3 = $con->query("SELECT prcno from d12 where rcno='$t'");
	$row3 = mysqli_fetch_array($result3);
	$t=$row3['prcno'];
	$result3 = $con->query("SELECT fi_id,date from fi_rcno where rcno='$t'");
	$row3 = mysqli_fetch_array($result3);
	$t1=$row3['fi_id'];
	$dt=$row3['date'];
	$result3 = $con->query("SELECT * FROM `fi_report` where insno='$t1' and rcno='$t'");
}
?>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:4px 4px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:4px 4px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-baqh{text-align:center;vertical-align:top}
.tg .tg-bn4o{font-weight:bold;font-size:18px;text-align:center;vertical-align:top}
.tg .tg-ygzf{font-weight:bold;font-size:15px;text-align:center;vertical-align:center}
.tg .tg-yw4l{vertical-align:top}
.tg .tg-l2oz{font-weight:bold;text-align:right;vertical-align:top}
.tg .tg-9hbo{font-weight:bold;vertical-align:top}
.tg .tg-amwm{font-weight:bold;text-align:center;vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}</style>
<div class="tg-wrap"><table class="tg">
  <tr>
    <th class="tg-yw4l" colspan="2" style="padding : 8px" rowspan="2"></th>
    <th class="tg-bn4o" colspan="19">MULTITECH INDUSTRIES INDIA PRIVATE LIMITED</th>
  </tr>
  <tr>
    <td class="tg-bn4o" colspan="19">RECEIVING INSPECTION REPORT</td>
  </tr>
  <tr>
    <td class="tg-l2oz" colspan="3">PART NAME : </td>
    <td class="tg-yw4l" colspan="18"><?php echo $row['pd']; ?></td>
  </tr>
  <tr>
    <td class="tg-l2oz" colspan="3">PART NO/REV NO :</td>
    <td class="tg-yw4l" colspan="4"><?php echo $row['pn']; ?></td>
    <td class="tg-ygzf" colspan="14" rowspan="3">MATERIAL CERTIFICATE / PLATING CERTIFICATE / HEAT TREATMENT CERTIFICATE WHICHEVER IS APPLICABLE.MUST BE ATTACHED TO THE REPORT</td>
  </tr>
  <tr>
    <td class="tg-l2oz" colspan="3">SUPPLIER :</td>
    <td class="tg-yw4l" colspan="4">VSS IPL</td>
  </tr>
  <tr>
    <td class="tg-l2oz" colspan="3">SUP Invoice No :</td>
    <td class="tg-yw4l" colspan="4"><?php echo $from; ?></td>
  </tr>
  <tr>
    <td class="tg-l2oz" colspan="3">MTI / GRN No / DATE :</td>
    <td class="tg-yw4l" colspan="4"></td>
    <td class="tg-9hbo" colspan="7">SUPPLIER INSPECTION DETAILS</td>
    <td class="tg-9hbo" colspan="7">MULTITECH INSPECTION DETAILS</td>
  </tr>
  <tr>
    <td class="tg-9hbo" rowspan="2">Sl. No</td>
    <td class="tg-9hbo" colspan="3" rowspan="2">PARAMETER</td>
    <td class="tg-9hbo">DRAWING DIMENTION</td>
    <td class="tg-9hbo" colspan="2">SPECIFICATION / LIMIT</td>
    <td class="tg-9hbo" colspan="4">Supplied Qty:</td>
    <td class="tg-9hbo" colspan="3">Inspected Qty : </td>
    <td class="tg-9hbo" colspan="4">Received Qty :</td>
    <td class="tg-9hbo" colspan="3">Inspected Qty :</td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-amwm">LSL</td>
    <td class="tg-amwm">USL</td>
    <td class="tg-amwm">Sl No 1</td>
    <td class="tg-amwm">Sl No 2</td>
    <td class="tg-amwm">Sl No 3</td>
    <td class="tg-amwm">Sl No 4</td>
    <td class="tg-amwm">Sl No 5</td>
    <td class="tg-amwm">OK</td>
    <td class="tg-amwm">NOT OK</td>
    <td class="tg-amwm">Sl No 1</td>
    <td class="tg-amwm">Sl No 2</td>
    <td class="tg-amwm">Sl No 3</td>
    <td class="tg-amwm">Sl No 4</td>
    <td class="tg-amwm">Sl No 5</td>
    <td class="tg-amwm">OK</td>
    <td class="tg-amwm">NOT OK</td>
  </tr>
  <?php
	while($row3 = mysqli_fetch_array($result3))
	{
		echo'<tr>
    <td class="tg-baqh" rowspan="2">'.$row3['sno'].'</td>
    <td class="tg-baqh" rowspan="2" colspan="3">'.$row3['chars'].'</td>
    <td class="tg-baqh" rowspan="2">'.$row3['drawspec'].'</td>
    <td class="tg-baqh" rowspan="2">'.$row3['lsl'].'</td>
    <td class="tg-baqh" rowspan="2">'.$row3['usl'].'</td>
    <td class="tg-baqh">'.$row3['s1'].'</td>
    <td class="tg-baqh">'.$row3['s2'].'</td>
    <td class="tg-baqh">'.$row3['s3'].'</td>
    <td class="tg-baqh">'.$row3['s4'].'</td>
    <td class="tg-baqh">'.$row3['s5'].'</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
  </tr>
  <tr>
    <td class="tg-baqh">-</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
	<td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
  </tr>';
	}
  ?>
  <tr>
    <td class="tg-yw4l" colspan="7">REMARKS : </td>
    <td class="tg-yw4l" colspan="4">INSPECTED BY :</td>
    <td class="tg-yw4l" colspan="3">APPROVED BY :</td>
    <td class="tg-yw4l" colspan="4">INSPECTED BY :</td>
    <td class="tg-yw4l" colspan="3">APPROVED BY :</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="21">NOTE : Irrespective to the sampling plan the dimensional results will be entered for 10 nos only, If the sampling size exceeds more than 10 nos</td>
  </tr>
</table></div>
<?php
//<img src="img/mfip.png" alt="Mountain View" style="width:30%;height:15%;">
?>