<?php
	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	if(!$con)
		die(mysqli_error());
	
	if(isset($_POST['submit']))
		{
			$pn = $_POST['pn'];
			$ct = $_POST['ct'];
			$rm = $_POST['rm'];
			$result = mysqli_query($con,"SELECT DISTINCT operation FROM m11");
			$n=mysqli_num_rows($result);
			mysqli_query($con,"delete from m12 where pnum='$pn'");
			$op="oper";
			for($i=1;$i<=$n;$i++)
			{
				$str=$op.$i;
				if(isset($_POST["$str"]))
				{
					$t=$_POST["$str"];
					mysqli_query($con,"INSERT INTO m12(pnum,operation,cycletime,settingtime) VALUES('$pn','$t','$ct','$rm')");
				}
				
			}
			//mysqli_query($con,"INSERT INTO m12(pnum,operation,cycletime,settingtime) VALUES('$pn','$oper','$ct','$rm')");
			header("location: inputlink.php");
		}
	else
		{
			echo "<script type='text/javascript'>alert('Not Successfully updated');</script>";
		}
		
	mysqli_close($con);
?>