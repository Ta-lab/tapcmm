<?php
		$p =  $_REQUEST["pnum"];
		
		$con = mysqli_connect('localhost','root','Tamil','invoicedb');
        if(!$con)
            echo "connection failed";
			$result = $con->query("SELECT DISTINCT cust_name FROM invoicedb where pnum='$p'");
		
			while($row = mysqli_fetch_array($result)) 
				echo $row['cust_name'];
			
			mysqli_close($con);
	

?>