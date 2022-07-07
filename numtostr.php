<?php
//addlines(no of lines)
//fspace(string,no of spaces)->output-> ex:Srini,5 ->     srini.
//bspace(string,no of spaces)->output-> ex:Srini,5 ->srini     .
//leftalign(string,no of char of that value) - > ex : -> srini,10 -> [srini     ]
//rightalign(string,no of char of that value) - > ex : -> srini,10 -> [     srini]
function numtostrfn($number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    //$paise = ($decimal) ? "and" ." paise " . ($words[$decimal / 10] ." ". $words[$decimal % 10]) : '';
    return ($Rupees ? $Rupees:'');
}
function addlines($t,$tt)
{
	$txt="";if($tt>0){while($tt>0){$txt=$txt."\n";$tt=$tt-1;}}return $txt;
}
function fspace($str,$t)
{
	$txt="";if($t>0){while($t!=0){$txt=$txt." ";$t=$t-1;}}return $txt.$str;
}
function bspace($str,$t)
{
	$txt="";if($t>0){while($t!=0){$txt=$txt." ";$t=$t-1;}}return $str.$txt;
}
function leftalign($str,$t)
{
	$txt="";$l=strlen($str);$l=$t-$l;if($l>0){while($l!=0){$txt=$txt." ";$l=$l-1;}}else if($l<0){$str=substr($str,0,$t);}return $str.$txt;
}
function rightalign($str,$t)
{
	$txt="";$l=strlen($str);$l=$t-$l;if($l>0){while($l!=0){$txt=$txt." ";$l=$l-1;}}return $txt.$str;
}
?>