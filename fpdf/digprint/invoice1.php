<?php
require('C:\xampp\htdocs\tapcm\fpdf\fpdf.php');
define('EURO', chr(128) );
define('EURO_VAL', 6.55957 );

// Xavier Nicolay 2004
// Version 1.02

//////////////////////////////////////
// Public functions                 //
//////////////////////////////////////
//  function sizeOfText( $texte, $larg )
//  function addSociete( $nom, $adresse )
//  function fact_dev( $libelle, $num )
//  function addDevis( $numdev )
//  function addFacture( $numfact )
//  function addDate( $date )
//  function addClient( $ref )
//  function addPageNumber( $page )
//  function addClientAdresse( $adresse )
//  function addReglement( $mode )
//  function addEcheance( $date )
//  function addNumTVA($tva)
//  function addReference($ref)
//  function addCols( $tab )
//  function addLineFormat( $tab )
//  function lineVert( $tab )
//  function addLine( $ligne, $tab )
//  function addRemarque($remarque)
//  function addCadreTVAs()
//  function addCadreEurosFrancs()
//  function addTVAs( $params, $tab_tva, $invoice )
//  function temporaire( $texte )

class PDF_Invoice extends FPDF
{
// private variables
var $colonnes;
var $format;
var $angle=0;
function Header()
{
	if ($this->page == 1)
	{
		//$this->Image('../imagens/logos/1.jpg', 10, 6);
		// Arial bold 15
		$this->SetFont('Arial','B',10);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(101,0,'Original Invoice', 0, 0,'R');
		// Line break
		$this->Ln(40);
	}
	if ($this->page == 2)
	{
		//$this->Image('../imagens/logos/1.jpg', 10, 6);
		// Arial bold 15
		$this->SetFont('Arial','B',10);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(101,0,'Transport Copy', 0, 0,'R');
		// Line break
		$this->Ln(40);
	}
	if ($this->page == 3)
	{
		//$this->Image('../imagens/logos/1.jpg', 10, 6);
		// Arial bold 15
		$this->SetFont('Arial','B',10);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(101,0,'Extra Copy', 0, 0,'R');
		// Line break
		$this->Ln(40);
	}
	if ($this->page == 4)
	{
		//$this->Image('../imagens/logos/1.jpg', 10, 6);
		// Arial bold 15
		$this->SetFont('Arial','B',10);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(101,0,'Accounts Copy', 0, 0,'R');
		// Line break
		$this->Ln(40);
	}
	  
}
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/4',0,0,'C');
}
// private functions
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' || $style=='DF')
        $op='B';
    else
        $op='S';
    $MyArc = 4/3 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
    $xc = $x+$w-$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

    $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
    $xc = $x+$w-$r ;
    $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
    $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
    $xc = $x+$r ;
    $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
    $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
    $xc = $x+$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
    $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
    $this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
    $h = $this->h;
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
                        $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
}

function Rotate($angle, $x=-1, $y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}

function _endpage()
{
    if($this->angle!=0)
    {
        $this->angle=0;
        $this->_out('Q');
    }
    parent::_endpage();
}

// public functions
function sizeOfText( $texte, $largeur )
{
    $index    = 0;
    $nb_lines = 0;
    $loop     = TRUE;
    while ( $loop )
    {
        $pos = strpos($texte, "\n");
        if (!$pos)
        {
            $loop  = FALSE;
            $ligne = $texte;
        }
        else
        {
            $ligne  = substr( $texte, $index, $pos);
            $texte = substr( $texte, $pos+1 );
        }
        $length = floor( $this->GetStringWidth( $ligne ) );
        $res = 1 + floor( $length / $largeur) ;
        $nb_lines += $res;
    }
    return $nb_lines;
}

// Company
function COMPANYDETAIL( $nom, $adresse )
{
    $x1 = 30;
    $y1 = 20;
    //Positionnement en bas
    $this->SetXY( $x1, $y1 );
    $this->SetFont('Arial','B',12);
    $length = $this->GetStringWidth( $nom );
    $this->Cell( $length, 2, $nom);
    $this->SetXY( $x1, $y1 + 4 );
    $this->SetFont('Arial','',10);
    $length = $this->GetStringWidth( $adresse );
    //Coordonnées de la société
    $lignes = $this->sizeOfText( $adresse, $length) ;
    $this->MultiCell($length, 4, $adresse);
}

function DELIVERYADDRESS( $nom, $adresse )
{
    $x1 = 10;
    $y1 = 70;
    //Positionnement en bas
    $this->SetXY( $x1, $y1 );
    $this->SetFont('Arial','B',8);
    $length = $this->GetStringWidth( "DELIVERY ADDRESS : " );
	$this->Cell( $length, 2, "DELIVERY ADDRESS : ");
	$this->SetXY( $x1, $y1 + 4 );
    $length = $this->GetStringWidth( $nom );
    $this->Cell( $length, 2, $nom);
    $this->SetXY( $x1, $y1 + 7 );
    $this->SetFont('Arial','',8);
    $length = $this->GetStringWidth( $adresse );
    //Coordonnées de la société
    $lignes = $this->sizeOfText( $adresse, $length) ;
    $this->MultiCell($length, 4, $adresse);
}


function SHIPPINGADDRESS( $nom, $adresse )
{
    $x1 = 110;
    $y1 = 70;
    //Positionnement en bas
    $this->SetXY( $x1, $y1 );
    $this->SetFont('Arial','',8);
    $length = $this->GetStringWidth( "SHIPPING ADDRESS : " );
	$this->Cell( $length, 2, "SHIPPING ADDRESS : ");
	$this->SetXY( $x1, $y1 + 4 );
    $this->SetFont('Arial','B',8);
	$length = $this->GetStringWidth( $nom );
    $this->Cell( $length, 2, $nom);
    $this->SetXY( $x1, $y1 + 7 );
    $this->SetFont('Arial','',8);
    $length = $this->GetStringWidth( $adresse );
    //Coordonnées de la société
    $lignes = $this->sizeOfText( $adresse, $length) ;
    $this->MultiCell($length, 4, $adresse);
}

// Label and number of invoice/estimate
function fact_dev( $libelle, $num )
{
    $r1  = $this->w - 125;
    $r2  = $r1 + 45;
    $y1  = 6;
    $y2  = $y1 + 2;
    $mid = ($r1 + $r2 ) / 2;
    
    $texte  = $libelle.$num;    
    $szfont = 12;
    $loop   = 0;
    
    while ( $loop == 0 )
    {
       $this->SetFont( "Arial", "B", $szfont );
       $sz = $this->GetStringWidth( $texte );
       if ( ($r1+$sz) > $r2 )
          $szfont --;
       else
          $loop ++;
    }

    $this->SetLineWidth(0.1);
    $this->SetFillColor(192);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 2.5, 'DF');
    $this->SetXY( $r1+1, $y1+2);
    $this->Cell($r2-$r1 -1,5, $texte, 0, 0, "C" );
}

// Estimate
function addDevis( $numdev )
{
    $string = sprintf("",$numdev);
    $this->fact_dev( "", $string );
}

// Invoice
function addFacture( $numfact )
{
    $string = sprintf("",$numfact);
    $this->fact_dev( "", $string );
}

function addDate( $date )
{
	$PNG_WEB_DIR = 'qr/temp/';
    $r1  = $this->w - 61;
    $r2  = $r1 + 30;
    $y1  = 17;
    $y2  = $y1 ;
    $mid = $y1 + ($y2 / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2+10, 3.5, 'D');
    //$this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
    $this->SetFont( "Arial", "B", 7);
	$this->Cell( 40, 40, $pdf->Image($pdf->Image($PNG_WEB_DIR.basename($date), $pdf->GetX(), $pdf->GetY(), 33.78), 0, 0, 'L', false ));
    //$this->Cell(10,5, ',10,10,-300), 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+9 );
    $this->SetFont( "Arial", "", 10);
    //$this->Cell(10,5,$date, 0,0, "C");
}

function addClient( $ref )
{
    $r1  = $this->w - 31;
    $r2  = $r1 + 19;
    $y1  = 17;
    $y2  = $y1;
    $mid = $y1 + ($y2 / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,5, "CLIENT", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$ref, 0,0, "C");
}

function addPageNumber( $page )
{
    $r1  = $this->w - 80;
    $r2  = $r1 + 19;
    $y1  = 17;
    $y2  = $y1;
    $mid = $y1 + ($y2 / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,5, "PAGE", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$page, 0,0, "C");
}

// Client address
function addClientAdresse( $adresse )
{
    $r1     = $this->w - 100;
    $r2     = $r1 + 68;
    $y1     = 65;
    $this->SetXY( $r1, $y1);
    $this->MultiCell( 60, 4, $adresse);
}

// Mode of payment
function PONUMBER( $mode )
{
    $r1  = 10;
    $r2  = $r1 + 30;
    $y1  = 55;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1+1 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,4, "PO NUMBER", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$mode, 0,0, "C");
}

// Expiry date
function PODATE( $date )
{
    $r1  = 45;
    $r2  = $r1 + 30;
    $y1  = 55;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2 - $r1)/2 - 5 , $y1+1 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,4, "PO DATE", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5 , $y1 + 5 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$date, 0,0, "C");
}

// VAT number
function LINENO($tva)
{
    $this->SetFont( "Arial", "B", 10);
    $r1  = $this->w - 130;
    $r2  = $r1 + 30;
    $y1  = 55;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + 3 , $y1+1 );
    $this->Cell(25, 4, "LINE NO", '', '', "C");
    $this->SetFont( "Arial", "", 10);
    $this->SetXY( $r1 + 2 , $y1+5 );
    $this->Cell(25, 5, $tva, '', '', "C");
}


// Mode of payment
function INVOICENUMBER( $mode )
{
    $r1  = 10;
    $r2  = $r1 + 40;
    $y1  = 40;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1+1 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,4, "INVOICE NUMBER", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$mode, 0,0, "C");
}

//FOR PACKING LIST
function PACKINGINVOICENUMBER( $mode )
{
    $r1  = 10;
    $r2  = $r1 + 40;
    $y1  = 20;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1+1 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,4, "INVOICE NUMBER", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$mode, 0,0, "C");
}

// Expiry date
function INVOICEDATE( $date )
{
    $r1  = 55;
    $r2  = $r1 + 45;
    $y1  = 40;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2 - $r1)/2 - 5 , $y1+1 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,4, "INVOICE DATE / TIME", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5 , $y1 + 5 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$date, 0,0, "C");
}

// VAT number
function VENDORCODE($tva)
{
    $this->SetFont( "Arial", "B", 10);
    $r1  = $this->w - 105;
    $r2  = $r1 + 45;
    $y1  = 40;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + 3 , $y1+1 );
    $this->Cell(40, 4, "VENDOR CODE", '', '', "C");
    $this->SetFont( "Arial", "", 10);
    $this->SetXY( $r1 + 2 , $y1+5 );
    $this->Cell(40, 5, $tva, '', '', "C");
}

// VAT number DUPLICATE
function EWAY($tva)
{
    $this->SetFont( "Arial", "B", 10);
    $r1  = $this->w - 95;
    $r2  = $r1 + 35;
    $y1  = 55;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + 6 , $y1+1 );
    $this->Cell(30, 4, "EWAY BILL", '', '', "L");
    $this->SetFont( "Arial", "", 10);
    $this->SetXY( $r1 + 3 , $y1+5 );
    $this->Cell(30, 5, $tva, '', '', "C");
}

function PANNO($tva)
{
    $this->SetFont( "Arial", "B", 10);
    $r1  = $this->w - 55;
    $r2  = $r1 + 35;
    $y1  = 55;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + 8 , $y1+1 );
    $this->Cell(30, 4, "PAN NO", '', '', "L");
    $this->SetFont( "Arial", "", 10);
    $this->SetXY( $r1 + 1 , $y1+5 );
    $this->Cell(30, 5, $tva, '', '', "C");
}


////////////////////////////////////////
// Mode of payment
function TRANSMODE( $mode )
{
    $r1  = 10;
    $r2  = $r1 + 40;
    $y1  = 230;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1+1 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,4, "TRANS MODE", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$mode, 0,0, "C");
}

// Expiry date
function DISTANCE( $date )
{
    $r1  = 55;
    $r2  = $r1 + 45;
    $y1  = 230;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2 - $r1)/2 - 5 , $y1+1 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,4, "DISTANCE (Kms)", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5 , $y1 + 5 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$date, 0,0, "C");
}

// VAT number
function TRANSPORTS($tva)
{
    $this->SetFont( "Arial", "B", 10);
    $r1  = $this->w - 105;
    $r2  = $r1 + 45;
    $y1  = 230;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + 3 , $y1+1 );
    $this->Cell(40, 4, "TRANSPORT NAME", '', '', "C");
    $this->SetFont( "Arial", "", 10);
    $this->SetXY( $r1 + 2 , $y1+5 );
    $this->Cell(40, 5, $tva, '', '', "C");
}

// VAT number DUPLICATE
function VEHICLE($tva)
{
    $this->SetFont( "Arial", "B", 10);
    $r1  = $this->w - 55;
    $r2  = $r1 + 45;
    $y1  = 230;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + 12 , $y1+1 );
    $this->Cell(40, 4, "VEHICLE NO", '', '', "L");
    $this->SetFont( "Arial", "", 10);
    $this->SetXY( $r1 + 3 , $y1+5 );
    $this->Cell(40, 5, $tva, '', '', "C");
}
















//////////////////////////////////////////
function addReference($ref)
{
    $this->SetFont( "Arial", "", 10);
    $length = $this->GetStringWidth( "Références : " . $ref );
    $r1  = 10;
    $r2  = $r1 + $length;
    $y1  = 92;
    $y2  = $y1+5;
    $this->SetXY( $r1 , $y1 );
    $this->Cell($length,4, "Références : " . $ref);
}

function addCols( $tab )
{
    global $colonnes;
    $this->SetFont( "Arial", "B", 8);
    $r1  = 10;
    $r2  = $this->w - ($r1 * 2) ;
    $y1  = 95;
    $y2  = $this->h - 90 - $y1;
    $this->SetXY( $r1, $y1 );
    $this->Rect( $r1, $y1, $r2, $y2, "D");
    $this->Line( $r1, $y1+6, $r1+$r2, $y1+6);
    $colX = $r1;
    $colonnes = $tab;
    while ( list( $lib, $pos ) = each ($tab) )
    {
        $this->SetXY( $colX, $y1+2 );
        $this->Cell( $pos, 1, $lib, 0, 0, "C");
        $colX += $pos;
        $this->Line( $colX, $y1, $colX, $y1+$y2);
    }
}

function addLineFormat( $tab )
{
    global $format, $colonnes;
    $this->SetFont( "Arial", "", 8);
    while ( list( $lib, $pos ) = each ($colonnes) )
    {
        if ( isset( $tab["$lib"] ) )
            $format[ $lib ] = $tab["$lib"];
    }
}

function lineVert( $tab )
{
    global $colonnes;
    reset( $colonnes );
    $maxSize=0;
    while ( list( $lib, $pos ) = each ($colonnes) )
    {
        $texte = $tab[ $lib ];
        $longCell  = $pos -2;
        $size = $this->sizeOfText( $texte, $longCell );
        if ($size > $maxSize)
            $maxSize = $size;
    }
    return $maxSize;
}

// add a line to the invoice/estimate
/*    $ligne = array( "REFERENCE"    => $prod["ref"],
                      "DESIGNATION"  => $libelle,
                      "QUANTITE"     => sprintf( "%.2F", $prod["qte"]) ,
                      "P.U. HT"      => sprintf( "%.2F", $prod["px_unit"]),
                      "MONTANT H.T." => sprintf ( "%.2F", $prod["qte"] * $prod["px_unit"]) ,
                      "TVA"          => $prod["tva"] );
*/
function addLine( $ligne, $tab )
{
    global $colonnes, $format;

    $ordonnee     = 10;
    $maxSize      = $ligne;

    reset( $colonnes );
    while ( list( $lib, $pos ) = each ($colonnes) )
    {
        $longCell  = $pos -2;
        $texte     = $tab[ $lib ];
        $length    = $this->GetStringWidth( $texte );
        $tailleTexte = $this->sizeOfText( $texte, $length );
        $formText  = $format[ $lib ];
        $this->SetXY( $ordonnee, $ligne-1);
        $this->MultiCell( $longCell, 4 , $texte, 0, $formText);
        if ( $maxSize < ($this->GetY()  ) )
            $maxSize = $this->GetY() ;
        $ordonnee += $pos;
    }
    return ( $maxSize - $ligne );
}

function addRemarque($remarque)
{
    $this->SetFont( "Arial", "", 10);
    $length = $this->GetStringWidth( "Remarque : " . $remarque );
    $r1  = 10;
    $r2  = $r1 + $length;
    $y1  = $this->h - 45.5;
    $y2  = $y1+5;
    $this->SetXY( $r1 , $y1 );
    //$this->Cell($length,4, "Remarque : " . $remarque);
}

function TABLE2($inw,$inw1,$v1,$v2,$v3,$v4,$v5,$tcs,$date)
{
	$sgst=$v3;
	$igst=$v2;
	$cgst=$v2;
	
	if($sgst==0)
	{
		$cgst="";
		$sgst="";
	}
	else{
		$igst="";
	}
	
    $r1  = 10;
    $r2  = $r1 + 190;
    $y1  = $this->h - 88.5;
    $y2  = $y1+20;
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 1, 'D');
    $this->Line( $r1, $y1+8, $r2, $y1+8);
    //$this->Line( $r1, $y1+15, $r2, $y1+15);
    //$this->Line( $r1, $y1+21, $r2, $y1+21);
	//$this->Line( $r1+5,  $y1+4, $r1+5, $y2); // avant BASES HT
    //$this->Line( $r1+27, $y1, $r1+27, $y2);  // avant REMISE
    /*$this->Line( $r1+116, $y1, $r1+116, $y2);  // avant MT TVA
    $this->Line( $r1+146, $y1, $r1+146, $y2);  // avant % TVA
    $this->Line( $r1+168, $y1, $r1+168, $y2-12);  // avant PORT*/
	$this->Line( $r1+97, $y1, $r1+97, $y2-12);
	$this->Line( $r1+127, $y1, $r1+127, $y2-12);	
	$this->Line( $r1+148, $y1, $r1+148, $y2);
    $this->Line( $r1+169, $y1, $r1+169, $y2-12);  
    //$this->Line( $r1+169, $y1, $r1+169, $y2-22);  
    //$this->Line( $r1+91, $y1, $r1+91, $y2);  // avant TOTAUX
    $this->SetFont( "Arial", "", 7);
	$this->SetXY( $r1+1, $y1);
    $this->Cell(10,4, " Rupees In Words : ".$inw);
	$this->SetX( $r1+29 );
	$this->SetXY( $r1+25, $y1+3);
    $this->Cell(10,4, $inw1);
    $this->SetX( $r1+50 );
	$this->SetFont( "Arial", "B", 8);
	//T VALUE
	$this->SetXY( $r1+110, $y1+2);
    $this->Cell(10,4, $v1);
    $this->SetX( $r1+29 );
	//IGST VALUE
	$this->SetXY( $r1+130, $y1+2);
    $this->Cell(10,4, $igst);
    $this->SetX( $r1+29 );
	//CG iG VALUE T
	$this->SetXY( $r1+151, $y1+2);
    $this->Cell(10,4, $cgst);
    $this->SetX( $r1+29 );
	//SG VALUE T
	$this->SetXY( $r1+173, $y1+2);
    $this->Cell(10,4, $sgst);
    $this->SetX( $r1+29 );
	
	//TOTAL
	/*
	$this->SetFont( "Arial", "", 9);
	$this->SetXY( $r1+1, $y1+10);
    $this->Cell(10,4, " TOTAL : ");
	$this->SetX( $r1+50 );
	$this->SetFont( "Arial", "B", 9);
	$this->SetXY( $r1+160 , $y1+10);
	$this->Cell(10,4, $v5);
    $this->SetX( $r1+10 );
	
	//TCS
	
	if($date<="2021-03-31"){
		$tcspercentage = "0.075%";
	}else{
		$tcspercentage = "0.1%";
	}
	
	$this->SetFont( "Arial", "", 9);
	$this->SetXY( $r1+1, $y1+16);
    $this->Cell(10,4, " TAX COLLECTED AT SOURCE @ $tcspercentage : ");
	$this->SetX( $r1+50 );
	$this->SetFont( "Arial", "B", 9);
	$this->SetXY( $r1+164 , $y1+16);
	$this->Cell(10,4, $tcs);
    $this->SetX( $r1+10 );
	
	*/
	
	//Declaration
	$this->SetFont( "Arial", "", 10);
	$this->SetXY( $r1, $y1+9);
    $this->Cell(10,4, " Declaration : We declare that this invoice shows the actual price of the ");
    $this->SetX( $r1+50 );
	$this->SetXY( $r1, $y1+14);
    $this->Cell(10,4, " goods and that all particulars are true and correct.");
    $this->SetX( $r1+50 );
	$this->SetFont( "Arial", "B", 10);
	$this->SetXY( $r1+117 , $y1+11);
	$this->Line( $r1+117, $y1+8, $r1+117, $y2);
    $this->Cell(10,4, " TOTAL VALUE ");
    $this->SetX( $r1+50 );
	$this->SetXY( $r1+165 , $y1+11);
    $this->Cell(10,4, $v4);
    $this->SetX( $r1+10 );
    /*$this->Cell(10,4, "MT TVA");
    $this->SetX( $r1+63 );
    $this->Cell(10,4, "% TVA");
    $this->SetX( $r1+78 );
    $this->Cell(10,4, "PORT");
    $this->SetX( $r1+100 );
    $this->Cell(10,4, "TOTAUX");
    $this->SetFont( "Arial", "B", 6);
    $this->SetXY( $r1+93, $y2 - 8 );
    $this->Cell(6,0, "H.T.   :");
    $this->SetXY( $r1+93, $y2 - 3 );
    $this->Cell(6,0, "T.V.A. :");
	*/
}

function AUTHSIGN()
{
    $r1  = $this->w - 70;
    $r2  = $r1 + 60;
    $y1  = $this->h - 45;
    $y2  = $y1+20;
    $this->RoundedRect($r1, $y1-10, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    //$this->Line( $r1+20,  $y1, $r1+20, $y2); // avant EUROS
    $this->Line( $r1, $y1-5, $r2, $y1-5); // Sous Euros & Francs
    //$this->Line( $r1+38,  $y1, $r1+38, $y2); // Entre Euros & Francs
    $this->SetFont( "Arial", "B", 8);
    $this->SetXY( $r1+20, $y1-9 );
    $this->Cell(20,4, "Authorised signatory( FOR VSSIPL)", 0, 0, "C");
}
function REMARK($rm1,$rm2,$rm3,$rm4,$rm5)
{
    $r1  = $this->w - 200;
    $r2  = $r1 + 125;
    $y1  = $this->h - 45;
    $y2  = $y1+20;
	$this->RoundedRect($r1, $y1-10, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    //$this->Line( $r1+20,  $y1, $r1+20, $y2); // avant EUROS
    $this->Line( $r1, $y1-5, $r2, $y1-5); // Sous Euros & Francs
    //$this->Line( $r1+38,  $y1, $r1+38, $y2); // Entre Euros & Francs
    $this->SetFont( "Arial", "B", 10);
    $this->SetXY( $r1+50, $y1-9.5 );
    $this->Cell(20,4, "Remarks : ( If Any )", 0, 0, "C");
	
	$rmstr=$rm1." ".$rm2." ".$rm3." ".$rm4." ".$rm5;
	
	
	
	$this->SetFont( "Arial", "", 7);
    $this->SetXY( 60, $y1-4 );
    $this->Cell(20,4, substr($rmstr,0,75)."-", 0, 0, "C");
	
	$this->SetFont( "Arial", "", 7);
    $this->SetXY( 50, $y2-20 );
    $this->Cell(20,4, substr($rmstr,76,150)."-", 0, 0, "C"); 
	
	
}

function temporaire( $texte )
{
    $this->SetFont('Arial','B',50);
    $this->SetTextColor(203,203,203);
    $this->Rotate(30,55,190);
    $this->Text(70,190,$texte);
    $this->Rotate(0);
    $this->SetTextColor(0,0,0);
}

}
?>