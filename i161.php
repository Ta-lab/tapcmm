<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		$tdate = $_GET['tdate'];
		$type = $_GET['type'];
		$rmdesc = $_GET['rmdesc'];
		$inum = $_GET['inum'];
		$idate = $_GET['idate'];
		$gnum = $_GET['gnum'];
		$gdate = $_GET['gdate'];
		$snam = $_GET['snam'];
		$rnum = $_GET['rnum'];
		$mnam = $_GET['mnam'];
		echo $tdate;
		echo $type;
		echo $rmdesc;
		echo $inum;
		echo $idate;
		echo $gnum;
		echo $gdate;
		echo $snam;
		echo $rnum;
		echo $mnam;
		if($type=='wire')
		{
			$hno = $_GET['hno'];
			$lno = $_GET['lno'];
			$cno = $_GET['cno'];
			mysqli_query($con,"INSERT INTO d16(date,type,rmdesc,inum,idate,hno,lno,cno) VALUES('$tdate','$type','$rmdesc','$inum','$idate','$hno','$lno','$cno')");
			mysqli_query($con,"INSERT INTO `d15` (`invno`,`invdate`, `rmtype`, `ginno`, `gindate`, `sname`, `rnum`, `manuf`) VALUES ('$inum','$idate', '$rmdesc', '$gnum', '$gdate', '$snam', '$rnum', '$mnam');");
		}
		else
		{
			mysqli_query($con,"INSERT INTO d16(date,type,rmdesc,inum,idate,hno,lno,cno) VALUES('$tdate','$type','$rmdesc','$inum','$idate','0','0','0')");
			mysqli_query($con,"INSERT INTO `d15` (`invno`,`invdate`, `rmtype`, `ginno`, `gindate`, `sname`, `rnum`, `manuf`) VALUES ('$inum','$idate', '$rmdesc', '$gnum', '$gdate', '$snam', '$rnum', '$mnam');");
		}
		mysqli_close($con);
		header("location: inputlink.php");	
?>