<?php
$con = mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	echo "connection failed";
$query = "SELECT * FROM `d18`";
$result = $con->query($query);
while ($row = mysqli_fetch_array($result)){
   $r[]=$row;
}
echo json_encode($r);
//echo $row['date'];
?>