<?php

// This is the slowliest and ugliest parsing method ever. Please do not take a closer look. Loudblog will be rewritten soon with an elegant and fast parsing method. Until then, you will have to use this one. I'm sorry.


//start parsing!
$parsedpage = de_lb_ize(fullparse(firstparse(hrefmagic($template))));

//do we have php code within our template? switch between echo and eval!
if ($php_use) {
	$templatepieces = explode ($phpseparator, $parsedpage);
	for ($i = 0; $i <= count($templatepieces); $i += 2) {
		echo $templatepieces[$i];
		if (isset($templatepieces[$i+1])) eval ($templatepieces[$i+1]);
	}
//no php code, no eval!
} else {
	echo $parsedpage;
}


//--------------------------------------------------------
function xml_ize ($string) {
return "<root>".$string."</root>";
}

//--------------------------------------------------------
function de_xml_ize ($string) {
$string = substr($string, 6);
$string = substr($string, 0, -7);

$trans = array("<![CDATA["=>"", "]]>"=>"");
$string = strtr ($string, $trans);

return $string;
}

//--------------------------------------------------------
function lb_ize ($string) {
    
$trans = array("<lb:"=>"[}#LB[}#", "</lb:"=>"[}#/LB[}#");
$string = strtr ($string, $trans);

$trans = array("<"=>"[}#HTML[}#", "</"=>"[}#/HTML[}#", "&"=>"[}#AMP]]]");
$string = strtr ($string, $trans);

$trans = array("[}#LB[}#"=>"<", "[}#/LB[}#"=>"</");
$string = strtr ($string, $trans);

return $string;
}

//--------------------------------------------------------
function de_lb_ize ($string) {
$trans = array("[}#LB[}#"=>"<", "[}#/LB[}#"=>"</");
$string = strtr ($string, array_flip($trans));

$trans = array("<"=>"[}#HTML[}#", "</"=>"[}#/HTML[}#", "&"=>"[}#AMP]]]");
$string = strtr ($string, array_flip($trans));

$trans = array("<lb:"=>"[}#LB[}#", "</lb:"=>"[}#/LB[}#");
$string = strtr ($string, array_flip($trans));

$trans = array("&gt;"=>">", "&lt;"=>"<");
$string = strtr ($string, $trans);

return $string;
}

//--------------------------------------------------------


function firstparse ($fullhtml) {
//parses only the loop_postings, because we need some global data for other functions, aight?

$fullhtml = lb_ize (trim($fullhtml));

$fulldom = new DOMDocument();
$fulldom->loadXML(xml_ize($fullhtml));

$myloops = $fulldom->getElementsByTagName('loop_postings');

if ($myloops->length > 0) {
    foreach ($myloops as $myloop) {
    	$newloopstring = loop_postings($fulldom->saveXML($myloop));
        $newloop = $fulldom->createCdataSection($newloopstring);
        $myloop->parentNode->replaceChild($newloop, $myloop); 
    } 
}
return de_xml_ize($fulldom->saveXML($fulldom->firstChild));
}


//--------------------------------------------------------

function fullparse ($string) {

$dom = new DOMDocument();
$dom->loadXML(xml_ize($string));

$allelems = $dom->documentElement->childNodes;

$myelems = array();
$i = 0;

foreach ($allelems as $elem) {
	if ($elem->nodeName != "#text") {
		$myelems[$i] = $elem;
		$i += 1;
	}
}

if ($i > 0) {
    foreach ($myelems as $myelem) {
		$call = $myelem->nodeName;
		$newstring = call_user_func($call, $dom->saveXML($myelem));
		if ($newstring != "") {
			$newelem = $dom->createCdataSection(lb_ize($newstring));
			$myelem->parentNode->replaceChild($newelem, $myelem); 			
		} else {
			$myelem->parentNode->removeChild($myelem);
		}
    }
}
return de_xml_ize($dom->saveXML($dom->firstChild));
}


//--------------------------------------------------------------------
function stripcontainer ($string) {
//put those "<lb:something>content</lb:something>" tags to trash
if ($string != "") {

    $start = strpos ($string,">") + 1;
    $length= strrpos($string,"<") - strlen($string);
    $string = substr ($string, $start, $length);
}
return $string;
}

//--------------------------------------------------------------------
function getattributes ($string) {
//takes the whole loudblog-tag and returns the attributes as array

$att = array();
if ($string != "") {
    $string = substr($string, 0, strpos($string, ">"));
    $fragments = explode('"', strstr($string, " "));
    for ($i = 0; $i < count($fragments)-1; $i+=2) {
        $att[substr(trim($fragments[$i]), 0, -1)] = $fragments[$i+1];
    } 
}
return $att;
}

//--------------------------------------------------------------------
function hrefmagic ($string) {
//takes all relative href-links and src-links and forward to template-location

$return = false;
if ($string != "") {
    global $settings;
	$search = '#(href|src)=["\']([^/][^:"\']*)["\']#';
	$replace= '$1="loudblog/custom/templates/'.$settings['template'].'/$2"';
    $return = preg_replace ($search, $replace, $string);
}
return $return;
}

?>