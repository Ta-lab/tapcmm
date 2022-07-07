<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];

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

	$query = "SELECT * FROM `reconciliation_improval`";
	$result = $con->query($query);
	while($row = mysqli_fetch_array($result))
	{
	
		$date = $row['date'];
		$stkpt = $row['stockingpoint'];
		$pnum = $row['pnum'];
		$prcno = $row['prcno'];
		$erpqty = $row['erpqty'];
		$actualqty = $row['actualqty'];
		
		$tmp = $erpqty - $actualqty;
		
		$l=strlen($prcno);
		$r=substr($prcno,0,1);
		$year=substr($prcno,3,2);
		$num=substr($prcno,$l-5,$l);
		$ircno=$r.date('dm').$year.$num;
		
		if($tmp>0)
		{
			if($actualqty == 0)
			{
				mysqli_query($con,"UPDATE D11 SET closedate='".date('Y-m-d')."' WHERE rcno='$prcno'");
			}
		
			//START OF OPERATION...
			
			if($row['stockingpoint'] == "ALFA N-IND PRIM")
			{
				//echo "ALFA N-IND PRIM";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status` ) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$prcno','$tmp','','F')");
				
				mysqli_query($con,"UPDATE `subcondb` SET total_rec_qty=total_rec_qty-$tmp WHERE dcnum='$prcno'");
			
			}
			
			
			if($row['stockingpoint'] == "CNC_SHEARING")
			{
				//echo "CNC_SHEARING";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status` ) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"UPDATE d12 set rmissqty=rmissqty-$tmp WHERE rcno='$prcno'");
				
			}
			
			if($row['stockingpoint'] == "MANUAL_AREA")
			{
				//echo "MANUAL_AREA";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status` ) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$prcno','$tmp','','F')");
				
			
			}
			
			if($row['stockingpoint'] == "MANUAL_AREA-1")
			{
				//echo "MANUAL_AREA-1";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status` ) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$prcno','$tmp','','F')");
				
			
			}
			
			if($row['stockingpoint'] == "MANUAL_AREA-2")
			{
				//echo "MANUAL_AREA-2";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status` ) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$prcno','$tmp','','F')");
			
			}
			
			if($row['stockingpoint'] == "SUBCONTRACT")
			{
				//echo "SUBCONTRACT";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status` ) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$prcno','$tmp','','F')");
			
			}
			
			if($row['stockingpoint'] == "Returned")
			{
				//echo "Returned";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status` ) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$prcno','$tmp','','F')");
			
			}
			
			if($row['stockingpoint'] == "100% Checking")
			{
				//echo "100% Checking";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status` ) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$prcno','$tmp','','F')");
			
			}
			
			
			if($row['stockingpoint'] == "CLE UNIT 2")
			{
				//echo "CLE UNIT 2";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status` ) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$prcno','$tmp','','F')");
			
			}
			
			//END OF OPERATION...
			
			
			//START OF STOCKING POINT
			
			if($row['stockingpoint'] == "FG For Invoicing")
			{
				//echo "FG For Invoicing";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus)
				VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus)
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$prcno','$tmp','','F')");
			
			}
			
			if($row['stockingpoint'] == "FG For S/C")
			{
				//echo "FG For S/C";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('$stkpt','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','$stkpt','$pnum','$ircno','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb (date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES ('".date('Y-m-d')."','FG For Invoicing','$pnum','$ircno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$ircno','$tmp','','F')");
				
			}
			
			if($row['stockingpoint'] == "From S/C")
			{
				//echo "From S/C";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('$stkpt','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','$stkpt','$pnum','$ircno','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb (date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES ('".date('Y-m-d')."','FG For Invoicing','$pnum','$ircno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$ircno','$tmp','','F')");
				
			}
			
			if($row['stockingpoint'] == "Rework")
			{
				//echo "Rework";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('$stkpt','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','$stkpt','$pnum','$ircno','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb (date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES ('".date('Y-m-d')."','FG For Invoicing','$pnum','$ircno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$ircno','$tmp','','F')");
				
			}
			
			if($row['stockingpoint'] == "Semifinished1")
			{
				//echo "Semifinished1";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('$stkpt','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','$stkpt','$pnum','$ircno','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb (date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES ('".date('Y-m-d')."','FG For Invoicing','$pnum','$ircno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$ircno','$tmp','','F')");
				
			}
			
			if($row['stockingpoint'] == "Semifinished2")
			{
				//echo "Semifinished2";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('$stkpt','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','$stkpt','$pnum','$ircno','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb (date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES ('".date('Y-m-d')."','FG For Invoicing','$pnum','$ircno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$ircno','$tmp','','F')");
				
			}
			
			if($row['stockingpoint'] == "Semifinished3")
			{
				//echo "Semifinished3";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('$stkpt','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','$stkpt','$pnum','$ircno','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb (date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES ('".date('Y-m-d')."','FG For Invoicing','$pnum','$ircno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$ircno','$tmp','','F')");
				
			}
			
			if($row['stockingpoint'] == "To S/C")
			{
				//echo "To S/C";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('$stkpt','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','$stkpt','$pnum','$ircno','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb (date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES ('".date('Y-m-d')."','FG For Invoicing','$pnum','$ircno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$ircno','$tmp','','F')");
				
			}
			
			if($row['stockingpoint'] == "FG For Scrap")
			{
				//echo "FG For Scrap";
				mysqli_query($con,"INSERT INTO `d17` (`sp`, `dt`, `pn`, `pr`, `eq`, `aq`, `username`, `ip`, `app_by`, `app_status`) 
				VALUES ('$stkpt', '".date('Y-m-d')."', '$pnum', '$prcno', '$erpqty', '$actualqty' , '$u' , '$ip' , '' , 'F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('$stkpt','".date('Y-m-d')."','$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','$stkpt','$pnum','$ircno','$prcno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb (date,stkpt,pnum,prcno,partreceived,approvedby,approvedstatus) 
				VALUES ('".date('Y-m-d')."','FG For Invoicing','$pnum','$ircno','$tmp','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledbd11(operation,date,rcno,closedate,rmk,approvedby,approvedstatus) 
				VALUES('FG For Invoicing','".date('Y-m-d')."','DIF$ircno','".date('Y-m-d')."','DUMMY FOR RECONCILE','','F')");
				
				mysqli_query($con,"INSERT INTO reconciledb(date,stkpt,pnum,rcno,prcno,partissued,approvedby,approvedstatus) 
				VALUES('".date('Y-m-d')."','FG For Invoicing','$pnum','DIF$ircno','$ircno','$tmp','','F')");
				
			}
			
			
			
			
			
			
			
		
		
		
		
		
		
		
		}
		
		
	}
		
	
	//header("Location: inputlink.php");




?>