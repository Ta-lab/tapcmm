<!DOCTYPE html>
<html>

<?php
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>
<body>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<?php
		
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		if(!$con)
			die(mysqli_error());
		if(isset($_POST['sub']) && $_POST['user']!='' && $_POST['pass'])
		{
			$id = $_POST['user'];
			$pass = $_POST['pass'];
			$query1 = "SELECT * from admin1 where userid='$id'";
			$result1 = $con->query($query1);
			$row1 = mysqli_fetch_array($result1);
			if($row1['status']=="0" || $_POST['user']=="123" || $_POST['user']=="100")
			{
				if($pass==$row1['password'])
				{
					session_start();
					$_SESSION['user']=$_POST['user'];
					$_SESSION['username']=$row1['username'];
					$_SESSION['access']=$row1['access'];
					$_SESSION['ip']=get_client_ip();
					$ip=$_SESSION['ip'];
					$_SESSION['timeout']=time();
					date_default_timezone_set('Asia/Kolkata');
					$time=date("Y-m-d g:i:s a");
					mysqli_query($con,"UPDATE admin1 set status='1',ip='$ip',lastact='$time' where userid='$id'");
					header("location: inputlink.php");
				}
				else
				{
					header("location: index.php?err1=1");
				}
			}
			else if($row1['ip']==get_client_ip() && $pass==$row1['password'])
			{
				session_start();
				$_SESSION['user']=$_POST['user'];
				$_SESSION['username']=$row1['username'];
				$_SESSION['access']=$row1['access'];
				$_SESSION['ip']=get_client_ip();
				$ip=$_SESSION['ip'];
				$_SESSION['timeout']=time();
				date_default_timezone_set('Asia/Kolkata');
				$time=date("Y-m-d g:i:s a");
				mysqli_query($con,"UPDATE admin1 set status='1',ip='$ip',lastact='$time' where userid='$id'");
				header("location: inputlink.php?msg=4");
			}
			else if($pass==$row1['password'])
			{
				$ip=$row1['ip'];
				header("location: index.php?err1=4&ip=$ip");
			}
			else
			{
				header("location: index.php?err1=1");
			}
			mysqli_close($con);
		}
		else
		{
			header("location: index.php");
		}
?>


</body>
</html>