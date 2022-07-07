<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
//if(isset($_SESSION['user']) && isset($_SESSION['access'])  && ($_SESSION['access']=="ALL" || $_SESSION['username']=="INDHUMATHI") )
if($_SESSION['user']=="123" || $_SESSION['user']=="134")
{
	
}
else
{
	header("location: index.php");
}
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
//if(isset($_POST['approve']))
//{
	
	
	
		
	$appdate = $_GET['appdate'];
	
	//echo "approve";
	//$query1 = "SELECT * FROM `reconciledb` WHERE approvedstatus='F' AND rejectedstatus=''";
	$query1 = "SELECT * FROM `reconciledb` WHERE approvedstatus='F' AND rejectedstatus='' AND date='$appdate'";
	$result1 = $con->query($query1);
	while($row1 = mysqli_fetch_array($result1))
	{
		$date = $row1['date'];
		$stkpt = $row1['stkpt'];
		//$operation = $row1['operation'];
		//$workcentre = $row1['workcentre'];
		//$rm = $row1['rm'];
		//$rmissqty = $row1['rmissqty'];
		$pnum = $row1['pnum'];
		$rcno = $row1['rcno'];
		$prcno = $row1['prcno'];
		$partissued = $row1['partissued'];
		$qtyrejected = $row1['qtyrejected'];
		$partreceived = $row1['partreceived'];
		//$inv = $row1['inv'];
		//$heat = $row1['heat'];
		//$lot = $row1['lot'];
		//$coil = $row1['coil'];
		//$rsn = $row1['rsn'];
		$username = $row1['username'];
		$ip = $row1['ip'];
		
		mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,rcno,prcno,partissued,partreceived,username,ip) VALUES('".date('Y-m-d')."','$stkpt','$pnum','$rcno','$prcno','$partissued','$partreceived','$username','$ip')");
		
		//mysqli_query($con,"UPDATE `reconciledb` SET approvedby='".$u."',approvedstatus='T' WHERE approvedstatus='F' AND rejectedstatus='' ");
		mysqli_query($con,"UPDATE `reconciledb` SET approvedby='".$u."',approvedstatus='T' WHERE approvedstatus='F' AND rejectedstatus='' AND date='$appdate' ");
		//echo "UPDATE `reconciledb` SET approvedby='".$u."',approvedstatus='T' WHERE approvedstatus='F'";
		
		mysqli_query($con,"UPDATE `d17` SET app_by='".$u."',app_status='T' WHERE app_status='F' AND rej_status='' AND dt='$appdate'");
		//echo "UPDATE `d17` SET app_by='".$u."',app_status='T' WHERE app_status='F'";
		
		mysqli_query($con,"UPDATE `stockinitialize` SET app_by='".$u."',app_status='T' WHERE app_status='F' AND rej_status='' AND date='$appdate'");
		//echo "UPDATE `stockinitialize` SET app_by='".$u."',app_status='T' WHERE app_status='F'";
	}
		
	
	//}
	
	
	//$query1 = "SELECT * FROM `reconciledbd11` WHERE approvedstatus='F' AND rejectedstatus=''";
	$query1 = "SELECT * FROM `reconciledbd11` WHERE approvedstatus='F' AND rejectedstatus='' AND date='$appdate'";
	$result1 = $con->query($query1);
	while($row1 = mysqli_fetch_array($result1))
	{
		$operation = $row1['operation'];
		$date = $row1['date'];
		$rcno = $row1['rcno'];
		$pnum = $row1['pnum'];
		$closedate = $row1['closedate'];
		
		mysqli_query($con,"INSERT INTO d11(operation,date,rcno,pnum,closedate) VALUES('$operation','$date','$rcno','$pnum','$closedate')");
		
		mysqli_query($con,"UPDATE `reconciledbd11` SET approvedby='".$u."',approvedstatus='T' WHERE approvedstatus='F' AND rejectedstatus='' AND date='$appdate' ");
		//echo "UPDATE `reconciledbd11` SET approvedby='".$u."',approvedstatus='T' WHERE approvedstatus='F'";
		
		mysqli_query($con,"UPDATE `d17` SET app_by='".$u."',app_status='T' WHERE app_status='F' AND rej_status='' AND dt='$appdate'");
		//echo "UPDATE `d17` SET app_by='".$u."',app_status='T' WHERE app_status='F'";
	
		mysqli_query($con,"UPDATE `stockinitialize` SET app_by='".$u."',app_status='T' WHERE app_status='F' AND rej_status='' AND date='$appdate'");
		//echo "UPDATE `stockinitialize` SET app_by='".$u."',app_status='T' WHERE app_status='F'";
	
	}
	
	
	header("Location: stock_reconcile_menu.php");




?>