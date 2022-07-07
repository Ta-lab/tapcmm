<?php
session_start();
$inactive = 600; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive)
{
	header("Location: logout.php");
}
$_SESSION['timeout']=time();
if(isset($_SESSION['user']))
{
	
	{
		$id=$_SESSION['user'];
		$activity="RM RETURN REPORT";
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
	<link rel="stylesheet" type="text/css" href="design.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<h4 style="text-align:center"><label>PRODUCTION UPDATION</label></h4>
		<div class="divclass">
		<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
		<form method="POST" action='i141.php'>	
			</br>
			<div>
				<label>DATE</label>
					<input type="date" id="tdate" name="tdate" value="<?php
					if(isset($_GET['tdate']))
					{
						echo $_GET['tdate'];
					}
					else
					{
						echo date('Y-m-d');
					}
					?>"/>
			</div>
			<br>
			<div>
				<label>RCNO</label>
					<select name ="rcno" id="rcno" onchange="reload(this.form)">
					<option value=''>Select one</option>
						<?php			
							$rat=$_GET['rat'];
							$date=0000-00-00;
					        $con = mysqli_connect('localhost','root','Tamil','mypcm');
				            if(!$con)
								echo "connection failed";
						    $query = "select distinct(d11.rcno) from d11 join d12 on d11.rcno=d12.rcno where closedate='0000-00-00' and d12.rcno!='' and d12.rcno like '_20__0%'";
						    $result = $con->query($query);  
							while ($row = mysqli_fetch_array($result)) 
								{
									if($_GET['rat']==$row['rcno'])
										echo "<option selected value='".$row['rcno']."'>".$row['rcno']."</option>";
									else
										echo "<option value='".$row['rcno']."'>".$row['rcno']."</option>";
								}
							echo "</select></h1>";
						?>
						<script>
						function reload(form)
						{	
							var val=form.rcno.options[form.rcno.options.selectedIndex].value; 
							var s = document.getElementById("tdate").value;
							self.location='i14.php?tdate='+s+'&rat='+val;
						}
						</script>
			</div>
			<br>
			<div>
				<label>PART NUMBER</label>
					<select name ="partnumber" id="pnum" onchange="reload2(this.form)">
					<option value=''>Select one</option>
						<?php			
							if(isset($_GET['rat'])) 
							{	 
								$rat=$_GET['rat'];
								$r=substr($rat,0,1);
								echo $r;
              					$date=0000-00-00;
                				$con = mysqli_connect('localhost','root','Tamil','mypcm');
                				if(!$con)
                  					echo "connection failed";
                    			if($r=="A")
                    			{
									$query1 = "SELECT rm from d12 where rcno='$rat' and rm!=''";
                        			$result1 = $con->query($query1);
									$row1 = mysqli_fetch_array($result1);
									echo $row1['rm'];
                        			$query2 = "SELECT pnum from m13 where rmdesc='".$row1['rm']."'";
                        			$result2 = $con->query($query2);
                        			while ($row2 = mysqli_fetch_array($result2)) 
                        			{
                            			if($_GET['mat']==$row2['pnum'])
											echo "<option selected value='" . $row2['pnum'] . "'>" . $row2['pnum'] ."</option>";
										else
											echo "<option value='" . $row2['pnum'] . "'>" . $row2['pnum'] ."</option>";
                        			}
                    			}
                    			else
                    			{
                        			$query2 = "SELECT DISTINCT d12.pnum FROM d12 join d11 on d11.rcno=d12.rcno where d11.closedate='$date' and d12.rcno = '$rat'";
                        			$result2 = $con->query($query2);
                        			while ($row2 = mysqli_fetch_array($result2)) 
                        			{
                       				     if($_GET['mat']==$row2['pnum'])
											echo "<option selected value='" . $row2['pnum'] . "'>" . $row2['pnum'] ."</option>";
										else
											echo "<option value='" . $row2['pnum'] . "'>" . $row2['pnum'] ."</option>";
                        			}
                    			}
								echo "</select></h1>";
							}
						?>
						<script>
						function reload2(form)
						{	
							var val=form.partnumber.options[form.partnumber.options.selectedIndex].value; 
							self.location=<?php if(isset($_GET['rat']))echo"'i14.php?tdate=".$_GET['tdate']."&rat=".$_GET['rat']."&mat='";?> + val ;
						}
						</script>
			</div>
			<br>
			<div>
				<label>OPERATION</label>
					<select name ="operation" id="prcno">
						<?php			
							if(isset($_GET['mat'])) 
							{	
								$mat=$_GET['mat'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								$query1 = "SELECT DISTINCT m12.operation from m12 join m11 on m12.operation=m11.operation where m12.pnum='$mat' and m11.opertype!='stocking point'";
								$result1 = $con->query($query1);  
								echo "<option value=''>Select one</option>";
								while ($row2 = mysqli_fetch_array($result1)) 
								{
									if($_GET['cat']==$row2['operation'])
										echo "<option selected value='" . $row2['operation'] . "'>" . $row2['operation'] ."</option>";
									else
										echo "<option value='" . $row2['operation'] . "'>" . $row2['operation'] ."</option>";
								}
							}
						?>
					</select>
			</div>
			<br>
			<div>
				<label>QTY PRODUCED</label>
					<input type="text" id="pro" name="pro" placeholder="Enter Quantity Produced">
			</div>
			<br>
			<div>
				<label>MACHINE ID</label>
					<input type="text" id="mid" name="mid" placeholder="Enter Machine ID">
			</div>
			</br>
			
			
			<div>
					<input type="Submit" name="submit" id="submit"  value="SUBMIT" onclick="myFunction()"/>
			</div>
<script>
var hidden = false;
function myFunction() {
        hidden = !hidden;
        if(hidden) {
			document.getElementById('submit').style.visibility = 'hidden';
        }
}
</script>
		</form>
	</div>
</body>
</html>