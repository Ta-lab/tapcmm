<?php
if(isset($_GET['rcno']))
	{		
		$rcno = $_GET['rcno'];
		$con = mysqli_connect('localhost','root','Tamil','mypcm');
		$q = "select * from d12 where rcno='$rcno'";
		$r = $con->query($q);
		$fch1=$r->fetch_assoc();
		$con = mysqli_connect('localhost','root','Tamil','storedb');
		$grn=$fch1['inv'];
		$q = "select * from receipt where grnnum='$grn'";
		$r = $con->query($q);
		$fch=$r->fetch_assoc();
	}
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
    <th class="tg-7fgq" colspan="7" rowspan="2">MASTER ROUTE CARD - VSSIPL - 1<br></th>
    <th class="tg-baqh" colspan="2">Issue date</th>
    <th class="tg-baqh" colspan="2"><?php echo date('d-m-Y', strtotime($fch1['date'])); ?></th>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">Closing Date</td>
    <td class="tg-baqh" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">MASTER ROUTE CARD #</td>
    <td class="tg-amwm" colspan="3"><?php echo $fch1['rcno']; ?></td>
    <td class="tg-baqh" colspan="3" rowspan="2"><img src="barcode.php?text=<?php echo $rcno; ?>" alt="testing" /></td>
    <td class="tg-baqh">Format.No</td>
    <td class="tg-baqh" colspan="2">STR/R/09</td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">PART NUMBER</td>
    <td class="tg-baqh" colspan="3"><?php echo $fch1['pnum']; ?></td>
    <td class="tg-baqh">Rev No / DATE</td>
    <td class="tg-baqh" colspan="2"> 00 / 02.01.2017 </td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">Size with Grade / Coil size</td>
    <td class="tg-baqh" colspan="3"><?php echo $fch1['rm']; ?></td>
    <td class="tg-baqh" colspan="2">Coil Number</td>
    <td class="tg-baqh" colspan="2">Coil No</td>
    <td class="tg-baqh" colspan="2"><?php echo $fch1['coil']; ?></td>
  </tr>
  <tr>
    <td class="tg-nrw1" colspan="2">Raw Material Lot s.no - Heat code</td>
    <td class="tg-baqh" colspan="3"><?php echo $fch1['heat']; ?></td>
    <td class="tg-baqh" colspan="2">RM.T.C Number</td>
    <td class="tg-baqh" colspan="4"><?php echo $fch['tcno']; ?></td>
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
    <td class="tg-baqh" colspan="2"><?php echo $fch1['rmissqty']; ?></td>
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
        }
}
</script>
<script>
var hidden = false;

</script>
<input type="button" id="cfm" value="confirm" onclick="myFunction();javascript:window.print(); <?php echo "window.location='rep_vi12_1.php'";?>">
