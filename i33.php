<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">	
	<link rel = "stylesheet" href="css\bootstrap.min.css">
	<script src = "js\jquery-2.2.4.js"></script>
	
	<link rel="stylesheet" type="text/css" href="design2.css">
</head>
<body>
	<div class="container-fluid">
		<div  align="center"><br>
			<img src="img/logo.png" alt="Mountain View" style="width:25%;height:25%;"><br><br>
		</div>
		<div style="float:right">
				<a href="index.php"><label>Logout</label></a>
		</div>
		<div style="float:left">
			<a href="inputlink.php"><label>Input</label></a>
		</div>
		</br>
		<h4 style="text-align:center"><label>PRODUCTION UPDATION</label></h4>
		<br>
		<form class="form-horizontal" method="POST" action='i331.php'>
			
			<div class="form-group">
				<label class="control-label col-xs-2">DATE</label>
				<input type="date" class='form-control' id="tdate" name="tdate" value="<?php
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
		
			<div>
				<label>SHIFT</label>
				<select name='shift' id='shift' required onchange="reload(this.form)">
				
				<?php
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
					else
						echo"<option value=''>Choose the shift</option>
							<option value='I'>I</option>
							<option value='II'>II</option>
							<option value='III'>III</option>";
				?>
				</select>
			</div>
			
			<script>
					function reload(form)
					{	
						var val=form.shift.options[form.shift.options.selectedIndex].value; 
						var s = document.getElementById("tdate").value;
						self.location='i33.php?tdate='+s+'&shift=' + val ;
					}
			</script>

			<div class = "form-group">
				<label class="control-label col-xs-2">RCNO</label>
				<select class='form-control' name ='rcno' required onchange='reload1(this.form)'>
				<option value=''>Choose the RCNO</option>
					<?php
					$f = date('Y-m-d',strtotime('-2 days'));
					
						$con = mysqli_connect('localhost','root','Tamil','mypcm');
						if(!$con)
							echo "connection failed";
						$result = $con->query("SELECT rcno from d11 where (rcno like 'A20___%' or rcno like 'B20___%') and (closedate='0000-00-00' or closedate>='$f') order by rcno");
						
						
						while($row = mysqli_fetch_array($result))
						{
							$r=substr($row['rcno'],0,1);
		
								if($_GET['rcno']==$row['rcno'])
									echo "<option selected value='".$row['rcno']."'>".$row['rcno']."</option>";
								else
									echo "<option value='".$row['rcno']."'>".$row['rcno']."</option>";
							
						}
					
					?>
				</select>
				<script>
				function reload1(form)
				{	
					var val=form.rcno.options[form.rcno.options.selectedIndex].value; 
					var s = document.getElementById("tdate").value;
					var s1 = document.getElementById("shift").value;
					self.location='i33.php?tdate='+s+'&shift='+s1+'&rcno='+val ;
				}
			</script>
			</div>
			
			<div class = "form-group">
				<label class="control-label col-xs-2">PART NUMBER</label>
				<select class='form-control' name ='pnum' onchange='reload2(this.form)'>
						<option>Choose the Part Number</option>
					<?php			
							if(isset($_GET['rcno'])) 
							{	 
								$rcno=$_GET['rcno'];
								$r=substr($rcno,0,1);
                				$con = mysqli_connect('localhost','root','Tamil','mypcm');
                				if(!$con)
                  					echo "connection failed";
								//echo"<option> Choose the Part Number </option>";
                    			if($r=="A")
                    			{
									$result = $con->query("SELECT m13.pnum FROM `m13` INNER JOIN d12 on m13.rmdesc=d12.rm where d12.rcno='$rcno'");
                        			while ($row = mysqli_fetch_array($result)) 
                        			{
                            			if($_GET['pnum']==$row['pnum'])
											echo "<option selected value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
										else
											echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
                        			}
                    			}
                    			else
                    			{
                        			$result = $con->query("SELECT DISTINCT pnum FROM d12 where rcno='$rcno'");
                        			while ($row = mysqli_fetch_array($result)) 
                        			{
                       				     if($_GET['pnum']==$row['pnum'])
											echo "<option selected value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
										else
											echo "<option value='" . $row['pnum'] . "'>" . $row['pnum'] ."</option>";
                        			}
                    			}
								
							}
						?>
					
				?>
				</select>
		
			
				<script>
					function reload2(form)
					{	
						var val=form.pnum.options[form.pnum.options.selectedIndex].value; 
						self.location=<?php if(isset($_GET['rcno']))echo"'i33.php?tdate=".$_GET['tdate']."&shift=".$_GET['shift']."&rcno=".$_GET['rcno']."&pnum='";?> + val ;
					}
				</script>

					
			</div>
			
			<div class = "form-group">
				<label class="control-label col-xs-2">OPERATION</label>
				<select class='form-control' name ='operation' onchange='reload3(this.form)'>
				<option value=''>Choose the Operation</option>
				<?php
					$con = mysqli_connect('localhost','root','Tamil','myppc');
					if(!$con)
						echo "connection failed";
						$result = $con->query("SELECT distinct(operation) FROM moper");
						while($row = mysqli_fetch_array($result))
						{	
							if($_GET['cat']==$row['operation'])
								echo "<option selected value='".$row['operation']."'>".$row['operation']."</option>";
							else
								echo "<option value='".$row['operation']."'>".$row['operation']."</option>";
						}
					echo "</select>";
					
				?>
			</div>
			<script>
					function reload3(form)
					{	
						var val=form.operation.options[form.operation.options.selectedIndex].value; 
						self.location=<?php if(isset($_GET['rcno']))echo"'i33.php?tdate=".$_GET['tdate']."&shift=".$_GET['shift']."&rcno=".$_GET['rcno']."&pnum=".$_GET['pnum']."&cat='";?> + val ;
					}
			</script>

			<div class="form-group">
				<label class="control-label col-xs-2">OPERATOR</label>
				<select class='form-control' name ='oid' onchange='reload4(this.form)'>
					<option value=''>Choose the Operator Name</option>
				<?php
					$con = mysqli_connect('localhost','root','Tamil','myppc');
					if(!$con)
						echo "connection failed";
					if(isset($_GET['cat'])) 
					{	
						$cat=$_GET['cat'];
						$result = $con->query("SELECT opid from moper where operation='$cat'");
						while($row = mysqli_fetch_array($result))
						{	
							if($_GET['oid']==$row['opid'])
								echo "<option selected value='".$row['opid']."'>".$row['opid']."</option>";
							else
								echo "<option value='".$row['opid']."'>".$row['opid']."</option>";
						}
					}
				?>
				</select>
			</div>
			<script>
				function reload4(form)
				{	
					var val=form.oid.options[form.oid.options.selectedIndex].value; 
					self.location=<?php if(isset($_GET['rcno']))echo"'i33.php?tdate=".$_GET['tdate']."&shift=".$_GET['shift']."&rcno=".$_GET['rcno']."&pnum=".$_GET['pnum']."&cat=".$_GET['cat']."&oid='";?> + val ;
				}
			</script>
			
			
			
			<div class="form-group">
				<label class="control-label col-xs-2">MACHINE ID</label>
				<select class='form-control' name ='mid' onchange='reload5(this.form)'>
				<option value=''>Choose the Machine Id</option>
				<?php
			
						$con = mysqli_connect('localhost','root','Tamil','myppc');
						if(!$con)
							echo "connection failed";
						
						$result = $con->query("SELECT DISTINCT mid from mmac");
						while($row = mysqli_fetch_array($result))
						{	
							if($_GET['mid']==$row['mid'])
								echo "<option selected value='".$row['mid']."'>".$row['mid']."</option>";
							else
								echo "<option value='".$row['mid']."'>".$row['mid']."</option>";
						}
						
				?>
				</select>
			</div> 
			
			<script>
				function reload5(form)
				{	
					var val=form.mid.options[form.mid.options.selectedIndex].value; 
					self.location=<?php if(isset($_GET['rcno']))echo"'i33.php?tdate=".$_GET['tdate']."&shift=".$_GET['shift']."&rcno=".$_GET['rcno']."&pnum=".$_GET['pnum']."&cat=".$_GET['cat']."&oid=".$_GET['oid']."&mid='";?> + val ;
				}
			</script>
			
			
			<div class="form-group">
				<label class="control-label col-xs-2">GROUP ID</label>
				<input class='form-control' type="text" id="groupid" readonly='readonly' name="groupid" value="<?php			
					if(isset($_GET['mid'])) 
					{
						$mid=$_GET['mid'];
						$query = "SELECT gid FROM mmac where mid='$mid'";
						$result = mysqli_query($con,$query);
						$temp1=mysqli_fetch_array($result);
						echo $temp1['gid'];
					}
					?>
				"/>
			</div>
			
			<div class="form-group">
				<label class="control-label col-xs-2">QTY PRODUCED</label>
				<input type="text" class="form-control" id="pro" name="pro" placeholder="Enter Produced Quantity" value="<?php if(isset($_GET['pro']))echo $_GET['pro'];?>">
			</div>
			
			


<table id="mytable" align='center'>
    <thead>
        <th>Type of loss</th>
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
									<option style='background:#536148;' value=''>Choose the Loss Type</option>";
									while($row = mysqli_fetch_array($result))	
										echo"<option style='background:#536148;' value='".$row['lname']."'>".$row['lname']."</option>";
							echo"</select>";
						?>
					
            </td>
            <td>
				<input type='text' style='text-align: center;background: rgba(0,0,0,.075);width:100%;' name='td[]'>
            </td>
            <td>
                <input type='text' style='text-align: center;background: rgba(0,0,0,.075);width:100%;'name='reason[]'>   
            </td>
			
            <td>
				<?php
					$con = mysqli_connect('localhost','root','Tamil','myppc');
					if(!$con)
						echo 'connection failed';	
					$result = $con->query('SELECT dept from dept');
					echo"<select style='background: rgba(0,0,0,.075); width:100%' name ='dept[]' id='dept[]'>
							<option style='background:#536148;' value=''>Choose the Department</option>";
							while($row = mysqli_fetch_array($result))	
								echo"<option style='background:#536148;'value='".$row['dept']."'>".$row['dept']."</option>";
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

			</div>
			<div class="form-group">
				<button type="Submit" class="btn btn-success" name="submit">Submit</button>
			</div>
		</form>
	</div>
</body>
</html>