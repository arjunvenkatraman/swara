<?php

////////////////// SOME INITIAL STUFF


//for developing we need some error messages
error_reporting(E_ALL);

//include database stuff and functions
include "custom/config.php";
include "inc/database/adodb.inc.php";
include "inc/connect.php";
include "inc/functions.php";

//magic quotes are evil!
if (get_magic_quotes_gpc()) {
   $_GET   = array_map('stripslashes_deep', $_GET);
   $_POST  = array_map('stripslashes_deep', $_POST);
}


//create some important globals
if (!isset($db['host'])) {
    die("<br /><br />Cannot find a valid configuration file! <a href=\"install.php\">Install Loudblog now!</a>");
}

$GLOBALS['prefix']     = $db['pref'];
$GLOBALS['path']       = $lb_path;
$GLOBALS['audiopath']  = $lb_path . "/audio/";
$GLOBALS['uploadpath'] = $lb_path . "/upload/";

//getting basic data
$settings = getsettings();
dumpdata();

//we want to logout? delete this session and the cookie!
if ((isset($_GET['do'])) AND ($_GET['do'] == 'logout')) {
	session_unset();
	session_destroy();
	setcookie('lbauth', '', time()+60*60*24*30);
	$_COOKIE['lbauth'] = '';
	#var_dump($_COOKIE);

//When we log out we also switch off the preview function
$dosql = "UPDATE ".$GLOBALS['prefix']."lb_settings SET
		       value  = '0'
           WHERE name = 'previews'";
           $GLOBALS['lbdata']->Execute($dosql);
}


////////////////// CHECK THE USER-LOGIN



$access = false;

//no login-information in session-vars or post-data? no access!!
if (!isset($_SESSION['nickname']) AND !isset($_POST['nickname']) AND !isset($_COOKIE['lbauth']))
    $access = false;

else {

    //fetch user-logins and passwords from database
    $dosql = "SELECT nickname, password FROM ".$GLOBALS['prefix']."lb_authors";
    $result = $GLOBALS['lbdata']->GetArray($dosql);

    //compare with the login-data from session and from post
    foreach ($result as $row) {
        if ((isset($_SESSION['nickname']))
        AND ($row['nickname'] == $_SESSION['nickname'])
        AND ($row['password'] == $_SESSION['password'] OR $row['password'] == md5($_SESSION['password']))
        AND ($_SERVER['REMOTE_ADDR'] == $_SESSION['ipnumber'])) {
        $access = true;
		}
        elseif (isset($_POST['nickname'])) {
            if ((isset($_POST['nickname']))
            AND ($row['nickname'] == $_POST['nickname'])
            AND ($row['password'] == md5($_POST['password'])))
            $access = true;
        }
		else {
			if (isset($_COOKIE['lbauth'])) {
				#var_dump($_COOKIE['lbauth']);
				
				$cookie_string  =  md5($row['nickname'] .':'.     $row['password']);
				$cookie_string2 =  md5($row['nickname'] .':'. md5($row['password']));
				
				#var_dump($cookie_string);
				
				if ($cookie_string == $_COOKIE['lbauth'] OR $cookie_string2 = $_COOKIE['lbauth']) {
					
					$_POST['nickname'] = $row['nickname'];
					$_POST['password'] = $row['password'];
					$access = true;
				}
			}
		}

    }
}

?>