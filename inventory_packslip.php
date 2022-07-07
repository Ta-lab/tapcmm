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
if(isset($_POST['submit']))
{
		
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		$box=$_POST['box'];
		$inv=$_POST['from'];
		$batch=$_POST['bat'];
		$result = mysqli_query($con,"SELECT invno,invdt,pn,pd,ccode,vc,qty FROM `inv_det` WHERE invno='$inv'");
		$r=$result->num_rows;
		$row = mysqli_fetch_array($result);
		$invdt=$row['invdt'];
		$invno=$row['invno'];
		$pn=$row['pn'];
		$pd=$row['pd'];
		$ccode=$row['ccode'];
		$vc=$row['vc'];
		//$qty=$row['qty'];
		$qty=$_POST['in'];
		//$bqty=$qty/$box;
		$bqty=$_POST['qty'];
		$result1 = mysqli_query($con,"SELECT ino FROM `invmaster` where ccode='$ccode' and pn='$pn'");
		$r=$result1->num_rows;
		$row1 = mysqli_fetch_array($result1);
		$iss=$row1['ino'];
		
		include('phpqrcode/qrlib.php'); 
		$folder="images/";
		$file_name="qr.png";
		$file_name=$folder.$file_name;
		QRcode::png($invno,$qty,$pn,$file_name);
		//echo"<img src='images/qr.png'>";
		 
		 
		for($i=1;$i<=$box;$i++)
		{
		
			echo '<div class="tg-wrap"><table class="tg">
				<tr>
					<!--<th class="tg-bn4o" colspan="12"></th>-->
				</tr>
				<tr>
					<!--<th class="tg-bn4o" colspan="12"></th>-->
				</tr>
				<tr>
					<!--<th class="tg-bn4o" colspan="12"></th>-->
				</tr>
				<tr>
					<!--<td class="tg-3b15" colspan="2" rowspan="2"><img src="img/logo1.png" alt="Mountain View" style="width:100%;height:60%;"></img></td>-->
					<tr>
						<td class="tg-bn4o" colspan="12"></td>
					</tr>
					<tr>
						<td class="tg-bn4o" colspan="12"></td>
						
					</tr>
					
					
					<td class="tg-bn4o" colspan="6" style="font-size:8px;">VENKATESWARA STEELS AND SPRINGS (INDIA) PVT LTD</td>
				</tr>
				  
				<tr>
					<td class="tg-9hbo" colspan="2" style="font-size:8px;"> Supplier Code : '.$vc.'</td>
					<td class="tg-9hbo" colspan="2" style="font-size:8px;"> Part Number : '.$pn.'</td>
				</tr>
				<tr>
					<td class="tg-9hbo" colspan="2" style="font-size:8px;"> Invoice Number : '.$invno.'</td>
					<td class="tg-9hbo" colspan="2" style="font-size:8px;"> Invoice Qty : '.$qty.'</td>
					
				</tr>
				<tr>
					<td class="tg-9hbo" colspan="2" style="font-size:8px;"> Box Qty : '.$bqty.'</td>
					<td class="tg-9hbo" colspan="2" style="font-size:8px;"> No of Box / Cover : '.$i.' / '.$box.'</td>
				</tr>
				<tr>
					<td class="tg-9hbo" colspan="2" style="font-size:8px;"> Mfg.Date : '.$invdt.'</td>
					<td class="tg-9hbo" colspan="2" style="font-size:8px;"> Batch ID : '.$batch.'</td>
				</tr>
				<tr>
					<!--<td class="tg-yw4l" rowspan="3"><img src="images/qr.png"></td>-->
					<!--<td class="tg-bn4o" colspan="12"></td>-->
					
				</tr>
				<tr>
					<!--<td class="tg-9hbo" colspan="2"> Invoice Number : '.$invno.'</td>-->
				</tr>
				<tr>
					<!--<td class="tg-9hbo" colspan="2" > Invoice Qty : '.$qty.'</td>-->
				</tr>
				<tr>
					<!--<td class="tg-9hbo" colspan="2" > Box Qty : '.$bqty.'</td>-->
				</tr>
				<tr>
					<!--<td class="tg-9hbo" colspan="2" > No of Box / Cover : '.$i.' / '.$box.'</td>-->
				</tr>
				<tr>
					<!--<td class="tg-9hbo" colspan="2" > Mfg.Date : '.$invdt.'</td>-->
				</tr>
				<tr>
					
					<!--<td class="tg-9hbo" colspan="2" > Batch ID : '.$batch.'</td>-->
					
				</tr>
				
				
			  <tr>
				<!--<td class="tg-yw4l" colspan="12" rowspan="2"><br> <br><br> <br>.</td>-->
			  </tr>
			  <tr>
			  </tr>
			</table></div>';
			
		}		
	
	}
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