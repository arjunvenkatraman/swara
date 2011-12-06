<?php

//we are using ADOdb for abstracting our database accesses!

//does a valid config file exist?
if (!isset($db['type']) OR ($db['type'] == "")) {
die("<br /><br />Cannot find a valid configuration file. <a href=\"install.php\">Install Loudblog now!</a>");
}

//make a global object for gaining data from database
$GLOBALS['lbdata'] = &ADONewConnection($db['type']);

//connect to the database 
if ($db['type'] == "sqlite") {
    $GLOBALS['lbdata']->Connect($lb_path."/audio/loudblogdata.db");
} else {
    $GLOBALS['lbdata']->Connect($db['host'], $db['user'], $db['pass'], $db['data']);
}

//we want only associated arrays in our query-results!
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

?>