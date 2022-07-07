<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_POST['submit']))
{
	header("location: inputlink.php");
}
if(isset($_GET['sno']) && $_GET['sno']!="")
{
	$sno = $_GET['sno'];
	$stat = $_GET['stat'];
	$time=date("Y-m-d");
	if($stat=="T")
	{
		$result = mysqli_query($con,"SELECT * FROM `f_insp` WHERE sno='$sno'");
		$r=$result->num_rows;
		$row = mysqli_fetch_array($result);
		$date=$row['date'];
		$stkpt=$row['stkpt'];
		$pnum=$row['pnum'];
		$prcno=$row['prcno'];
		$qty=$row['qty'];
		$user=$row['user'];
		$uip=$row['ip'];
		$apby=$row['apby'];
		
		$approve_transaction_number = $row['app_trans_num'];
		
		if($apby=="") 
		{
			//correct working
			mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,prcno,partreceived,username,ip) VALUES ('$date','$stkpt','$pnum','$prcno','$qty','$user','$uip')");
			mysqli_query($con,"UPDATE f_insp SET apby='$u',status='$stat',date_insp='$time' where sno='$sno'");
			
			
			//new add with app transaction
			/*mysqli_query($con,"UPDATE d12 SET stkpt='$stkpt' where rsn='$approve_transaction_number' AND stkpt='$stkpt.(FINAL APPROVAL PENDING)' AND prcno='$prcno' AND pnum='$pnum'");
			echo "UPDATE d12 SET stkpt='$stkpt' where rsn='$approve_transaction_number' AND stkpt='$stkpt.(FINAL APPROVAL PENDING)' AND prcno='$prcno' AND pnum='$pnum'";
			*/
			//it updates qty
			mysqli_query($con,"UPDATE d12 SET partreceived='0',rsn='' where rsn='$approve_transaction_number' AND stkpt='$stkpt.(FINAL APPROVAL PENDING)' AND prcno='$prcno' AND pnum='$pnum'");
			
			echo "UPDATE d12 SET partreceived='0',rsn='' where rsn='$approve_transaction_number' AND stkpt='$stkpt.(FINAL APPROVAL PENDING)' AND prcno='$prcno' AND pnum='$pnum'";
			
		}
	}
	else
	{
		$result = mysqli_query($con,"SELECT prcno,pnum,stkpt,apby,app_trans_num FROM `f_insp` WHERE sno='$sno'");
		$r=$result->num_rows;
		$row = mysqli_fetch_array($result);
		$prcno=$row['prcno'];
		
		$stkpt = $row['stkpt'];
		$pnum = $row['pnum'];
		$approve_transaction_number = $row['app_trans_num'];
		$apby=$row['apby'];
		
		if($apby=="")
		{
			mysqli_query($con,"UPDATE d11 SET closedate='0000-00-00' WHERE rcno='$prcno'");
			mysqli_query($con,"UPDATE f_insp SET apby='$u',status='$stat',date_insp='$time' where sno='$sno'");
			
			//new add with app transaction
			mysqli_query($con,"UPDATE d12 SET partreceived='0',rsn='' where rsn='$approve_transaction_number' AND stkpt='$stkpt.(FINAL APPROVAL PENDING)' AND prcno='$prcno' AND pnum='$pnum'");
			
			echo "UPDATE d12 SET partreceived='0',rsn='' where rsn='$approve_transaction_number' AND stkpt='$stkpt.(FINAL APPROVAL PENDING)' AND prcno='$prcno' AND pnum='$pnum' ";
			
			
			/*mysqli_query($con,"UPDATE d12 SET stkpt='Rework' where rsn='$approve_transaction_number' AND stkpt='$stkpt.(FINAL APPROVAL PENDING)' AND prcno='$prcno' AND pnum='$pnum'");
			
			echo "UPDATE d12 SET stkpt='Rework' where rsn='$approve_transaction_number' AND stkpt='$stkpt.(FINAL APPROVAL PENDING)' AND prcno='$prcno' AND pnum='$pnum'";
			*/
			
		}
	}
}
//header("location: f_insp_app.php");
?>