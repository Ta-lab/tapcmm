<?php
session_start();
if(isset($_SESSION['user']))
{
	//if(($_SESSION['access']=="ALL" && $_SESSION['user']=="123") || ($_SESSION['user']=="100"))
	if(($_SESSION['access']=="ALL" && $_SESSION['user']=="123"))
	{
		//header("location: inputlink.php?msg=7");
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
	<script src = "js\excelreport.js"></script>
	<link rel="stylesheet" type="text/css" href="des.css">

</head>
<body>
<script>
	function preventback()
	{
		window.history.forward();
	}
		setTimeout("preventback()",0);
		window.onunload = function(){ null };
</script>
<style>
.column
{
    float: left;
    width: 33%;
}
.column1
{
    float: left;
    width: 33%;
	display: none;
}
</style>
	<div style="float:right">
		<a href="logout.php"><label>Logout</label></a>
	</div>
	<div style="float:left">
		<a href="inputlink.php"><label>Input</label></a>
	</div>
	<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
	</div>
	<h4 style="text-align:center"><label>STOCKING POINT RECONCILATION</label></h4>
	<div>
			
		<div style="float:left">
			<a href="inputlink.php"><label>Back to report</label></a>
		</div>
		<br/>
		<script>
			function reload(form)
			{
				var p2 = document.getElementById("p4").value;
				self.location=<?php echo"'reconcile.php?stkpt='"?>+p2;
			}
			function reload1(form)
			{
				var p2 = document.getElementById("p4").value;
				var p3 = document.getElementById("p5").value;
				self.location=<?php echo"'reconcile.php?stkpt='"?>+p2+'&pnum='+p3;
			}
		</script>
	<div class="divclass">
		<form method="POST" action='recdb.php'>	
			</br>
			<datalist id="splist" >
					<?php
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
						if(!$con)
							echo "connection failed";
						$t=$_GET['cname'];
						$t1=$_GET['partnumber'];
						$result1 = $con->query("select operation from m11 where opertype='STOCKING POINT' and operation!='STORES'");
						while ($row = mysqli_fetch_array($result1)) 
						{
							echo "<option value='".$row['operation']."'>".$row['operation']."</option>";
						}
					?>
				</datalist>
			<div class="find">
				<label>STOCKING POINT</label>
				<input type="text" style="width:50%; background-color:white;" onchange=reload(this.form) id="p4" name="stkpt" list="splist" value="<?php if(isset($_GET['stkpt'])){echo $_GET['stkpt'];}?>"/>
			</div>
			<div class="find1">
				<datalist id="partlist" >
						<?php
							if(isset($_GET['stkpt']))
							{
								$stkpt=$_GET['stkpt'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								if(!$con)
									echo "connection failed";
								$query = "SELECT distinct pnum from m12 where operation='$stkpt'";
										$result = $con->query($query);
										echo"<option value=''>Select one</option>";
										while ($row = mysqli_fetch_array($result)) 
										{
											echo "<option value='".$row['pnum']."'>".$row['pnum']."</option>";
										}
							}
						?>
						</datalist>
					<label>PART NUMBER</label>
					<input type="text" style="width: 60%; background-color:white;" 	onchange=reload1(this.form) id="p5" name="partnumber" list="partlist" value="<?php if(isset($_GET['pnum'])){echo $_GET['pnum'];}?>"/>
			</div>
		</div>
			<?php
			if(isset($_GET['stkpt']))
			{
				if(isset($_GET['pnum']) && $_GET['pnum']!="")
				{
					$pnum=$_GET['pnum'];
				}
				else
				{
					$pnum="%%";
				}
				$stkpt=$_GET['stkpt'];
				$servername = "localhost";
				$username = "root";
				$password = "Tamil";
				$conn = new mysqli($servername, $username, $password, "mypcm");
				echo'<table id="testTable" align="center">
					  <tr>
					    <th>S.No</th>
						<th>STOCKING POINT</th>
						<th>DATE</th>
						<th>PART NUMBER</th>
						<th>RC / DC  NUMBER</th>
						<th>QUANTITY  IN ERP STOCK</th>
						<th>CUMULATIVE ERP STOCK</th>
						<th>AVAILABLE STOCK</th>
						<th>NO OF DAYS</th>
					  </tr>';
				$query2 = "SELECT T1.date,T1.pnum,T1.stkpt,prc,pr,days,SUM(partissued),pr-SUM(partissued) as stock FROM (select distinct(PNUM) as pnum,stkpt,prcno as prc,date,sum(partreceived) as pr,datediff(NOW(),date) as days from d12 where pnum!='' and stkpt!='' AND pnum='$pnum' and stkpt='$stkpt' GROUP BY prcno ) AS T1 LEFT JOIN d12 on d12.prcno=T1.prc WHERE d12.stkpt='$stkpt' GROUP BY prcno  HAVING stock>0 ORDER BY days DESC";
				$result2 = $conn->query($query2);
				$i=0;
				$stk=0;
				while($row1 = mysqli_fetch_array($result2))
				{
					$i=$i+1;
					echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text' style='background-color:' readonly='readonly' value='$i'</td>";
					echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text' style='background-color:' readonly='readonly' name='sp[]' value='$stkpt'</td>";
					echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text' style='background-color:' name='dt[]' readonly='readonly' value='".$row1['date']."'</td>";
					echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text' style='background-color:' name='pn[]' readonly='readonly' value='".$row1['pnum']."'</td>";
					echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text' style='background-color:' name='pr[]' readonly='readonly' value='".$row1['prc']."'</td>";
					echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text' style='background-color:' name='eq[]' readonly='readonly' value='".$row1['stock']."'</td>";
					$stk=$stk+$row1['stock'];
					echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text' style='background-color:' readonly='readonly' value='".$stk."'</td>";
					echo"<td><input  style='background: rgba(0,0,0,.075); width:100%; text-align:center; color:white' type='text' style='background-color:' name='aq[]' value='".$row1['stock']."'</td>";
					echo"<td>".$row1['days']."</td>";
					echo"</tr>";
				}
			}
			?>
			<div>
				<input type="SUBMIT" style="right-align:50px" name="submit" value="RECONCILE"/>
			</div>
			<br><br>
		</form>
	</body>
</html>
		