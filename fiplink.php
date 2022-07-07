<?php
$invno=$_POST['from'];
$fi=$_POST['fi'];
$rc=$_POST['rc'];
$pt=$_POST['ptype'];
if($pt=="VSS_REPORT")
{
	header("location: fip.php?from=$invno&fi=$fi&rc=$rc");
}
if($pt=="GILBARCO")
{
	
	//header("location: gfip.php?from=$invno&fi=$fi&rc=$rc");
}
if($pt=="MULTITECH")
{
	//header("location: mfip.php?from=$invno&fi=$fi&rc=$rc");
}
if($pt=="BOSCH")
{
	//header("location: bfip.php?from=$invno&fi=$fi&rc=$rc");
}
?>