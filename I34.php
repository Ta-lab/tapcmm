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
	if($_SESSION['access']=="Quality" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="QUALITY UPDATION";
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
		<form method="POST" action='i341.php'>	
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
				<label>SHIFT</label>
					<select name ="shift" required id="shift" onchange="reload(this.form)">
						<?php			
							if(isset($_GET['shift'])) 
							{
								$shift=$_GET['shift'];
								if($_GET['shift']=='I')
									echo"<option selected value='I'>I</option>
										<option value='II'>II</option>
										<option value='III'>III</option>";
								else if($_GET['shift']=='II')
									echo"<option value='I'>I</option>
										<option selected value='II'>II</option>
										<option value='III'>III</option>";
								else if($_GET['shift']=='III')
									echo"<option value='I'>I</option>
										<option value='II'>II</option>
										<option selected value='III'>III</option>";
							}
							else
							{
								echo"<option value=''>Choose the shift</option>
										<option value='I'>I</option>
										<option value='II'>II</option>
										<option value='III'>III</option>";
							}
						?>
					</select>
			</div>
			<br>
			<div>
				<label>OPERATION</label>
					<select name ="operation" required id="operation" onchange="reload1(this.form)">
						<?php			
							if(isset($_GET['shift']) && $_GET['shift']!="") 
							{	
								$mat=$_GET['mat'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								$query1 = "SELECT DISTINCT operation FROM `m11` WHERE opertype='OPERATION'";
								$result1 = $con->query($query1);  
								echo "<option value=''>Select Operation</option>";
								while ($row2 = mysqli_fetch_array($result1)) 
								{
									if($_GET['opr']==$row2['operation'])
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
				<label>GROUP ID</label>
					<select name ="groupid"  id="groupid" onchange="reload2(this.form)">
						<?php			
							if(isset($_GET['opr']) && $_GET['opr']!="") 
							{	
								$mat=$_GET['opr'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								$query1 = "SELECT DISTINCT groupid FROM `m16`  WHERE area='$mat'";
								$result1 = $con->query($query1);  
								echo "<option value=''>Select Group Id</option>";
								while ($row2 = mysqli_fetch_array($result1)) 
								{
									if($_GET['gid']==$row2['groupid'])
										echo "<option selected value='" . $row2['groupid'] . "'>" . $row2['groupid'] ."</option>";
									else
										echo "<option value='" . $row2['groupid'] . "'>" . $row2['groupid'] ."</option>";
								}
							}
						?>
					</select>
			</div>
			<br>
			<div>
				<label>MACHINE ID</label>
					<select name ="mid" required id="mid" onchange="reload3(this.form)">
						<?php			
							if(isset($_GET['opr']) && $_GET['opr']!="" && isset($_GET['gid']) && $_GET['gid']!="") 
							{	
								$p=$_GET['opr'];
								$p1=$_GET['gid'];
								$con = mysqli_connect('localhost','root','Tamil','mypcm');
								$result = $con->query("SELECT DISTINCT midmwc FROM `m16` WHERE area='$p' AND groupid='$p1'");
								echo "<option value=''>Select Machine ID</option>";
								while($row = mysqli_fetch_array($result))
								{	
									if($_GET['mid']==$row['midmwc'])
										echo "<option selected value='".$row['midmwc']."'>".$row['midmwc']."</option>";
									else
										echo "<option value='".$row['midmwc']."'>".$row['midmwc']."</option>";
								}
							}
						?>
					</select>
			</div>
			<br>
			<div>
				<label>OPERATOR ID</label>
					<select name ="operid" required id="operid" onchange="reload4(this.form)">
						<?php			
							if(isset($_GET['opr']) && $_GET['opr']!="") 
							{	
								$s=$_GET['opr'];
								$con = mysqli_connect('localhost','root','Tamil','myppc');
								$result = $con->query("SELECT DISTINCT opername FROM `moper` WHERE operation='$s'");
								echo "<option value=''>Select Operator Name</option>";
								while($row = mysqli_fetch_array($result))
								{	
									if($_GET['opername']==$row['opername'])
										echo "<option selected value='".$row['opername']."'>".$row['opername']."</option>";
									else
										echo "<option value='".$row['opername']."'>".$row['opername']."</option>";
								}
							}
						?>
					</select>
			</div>
			<br>
			<div>
			<label>RCNO</label>
			<input type="text" list="combo-options" name ="rcno" id="rcno" onchange="reload5(this.form)" value="<?php
				if(isset($_GET['rat']))
				{
					echo $_GET['rat'];
				}
				?>"/>
			<?php					
					$con=mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
						die(mysqli_error());
					$query = "select distinct(d11.rcno) from d11 join d12 on d11.rcno=d12.rcno where closedate='0000-00-00' and d12.rcno!='' and d12.rcno like '_20__0%'";
					$result = $con->query($query); 
					echo "";
					echo"<datalist id='combo-options'>";
						while ($row = mysqli_fetch_array($result))
							{
								echo "<option value='".$row['rcno']."'>".$row['rcno']."</option>";
							}
					echo"</datalist>";
				?>
		</div>
		<script>
		function reload(form)
					{	
						var val=form.shift.options[form.shift.options.selectedIndex].value; 
						var s = document.getElementById("tdate").value;
						self.location='i34.php?tdate='+s+'&shift=' + val ;
					}
		function reload1(form)
		{	
			var val=form.shift.options[form.shift.options.selectedIndex].value; 
			var val1=form.operation.options[form.operation.options.selectedIndex].value; 
			var s = document.getElementById("tdate").value;
			self.location='i34.php?tdate='+s+'&shift=' + val+'&opr=' + val1 ;
		}
		function reload2(form)
		{	
			var val=form.shift.options[form.shift.options.selectedIndex].value; 
			var val1=form.operation.options[form.operation.options.selectedIndex].value; 
			var s = document.getElementById("tdate").value;
			var s1 = form.groupid.options[form.groupid.options.selectedIndex].value; 
			self.location='i34.php?tdate='+s+'&shift=' + val+'&opr=' + val1+'&gid=' + s1 ;
		}
		function reload3(form)
		{	
			var val=form.shift.options[form.shift.options.selectedIndex].value; 
			var val1=form.operation.options[form.operation.options.selectedIndex].value; 
			var val2=form.mid.options[form.mid.options.selectedIndex].value; 
			var s = document.getElementById("tdate").value;
			var s1 = form.groupid.options[form.groupid.options.selectedIndex].value; 
			self.location='i34.php?tdate='+s+'&shift=' + val+'&opr=' + val1+'&gid=' + s1+'&mid=' + val2 ;
		}
		function reload5(form)
		{	
			var val=form.shift.options[form.shift.options.selectedIndex].value; 
			var val1=form.operation.options[form.operation.options.selectedIndex].value; 
			var val2=form.mid.options[form.mid.options.selectedIndex].value; 
			var val3=form.operid.options[form.operid.options.selectedIndex].value; 
			var s = document.getElementById("tdate").value;
			var s1 = form.groupid.options[form.groupid.options.selectedIndex].value; 
			var s2 = document.getElementById("rcno").value;
			self.location='i34.php?tdate='+s+'&shift=' + val+'&opr=' + val1+'&gid=' + s1+'&mid=' + val2+'&opername=' + val3+'&rat=' + s2 ;
		}
		</script>
			<br>
			<div>
				<label>PART NUMBER</label>
					<select name ="partnumber" required id="pnum">
					<option value=''>Select one</option>
						<?php			
							if(isset($_GET['rat'])) 
							{	 
								$rat=$_GET['rat'];
								$query2 = "SELECT DISTINCT pnum FROM d11 where rcno = '$rat'";
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
						?>
			</div>
			<br>
			<div>
				<label>QTY PRODUCED</label>
					<input type="text" id="qtyprod" name="qtyprod" required placeholder="Enter Reciept Quantity">
			</div>
			<br>
			<table id="mytable" align='center'>
    <thead>
        <th>Type of loss</th>
        <th>Quantity</th>
        <th>Time Taken</th>
        <th>Reason</th>
        <th>Loss Allocated Dept </th>
    </thead>
    <tbody>
        <tr>
            <td>
               <?php
							$con = mysqli_connect('localhost','root','Tamil','myppc');
							if(!$con)
								echo 'connection failed';	
							$result = $con->query('SELECT lname from mloss');
							echo"<select style='background: rgba(0,0,0,.075); width:100%' name ='lname[]'  >
									<option value=''>Choose the Loss Type</option>";
									while($row = mysqli_fetch_array($result))	
										echo"<option value='".$row['lname']."'>".$row['lname']."</option>";
							echo"</select>";
						?>
					
            </td>
			<td>
				<input type='text' style='text-align: center;background: rgba(0,0,0,0);width:100%;' name='qty[]'>
            </td>
            <td>
				<input type='text' style='text-align: center;background: rgba(0,0,0,0);width:100%;' name='td[]'>
            </td>
            <td>
                <input type='text' style='text-align: center;background: rgba(0,0,0,0);width:100%;'name='reason[]'>   
            </td>
			
            <td>
				<?php
					$con = mysqli_connect('localhost','root','Tamil','myppc');
					if(!$con)
						echo 'connection failed';	
					$result = $con->query('SELECT dept from dept');
					echo"<select style='background: rgba(0,0,0,.075); width:100%' name ='dept[]' id='dept[]'>
							<option value=''>Choose the Department</option>";
							while($row = mysqli_fetch_array($result))	
								echo"<option value='".$row['dept']."'>".$row['dept']."</option>";
					echo"</select>";
				?> 
			</td>
        </tr>
    </tbody>
</table><br/>
<a href="#" id="insert-more" style='margin-left:45%'><label>Add Row</label> </a>&nbsp;&nbsp;&nbsp;
<a href="" id="remove"><label>Remove Row</label> </a>
<script>
 $("#insert-more").click(function () {
     $("#mytable").each(function () {
         var tds = '<tr>'; 
         jQuery.each($('tr:last td', this), function () {
             tds += '<td>' + $(this).html() + '</td>';
         });
         tds += '</tr>';
         if ($('tbody', this).length > 0) {
             $('tbody', this).append(tds);
         } else {
             $(this).append(tds);
         }
     });
});

$('#remove').on('click', function() {
    $('#mytable').last().remove();
});

</script>
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