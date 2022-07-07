<?php
$t=[30,50,40];
$q=100;
$i=0;
	while($q>0)
	{
		echo $t[$i]." ";
		$q=$q-$t[$i];
		echo $q."<br>";
		$i=$i+1;
	}
?>