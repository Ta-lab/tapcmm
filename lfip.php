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
.tg .tg-amwm{font-weight:bold;text-align:center;vertical-align:top}
.tg .tg-hgcj{font-weight:bold;text-align:center}
.tg .tg-yw4l{vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}</style>
<div class="tg-wrap"><table class="tg">
  <tr>
    <th class="tg-amwm" colspan="15">FINAL INSPECTION REPORT</th>
  </tr>
  <tr>
    <td class="tg-hgcj" colspan="4" rowspan="4">VENKATESWARA STEELS &amp; SPRINGS (INDIA) PRIVATE LIMITED<br><br>1/89-6, RAVATHUR PIRIVU , KANNAMPALAYAM.</td>
    <td class="tg-e3zv">DELIVERY CHALLAN No </td>
    <td class="tg-031e" colspan="4"><?php echo $from; ?></td>
    <td class="tg-e3zv" colspan="2">PRODUCT NO</td>
    <td class="tg-031e" colspan="4"><?php echo $row['pn']; ?></td>
  </tr>
  <tr>
    <td class="tg-e3zv">TOTAL QUANTITY</td>
    <td class="tg-031e" colspan="4"><?php echo $row['qty']; ?></td>
    <td class="tg-e3zv" colspan="2">PRODUCT NAME</td>
    <td class="tg-031e" colspan="4"><?php echo $row['pd']; ?></td>
  </tr>
  <tr>
    <td class="tg-e3zv">SAMPLING QUANITY</td>
    <td class="tg-031e" colspan="4">5 Nos</td>
    <td class="tg-e3zv" colspan="2">OPERATION NO</td>
    <td class="tg-031e" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-e3zv">INSPECTION REPORT No</td>
    <td class="tg-031e" colspan="4"><?php echo $t1; ?></td>
    <td class="tg-e3zv" colspan="2">OPERATION DESC</td>
    <td class="tg-031e" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-amwm" colspan="15">VENDOR OBSERVATION</td>
  </tr>
  <tr>
    <td class="tg-hgcj" rowspan="2">Sl . No</td>
    <td class="tg-hgcj" rowspan="2">CHARACTERISTICS</td>
    <td class="tg-hgcj" rowspan="2">SPECIFICATIONS</td>
    <td class="tg-hgcj" rowspan="2">TOL'S</td>
    <td class="tg-hgcj" rowspan="2">METHOD OF INSPECTION</td>
    <td class="tg-amwm" colspan="5">VENDOR OBSERVATION</td>
    <td class="tg-amwm" colspan="5">L.G.B OBSERVATION</td>
  </tr>
  <tr>
    <td class="tg-hgcj">1</td>
    <td class="tg-hgcj">2</td>
    <td class="tg-hgcj">3</td>
    <td class="tg-hgcj">4</td>
    <td class="tg-hgcj">5</td>
    <td class="tg-hgcj">Samp-1</td>
    <td class="tg-hgcj">Samp-2</td>
    <td class="tg-hgcj">Samp-3</td>
    <td class="tg-hgcj">Samp-4</td>
    <td class="tg-amwm">Samp-5</td>
  </tr>
  <?php
	while($row3 = mysqli_fetch_array($result3))
	{
		echo '<tr>
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
			<td class="tg-yw4l"></td>
		  </tr>';
	}
  ?>
  <tr>
    <td class="tg-031e" colspan="10" rowspan="2">REMARKS : </td>
    <td class="tg-031e" colspan="5">REMARKS :</td>
  </tr>
  <tr>
    <td class="tg-e3zv">ACC</td>
    <td class="tg-e3zv">REJ</td>
    <td class="tg-e3zv">REW</td>
    <td class="tg-e3zv" colspan="2">ACC WITH DIV</td>
  </tr>
  <tr>
    <td class="tg-031e" colspan="4">INSPECTED BY :</td>
    <td class="tg-yw4l" colspan="6" rowspan="2">CONCLUSION ACCEPTED OR REJECTED</td>
    <td class="tg-031e" colspan="5">INSPECTED BY :</td>
  </tr>
  <tr>
    <td class="tg-031e" colspan="4">APPROVED BY :</td>
    <td class="tg-031e" colspan="5">APPROVED BY :</td>
  </tr>
</table></div>