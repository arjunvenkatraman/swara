<?php header("Content-type: text/html; charset=utf-8");session_start();

include "inc/accesscheck.php";

if (isset($_GET['test'])) {
	var_dump(gettaglist(false, $_GET['test']));
}

//show the login-screen if access is denied
if ($access) {

	$p = $_POST;
	
	//which table are we in?
	$table = $GLOBALS['prefix'].'lb_';
	$tables = array (
		"authors"=>"authors", 
		"categories"=>"categories", 
		"comments"=>"comments", 
		"links"=>"links", 
		"postings"=>"postings", 
		"settings"=>"settings"
		);
	$table .= $tables[$p['table']];
	
	//which row do we manipulate?
	$rowpick = "";
	if (isset($p['rowpick'])) $rowpick = $p['rowpick'];
	$rowval = "";
	if (isset($p['rowval'])) $rowval = $p['rowval'];
	
	
	//which column do we manipulate or read?
	$colpick = "";
	if (isset($p['colpick'])) $colpick = $p['colpick'];
	$colval = "";
	if (isset($p['colval'])) $colval = $p['colval'];
	if (isset($p['makehtml'])) $colval = makehtml($p['colval']);
	
	
	// do the request action!!
	
	if ($p['action'] == "singleread") {
		$dosql = "SELECT ".$colpick." FROM ". $table." WHERE ".$rowpick." = '".$rowval."'";
		$return = $GLOBALS['lbdata']->GetArray($dosql);
		echo $return[0][$colpick];
	}
	
	if ($p['action'] == "singleupdate") {
		$dosql = "UPDATE ".$table." SET ". $colpick." = '". $colval . "' WHERE ".$rowpick." = '".$rowval."'";
		$GLOBALS['lbdata']->Execute($dosql);
		echo $colval;
	}
	
	
} else {
	echo "access denied!";
}

?>