<?php
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		
		if(isset($_POST['submit']))
		{
			$id = $_POST['idn'];
			$uname = $_POST['name'];
			$psw = $_POST['psw'];
			$npsw = $_POST['npsw'];
			$query1 = "SELECT password from admin1 where userid='$id'";
			$result1 = $con->query($query1);
			$row1 = mysqli_fetch_array($result1);
			echo $psw;
			echo $row1['password'];
			if($psw==$row1['password'])
			{
				mysqli_query($con,"UPDATE admin1 set password='$npsw' WHERE userid='$id' AND password='$psw'");
				mysqli_query($con,"UPDATE admin1 set status='0' where userid='$id'");
				//echo "UPDATE admin1 set password='$npsw' WHERE userid='$id' AND password='$psw'";
				session_start();
				unset($_SESSION['user']);
				unset($_SESSION['username']);
				unset($_SESSION['access']);
				unset($_SESSION['ip']);
				session_destroy();
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
		}
?>