<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<?php
$con = mysqli_connect('localhost','root','Tamil','mypcm');
require "numtostr.php";
$t1=0;$s=1;$t2=0;$t3=68;$t4=5;$t5=26;$t6=27;$t7=14;$t8=3;$t8_1=3;$t9=4;$t10=9;$t11=10;$t12=10;$t13=10;$t14=13;$t15=11;$t16=11;$t17=15;$t18=0;$t19=1;$t20=43;$t21=57;$t22=0;$t23=0;
if(isset($_POST['submit']))
{
	$sc="";
	if(isset($_POST['sc']) && $_POST['sc']!="")
	{
		$sc=$_POST['sc'];
	}
	$cori="";$inum = $_POST['inum'];$ccode = $_POST['cc'];/*$msg = $_POST['msg'];*/$tdate = date('d-m-Y', strtotime($_POST['tdate']));
	$pn = $_POST['pn'];
	
	//einvoice changes
	$document_type = $_POST['document_type'];
	$reverse_charge = $_POST['reverse_charge'];
	$igst_on_intra = $_POST['igst_on_intra'];
	
	if(isset($_POST['pn1']) && $_POST['pn1']!="")
	{
		$part=$_POST['pn1'];
	}
	else
	{
		$part=$_POST['pn'];
	}
	$cname = $_POST['cname'];$cpono = $_POST['cpono'];$tiqty = $_POST['tiqty'];$mod = $_POST['mod'];$tm = $_POST['tm'];$dis = $_POST['dis'];$vno = $_POST['vno'];
	$appno = $_POST['appno'];
	if(isset($_POST['pdc']) && $_POST['pdc']!="")
	{
		$pdc=$_POST['pdc'];
		mysqli_query($con,"INSERT INTO `inv_det_dcinfo` (`invno`, `dcno`, `pnum`, `qty`) VALUES ('$inum', '$pdc', '$pn', '$tiqty')");
	}
	$q = "select * from invmaster where pn='$pn' and ccode='$ccode'";
	mysqli_query($con,"DELETE FROM inv_det WHERE invno='$inum'");
	mysqli_query($con,"UPDATE inv_correction SET status='T' WHERE invno='$inum'");
	mysqli_query($con,"UPDATE npd_invoicing SET invno='$inum' WHERE appno='$appno'"); //npd invoicing update...
	$r = $con->query($q);$fch=$r->fetch_assoc();$str2=substr($fch['cgstno'],0,2);
	
	
	//new code for tax collected at source
	$cname=$fch['cname'];
	$q1 = "select * from tcs where cname='$cname'";
	$r1 = $con->query($q1);
	$fch1=$r1->fetch_assoc();
	$pan_no=$fch1['pan_no'];
	
	
	if($str2=="33"){$cori="CGST";}else{$cori="IGST";}
	date_default_timezone_set("Asia/Kolkata");
	date("H:i");
	$cname1=strtoupper(substr($fch['cname'],0,26));
	if(strlen($fch['cname'])>26)
	{
		$cname2=strtoupper(substr($fch['cname'],26,25));
	}
	else
	{
		$cname2="";
	}
	$dtname=strtoupper(substr($fch['dtname'],0,26));
	if(strlen($fch['dtname'])>26)
	{
		$dtname1=strtoupper(substr($fch['dtname'],26,25));
	}
	else
	{
		$dtname1="";
	}
	$cpono=$fch['cpono'];
	if($fch['cpodt']=="0000-00-00" || $fch['cpodt']=="1970-01-01")
	{
		$cpodt="";
	}
	else
	{
		$cpodt=date('d-m-Y', strtotime($fch['cpodt']));
	}
	$cadd1=$fch['cadd1'];
	$dtadd1=$fch['dtadd1'];
	
	$cadd2=$fch['cadd2'];
	$dtadd2=$fch['dtadd2'];
	
	$cadd3=$fch['cadd3'];
	$dtadd3=$fch['dtadd3'];
	$mot=$fch['despatch'];
	$cgstin="GSTIN :".$fch['cgstno'];
	$dtgstin="GSTIN :".$fch['dtgstno'];
	$vc=$fch['vc'];
	$sno=1;
	$pn=$fch['pn'];
	$pd=$fch['pd'];
	$hsnc=$fch['hsnc'];
	$rate=$fch['rate'];
	if($fch['pc']==0){$pc="";}else{$pc=$fch['pc']." %";}
	$six=number_format((float)((($fch['rate']*$tiqty)/$fch['per'])+(round(($fch['rate']*round($tiqty*$fch['pc'],2))/100,2))),2, '.', '');
	if($cori=="CGST"){$sev=$fch['cgst'];}else{$sev=$fch['igst'];}
	if($sev==0){$sev1="";}else{$sev1=$sev." %";}
	if($cori=="CGST"){$egt=$fch['sgst'];}else{$egt=0;}
	if($egt==0){$egt1="";}else{$egt1=$egt." %";}
	$poino=$fch['poino'];
	$per=$fch['per'];
	$perdisplay="";
	if($fch['per']==1){$perdisplay="EACH";}else{$perdisplay=$fch['per'];}
	$uom=$fch['uom'];
	$five=number_format((float)(($fch['rate']*round($tiqty*$fch['pc'],2))/100),2, '.', '');
	if($five==0){$five1="";}else{$five1=$five;}
	$vsev=number_format((float)(($six*$sev)/100),2, '.', '');
	if($vsev==0){$vsev1="";}else{$vsev1=$vsev;}
	$vegt=number_format((float)(($six*$egt)/100),2, '.', '');
	if($vegt==0){$vegt1="";}else{$vegt1=$vegt;}
	if($fch['remark1']==""){$r1="";}else{$r1=$fch['remark1'];}
	if($fch['remark2']==""){$r2="";}else{$r2=$fch['remark2'];}
	if($fch['remark3']==""){$r3="";}else{$r3=$fch['remark3'];}
	if($fch['remark4']==""){$r4="";}else{$r4=$fch['remark4'];}
	if($fch['remark5']==""){$r5="";}else{$r5=$fch['remark5'];}
	$amt=number_format((float)($six+$vsev+$vegt),2, '.', '');
	
	//code for tcs
	/*
	$tcspercentage = "0.1%";
	if($cname==$fch1['cname']){	
		$tcs=($amt*0.1)/100;
		$tcs_amt=round($tcs,2);
	}
	else{
		$tcs_amt=0.00;
	}
	*/
	
	//$amtwithtcs=number_format((float)($six+$vsev+$vegt+$tcs_amt),2, '.', '');
	
	$whole = floor($amt);
	$fraction = $amt - $whole;
	$fraction=$fraction*100;
	$rup=ROUND($fraction);
	$pai=floor($amt);
	if(strtoupper(numtostrfn($rup)==""))
	{
		$a=strtoupper(numtostrfn($pai))."ONLY";
		$amtstr=$a;
	}
	else
	{
		$a=strtoupper(numtostrfn($pai))."AND PAISE ".strtoupper(numtostrfn($rup)."ONLY");
		$amtstr=$a;
	}
	$t1="";
	$a1="";
	$a2="";
	$a3="";
	$c=0;	
	for($i=0;$i<strlen($a);$i++)
	{
		if($a[$i]==" ")
		{
			$c=$c+1;
			if($c==8)
			{
				$a1=$t1.$a[$i];
				$t1="";
			}
			if($c==16)
			{
				$a2=$t1.$a[$i];
				$t1="";
			}
		}
		$t1=$t1.$a[$i];
	}
	$tdate = date("Y-m-d", strtotime($tdate));
	mysqli_query($con, "INSERT INTO `inv_det` (`invno`, `invdt`, `invt` , `ccode` , `cname`, `cname1`, `cadd1`, `cadd2`, `cadd3`, `cgstno`, `transmode` , `distance` ,  `vehicle` , `dtname`, `dtname1`, `dtadd1`, `dtadd2`, `dtadd3`, `dtgstno`, `cpono`, `cpodt`, `mot`, `vc`, `ewbno`, `pn`, `pd`, `hsnc`, `poino`, `rate`, `per`, `uom`, `qty`, `pc`, `pcamt`, `taxgoods`, `cigst`, `sgst`, `cigstamt`, `sgstamt`, `taxtotal`, `totcigstamt`, `totsgstamt`, `invtotal`, `inwords`,`inwords1`, `cori`, `r1`, `r2`, `r3`, `r4`, `r5` , `ok` , `print` , `pan_no` , `tcs` , `tcspercentage`, `document_type`, `reverse_charge`, `igst_on_intra`) VALUES 
	('$inum','$tdate','".date("H:i")."', '$ccode' ,'$cname1','$cname2', '$cadd1', '$cadd2', '$cadd3', '$cgstin', '$tm' , '$dis' , '$vno' , '$dtname', '$dtname1', '$dtadd1', '$dtadd2', '$dtadd3', '$dtgstin', '$cpono', '$cpodt', '$mot', '$vc', '', '$pn', '$pd', '$hsnc', '$poino', '$rate', '$perdisplay', '$uom', '$tiqty', '$pc', '$five1', '$six', '$sev1', '$egt1', '$vsev1', '$vegt1', '$six', '$vsev1', '$vegt1', '$amt', '$a1', '$t1', '$cori' , '$r1', '$r2', '$r3', '$r4', '$r5' , 'F' , 'F' , '$pan_no' , '' , '', '$document_type', '$reverse_charge', '$igst_on_intra')");
	$result = $con->query("SELECT pnum FROM `pn_st` WHERE invpnum='$pn' and n_iter=1 and stkpt='FG For Invoicing'");
	
	if($result === FALSE) { 
		$n=0;
	}
	else
	{
		$n = mysqli_num_rows($result);
	}
	$result = $con->query("SELECT pnum FROM `pn_st` WHERE invpnum='$pn' and stkpt='FG For Invoicing' group by n_iter");
	if($result === FALSE) { 
		$n1=0;
	}
	else
	{
		$n1 = mysqli_num_rows($result);
	}
}
?>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-yf2n{font-weight:bold;background-color:#333333;color:#ffffff;text-align:center;vertical-align:top}
.tg .tg-3ojx{font-weight:bold;background-color:#333333;color:#ffffff;vertical-align:top}
.tg .tg-9hbo{font-weight:bold;vertical-align:top}
.tg .tg-yw4l{vertical-align:top}
</style>
<table class="tg" style="undefined;table-layout: fixed; width: 751px">
<colgroup>
<col style="width: 71.66667px">
<col style="width: 95.66667px">
<col style="width: 54.66667px">
<col style="width: 82.66667px">
<col style="width: 82.66667px">
<col style="width: 58.66667px">
<col style="width: 166.66667px">
<col style="width: 138.66667px">
</colgroup>
  <tr>
    <th class="tg-yf2n" colspan="8">INVOICE  PREVIEW</th>
  </tr>
  <tr>
    <td class="tg-3ojx" colspan="6">Venkateswara Steels &amp; Springs (INDIA) Pvt. Ltd</td>
    <td class="tg-9hbo">Invoice No:</td>
    <td class="tg-yw4l"><?php echo $_POST['inum']; ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="6" rowspan="3"></td>
    <td class="tg-9hbo">Invoice Date: </td>
    <td class="tg-yw4l"><?php echo $_POST['tdate']; ?></td>
  </tr>
  <tr>
    <td class="tg-9hbo">TIME :</td>
    <td class="tg-yw4l"><?php echo date("H:i"); ?></td>
  </tr>
  <tr>
    <td class="tg-9hbo">GSTIN</td>
    <td class="tg-9hbo">33AACCV3065FIZL</td>
  </tr>
  <tr>
    <td class="tg-9hbo" colspan="3">Consignee</td>
    <td class="tg-9hbo" colspan="3">Delivery To</td>
    <td class="tg-9hbo">Po NO</td>
    <td class="tg-yw4l"><?php echo $fch['cpono']; ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['cname'];?></td>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['dtname'];?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['cadd1'];?></td>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['dtadd1'];?></td>
    <td class="tg-9hbo">PO DATE</td>
    <td class="tg-yw4l"><?php echo $fch['cpodt'];?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['cadd2'];?></td>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['dtadd2'];?></td>
    <td class="tg-9hbo">MODE OF TRANS :</td>
    <td class="tg-yw4l"><?php echo $mod; ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['cgstno'];?></td>
    <td class="tg-yw4l" colspan="3"><?php echo $fch['dtgstno'];?></td>
    <td class="tg-9hbo">VENDOR CODE </td>
    <td class="tg-yw4l"><?php echo $fch['vc']; ?></td>
  </tr>
  <tr>
	<td class="tg-9hbo" colspan="3">PAN NO</td>
	<td class="tg-yw4l" colspan="3"><?php echo $fch1['pan_no']; ?></td>
	<td class="tg-yw4l"></td>
	<td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"></td>
    <td class="tg-yw4l" colspan="3"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-9hbo" rowspan="2">S .No</td>
    <td class="tg-9hbo" rowspan="2">PART NO<br>PART NAME<br>HSN CODE<br>ITEM NO</td>
    <td class="tg-9hbo" rowspan="2">PRICE<br>PER</td>
    <td class="tg-9hbo" rowspan="2">QUANTITY<br>UOM</td>
    <td class="tg-9hbo" rowspan="2">P.C %<br>P.C VALUE</td>
    <td class="tg-9hbo" rowspan="2">VALUE</td>
    <td class="tg-yw4l"><?php echo $cori; ?></td>
    <td class="tg-9hbo">SGST RATE <br>&amp; VALUE</td>
  </tr>
  <tr>
    <td class="tg-9hbo">RATE &amp; VALUE</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l">1</td>
    <td class="tg-yw4l"><?php echo $fch['pn']; ?></td>
    <td class="tg-yw4l"><?php echo $fch['rate']; ?></td>
    <td class="tg-yw4l"><?php echo $tiqty; ?></td>
    <td class="tg-yw4l"><?php echo $fch['pc']." %"; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"><?php echo $sev." %"; ?></td>
    <td class="tg-yw4l"><?php echo $egt." %"; ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"><?php echo $fch['pd']; ?></td>
    <td class="tg-yw4l"><?php echo $fch['per']; ?></td>
    <td class="tg-yw4l"><?php echo $fch['uom']; ?></td>
    <td class="tg-yw4l"><?php echo round(($fch['rate']*$tiqty*$fch['pc'])/100,2); ?></td>
    <td class="tg-yw4l"><?php echo $six; ?></td>
    <td class="tg-yw4l"><?php echo $vsev; ?></td>
    <td class="tg-yw4l"><?php echo $vegt; ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"><?php echo $fch['hsnc']; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"><?php echo $fch['poino']; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-9hbo" colspan="3">ELECTRONIC REF. NO</td>
    <td class="tg-9hbo" colspan="2">TOTAL</td>
    <td class="tg-yw4l"><?php echo $six; ?></td>
    <td class="tg-yw4l"><?php echo $vsev; ?></td>
    <td class="tg-yw4l"><?php echo $vegt; ?></td>
  </tr>
  <!--<tr>
	<td class="tg-yw4l" colspan="3"></td>
	<td class="tg-9hbo" colspan="2">TCS 0.1%</td>
    <td class="tg-yw4l"><?php//echo $tcs_amt; ?></td>
	<td class="tg-yw4l"></td>
	<td class="tg-yw4l"></td>
  </tr>-->
  <tr>
    <td class="tg-yw4l" colspan="3"></td>
    <td class="tg-9hbo">TOTAL : RS.</td>
    <td class="tg-yw4l"><?php echo $amt; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="3"></td>
    <td class="tg-9hbo" colspan="2">INVOICE VALUE : RS.</td>
    <td class="tg-yw4l"><?php echo $amt; ?></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-9hbo" colspan="3">RUPEES :</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="8"><?php echo "Rupees : ".$amtstr; ?></td>
  </tr>
</table>
<form method="POST" action="<?php echo "try.php?tdate=$tdate&cat=FG%20For%20Invoicing&rat=$part&dc=$inum&tiqty=$tiqty&rem=$tiqty&n1=$n1&sc=$sc";?>">
<div>
	<input type="button" name="but" id="but" value="ABORT INVOICE" onclick="<?php echo "window.location='invabort.php?inum=$inum'";?>">
	<input type="submit" name="submit" id="submit" value="ACCEPT & NEXT"/>
</div>
</form>