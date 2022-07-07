<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		
		if(isset($_POST['submit']))
			{
				$p = $_POST['partnumber'];
				$cc = $_POST['ccode'];
				$cname = $_POST['cname'];
				$cadd1 = $_POST['cadd1'];
				$cadd2 = $_POST['cadd2'];
				$cadd3 = $_POST['cadd3'];
				$cgstno = $_POST['cgstno'];
				$dtname = $_POST['dtname'];
				$dtadd1 = $_POST['dtadd1'];
				$dtadd2 = $_POST['dtadd2'];
				$dtadd3 = $_POST['dtadd3'];
				$dtgstno = $_POST['dtgstno'];
				$pd = $_POST['pd'];
				$vc = $_POST['vc'];
				$hsnc = $_POST['hsnc'];
				$cpono = $_POST['cpono'];
				$cpodt = $_POST['cpodt'];
				$poino = $_POST['poino'];
				$rate = $_POST['rate'];
				$per = $_POST['per'];
				$uom = $_POST['uom'];
				$pc = $_POST['pc'];
				$cgst = $_POST['cgst'];
				$sgst = $_POST['sgst'];
				$igst = $_POST['igst'];
				$ccode = $_POST['ccode'];
				$r1 = $_POST['r1'];
				$r2 = $_POST['r2'];
				$r3 = $_POST['r3'];
				$r4 = $_POST['r4'];
				$r5 = $_POST['r5'];
				$dm = $_POST['dm'];
				$tm = $_POST['tm'];
				$dis = $_POST['dis'];
				mysqli_query($con,"UPDATE `invmaster` SET `cname`='$cname', `cadd1`='$cadd1', `cadd2`='$cadd2', `cadd3`='$cadd3', `cgstno`='$cgstno', `dtname`='$dtname', `dtadd1`='$dtadd1', `dtadd2`='$dtadd2', `dtadd3`='$dtadd3', `dtgstno`='$dtgstno', `cpono`='$cpono', `cpodt`='$cpodt', `vc`='$vc', `pd`='$pd', `hsnc`='$hsnc', `poino`='$poino', `rate`='$rate', `per`='$per', `uom`='$uom', `remark1`='$r1', `remark2`='$r2', `remark3`='$r3', `remark4`='$r4', `remark5`='$r5', `ccode`='$ccode', `despatch`='$dm', `pc`='$pc', `cgst`='$cgst', `igst`='$igst', `sgst`='$sgst' , `transmode`= '$tm', `distance`= '$dis' where pn='$p' and ccode='$cc'");
				//echo "UPDATE `invmaster` SET `cname`='$cname', `cadd1`='$cadd1', `cadd2`='$cadd2', `cadd3`='$cadd3', `cgstno`='$cgstno', `dtname`='$dtname', `dtadd1`='$dtadd1', `dtadd2`='$dtadd2', `dtadd3`='$dtadd3', `dtgstno`='$dtgstno', `cpono`='$cpono', `cpodt`='$cpodt', `vc`='$vc', `pd`='$pd', `hsnc`='$hsnc', `poino`='$poino', `rate`='$rate', `per`='$per', `uom`='$uom', `remark1`='$r1', `remark2`='$r2', `remark3`='$r3', `remark4`='$r4', `remark5`='$r5', `ccode`='$ccode', `despatch`='$dm', `pc`='$pc', `cgst`='$cgst', `igst`='$igst', `sgst`='$sgst' where pn='$p' and ccode='$cc'";
			}
			if(isset($_POST['sub']))
			{
				$p = $_POST['pn'];
				$cname = $_POST['cname'];
				$cadd1 = $_POST['cadd1'];
				$cadd2 = $_POST['cadd2'];
				$cadd3 = $_POST['cadd3'];
				$cgstno = $_POST['cgstno'];
				$dtname = $_POST['dtname'];
				$dtadd1 = $_POST['dtadd1'];
				$dtadd2 = $_POST['dtadd2'];
				$dtadd3 = $_POST['dtadd3'];
				$dtgstno = $_POST['dtgstno'];
				$pd = $_POST['pd'];
				$vc = $_POST['vc'];
				$hsnc = $_POST['hsnc'];
				$cpono = $_POST['cpono'];
				$cpodt = $_POST['cpodt'];
				$poino = $_POST['poino'];
				$rate = $_POST['rate'];
				$per = $_POST['per'];
				$uom = $_POST['uom'];
				$pc = $_POST['pc'];
				$cgst = $_POST['cgst'];
				$sgst = $_POST['sgst'];
				$igst = $_POST['igst'];
				$ccode = $_POST['ccode'];
				$r1 = $_POST['r1'];
				$r2 = $_POST['r2'];
				$r3 = $_POST['r3'];
				$r4 = $_POST['r4'];
				$r5 = $_POST['r5'];
				$dm = $_POST['dm'];
				$tm = $_POST['tm'];
				$dis = $_POST['dis'];
				mysqli_query($con,"INSERT INTO `invmaster` (`cname`, `cadd1`, `cadd2`, `cadd3`, `cgstno`, `transmode` , `distance` , `dtname`, `dtadd1`, `dtadd2`, `dtadd3`, `dtgstno`, `cpono`, `cpodt`, `vc`, `pn`, `pd`, `hsnc`, `poino`, `rate`, `per`, `uom`,`remark1`,`remark2`,`remark3`,`remark4`,`remark5`,`despatch`,`ccode`, `pc`, `cgst`, `igst`, `sgst`) VALUES ('$cname', '$cadd1', '$cadd2', '$cadd3', '$cgstno', '$tm' , '$dis' , '$dtname', '$dtadd1', '$dtadd2', '$dtadd3', '$dtgstno', '$cpono', '$cpodt', '$vc', '$p', '$pd', '$hsnc', '$poino', '$rate', '$per', '$uom','$r1','$r2','$r3','$r4','$r5','$dm','$ccode', '$pc', '$cgst', '$igst', '$sgst');");
				//echo "INSERT INTO `invmaster` (`cname`, `cadd1`, `cadd2`, `cadd3`, `cgstno`, `dtname`, `dtadd1`, `dtadd2`, `dtadd3`, `dtgstno`, `cpono`, `cpodt`, `vc`, `pn`, `pd`, `hsnc`, `poino`, `rate`, `per`, `uom`,`remark1`,`remark2`,`remark3`,`remark4`,`remark5`,`despatch`,`ccode`, `pc`, `cgst`, `igst`, `sgst`) VALUES ('$cname', '$cadd1', '$cadd2', '$cadd3', '$cgstno', '$dtname', '$dtadd1', '$dtadd2', '$dtadd3', '$dtgstno', '$cpono', '$cpodt', '$vc', '$p', '$pd', '$hsnc', '$poino', '$rate', '$per', '$uom','$r1','$r2','$r3','$r4','$r5','$dm','$ccode', '$pc', '$cgst', '$igst', '$sgst');";
			}
			mysqli_close($con);
			header("location: newbom.php?part=$p");
?>