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
	header("location: takebackup.php");
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
if(substr($dt,7,2)!=date("W") && date("H")>=12)
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
         echo "' Message : $t AREA HAVING RC MORE THAN 7 DAYS';";
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
			if($_SESSION['user']!='123' && $_SESSION['user']!="100" && $_SESSION['user']!="113" && $_SESSION['user']!="122")
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
			echo'<a class="fontchange" href="i31.php" ><label> STOCK INITIALISATION (I31) </label></a><br><br>';
			echo'<a class="fontchange" href="i27.php" ><label> GRN NUMBER BASED ENTRY VERIFICATION [ I27 ] </label></a><br><br>';
			echo'<a class="fontchange" href="i34.php" ><label> PRODUCTION ENTRY [ I34 ] </label></a><br><br>';
		}
		if($_SESSION['user']=="111" || $_SESSION['user']=="123" || $_SESSION['user']=="127")
		{
			echo'<a class="fontchange" href="orderbook.php" ><label> ORDER BOOK UPDATION (OB) </label></a><br><br>';
			echo'<a class="fontchange" href="vssorder.php" ><label> DEMAND MASTER UPDATION [ DM ] </label></a><br><br>';
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
		if($_SESSION['user']=="105" || $_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="packing.php" ><label> PACKING SLIP PRINTING [PSP]</label></a><br><br>';
			echo'<a class="fontchange" href="packing1.php" ><label> PACKING SLIP PRINTING 2 </label></a><br><br>';
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
			echo'<a class="fontchange" href="ir_print.php" ><label>FINAL INSP PRINTING </label></a><br><br>';
			echo'<a class="fontchange" href="f_insp_app.php" ><label>FINAL INSP APPROVAL (RC) </label></a><br><br>';
		}
		if($_SESSION['access']=="ALL" || $_SESSION['user']=="100")
		{
			echo'<a class="fontchange" href="inv_c_app.php" ><label>INVOICE CORRECTION APPROVAL [INVCA]</label></a><br><br>';
			//echo'<a class="fontchange" href="i24.php" ><label>FINAL INSPECTION [i24]</label></a><br><br>';
			echo'<a class="fontchange" href="fireport.php" ><label>FINAL INSPECTION REPORT PRINTING</label></a><br><br>';
		}
		if(($_SESSION['access']=="To S/C" || $_SESSION['access']=="FG For S/C" || $_SESSION['access']=="ALL")  && $_SESSION['user']!="110")
		{
			echo'<a class="fontchange" href="alfatest.php" ><label>DC ISSUANCE [DC]</label></a><br><br>';
			echo'<a class="fontchange" href="re_sub.php" ><label>DC RE-ISSUANCE [DC]</label></a><br><br>';
			echo'<a class="fontchange" href="dcprint.php" ><label>DC PRINTING [DCP]</label></a><br><br>';
		}
		if($_SESSION['access']=="ACCOUNTS")
		{
			echo'<a class="fontchange" href="dcprint.php" ><label>DC PRINTING [DCP]</label></a><br><br>';
		}
		if($_SESSION['access']=="FG For Invoicing")
		{
			echo'<a class="fontchange" href="newinv.php" ><label>INVOICE [INV]</label></a><br><br>';
			echo'<a class="fontchange" href="nontraceinv.php" ><label> INVOICING FOR NON-UNIT 1 PARTS [INV]</label></a><br><br>';
			echo'<a class="fontchange" href="inv_c_req.php" ><label>INVOICE CORRECTION REQUEST[INVCR]</label></a><br><br>';
			echo'<a class="fontchange" href="reinv.php" ><label>INVOICE CORRECTION [INVC]</label></a><br><br>';
			echo'<a class="fontchange" href="supinv.php" ><label>SUPPLIMENTARY INVOICE [SINV]</label></a><br><br>';
			echo'<a class="fontchange" href="inv_c_app.php" ><label> INVOICE CORRECTION APPROVAL STATUS </label></a><br><br>';
		}
		if($_SESSION['access']=="FG For Invoicing" || $_SESSION['access']=="Quality")
		{
			echo'<a class="fontchange" href="mprint.php" ><label> PRINT INVOICES [INVP]</label></a><br><br>';
		}
		if(($_SESSION['access']=="ALL" || $_SESSION['user']=="100") && $_SESSION['user']!="110")
		{
			echo'<a class="fontchange" href="downtime.php" ><label> MACHINE DOWN TIME ENTRY [MDE]</label></a><br><br>';
		}
		if(($_SESSION['access']=="ALL" || $_SESSION['user']=="100" || $_SESSION['user']=="115") && $_SESSION['user']!="110" || $_SESSION['user']=="117")
		{
			echo'<a class="fontchange" href="weeklycommit.php" ><label> WEEKLY COMMITMENT ENTRY [WCE]</label></a><br><br>';
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
		echo'<a class="fontchange" href="o13tab.php" ><label>ROUTE CARD CLOSURE VARIANCE REPORT [o13]</label></a><br><br>';
		echo'<a class="fontchange" href="o14tab.php"> <label>STOCK REPORT  [o14]</label></a><br><br>';
		echo'<a class="fontchange" href="o22.php"> <label> COMMIT VS ACTUAL REPORT  [ O22 ]</label></a><br><br>';
		echo'<a class="fontchange" href="invoice.php"><label>INVOICE REPORT</label></a><br><br>';
		echo'<a class="fontchange" href="o20tab.php" ><label>PART NUMBER SUMMARY REPORT [ O20 ] </label></a><br><br>';
		echo'<a class="fontchange" href="O21tab.php" ><label>DC REPORT [ O21 ] </label></a><br><br>';
		echo'<a class="fontchange" href="getinvoice.php" ><label>TRACABILITY REPORT [o15]</label></a><br><br>';
		echo'<a class="fontchange" href="o16tab.php" ><label>SUSPECT REPORT [o16]</label></a><br><br>';
		echo'<a class="fontchange" href="o23tab.php"> <label>STORES STOCK REPORT  [o23]</label></a><br><br>';
		echo'<a class="fontchange" href="o28tab.php"> <label> STOCK REPORT SUMMARY [o28]</label></a><br><br>';
		echo'<a class="fontchange" href="o18tab.php" ><label>VSS VALUATION REPORT [o18]</label></a><br><br>';
	}
	if($_SESSION['user']=="111" || $_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="orderbookpartstatus.php" ><label> ORDER BOOK STATUS (REG / STRGER) </label></a><br><br>';
			echo'<a class="fontchange" href="kanbanreport.php" ><label> KANBAN STATUS REPORT </label></a><br><br>';
		}
	if($_SESSION['access']=="FG For S/C" || $_SESSION['access']=="FI")
	{
		echo'<a class="fontchange" href="o17.php" ><label>DESPATCH ROUTE CARD LIST (DIRECT)[o17]</label></a><br><br>';
		echo'<a class="fontchange" href="o171.php" ><label>DESPATCH ROUTE CARD LIST (INDIRECT) [o171]</label></a><br><br>';
	}
	if($_SESSION['access']=="Stores" || $_SESSION['user']=="100")
	{
		echo'<a class="fontchange" href="return.php" ><label>RETURN RAW MATERIAL REPORT [RET]</label></a><br><br>';
	}
	if($_SESSION['access']=="Quality" || $_SESSION['user']=="100" || $_SESSION['user']=="132")
	{
		echo'<a class="fontchange" href="o25tab.php"> <label> REJECTION REPORT  [o25]</label></a><br><br>';
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
			echo'<a class="fontchange1" href="i11.php"><label>ITEM PROCESS MASTER UPDATION [i11]</label></a><br><br>';
		}
		if($_SESSION['user']=="100" || $_SESSION['user']=="123")
		{
			echo'<a class="fontchange" href="ppc8.php" ><label> PPC PLAN [PPC]</label></a><br><br>';
			echo'<a class="fontchange" href="obinv2.php" ><label> OB STATUS [OBINV2]</label></a><br><br>';
			echo'<a class="fontchange" href="kanbanstatus1.php" ><label> KANBAN STATUS</label></a><br><br>';
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
			echo'<a class="fontchange" href="vmiupdate.php" ><label> VMI MASTER UPDATION [VMIU]</label></a><br><br>';
			echo'<a class="fontchange" href="orderbook.php" ><label> ORDERBOOK MASTER UPDATION [OBMU]</label></a><br><br>';
		}
		if($_SESSION['access']=="FI")
		{
			echo'<a class="fontchange1" href="i23.php" ><label>FINAL INSPECTION MASTER INSERTION [ I23 ]</label></a><br><br>';
		}
	?>
	</form>	
</div>
</body>
</html>