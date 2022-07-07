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
	if(isset($_POST['prcno']))
	{
		$rc=$_POST['prcno'];
		if(substr($rc,0,1)=="E")
		{
			mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty');");
			mysqli_query($con,"INSERT INTO `d18`(`date`, `prcno`, `ndc`, `pnum`, `iqty`) VALUES('$tdate','$rc','$dc','$pn','$tiqty')");
			mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('$dct','$tdate','$pn','$dt')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$tdate','$dct','$pn','$rc','$dt','$tiqty','$u','$ip')");
			mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$dt','$rc')");
		}
		else
		{
			mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty');");
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
		mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`) VALUES ('$dc', '$tdate', '$sc', '$pn', '$mot', '$tiqty');");
		if($n1==0)
		{
			header("location: inputlink.php");
		}
		else if($n1>=1)
		{
			$row = mysqli_fetch_array($result);
			$rat=$row['pn'];
			//$rat=$pn;
			header("location: i12_4.php?tdate=$tdate&cat=$s&rat=$rat&dc=$dt&tiqty=$tiqty&rem=$tiqty&ino=1&i=$iter");
		}
	}
}
?>
