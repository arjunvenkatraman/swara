<?php 

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


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">



<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <meta http-equiv="content-language" content="en" />
    <title>LoudBlog Install</title>

    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="LoudBlog" />

    <meta name="author" content="ONLINETRUST new media" />
    
    <link rel="stylesheet" type="text/css" href="loudblog/backend/screen.css" />

    <style type="text/css">
    <!--
        table { width: 740px; }

        td {
        vertical-align: top;
        padding: 5px 15px 5px 0;
        }

        th { padding-top: 30px; }

        input[type=text],
        input[type=password], 
        select
        { width: 280px; }

        textarea {
        font: normal 10px/1.2 monaco, courier, "Courier New", fixed;
        margin: 15px 0;
        width: 400px;
        height: 200px;
        }

        td.right {
        color: white;
        font-size: 0.9em;
        width: 270px;
        }

        td.left { width: 140px; }

        #last th { padding: 20px 0 10px 0; }
        #last input { width: 90px; }

        td.right a:link, 
        td.right a:visited { color: white; } 
        
        ul, li {
        list-style-type: square;
        }
        
        li {
        margin: 3px 0 0 20px;
        }
        
        #cgistuff {
        font-size: 0.9em;
        border: 1px dotted white;
        padding: 12px;
        margin: 15px 0;
        }
        
        #cgistuff h2 {
        margin-top: 0;
        }
        
        #cgistuff ul {
        margin-bottom: 5px;
        }


    -->
    </style>
    
    <script language="JavaScript" type="text/javascript">
<!--

function hide(object) 
{    
if (object.value == 'sqlite') {
    document.getElementById('notsqlite').style.display = 'none';
} else {
    document.getElementById('notsqlite').style.display = 'block';
}

}


-->
</script>


</head>


<body>

<div id="wrapper">

<?php

if (!isset($_GET['do'])) { step1(); }
if ((isset($_GET['do'])) AND ($_GET['do'] == "1")) { step1(); }
if ((isset($_GET['do'])) AND ($_GET['do'] == "2")) { step2(); }
if ((isset($_GET['do'])) AND ($_GET['do'] == "3")) { step3(); }
if ((isset($_GET['do'])) AND ($_GET['do'] == "4")) { step4(); }

?>

</div>

<div id="footer">
    <p>LoudBlog 0.7.0 | &copy; by <a href="http://www.onlinetrust.de">ONLINETRUST new media</a></p>
</div>

</body>
</html>


<?php

// ------------------------------------------------------------------------

function step1 () {

echo "<h1>Installation [1/3]</h1>\n\n";

echo "<p>Welcome to the LoudBlog installation. It's easy, so don't worry!</p>\n\n";


echo "<h2>Setting permissions</h2>";
echo "<p>At first you have to make sure that the folders \"audio\" and \"upload\" are on \"chmod 777\", so I can read AND write exciting content there.</p><p>Don't know what I'm talking about? <a href=\"http://www.tamba2.org.uk/wordpress/ftp/chmod/index.html\">Read this!</a></p>\n\n";

echo "<div id=\"cgistuff\">\n<h2>For advanced users</h2>\n";
echo "<p>If you want to use CGI for uploading audio data, you have to set some further permissions.</p>";
echo "<ul>\n<li>set \"loudblog/modules/cgi-bin\" to \"chmod 777\"</li>\n";
echo "<li>set \"loudblog/modules/cgi-bin/upload.cgi\" to \"chmod 755\"</li>\n";
echo "<li>set \"loudblog/modules/cgi-bin/download.cgi\" to \"chmod 755\"</li>\n</ul>\n\n";

echo "<p>CGI uploading is not necessary, but useful for experienced users. Check if your server is able to execute CGI scripts!</p>\n</div>";

//get the permission of the folders
if ((fileperms(getcwd()."/upload") == 16895) AND (fileperms(getcwd()."/audio") == 16895)) {

echo "<h2>Checking ... Permissions are okay now!</h2>\n";
echo "<form action=\"install.php?do=2\" 
        method=\"post\" enctype=\"multipart/form-data\">";
echo "<input type=\"submit\" value=\"next step!\" />\n";
echo "</form>";

} else {

echo "<h2>Do it now!</h2>\n";

echo "Take your time, change the file permissions of those folders, and then check again here!<br /><br/>\n\n";

echo "<form action=\"install.php?do=1\" 
        method=\"post\" enctype=\"multipart/form-data\">";
echo "<input type=\"submit\" value=\"check chmod status\" />\n";
echo "</form>";

}


}

// ------------------------------------------------------------------------

function step2() {

echo "<h1>Installation [2/3]</h1>\n\n";

echo "<form action=\"install.php?do=3\" 
        method=\"post\" enctype=\"multipart/form-data\">";

echo "<table>\n";
echo "<tr><th colspan=\"3\">Create your first login</th><tr>\n\n";

echo "<tr>\n";
echo "    <td class=\"left\">Nickname</td>\n";
echo "    <td class=\"center\">\n";
echo "    <input name=\"nick\" type=\"text\" value=\"\" />\n";
echo "    </td>\n";
echo "    <td class=\"right\">\n";
echo "    Note that this is cASe-SenSiTIv!\n";
echo "    </td>\n";
echo "</tr>\n\n";

echo "<tr>\n";
echo "    <td class=\"left\">Password</td>\n";
echo "    <td class=\"center\">\n";
echo "    <input name=\"pass\" type=\"password\" value=\"\" />\n";
echo "    </td>\n";
echo "    <td class=\"right\">";
echo "    You can add or change all details later.\n";
echo "    </td>\n";
echo "</tr>\n\n";

echo "<tr><th colspan=\"3\">Database settings</th><tr>\n\n";

echo "<tr>\n";
echo "    <td class=\"left\">Type</td>\n";
echo "    <td class=\"center\">\n";
echo "    <select onChange=\"hide(this)\" name=\"sqltype\">\n";
echo "    <option value=\"mysql\" selected=\"selected\">MySQL</option>\n";
echo "    <option value=\"sqlite\">SQLite</option>\n";
echo "    <option value=\"postgres7\">PostgreSQL 7 (not tested)</option>\n";
echo "    <option value=\"postgres8\">PostgreSQL 8 (not tested)</option>\n";
echo "    <option value=\"mssql\">Microsoft SQL (not tested)</option>\n";
echo "    <option value=\"oracle\">Oracle 7 (not tested)</option>\n";
echo "    <option value=\"oci8\">Oracle 8 (not tested)</option>\n";
echo "    </select>\n";
echo "    </td>\n";
echo "    <td class=\"right\">\n";
echo "    Which type of SQL database are you using?\n";
echo "    </td>\n";
echo "</tr>\n\n";

echo "</table>\n\n";
echo "<table id=\"notsqlite\">\n\n";

echo "<tr>\n";
echo "    <td class=\"left\">Host</td>\n";
echo "    <td class=\"center\">\n";
echo "    <input name=\"sqlhost\" type=\"text\" value=\"localhost\" />\n";
echo "    </td>\n";
echo "    <td class=\"right\">\n";
echo "    In most cases this is simply <code>localhost.</code>\n";
echo "    </td>\n";
echo "</tr>\n\n";

echo "<tr>\n";
echo "    <td class=\"left\">Database</td>\n";
echo "    <td class=\"center\">\n";
echo "    <input name=\"sqldata\" type=\"text\" value=\"\" />\n";
echo "    </td>\n";
echo "    <td class=\"right\">\n";
echo "    The name of your MySQL database.\n";
echo "    </td>\n";
echo "</tr>\n\n";

echo "<tr>\n";
echo "    <td class=\"left\">Username</td>\n";
echo "    <td class=\"center\">\n";
echo "    <input name=\"sqluser\" type=\"text\" value=\"\" />\n";
echo "    </td>\n";
echo "    <td class=\"right\">\n";
echo "    Your SQL username for the database above.\n";
echo "    </td>\n";
echo "</tr>\n\n";

echo "<tr>\n";
echo "    <td class=\"left\">Password</td>\n";
echo "    <td class=\"center\">\n";
echo "    <input name=\"sqlpass\" type=\"password\" value=\"\" />\n";
echo "    </td>\n";
echo "    <td class=\"right\">\n";
echo "    The appropiate SQL password.\n";
echo "    </td>\n";
echo "</tr>\n\n";

echo "<tr>\n";
echo "    <td class=\"left\">Table-Prefix</td>\n";
echo "    <td class=\"center\">\n";
echo "    <input name=\"sqlprefix\" type=\"text\" value=\"\" />\n";
echo "    </td>\n";
echo "    <td class=\"right\">\n";
echo "    If you wish to have more than one LoudBlog-installation on your database, you have to give each of them an unique prefix.\n";
echo "    </td>\n";
echo "</tr>\n\n";

echo "</table>\n\n";
echo "<table>\n\n";


echo "<tr><th colspan=\"3\">One more thing ...</th><tr>\n\n";

echo "<tr>\n";
echo "    <td class=\"left\">Website-URL</td>\n";
echo "    <td class=\"center\">\n";
echo "    <input name=\"siteurl\" type=\"text\" value=\"http://\" />\n";
echo "    </td>\n";
echo "    <td class=\"right\">\n";
echo "    Address of your LoudBlog powered audioblog or podcast. Without trailing slash! Example:<br /><code>http://www.mikeswebsite.com/mypodcast</code>";
echo "    </td>\n";
echo "</tr>\n\n";

echo "<tr id=\"last\">\n";
echo "    <th class=\"left\"></th>\n";
echo "    <th colspan=\"2\">\n";
echo "    <input type=\"submit\" value=\"go to step 3\" />\n";
echo "    </th>\n";
echo "</tr>\n\n";

echo "</table>\n";
echo "</form>";

}

// ------------------------------------------------------------------------

function step3 () {

echo "<h1>Installation [3/3]</h1>\n\n";

if (create() != false) {

//prepare data for SQLite-users
if ($_POST['sqltype'] == "sqlite") {
$_POST['sqlhost'] = "";
$_POST['sqldata'] = "";
$_POST['sqluser'] = "";
$_POST['sqlpass'] = "";
$_POST['sqlprefix'] = "";
}

echo "<h2>That was cool!</h2>\n";
echo "<p>All necessary data was written into your SQL database.</p>\n";
echo "<h2>Last action for today</h2>\n";
echo "<p>You have to do some copy-and-pasting now. Don't panic!</p>\n";
echo "<ul><li>Copy everything from the text-field below into the clipboard.</li>\n";
echo "<li>Paste it into the text file called \"config.php\".</li>\n";
echo "<li>This file can be found in the subfolder \"custom\" which is located in the folder \"loudblog\".</li>\n";
echo "<li>Save this file.</li></ul>\n\n";

echo "<textarea>\n";

echo '<?php
// YOUR DATABASE INFORMATION --------------------
$db = array(
"type" => "' . $_POST['sqltype'] . '",
"host" => "' . $_POST['sqlhost'] . '",
"data" => "' . $_POST['sqldata'] . '",
"user" => "' . $_POST['sqluser'] . '",
"pass" => "' . $_POST['sqlpass'] . '",
"pref" => "' . $_POST['sqlprefix'] . '"
);

// DOCUMENT ROOT ---------------------
$lb_path = "' . str_replace("\\", "/", getcwd()) . '";
?>';

echo "</textarea>\n\n";


echo "<form action=\"install.php?do=4\" 
        method=\"post\" enctype=\"multipart/form-data\">";
echo "<input type=\"submit\" value=\"I've done that!\" />\n";
echo "</form>";

} else {

echo "<h2>I'm so sorry!</h2>\n";
echo "<p>I could not write all necessary data into your database. Maybe there is something wrong with your account?</p>";

}

}


// ------------------------------------------------------------------------

function step4 () {

echo "<h1>Installation done!</h1>\n\n";


echo "<h2>Congratulations!</h2>\n";
echo "<p>All good. You should delete this installation file now. It may harm your data.</p>\n\n";

echo "<h2>And now?</h2>\n";
echo "<a href=\"index.php\">visit your new website</a> or\n";
echo "<a href=\"loudblog/index.php\">go to administration</a>\n";

}


// ------------------------------------------------------------------------

function create() {

$return = false;

if (isset($_POST['sqltype']) AND ($_POST['sqltype'] != "")) {



$db = array(
'type' => $_POST['sqltype'],
'host' => $_POST['sqlhost'],
'data' => $_POST['sqldata'],
'user' => $_POST['sqluser'],
'pass' => $_POST['sqlpass'],
'pref' => $_POST['sqlprefix']
);

$lb_path = str_replace("\\", "/", getcwd());
$GLOBALS['audiopath'] = $lb_path . "/audio/";



//include database stuff and functions
include "loudblog/inc/database/adodb.inc.php";
include "loudblog/inc/connect.php";


//build prefix-strings
$lb_authors = $_POST['sqlprefix'] . "lb_authors";
$lb_comments = $_POST['sqlprefix'] . "lb_comments";
$lb_categories = $_POST['sqlprefix'] . "lb_categories";
$lb_links = $_POST['sqlprefix'] . "lb_links";
$lb_postings = $_POST['sqlprefix'] . "lb_postings";
$lb_settings = $_POST['sqlprefix'] . "lb_settings";

//SQLite does not do auto_increment!!
if ($db['type'] == "sqlite") {
    $increm = "";
} else {
    $increm = "AUTO_INCREMENT";
}

$dosql =
"CREATE TABLE ".$lb_authors." (
  id INTEGER PRIMARY KEY ".$increm.",
  nickname VARCHAR(32),
  password VARCHAR(32),
  mail VARCHAR(128),
  realname VARCHAR(64),
  joined DATETIME,
  edit_own INTEGER,
  publish_own INTEGER,
  edit_all INTEGER,
  publish_all INTEGER,
  admin INTEGER
)";
$GLOBALS['lbdata']->Execute($dosql);

$dosql = 
"INSERT INTO ".$lb_authors." (
    id, 
    nickname, 
    password, 
    joined, 
    edit_own, 
    publish_own, 
    edit_all, 
    publish_all, 
    admin
) VALUES (
    '1', 
    '".$_POST['nick']."',
    '".md5($_POST['pass'])."',
    '".date("Y-m-d H:i:s")."',
    '1','1','1','1','1'
)";

$GLOBALS['lbdata']->Execute($dosql);


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

$dosql = 
"CREATE TABLE ".$lb_comments." (
  id INTEGER PRIMARY KEY ".$increm.",
  posting_id INTEGER(11),
  posted datetime,
  name VARCHAR(64) ,
  mail VARCHAR(128) ,
  web VARCHAR(128) ,
  ip VARCHAR(32) ,
  message_input text,
  message_html text,
  audio_file VARCHAR(255) ,
  audio_type INTEGER(4) ,
  audio_length INTEGER(8) ,
  audio_size INTEGER(11)
)";
$GLOBALS['lbdata']->Execute($dosql);



$dosql = 
"CREATE TABLE ".$lb_links." (
  id INTEGER PRIMARY KEY ".$increm.",
  posting_id INTEGER(11) ,
  description VARCHAR(255) ,
  title VARCHAR(255) ,
  url VARCHAR(255) ,
  linkorder INTEGER(3) 
)";
$GLOBALS['lbdata']->Execute($dosql);



$dosql = 
"CREATE TABLE ".$lb_postings." (
  id INTEGER PRIMARY KEY ".$increm.",
  author_id INTEGER(4) ,
  title VARCHAR(255) ,
  posted DATETIME ,
  filelocal INTEGER(2) ,
  audio_file VARCHAR(255) ,
  audio_type INTEGER(4) ,
  audio_length INTEGER(8) ,
  audio_size INTEGER(11) ,
  message_input TEXT,
  message_html TEXT,
  comment_on INTEGER(2) ,
  comment_size INTEGER(11) ,
  category1_id INTEGER(4) ,
  category2_id INTEGER(4) ,
  category3_id INTEGER(4) ,
  category4_id INTEGER(4) ,
  tags TEXT , 
  status INTEGER(2) ,
  countweb INTEGER(11) ,
  countfla INTEGER(11) ,
  countpod INTEGER(11) ,
  countall INTEGER(11) ,
  
  videowidth INTEGER(11) ,
  videoheight INTEGER(11) ,
  explicit INTEGER(2) ,
  sticky INTEGER(2)
)";
$GLOBALS['lbdata']->Execute($dosql);


$dosql = 
"INSERT INTO ".$lb_postings." (id , author_id , title , posted , filelocal , audio_file , audio_type , audio_length , audio_size , message_input , message_html , comment_on , comment_size , status, countweb, countfla, countpod, countall) VALUES ('1' , '1' , 'LoudBlog' , '2005-03-29 16:32:42' , '1' , 'podcast-2005-03-29-69562.mp3' , '1' , '7' , '28877' , '' , '' , '1' , '1048576' , '3', '0', '0', '0', '0')";


//make check here
$GLOBALS['lbdata']->Execute($dosql);



$dosql = 
"CREATE TABLE ".$lb_settings." (
  name VARCHAR(32),
  value VARCHAR(255)
)";
$GLOBALS['lbdata']->Execute($dosql);

$dosql = array( 
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('sitename','My LoudBlog')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('slogan','blogging it loud since 2005')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('description','My first LoudBlog installation')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('url','".$_POST['siteurl']."')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('flashcom_on','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('markuphelp','1')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('filename','podcast')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('dateformat','Y-m-d')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('rename','1')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('showlinks','10')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('comments_on','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('id3_overwrite','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('id3_album','your podcast')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('id3_artist','your name')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('id3_year','2005')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('id3_genre','Podcast')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('id3_comment','your comment')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('rss_postings','10')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('showpostings','15')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('template','default')",

"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('ftp','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('ftp_server','')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('ftp_user','')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('ftp_pass','')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('ftp_path','')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('cgi','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('cgi_url','http://')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('cgi_local','0')",

"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('itunes_author','')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('itunes_email','')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('itunes_explicit','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('copyright','')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('languagecode','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('feedcat1','00-00')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('feedcat2','00-00')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('feedcat3','00-00')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('feedcat4','00-00')",

"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('ping','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('language','english')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('countweb','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('countfla','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('countpod','0')",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('staticfeed','0')",


"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('emergency_email','');",

"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('version06','1');",
"INSERT INTO ".$lb_settings." ( name , value ) VALUES ('version07','1');"
);


foreach ($dosql as $sql) {
$GLOBALS['lbdata']->Execute($sql);
}


$dosql = "SELECT title FROM ".$lb_postings." WHERE id = '1'";
$result = $GLOBALS['lbdata']->GetArray($dosql);
if ($result != false) { $return = true; }

}


return $return;

}


?>