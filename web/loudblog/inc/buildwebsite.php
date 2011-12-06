<?php header("Content-type: text/html; charset=utf-8");

// ----------------------------------------------------- //
// LoudBlog                                              //
// easy-to-use audioblogging and podcasting              //
// Version 0.7.1 (2007-10-30)                            // 
// http://www.loudblog.com                               //
//                                                       //
// Written by Sebastian Steins (sebastian@loudblog.com)  //
// Based on the fabolous work of Gerrit van Aaken        //
//                                                       //
// Released under the Gnu General Public License         //
// http://www.gnu.org/copyleft/gpl.html                  //
//                                                       //
// Have Fun! Drop me a line if you like LoudBlog!        //
// ----------------------------------------------------- //

//start timer
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
$time_start = microtime_float();

//for developing we need some error messages
// error_reporting(E_ALL);
//but not for the end user...
error_reporting(0);

//include database stuff and functions
include "loudblog/custom/config.php";
include "loudblog/inc/database/adodb.inc.php";
include "loudblog/inc/connect.php";
include "loudblog/inc/functions.php";

//magic quotes are evil!
if (get_magic_quotes_gpc()) {
   $_GET   = array_map('stripslashes_deep', $_GET);
   $_POST  = array_map('stripslashes_deep', $_POST);
}

cleanmygets();

//create some important globals
if (!isset($db['host'])) { die("<br /><br />Cannot find a valid configuration file! <a href=\"install.php\">Install Loudblog now!</a>"); }
$GLOBALS['prefix'] = $db['pref'];
$GLOBALS['path'] = $lb_path;
$GLOBALS['audiopath'] = $lb_path . "/audio/";
$GLOBALS['uploadpath'] = $lb_path . "/upload/";
$GLOBALS['templatepath'] = $lb_path . "/loudblog/custom/templates/";

//getting basic data
$settings = getsettings();
dumpdata();

//get the language translation table
global $lang;
$lang = array();
@include_once($GLOBALS['path']."/loudblog/lang/".$settings['language'].".php");


// here comes bad behavior...
if (($settings['badbehavior']) == "1")  {
require_once $lb_path . '/loudblog/inc/bad_behavior.php';
}

//Ready to rock'n'roll? Let's start building the website!

//template required by URL? Override template-setting
if (isset($_GET['template'])) {
	$requested_template = killevilcharacters(strip_tags($_GET['template']));
    $settings['template'] = $requested_template;
}

//building the right path to required template
$templpath = $GLOBALS['templatepath'] . 
             $settings['template'] . "/index.html";

//copies template into variable
$connect = @fopen ($templpath, "rb") OR die("Unfortunately I could not find a valid template! $templpath");
$template = fread ($connect, 262144);
fclose($connect);

//includes official loudblog-tags
include "loudblog/inc/loudblogtags.php";

//includes plugins from plugins-folder
$folder = opendir('loudblog/custom/plugins'); 
while ($file = readdir($folder)) { 
    if (substr($file, -4, 4) == ".php") {
        include_once("loudblog/custom/plugins/" . $file);
    }
}

//this is special: at first we pretend that no "next page" is possible
global $nextpage;
$nextpage = false;

//Set random separator for enabling php code execution within template
global $phpseparator;
global $php_use;
$phpseparator = "|php".rand(1,99999)."php|";
$php_use = false; //in dubio contra php

$xmlparsing = false;

//new parsing method for PHP 5.2x
if ((version_compare(phpversion(), "5.2.0") >= 0) && (!ini_set('pcre.backtrack_limit', 1000000))) {
	include "loudblog/inc/parse_new.php";
} else {
	include "loudblog/inc/parse_old.php";
}

//show timer
$time_end = microtime_float();
$time = $time_end - $time_start;
echo "<!-- LoudBlog built this page in ".$time." seconds-->";

?>