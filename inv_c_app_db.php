<?php
session_start();
$u=$_SESSION['username'];
$ip=$_SESSION['ip'];
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
if(isset($_POST['submit']))
{
	header("location: inputlink.php");
}
if(isset($_GET['invno']) && $_GET['invno']!="")
{
	$invno = $_GET['invno'];
	$stat = $_GET['stat'];
	echo $stat;
	if($stat==0)
	{
		mysqli_query($con,"UPDATE inv_correction SET apby='',status='F' where invno='$invno'");
	}
	else
	{
		mysqli_query($con,"UPDATE inv_correction SET apby='$u',status='F' where invno='$invno'");
		if(strlen($invno)>8)
		{
			mysqli_query($con,"DELETE from d12 where rcno='$invno'");
			mysqli_query($con,"DELETE from d11 where rcno='$invno'");
			if(isset($_GET['ret']))
			{
				mysqli_query($con,"UPDATE inv_correction SET status='T' WHERE invno='$invno'");
			}
		}
	}
	header("location: inv_c_app.php");
}
?>