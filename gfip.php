<?php
$from = $_GET['from'];
$cc = $_GET['cc'];						
$con = mysqli_connect('localhost','root','Tamil','mypcm');
$invno=$_GET['from'];
$cc=$_GET['cc'];
$result1 = $con->query("SELECT cname,cname1,cadd1,cadd2,cadd3,vc,pn,pd,qty from inv_det where invno='$invno'");
$row = mysqli_fetch_array($result1);
$pnum=$row['pn'];
$result3 = $con->query("SELECT * from fi_detail where ccode='$cc' and pnum='$pnum'");
echo "SELECT * from fi_detail where ccode='$cc' and pnum='$pnum'";
$result2 = $con->query("SELECT max(`s.no` from fi_detail where ccode='$cc' and pnum='$pnum'");
$row1 = mysqli_fetch_array($result2);
?>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:7px 20px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:7px 20px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-nrw1{font-size:10px;text-align:center;vertical-align:top}
.tg .tg-baqh{text-align:center;vertical-align:top}
.tg .tg-7fgq{font-weight:bold;font-family:"Comic Sans MS", cursive, sans-serif !important;;text-align:center;vertical-align:top}
.tg .tg-amwm{font-weight:bold;text-align:center;vertical-align:top}
.tg .tg-yw4l{vertical-align:top}
</style>
<table class="tg" style="undefined;table-layout: fixed; width: 1022px">
<colgroup>
<col style="width: 86.66667px">
<col style="width: 166.66667px">
<col style="width: 86.66667px">
<col style="width: 95.66667px">
<col style="width: 88.66667px">
<col style="width: 88.66667px">
<col style="width: 90.66667px">
<col style="width: 86.66667px">
<col style="width: 106.66667px">
<col style="width: 76.66667px">
<col style="width: 48.66667px">
</colgroup>
  <tr>
    <th class="tg-7fgq" colspan="5" >VENKATESWARA STEELS & SPRINGS<br></th>
    <th class="tg-baqh" colspan="17">FINAL INSPECTION REPORT</th>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">CUSTOMER</td>
    <td class="tg-baqh" colspan="6">GILBARCO VEEDER ROOT INDIA PRIVATE LIMITED</td>
	<td class="tg-baqh" colspan="1">Drg. Rev. No & Date</td>
	<td class="tg-baqh" colspan="2">A/31.12.2011</td>
	<td class="tg-baqh" colspan="2">P>O No & Date</td>
	<td class="tg-baqh" colspan="2"></td>
	<td class="tg-baqh" colspan="1">Inspected Qty</td>
	<td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">MASTER ROUTE CARD #</td>
    <td class="tg-amwm" colspan="3"><?php echo $rcno; ?></td>
    <td class="tg-baqh" colspan="3" rowspan="2"><img src="barcode.php?text=<?php echo $rcno; ?>" alt="testing" /></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">PART NUMBER</td>
    <td class="tg-baqh" colspan="3"><?php echo $pnum; ?></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">Size with Grade / Coil size</td>
    <td class="tg-baqh" colspan="3"><?php echo $rm; ?></td>
    <td class="tg-baqh" colspan="2">Coil Number</td>
    <td class="tg-baqh" colspan="2">Coil No</td>
    <td class="tg-baqh" colspan="2"><?php echo $cno; ?></td>
  </tr>
  <tr>
    <td class="tg-nrw1" colspan="2">Raw Material Lot s.no - Heat code</td>
    <td class="tg-baqh" colspan="3"><?php echo $hno; ?></td>
    <td class="tg-baqh" colspan="2">RM.T.C Number</td>
    <td class="tg-baqh" colspan="4"><?php echo $fch1['tcno']; ?></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">Manufacturer</td>
    <td class="tg-baqh" colspan="3"><?php ?></td>
    <td class="tg-baqh" colspan="2">Supplier Name</td>
    <td class="tg-baqh" colspan="4"><?php echo $fch['sname']; ?></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">Gin Number and Date</td>
    <td class="tg-baqh" colspan="2"><?php echo $fch['grnnum']; ?></td>
    <td class="tg-baqh" colspan="2"><?php echo $fch['date']; ?></td>
    <td class="tg-baqh" colspan="1">Inv No &amp; Date</td>
    <td class="tg-baqh" colspan="2"><?php echo $fch['inv_no']; ?></td>
    <td class="tg-baqh" colspan="2"><?php echo $fch['inv_date']; ?></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">WORK ORDER QTY</td>
    <td class="tg-baqh" colspan="2"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="3">REQUIRED WEIGHT IN KG</td>
    <td class="tg-baqh" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">Physical issue coil weight</td>
    <td class="tg-baqh" colspan="2"><?php echo $rmiq; ?></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="3">Bal RETURN TO STORES WT</td>
    <td class="tg-baqh" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">Stores Person Name</td>
    <td class="tg-baqh" colspan="2"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">Coil Loading DATE &amp; shift &amp; Time</td>
    <td class="tg-baqh" colspan="2">Date</td>
    <td class="tg-baqh" colspan="2">Shift</td>
    <td class="tg-baqh" colspan="2">Time</td>
    <td class="tg-baqh" colspan="3">Total running hours</td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">Coil Loading DATE &amp; shift &amp; Time</td>
    <td class="tg-baqh" colspan="2">Date</td>
    <td class="tg-baqh" colspan="2">Shift</td>
    <td class="tg-baqh" colspan="2">Time</td>
    <td class="tg-baqh" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="3">DEPARTMENT&amp;MACHINE</td>
    <td class="tg-baqh" colspan="4"></td>
    <td class="tg-baqh" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="11">MATERIAL PROCESS DETAILS</td>
  </tr>
  <tr>
    <td class="tg-baqh">SL.No</td>
    <td class="tg-baqh">MACHINE ID</td>
    <td class="tg-baqh">DATE</td>
    <td class="tg-baqh">SHIFT</td>
    <td class="tg-baqh">OPER-<br>ATOR</td>
	<td class="tg-baqh">PART<br>NUMBER</td>
    <td class="tg-baqh">OK-(KGS)</td>
    <td class="tg-baqh">R/W-(KGS)</td>
    <td class="tg-baqh">SCARP-<br>(KGS)</td>
    <td class="tg-baqh" colspan="2">FOREMAN SIGNATURE</td>
  </tr>
  <tr>
    <td class="tg-baqh">1</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh">2</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh">3</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh">4</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh">5</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh">6</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh">7</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh">8</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh">9</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh">10</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="6">Total QTY</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="6">Total KG</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="6">Balence Coil Return To Stores (KG)</td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">FOREMAN SIGN</td>
    <td class="tg-baqh" colspan="3"></td>
    <td class="tg-baqh" colspan="2">STORES SIGN</td>
    <td class="tg-baqh" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="7">ROUTE CARD CLOSING DETAIL</td>
    <td class="tg-baqh" colspan="4">STORES ALERT</td>
  </tr>
  <tr>
    <td class="tg-baqh">DATE</td>
    <td class="tg-baqh">CHILD RC NO</td>
    <td class="tg-baqh">QTY ISSUE</td>
    <td class="tg-baqh">KG ISSUE</td>
    <td class="tg-baqh">RECEI<br>VED</td>
    <td class="tg-baqh" colspan="2">ISSUED BY</td>
    <td class="tg-baqh" colspan="4">STOCK DETAIL EXCEPT THIS MRC</td>
  </tr>
  <tr>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2">PART NO</td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2">MAX STOCK</td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2">SEMI FINS</td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2">WIP</td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2">FG</td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2">OUT SC</td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2">TOTAL</td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh"></td>
    <td class="tg-baqh" colspan="2">RC VALID QUANTITY</td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="5">PREPARED BY</td>
    <td class="tg-yw4l" colspan="6">RECEIVED BY</td>
  </tr>
</table>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('cfm').style.visibility = 'hidden';
			document.getElementById('save').style.display = '';
        }
}
</script>
<script>
var hidden = false;
function myFunction1() {
        window.location='i12_1db.php?tdate=$tdate&stockingpoint=$stkpt&ircn=$rcno&rcno=$rcno&raw=$rm&uom=$uom&rmqty=$rmiq&inum=$inum&hno=$hno&lno=$lno&cno=$cno';
}
</script>
<input type="button" id="cfm" value="confirm" onclick="myFunction();javascript:window.print(); <?php echo "window.location='i12_1db.php?tdate=$tdate&stockingpoint=$stkpt&ircn=$rcno&pnum=$pnum&raw=$rm&uom=$uom&rmqty=$rmiq&inum=$inum&hno=$hno&lno=$lno&cno=$cno'";?>">
<input type="button" id="save" value="save" style="display: none"; onclick="myFunction1()">