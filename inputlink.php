<!DOCTYPE html>
<html>
<style>
	.divclass{
		
		margin-left: 250px;
		color : green;
	}
	
	a.fontchange:hover, a.ex4:active {
		font-family: monospace; font-size: 15px; color: green;
	}
	a.fontchange1:hover, a.ex4:active {
		font-family: monospace; font-size: 15px; color: red;
	}
	
	#mydiv {
		background: #17eccf;
		margin-top: 10px;
	}
	
	.blink_me {
	  animation: blinker 1s linear infinite;
	}

	@keyframes blinker {
	  50% {
		opacity: 0;
	  }
	}

	
</style>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<link rel="stylesheet" type="text/css" href="designlink.css">
<link rel="icon" href="./img/fav_icon.png" type="image/png" sizes="16x16">

<div class="blink_me"><label>version 1.0</label></div>

<link rel="stylesheet" href="notification-demo-style1.css" type="text/css">

<script type="text/javascript">
	
	function myFunction1() {
	var x = document.getElementById("mydiv");
		if (x.style.display === "none") {
		x.style.display = "block";
		} else {
		x.style.display = "none";
	  }	
	}
</script>


<body>
	<div style="float:left">
		<a href="manage.php"><h2><label>Hi <?php
		session_start();
		echo $_SESSION['username'];
		?>...!</label><h2></a>
	</div>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;">
	</div>

	
	<?php if($_SESSION['user']=='100') { ?>
	
		<?php
			$con=mysqli_connect('localhost','root','Tamil','mypcm');
			$sql2="SELECT * FROM orderbook WHERE invstatus = 1";
			$result=mysqli_query($con, $sql2);
			$count=mysqli_num_rows($result);
		?>
	
	<div style="float:right">
		<button id="notification-icon" onclick="myFunction1()">
			<span id="notification-count">
				<?php if($count>0) { echo $count; } ?>
			</span>
			<img src="notification-icon.png" />
		</button>
	
		<?php
		$sqll="SELECT * FROM `orderbook` where notify=1 ORDER BY `orderbook`.`ob_id` DESC";
		$resultt=mysqli_query($con, $sqll);
		echo"<div id='mydiv' style='height:250px;width:auto;overflow:scroll;overflow-x:hidden;overflow-y:scroll;'>";
		while($row = mysqli_fetch_array($resultt) ) {
			echo"<div class='notification-item'>" .
			"<div class='notification-subject' style='color:black; font-size:12px; ' >" ."Date : ".$row["date"] . "</div>" .
			"<div class='notification-subject' style='color:black; font-size:12px; ' >" ."Part no : ".$row["pnum"] . "</div>" .
			"<div class='notification-subject' style='color:black; font-size:12px; ' >" ."Orderqty : ".$row["qty"] . "</div>" .
			"</div>";
		}
		$sql1="UPDATE orderbook SET invstatus=0";
		$result1=mysqli_query($con, $sql1);
		?>	
		</div>
		<?php } ?>
	
	</div>

	
	<label style="color:yellow";><p align="center" id="msg"></p></label>	
<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
date_default_timezone_set("Asia/Kolkata");
$time=date("H");
$querydwm = "SELECT backup from autoreport";
$resultdwm = $con->query($querydwm);
$row = mysqli_fetch_array($resultdwm);


if(date("H", strtotime($row['backup']))!=$time)
{
	//if($time!='17')
	//{
		
		//header("location: takebackup.php");
	
	//}
}



?>

<script>
var i = 0;
var txt = <?php
$query = "SELECT week,max FROM `d19`";
$result = $con->query($query);
$row = mysqli_fetch_array($result);
$dt=$row['week'];
$last=$row['week'];
date_default_timezone_set("Asia/Kolkata");
//if(substr($dt,7,2)==date("W") && date("D")=="Sat" && date("H")>=12)
//if(substr($dt,7,2)!=date("W") && date("H")>=12)
//if(substr($dt,7,2)!=date("W"))
//if(substr($dt,7,2)==date("W") && date("D")=="Fri")	

if(substr($dt,7,2)==date("W") && date("D")=="Sat")	
{
	$query = "SELECT max(rowid) as max FROM `d12`";
	$result = $con->query($query);
	$row = mysqli_fetch_array($result);
	$end=$row['max'];
	$start=$end+1;
	$d=date("Y - ").(date("W")+1);
	mysqli_query($con,"UPDATE d19 SET week='$d'");
	mysqli_query($con,"UPDATE weekinfo SET end='$end' where week='$last'");
	mysqli_query($con,"INSERT INTO `weekinfo` (`week`, `start`, `end`) VALUES ('$d', '$start', '10000000')");
	$dt=$d;
}
if(isset($_GET['msg']))
{
	switch($_GET['msg']){
	case "1":
        echo "' Message : CNC ISSUANCE BLOCKED... Number Of Route Cards In CNC Area Is More Than 125... Let CNC To Close Those Route Cards ';";
        break;
    case "2":
        echo "' Message : SHEARING & SHEET CUTTING ISSUANCE BLOCKED... Number Of Route Cards In SHEARING & SHEET CUTTING Area Is More Than 25... Let Them To Close Those Route Cards ';";
        break;
    case "3":
         echo "' Message : Someone Raising DC.. Please Wait...';";
        break;
	case "4":
        echo "' Message : Your Last Session Is Not Logged Out Safely... Please Dont Forget To Logout... ';";
        break;
	case "5":
         echo "' Message : Password Successfully Changed...';";
        break;
	case "6":
         echo "' Message : Someone Raising INVOICE.. Please Wait...';";
        break;
	case "7":
         echo "' Message : Stocking Point Reconcile is closed.';";
        break;
	case "8":
         echo "' Message : Operation Reconcile is closed.';";
        break;
	case "9":
         echo "' Message : Part Number Is Not Connected With Invoicing. Make Sure Parent Child Master Updated And Invoice Master Updated';";
        break;
	case "10":
         echo "' Message : CNC Department having RC more than 10 days';";
        break;
	case "11":
         echo "' Message : SHEARING & SHEET CUTTING Department having RC more than 10 days';";
        break;
	case "12":
         echo "' Message : INVOICE AND DC RAISING CLOSED';";
        break;
	case "13":
         echo "' Message : OOPS... YOU CAN`T CHANGE TIMING ';";
        break;
	case "14":
         echo "' Message : RC Issuance disabled as qty available in CNC/SHEARING area';";
        break;
	case "15":
         echo "' Message : A RC Issuance disabled';";
        break;
	case "16":
		 $t=$_GET['name'];
         echo "' Message : $t AREA HAVING RC MORE THAN 10 DAYS';";
        break;
	case "17":
         echo "' Message : PART NUMBER SHOULD HAVE FOREMAN NAME IN BOM MASTER ( NAME PBR NOT ALLOWED )';";
        break;
	case "18":
         echo "' Message : RC Issuance disabled as qty available in MANUAL area (MANUAL COMMIT UPDATE REQUIRED)';";
        break;
	case "19":
         echo "' Message : Invoice Correction Approval is Pending...';";
        break;
	case "20":
         echo "' Message : PRODUCTION TEAM HANDOVER TIME TO STOCKING POINT FOR LAST WEEK COMMIT IS TILL 2 PM. SO MATERIAL ISSUANCE FROM STORES CAN BE DONE AFTER 2 P.M. ONLY(IFF COMMIT UPDATION DONE)';";
        break;
	case "21":
         echo "' Message : NON-Traceability Part (Please Convert To Traceability)';";
        break;
	case "22":
         echo "' Message : You Cannot Raise Route Card For The Part Which Is Returned To Stores For Next 3 Days (RC ONLY OPTION DISABLED)';";
        break;
	case "23":
         echo "' Message : You Cannot RAISE ROUTE CARD WHILE STOCK ABOVE 30 DAYS... ';";
        break;
	case "24":
         echo "' Message : PRODUCTION ENTRY TO BE MADE FOR THIS RC TO RECEIVE... ';";
        break;
	case "25":
         echo "' Message : NETWORK PRINTER CONNECTION NEEDED... CONTACT Mr.SARAVANAN(SALES)';";
        break;
	case "26":
         echo "' Message : BOM is Not Updated... CONTACT Mr.PRABHAKAR';";
        break;
    case "27":
         echo "' Message : FOREMAN IS NOT UPDATED... CONTACT Mr.PRABHAKAR';";
        break;
	case "28":
         echo "' Message : ORDERBOOK IS NOT UPDATED...Please Update Orderbook/Demandmaster ';";
    break;
    case "29":
         echo "' Message : Input OR Output BOM is 0 Please Update Correct BOM ';";
    break;
	case "30":
         echo "' Message : Invoice Locked...FG For Invoicing Stock More than 30 Days...';";
    break;
	case "31":
         echo "' Message : DC Issuance Locked...FG For Invoicing Stock More than 30 Days...';";
    break;
	case "32":
         echo "' Message : PART NUMBER IS NOT AVAILABLE IN DC MASTER...PLEASE UPDATE DC MASTER...';";
    break;
	case "33":
         echo "' Message : PART NUMBER IS NOT AVAILABLE IN CLE MASTER...PLEASE UPDATE CLE MASTER...';";
    break;
	case "34":
         echo "' Message : PART NUMBER IS LOCKED...PART NUMBER HAVING RC MORE THAN 10 DAYS...';";
    break;
	case "35":
         echo "' Message : FINAL INSPECTION IS ALREADY PENDING...';";
    break;
	case "36":
         echo "' Message : PART NUMBER IS LOCKED...FG For Invoicing Stock More than 30 Days...';";
    break;
	case "37":
         echo "' Message : DC Raising is Locked...DC Having More than 15 Days...';";
    break;
	case "38":
         echo "' Message : Rework Option is Disabled...';";
    break;
	case "39":
         echo "' Message : Incoming Inspection Pending More than 1 Day...';";
    break;
	case "40":
         echo "' Message : VSS Unit 2 DC Raising is Locked...DC Having More than 15 Days...';";
    break;
	default:
        echo "' Message : WELCOME - Date-Time is ".date("d-m-Y / H:i")."...';";
	}
}
else
{
	echo "'Message : Users Requested To Keep Your Password Personal... User Only Responsible For All The Entries Made In Their Login. To Change Your Password Please Click On Your Name';";
}
?>
var speed = 75;
window.onload = typeWriter();
function typeWriter() {
  if (i < txt.length) {
    document.getElementById("msg").innerHTML += txt.charAt(i);
    i++;
    setTimeout(typeWriter, speed);
  }
}
</script>
<div>
	<form class="left" method="post">
	<h4 style="text-align:left"><label style="color:#33ff99";>VSS MATERIAL TRANSACTION ENTRIES</label></h4>
	<?php
	if(isset($_SESSION['user']) && isset($_SESSION['access']))
	{
		$date=date("Y-m-d");
		//mysqli_query($con,"UPDATE d11 SET closedate='$date' WHERE rcno IN (SELECT rcno FROM (SELECT date,rcno,rm,if(used is null,issqty,issqty-used) as stock,days FROM (SELECT T2.rcno,T4.cnt,T2.operation,IF(T2.rcno LIKE 'A20%',T3.foreman,T5.foreman) as foreman,T2.date,T2.rm,T2.issqty, T1.prcno,T1.pnum,T1.received,T1.rejected, T3.rmdesc,IF(T2.rcno LIKE 'A20%',T3.uom,'Nos') as uom,IF(T2.rcno LIKE 'A20%',T3.useage,1) as bom,((T1.received+T1.rejected)*IF(T2.rcno LIKE 'A20%',T3.useage,1)) as used,datediff(NOW(),T2.date) as days FROM (SELECT prcno,d12.pnum,SUM(partreceived) as received,SUM(qtyrejected) as rejected FROM `d12` WHERE prcno!='' GROUP BY d12.prcno) AS T1 RIGHT JOIN (SELECT DISTINCT d11.rcno,d11.operation,d11.date,IF(d11.rcno LIKE 'A20%', d12.rm, d12.pnum) as rm,IF(d11.rcno LIKE 'A20%',d12.rmissqty,sum(d12.partissued)) as issqty FROM d12 JOIN d11 ON d12.rcno=d11.rcno WHERE d11.rcno!='' AND d11.closedate='0000-00-00' AND d11.operation!='FG For Invoicing' GROUP BY rcno ORDER BY date) AS T2 ON T1.prcno=T2.rcno LEFT JOIN (SELECT distinct(pnum),foreman FROM m13) AS T5 ON (T2.rm=T5.pnum) LEFT JOIN (SELECT pnum,m13.rmdesc,uom,useage,foreman FROM m13) AS T3 ON (T1.pnum=T3.pnum AND T2.rm=T3.rmdesc) LEFT JOIN (SELECT d11.rcno,COUNT(DISTINCT d12.pnum) as cnt FROM `d12` JOIN d11 ON d11.rcno=d12.prcno GROUP BY d11.rcno) AS T4 ON T2.rcno=T4.rcno where T2.operation like 'FG For S/C' order by date,rcno) AS TABLE1 where rm like '%%' HAVING stock<=0 order by rm) AS T)");
		$id=$_SESSION['user'];
		$ip=$_SESSION['ip'];
		$query1 = "SELECT erp from status";
		$result1 = $con->query($query1);
		$row1 = mysqli_fetch_array($result1);
		if($row1['erp']=="1")
		{
			if($_SESSION['user']!='123' && $_SESSION['user']!="100" && $_SESSION['user']!="126" && $_SESSION['user']!="129" && $_SESSION['user']!="124" && $_SESSION['user']!="113" && $_SESSION['user']!="109" && $_SESSION['user']!="105" && $_SESSION['user']!="117" && $_SESSION['user']!="130" )
			{
				unset($_SESSION['user']);
				unset($_SESSION['username']);
				unset($_SESSION['access']);
				unset($_SESSION['ip']);
				session_destroy();
				mysqli_query($con,"UPDATE admin1 set status='0' where userid='$id'");
				header("location: index.php?err1=6");
			}
		}
		
		
		
		/*
		//new add mare than 30days lock
		$query2 = "select d11.pnum,stkpt,prcno as prc,d12.date,sum(partreceived)-sum(partissued) as s ,datediff(NOW(),d12.date) as days from d12 join d11 on d12.prcno=d11.rcno where d12.pnum!='' and stkpt!='Rework' AND stkpt!='Stores' GROUP BY prcno,stkpt HAVING (sum(partreceived)-sum(partissued))>0";
		$result2 = $con->query($query2);
		//while($row1 = mysqli_fetch_array($result2))
		$row1 = mysqli_fetch_array($result2);
		{
			if($row1['days']>'30')
			{
				mysqli_query($con,"UPDATE status set erp='2'");
				//header("location: inputlink.php?msg=30");
			}
		}
		
		$query1 = "SELECT erp from status";
		$result1 = $con->query($query1);
		$row1 = mysqli_fetch_array($result1);
		if($row1['erp']=="2")
		{
			//if($_SESSION['user']!='123' && $_SESSION['user']!="100" && $_SESSION['user']!="126" && $_SESSION['user']!="102" && $_SESSION['user']!="107" && $_SESSION['user']!="117" && $_SESSION['user']!="122" && $_SESSION['user']!="113" && $_SESSION['user']!="109" && $_SESSION['user']!="105" && $_SESSION['user']!="104" && $_SESSION['user']!="111" && $_SESSION['user']!="127" && $_SESSION['user']!="108" && $_SESSION['user']!="125" && $_SESSION['user']!="129")
			if($_SESSION['user']!='123' && $_SESSION['user']!="100" && $_SESSION['user']!="126" && $_SESSION['user']!="107" && $_SESSION['user']!="117" && $_SESSION['user']!="109")
			{
				unset($_SESSION['user']);
				unset($_SESSION['username']);
				unset($_SESSION['access']);
				unset($_SESSION['ip']);
				session_destroy();
				mysqli_query($con,"UPDATE admin1 set status='0' where userid='$id'");
				header("location: lock1.php?err1=7");
			}
		}
		*/
		
		$_SESSION['timeout']=time();
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$activity="HOME";
		mysqli_query($con,"UPDATE admin1 set activity='$activity',status='1',ip='$ip',lastact='$time' where userid='$id'");
		if($_SESSION['access']!="")
		{
			echo'<a class="fontchange" href="i26.php" ><label> ROUTE CARD TRANSACTION DETAILS </label></a><br><br>';
		}
		if(($_SESSION['access']=="ALL" || $_SESSION['access']=="PURCHASE") && $_SESSION['user']!="110")
		{
			echo'<a class="fontchange" href="i71.php" ><label> PURCHASE ORDER PREPERATION (I71) </label></a><br><br>';
			echo'<a class="fontchange" href="poprinting.php" ><label> PURCHASE ORDER PRINTING </label></a><br><br>';
		}
		if($_SESSION['user']=="100" || $_SESSION['user']=="123")
		{
			//echo'<a class="fontchange" href="i31.php" ><label> STOCK INITIALISATION (I31) </label></a><br><br>';
			echo'<a class="fontchange" href="i27.php" ><label> GRN NUMBER BASED ENTRY VERIFICATION [ I27 ] </label></a><br><br>';
			echo'<a class="fontchange" href="i34.php" ><label> PRODUCTION ENTRY [ I34 ] </label></a><br><br>';
		}
		if($_SESSION['user']=="100" || $_SESSION['user']=="123" || $_SESSION['user']=="121")
		{
			echo'<a class="fontchange" href="npdparts.php" ><label> NPD PARTS UPDATION </label></a><br><br>';
		}
		if($_SESSION['user']=="111" || $_SESSION['user']=="123" || $_SESSION['user']=="127")
		{
			echo'<a class="fontchange" href="orderbook.php" ><label> ORDER BOOK UPDATION (OB) </label></a><br><br>';
			echo'<a class="fontchange" href="vssorder.php" ><label> DEMAND MASTER UPDATION [ DM ] </label></a><br><br>';
		}
		if($_SESSION['user']=="127" || $_SESSION['user']=="109" || $_SESSION['user']=="135")
		{
			//echo'<a class="fontchange" href="parentchildupdation.php" ><label>PARENT CHILD MASTER UPDATION</label></a><br><br>';
			//echo'<a class="fontchange1" href="i11.php"><label>ITEM PROCESS MASTER UPDATION </label></a><br><br>';
			echo'<a class="fontchange1" href="orderbook_master.php"><label>ORDERBOOK MASTER UPDATION </label></a><br><br>';
		}
		if($_SESSION['access']=="Stores" || $_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="i27.php" ><label> GRN NUMBER BASED ENTRY VERIFICATION [ I27 ] </label></a><br><br>';
			echo'<a class="fontchange" href="i81.php" ><label> ( SUB-CONTRACTOR ) RM ENTRY (I81) </label></a><br><br>';
			echo'<a class="fontchange" href="i83.php" ><label> CUSTOMER REJECTION RECEIPT ENTRY (I83) </label></a><br><br>';
			echo'<a class="fontchange" href="rep_vi12_1.php" ><label> A ROUTE CARD RE-PRINT </label></a><br><br>';
			echo'<a class="fontchange" href="i12_1.php" ><label>RAW MATERIAL ISSUANCE & RC GENERATION [i12_1]</label></a><br><br>';
			echo'<a class="fontchange" href="i84.php" ><label> RETURNED MATERIAL ISSUANCE & RC GENERATION [i84]</label></a><br><br>';
			
			echo'<a class="fontchange" href="rmdc.php" ><label> DC ISSAUNCE FOR RAW MATERIAL [RMDC]</label></a><br><br>';
			echo'<a class="fontchange" href="dcprint_rm.php" ><label> DC PRINT RAW MATERIAL</label></a><br><br>';
			
			//echo'<a class="fontchange" href="grn_reco.php" ><label> GRN RECONCILIATION </label></a><br><br>';
		}
		if($_SESSION['user']=="123" || $_SESSION['user']=="104")
		{
			echo'<a class="fontchange" href="i13_rm_receipt.php" ><label> RAW MATERIAL RECEIPT </label></a><br><br>';
		}
		if($_SESSION['user']=="123" || $_SESSION['user']=="100")
		{
			echo'<a class="fontchange" href="rep_vi12_1.php" ><label> A ROUTE CARD RE-PRINT </label></a><br><br>';
			//echo'<a class="fontchange" href="i81.php" ><label> ( SUB-CONTRACTOR ) RM ENTRY (I81) </label></a><br><br>';
		}
		if(($_SESSION['access']=="To S/C" || $_SESSION['access']=="From S/C" || $_SESSION['access']=="Semifinished1" || $_SESSION['access']=="Semifinished2" || $_SESSION['access']=="ALL")  && $_SESSION['user']!="110")
		{
			echo'<a class="fontchange" href="i12_2.php"> <label>PARTS ISSUANCE & ROUTE CARD GENERATION [i12_2]</label></a><br><br>';
		}
		if(($_SESSION['access']=="From S/C" || $_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C" || $_SESSION['access']=="Semifinished1" || $_SESSION['access']=="Semifinished2" || $_SESSION['access']=="ALL")  && $_SESSION['user']!="110")
		{
			echo'<a class="fontchange" href="i13.php" ><label> PARTS RECEIPT & ROUTE CARD CLOSURE [i13]</label></a><br><br>';
		}
		if(($_SESSION['access']=="FG For S/C" || $_SESSION['access']=="ALL")  && $_SESSION['user']!="110")
		{
			echo'<a class="fontchange" href="i13alfa.php" ><label> ALFA PARTS RECEIPT & ROUTE CARD CLOSURE  [i13alfa]</label></a><br><br>';
		}
		if(($_SESSION['access']=="Quality" || $_SESSION['access']=="ALL") && $_SESSION['user']!="110")
		{
			echo'<a class="fontchange" href="i15.php" ><label> QUALITY UPDATE [i15]</label></a><br><br>';
			echo'<a class="fontchange" href="i82.php" ><label> INCOMING INSP QUALITY UPDATE [i82]</label></a><br><br>';
		}
		if($_SESSION['user']=="105" || $_SESSION['user']=="123" || $_SESSION['user']=="135")
		{
			echo'<a class="fontchange" href="pack_slip_print.php" ><label> PACKING SLIP PRINTING [ALTERNATE OPTION]</label></a><br><br>';
			echo'<a class="fontchange" href="packing.php" ><label> PACKING SLIP PRINTING [PSP]</label></a><br><br>';
			echo'<a class="fontchange" href="packing1.php" ><label> PACKING SLIP PRINTING 2 </label></a><br><br>';
			echo'<a class="fontchange" href="label_printing.php" ><label> HEAT LOT COIL NO PRINTING </label></a><br><br>';
		}
		if($_SESSION['user']=="123" || $_SESSION['user']=="105" || $_SESSION['user']=="104")
		{
			echo'<a class="fontchange" href="eway_bill_dc.php" ><label> EWAY BILL FOR DC REPORT </label></a><br><br>';
		}
		if($_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="packingu2.php" ><label> PACKING SLIP PRINTING [UNIT 2]</label></a><br><br>';
		}
		if($_SESSION['access']=="Stores" || $_SESSION['access']=="Semifinished1" || $_SESSION['access']=="Semifinished2")
		{
			echo'<a class="fontchange" href="i17.php" ><label>RETURN OF RAW MATERIAL [i17]</label></a><br><br>';
		}
		if($_SESSION['access']=="To S/C" || $_SESSION['access']=="ALL")
		{
			//echo'<a class="fontchange" href="i18.php" ><label>SUB CONTRACT REWORK ISSUANCE ENTRY [i18]</label></a><br><br>';
			//echo'<a class="fontchange" href="i19.php" ><label>SUB CONTRACT REWORK RECEIPT ENTRY[i19]</label></a><br><br>';
		}
		if($_SESSION['access']=="FI")
		{
			echo'<a class="fontchange" href="i23.php" ><label>FINAL INSPECTION MASTER [i23]</label></a><br><br>';
			echo'<a class="fontchange" href="f_insp_app.php" ><label>FINAL INSP APPROVAL (RC) </label></a><br><br>';
			echo'<a class="fontchange" href="ir_print.php" ><label>FINAL INSP PRINTING </label></a><br><br>';
			//echo'<a class="fontchange" href="ir_print1.php" ><label>FI INSP PRINT </label></a><br><br>';
			
		}
		if($_SESSION['user']=="125")
		{
			echo'<a class="fontchange" href="i23.php" ><label>FINAL INSPECTION MASTER [i23]</label></a><br><br>';
			echo'<a class="fontchange" href="ir_print.php" ><label>FINAL INSP PRINTING </label></a><br><br>';
		}
		if($_SESSION['access']=="ALL" || $_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="inv_c_app.php" ><label>INVOICE CORRECTION APPROVAL [INVCA]</label></a><br><br>';
			//echo'<a class="fontchange" href="i24.php" ><label>FINAL INSPECTION [i24]</label></a><br><br>';
			echo'<a class="fontchange" href="fireport.php" ><label>FINAL INSPECTION REPORT PRINTING</label></a><br><br>';
		}
		if($_SESSION['access']=="ALL" || $_SESSION['user']=="121")
		{
			echo'<a class="fontchange" href="npd_inv_app.php" ><label>NPD PARTS INVOICE APPROVAL</label></a><br><br>';
			echo'<a class="fontchange" href="npdinvoicedparts.php" ><label>INVOICED NPD PARTS</label></a><br><br>';
		}
		if($_SESSION['user']=="136" || $_SESSION['user']=="132")
		{
			echo'<a class="fontchange" href="npdinvoicedparts.php" ><label>INVOICED NPD PARTS</label></a><br><br>';
		}
		if(($_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C" || $_SESSION['access']=="ALL")  && $_SESSION['user']!="110")
		{
			echo'<a class="fontchange" href="alfatest.php" ><label>DC ISSUANCE [DC]</label></a><br><br>';
			echo'<a class="fontchange" href="re_sub.php" ><label>DC RE-ISSUANCE [DC]</label></a><br><br>';
			echo'<a class="fontchange" href="dcprint.php" ><label>DC PRINTING [DCP]</label></a><br><br>';
			
			echo'<a class="fontchange" href="dc_combine_print.php" ><label> DC PRINT [MULTIPLE PARTS]</label></a><br><br>';
		}
		if($_SESSION['access']=="ACCOUNTS")
		{
			echo'<a class="fontchange" href="dcprint.php" ><label>DC PRINTING [DCP]</label></a><br><br>';
		}
		if($_SESSION['access']=="FG For Invoicing")
		{
			echo'<a class="fontchange" href="newinv.php" ><label>INVOICE [INV]</label></a><br><br>';
			echo'<a class="fontchange" href="newinv2dc.php" ><label>DC WITHOUT INVOICE</label></a><br><br>';
			echo'<a class="fontchange" href="nontraceinv.php" ><label> INVOICING FOR NON-UNIT 1 PARTS [INV]</label></a><br><br>';
			echo'<a class="fontchange" href="inv_c_req.php" ><label>INVOICE CORRECTION REQUEST[INVCR]</label></a><br><br>';
			echo'<a class="fontchange" href="reinv.php" ><label>INVOICE CORRECTION [INVC]</label></a><br><br>';
			echo'<a class="fontchange" href="supinv.php" ><label>SUPPLIMENTARY INVOICE [SINV]</label></a><br><br>';
			//echo'<a class="fontchange" href="supinv_temp.php" ><label>SUPPLIMENTARY INVOICE [TEMP]</label></a><br><br>';
			echo'<a class="fontchange" href="inv_c_app.php" ><label> INVOICE CORRECTION APPROVAL STATUS </label></a><br><br>';
			
			//echo'<a class="fontchange" href="einvoice_final.php" ><label> E - INVOICE (GOVT PORTAL FORMAT) </label></a><br><br>';
			echo'<a class="fontchange" href="einvoice_final_dt.php" ><label> E - INVOICE (GOVT PORTAL FORMAT) </label></a><br><br>';
		}
		if($_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="newinv2dc.php" ><label>DC WITHOUT INVOICE</label></a><br><br>';
		}
		if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="Quality" || $_SESSION['user']=="127" || $_SESSION['user']=="111" || $_SESSION['user']=="124")
		{
			echo'<a class="fontchange" href="mprint.php" ><label> PRINT INVOICES [INVP]</label></a><br><br>';
		}
		if(($_SESSION['access']=="ALL" || $_SESSION['user']=="100") && $_SESSION['user']!="110")
		{
			echo'<a class="fontchange" href="downtime.php" ><label> MACHINE DOWN TIME ENTRY [MDE]</label></a><br><br>';
		}
		if(($_SESSION['access']=="ALL" || $_SESSION['user']=="100" || $_SESSION['user']=="115") && $_SESSION['user']!="110" || $_SESSION['user']=="117" || $_SESSION['user']=="102" || $_SESSION['user']=="108")
		{
			echo'<a class="fontchange" href="weeklycommit.php" ><label> WEEKLY COMMITMENT ENTRY [WCE]</label></a><br><br>';
		}
		if($_SESSION['user']=="127" || $_SESSION['user']=="117" || $_SESSION['user']=="111")
		{
			echo'<a class="fontchange" href="obinv6.php" ><label> ORDERBOOK STATUS [REPORT]</label></a><br><br>';
		}
		if( $_SESSION['user']=="123" || $_SESSION['user']=="100" || $_SESSION['user']=="102" )
		{
			echo'<a class="fontchange" href="bom_calc.php" ><label>BOM CALCULATOR</label></a><br><br>';
		}
		if($_SESSION['user']=="123" || $_SESSION['user']=="100" || $_SESSION['user']=="105" || $_SESSION['user']=="104"  )
		{
			echo'<a class="fontchange" href="bom_calc_finalarea.php" ><label>BOM CALCULATOR (FINAL AREA PARTS)</label></a><br><br>';
		}
		if( $_SESSION['user']=="123" || $_SESSION['user']=="108")
		{
			echo'<a class="fontchange" href="m_code.php" ><label>MATERIAL CODE & RAW MATERIAL MASTER</label></a><br><br>';
		}
		if($_SESSION['user']=="133" || $_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="unit2receiving_entry.php" ><label>PART RECEIPT [ UNIT 1 PART RECEIVE ENTRY ]</label></a><br><br>';
			
			echo'<a class="fontchange" href="unit2cle.php" ><label>CLE PART ISSUANCE</label></a><br><br>';
			echo'<a class="fontchange" href="unit2cle_receipt.php" ><label>CLE PART RECEIPT</label></a><br><br>';
			
			echo'<a class="fontchange" href="subcontract_entry.php" ><label> SUB CONTRACT VIRTUAL DC ENTRY </label></a><br><br>';
			echo'<a class="fontchange" href="subcontract_rej_entry.php" ><label> SUB CONTRACTOR REJECTION ENTRY </label></a><br><br>';
			
			echo'<a class="fontchange" href="alfatestunit2.php" ><label> DC ISSUANCE [ ONLY TO UNIT 1 ] </label></a><br><br>';
			echo'<a class="fontchange" href="dcprint_unit2.php" ><label> DC PRINT  </label></a><br><br>';
			
			echo'<a class="fontchange" href="packingu2.php" ><label> PACKING SLIP PRINTING [UNIT 2]</label></a><br><br>';
		}
		
	}
	else
	{
		header("location: index.php");
	}
	?>
	</form>	
</div>
<div>
	<form class="right" method="post">
	<h4 style="text-align:left;"><label style="color:#33ff99";>VSS REPORTS - [REAL TIME REPORTS ONLY]</label></h4>
	<?php
	if($_SESSION['access']!="")
	{
		//echo'<a class="fontchange" href="o11tab.php"><label>PRODUCTION REPORT [o11]</label></a><br><br>';
		echo'<a class="fontchange" href="o12tab.php"><label>OPEN ROUTE CARD STATUS REPORT [o12]</label></a><br><br>';
		echo'<a class="fontchange" href="o12tab_open_close.php"><label>OPEN & CLOSE ROUTE CARD COMBINE REPORT </label></a><br><br>';
		echo'<a class="fontchange" href="o13tab.php" ><label>ROUTE CARD CLOSURE VARIANCE REPORT [o13]</label></a><br><br>';
		echo'<a class="fontchange" href="o14tab.php"> <label>STOCK REPORT  [o14]</label></a><br><br>';
		echo'<a class="fontchange" href="o22.php"> <label> COMMIT VS ACTUAL REPORT  [ O22 ]</label></a><br><br>';
		echo'<a class="fontchange" href="invoice.php"><label>INVOICE REPORT</label></a><br><br>';
		echo'<a class="fontchange" href="invoice_for_weight.php"><label>INVOICE REPORT(WEIGHT)</label></a><br><br>';
		echo'<a class="fontchange" href="o20tab.php" ><label>PART NUMBER SUMMARY REPORT [ O20 ] </label></a><br><br>';
		echo'<a class="fontchange" href="O21tab.php" ><label>DC REPORT [ O21 ] </label></a><br><br>';
		echo'<a class="fontchange" href="open_subcontract.php" ><label>OPEN DC/SUB-CONTRACT REPORT </label></a><br><br>';
		echo'<a class="fontchange" href="getinvoice.php" ><label>TRACABILITY REPORT [o15]</label></a><br><br>';
		echo'<a class="fontchange" href="o16tab.php" ><label>SUSPECT REPORT [o16]</label></a><br><br>';
		echo'<a class="fontchange" href="o23tab.php"> <label>STORES STOCK REPORT  [o23]</label></a><br><br>';
		//echo'<a class="fontchange" href="o28tab.php"> <label> STOCK REPORT SUMMARY [o28]</label></a><br><br>';
		echo'<a class="fontchange" href="o18tab.php" ><label>VSS VALUATION REPORT [o18]</label></a><br><br>';
	}
	if($_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="orderbookpartstatus.php" ><label> ORDER BOOK STATUS (REG / STRGER) </label></a><br><br>';
			echo'<a class="fontchange" href="kanbanreport.php" ><label> KANBAN STATUS REPORT </label></a><br><br>';
		}
	if($_SESSION['access']=="FG For S/C" || $_SESSION['access']=="FI")
	{
		echo'<a class="fontchange" href="o17.php" ><label>DESPATCH ROUTE CARD LIST (DIRECT)[o17]</label></a><br><br>';
		echo'<a class="fontchange" href="o171.php" ><label>DESPATCH ROUTE CARD LIST (INDIRECT) [o171]</label></a><br><br>';
	}
	if($_SESSION['user']=="133" || $_SESSION['user']=="123" || $_SESSION['user']=="110")
	{
		//echo'<a class="fontchange" href="o17.php" ><label>DESPATCH ROUTE CARD LIST (DIRECT)[o17]</label></a><br><br>';
		echo'<a class="fontchange" href="o171.php" ><label>DESPATCH ROUTE CARD LIST (INDIRECT) [o171]</label></a><br><br>';
		
	}
	if($_SESSION['access']=="Stores" || $_SESSION['user']=="100")
	{
		echo'<a class="fontchange" href="return.php" ><label>RETURN RAW MATERIAL REPORT [RET]</label></a><br><br>';
	}
	//if($_SESSION['access']=="Quality" || $_SESSION['user']=="123" || $_SESSION['user']=="100" || $_SESSION['user']=="132" || $_SESSION['user']=="136" )
	if($_SESSION['user']!="" )
	{
		//echo'<a class="fontchange" href="o25tab.php"> <label> REJECTION REPORT  [o25]</label></a><br><br>';
		echo'<a class="fontchange" href="Rej_Report2.php"> <label> REJECTION REPORT  [o25(update)]</label></a><br><br>';
	}
	if($_SESSION['user']=="123" || $_SESSION['user']=="133")
	{
		echo'<a class="fontchange" href="Rej_Report_unit2.php"> <label> REJECTION REPORT UNIT 2</label></a><br><br>';
	}
	if( $_SESSION['access']=="ALL" || $_SESSION['access']=="ACCOUNTS" )
	{
		echo'<a class="fontchange" href="o18tab.php" ><label>VSS VALUATION REPORT [o18]</label></a><br><br>';
	}
	if( $_SESSION['access']=="ALL" || $_SESSION['access']=="FG For Invoicing" )
	{
		echo'<a class="fontchange" href="eway.php" ><label>VSS E-WAY REPORT [o21]</label></a><br><br>';
	}
	if( $_SESSION['access']=="ALL" || $_SESSION['access']=="To S/C" || $_SESSION['access']=="NONE")
	{
		echo'<a class="fontchange" href="o19tab.php" ><label>SUB-CONTRACT STOCK REPORT  [ O19 ]</label></a><br><br>';
	}
	if( $_SESSION['access']=="ACCOUNTS" || $_SESSION['user']=="123" || $_SESSION['user']=="100" || $_SESSION['user']=="116" || $_SESSION['user']=="130")
	{
		echo'<a class="fontchange" href="prod_iss3.php" ><label>STORE RM ISSUANCE TO PRODUCTION REPORT</label></a><br><br>';
	}
	
	?>
	</form>	
</div>
<div>
	<form class="tooleft" method="post">
	<h4 style="text-align:left"><label style="color:red";>ADMINISTRATION / MASTER UPDATION</label></h4>
	<?php
		if(($_SESSION['access']=="ALL" && $_SESSION['user']=="123") || ($_SESSION['user']=="100"))
		{
			$query1 = "SELECT erp from status";
			$result1 = $con->query($query1);
			$row1 = mysqli_fetch_array($result1);
			if($row1['erp']=="0")
			{
				echo'<a class="fontchange1" href="lock.php"><label>LOCK ERP SYSTEM [LES]</label></a><br><br>';
			}
			else
			{
				echo'<a class="fontchange1" href="unlock.php"><label>UNLOCK ERP SYSTEM [UES]</label></a><br><br>';
			}
			//echo'<a class="fontchange1" href="reconcile.php" ><label> STOCKING POINT RECONCILATION </label></a><br><br>';
			//echo'<a class="fontchange1" href="operationreconcile.php" ><label> OPERATION RECONCILATION</label></a><br><br>';
			echo'<a class="fontchange1" href="bom.php"><label>BOM MASTER UPDATION [BOM]</label></a><br><br>';
			echo'<a class="fontchange1" href="i11.php"><label>ITEM PROCESS MASTER UPDATION </label></a><br><br>';
		}
		if($_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="weekly_mpreport.php" ><label> MATERIAL PROCESSING </label></a><br><br>';
			echo'<a class="fontchange" href="weekly_mpreport_summary.php" ><label> MATERIAL PROCESSING SUMMARY </label></a><br><br>';
			echo'<a class="fontchange" href="weekly_mpreport_summary_category.php" ><label> MATERIAL PROCESSING SUMMARY CATEGORY </label></a><br><br>';
		}
		
		if($_SESSION['user']=="100" || $_SESSION['user']=="123" || $_SESSION['user']=="133")
		{
			echo'<a class="fontchange" href="alfa_nind_primech.php" ><label> ALFA N-IND PRIM UNIT 2 STOCK</label></a><br><br>';
		}
		
		if($_SESSION['user']=="100" || $_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="ppc9.php" ><label> PPC PLAN [PPC]</label></a><br><br>';
			echo'<a class="fontchange" href="obinv6.php" ><label> OB STATUS [OBINV6]</label></a><br><br>';
			echo'<a class="fontchange" href="kanbanstatus1.php" ><label> KANBAN STATUS</label></a><br><br>';
			echo'<a class="fontchange" href="alfa_nind_primech.php" ><label> ALFA N-IND PRIM UNIT 2 STOCK</label></a><br><br>';
			
			//echo'<a class="fontchange" href="weekly_mpreport.php" ><label> MATERIAL PROCESSING </label></a><br><br>';
			//echo'<a class="fontchange" href="weekly_mpreport_summary.php><label> MAT SUMMARY </label></a><br><br>';
			//echo'<a class="fontchange" href="weekly_mpreport_summary_category.php"><label> MATERIAL PROCESSING SUMMARY BY CATEGORY</label></a><br><br>';
			
			//echo'<a class="fontchange" href="DailyMatReco.php" ><label> DMR [OPERATION]</label></a><br><br>';
			//echo'<a class="fontchange" href="dmr_stkpt.php" ><label> DMR [STOCKING POINT]</label></a><br><br>';
			//echo'<a class="fontchange" href="DailyMatReco_stkpt.php" ><label> DMR [STOCKING POINT]</label></a><br><br>';
			//echo'<a class="fontchange" href="obinv.php" ><label> OB REG / STR INVOICED STATUS [OBINV]</label></a><br><br>';
			//echo'<a class="fontchange" href="obinv1.php" ><label> OB KANBAN INVOICED STATUS [OBINV1]</label></a><br><br>';
			//echo'<a class="fontchange" href="ppc1.php" ><label> PPC PLAN UPDATION [ PPC ] </label></a><br><br>';
		}
		if($_SESSION['access']=="ALL" || $_SESSION['access']=="PURCHASE")
		{
			echo'<a class="fontchange" href="i72.php" ><label> SUPPLIER MASTER UPDATION (PURCHASE) (I72) </label></a><br><br>';
		}
		if($_SESSION['access']=="ALL" || $_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C")
		{
			echo'<a class="fontchange" href="i28.php" ><label> NEW DC MASTER INSERTION & UPDATION (I28) </label></a><br><br>';
		}
		if($_SESSION['access']=="ALL")
		{
			//echo'<a class="fontchange1" href="create.php"><label>CREATE USER ACCOUNTS [CUA]</label></a><br><br>';
			//echo'<a class="fontchange1" href="manageuser.php"><label>MANAGE USER ACCOUNTS [MUA]</label></a><br><br>';
			echo'<a class="fontchange1" href="parentchildupdation.php" ><label>PARENT CHILD MASTER UPDATION</label></a><br><br>';
			//echo'<a class="fontchange1" href="time.php" ><label> INVOICE TIMING [INVT] </label></a><br><br>';
			echo'<a class="fontchange1" href="i25.php"><label>ROUTE CARD BASED ENTRY CORRECTION [ I25 ]</label></a><br><br>';
			echo'<a class="fontchange1" href="i21.php" ><label>INVOICE MASTER INSERTION [i21]</label></a><br><br>';
			//echo'<a class="fontchange1" href="setprinter.php" ><label> INVOICE PRINTING METHOD </label></a><br><br>';
		}
		if($_SESSION['user']=="102")
		{
			//echo'<a class="fontchange1" href="i25.php"><label>ROUTE CARD BASED ENTRY CORRECTION [ I25 ]</label></a><br><br>';
		}
		if($_SESSION['access']!="")
		{
			//echo'<a class="fontchange1" href="manage.php"><label>MANAGE YOUR ACCOUNTS [MUA]</label></a><br><br>';
		}
		if($_SESSION['access']=="FG For Invoicing")
		{
			echo'<a class="fontchange1" href="newpart.php"><label> ADD NEW PART IN TRACEABILITY  [ANP]</label></a><br><br>';
			echo'<a class="fontchange1" href="newbom.php"><label> PART UPDATE IN Traceability  [ANP]</label></a><br><br>';
			echo'<a class="fontchange1" href="i20.php" ><label>INVOICE MASTER PART UPDATION [i20]</label></a><br><br>';
			echo'<a class="fontchange1" href="i22.php" ><label>INVOICE MASTER CUSTOMER UPDATION [i22]</label></a><br><br>';
			//echo'<a class="fontchange" href="vmiupdate.php" ><label> VMI MASTER UPDATION [VMIU]</label></a><br><br>';
			echo'<a class="fontchange" href="vssorder.php" ><label> KANBAN MASTER UPDATION [VMIU]</label></a><br><br>';
			echo'<a class="fontchange" href="orderbook.php" ><label> ORDERBOOK MASTER UPDATION [OBMU]</label></a><br><br>';
		}
		if($_SESSION['access']=="FI")
		{
			echo'<a class="fontchange1" href="i23.php" ><label>FINAL INSPECTION MASTER INSERTION [ I23 ]</label></a><br><br>';
		}
		if($_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="opclunion3.php" ><label> MP </label></a><br><br>';
			echo'<a class="fontchange" href="wm_mp_analysis.php" ><label> MP SUMMARY </label></a><br><br>';
			echo'<a class="fontchange" href="wm_mp_analysis_category.php" ><label> MP CATEGORY SUMMARY </label></a><br><br>';
		}
		if($_SESSION['user']=="123" || $_SESSION['user']=="100")
		{
			echo'<a class="fontchange" href="f_insp_app_pending.php" ><label> FINAL INSPECTION PENDING</label></a><br><br>';
		}
		if($_SESSION['user']=="100" || $_SESSION['user']=="130")
		{
			echo'<a class="fontchange" href="f_insp_app_pending.php" ><label> FINAL INSPECTION PENDING</label></a><br><br>';
			echo'<a class="fontchange" href="opclunion3.php" ><label> MATERIAL PROCESSIG REPORT(RC WISE)</label></a><br><br>';
			echo'<a class="fontchange" href="wm_mp_analysis.php" ><label> MP SUMMARY (OPERATION & FOREMAN WISE)</label></a><br><br>';
			echo'<a class="fontchange" href="wm_mp_analysis_category.php" ><label> MP CATEGORY SUMMARY (RM CATEGORY WISE)</label></a><br><br>';
		}
		if($_SESSION['user']=="105" || $_SESSION['user']=="123" )
		{
			echo'<a class="fontchange" href="subcontract_entry.php" ><label> SUB CONTRACT VIRTUAL DC ENTRY </label></a><br><br>';
			echo'<a class="fontchange" href="subcontract_rej_entry.php" ><label> SUB CONTRACTOR REJECTION ENTRY </label></a><br><br>';
			echo'<a class="fontchange" href="subcontract_inventory_report1.php" ><label> SUB CONTRACTOR INVENTORY REPORT </label></a><br><br>';
		}
		if($_SESSION['user']=="123" || $_SESSION['user']=="133")
		{
			echo'<a class="fontchange" href="subcontract_inventory_report_for_unit2.php" ><label> SUB CONTRACTOR INVENTORY REPORT ( UNIT 2 ) </label></a><br><br>';
			echo'<a class="fontchange" href="CLE_report_rc.php" ><label>CLE TRANSACTION REPORT</label></a><br><br>';
			
			echo'<a class="fontchange" href="unit2receiving_report.php" ><label> UNIT 2 INVENTORY REPORT </label></a><br><br>';
			echo'<a class="fontchange" href="unit2DC_report.php" ><label> DC REPORT [ UNIT 2 ] </label></a><br><br>';
			
			echo'<a class="fontchange" href="bom_calc_finalarea.php" ><label>BOM CALCULATOR (FINAL AREA PARTS)</label></a><br><br>';	
		}
		if($_SESSION['user']=="109")
		{
			echo'<a class="fontchange" href="subcontract_inventory_report1.php" ><label> SUB CONTRACTOR INVENTORY REPORT </label></a><br><br>';
		}
		if($_SESSION['user']=="123" || $_SESSION['user']=="100")
		{
			echo'<a class="fontchange" href="stock_reconcile_menu.php" ><label>RECONCILATION MENU</label></a><br><br>';
		}
		if($_SESSION['user']=="134")
		{
			echo'<a class="fontchange" href="stock_reconcile_menu.php" ><label>RECONCILATION MENU</label></a><br><br>';
		}
		if($_SESSION['user']=="121" )
		{
			echo'<a class="fontchange" href="Rej_Report2.php"> <label> REJECTION REPORT  </label></a><br><br>';
		}
		
	?>
	</form>	
</div>
</body>
</html>