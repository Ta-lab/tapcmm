<?php
session_start();
if(isset($_SESSION['user']))
{
	if($_SESSION['access']=="Quality" || $_SESSION['access']=="ALL")
	{
		$id=$_SESSION['user'];
		$activity="RM INSPECTION";
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
	<link rel="stylesheet" type="text/css" href="designformasterinv.css">
	<style>
.column
{
    float: left;
    width: 33%;
}
.column1
{
    float: left;
    width: 90%;
}

</style>
</head>
<body>
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
		<h4 style="text-align:center"><label>INCOMING INSPECTION UPDATION [I82]</label></h4>
		<br>
		<form  method="POST" action="i82db.php">
		<div id="stylized" class="myform">
			<div class="column">
				<label>&nbsp;GIN NUMBER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</label>
				<select  id="gnum" name="gnum" required onchange="reload(this.form)" />
					<?php
							$rat=$_GET['rat'];
							$con = mysqli_connect('localhost','root','Tamil','storedb');
							if(!$con)
								echo "connection failed";
							$query = "SELECT grnnum FROM receipt WHERE grnnum NOT IN (SELECT grnnum FROM inspdb)";
									$result = $con->query($query);
									echo"<option value=''>Select one</option>";
									while ($row = mysqli_fetch_array($result)) 
									{
										if($_GET['rat']==$row['grnnum'])
											echo "<option selected value='".$row['grnnum']."'>".$row['grnnum']."</option>";
										else
											echo "<option value='".$row['grnnum']."'>".$row['grnnum']."</option>";
									}
									echo "</select>";
					?>																																	
					<script>
						function reload(form)
						{	
							var val=form.gnum.options[form.gnum.options.selectedIndex].value; 							
							self.location='i82.php?rat='+val;
						}
					</script>					
			</div>
			
			<div class="column">
				<label>INWARDED QUANTITY&nbsp;&nbsp;&nbsp;</label>					
				<?php	
					if(isset($_GET['rat']))
					{
						$query = "SELECT quantity_received FROM receipt where grnnum='$rat'";
								$result = $con->query($query);
								if(mysqli_num_rows($result) > 0)
								{
									while ($row = mysqli_fetch_array($result)) 
									{
										$str=$row['quantity_received'];
									}
									echo '<input  type="text" id="iqty"  required name="iqty" value="'.$str.'" />';
								}
								else
								{
									echo '<input  type="text" id="iqty"  required name="iqty" />';
								}
					}
					else
					{
						echo '<input  type="text" id="iqty"  required name="iqty" />';
					}
				?>
			</div>
								
			<div class="column">						
				<label>MATERIAL CODE&nbsp;&nbsp;&nbsp;</label>
				<?php	
					if(isset($_GET['rat']))
					{
						$query = "SELECT part_number FROM receipt where grnnum='$rat'";
								$result = $con->query($query);
								if(mysqli_num_rows($result) > 0)
								{
									while ($row = mysqli_fetch_array($result)) 
									{
										$str=$row['part_number'];
									}
									echo '<input  type="text" id="partnumber"  required name="partnumber" value="'.$str.'" />';
								}
								else
								{
									echo '<input  type="text" id="partnumber"  required name="partnumber" />';
								}
					}
					else
					{
						echo '<input  type="text" id="partnumber"  required name="partnumber" />';
					}
				?>					
			</div>	
			<br><br>
			<div class="column">
				<label>&nbsp;RM DESCRIPTION&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<?php	
					if(isset($_GET['rat']))
					{
						$con = mysqli_connect('localhost','root','Tamil','storedb');
						if(!$con)
							echo "connection failed";
						$query = "SELECT rmdesc FROM receipt where grnnum='$rat'";
								$result = $con->query($query);
								if(mysqli_num_rows($result) > 0)
								{
									while ($row = mysqli_fetch_array($result)) 
									{
										$str=$row['rmdesc'];
									}
									echo '<input  type="text" id="partdesc"  required name="partdesc" value="'.$str.'" />';
								}
								else
								{
									echo '<input  type="text" id="partdesc"  required name="partdesc" />';
								}
					}
					else
					{
						echo '<input  type="text"  required id="partdesc" name="partdesc" />';
					}
				?>	
			</div>
		
			<div class="column">
				<label>DATE_OF INSPECTION&nbsp;&nbsp;</label>					
					<input type="date"  id="dtinsp"  readonly value="<?php echo date("Y-m-d");?>" name="dtinsp" />
			</div>
			
			
			<div class="column">
				<label>INSPECTED_BY&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="text"  id="inspby"  required name="inspby" />
			</div>
			
			<br><br>
			
			<div class="column">
				<label>&nbsp;QTY_ACCEPTED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<input type="text"  id="qtyok"  required name="qtyok"/>							
			</div>
			
			<div class="column">
				<label>QTY_REJECTED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<input type="text"  id="qtyrej"  required name="qtyrej" />
			</div>
			
			<div class="column">
				<label>REASON&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<input type="text"  id="reason" name="reason" />
			</div>
			
			<br><br><br>
			
			<div>
				<button type="Submit" name="submit" style='margin-left:70%'>Submit</button>
			</div>
</form>
</body>
</html>