<?php
			if(isset($_POST['submit']))
			{
				$con=mysqli_connect('localhost','root','Tamil','storedb');
				if(!$con)
					die(mysqli_error());
				$date=$_POST['tdate'];
				$partnumber= $_POST['partnumber'];	
				$gnum=$_POST['gnum'];	
				$partdesc=$_POST['partdesc'];
				$ponum=$_POST['ponum'];
				$dcnum=$_POST['dcnum'];	
				$dcdate=$_POST['dcdate'];	
				$tcnum=$_POST['tcnum'];	
				$sname=$_POST['sname'];
				$invnum=$_POST['invnum'];
				$invdate=$_POST['invdate'];
				$uom=$_POST['uom'];
				$qty=$_POST['qty'];
				$heat = $_POST['h'];
				$count=count($heat);
				for($i=0;$i<$count-1;$i++)
				{
					mysqli_query($con,"INSERT INTO heatnumber(`grnnum`, `heatnum`) VALUES ('$gnum','$heat[$i]')");
				}
				mysqli_query($con,"INSERT INTO receipt (`grnnum`, `date`, `part_number`, `rmdesc`, `ponum`, `sname`, `dc_number`, `dc_date`, `inv_no`, `inv_date`, `tcno`, `uom`, `quantity_received`) VALUES ('$gnum','$date','$partnumber','$partdesc','$ponum','$sname','$dcnum','$dcdate','$invnum','$invdate','$tcnum','uom','$qty')");
				mysqli_close($con);
				header("location: inputlink.php");
			}
?>