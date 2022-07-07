<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		
		if(isset($_POST['submit']))
			{
				$id = $_POST['idn'];
				$uname = $_POST['name'];
				$psw = $_POST['psw'];
				$type = $_POST['type'];
				$area = $_POST['acc'];
				date_default_timezone_set('Asia/Kolkata');
				$time=date("Y-m-d");
				mysqli_query($con,"INSERT INTO `admin1` (`userid`, `username`, `password`, `temp`, `date`, `access`, `status`, `activity`, `lastact`, `ip`) VALUES ('$id', '$uname', '$psw', '$type', '$time', '$area', '', '', '', '')");
				mysqli_close($con);
				header("location: inputlink.php");
			}
?>