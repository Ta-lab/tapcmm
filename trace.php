<?php
session_start();
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(isset($_SESSION['user']))
{
	if($_SESSION['access']!="")
	{
		$id=$_SESSION['user'];
		$activity="TRACEBILITY REPORT";
		date_default_timezone_set('Asia/Kolkata');
		$time=date("Y-m-d g:i:s a");
		mysqli_query($con,"UPDATE admin1 set activity='$activity',lastact='$time' where userid='$id'");
	}
	else
	{
		//header("location: logout.php");
	}
}
else
{
	//header("location: index.php");
}
?><!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel = "stylesheet" href="tree.css">
	<link rel = "stylesheet" href="design3.css">
</head>
<body>
	<div style="float:right">
			<a href="logout.php"><label>Logout</label></a>
		</div>
		<div  align="center"><br>
		<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		<div style="float:left">
			<a href="inputlink.php"><label>HOME</label></a>
		</div>
		<?php
			$it=0;

			$str="";
			$inv = $_GET['inv'];
			echo '<h6 style="text-align:center"><label>ROUTECARD TRACEBILITY</label></h6>';
			$rcno=$inv;
			$s="";
			echo '<div class="tree">';
			track($rcno,1,0,$it);
			function track($rcno,$n,$i,$it)
			{
				$con=mysqli_connect('localhost','root','Tamil','mypcm');
				if($rcno!='')
				{
					$query = "SELECT pnum,operation FROM `d11` WHERE rcno='$rcno'";
					$result = $con->query($query);
					$row = mysqli_fetch_array($result);
					$query1 = "SELECT IF(rcno LIKE 'A%',SUM(rmissqty),SUM(partissued)) AS issqty,rm,inv,heat,IF(rcno LIKE 'A%','Kg','Nos') AS uom FROM `d12` WHERE rcno='$rcno'";
					$result1 = $con->query($query1);
					$row1 = mysqli_fetch_array($result1);
					if($n==1)
					{
						
						//echo '<ul><li><a href="#">'.$rcno.'<br>'.$n.' ('.$it.')</a>';
						echo '<ul><li><a href="#">'.$row["operation"].'<br>'.$rcno.'<br>PART NO : '.$row["pnum"].'<br>ISSUED QTY : '.round($row1["issqty"],2).' ('.$row1["uom"].')';
						if($row1['rm']!="")
						{
							echo '<br>RAW MAT : '.$row1["rm"].'<br>GRN NO : '.$row1["inv"].'<br>HEAT NO : '.$row1["heat"];
						}
						echo '</a>';
						
					}
					else
					{
						//echo '<li><a href="#">'.$rcno.'<br>'.$n.' ('.$it.')</a>';
						echo '<li><a href="#">'.$row["operation"].'<br>'.$rcno.'<br>PART NO : '.$row["pnum"].'<br>ISSUED QTY : '.round($row1["issqty"],2).' ('.$row1["uom"].')';
						if($row1['rm']!="")
						{
							echo '<br>RAW MAT : '.$row1["rm"].'<br>GRN NO : '.$row1["inv"].'<br>HEAT NO : '.$row1["heat"];
						}
						echo '</a>';
					}
				}
				$ret = $con->query("SET @row_number:=0;");
				$ret = $con->query("SELECT DISTINCT prcno,d12.rcno,@row_number:=@row_number + 1 AS num from d12  where rcno='$rcno' GROUP by prcno");
				$c= $ret->num_rows;
				$row2 = mysqli_fetch_array($ret);
				do{
						$it=$it+1;
						$temp=$row2['rcno'];
						if($row2['prcno']=="")
						{
							if($n==$c)
							{
								echo '</li></ul>';
							}
							else
							{
								echo '</li>';
							}
							break;
						}
						else
						{
							if($n==$c && $n>1)
							{
								echo '</li>';
							}
							track($row2['prcno'],$row2['num'],$i,$it);
						}
				}while($row2 = $ret->fetch_assoc());
			}
	?>
		</div>
		<br>
		<br>
</body>
</html>