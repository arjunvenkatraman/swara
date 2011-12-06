<?php

//this is the auto-update script

//Minor than LB 0.4x? No auto-update possible!
if (!isset($settings['ping'])) {
	die ("<p class=\"msg\">".bla("msg_upgradeto04first")."</p>");
}

//Minor than LB 0.5?
if (!isset($settings['spamquestion'])) {

	//adding the 0.5 stuff
	$dosql = array( 
	"INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('acceptcomments','1');",
	"INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('preventspam','0');",
	"INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('spamquestion','Your favourite podcasting tool?');",
	"INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('spamanswer','Loudblog');"
	);
	
	foreach ($dosql as $sql) {
		$GLOBALS['lbdata']->Execute($sql);
		
	}
}

//Minor than LB 0.6?
if (!isset($settings['version06'])) {

	//adding the 0.6 stuff
	$dosql = array( 
	"INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('version06','1');",
	"ALTER TABLE ".$GLOBALS['prefix']."lb_postings ADD videowidth INT(11) DEFAULT '0';",
	"ALTER TABLE ".$GLOBALS['prefix']."lb_postings ADD videoheight INT(11) DEFAULT '0';",
	"ALTER TABLE ".$GLOBALS['prefix']."lb_postings ADD explicit INT(2) DEFAULT '0';",
	"ALTER TABLE ".$GLOBALS['prefix']."lb_postings ADD sticky INT(2) DEFAULT '0';"
	);
	
	foreach ($dosql as $sql) {
		$GLOBALS['lbdata']->Execute($sql);
		
	}
}


	// Minor than LB 0.7?
	if (!isset($settings['version07'])) {
	
	$dosql = "ALTER TABLE ".$GLOBALS['prefix']."lb_postings ADD tags VARCHAR(255) DEFAULT '';";
	$GLOBALS['lbdata']->Execute($dosql);
	
	$dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('version07','1');";
	$GLOBALS['lbdata']->Execute($dosql);
		
	}
	
	//minor than Loudblog 0.7.1?
	if (!isset($settings['version071'])) {
		$dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('version071','1');";
		$GLOBALS['lbdata']->Execute($dosql);
		$dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('badbehavior','1');";
		$GLOBALS['lbdata']->Execute($dosql);
		$dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('staticfeeds_tags','1');";
		$GLOBALS['lbdata']->Execute($dosql);
		if (!isset($settings['emergency_email']))   {
		$dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('emergency_email','');";
		$GLOBALS['lbdata']->Execute($dosql); }
		
    }


	//minor than Loudblog 0.8.0?
	// Yes, community, we DO bring back categories!
	if (!isset($settings['version080'])) {
		$dosql = "SHOW COLUMNS FROM ".$GLOBALS['prefix']."lb_postings LIKE 'category%_id'";
		$result = $GLOBALS['lbdata']->Execute($dosql);
		if ($result->RecordCount()<1) {
			$dosql = "ALTER TABLE ".$GLOBALS['prefix']."lb_postings ADD category1_id int(11) DEFAULT 0;";
			$GLOBALS['lbdata']->Execute($dosql);
			
			$dosql = "ALTER TABLE ".$GLOBALS['prefix']."lb_postings ADD category2_id int(11) DEFAULT 0;";
			$GLOBALS['lbdata']->Execute($dosql);
			
			$dosql = "ALTER TABLE ".$GLOBALS['prefix']."lb_postings ADD category3_id int(11) DEFAULT 0;";
			$GLOBALS['lbdata']->Execute($dosql);
			
			$dosql = "ALTER TABLE ".$GLOBALS['prefix']."lb_postings ADD category4_id int(11) DEFAULT 0;";
			$GLOBALS['lbdata']->Execute($dosql);
		}
		
		$dosql = "SHOW TABLES";
		$tables = $GLOBALS['lbdata']->getCol($dosql);
		foreach ($tables as $i=>$table) $tables[$i] = strtolower($table);
		if (!in_array(strtolower($GLOBALS['prefix'] . 'lb_categories'), $tables)) {
			
			//SQLite does not do auto_increment!!
			if ($db['type'] == "sqlite") {
			    $increm = "";
			} else {
			    $increm = "AUTO_INCREMENT";
			}
			
			$lb_categories = $GLOBALS['prefix'] . 'lb_categories';
			
			$dosql = 
			"CREATE TABLE ".$lb_categories." (
			  id INTEGER PRIMARY KEY ".$increm.",
			  name VARCHAR(32),
			  description VARCHAR(255)
			)";
			$GLOBALS['lbdata']->Execute($dosql);

			 $dosql = 
			"INSERT INTO ".$lb_categories." (id, name, description) VALUES ('1', 'default', 'this is the default category')";
			$GLOBALS['lbdata']->Execute($dosql);
		}
		
		$dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('version080','1');";
		$GLOBALS['lbdata']->Execute($dosql);

		$dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_settings ( name , value ) VALUES ('previews','0');";
		$GLOBALS['lbdata']->Execute($dosql);

	echo "<p class=\"msg\">".bla("msg_080updated")."</p>";
		
	}

