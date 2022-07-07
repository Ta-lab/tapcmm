<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
$invno=$_GET['invno'];
$iter=$_GET['iter'];
$result = $con->query("SELECT * FROM d12 WHERE rcno='$invno'");
$result1 = $con->query("SELECT sum(partissued) as tq FROM d12 WHERE rcno='$invno'");
?>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-1sci{background-color:#333333;color:#ffffff;text-align:center;vertical-align:top}
.tg .tg-baqh{text-align:center;vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}</style>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<br><br><br><br><br><br><div class="tg-wrap"><table class="tg">
  <tr>
    <th class="tg-1sci" colspan="6">DC SLIP</th>
  </tr>
  <tr>
    <td class="tg-baqh">INVOICE #</td>
    <td class="tg-baqh">DATE OF INV</td>
    <td class="tg-baqh">PART NUMBER</td>
    <td class="tg-baqh">QUANTITY</td>
    <td class="tg-baqh">DC NUMBER</td>
    <td class="tg-baqh">QUANTITY</td>
  </tr>
<?php
	$row = mysqli_fetch_row($result);
	$row1 = mysqli_fetch_row($result1);
	echo'<tr>
    <td class="tg-baqh">'.$row[7].'</td>
    <td class="tg-baqh">'.$row[1].'</td>
    <td class="tg-baqh">'.$row[6].'</td>
    <td class="tg-baqh">'.$row1[0]/$iter.'</td>
    <td class="tg-baqh">'.$row[8].'</td>
    <td class="tg-baqh">'.$row[9]/$iter.'</td>
  </tr>';
  while($row = mysqli_fetch_row($result))
  {
	  echo'<tr>
    <td class="tg-baqh">'.$row[7].'</td>
    <td class="tg-baqh">'.$row[1].'</td>
    <td class="tg-baqh">'.$row[6].'</td>
    <td class="tg-baqh">'.$row1[0]/$iter.'</td>
    <td class="tg-baqh">'.$row[8].'</td>
    <td class="tg-baqh">'.$row[9]/$iter.'</td>
  </tr>';
  }
?>
</table></div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('cfm').style.visibility = 'hidden';
        }
}
</script>
<input type="button" id="cfm" value="PRINT DC SLIP" onclick="myFunction();<?php
//echo "javascript:window.print();";
$result1 = $con->query("SELECT * from invprinter");
	$row = mysqli_fetch_array($result1);
	if($row['popt']=="0")
	{
		mysqli_query($con,"UPDATE inv_det set ok='T' where invno='$invno'");
		mysqli_query($con,"UPDATE inv_correction SET status='T' WHERE invno='$invno'");
		echo "window.location='printphp.php?invno=$invno&n=0'";
	}
	else
	{
		mysqli_query($con,"UPDATE inv_det set ok='T' where invno='$invno'");
		mysqli_query($con,"UPDATE inv_correction SET status='T' WHERE invno='$invno'");
		echo "window.location='inputlink.php'";
	} 
?>">