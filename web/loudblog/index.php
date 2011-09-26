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


// check if session_save_path is writable and test some other paths...
$session_save_path = session_save_path();
if (!is_writable($session_save_path)) {
	if (is_writable("/tmp/")) $session_save_path = "/tmp/";
	if (is_writable("modules/cgi-bin/")) $session_save_path = "modules/cgi-bin/";
	if (is_writable("../")) $session_save_path = "../";
	session_save_path($session_save_path);
}
session_start();


include "inc/accesscheck.php";

////////////////// WHICH CONTENT DO WE SHOW?

//get the language translation table
global $lang;
$lang = array();
@include_once($GLOBALS['path']."/loudblog/lang/".$settings['language'].".php");

//show the html-head which is always needed
#include "inc/head.php";

//show the login-screen if access is denied
if (!$access) {
	include "inc/head.php";
	include "inc/backend_login.php";
}

//do other things if access is granted
else {

    //write login-data into session-data (if needed)
    if (!isset($_SESSION['nickname'])) {
        session_register ('nickname');
        session_register ('password');
        session_register ('ipnumber');
        session_register ('authorid');
        $_SESSION['nickname'] = $_POST['nickname'];
        $_SESSION['authorid'] = getuserid($_POST['nickname']);
        // $_SESSION['password'] = md5($_POST['password']);
		$_SESSION['password'] = ($_POST['password']);
        $_SESSION['ipnumber'] = $_SERVER['REMOTE_ADDR'];
    }

	if (isset($_POST['remember_me']) AND $_POST['remember_me']==1 ) {
		$cookie_string =  md5($_POST['nickname'] .':'. $_POST['password']);
		setcookie('lbauth', $cookie_string, time()+60*60*24*30);
	}
	
	if (isset($_COOKIE['lbauth'])) {
		$cookie_string =  $_COOKIE['lbauth'];
		setcookie('lbauth', $cookie_string, time()+60*60*24*30);
	}

#var_dump($_POST['password']) ;echo ":"; var_dump($_SESSION['password']);
	include "inc/head.php";

    //no url request? show postings as default
    if (!isset($_GET['page'])) { $loadme = "inc/backend_postings.php"; }

    //build an include-path from the url-request
    else {
    $requested_page = killevilcharacters(strip_tags($_GET['page']));
    $loadme = "inc/backend_" . $requested_page . ".php";
    }
    
    //we don't want our users to run update scripts manually, do we?
    include "inc/autoupdate.php";

    //yee-hah! finally we do show real content on our page!
    include ($loadme);

}

include "inc/footer.php";

