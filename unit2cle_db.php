<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
$tdate = $_GET['tdate'];
$prcno = $_GET['prcno'];
$stkpt = $_GET['operation'];
$ircn = $_GET['ircn'];
$pnum = $_GET['pnum'];
$iss = $_GET['iss'];
$type = $_GET['type'];

if($stkpt=="CLE UNIT 2")
{
	/*mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno,rmk) VALUES('$stkpt','$tdate','$pnum','$ircn','$type')");
	mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,partreceived,username,ip) VALUES('$tdate','$stkpt','$pnum','$prcno','$iss','$u','$ip')");
	mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$tdate','$stkpt','$pnum','$prcno','$ircn','$iss','$u','$ip')");
	mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$ircn','$prcno')");
	*/
	
	mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno,rmk) VALUES('$stkpt','$tdate','$pnum','$ircn','$type')");
	mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,partreceived,rsn,username,ip) VALUES('$tdate','$stkpt','$pnum','$prcno','$ircn','$iss','$iss','$type','$u','$ip')");
	mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$ircn','$prcno')");
	
	
	mysqli_query($con,"INSERT INTO cledb(date,operation,type,rcno,dcnum,pnum,issued_qty) VALUES('$tdate','$stkpt','$type','$ircn','$prcno','$pnum','$iss')");
	
	
}
header("location: inputlink.php");	

/*if($stkpt=="FG For Invoicing" || $stkpt=="FG For S/C")
{
	$result = $con->query("SELECT * FROM `pn_st` WHERE pnum='$pnum'");
	$row = mysqli_fetch_array($result);
	$p=$row['invpnum'];
	mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('$stkpt','$tdate','$p','$ircn')");
	mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$tdate','$stkpt','$p','$prcno','$ircn','$iss','$u','$ip')");
	mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$ircn','$prcno')");
	if($stkpt=="FG For S/C")
	{
		$ino = $_GET['ino'];
		if($ino!=1)
		{
			mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,prcno,partreceived,username,ip) VALUES ('".date('Y-m-d')."','FG For Invoicing','$p','$ircn','$iss','$u','$ip')");
			mysqli_query($con,"INSERT INTO d11(operation,date,rcno) VALUES('FG For Invoicing','".date('Y-m-d')."','AF$ircn')");
			mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,rcno,prcno,partissued,username,ip) VALUES ('".date('Y-m-d')."','FG For Invoicing','$p','AF$ircn','$ircn','$iss','$u','$ip')");
		}
	}
	if($stkpt=="FG For Invoicing")
	{
		mysqli_query($con,"UPDATE inv_det set ok='T' where invno='$ircn'");
		mysqli_query($con,"UPDATE inv_correction SET status='T' WHERE invno='$ircn'");
		
		$result1 = $con->query("SELECT COUNT(*) AS c FROM `orderbook` WHERE pnum='$pnum'");
		$row = mysqli_fetch_array($result1);
		$c=$row['c'];
		if($c>0)
		{								
			$result = $con->query("SELECT ob_id,date,pnum,qty,ref_no,order_date,req_date,commit,invstatus,invoiced_qty,qty-invoiced_qty AS balance FROM `orderbook` WHERE pnum='$p' HAVING balance>0");			
			$result1 = $con->query("SELECT * FROM `inv_det` WHERE invno='$ircn'");
			$row = mysqli_fetch_array($result1);
			$invoiced_qty=$row['qty'];
			
			$t=$invoiced_qty;
			while($row = mysqli_fetch_array($result)){
				$ob_id = $row['ob_id'];
				$req_qty = $row['qty'];
				$b = $row['balance'];
				
				if($t>$b){
					mysqli_query($con,"UPDATE orderbook SET invoiced_qty=invoiced_qty+$b where ob_id='$ob_id' ");
					$t = $t-$b; 
				}else if($t<$b){ //new add...
					mysqli_query($con,"UPDATE orderbook SET invoiced_qty=invoiced_qty+$t where ob_id='$ob_id' ");
					$t=0; 
				}else{ //new add invoiced_qty+t.
					mysqli_query($con,"UPDATE orderbook SET invoiced_qty=invoiced_qty+'$t' where ob_id='$ob_id' ");
					$t=0;
				}
				if($t==0){
					break;
				}
				
			}		
		}
		else{
			$result = $con->query("SELECT * FROM `pn_st` WHERE pnum='$pnum'");
			$row = mysqli_fetch_array($result);
			$p=$row['invpnum'];
	
			$result1 = $con->query("SELECT COUNT(*) AS c FROM `orderbook` WHERE pnum='$p'");
			$row = mysqli_fetch_array($result1);
			$c=$row['c'];
			if($c>0)
			{								
				$result = $con->query("SELECT ob_id,date,pnum,qty,ref_no,order_date,req_date,commit,invstatus,invoiced_qty,qty-invoiced_qty AS balance FROM `orderbook` WHERE pnum='$p' HAVING balance>0");			
				$result1 = $con->query("SELECT * FROM `inv_det` WHERE invno='$ircn'");
				$row = mysqli_fetch_array($result1);
				$invoiced_qty=$row['qty'];
			
				$t=$invoiced_qty;
				while($row = mysqli_fetch_array($result)){
					$ob_id = $row['ob_id'];
					$req_qty = $row['qty'];
					$b = $row['balance'];
				
					if($t>$b){
						mysqli_query($con,"UPDATE orderbook SET invoiced_qty=invoiced_qty+$b where ob_id='$ob_id' ");
						$t = $t-$b; 
					}else if($t<$b){ //new add...
						mysqli_query($con,"UPDATE orderbook SET invoiced_qty=invoiced_qty+$t where ob_id='$ob_id' ");
						$t=0; 
					}else{ //new add invoiced_qty+t.
						mysqli_query($con,"UPDATE orderbook SET invoiced_qty=invoiced_qty+'$t' where ob_id='$ob_id' ");
						$t=0;
					}
					if($t==0){
						break;
					}				
				}		
			}
		}
		
	}
}
else
{
	if($stkpt=="To S/C")
	{
		$result = $con->query("SELECT * FROM `pn_st` WHERE pnum='$pnum'	AND stkpt='To S/C'");
		$row = mysqli_fetch_array($result);
		$p=$row['invpnum'];
	}
	if($p=="")
	{
		$p=$pnum;
	}
	mysqli_query($con,"INSERT INTO d11(operation,date,pnum,rcno) VALUES('$stkpt','$tdate','$p','$ircn')");
	mysqli_query($con,"INSERT INTO d12(date,stkpt,pnum,prcno,rcno,partissued,username,ip) VALUES('$tdate','$stkpt','$pnum','$prcno','$ircn','$iss','$u','$ip')");
	mysqli_query($con,"INSERT INTO d13(rcno,prcno)VALUES('$ircn','$prcno')");
	if($stkpt=="To S/C")
	{
		$ino = $_GET['ino'];
		if($ino!=1)
		{
			mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,prcno,partreceived,username,ip) VALUES ('".date('Y-m-d')."','FG For Invoicing','$p','$ircn','$iss','$u','$ip')");
			mysqli_query($con,"INSERT INTO d11(operation,date,rcno) VALUES('FG For Invoicing','".date('Y-m-d')."','AF$ircn')");
			mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,rcno,prcno,partissued,username,ip) VALUES ('".date('Y-m-d')."','FG For Invoicing','$p','AF$ircn','$ircn','$iss','$u','$ip')");
		}
	}
	$query = "SELECT week FROM `d19`";
	$result = $con->query($query);
	$row = mysqli_fetch_array($result);
	$dt=$row['week'];
	mysqli_query($con,"UPDATE commit SET issuedqty=issuedqty+'$iss' where week='$dt' and pnum='$pnum' and foremac='MANUAL'");
	header("location: inputlink.php");
}
if($stkpt=="FG For Invoicing")
{
	$ino = $_GET['ino'];
	$noi = $_GET['i'];
	$tiqty = $_GET['tiqty'];
	if($noi==$ino)
	{
		//TO FIND WHETHER THE PART IS DIRECT OR ALFA.
		$result = $con->query("SELECT * FROM m12 WHERE pnum='$pnum' and operation='FG For S/C')");
		if($result === FALSE) {
			//FINAL INSPECTION REPORT LINKING
			$q1 = "SELECT DISTINCT fi_id,fi_rcno.rcno,inv_det.pn,inv_det.ccode FROM `fi_rcno` LEFT JOIN d12 ON fi_rcno.rcno=d12.prcno LEFT JOIN inv_det ON d12.rcno=inv_det.invno WHERE inv_det.invno='$ircn'";
			$r1 = $con->query($q1);
			while($row1=$r1->fetch_assoc())
			{
				$fi=$row1['fi_id'];
				$pn=$row1['pn'];
				$rc=$row1['rcno'];
				$cc=$row1['ccode'];
				mysqli_query($con,"INSERT INTO `printfi` (`inv`, `ccode` , `finum`, `pnum`, `rcno`, `status`) VALUES ('$ircn', '$cc', '$fi', '$pn', '$rc' , 'F')");
			}
			$result1 = $con->query("SELECT * from invprinter");
			$row = mysqli_fetch_array($result1);
			if($row['popt']=="0")
			{
				header("location: printphp.php?invno=$ircn&n=0");
			}
			else
			{
				header("location: inputlink.php");
			}
		}
		else
		{
			$q1 = "SELECT ccode,pn FROM `inv_det` WHERE invno='$ircn'";
			$r1 = $con->query($q1);
			$row1=$r1->fetch_assoc();
			$q2 = "SELECT * FROM fi_rcno WHERE rcno IN (SELECT prcno FROM `d12` WHERE rcno IN (SELECT prcno FROM `d12` WHERE rcno='$ircn'))";
			$r2 = $con->query($q2);
			while($row2=$r2->fetch_assoc())
			{
				$fi=$row2['fi_id'];
				$pn=$row1['pn'];
				$rc=$row2['rcno'];
				$cc=$row1['ccode'];
				mysqli_query($con,"INSERT INTO `printfi` (`inv`, `ccode` , `finum`, `pnum`, `rcno`, `status`) VALUES ('$ircn', '$cc', '$fi', '$pn', '$rc' , 'F')");
			}
			header("location: invslip.php?invno=$ircn");
		}
	}
	else
	{
		$ino=$ino+1;
		$result = $con->query("SELECT * FROM `pn_st` WHERE n_iter='$ino' AND invpnum IN (SELECT DISTINCT invpnum FROM `pn_st` WHERE pnum='$pnum')");
		$row = mysqli_fetch_array($result);
		if($result === FALSE) { 
			header("location: inputlink.php");
		}
		else
		{
			$p=$row['pnum'];
			header("location: i12_3.php?tdate=$tdate&cat=$stkpt&rat=$p&dc=$ircn&tiqty=$tiqty&rem=$tiqty&ino=$ino&i=$noi");
		}
	}
	
}
if($stkpt=="FG For S/C" || $stkpt=="To S/C")
{
	$ino = $_GET['ino'];
	$noi = $_GET['i'];
	$tiqty = $_GET['tiqty'];
	if($noi==$ino)
	{
		$id=$_SESSION['user'];
		mysqli_query($con,"UPDATE admin1 set status='1' where userid='$id'");
		header("location: inputlink.php");
		//header("location: inventory1.php?invno=$ircn&n=0");
	}
	else
	{
		$ino=$ino+1;
		if($stkpt=="FG For S/C")
		{
			$s="FG For Invoicing";
		}
		else
		{
			$s="To S/C";
		}
		$result = $con->query("SELECT * FROM `pn_st` WHERE n_iter='$ino' AND stkpt='$s' AND invpnum IN (SELECT DISTINCT invpnum FROM `pn_st` WHERE pnum='$pnum' and stkpt='$s')");
		echo "SELECT * FROM `pn_st` WHERE n_iter='$ino' stkpt='$s' AND invpnum IN (SELECT DISTINCT invpnum FROM `pn_st` WHERE pnum='$pnum')";
		$row = mysqli_fetch_array($result);
		$p=$row['pnum'];
		header("location: i12_4.php?tdate=$tdate&cat=$stkpt&rat=$p&dc=$ircn&tiqty=$tiqty&rem=$tiqty&ino=$ino&i=$noi");
	}
	
}*/


//mysqli_close($con);
?>
