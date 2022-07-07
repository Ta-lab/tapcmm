<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());

if(isset($_POST['submit']))
	{
		echo "UPDATE fi_rcno WHERE ok='T'";
		$rcno=$_POST['rcno'];
		mysqli_query($con,"UPDATE fi_rcno SET ok='T' WHERE rcno='$rcno'");
		$insno=$_POST['insno'];
		$pn = $_POST['pn'];
		$i=0;
		$s="c1";
		while(isset($_POST[$s]))
		{
			$sno=$i+1;
			$c=$_POST["c".$i];
			$d=$_POST["d".$i];
			$m=$_POST["m".$i];
			if(isset($_POST["l".$i]))
			{
				$l=$_POST["l".$i];
				$u=$_POST["u".$i];
				$s1=$_POST["s1".$i];
				$s2=$_POST["s2".$i];
				$s3=$_POST["s3".$i];
				$s4=$_POST["s4".$i];
				$s5=$_POST["s5".$i];
				$ut=$_POST["ut".$i];
				mysqli_query($con,"INSERT INTO `fi_report` (`insno`, `rcno`, `pnum`, `sno`, `chars`, `drawspec`, `method`, `unit` , `lsl`, `usl`, `s1`, `s2`, `s3`, `s4`, `s5`) VALUES ('$insno', '$rcno', '$pn' , '$sno', '$c', '$d', '$m', '$ut', '$l', '$u', '$s1', '$s2', '$s3', '$s4', '$s5')");
			}
			else
			{
				$ta=$_POST["ta".$i];
				mysqli_query($con,"INSERT INTO `fi_report` (`insno`, `rcno`, `pnum`, `sno`, `chars`, `drawspec`, `method`, `textprint`) VALUES ('$insno', '$rcno', '$pn' , '$sno', '$c', '$d', '$m', '$ta')");
			}
			$i++;
			$s="c".$i;
		}
	}
	mysqli_close($con);
	header("location: inputlink.php");
?>