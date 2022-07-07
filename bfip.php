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
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:5px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:5px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-e3zv{font-weight:bold}
.tg .tg-hgcj{font-weight:bold;text-align:center}
.tg .tg-uiv9{vertical-align:bottom}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}</style>
<div class="tg-wrap"><table class="tg">
  <tr>
    <th class="tg-031e" colspan="2"></th>
    <th class="tg-hgcj" colspan="16">Inspection Report</th>
  </tr>
  <tr>
    <td class="tg-e3zv" colspan="2">PART NAME</td>
    <td class="tg-031e" colspan="8"><?php echo $row['pd']; ?></td>
    <td class="tg-hgcj" colspan="8">All Dimentions are in 'mm'</td>
  </tr>
  <tr>
    <td class="tg-e3zv" colspan="2">PART NUMBER</td>
    <td class="tg-031e" colspan="8"><?php echo $row['pn']; ?></td>
    <td class="tg-e3zv" colspan="2">Inspection No & Date</td>
    <td class="tg-031e" colspan="6"><?php echo $t1." @ ".$dt; ?></td>
  </tr>
  <tr>
    <td class="tg-e3zv" colspan="2">REV. No &amp; Date</td>
    <td class="tg-031e" colspan="8"></td>
    <td class="tg-e3zv" colspan="2">Lot Qty</td>
    <td class="tg-031e" colspan="6"><?php echo $row['qty']; ?></td>
  </tr>
  <tr>
    <td class="tg-e3zv" colspan="2">Supplier Name</td>
    <td class="tg-031e" colspan="8">VENKATESWARA STEELS AND SPRINGS (INDIA) PVT LTD</td>
    <td class="tg-e3zv" colspan="2">Sample Qty</td>
    <td class="tg-031e" colspan="6">5 Nos</td>
  </tr>
  <tr>
    <td class="tg-hgcj" rowspan="3">S. No</td>
    <td class="tg-hgcj" colspan="3" rowspan="2">Characteristic / Nominal Value / Tolerance / Unit </td>
    <td class="tg-hgcj" colspan="7">BOSCH</td>
    <td class="tg-hgcj" colspan="7">SUPPLIER</td>
  </tr>
  <tr>
    <td class="tg-hgcj" rowspan="2">Evaluation Method</td>
    <td class="tg-hgcj" colspan="5">ACTUAL VALUES</td>
    <td class="tg-hgcj" rowspan="2">RESULT</td>
    <td class="tg-hgcj" rowspan="2">EVALUATION METHOD</td>
    <td class="tg-hgcj" colspan="5">ACTUAL VALUES</td>
    <td class="tg-hgcj" rowspan="2">RESULT</td>
  </tr>
  <tr>
    <td class="tg-hgcj">Characteristic</td>
    <td class="tg-hgcj">Min</td>
    <td class="tg-hgcj">Max</td>
    <td class="tg-hgcj">1</td>
    <td class="tg-hgcj">2</td>
    <td class="tg-hgcj">3</td>
    <td class="tg-hgcj">4</td>
    <td class="tg-hgcj">5</td>
    <td class="tg-hgcj">Samp-1</td>
    <td class="tg-hgcj">Samp-2</td>
    <td class="tg-hgcj">Samp-3</td>
    <td class="tg-hgcj">Samp-4</td>
    <td class="tg-hgcj">Samp-5</td>
  </tr>
  <?php
	while($row3 = mysqli_fetch_array($result3))
	{
		echo'<tr>
			<td class="tg-031e">'.$row3['sno'].'</td>
			<td class="tg-031e">'.$row3['chars'].'</td>
			<td class="tg-031e">'.$row3['lsl'].'</td>
			<td class="tg-031e">'.$row3['usl'].'</td>
			<td class="tg-031e">'.$row3['method'].'</td>
			<td class="tg-031e">'.$row3['s1'].'</td>
			<td class="tg-031e">'.$row3['s2'].'</td>
			<td class="tg-031e">'.$row3['s3'].'</td>
			<td class="tg-031e">'.$row3['s4'].'</td>
			<td class="tg-031e">'.$row3['s5'].'</td>
			<td class="tg-031e"></td>
			<td class="tg-031e"></td>
			<td class="tg-031e"></td>
			<td class="tg-031e"></td>
			<td class="tg-031e"></td>
			<td class="tg-031e"></td>
			<td class="tg-031e"></td>
			<td class="tg-031e"></td>
		</tr>';
	}
  ?>
  <tr>
    <td class="tg-031e" colspan="2">SUPPLIER</td>
    <td class="tg-031e" colspan="9"></td>
    <td class="tg-031e">RBDI</td>
    <td class="tg-031e" colspan="3"></td>
    <td class="tg-uiv9" colspan="3" rowspan="2">Signature of the person responsible</td>
  </tr>
  <tr>
    <td class="tg-031e"></td>
    <td class="tg-031e" colspan="3"></td>
    <td class="tg-031e" colspan="7">Signature of the person responsible</td>
    <td class="tg-031e">DATE</td>
    <td class="tg-031e" colspan="3"></td>
  </tr>
</table></div>