<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		
		if(isset($_POST['submit']))
			{
				$tdate = $_POST['tdate'];
				$stkpt = $_POST['operation'];
				$prcno = $_POST['rcno'];
				$pnum = $_POST['partnumber'];
				$pro = $_POST['pro'];
				$mid = $_POST['mid'];
				mysqli_query($con,"INSERT INTO d14(date,oper,rcno,pnum,pro,mid) VALUES('$tdate','$stkpt','$prcno','$pnum','$pro','$mid')");
				mysqli_close($con);
			
				header("location: inputlink.php");
				
			}
?>