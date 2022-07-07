<?php
function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
if(isset($_POST['submit']))
	{
		$cori="";
		$inum = $_POST['inum'];
		$tdate = $_POST['tdate'];
		$pn = $_POST['pn'];						
		$cname = $_POST['cname'];
		$cpono = $_POST['cpono'];
		$tiqty = $_POST['tiqty'];
		$con = mysqli_connect('localhost','root','Tamil','mypcm');
		$q = "select * from invmaster where pn='$pn' and cname='$cname' and cpono='$cpono'";
		$r = $con->query($q);
		$fch=$r->fetch_assoc();
		$str2=substr($fch['cgstno'],0,2);
		if($str2=="33")
		{
			$cori="CGST";
		}
		else
		{
			$cori="IGST";
		}
	}
?>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:10;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:16px;align:right;padding:6px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:16px;font-weight:normal;padding:1px 5px;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-yw4l{vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin:0px;}}</style>
<div id="invoice" class="tg-wrap"><table class="tg">
  <tr>
    <th class="tg-yw4l"  style="visibility: hidden;">.</th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
  </tr>
  <tr>
    <td class="tg-031e"  style="visibility: hidden;">.</td>
    <td class="tg-031e"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"   style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l">GSTIN :</td>
    <td class="tg-yw4l" colspan="3">33AACCV3065F1ZL</td>
    <td class="tg-yw4l">INVOICE NO :</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $inum; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l">CIN     :</td>
    <td class="tg-yw4l" colspan="3">U27310TZ2006OPTC012531</td>
    <td class="tg-yw4l">INV DATE   :</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $tdate; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l">PAN :</td>
    <td class="tg-yw4l" colspan="3">AACCV3065F</td>
    <td class="tg-yw4l">INV TIME :</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date("g:i:s a"); ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" id="">CONSIGNEE</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">DELIEVARY TO</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">PO NO :</td>
    <td class="tg-yw4l"><?php echo $fch['cpono']; ?></td>
  </tr>
  
  <tr>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['cname']; ?></td>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['dtname']; ?></td>
    <td class="tg-yw4l">PO DATE :</td>
    <td class="tg-yw4l"><?php echo $fch['cpodt']; ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['cadd1']; ?></td>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['dtadd1']; ?></td>
    <td class="tg-yw4l" colspan="2"  style="visibility: hidden;">mode of dispatch</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['cadd2']; ?></td>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['dtadd2']; ?></td>
    <td class="tg-yw4l" colspan="2">mode of dispatch replace</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['cadd3']; ?></td>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['dtadd3']; ?></td>
    <td class="tg-yw4l">CENDOR CODE:</td>
    <td class="tg-yw4l"><?php echo $fch['vc']; ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['cgstno']; ?></td>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['dtgstno']; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Part No</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cori; ?>&nbsp;&nbsp;&nbsp;</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"> S.No </td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbspPart Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quantity&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PC  %&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;Taxable Value&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;Rate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hsn No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Per&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item No</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l">1</td>
    <td class="tg-yw4l"><?php echo $fch['pn']; ?><br></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $fch['rate']; ?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $tiqty; ?><br></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $fch['pc']." %"; ?><br></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo round((($fch['rate']*$tiqty)/$fch['per'])+(round(($fch['rate']*$tiqty*$fch['pc'])/100,2)),2);$six=round((($fch['rate']*$tiqty)/$fch['per'])+(round(($fch['rate']*$tiqty*$fch['pc'])/100,2)),2); ?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php if($cori=="33"){echo $fch['cgst']." %";$sev=$fch['cgst'];}else{ echo $fch['igst']." %";$sev=$fch['cgst'];} ?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php if($cori=="33"){echo $fch['sgst']." %";$egt=$fch['cgst'];}else{ echo "0 %";$egt=0.00;} ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">WEB<?php //echo $fch['pd']; ?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $fch['per']; ?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $fch['uom']; ?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo round(($fch['rate']*$tiqty*$fch['pc'])/100,2); ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo round($six*$sev,2);$vsev=$six*$sev;?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo round($six*$egt,2);$vegt=$six*$egt;?></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fch['hsnc']; ?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $fch['uom']; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fch['poino']; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"  style="visibility: hidden;">.</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $tiqty; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $six; ?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $vsev; ?></td>
    <td class="tg-yw4l">&nbsp;&nbsp;&nbsp;<?php echo $vegt; ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="2"></td>
    <td class="tg-yw4l" colspan="2"><?php echo round($six+$vsev+$vegt);$amt=round($six+$vsev+$vegt); ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="8">Rupees : <?php echo convert_number_to_words($amt);?></td>
  </tr>
</table></div>
 <script type="text/javascript">     
    function PrintDiv() {
		function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('cfm').style.visibility = 'hidden';
			document.getElementById('hide').style.display = '';
        }
}
       var divToPrint = document.getElementById('invoice');
       var popupWin = window.open('', '_blank', 'width=300,height=300');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
		document.location.href = "inputlink.php";
            }
 </script>
<div>
  <input type="button" value="print" onclick="PrintDiv();" />
</div>
