<?php

	$con=mysqli_connect('localhost','root','Tamil','mypcm');
	
	$sql="CALL GetOpenRouteCard();";
	if($result=mysqli_query($con, $sql)){
		while($row=mysqli_fetch_assoc($result)){
			echo $row['rcno'];
			echo $row['operation'];
			
		}
	}
?>