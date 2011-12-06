<?php

// This is the second slowliest and second ugliest parsing method ever. Please do not take a closer look. Loudblog will be rewritten soon with an elegant and fast parsing method. Until then, you will have to use this one. I'm sorry.

//start parsing!
$parsedpage = fullparse(firstparse(hrefmagic($template)));

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


function firstparse ($string) {

//very first, we do the loop_postings, because we need some global data for other functions, aight?
$postparsed = parsepostings ($string);

//now we put the posting-parsing-results into the original string
if ((isset($postparsed[0])) AND ($postparsed[0] != false)) {
    foreach ($postparsed as $replace) {
        $string = str_replace($replace['origin'], $replace['parsed'], $string);
    }
}
return $string;
}


//--------------------------------------------------------


function fullparse ($string) {

//we have to look for container-tags and parse them.
$contparsed = parsecontainer ($string);

//now we put the container-parsing-results into the original string
if ((isset($contparsed[0])) AND ($contparsed[0] != false)) {
    foreach ($contparsed as $replace) {
        $string = str_replace($replace['origin'], $replace['parsed'], $string);
    }
}

//secondly, we have to look for single-tags and parse them, too.
$singleparsed = parsesingle ($string);

//now we put the single-parsing-results into the original template
if (isset($singleparsed[0])) {
    foreach ($singleparsed as $replace) {
        $string = str_replace($replace['origin'], $replace['parsed'], $string);
    }
}
return $string;
}


//--------------------------------------------------------------------
function parsepostings ($string) {
//search for postings-tags

$parsing = "";
$search = '|<(lb:loop_postings)[^>]*>.*?</\1>|s';
preg_match_all($search, $string, $matches);
$i = 0;
$parsing = false;
if (isset($matches[0])) {
    foreach ($matches[1] as $containertag) {
        $call = substr ($containertag, 3);
        $parsing[$i]['origin'] = $matches[0][$i];
        $parsing[$i]['parsed'] = call_user_func($call, $matches[0][$i]);
        $i +=1;
    } 
}
return $parsing;
}

//--------------------------------------------------------------------
function parsecontainer ($string) {
//search for container-tags

$parsing = "";
$search = '|<(lb:[_a-z][_a-z0-9]*)[^>]*>.*?</\1>|s';
preg_match_all($search, $string, $matches);
$i = 0;
$parsing = false;
if (isset($matches[0])) {
    foreach ($matches[1] as $containertag) {
        $call = substr ($containertag, 3);
        $parsing[$i]['origin'] = $matches[0][$i];
        $parsing[$i]['parsed'] = call_user_func($call, $matches[0][$i]);
        $i +=1;
    } 
}
return $parsing;
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
function parsesingle ($string) {
//search for single-tags

$parsing = "";
if ($string != "") {
    $search = '|<(lb:[_a-z][_a-z0-9]*)[^>]* />|s';
    preg_match_all($search, $string, $matches);
    $i = 0;
    $parsing = false;
    if (isset($matches[0])) {
        foreach ($matches[1] as $singletag) {
            $call = substr ($singletag, 3);
            $parsing[$i]['origin'] = $matches[0][$i];
            $parsing[$i]['parsed'] = call_user_func($call, $matches[0][$i]);
            $i +=1;
        }
    }
} 
return $parsing;
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