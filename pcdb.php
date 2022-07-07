<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

if(isset($_POST['submit']))
	{
		$pnum = $_POST['partnumber'];
		$stkpt = $_POST['stkpt'];
		$ia1 = $_POST['ia1'];
		$ia2 = $_POST['ia2'];
		$ia3 = $_POST['ia3'];
		$as1 = $_POST['as1'];
		$as2 = $_POST['as2'];
		$as3 = $_POST['as3'];
		mysqli_query($con,"DELETE FROM pn_st WHERE invpnum='$pnum' and stkpt='$stkpt'");
		if($ia1!="")
		{
			mysqli_query($con,"INSERT INTO pn_st(stkpt,pnum,invpnum,n_iter) VALUES('$stkpt','$ia1','$pnum','$as1')");
		}
		if($ia2!="")
		{
			mysqli_query($con,"INSERT INTO pn_st(stkpt,pnum,invpnum,n_iter) VALUES('$stkpt','$ia2','$pnum','$as2')");
		}
		if($ia3!="")
		{
			mysqli_query($con,"INSERT INTO pn_st(stkpt,pnum,invpnum,n_iter) VALUES('$stkpt','$ia3','$pnum','$as3')");
		}
		if($ia1=="" && $ia2=="" && $ia3=="")
		{
			mysqli_query($con,"DELETE FROM pn_st WHERE invpnum='$pnum' and stkpt='$stkpt'");
		}
		mysqli_close($con);
		header("location: inputlink.php");
	}
?>