<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$dt=date("Y-m-d");
if(($_SESSION['access']=="ALL" && $_SESSION['user']=="123") || ($_SESSION['user']=="100"))
{
	
}
else
{
	header("location: index.php");
}
$sp = $_POST['sp'];
$pn = $_POST['pn'];
$pr = $_POST['pr'];
$eq = $_POST['eq'];
$aq = $_POST['aq'];
$count=count($pr);
$con=mysqli_connect('localhost','root','Tamil','mypcm');
for($i=0;$i<$count;$i++)
{
	$tmp=$eq[$i]-$aq[$i];
	$l=strlen($pr[$i]);
	$r=substr($pr[$i],0,1);
	$year=substr($pr[$i],3,2);
	$num=substr($pr[$i],$l-5,$l);
	$ircno=$r.date('dm').$year.$num;
	if($sp[$i]=="FG For Invoicing")
	{
		if($tmp>0)
		{
			//mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`) VALUES ('$sp[$i]', '".date('Y-m-d')."', '$pn[$i]', '$pr[$i]', '$eq[$i]', '$aq[$i]' , '$u' , '$ip')");
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) VALUES ('$sp[$i]', '".date('Y-m-d')."', '$pn[$i]', '$pr[$i]', '$eq[$i]', '$aq[$i]' , '$u' , '$ip' , '' , 'F')");
			//mysqli_query($con,"INSERT INTO d11(operation,date,rcno,closedate,rmk) VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE')");
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) VALUES('".date('Y-m-d')."','FG For Invoicing','$pn[$i]','DIF$ircno','$pr[$i]','$tmp','','F')");
			//mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,rcno,prcno,partissued) VALUES('".date('Y-m-d')."','FG For Invoicing','$pn[$i]','DIF$ircno','$pr[$i]','$tmp')");
		}
		else if($tmp<0)
		{
			$tmp=$aq[$i]-$eq[$i];
			//code for increase the stock
			//mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`) VALUES ('$sp[$i]', '".date('Y-m-d')."', '$pn[$i]', '$pr[$i]', '$eq[$i]', '$aq[$i]' , '$u' , '$ip')");
			//mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,prcno,partreceived) VALUES ('".date('Y-m-d')."','$sp[$i]','$pn[$i]','$pr[$i]','$tmp')");
		}
	}
	else
	{
		if($tmp>0)
		{
			//mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`) VALUES ('$sp[$i]', '".date('Y-m-d')."', '$pn[$i]', '$pr[$i]', '$eq[$i]', '$aq[$i]' , '$u' , '$ip')");
			mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) VALUES ('$sp[$i]', '".date('Y-m-d')."', '$pn[$i]', '$pr[$i]', '$eq[$i]', '$aq[$i]' , '$u' , '$ip' , '' , 'F')");
			//mysqli_query($con,"INSERT INTO d11(operation,date,rcno,closedate,rmk) VALUES('$sp[$i]','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE')");
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) VALUES('$sp[$i]','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
			//mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,rcno,prcno,partissued) VALUES('".date('Y-m-d')."','$sp[$i]','$pn[$i]','$ircno','$pr[$i]','$tmp')");
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) VALUES('".date('Y-m-d')."','$sp[$i]','$pn[$i]','$ircno','$pr[$i]','$tmp','','F')");
			//mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,prcno,partreceived) VALUES ('".date('Y-m-d')."','FG For Invoicing','$pn[$i]','$ircno','$tmp')");
				mysqli_query($con,"INSERT INTO reconciledb (date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) VALUES ('".date('Y-m-d')."','FG For Invoicing','$pn[$i]','$ircno','$tmp','','F')");
			//mysqli_query($con,"INSERT INTO d11(operation,date,rcno,closedate,rmk) VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE')");
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
			//mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,rcno,prcno,partissued) VALUES('".date('Y-m-d')."','FG For Invoicing','$pn[$i]','DIF$ircno','$ircno','$tmp')");
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) VALUES('".date('Y-m-d')."','FG For Invoicing','$pn[$i]','DIF$ircno','$ircno','$tmp','','F')");
		}
		else if($tmp<0)
		{
			$tmp=$aq[$i]-$eq[$i];
			//code for increase the stock
			//mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`) VALUES ('$sp[$i]', '".date('Y-m-d')."', '$pn[$i]', '$pr[$i]', '$eq[$i]', '$aq[$i]' , '$u' , '$ip')");
			//mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,prcno,partreceived) VALUES ('".date('Y-m-d')."','$sp[$i]','$pn[$i]','$pr[$i]','$tmp')");
		}
	}
}
header("location: reconcile.php?stkpt=$sp[0]");
?>