<?php

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

$GLOBALS['prefix'    ] = $db['pref'];
$GLOBALS['path']       = $lb_path;
$GLOBALS['audiopath']  = $lb_path . "/audio/";
$GLOBALS['uploadpath'] = $lb_path . "/upload/";

//getting basic data
$settings = getsettings();
dumpdata();

//do the pinging action!!
if ($settings['ping'] == "1") {

	include ('inc/IXR_Library.php');
	
	$pingname = $settings['sitename'];
	$pingfeed = $settings['url']."/podcast.php";
	$pingurl  = $settings['url'];
		
	$tec_client = new IXR_Client('http://rpc.technorati.com/rpc/ping');
	if (!$tec_client->query('weblogUpdates.ping', $pingname, $pingurl)) {
		echo $tec_client->getErrorMessage();
	}
	
	$ode_client = new IXR_Client('http://odeo.com/api/xmlrpc/1.0/ping');
	if (!$ode_client->query('rssUpdate', $pingname, $pingurl)) {
		echo $ode_client->getErrorMessage();
	}
	
	$awl_client = new IXR_Client('http://audiorpc.weblogs.com/RPC2');
	if (!$awl_client->query('audioUpdate', $pingname, $pingurl)) {
		echo $awl_client->getErrorMessage();
	}
	
	$pom_client = new IXR_Client('http://rpc.pingomatic.com/');
	if (!$pom_client->query('weblogUpdates.ping', $pingname, $pingurl, "", $pingfeed)) {
		echo $pom_client->getErrorMessage();
	}

}

echo '<script type="text/javascript">parent.close();</script>';


?>