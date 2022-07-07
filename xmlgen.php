<?php
$con=mysqli_connect('localhost','root','Tamil','mypcm');
if(!$con)
	die(mysqli_error());
$query = "select * from inv_det";
$result = $con->query($query);
$xml = new XMLWriter();

$xml->openURI("C://output.xml");
$xml->startDocument();
$xml->setIndent(true);

$xml->startElement('InvoiceDetails');

while($r = $result->fetch_assoc())
{
	$invno=$r['invno'];$invdt=$r['invdt'];$cname=$r['cname'].$r['cname1'];$dtname=$r['dtname'];$pn=$r['pn'];$pd=$r['pd'];
    $hsnc=$r['hsnc'];$cpono=$r['cpono'];$poino=$r['poino'];$rate=$r['rate'];$per=$r['per'];$qty=$r['qty'];$pc=$r['pc'];$pcamt=$r['pcamt'];
    $sgst=$r['sgst'];$cigst=$r['cigst'];$sgstamt=$r['sgstamt'];$cigstamt=$r['cigstamt'];$taxgoods=$r['taxgoods'];$invtotal=$r['invtotal'];
	$xml->startElement("Invoice");$xml->writeAttribute('invno', $invno);
		$xml->startElement("InvoiceNumber");$xml->text($invno);$xml->endElement();
		$xml->startElement("InvoiceDate");$xml->text($invdt);$xml->endElement();
		$xml->startElement("PartyName");$xml->text($cname);$xml->endElement();
		$xml->startElement("DeliveryPlace");$xml->text($dtname);$xml->endElement();
		$xml->startElement("PartNumber");$xml->text($pn);$xml->endElement();
		$xml->startElement("PartDescription");$xml->text($pd);$xml->endElement();
		$xml->startElement("HSNcode");$xml->text($hsnc);$xml->endElement();
		$xml->startElement("PONumber");$xml->text($cpono);$xml->endElement();
		$xml->startElement("LineNumber");$xml->text($poino);$xml->endElement();
		$xml->startElement("Rate");$xml->text($rate);$xml->endElement();
		$xml->startElement("Per");$xml->text($per);$xml->endElement();
		$xml->startElement("Quantity");$xml->text($qty);$xml->endElement();
		$xml->startElement("PackingCharge");$xml->text($pc);$xml->endElement();
		$xml->startElement("PackingChargeValue");$xml->text($pcamt);$xml->endElement();
		$xml->startElement("SGST");$xml->text($sgst);$xml->endElement();
		$xml->startElement("SGSTValue");$xml->text($sgstamt);$xml->endElement();
		$xml->startElement("CGST_IGST");$xml->text($cigst);$xml->endElement();
		$xml->startElement("CGST_IGSTValue");$xml->text($cigstamt);$xml->endElement();
		$xml->startElement("TaxableGoods");$xml->text($taxgoods);$xml->endElement();
		$xml->startElement("InvoiceAmount");$xml->text($invtotal);$xml->endElement();
	$xml->endElement();
}
$xml->endElement();
header('Content-type: text/xml encoding="UTF-8"');
$xml->flush(); 
?>