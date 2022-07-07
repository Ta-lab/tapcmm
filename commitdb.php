<?php
	if(isset($_POST['submit']))
	{
		
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		$w = $_POST['w'];$p = $_POST['p'];$fm = $_POST['fm'];$c = $_POST['c'];
		$count=count($w);
		for($i=0;$i<$count;$i++)
		{
			if($w[$i]!="" && $c[$i]!=0)
			{
				mysqli_query($con,"INSERT INTO `commit` (`week`, `pnum`, `foremac`, `qty`) VALUES ('$w[$i]', '$p[$i]', '$fm[$i]', '$c[$i]')");
			}
		}
		header("location: inputlink.php");
	}
?>