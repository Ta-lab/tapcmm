<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		
		if(isset($_POST['submit']))
			{
				$tdate = date("Y-m-d", strtotime($_POST['tdate']));
				$stkpt = $_POST['operation'];
				$pnum = $_POST['partnumber'];
				$rcpt = $_POST['rcpt'];
				mysqli_query($con,"INSERT INTO d12 (date,stkpt,pnum,prcno,partreceived) VALUES ('$tdate','$stkpt','$pnum','$pnum','$rcpt')");
				echo "<script type='text/javascript'>alert('Successfully updated');</script>";
			}
			header("location: inputlink.php");
			if(!isset($_POST['submit']))
				echo "<script type='text/javascript'>alert('Not Successfully updated');</script>";
		mysqli_close($con);
?>
