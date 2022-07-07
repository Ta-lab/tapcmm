<?php
require "numtostr.php";
$t1=0;$s=1;$t2=0;$t3=68;$t4=5;$t5=26;$t6=27;$t7=14;$t8=3;$t8_1=3;$t9=4;$t10=9;$t11=10;$t12=10;$t13=10;$t14=13;$t15=11;$t16=11;$t17=15;$t18=0;$t19=1;$t20=43;$t21=57;$t22=0;$t23=0;
$invno=17;
$con = mysqli_connect('localhost','root','Tamil','mypcm');
$q = "select * from inv_det where invno='$invno'";
$r = $con->query($q);
$fch=$r->fetch_assoc();
echo $fch['invno'];	

?>