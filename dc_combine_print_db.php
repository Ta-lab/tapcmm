<?php
	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	if(!$con)
		die(mysqli_error());
	
	if(isset($_POST['submit']))
		{
			$dc_sno = $_POST['dc_sno'];
			$dcnum = $_POST['dcnum'];
			
			for($i=0;$i<count($dcnum);$i++)
			{
				//mysqli_query($con,"INSERT INTO dc_print(dc_sno,dcnum) VALUES('$dc_sno','$t','$ct','$rm')");
				mysqli_query($con,"INSERT INTO dc_print(dc_sno,dcnum,print_status) VALUES('$dc_sno','$dcnum[$i]','T')");
				
			}
			
			header("location: dc_combine_print_pdf.php?dc_sno=".$dc_sno."");
			//header("location: ../fpdf/digprint/invoice_pdf_auto.php?invoice_number=".$invoice_number."");
			//header("location: inputlink.php");
		}
	else
		{
			echo "<script type='text/javascript'>alert('Not Successfully updated');</script>";
		}
		
	mysqli_close($con);
?>