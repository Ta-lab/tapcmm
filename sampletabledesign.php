<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=3">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h3>ROUTE CARD BASED ENTRY VERIFICATION</h3>
  <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>
        <th>DATE</th>
		<th>STOCKING POINT</th>
		<th>OPERATION</th>
		<th>RAW MATERIAL</th>
		<th>RM ISSUANCE</th>
		<th>PART NUMBER</th>
		<th>ROUTE CARD</th>
		<th>PREVIOUS RC</th>
		<th>PART ISSUED</th>
		<th>PART REJECTED</th>
		<th>PART RECEIVED</th>
		<th>GIN NUMBER</th>
		<th>HEAT NUMBER</th>
		<th>LOT NUMBER</th>
		<th>COIL NUMBER</th>
		<th>REASON</th>
		<th>ENTERED BY </th>
      </tr>
    </thead>
	
	<tbody>
	
			<?php 
				$con = mysqli_connect('localhost','root','Tamil','mypcm');
					if(!$con)
				echo "connection failed";
						
				$query2 = "SELECT * FROM d12 where rcno='C2020000191' OR prcno='C2020000191'";
				$result2 = $con->query($query2);
				while($row1 = mysqli_fetch_array($result2))
				{
					echo"<tr><td>".$row1['date']."</td>";
					echo"<td>".$row1['stkpt']."</td>";
					echo"<td>".$row1['operation']."</td>";
					echo"<td>".$row1['rm']."</td>";
					echo"<td>".$row1['rmissqty']."</td>";
					echo"<td>".$row1['pnum']."</td>";
					echo"<td>".$row1['rcno']."</td>";
					echo"<td>".$row1['prcno']."</td>";
					echo"<td>".$row1['partissued']."</td>";
					echo"<td>".$row1['qtyrejected']."</td>";
					echo"<td>".$row1['partreceived']."</td>";
					echo"<td>".$row1['inv']."</td>";
					echo"<td>".$row1['heat']."</td>";
					echo"<td>".$row1['lot']."</td>";
					echo"<td>".$row1['coil']."</td>";
					echo"<td>".$row1['rsn']."</td>";
					echo"<td>".$row1['username']."</td>";
				}
				
			?>
			
	</tbody>	
  </table>
  </div>
</div>

</body>
</html>
