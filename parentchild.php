<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if(isset($_POST['alfaok']))
{
	$tdate=$_POST['tdate'];
	$pn=$_POST['pn'];
	$tiqty=$_POST['tiqty'];
	$dc=$_POST['dc'];
	$dt="DC-".$dc;
	$dct=$_POST['dctype'];
	$sc=$_POST['sc'];
	$mot=$_POST['mot'];
	
	//new add
	$iss=$_POST['dctype'];
	$vno=$_POST['vno'];
	
	$weight=$_POST['weight'];
	$remarks=$_POST['remarks'];
	
	$result3 = $con->query("SELECT rate,per FROM (SELECT DISTINCT pnum,invpnum FROM `pn_st` WHERE pnum='$pn' OR invpnum='$pn') AS Tpnst LEFT JOIN invmaster ON invmaster.pn=Tpnst.pnum OR invmaster.pn=Tpnst.invpnum ORDER BY `invmaster`.`rate` DESC LIMIT 1");
	$row3 = mysqli_fetch_array($result3);
	$rate = $row3['rate'];
	$per = $row3['per'];
	
	$result2 = $con->query("SELECT * FROM `dcmaster` WHERE pn='$pn' AND sccode='$sc'");
	$row2 = mysqli_fetch_array($result2);
	$uom = $row2['uom'];
	$cgst = $row2['cgst'];
	$sgst = $row2['sgst'];
	$igst = $row2['igst'];
	
	if($iss=="FG For S/C"){
		$valuepercentage = 90;
		$rateperqty = ($rate/$per) * 90/100;
	}else{
		$valuepercentage = 70;
		$rateperqty = ($rate/$per) * 70/100;
	}
	$basicvalue = number_format((float)($tiqty * $rateperqty),2, '.','');
	
	if($igst == "")
	{
		$cgstamt = number_format((float)($basicvalue * $cgst/100),2,'.','');
		$sgstamt = number_format((float)($basicvalue * $sgst/100),2,'.','');
		$igstamt = '';
		$totalvalue = number_format((float)($basicvalue + $cgstamt + $sgstamt),2,'.','');
	}else{
		$igstamt = number_format((float)($basicvalue * $igst/100),2,'.','');
		$cgstamt = '';
		$sgstamt = '';
		$totalvalue = number_format((float)($basicvalue + $igstamt),2,'.','');
	}
	
	
	if(isset($_POST['prcno']))
	{
		$rc=$_POST['prcno'];
		if(substr($rc,0,1)=="E")
		{
			//mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty');");
			mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`, `rate`, `per`, `value_percentage`, `uom`, `basicvalue`, `cgst`, `sgst`, `igst`, `cgstamount`, `sgstamount`, `igstamount`, `totalvalue`, `vehiclenumber`, `weight`, `remarks` ) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty', '$rate', '$per', '$valuepercentage', '$uom', '$basicvalue', '$cgst', '$sgst', '$igst', '$cgstamt', '$sgstamt', '$igstamt', '$totalvalue', '$vno', '$weight' ,'$remarks');");
			mysqli_query($con,"INSERT INTO `d18`(`date`, `prcno`, `ndc`, `pnum`, `iqty`) VALUES('$tdate','$rc','$dc','$pn','$tiqty')");
			mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('$dct','$tdate','$pn','$dt')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$tdate','$dct','$pn','$rc','$dt','$tiqty','$u','$ip')");
			mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$dt','$rc')");
		}
		else
		{
			//mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty');");
			mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`, `rate`, `per`, `value_percentage`, `uom`, `basicvalue`, `cgst`, `sgst`, `igst`, `cgstamount`, `sgstamount`, `igstamount`, `totalvalue`, `vehiclenumber`, `weight`, `remarks` ) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty', '$rate', '$per', '$valuepercentage', '$uom', '$basicvalue', '$cgst', '$sgst', '$igst', '$cgstamt', '$sgstamt', '$igstamt', '$totalvalue', '$vno', '$weight' ,'$remarks');");
			mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('$dct','$tdate','$pn','$dt')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$tdate','$dct','$pn','$rc','$dt','$tiqty','$u','$ip')");
			mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$dt','$rc')");
		}
		header("location: inputlink.php");
	}
	else
	{
		IF($dct=="To S/C")
		{
			$stkpt="To S/C";
			$s="To S/C";
		}
		else
		{
			$stkpt="FG For Invoicing";
			$s="FG For S/C";	
		}
		$query = "SELECT DISTINCT pnum as pn FROM `pn_st` WHERE stkpt='$stkpt' AND invpnum='$pn' group by n_iter";
		$result = $con->query($query);
		$iter = mysqli_num_rows($result);
		$query = "SELECT DISTINCT pnum as pn FROM `pn_st` WHERE stkpt='$stkpt' AND invpnum='$pn'";
		//echo "SELECT DISTINCT pnum as pn FROM `pn_st` WHERE invpnum='$pn' and n_iter='1'";
		$result1 = $con->query($query);
		$n1 = mysqli_num_rows($result1);
		//mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty');");
		mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`, `rate`, `per`, `value_percentage`, `uom`, `basicvalue`, `cgst`, `sgst`, `igst`, `cgstamount`, `sgstamount`, `igstamount`, `totalvalue`, `vehiclenumber`,`weight`, `remarks`  ) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty', '$rate', '$per', '$valuepercentage', '$uom', '$basicvalue', '$cgst', '$sgst', '$igst', '$cgstamt', '$sgstamt', '$igstamt', '$totalvalue', '$vno', '$weight' ,'$remarks');");
		if($n1==0)
		{
			header("location: inputlink.php");
		}
		else if($n1>=1)
		{
			$row = mysqli_fetch_array($result);
			$rat=$row['pn'];
			//$rat=$pn;
			
			//change for unit 3 dc
			//header("location: i12_4.php?tdate=$tdate&cat=$s&rat=$rat&dc=$dt&tiqty=$tiqty&rem=$tiqty&ino=1&i=$iter");
		
			header("location: i12_4.php?tdate=$tdate&cat=$s&rat=$rat&dc=$dt&tiqty=$tiqty&rem=$tiqty&ino=1&i=$iter&sc=$sc");

		}
	}
}
?>
