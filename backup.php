<?php
   $dbhost = 'localhost';
   $dbuser = 'root';
   $dbpass = 'Tamil';
   $dbname='mypcm';
   $dbname1='storedb';
   $dbname2='erpdb';
   $dbname3='fabrication';
   $dir="D:\\";
   date_default_timezone_set("Asia/Kolkata");
   
   $con=mysqli_connect('localhost','root','Tamil','mypcm');
   date_default_timezone_set("Asia/Kolkata");
   $time=date("H:i:s");
   mysqli_query($con,"UPDATE autoreport SET backup='$time'");
   
   //MYPCM
   $backup_file = $dbname."_".date("Y-m-d@H-i");
   //$command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass ". "test_db | gzip > $backup_file";
   //echo "C:\\xampp\mysql\bin\mysqldump.exe -u root -p $dbname > $dir$backup_file.sql";
   //$command = "C:\\xampp\mysql\bin\mysqldump.exe -u root $dbname > $dir$backup_file.sql";
   $command = "C:\\xampp\mysql\bin\mysqldump.exe -u root -p$dbpass $dbname > $dir$backup_file.sql";
   system($command);
   copy('D:\\'.$backup_file.'.sql', '\\\\192.168.1.100\\narayanan\BACK UP\\'.$backup_file.'.sql');
   
   //STOREDB
   $backup_file = $dbname1."_".date("Y-m-d@H-i");
   //$command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass ". "test_db | gzip > $backup_file";
   //echo "C:\\xampp\mysql\bin\mysqldump.exe -u root -p $dbname1 > $dir$backup_file.sql";
   $command = "C:\\xampp\mysql\bin\mysqldump.exe -u root -p$dbpass $dbname1 > $dir$backup_file.sql";
   system($command);
   copy('D:\\'.$backup_file.'.sql', '\\\\192.168.1.100\\narayanan\BACK UP\\'.$backup_file.'.sql');
   
   //ERP DB
   
   $backup_file = $dbname2."_".date("Y-m-d@H-i");
   //$command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass ". "test_db | gzip > $backup_file";
   echo "C:\\xampp\mysql\bin\mysqldump.exe -u root -p $dbname2 > $dir$backup_file.sql";
   $command = "C:\\xampp\mysql\bin\mysqldump.exe -u root -p$dbpass $dbname2 > $dir$backup_file.sql";
   system($command);
   copy('D:\\'.$backup_file.'.sql', '\\\\192.168.1.100\\narayanan\BACK UP\\'.$backup_file.'.sql');
   
   //FABRICATION DB
   
   $backup_file = $dbname3."_".date("Y-m-d@H-i");
   //$command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass ". "test_db | gzip > $backup_file";
   echo "C:\\xampp\mysql\bin\mysqldump.exe -u root -p $dbname3 > $dir$backup_file.sql";
   $command = "C:\\xampp\mysql\bin\mysqldump.exe -u root -p$dbpass $dbname3 > $dir$backup_file.sql";
   system($command);
   copy('D:\\'.$backup_file.'.sql', '\\\\192.168.1.100\\narayanan\BACK UP\\'.$backup_file.'.sql');
   
   
   /*$con=mysqli_connect('localhost','root','Tamil','mypcm');
   date_default_timezone_set("Asia/Kolkata");
   $time=date("H:i:s");
   mysqli_query($con,"UPDATE autoreport SET backup='$time'");
   */
?>
