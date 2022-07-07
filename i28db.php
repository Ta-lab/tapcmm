<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

if(isset($_POST['submit']) || isset($_POST['sub']))
	{
		$sccode=$_POST['sccode'];
		$scn = $_POST['scn'];
		$sca1 = $_POST['sca1'];
		$sca2 = $_POST['sca2'];
		$sca3 = $_POST['sca3'];
		$gst = $_POST['gst'];
		$sp = $_POST['sp'];
		$pn = $_POST['pn'];
		$hsnc = $_POST['hsnc'];
		$pd = $_POST['pd'];
		$od = $_POST['od'];
		$uom = $_POST['uom'];
		$state = $_POST['state'];
		$sc = $_POST['sc'];
		$dm = $_POST['dm'];
		
		$cgst = $_POST['cgst'];
		$sgst = $_POST['sgst'];
		$igst = $_POST['igst'];
		
		if(isset($_POST['submit']))
		{
			mysqli_query($con,"DELETE FROM dcmaster WHERE sccode='$sccode' AND sp='$sp' AND pn='$pn'");
			mysqli_query($con,"INSERT INTO `dcmaster` (`sccode`, `pn`, `pd`, `sp`, `scn`, `sca1`, `sca2`, `sca3`, `state`, `sc`, `gst`, `operdesc`, `uom`, `hsnc`, `mot`, `cgst`, `sgst`, `igst`) VALUES ('$sccode', '$pn', '$pd', '$sp', '$scn', '$sca1', '$sca2', '$sca3', '$state', '$sc', '$gst', '$od', '$uom', '$hsnc', '$dm', '$cgst', '$sgst', '$igst');");
		}
		else
		{
			if($pn=="")
			{
				mysqli_query($con,"UPDATE `dcmaster` SET `scn`='$scn', `sca1`='$sca1', `sca2`='$sca2', `sca3`='$sca3', `state`='$state', `sc`='$sc', `gst`='$gst', `mot`='$dm' where sccode='$sccode'");
				//echo "UPDATE `dcmaster` SET `scn`='$scn', `sca1`='$sca1', `sca2`='$sca2', `sca3`='$sca3', `state`='$state', `sc`='$sc', `gst`='$gst', `mot`='$dm' where sccode='$sccode'";
			}
			else
			{
				mysqli_query($con,"UPDATE `dcmaster` SET `pd`='$pd', `operdesc`='$od', `uom`='$uom', `hsnc`='$hsnc' where sccode='$sccode' and pn='$pn'");
				//echo "UPDATE `dcmaster` SET `pd`='$pd', `operdesc`='$operdesc', `uom`='$uom', `hsnc`='$hsnc' where sccode='$sccode' and pn='$pn'";
			}
		}
	}
	mysqli_close($con);
	header("location: inputlink.php");
?>