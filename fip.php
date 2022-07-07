<?php
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
require "numtostr.php";
/* Open the printer; this will change depending on how it is connected */
$con = mysqli_connect('localhost','root','Tamil','mypcm');
$result1 = $con->query("SELECT * from invprinter");
$row = mysqli_fetch_array($result1);
$port="LPT1";
$connector = new FilePrintConnector($port);
$printer = new Printer($connector);
$s=1;$t1=35;$t2=20;$t3=16;$t4=51;$t5=3;$t6=10;$t7=14;$t8=12;
/* Start the printer */
$printer = new Printer($connector);

/* Print top logo */
//$printer -> setJustification(Printer::JUSTIFY_CENTER);
$dt="";

$invno = $_POST['from'];
$pn = $_POST['pn'];
$cc = $_POST['cc'];
$in = $_POST['in'];
//$fin = $_GET['fi'];
//$rc = $_GET['rc'];
//mysqli_query($con,"UPDATE fi_tobeprint SET status='T' WHERE finum='$fin' and rcno='$rc'");

$result1 = $con->query("SELECT ccode,cname,cname1,cadd1,cadd2,cadd3,vc,pn,pd,qty from inv_det where invno='$invno'");
$row = mysqli_fetch_array($result1);
$pnum=$row['pn'];
	//PRINTING FI REOPRT
	$result3 = $con->query("SELECT * FROM `fi_report` where insno='$fin' order by sno");
	$printer -> selectPrintMode();
	$printer -> setEmphasis(true);
	$s=addlines("",0);
	$printer -> text($s);
	$s=fspace($row['cname'],$t4)."\n";
	$printer -> text($s);
	$s=fspace($row['cadd1'],$t4)."\n";
	$printer -> text($s);
	$s=fspace($row['cadd2'],$t4)."\n";
	$printer -> text($s);
	$s=fspace($row['cadd3'],$t4)."\n";
	$printer -> text($s);
	$s=addlines("",1);
	$printer -> text($s);
	$s=fspace(leftalign($row['vc'],$t6),$t5*3).fspace(leftalign($rc,$t6*2),$t5*4).fspace(leftalign($fin,($t6*2)-3),$t6-2).$rowdate['date']."\n";
	$printer -> text($s);
	$s=addlines("",3);
	$printer -> text($s);
	$t1=35;
	$l="";
	while($row3 = mysqli_fetch_array($result3))
	{
		$l=ceil(strlen($row3['chars'])/$t6);
		if(ceil(strlen($row3['drawspec'])/$t7) > $l)
		{
			$l=ceil(strlen($row3['drawspec'])/$t7);
		}
		if(ceil(strlen($row3['method'])/$t8) > $l)
		{
			$l=ceil(strlen($row3['method'])/$t8);
		}
		if($t1<$l)
		{
			$s=addlines("",$t1+1);
			$printer -> text($s);
			$s=fspace($invno,$t3)."\n\n";
			$printer -> text($s);
			$s=fspace($pn,$t3).$in."\n\n";
			$printer -> text($s);
			$s=fspace($row['pd'],$t3)."\n\n";
			$printer -> text($s);
			$s=fspace($row['qty'],$t3)."\n";
			$printer -> text($s);
			$s=addlines("",8);
			$printer -> text($s);
			$s=fspace($row['cname'].$row['cname1'],$t4)."\n";
			$printer -> text($s);
			$s=fspace($row['cadd1'],$t4)."\n";
			$printer -> text($s);
			$s=fspace($row['cadd2'],$t4)."\n";
			$printer -> text($s);
			$s=fspace($row['cadd3'],$t4)."\n";
			$printer -> text($s);
			$s=addlines("",1);
			$printer -> text($s);
			$s=fspace(leftalign($row['vc'],$t6),$t5*3).fspace(leftalign($rc,$t6*2),$t5*4).fspace(leftalign($fin,($t6*2)-3),$t6-2).$rowdate['date']."\n";
			$printer -> text($s);
			$s=addlines("",3);
			$printer -> text($s);
			$t1=35;
		}
		$c=0;
		while($l!=0)
		{
			$l--;
			if($c==0)
			{
				$s=leftalign($row3['sno'],$t5);
				$printer -> text($s);
			}
			else
			{
				$s=leftalign("",$t5);
				$printer -> text($s);
			}
			$s=leftalign(substr($row3['chars'],$c*$t6,$t6),$t6);
			$printer -> text($s);
			$s=leftalign(substr($row3['drawspec'],$c*$t7,$t7),$t7);
			$printer -> text(" ".$s);
			$s=leftalign(substr($row3['method'],$c*$t8,$t8),$t8);
			$printer -> text(" ".$s." ");
			if($c==0)
			{
				/*
				if($row3['s1']==0 && $row3['s2']==0 && $row3['s3']==0 && $row3['s4']==0 && $row3['s5']==0)
				{
					$s=leftalign($row3['textprint'],30);
					$printer -> text(" ".$s);
				}
				else
				{
					$s=rightalign($row3['s1'].$row3['unit'],5);
					$printer -> text($s);
					$s=rightalign($row3['s2'].$row3['unit'],7);
					$printer -> text($s);
					$s=rightalign($row3['s3'].$row3['unit'],7);
					$printer -> text($s);
					$s=rightalign($row3['s4'].$row3['unit'],7);
					$printer -> text($s);
					$s=rightalign($row3['s5'].$row3['unit'],6);
					$printer -> text($s);
				}
				*/
			}
			if($l==0)
			{
				$t1=$t1-2;
				$s=addlines("",2);
			}
			else
			{
				$t1=$t1-1;
				$s=addlines("",1);
			}
			$printer -> text($s);
			$c++;
		}
		
	}
	$s=addlines("",$t1+1);
	$printer -> text($s);
	$s=fspace($invno,$t3)."\n\n";
	$printer -> text($s);
	$s=fspace($pn,$t3).$in."\n\n";
	$printer -> text($s);
	$s=fspace($row['pd'],$t3)."\n\n";
	$printer -> text($s);
	$s=fspace($row['qty'],$t3)."\n";
	$printer -> text($s);
	$s=addlines("",8);
	$printer -> text($s);

$printer -> close();

header("location: inputlink.php");