<?php
session_start();
if(isset($_SESSION['user']))
{
	if(($_SESSION['access']=="ALL" && $_SESSION['user']=="123") || ($_SESSION['user']=="100" || $_SESSION['user']=="109" || $_SESSION['user']=="135"))
	{
		$id=$_SESSION['user'];
		$activity="PROCESS MASTER UPDATION";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		$con=mysqli_connect('localhost','root','Tamil','mypcm');
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
	}
	else
	{
		header("location: logout.php");
	}
}
else
{
	header("location: index.php");
}
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\bootstrap.min.js"></script>
	<script src = "js\jquery-2.2.4.js"></script>
	<link rel="stylesheet" type="text/css" href="design.css">
</head>
<body>	
	<div class="container-fluid">	
		<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<div class="divclass">
		<h4 style="text-align:center"><label>ITEM PROCESS MASTER UPDATION</label></h4>
		<script>
			function preventback()
			{
				window.history.forward();
			}
			setTimeout("preventback()",0);
			window.onunload = function(){ null };
			function reload0(form)
			{
				var p2 = document.getElementById("pnum").value;
				self.location=<?php echo"'i11.php?mat='"?>+p2;
			}
		</script>
		<form class="pagetitle" method="POST" action="i11db.php">			
		<div>
			<br>
			<label>PART NUMBER</label>
			<input type="text" list="combo-options" onchange="reload0(this.form)" name ="pn" id="pnum"  value="<?php
				if(isset($_GET['mat']))
				{
					echo $_GET['mat'];
				}
				?>"/>
			<?php					
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$result = mysqli_query($con,"SELECT DISTINCT pnum FROM m13");
					echo "";
					echo"<datalist id='combo-options'>";
					while($row = mysqli_fetch_array($result))
					{
						echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
					}
					echo"</datalist>";
				?>
		</div>
		<div>
				<?php
				if(isset($_GET['mat']) && $_GET['mat']!="")
				{
					$pnum=$_GET['mat'];
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$result = mysqli_query($con,"SELECT T1.operation flow,T2.operation as actual FROM (SELECT DISTINCT m11.operation FROM m11 where operation!='Stores') AS T1 LEFT JOIN (SELECT DISTINCT m12.operation FROM m12 WHERE pnum='$pnum') AS T2 ON T1.operation=T2.operation");
					$i=0;
					while($row = mysqli_fetch_array($result))
					{
						$i=$i+1;
						if($row['actual'] == NULL)
						{
							echo'<div style="color: white;"><input  type="checkbox" name="oper'.$i.'" value="'.$row['flow'].'" />'.$row['flow'].'</div>';
						}
						else
						{
							echo'<div style="color: white;"><input  type="checkbox" checked="checked" name="oper'.$i.'" value="'.$row['flow'].'" />'.$row['flow'].'</div>';
						}
						
					}
					echo "</select>";
				}	
				?>
	
		</div>
		</br>
		<div>
			<label>CYCLE TIME</label>
				<input type="text" id="ct" name="ct" placeholder="Enter Cycle Time">
		</div>
		</br>
		<div>
			<label>SETTING TIME</label>
				<input type="text" id="rm" name="rm" placeholder="Enter Setting Time">
		</div>	
		</br>
		</br>
		<div>
				<input type="Submit" name="submit" id="submit"  value="SUBMIT" onclick="myFunction()"/>
		</div>
		</form>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
</script>
		</div>
</body>
</html>
