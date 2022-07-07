@echo off
For /f "tokens=2-4 delims=/ " %%a in ('date /t') do (set mydate=%%c-%%a-%%b)
For /f "tokens=1-2 delims=/:" %%a in ("%TIME%") do (set mytime=%%a%%b)
 
SET backupdir=D:\
SET mysqluername=root
SET mysqlpassword=
SET databasem=mypcm
SET databases=storedb
SET databasee=erpdb
 
C:\xampp\mysql\bin\mysqldump.exe -u root  %databasem% > %backupdir%\%databasem%_%mydate%_%MYTIME%_.sql
C:\xampp\mysql\bin\mysqldump.exe -u root  %databases% > %backupdir%\%databases%_%mydate%_%MYTIME%_.sql
C:\xampp\mysql\bin\mysqldump.exe -u root  %databasee% > %backupdir%\%databasee%_%mydate%_%MYTIME%_.sql