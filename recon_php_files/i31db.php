<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
if(isset($_POST['submit']))
{
	$type=$_POST['type'];
	if($type=="Stores")
	{
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		$date=$_POST['tdate'];
		$partnumber=$_POST['mcodes'];
		$partdesc=$_POST['rmdesc'];
		$sname=$_POST['scode'];
		$qty=$_POST['qty'];
		$q = "SELECT genrcno as gen FROM `stockinitialize` WHERE genrcno LIKE 'G%' ORDER BY genrcno DESC LIMIT 1";
		$r = $con->query($q);
		$fch=$r->fetch_assoc();
		if($fch['gen']=="")
		{
			$gnum="G20-000100";
		}
		else
		{
			$gnum=substr($fch['gen'],0,4).str_pad((substr($fch['gen'],4,6)+1),6,"0",STR_PAD_LEFT);
			echo $gnum;
		}
		mysqli_query($con,"INSERT INTO `stockinitialize` (`date`, `type`, `area`, `genrcno`, `mc_pn` , `supplier`, `qty`, `user`, `ip`) VALUES ('$date', '$type', '$type', '$gnum', '$partnumber' , '$sname', '$qty', '$u', '$ip')");
		$con=mysqli_connect('localhost','root','Tamil','storedb');
		mysqli_query($con,"INSERT INTO receipt (`grnnum`, `date`, `part_number`, `rmdesc`, `ponum`, `sname`, `dc_number`, `dc_date`, `inv_no`, `inv_date`, `tcno`, `uom`, `quantity_received`) VALUES ('$gnum','$date','$partnumber','$partdesc','','$sname','','','','','','Kgs','$qty')");
		mysqli_query($con,"INSERT INTO inspdb (`grnnum`, `inspdate`, `inspby`, `quantityaccepted`, `quantityrejected`, `reason`) VALUES ('$gnum','$date','OPENING STOCK','$qty','0','')");
		header("location: i31show.php?type=$type");
	}
	if($type=="STOCKING POINT")
	{
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		$date=$_POST['tdate'];
		$partnumber=$_POST['pnum'];
		$qty=$_POST['qty'];
		$area=$_POST['area'];
		$q = "SELECT dummy FROM `m11` WHERE operation='$area'";
		$r = $con->query($q);
		$fch=$r->fetch_assoc();
		$rct=$fch['dummy'];
		$q = "SELECT genrcno as gen FROM `stockinitialize` WHERE genrcno LIKE '$rct%' ORDER BY genrcno DESC LIMIT 1";
		//echo "SELECT genrcno as gen FROM `stockinitialize` WHERE genrcno LIKE '$rct%' ORDER BY genrcno DESC LIMIT 1";
		$r = $con->query($q);
		$fch=$r->fetch_assoc();
		if($fch['gen']=="")
		{
			$gnum=$rct."20-000100";
		}
		else
		{
			$gnum=substr($fch['gen'],0,4).str_pad((substr($fch['gen'],4,6)+1),6,"0",STR_PAD_LEFT);
			echo $gnum;
		}
		mysqli_query($con,"INSERT INTO `stockinitialize` (`date`, `type`, `area`, `genrcno`, `mc_pn` , `supplier`, `qty`, `user`, `ip`) VALUES ('$date', '$type', '$area', '$gnum', '$partnumber', '' , '$qty', '$u', '$ip')");
		mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,prcno,partreceived,username,ip) VALUES ('$date','$area','$partnumber','$gnum','$qty','$u','$ip')");
		mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno,closedate) VALUES('$area','$date','$partnumber','$gnum','$date')");
	}
	if($type=="OPERATION")
	{
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		$date=$_POST['tdate'];
		$partnumber=$_POST['pnum'];
		$qty=$_POST['qty'];
		$area=$_POST['area'];
		$q = "SELECT dummy FROM `m11` WHERE operation='$area'";
		$r = $con->query($q);
		$fch=$r->fetch_assoc();
		$rct=$fch['dummy'];
		$q = "SELECT genrcno as gen FROM `stockinitialize` WHERE genrcno LIKE '$rct%' ORDER BY genrcno DESC LIMIT 1";
		//echo "SELECT genrcno as gen FROM `stockinitialize` WHERE genrcno LIKE '$rct%' ORDER BY genrcno DESC LIMIT 1";
		$r = $con->query($q);
		$fch=$r->fetch_assoc();
		if($fch['gen']=="")
		{
			$gnum=$rct."20-000100";
		}
		else
		{
			$gnum=substr($fch['gen'],0,4).str_pad((substr($fch['gen'],4,6)+1),6,"0",STR_PAD_LEFT);
			echo $gnum;
		}
		mysqli_query($con,"INSERT INTO `stockinitialize` (`date`, `type`, `area`, `genrcno`, `mc_pn` , `supplier`, `qty`, `user`, `ip`) VALUES ('$date', '$type', '$area', '$gnum', '$partnumber', '' , '$qty', '$u', '$ip')");
		if($rct=="A")
		{
			$rmdesc=$_POST['rmdesc'];
			mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('Stores','$date','$partnumber','$gnum')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,rm,rmissqty,pnum,prcno,rcno,partissued,username,ip) VALUES('$date','Stores', '$rmdesc' , '$qty' , '$partnumber','','$gnum','','$u','$ip')");
		}
		if($rct=="B")
		{
			mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('Semifinished1','$date','$partnumber','$gnum')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$date','Semifinished1','$partnumber','','$gnum','$qty','$u','$ip')");
		}
		if($rct=="C")
		{
			mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('Semifinished2','$date','$partnumber','$gnum')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$date','Semifinished2','$partnumber','','$gnum','$qty','$u','$ip')");
		}
		if($rct=="D")
		{
			mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('To S/C','$date','$partnumber','$gnum')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$date','To S/C','$partnumber','','$gnum','$qty','$u','$ip')");
		}
		if($rct=="E")
		{
			mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('From S/C','$date','$partnumber','$gnum')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$date','From S/C','$partnumber','','$gnum','$qty','$u','$ip')");
		}
	}
	mysqli_close($con);
	header("location: i31show.php?type=$type&area=$area&gen=$gnum");
}
?>