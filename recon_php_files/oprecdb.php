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
$op = $_POST['op'];
$pn = $_POST['pn'];
$pr = $_POST['pr'];
$eq = $_POST['eq'];
$aq = $_POST['aq'];
$count=count($pr);
$con=mysqli_connect('localhost','root','Tamil','mypcm');
for($i=0;$i<$count;$i++)
{
	$result1 = $con->query("select * from m14 where oper='$op[$i]'");
	$row = mysqli_fetch_array($result1);
	$sp=$row['stkpt'];
	$tmp=$eq[$i]-$aq[$i];
	$l=strlen($pr[$i]);
	$r=substr($pr[$i],0,1);
	$year=substr($pr[$i],3,2);
	$num=substr($pr[$i],$l-5,$l);
	$ircno=$r.date('dm').$year.$num;
	if(substr($pr[$i],0,1)=="A")
	{
		if($tmp>0)
		{
			if($aq[$i]==0)
			{
				mysqli_query($con,"UPDATE D11 SET closedate='".date('Y-m-d')."' WHERE rcno='$pr[$i]'");
			}
			mysqli_query($con,"UPDATE d12 set rmissqty=rmissqty-$tmp WHERE rcno='$pr[$i]'");
		}
		else if($tmp<0)
		{
			$tmp=$aq[$i]-$eq[$i];
			if($aq[$i]==0)
			{
				mysqli_query($con,"UPDATE D11 SET closedate='".date('Y-m-d')."' WHERE rcno='$pr[$i]'");
			}
			mysqli_query($con,"UPDATE d12 set rmissqty=rmissqty+$tmp WHERE rcno='$pr[$i]'");
		}
		else
		{
			if($aq[$i]==0)
			{
				mysqli_query($con,"UPDATE D11 SET closedate='".date('Y-m-d')."' WHERE rcno='$pr[$i]'");
			}
		}
	}
	else
	{
		if($tmp>0)
		{
			if($aq[$i]==0)
			{
				mysqli_query($con,"UPDATE D11 SET closedate='".date('Y-m-d')."' WHERE rcno='$pr[$i]'");
			}
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,partreceived) VALUES('".date('Y-m-d')."','FG For Invoicing','$pn[$i]','$pr[$i]','$tmp')");
			mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,rcno,prcno,partissued) VALUES('".date('Y-m-d')."','FG For Invoicing','$pn[$i]','DIF$ircno','$pr[$i]','$tmp')");
		}
		else if($tmp<0)
		{
			$tmp=$aq[$i]-$eq[$i];
			if($aq[$i]==0)
			{
				mysqli_query($con,"UPDATE D11 SET closedate='".date('Y-m-d')."' WHERE rcno='$pr[$i]'");
			}
			mysqli_query($con,"UPDATE d12 set partissued=partissued+$tmp WHERE rcno='$pr[$i]'");
		}
		else
		{
			if($aq[$i]==0)
			{
				mysqli_query($con,"UPDATE D11 SET closedate='".date('Y-m-d')."' WHERE rcno='$pr[$i]'");
			}
		}
	}
}
header("location: operationreconcile.php?stkpt=$op[0]&pnum=$pn[0]");
?>