<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

if(isset($_POST['submit']))
	{
		$cc=$_POST['ccode'];
		$oldcc=$_POST['oldccode'];
		$cname = $_POST['cname'];
		$cadd1 = $_POST['cadd1'];
		$cadd2 = $_POST['cadd2'];
		$cadd3 = $_POST['cadd3'];
		$pn = $_POST['pn'];
		$pd = $_POST['pd'];
		$rmk = $_POST['rmk'];
		$vc = $_POST['vc'];
		$ccode = $_POST['ccode'];
		$issue = $_POST['issue'];
		mysqli_query($con,"UPDATE `invmaster` SET ino='$issue' WHERE ccode='$cc' and pn='$pn'");
		//echo "UPDATE `invmaster` SET ino='$issue' WHERE ccode='$cc' and pn='$pn'";
		//echo "UPDATE `fi_customer_master` SET `ccode`='$ccode',`cname`='$cname',`add1`='$cadd1', `add2`='$cadd2', `add3`='$cadd3',`pcode`='$vc' WHERE CCODE='$oldcc'";
		if($_POST['c1']=="")
		{
			mysqli_query($con,"UPDATE `invmaster` SET firmk='$rmk' WHERE ccode='$cc' and pn='$pn'");
		}
		else
		{
			mysqli_query($con,"UPDATE `invmaster` SET firmk='$rmk' WHERE ccode='$cc' and pn='$pn'");
			mysqli_query($con,"DELETE FROM `fi_detail` where ccode='$cc' and pnum='$pn'");
			$i=1;
			$s="c1";
			while($_POST[$s]!="")
			{
				$c=$_POST["c".$i];
				$d=$_POST["d".$i];
				$m=$_POST["m".$i];
				$l=$_POST["l".$i];
				$u=$_POST["u".$i];
				$ut=$_POST["ut".$i];
				mysqli_query($con,"INSERT INTO `fi_detail` (`ccode`, `pnum`, `s.no`, `chars`, `drawspec`, `method` , `lsl` , `usl` , `unit` ) VALUES ('$cc', '$pn', '$i', '$c', '$d', '$m' , '$l', '$u', '$ut' )");
				$i++;
				$s="c".$i;
			}
		}
	}
	mysqli_close($con);
	header("location: inputlink.php");
?>