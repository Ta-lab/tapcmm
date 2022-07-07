<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

if(isset($_POST['submit']))
	{
		$cc=$_POST['cc'];
		$cname = $_POST['cname'];
		$cadd1 = $_POST['cadd1'];
		$cadd2 = $_POST['cadd2'];
		$cadd3 = $_POST['cadd3'];
		$cgstno = $_POST['cgstno'];
		$dtname = $_POST['dtname'];
		$dtadd1 = $_POST['dtadd1'];
		$dtadd2 = $_POST['dtadd2'];
		$dtadd3 = $_POST['dtadd3'];
		$dtgstno = $_POST['dtgstno'];
		$vc = $_POST['vc'];
		$tm = $_POST['tm'];
		$dis = $_POST['dis'];
		$ccode = $_POST['ccode'];
		$pan_no = $_POST['pan_no'];
		
		$supplytypecode = $_POST['supplytypecode'];
		$city = $_POST['city'];
		$pincode = $_POST['pincode'];
		$state = $_POST['state'];
		
		mysqli_query($con,"UPDATE `invmaster` SET `cname`='$cname', `cadd1`='$cadd1', `cadd2`='$cadd2', `cadd3`='$cadd3', `cgstno`='$cgstno', `dtname`='$dtname', `dtadd1`='$dtadd1', `dtadd2`='$dtadd2', `dtadd3`='$dtadd3', `dtgstno`='$dtgstno', `vc`='$vc' , `ccode`='$ccode' , `transmode`='$tm' , `distance`='$dis',`supplytypecode`='$supplytypecode',`city`='$city',`pincode`='$pincode',`state`='$state' where ccode='$cc'");
		//echo "UPDATE `invmaster` SET `cname`='$cname', `cadd1`='$cadd1', `cadd2`='$cadd2', `cadd3`='$cadd3', `cgstno`='$cgstno', `dtname`='$dtname', `dtadd1`='$dtadd1', `dtadd2`='$dtadd2', `dtadd3`='$dtadd3', `dtgstno`='$dtgstno', `vc`='$vc' , `ccode`='$ccode' where ccode='$cc'";
	
		//PAN_NO
		$tcs="SELECT * FROM `tcs` where cname='$cname'";
		$result=mysqli_query($con, $tcs);
		$row = mysqli_fetch_array($result);

		if($row['cname']!='')
		{
			mysqli_query($con,"UPDATE `tcs` SET `pan_no`='$pan_no' where cname='$cname'");
		}else
		{
			mysqli_query($con,"INSERT INTO `tcs` (`cname` , `pan_no`) VALUES ('$cname' , '$pan_no')");
		}
		
		
	}
	mysqli_close($con);
	header("location: inputlink.php");
?>