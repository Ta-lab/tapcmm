<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<?php
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if(isset($_POST['submit']))
{
	$dcdate = date("Y-m-d");
	$dcnum = $_POST['dc'];
	$dt="DC-".$dcnum;
	$stkpt = $_POST['operation'];
	$ccode = $_POST['cc'];
	$pnum = $_POST['pn'];
	$dcqty = $_POST['tiqty'];
	$mot = $_POST['mot'];
	
	mysqli_query($con,"INSERT INTO `dc_det` (`dcnum`, `dcdate`, `scn`, `pn`, `mot`, `qty`) VALUES ('$dcnum', '$dcdate', '$ccode', '$pnum', '$mot', '$dcqty');");
	
	header("location: newinv2dc_rc.php?dcdate=$dcdate&stkpt=FG%20For%20Invoicing&pnum=$pnum&dcnum=$dt&dcqty=$dcqty&rem=$dcqty");
	
	/*echo $dcnum;	
	echo $dt;	
	echo $stkpt;	
	echo $ccode;	
	echo $pnum;
	echo $dcqty;
	echo $mot;
	*/
	
	
}


?>