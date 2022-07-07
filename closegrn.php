<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="ALL" || $_SESSION['access']=="Stores")
	{
		if(isset($_GET['grn']) && $_GET['grn']!="")
		{
			$con=mysqli_connect('localhost','root','Tamil','storedb');
			$date = date("Y-m-d");
			$grn=$_GET['grn'];
			mysqli_query($con,"UPDATE `receipt` set closed='$date' where grnnum='$grn'");
			header("location: o23tab.php");
		}
		else
		{
			echo "<script type='text/javascript'>alert('Not Successfully updated');</script>";
		}
	}
}
?>