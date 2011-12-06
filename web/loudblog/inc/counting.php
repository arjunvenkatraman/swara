<?php

//This download counter is heavily based on 
//Simple Download Counter 1.0 from Drew Phillips
//Thank you, man. I modified your script to suit my need.
//Hope that's okay for you.

//Security advice by Lars H. Strojny


//include database stuff and functions
require_once 'loudblog/custom/config.php';
require_once 'loudblog/inc/database/adodb.inc.php';
require_once 'loudblog/inc/connect.php';
require_once 'loudblog/inc/functions.php';

//create some important globals
if (!isset($db['host'])) { die("<br /><br />Cannot find a valid configuration file! <a href=\"install.php\">Install Loudblog now!</a>"); }
$GLOBALS['prefix'] = $db['pref'];
$settings = getsettings();

$legal_methods = array ('fla', 'pod', 'web');

foreach ($_GET as $themethod => $thefile) {
	if (in_array($themethod, $legal_methods)) {
    	$method = "count".$themethod; 
    	$file = $thefile;
    } else { die('Hack attempt!'); }
}

//apache rewrite kills the double slashes of http:// let's undo this!
$file = preg_replace ("#^([a-z]{3,5})\:/([^/])#i", "$1://$2", $file);
$file = strip_tags ($file);


//No counting if we have only a head request! (Thanks to Michael Roedhamer)
if (strtolower($_SERVER['REQUEST_METHOD']) <> "head") {
	//do the counting action
	$dosql = "UPDATE ".$GLOBALS['prefix']."lb_postings 
				SET countall = countall + 1, 
				".$method." = ".$method." + 1 
				WHERE audio_file = '".urldecode($file)."'";
	$GLOBALS['lbdata']->Execute($dosql);
}


if(isset($method)) {
	if(empty($file)) {
		die ("No File Specified");
			}
	if(strpos($file, "..") !== false) {
		die ("Hack attempt!");
	}
	
	//local or remote file?
	if(strpos($file, "://") !== false) {
        $before = "";
	} else {
        $before = $settings['url']."/audio/";
    }


//fight http response splitting!
$file = str_replace ("\n", '', $file);
	
//redirect us to the real location of the audio file
header("Location: ".$before.$file, FALSE, 302);
}

?>

