<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

if(isset($_POST['submit']))
{
	$id = $_POST['idn'];
	$uname = $_POST['name'];
	$acc = $_POST['acc'];
	$stat = $_POST['stat'];
	$npsw = $_POST['npsw'];
	if($npsw!='')
	{
		mysqli_query($con,"UPDATE admin1 set username='$uname',access='$acc',password='$npsw',status='$stat' WHERE userid='$id'");
	}
	else
	{
		mysqli_query($con,"UPDATE admin1 set username='$uname',access='$acc',status='$stat' WHERE userid='$id'");
	}
	session_start();
	if($id==$_SESSION['user'])
	{
		mysqli_query($con,"UPDATE admin1 set status='0' WHERE userid='$id'");
		unset($_SESSION['user']);
		unset($_SESSION['username']);
		unset($_SESSION['access']);
		unset($_SESSION['ip']);
		session_destroy();
	}
	header("location: index.php?err1=5");
}
else
{
	session_start();
	unset($_SESSION['user']);
	unset($_SESSION['username']);
	unset($_SESSION['access']);
	unset($_SESSION['ip']);
	session_destroy();
	mysqli_query($con,"UPDATE admin1 set status='0' where userid='$id'");
	header("location: index.php?err1=2");
}
mysqli_close($con);
?>