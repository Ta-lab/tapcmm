<?php
if(isset($_POST['submit']))
{
	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	if(!$con)
		die(mysqli_error());
	$inv=$_POST['inv'];
	$dc= $_POST['dc'];	
	if(isset($_POST['pw']) && $_POST['pw']=="DELL")
	{
		mysqli_query($con,"UPDATE D19 SET invoice='$inv',dc='$dc'");
	}
	mysqli_close($con);
	header("location: inputlink.php");
}
?>