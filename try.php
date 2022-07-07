<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
$tdate = $_GET['tdate'];
$cat = $_GET['cat'];
$rat = $_GET['rat'];
$r = $_GET['rat'];
$dc = $_GET['dc'];
$tiqty = $_GET['tiqty'];
$rem = $_GET['rem'];
$iter = $_GET['n1'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
$result = $con->query("SELECT distinct pnum,invpnum FROM `pn_st` WHERE stkpt='FG For Invoicing' and invpnum='$rat' and n_iter=1");
$row = mysqli_fetch_array($result);
$rat=$row['pnum'];
$invrat=$row['invpnum'];
if($rat=="")
{
	$rat=$_GET['rat'];
}
//echo $rat;
$result1 = $con->query("SELECT count(*) as c from m12 WHERE pnum='$rat' AND operation='FG For S/C'");
//echo "SELECT count(*) as c from m12 WHERE pnum='$rat' AND operation='FG For S/C'";
$row1 = mysqli_fetch_array($result1);
if($row1['c']>0)
{
	$sc=$_GET['sc'];
	header("location: newinv4dc_rc.php?tdate=$tdate&cat=FG%20For%20Invoicing&rat=$invrat&dc=$dc&tiqty=$tiqty&rem=$rem&scn=$sc");
	
	//header("location: newinv4dc_rc.php?tdate=$tdate&cat=FG%20For%20Invoicing&rat=$invrat&dc=$dc&tiqty=$tiqty&rem=$rem");
	
	
	/*
	if(isset($_GET['sc']) && $_GET['sc']!="")
	{
		$sc=$_GET['sc'];
		$query = "SELECT SUM(rem) as stock FROM (SELECT DISTINCT T2.rcno,T2.pnum,T2.date,T2.issqty-IF(T1.received IS NULL,'0',T1.received) as rem,datediff(NOW(),T2.date) as days,dc_det.scn FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,sum(d12.partissued) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation='FG For S/C' AND d11.pnum='$r' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN dc_det ON T2.rcno=CONCAT('DC-',dc_det.dcnum) WHERE scn LIKE '$sc'  GROUP BY T2.rcno order by t2.date,T2.rcno ASC) AS T";
		
	}
	else
	{
		$query = "SELECT SUM(rem) as stock FROM (SELECT T2.rcno,T2.pnum,T2.date,T2.issqty-IF(T1.received IS NULL,'0',T1.received) as rem,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,sum(d12.partissued) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation='FG For S/C' AND d11.pnum='$r' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno GROUP BY T2.rcno order by t2.date,T2.rcno ASC) AS T";
	}

	$result2 = $con->query($query);
	$row2 = mysqli_fetch_array($result2);
	if($iter==0)
	{
		$iter=1;
		echo "<script>
		alert('PROBLEM IN MASTER PART NOT IN INVMASTER BUT FOUND IN TRACEABILITY');
		window.location.href='invabort.php?inum=$dc';
		</script>";
	}
	$iter=1;
	if(($row2['stock']/$iter)>=$tiqty)
	{
		if(isset($_GET['sc']) && $_GET['sc']!="")
		{
			$sc=$_GET['sc'];
			$query = "SELECT DISTINCT T2.rcno,T2.pnum,T2.date,T2.issqty-IF(T1.received IS NULL,'0',T1.received) as rem,datediff(NOW(),T2.date) as days,dc_det.scn FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,sum(d12.partissued) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation='FG For S/C' AND d11.pnum='$r' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN dc_det ON T2.rcno=CONCAT('DC-',dc_det.dcnum) WHERE scn LIKE '$sc'  GROUP BY T2.rcno order by t2.date,T2.rcno ASC";
			
		}
		else
		{
			$query = "SELECT T2.rcno,T2.pnum,T2.date,T2.issqty-IF(T1.received IS NULL,'0',T1.received) as rem,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,sum(d12.partissued) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation='FG For S/C'  AND d11.pnum='$r' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno GROUP BY T2.rcno order by t2.date,T2.rcno ASC";
		}
		
		//echo "SELECT T2.rcno,T2.pnum,T2.date,T2.issqty-IF(T1.received IS NULL,'0',T1.received) as rem,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,m14.oper as operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,d12.pnum,sum(d12.partissued) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno JOIN m14 on d11.operation=m14.stkpt WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation='FG For S/C'  AND d11.pnum='$r' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno GROUP BY T2.rcno order by t2.date,T2.rcno ASC";
		$result3 = $con->query($query);
		$q=$tiqty*$iter;
		$i=0;
		while($q>0)
		{
			$i=$i+1;
			echo $i;
			$row3 = mysqli_fetch_array($result3);
			$t1=$row3['rcno'];
			$t2=$row3['rem'];
			
			if($q<$t2)
			{
				$t2=$q;
			}
			else
			{
				//echo "CLOSED & ";
				mysqli_query($con,"UPDATE d11 set closedate='".date('Y-m-d')."' where rcno='$t1'");
				//echo "UPDATE d11 set closedate='".date('Y-m-d')."' where rcno='$t1'";
			}
			//echo "RECEIVED : $t1 QTY : $t2 <br>";
			mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,rcno,prcno,partreceived,username,ip) VALUES ('".date('Y-m-d')."','FG For Invoicing','$r','$dc','$t1','$t2','$u','$ip')");
			mysqli_query($con,"INSERT INTO d11(operation,date,rcno) VALUES('FG For Invoicing','".date('Y-m-d')."','$dc')");
			mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,rcno,prcno,partissued,username,ip) VALUES ('".date('Y-m-d')."','FG For Invoicing','$r','$dc','$t1','$t2','$u','$ip')");
			$q-=$t2;
			
	//orderbook invoiced_qty updation.	
		$pnum=$invrat;
		$result1 = $con->query("SELECT COUNT(*) AS c FROM `orderbook` WHERE pnum='$pnum'");
		$row = mysqli_fetch_array($result1);
		$c=$row['c'];
		if($c>0)
		{								
			$result = $con->query("SELECT ob_id,date,pnum,qty,ref_no,order_date,req_date,commit,invstatus,invoiced_qty,qty-invoiced_qty AS balance FROM `orderbook` WHERE pnum='$pnum' HAVING balance>0");			
			$result1 = $con->query("SELECT * FROM `inv_det` ORDER BY `inv_det`.`invno` DESC LIMIT 1");

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
		if($q==0)
		{
			header("location: invslip.php?invno=$dc&iter=$iter");
		}
	}
	else
	{
		echo "<script>
		alert('CLOSE DC OR DO RECONCILE AND TRY AGAIN');
		window.location.href='invabort.php?inum=$dc';
		</script>";
	}
	
	*/
	
}
else
{
	if(isset($_POST['part']))
	{
		//$rat=$_POST['part'];
	}
	if($iter==0)
	{
		header("location: dirprint.php?dc=$dc&pnum=$rat");
	}
	else
	{
		header("location: i12_3.php?tdate=$tdate&cat=FG%20For%20Invoicing&rat=$rat&dc=$dc&tiqty=$tiqty&rem=$rem&ino=1&i=$iter");
	}
}
?>