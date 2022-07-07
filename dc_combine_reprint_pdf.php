<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:16px;font-weight:normal;padding:1px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-baqh{text-align:center;vertical-align:top}
.tg .tg-bn4o{font-weight:bold;font-size:14px;text-align:center;vertical-align:top}
.tg .tg-3b15{background-color:#010066;vertical-align:top}
.tg .tg-amwm{font-weight:bold;text-align:center;vertical-align:top}
.tg .tg-l2oz{font-weight:bold;text-align:right;vertical-align:top}
.tg .tg-9hbo{font-weight:bold;vertical-align:top}
.tg .tg-yw4l{vertical-align:top}
.tg .tg-ujoh{font-weight:bold;font-size:22px;text-align:center;vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}</style>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<?php
$rate=0;$per=1;
require "numtostr.php";
//if(isset($_POST['submit']))
//{
	$dc_sno = $_POST['dc_sno'];
	echo $dc_sno;
	//$dc=$_POST['dc'];
	$con = mysqli_connect('localhost','root','Tamil','mypcm');
	/*$q = "select distinct scn from dc_det where dcnum='$dc'";
	$r = $con->query($q);
	if($r === FALSE) { 
		$n1=0;
		echo "hi";
	}
	else
	{
		$n1 = mysqli_num_rows($r);
		
	}
	if($n1==0)
	{
		echo "<script>
		alert('NO DC RAISED ON THIS NUMBER');
		
		</script>";
		
	}*/
	//else if($n1==1)
	
		
		/*$query2 = "SELECT * FROM dc_print where dc_sno='$dc_sno'";
		$result2 = $con->query($query2);
		while($row2 = mysqli_fetch_array($result2)){
			$dc = $row2['dcnum'];
		}
		*/
		
		
		//$query = "SELECT * FROM dc_det where dcnum='$dc'";
		$query = "SELECT * FROM(SELECT * FROM `dc_print` WHERE dc_sno='$dc_sno') AS DCPRINT LEFT JOIN(SELECT * FROM dc_det) AS TDCDET ON DCPRINT.dcnum=TDCDET.dcnum";
		$result = $con->query($query);
		$row = mysqli_fetch_array($result);
		$t1=$row['pn'];
		$t2=$row['scn'];
		//$query1 = "SELECT  DISTINCT rate,per FROM `invmaster` WHERE pn IN (SELECT DISTINCT invpnum FROM pn_st WHERE pnum='$t1' OR invpnum='$t1')";
		/*$query1 = "SELECT * FROM dc_det where dcnum='$dc'";
		//$query1 = "SELECT  DISTINCT rate,per FROM `invmaster` WHERE pn IN (SELECT DISTINCT invpnum FROM pn_st WHERE pnum='$t1')";
		//echo "SELECT  DISTINCT rate,per FROM `invmaster` WHERE pn IN (SELECT DISTINCT invpnum FROM pn_st WHERE pnum='$t1')";
		$result1 = $con->query($query1);
		$row1 = mysqli_fetch_array($result1);
		//$rate=$row1['rate'];
		//$per=$row1['per'];
		*/
		
		$query1 = "SELECT * FROM dcmaster where pn='$t1' and sccode='$t2'";
		$result1 = $con->query($query1);
		$row1 = mysqli_fetch_array($result1);
		$type="";
		
		session_start();
		if($_SESSION['access']=="ACCOUNTS")
		{
			$type=" ( ACCOUNTS COPY )";
		}
		date_default_timezone_set("Asia/Kolkata");
		echo '<div class="tg-wrap"><table class="tg">
			  <tr>
				<th class="tg-bn4o" colspan="14">GST DELIVERY CHALLAN '.$type.'</th>
			  </tr>
			  <tr>
				<td class="tg-3b15" colspan="2" rowspan="3"><img src="img/logo1.png" alt="Mountain View" style="width:100%;height:60%;"></img></td>
				<td class="tg-bn4o" colspan="12">VENKATESWARA STEELS AND SPRINGS (INDIA) PVT LTD</td>
			  </tr>
			  <tr>
				<td class="tg-amwm" colspan="12">1/89 Ravuthur Pirivu, Kannampalayam.</td>
			  </tr>
			  <tr>
				<td class="tg-amwm" colspan="12">Sulur Coimbatore 641 402 Ph.No. 0422 2680840 , 9659877955</td>
			  </tr>
			  <tr>
				<td class="tg-amwm" colspan="2">GSTIN: 33AACCV3065F1ZL</td>
				<td class="tg-l2oz" colspan="2">MODE OF TRANSPORT</td>
				<td class="tg-9hbo" colspan="12">'.$row['mot'].'</td>
			  </tr>
			  <tr>
				<td class="tg-amwm" colspan="2">DC NUMBER</td>
				<td class="tg-l2oz" colspan="2">VEHICLE NUMBER</td>
				<td class="tg-yw4l" colspan="12">'.$row['vehiclenumber'].'</td>
			  </tr>
			  <tr>
				<td class="tg-ujoh" colspan="2">'.'DCU1-'.$dc_sno.'</td>
				<td class="tg-l2oz" colspan="2">DATE AND TIME OF SUPPLY</td>
				<td class="tg-yw4l" colspan="12">'.date('d-m-Y  # H:i').'</td>
			  </tr>
			  <tr>
				<td class="tg-amwm">DATE:</td>
				<td class="tg-baqh">'.date('d-m-Y').'</td>
				<td class="tg-l2oz" colspan="2">PLACE OF SUPPLY</td>
				<td class="tg-yw4l" colspan="12">'.$row1['scn'].'</td>
			  </tr>
			  <tr>
				<td class="tg-bn4o" colspan="14">DETAILS OF THE RECEIVER</td>
			  </tr>
			  <tr>
				<td class="tg-9hbo" colspan="4">Name:<br>Address:<br><br>State                                          :<br>state code                                :<br>GSTUnique ID                          :</td>
				<td class="tg-yw4l" colspan="12">'.$row['scn'].'<br>'.$row1['sca1'].$row1['sca2'].$row1['sca3'].'<br><br>'.$row1['state'].'<br>'.$row1['sc'].'<br>'.$row1['gst'].'</td>
			  </tr>
			  <tr>
				<td class="tg-amwm">S.NO</td>
				<td class="tg-amwm">DC NO</td>
				<td class="tg-amwm">ITEM DESCRIPTION</td>
				<td class="tg-amwm">HSN Code</td>
				<td class="tg-amwm">QUANTITY</td>
				<td class="tg-amwm">UNIT</td>
				<td class="tg-amwm">RATE / PER</td>
				<td class="tg-amwm">BASIC (Rs.)</td>
				<td class="tg-amwm">CGST (Rs.)</td>
				<td class="tg-amwm">SGST (Rs.)</td>
				<td class="tg-amwm">IGST (Rs.)</td>
				<td class="tg-amwm">TOTAL (Rs.)</td>
				<td class="tg-amwm">WEIGHT (Kgs)</td>
				<td class="tg-amwm">Remarks</td>
			  </tr>';
			
		
		//$query = "SELECT * FROM dc_det where dcnum='$dc'";
		$query = "SELECT * FROM(SELECT * FROM `dc_print` WHERE dc_sno='$dc_sno') AS DCPRINT LEFT JOIN(SELECT * FROM dc_det) AS TDCDET ON DCPRINT.dcnum=TDCDET.dcnum";
		$result = $con->query($query);
		$s=0;
		$sum=0;
		$sum_qty=0;
		while($row = mysqli_fetch_array($result))
		{
			$s=$s+1;
			$t1=$row['pn'];
			$t2=$row['scn'];
			$dcnum = $row['dcnum'];
			$query1 = "SELECT * FROM dcmaster where pn='$t1' and sccode='$t2'";
			//$query1 = "SELECT * FROM dc_det where dcnum='$dcnum'";
			$result1 = $con->query($query1);
			$row1 = mysqli_fetch_array($result1);
			$sp = $row1['sp'];
			
			
			$query3 = "SELECT * FROM dc_det where dcnum='$dcnum'";
			$result3 = $con->query($query3);
			$row3 = mysqli_fetch_array($result3);
			$rate = $row3['rate'];
			$per = $row3['per'];
			
			
			if($sp=="FG For S/C"){
				$rate_p = $rate*90/100; 
			}
			else{
				$rate_p = $rate*70/100; 
			}
			echo '<tr>
				<td class="tg-baqh">'.$s.'</td>
				<td class="tg-baqh">'.$row['dcnum'].'</td>
				<td class="tg-baqh">'.$row['pn'].$row1['pd'].'</td>
				<td class="tg-baqh">'.$row1['hsnc'].'</td>
				<td class="tg-baqh">'.$row['qty'].'</td>
				<td class="tg-baqh">Nos</td>
				<td class="tg-baqh">'.$rate_p.' / '.$per.'</td>
				<td class="tg-baqh">'.$row['basicvalue'].'</td>
				<td class="tg-baqh">'.$row['cgstamount'].'</td>
				<td class="tg-baqh">'.$row['sgstamount'].'</td>
				<td class="tg-baqh">'.$row['igstamount'].'</td>
				<td class="tg-baqh">'.$row['totalvalue'].'</td>
				<td class="tg-baqh">'.$row['weight'].'</td>
				<td class="tg-baqh">'.$row['remarks'].'</td>
			  </tr>';
			//$sum=$sum+($row['qty']*$rate)/$per;
			$sum=$sum+$row['totalvalue'];
			
			$sum_qty=$sum_qty+$row['qty'];
			
		}
		while($s<10)
		{
			$s=$s+1;
			echo'<tr>
			<td class="tg-baqh">'.$s.'</td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
			<td class="tg-baqh"></td>
		  </tr>';
		}
		$whole = floor($sum);
		$fraction = $sum - $whole;
		$fraction=$fraction*100;
		$rup=ROUND($fraction);
		$pai=floor($sum);
		if(strtoupper(numtostrfn($rup)==""))
		{
			$a=strtoupper(numtostrfn($pai))."ONLY";
			$amtstr=$a;
		}
		else
		{
			$a=strtoupper(numtostrfn($pai))."AND PAISE ".strtoupper(numtostrfn($rup)."ONLY");
		}
		
		echo '<tr>
			<td class="tg-amwm" colspan="4">TOTAL</td>
			<td class="tg-amwm">'.$sum_qty.'</td>
			<td class="tg-amwm" colspan="6"></td>
			
			<td class="tg-amwm">'.$sum.'</td>
			
			<td class="tg-amwm" colspan="4"></td>
			
		  </tr>
		  <tr>
			<td class="tg-amwm" colspan="14">TOTAL VALUE IN WORDS</td>
		  </tr>
		  <tr>
			<td class="tg-yw4l" colspan="14">'.$a.'</td>
		  </tr>
		   <tr>
			<td class="tg-9hbo" colspan="8">Purpose : meterial sent for '.$row1['operdesc'].' work only</td>
			<td class="tg-9hbo" colspan="8">For Venkateswara Steels &amp; Springs India Pvt Ltd            </td>
		  </tr>
		  <tr>
			<td class="tg-yw4l" colspan="14" rowspan="2"><br>RECEIVED,THE ABOVE GOODS<br><br>CUSTOMER SIGNATURE<br>.</td>
		  </tr>
		  <tr>
		  </tr>
		</table></div>';
				
				
				
				
		
	//}

	/*else
	{
		echo "<script>
		alert('SAME DC FOR MAORE THAN ONE CUSTOMER');
		window.location.href='dcabort.php?dc=$dc';
		</script>";
	}*/
//}//<input type="button" name="but" id="but" value="DELETE DC" onclick="<?php echo "window.location='dcabort.php?dc=$dc'";
?>
	
	<input type="button" name="submit" id="submit" value="PRINT" onclick="myFunction();javascript:window.print(); <?php echo "window.location='inputlink.php'";?>"><script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
		
}
</script>