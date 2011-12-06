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
// function stripusc added
#################################################
#################################################

function whitelist_okay($input, $list) {
	$return = true;
	for ($i = 0; $i < strlen($input); $i++) {
		if (!in_array(substr($input, $i, 1), $list)) {
			$i = strlen($input);
			$return = false;
		}
	}
	return $return;
}

#################################################
#################################################


function cleanmygets() {

	//some white lists
	$list_bool = array("1","0");
	$list_date = array("1","2","3","4","5","6","7","8","9","0","-");

	//id must be an integer
    if (isset($_GET['id'])) {
    	$_GET['id'] = (int)$_GET['id'];
    }
    
    //page must be an integer
    if (isset($_GET['page'])) {
    	$_GET['page'] = (int)$_GET['page'];
    }
    
    //com must be either 1 or 0
    if ((isset($_GET['com'])) && (!in_array($_GET['com'], $list_bool))) {    	
		$_GET['com'] = "0";
    }
    
    //date must contain only numbers and hyphens
    if (isset($_GET['date'])) {
    	if (!whitelist_okay($_GET['date'], $list_date)) {
			$_GET['date'] = "2000-01-01";
		}
	}
    
    //cat must not contain evil characters
    if (isset($_GET['cat'])) {
    	$_GET['cat'] = killevilcharacters($_GET['cat']);
    }

	//tag must not contain evil characters
    if (isset($_GET['tag'])) {
    	$_GET['tag'] = killevilcharacters($_GET['tag']);
    }

}

#################################################
#################################################

function stripslashes_deep($value) {
    $output = (is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value));
    return $output;
}

#################################################
#################################################

/**
 * makes translating into different languages easy!
 *
 * @param unknown_type $text
 * @return unknown
 */
function bla($text) {
    global $lang;
    $output = (isset($lang[$text])) ? $lang[$text]: $text;
    return $output;
}

#################################################
#################################################

/**
 * extended version of empty()
 *
 * @param unknown_type $text
 * @return unknown
 */
function veryempty ($text) {
    $output = ((empty($text)) || (trim($text) == '') || ($text == 'NULL') || ($text == '0')) ? true: false;
    return $output;
}

#################################################
#################################################

function dumpdata() {
    global $catsdump, $authordump;
    //fetch all category data from database and create a global array

    $dosql = 'SELECT * FROM '.$GLOBALS['prefix'].'lb_categories;';
    $catsdump = $GLOBALS['lbdata']->GetArray($dosql);

    //fetch all author data from database and create a global array
    $dosql = 'SELECT * FROM '.$GLOBALS['prefix'].'lb_authors;';
    $authordump = $GLOBALS['lbdata']->GetArray($dosql);
}

#################################################
#################################################

function readonly($posting) {
    if (!allowed(1, $posting)) {
        return "readonly=\"readonly\"";
    }
}

#################################################
#################################################

/**
 * checks if the author has the right to do a certain action
 * action 1 = edit a posting
 * action 2 = publish a posting
 * action 3 = administration tasks
 *
 * @param unknown_type $action
 * @param unknown_type $posting
 * @return unknown
 */
function allowed($action,$posting) {
    //admin may do anything
    if (getuserrights('admin')) {
        return true;
    } else {
        switch ($action) {
            case '1':
            $tempreturn = false;
            if (getuserrights("edit_all")) { $tempreturn = true; }
            else {
            if (getuserrights("edit_own") AND owner($posting))
            { $tempreturn = true; }
            }
            return $tempreturn;
            break;

            case '2':
            $tempreturn = false;
            if (getuserrights("publish_all")) { $tempreturn = true; }
            else {
            if (getuserrights("publish_own") AND owner($posting))
            { $tempreturn = true; }
            }
            return $tempreturn;
            break;

            default:
            return false;
            break;
        }
    }
}

#################################################
#################################################

/**
 * checks if the logged author is the owner of a certain posting
 *
 * @param unknown_type $article
 * @return unknown
 */
function owner($article) {
    $dosql = 'SELECT author_id FROM '.$GLOBALS['prefix'].'lb_postings WHERE id="'.$article.'";';
    $row   = $GLOBALS['lbdata']->GetArray($dosql);
    if ($row[0]['author_id'] == getuserid($_SESSION['nickname'])) {
        return true;
    }else {
        return false;
    }
}

#################################################
#################################################

/**
 * checks if the logged user has got a certain right
 *
 * @param unknown_type $request
 * @return unknown
 */
function getuserrights ($request) {
    $dosql = "SELECT ".$request." FROM ".$GLOBALS['prefix']."lb_authors
    WHERE nickname='" . $_SESSION['nickname'] . "';";
    $row = $GLOBALS['lbdata']->GetArray($dosql);
    if ($row[0][$request] == 1) { return true; } else { return false; }
}

#################################################
#################################################

function checker($request) {
    $x = (int) $request;
    if ($x == 1) { return "checked=\"checked\" "; } else { return ""; }
}

#################################################
#################################################

/**
 * count rows of a certain table in database
 *
 * @param unknown_type $request
 * @return unknown
 */
function countrows($request) {
    $dosql = 'SELECT COUNT(*) FROM '.$GLOBALS['prefix'].$request;
    $row   = $GLOBALS['lbdata']->GetArray($dosql);
    return $row[0]['COUNT(*)'];
}

#################################################
#################################################

/**
 * get the highest id-number of a certain table
 *
 * @param unknown_type $request
 * @return unknown
 */
function max_id ($request) {
    $dosql  = "SELECT * FROM ".$GLOBALS['prefix'] . $request;
    $result = $GLOBALS['lbdata']->GetArray($dosql);
    $max    = 0;
    foreach ($result as $row) {
        if ($row['id'] > $max) $max = $row['id'];
    }
    return $max;
}

#################################################
#################################################

/**
 * count number of comment of a certain posting
 *
 * @param unknown_type $id
 * @return unknown
 */
function countcomments ($id) {
    $dosql = "SELECT COUNT(*) FROM ".$GLOBALS['prefix']."lb_comments ";
    $dosql .= "WHERE posting_id = '".$id."'";
    $row = $GLOBALS['lbdata']->GetArray($dosql);
    $i = $row[0]['COUNT(*)'];
    if ((isset($_POST['commentpreview'])) OR (isset($_POST['commentsubmit']))) {
    $i += 1;
    }
    return $i;
}

#################################################
#################################################

/**
 * returns a string with the attributes of the current URL plus the given one
 *
 * @param unknown_type $att
 * @param unknown_type $value
 * @return unknown
 */
function addToUrl ($att, $value) {
    $return = "";

    foreach ($_GET as $oldatt => $oldvalue) {
    if ($oldatt != $att) {
    $return .= "&".$oldatt."=".urlencode($oldvalue);
    }
    }
    $return .= "&".$att."=".urlencode($value);
    return "?".substr($return, 1);
}

#################################################
#################################################

/**
 * turns seconds into "3:33" scheme
 *
 * @param unknown_type $sec
 * @return unknown
 */
function getminutes ($sec) {
    $min = (int) ($sec / 60);
    $min2 = $sec%60;
    if ($min2 < 10) { $min2 = "0" . $min2; }
    return $min.":".$min2;
}

#################################################
#################################################

/**
 * turns seconds into "HH:MM:SS" scheme
 *
 * @param unknown_type $sec
 * @return unknown
 */
function getitunesduration($sec) {
    $hou = (int) ($sec / 3600);
    $sec -= ($hou*3600);
    $min = (int) ($sec / 60);
    $sec -= ($min*60);

    if ($hou < 10) { $hou = "0" . $hou; }
    if ($min < 10) { $min = "0" . $min; }
    if ($sec < 10) { $sec = "0" . $sec; }
    return $hou.":".$min.":".$sec;
}

#################################################
#################################################

/**
 * turns a "3:33" string into pure seconds
 *
 * @param unknown_type $request
 * @return unknown
 */
function getseconds($request) {
    $pieces = explode (":", $request);
    $sec = $pieces[0] * 60;
    $sec += $pieces[1];
    return $sec;
}

#################################################
#################################################

/**
 * turns byte into a nice megabyte-string
 *
 * @param unknown_type $request
 * @return unknown
 */
function getmegabyte ($request) {
    $mb = $request / 1024 / 1024;
    $mb = round ($mb, 1);
    if ($mb == 0) { $mb = 0.1; }
    if ($request < 10) { $mb = 0; };
    return $mb;
}

#################################################
#################################################

/**
 * calculates the upload-via-browser size limit
 *
 * @return unknown
 */
function uploadlimit() {
    $load = ini_get('upload_max_filesize');
    $post = ini_get('post_max_size');

    $load = trim($load);
    $last = strtolower($load{strlen($load)-1});
    switch($last) {
    case 'g': $load *= 1024;
    case 'm': $load *= 1024;
    case 'k': $load *= 1024;
    }

    $post = trim($post);
    $last = strtolower($post{strlen($post)-1});
    switch($last) {
    case 'g': $post *= 1024;
    case 'm': $post *= 1024;
    case 'k': $post *= 1024;
    }

    if ($post <= $load) { return $post; } else { return $load; }
}

#################################################
#################################################

/**
 * makes an url browser- and sql-friendly
 *
 * @param unknown_type $x
 * @return unknown
 */
function tunefilename ($x) {
    $x = str_replace(" ", "_", $x);
    $x = str_replace("'", "", $x);
    $x = str_replace('"', '', $x);
    $x = str_replace("(", "", $x);
    $x = str_replace(")", "", $x);
    $x = str_replace("‚Äô", "", $x);
    $x = str_replace(",", "", $x);
    $x = str_replace("?", "", $x);
    $x = str_replace("‚Äì", "-", $x);
    $x = str_replace("#", "", $x);
    $x = str_replace("+", "", $x);
    $x = str_replace("&", "", $x);
    $x = str_replace("\\", "", $x);
    return $x;
}

#################################################
#################################################

/**
 * extracts the pure filename from a whole url
 *
 * @param unknown_type $request
 * @return unknown
 */
function extractfilename ($request) {
    $url = parse_url($request);
    $path = $url['path'];
    $fragments = explode ("/", $path);
    $i = 0;
    while (isset($fragments[$i])) {
    $filename = $fragments[$i];
    $i += 1;
    }
    return $filename;
}

#################################################
#################################################

/**
 * delete the file-suffix of media files
 *
 * @param unknown_type $request
 * @return unknown
 */
function stripsuffix($request) {
    $suffs = array(".mp3", ".aac", ".mp4", ".m4a", ".m4b", ".wav", ".aif", ".aiff", ".ogg", ".mov", ".wmf", ".avi", ".asf", ".wmv");
    foreach ($suffs as $suff) {
    $request = str_replace($suff, "", $request);
    $request = str_replace(strtoupper($suff), "", $request);
    }
    return $request;
}

#################################################
#################################################

/**
 * build a new filename for the audio file
 *
 * @param unknown_type $suffix
 * @param unknown_type $prefix
 * @return unknown
 */
function freshaudioname ($suffix, $prefix) {
    if ((!isset($suffix)) OR (trim($suffix) == "")) { $suffix = ".mp3"; }
    $daysec = 10000 + date("G")*3600 + date("i")*60 + date("s");
    $filename = $prefix."-".date("Y-m-d-").$daysec.$suffix;
    return $filename;
}

#################################################
#################################################

/**
 * build a new filename for the audio file
 *
 * @param unknown_type $suffix
 * @param unknown_type $prefix
 * @param unknown_type $date
 * @return unknown
 */
function buildaudioname ($suffix, $prefix, $date) {
    if ((!isset($suffix)) OR (trim($suffix) == "")) { $suffix = ".mp3"; }
    $daysec = 10000 + date("G")*3600 + date("i")*60 + date("s");
    $filename = $prefix."-".substr($date,0,10)."-".$daysec.$suffix;
    return $filename;
}

#################################################
#################################################

/**
 * converts the media type from Loudblog-codes to MIME
 *
 * @param unknown_type $request
 * @return unknown
 */
function mime_type($request) {
    switch ($request) {
    case "1": $type = "audio/mpeg";
    break;
    case "2": $type = "application/octet-stream";
    break;

	default: $type = "application/octet-stream";
    break;
    }
    return $type;
}

#################################################
#################################################

/**
 * converts the media type from MIME to Loudblog-codes
 *
 * @param unknown_type $request
 * @return unknown
 */
function type_mime($request) {
    switch ($request) {
    case "audio/mpeg": $type = "1";
    break;
    case "application/octet-stream": $type = "2";
    break;

    default: $type = "0";
    break;
    }
    return $type;
}

#################################################
#################################################

/**
 * gets the Loudblog-Code from a filename
 *
 * @param unknown_type $request
 * @return unknown
 */
function type_suffix($request) {
    //we have an absolute path? okay, the file is the request
    if (substr_count($request, "/") > 0) {
    $file = $request;

    //relative path? use the audio path!
    } else {
    $file = $GLOBALS['audiopath'] . $request;
    }


    $type = strtoupper(strrchr($request, "."));
    switch ($type) {
    case ".MP3": return "1"; break;
    case ".AAC": return "2"; break;
    case ".MP4": return "10"; break;
    case ".M4V": return "10"; break;
    case ".3GP": return "10"; break;
    case ".OGG": return "3"; break;
    case ".WMA": return "4"; break;
    case ".WMV": return "11"; break;
    case ".WMF": return "11"; break;
    case ".WAV": return "5"; break;
    case ".AIF": return "6"; break;
    case ".AIFF":return "6"; break;
    case ".MOV": return "7"; break;
    case ".AVI": return "8"; break;
    case ".MID": return "12"; break;
    case ".MPG": return "13"; break;

    //do we have a width/length in the files? These are Enhanced Podcasts!
    case ".M4A":
    if (substr_count($request, "://") > 0) {
    return "14";
    } else {
    $id3 = getid3data($file, "back");
    if (!veryempty($id3['height'])) {
    return "14";
    } else { return "2"; }
    }
    break;
    case ".M4B":
    if (substr_count($request, "://") > 0) {
    return "9";
    } else {
    $id3 = getid3data($file, "back");
    if (!veryempty($id3['height'])) {
    return "14";
    } else { return "9"; }
    }
    break;

    default: return "0"; break;
    }
}

#################################################
#################################################

/**
 * converts media type from Loudblog-code to a beautiful string
 *
 * @param unknown_type $request
 * @return unknown
 */
function getmediatypename($request) {
    switch ($request) {
    case 1: $name = "MP3";
    break;
    case 2: $name = "AAC";
    break;
    case 3: $name = "Ogg Vorbis";
    break;
    case 4: $name = "WindowsMedia";
    break;
    case 5: $name = "WAV";
    break;
    case 6: $name = "AIFF";
    break;
    case 7: $name = "QuickTime";
    break;
    case 8: $name = "AVI";
    break;
    case 9: $name = "Audiobook";
    break;
    case 10:$name = "MPEG-4 Video";
    break;
    case 11:$name = "WindowsMedia";
    break;
    case 12:$name = "MIDI";
    break;
    case 13:$name = "MPEG Video";
    break;
    case 14:$name = "Enhanced Podcast";
    break;

    default: $name = "Media";
    break;
    }
    return $name;
}

#################################################
#################################################

/**
 * gets the author_id from the nickname
 *
 * @param unknown_type $table
 * @param unknown_type $giverow
 * @param unknown_type $givevalue
 * @param unknown_type $getrow
 * @return unknown
 */
function getdata($table, $giverow, $givevalue, $getrow) {
    global $catsdump, $authordump;

    switch ($table) {
        case "categories":
        foreach ($catsdump as $cat) {
            if ($cat[$giverow] == $givevalue) {
                return $cat[$getrow];
            }
        }
        break;
    case "authors":
        foreach ($authordump as $author) {
            if ($author[$giverow] == $givevalue) {
                return $author[$getrow];
            }
        }
        break;
    }
}

#################################################
#################################################

/**
 * gets the author_id from the nickname
 *
 * @param unknown_type $request
 * @return unknown
 */
function getuserid($request) {
    global $authordump;

    foreach ($authordump as $author) {
        if ($author['nickname'] == $request) {
            return $author['id'];
        }
    }
}

#################################################
#################################################

/**
 * gets the nickname from the author_id
 *
 * @param unknown_type $request
 * @return unknown
 */
function getnickname($request) {
    global $authordump;

    foreach ($authordump as $author) {
        if ($author['id'] == $request) {
            return $author['nickname'];
        }
    }
}

#################################################
#################################################

/**
 * gets the realname from the author_id
 *
 * @param unknown_type $request
 * @return unknown
 */
function getfullname($request) {
    global $authordump;

    foreach ($authordump as $author) {
        if ($author['id'] == $request) {
            return $author['realname'];
        }
    }
}

#################################################
#################################################

/**
 * gets the mail address from the author_id
 *
 * @param unknown_type $request
 * @return unknown
 */
function getmail($request) {
    global $authordump;

    foreach ($authordump as $author) {
        if ($author['id'] == $request) {
            return $author['mail'];
        }
    }
}

#################################################
#################################################

/**
 * gets the title of a posting from the id.
 *
 * @param unknown_type $id
 * @return unknown
 */
function gettitlefromid ($id) {
    $dosql = "SELECT title FROM ".$GLOBALS['prefix']."lb_postings
    WHERE id='" . $id . "';";
    $row = $GLOBALS['lbdata']->GetArray($dosql);
    return $row[0]['title'];
}

#################################################
#################################################

function killentities($text) {
    $trans      = get_html_translation_table(HTML_ENTITIES);
    $trans["'"] = '&rsquo;';
    $trans["'"] = '&#039;';
    $trans["E"] = '&euro;';
    $trans[" "] = ' ';

    foreach($trans as $k => $v) {
        $ttr[$v] = "";
    }
    return strtr($text, $ttr);
}

#################################################
#################################################

function killevilcharacters($text) {
    $trans       = array();
    $trans[" "]  = '';
    $trans[".."] = '';
    $trans["/"]  = '';
    $trans["'"]  = '';
    $trans['"']  = '';
    $trans['"']  = '';
    $trans['<']  = '';
    $trans['>']  = '';

    return strtr($text, $trans);
}

#################################################
#################################################

function unichr($dec) {
    if ($dec < 256) {
        $utf = chr($dec);
    } elseif ($dec < 2048) {
        $utf = chr(192 + (($dec - ($dec % 64)) / 64));
        $utf .= chr(128 + ($dec % 64));
    } else {
        $utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
        $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
        $utf .= chr(128 + ($dec % 64));
    }
    return $utf;
}

#################################################
#################################################

function entities_to_chars($text) {
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);

    //some HTML4.0 Entities
    $trans_tbl['&#039;']   = "'";
    $trans_tbl['&#8220;']  = "\"";
    $trans_tbl['&#8221;']  = "\"";
    $trans_tbl['&#8222;']  = "\"";
    $trans_tbl['&#8249;']  = "'";
    $trans_tbl['&#8250;']  = "'";
    $trans_tbl['&#8216;']  = "'";
    $trans_tbl['&#8217;']  = "'";
    $trans_tbl['&#8218;']  = "'";
    $trans_tbl['&#8211;']  = "-";
    $trans_tbl['&#8212;']  = "-";
    $trans_tbl['&mdash;']  = "-";
    $trans_tbl['&ndash;']  = "-";
    $trans_tbl['&euro;']   = "EUR";
    $trans_tbl['&#8364;']  = "EUR";
    $trans_tbl['&apos;']   = "'";
    $trans_tbl['&#8217;']  = "'";
    $trans_tbl['&hellip;'] = "...";
    $trans_tbl['&#8230;']  = "...";
    $trans_tbl['&#8240;']  = "%%";

    $ret = strtr($text, $trans_tbl);
    $ret = preg_replace("/&#(\d{2,5});/e", "unichr($1);", $ret);
    //$ret = preg_replace('/&#(\d+);/me', "chr('\\1')",$ret);
    $ret = preg_replace('|&\w*;|me', "",$ret);
    return $ret;
}

#################################################
#################################################

/**
 * gets the category from the category_id
 *
 * @param unknown_type $request
 * @return unknown
 */

function getcategory($request) {
    global $catsdump;

    foreach ($catsdump as $cat) {
        if ($cat['id'] == $request) {
            return $cat['name'];
        }
    }
}


#################################################
#################################################

/**
 * gets the category_id from a category name
 *
 * @param unknown_type $request
 * @return unknown
 */
function getcategoryid ($request) {
    global $catsdump;

    foreach ($catsdump as $cat) {
        if (trim($cat['name']) == $request) {
            return $cat['id'];
        }
    }
}

#################################################
#################################################

/**
 * gets the category_id from a shortened category name
 *
 * @param unknown_type $request
 * @return unknown
 */
function getcategoryidshort ($request) {
    global $catsdump;

    foreach ($catsdump as $cat) {
        if (killentities($cat['name']) == $request) {
          return $cat['id'];
        }
    }
}

#################################################
#################################################

/**
 * putting settings into a handy array
 *
 * @return unknown
 */
function getsettings () {
    $dosql    = "SELECT * FROM ".$GLOBALS['prefix']."lb_settings;";
    $settings = $GLOBALS['lbdata']->GetAssoc($dosql);
    return $settings;
}

#################################################
#################################################

/**
 * gets current page-title from url and make it more beautifu.
 *
 * @return unknown
 */
function pagetitle () {
    //does the url contain a page-information?
    if (isset($_GET['page'])) {
        switch ($_GET['page']) {
            case "record1": $title = bla("hl_rec1");
                break;
            case "record2": $title = bla("hl_rec2");
                break;
            case "comments": $title = bla("hl_comments");
                break;
            case "postings": $title = bla("hl_postings");
                break;
            case "settings": $title = bla("hl_settings");
                break;
            case "stats": $title = bla("hl_stats");
                break;
            case "organisation": $title = bla("hl_organisation");
                break;
            default: $title = "Postings";
                break;
        }
        //build even more beautiful page-title
        $title = "LoudBlog: " . $title;

        //no page-info from url? has to be the login
    } else {
        $title = "LoudBlog: Login";
    }

    return $title;
}

#################################################
#################################################

/**
 * changes all special chars to entities, but allows < and >
 *
 * @param unknown_type $text
 * @return unknown
 */
function change_entities ($text) {
    $text = htmlentities($text, ENT_QUOTES, "UTF-8");
    $text = str_replace ("&lt;","<",$text);
    $text = str_replace ("&gt;",">",$text);
    return $text;
}

#################################################
#################################################

/**
 * changes all &amp; to &
 *
 * @param unknown_type $text
 * @return unknown
 */
function no_amp ($text) {
    $text = str_replace ("&amp;","&",$text);
    return $text;
}

#################################################
#################################################

/**
 * transform a given text with the preferred html-helper.
 *
 * @param unknown_type $text
 * @return unknown
 */
function makehtml ($text) {
    global $settings;

    //include a markup-helper and make use of it
    switch ($settings['markuphelp']) {
        case "1":
            include_once ($GLOBALS['path'].'/loudblog/inc/markuphelp/textile.php');
            $textile = new Textile;
            $temphtml = $textile->TextileThis($text);
            break;
        case "2":
            include_once ($GLOBALS['path'].'/loudblog/inc/markuphelp/markdown.php');
            $temphtml = change_entities (Markdown($text));
            break;
        case "3":
            include_once ($GLOBALS['path'].'/loudblog/inc/markuphelp/stringparser_bbcode.class.php');
            include_once ($GLOBALS['path'].'/loudblog/inc/markuphelp/bbcode.php');
            $temphtml = $bbcode->parse($text);
            break;
        case "0":
            $temphtml = "<p>".change_entities ($text)."</p>";
            break;
    }
    return $temphtml;
}

#################################################
#################################################

/**
 * getting the current id3-tags!
 *
 * @param unknown_type $file
 * @return unknown
 */
function checksuffix ($file) {
    $suffix = strtoupper(strrchr($file, "."));
    if (($suffix == ".MP3") OR
        ($suffix == ".AAC") OR
        ($suffix == ".MP4") OR
        ($suffix == ".M4A") OR
        ($suffix == ".M4B") OR
        ($suffix == ".OGG") OR
        ($suffix == ".WMA") OR
        ($suffix == ".WMF") OR
        ($suffix == ".WAV") OR
        ($suffix == ".AIF") OR
        ($suffix == ".AIFF") OR
        ($suffix == ".MOV") OR
        ($suffix == ".AVI")) {
        return true;
    } else {
        return false;
    }
}

#################################################
#################################################

/**
 * send an email when receiving a fresh comment
 *
 * @param unknown_type $posting
 * @param unknown_type $name
 * @param unknown_type $mail
 * @param unknown_type $web
 * @param unknown_type $message
 */
function notify ($posting, $name, $mail, $web, $message) {
    global $settings;

    if (empty($mail)) { $mail = "no mail address"; }
    if (empty($web)) { $web = "no web address"; }

    $authorid = $posting['author_id'];

    $to = getfullname($authorid)." <".getmail($authorid).">";
    $subject = "New comment on '".$settings['sitename']."'";

    $name = entities_to_chars($name);
    $mail = entities_to_chars($mail);
    $web = entities_to_chars($web);

    $body = $name." has left a comment for '".$posting['title']."'\n";
    $body .= $settings['url']."/index.php?id=".$posting['id']."\n\n";
    $body .= entities_to_chars(strip_tags($message))."\n\n";
    $body .= "Name: ".$name;
    if (!empty($mail)) { $body .= " / eMail: ".$mail; $showmail = $mail; }
    else { $showmail = getmail($authorid); }
    if ($web != "http://") { $body .= " / Web: ".$web; }

    //killing komma and semikolon
    $name = str_replace(","," ",$name);
    $name = str_replace(";"," ",$name);

    $headers = "From: ".$name." <".$showmail.">\r\n";

    mail($to, $subject, $body, $headers);
}

#################################################
#################################################

/**
 * two methods: first try to temporarily copy, then try to get remote data
 *
 * @param unknown_type $url
 * @return unknown
 */
function remote_fileatts($url) {
    $return['size']   = 0;
    $return['length'] = 0;

    // Attempts to determine the size of the file given in
    // the supplied URL using HTTP/1.1.
    // Returns: the file size in bytes, null otherwise.
    // This script was written by Ektoras. Thank you very much!!!

    $parsedURL = parse_url($url);
    $host      = $parsedURL['host'];
    $port      = isset($parsedURL['port']) ? $parsedURL['port'] : 80;
    $resource  = $parsedURL['path'];

    // Connect to the remote web server.
    $fp = @fsockopen ($host, $port);
    if ($fp != false) {
        // We are connected. Let's talk.
        $headString       = sprintf("HEAD %s HTTP/1.1\r\n", $resource);
        $hostString       = sprintf("HOST: %s\r\n", $host);
        $connectionString = sprintf("Connection: close\r\n\r\n");

        fputs($fp, $headString);
        fputs($fp, $hostString);
        fputs($fp, $connectionString);

        $response = '';
        while (!feof($fp)) {
            $response .= fgets($fp);
        }

        fclose ($fp);

        // Examine the HTTP response header to determine the size of the resource.
        if (preg_match('/Content-Length:\s*(\d+)/i', $response, $matches)) {
            $return['size'] = $matches[1];
        }
    }

    return $return;
}

#################################################
#################################################

/**
 * delete all temporary comments older than 12 hours!
 *
 */
function deleteorphans () {
    //gets the filenames of all the files in the audio-folder and check them
    $audiofolder = opendir('audio');
    while ($file = readdir($audiofolder)) {
        if (substr($file, 0, 5) == "temp-") {
            $filename  = substr($file, 0, strpos($file, "."));
            $filestamp = strtotime(substr($filename, -16, 10)) + substr($filename, -5) - 10000;
            $dropstamp = time() - 43200;

            if ($filestamp < $dropstamp) {
                unlink($GLOBALS['audiopath'].$file);
            }
        }
    }
    closedir($audiofolder);
}

#################################################
#################################################

function showflash ($url, $width, $height) {
    $return = "\n<object type=\"application/x-shockwave-flash\" ";
    $return .= "data=\"".$url."\" ";
    $return .= "width=\"".$width."\" height=\"".$height."\">\n";
    $return .= "    <param name=\"movie\" value=\"".$url."\" />\n";
    $return .= "</object>\n";

    return $return;
}

#################################################
#################################################

function showquicktime ($src, $href, $width, $height, $target, $controller) {
    $return = "\n<object CLASSID=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" width=\"".$width."\" height=\"".$height."\" codebase=\"http://www.apple.com/qtactivex/qtplugin.cab\">\n";
    $return .= "    <param name=\"src\" value=\"".$src."\" />\n";
    $return .= "    <param name=\"href\" value=\"".$href."\" />\n";
    $return .= "    <param name=\"target\" value=\"".$target."\" />\n";
    $return .= "    <param name=\"autohref\" value=\"false\" />\n";
    $return .= "    <param name=\"autoplay\" value=\"false\" />\n";
    $return .= "    <param name=\"controller\" value=\"".$controller."\" />\n";
    $return .= "    <embed src=\"".$src."\" href=\"".$href."\" autohref=\"false\" width=\"".$width."\" height=\"".$height."\" controller=\"".$controller."\" target=\"".$target."\" autoplay=\"false\" pluginspage=\"http://www.apple.com/de/quicktime/download/\" />\n";
    $return .= "</embed>\n</object>\n\n";

    return $return;
}

#################################################
#################################################

/**
 * getting the current id3-tags!
 *
 * @param unknown_type $filename
 * @param unknown_type $where
 * @return unknown
 */
function getid3data ($filename, $where) {
    if ($where == "back") {
        require_once('inc/id3/getid3.php');
    } else {
        require_once('loudblog/inc/id3/getid3.php');
    }

    $getID3           = new getID3;
    $getID3->encoding = 'UTF-8';
    $fileinfo         = $getID3->analyze($filename);

    //TITLE---------------------------
    $title = "";
    if (isset($fileinfo['id3v2']['comments']['title'][0])) {
        $title = $fileinfo['id3v2']['comments']['title'][0];
    } else {
        if (isset($fileinfo['id3v1']['title'])) {
            $title = $fileinfo['id3v1']['title'];
        }
    }
    if (isset($fileinfo['tags']['quicktime']['title'][0])) {
        $title = $fileinfo['tags']['quicktime']['title'][0];
    }

    //ARTIST---------------------------
    $artist = "";
    if (isset($fileinfo['id3v2']['comments']['artist'][0])) {
        $artist = $fileinfo['id3v2']['comments']['artist'][0];
    } else {
        if (isset($fileinfo['id3v1']['artist'])) {
            $artist = $fileinfo['id3v1']['artist'];
        }
    }
    if (isset($fileinfo['tags']['quicktime']['artist'][0])) {
        $artist = $fileinfo['tags']['quicktime']['artist'][0];
    }

    //ALBUM---------------------------
    $album = "";
    if (isset($fileinfo['id3v2']['comments']['album'][0])) {
        $album = $fileinfo['id3v2']['comments']['album'][0];
    } else {
        if (isset($fileinfo['id3v1']['album'])) {
            $album = $fileinfo['id3v1']['album'];
        }
    }
    if (isset($fileinfo['tags']['quicktime']['album'][0])) {
        $album = $fileinfo['tags']['quicktime']['album'][0];
    }

    //YEAR---------------------------
    $year = "";
    if (isset($fileinfo['id3v2']['comments']['year'][0])) {
        $year = $fileinfo['id3v2']['comments']['year'][0];
    } else {
        if (isset($fileinfo['id3v1']['year'])) {
            $year = $fileinfo['id3v1']['year'];
        }
    }
    if (isset($fileinfo['tags']['quicktime']['creation_date'][0])) {
        $year = $fileinfo['tags']['quicktime']['creation_date'][0];
    }

    //TRACK---------------------------
    $track = "";
    if (isset($fileinfo['id3v2']['comments']['track'][0])) {
        $track = $fileinfo['id3v2']['comments']['track'][0];
    } else {
        if (isset($fileinfo['id3v1']['track'])) {
            $track = $fileinfo['id3v1']['track'];
        }
    }

    //GENRE---------------------------
    $genre = "";
    if (isset($fileinfo['id3v2']['comments']['genre'][0])) {
        $genre = $fileinfo['id3v2']['comments']['genre'][0];
    } else {
        if (isset($fileinfo['id3v1']['genre'])) {
            $genre = $fileinfo['id3v1']['genre'];
        }
    }
    if (isset($fileinfo['tags']['quicktime']['genre'][0])) {
        $genre = $fileinfo['tags']['quicktime']['genre'][0];
    }

    //COMMENT---------------------------
    $comment = "";
    if (isset($fileinfo['id3v2']['comments']['comment'][0])) {
        $comment = $fileinfo['id3v2']['comments']['comment'][0];
    } else {
        if (isset($fileinfo['id3v1']['comment'])) {
            $comment = $fileinfo['id3v1']['comment'];
        }
    }
    if (isset($fileinfo['tags']['quicktime']['comment'][0])) {
        $comment = $fileinfo['tags']['quicktime']['comment'][0];
    }

    //IMAGE---------------------------
    $image = ""; $imgtype = ".jpg";
    if (isset($fileinfo['id3v2']['APIC'][0]['data'])) {
        $image = $fileinfo['id3v2']['APIC'][0]['data'];
        $mime = $fileinfo['id3v2']['APIC'][0]['image_mime'];
        switch ($mime) {
            case "image/jpeg" : $imgtype = ".jpg";
            case "image/png"  : $imgtype = ".png";
            case "image/gif"  : $imgtype = ".gif";
        }
    }

    //NON ID3-INFO---------------------------
    if (!isset($fileinfo['audio']['bitrate'])) { $fileinfo['audio']['bitrate'] = "?"; }
    if (!isset($fileinfo['audio']['sample_rate'])) { $fileinfo['audio']['sample_rate'] = "?"; }
    if (!isset($fileinfo['audio']['channelmode'])) { $fileinfo['audio']['channelmode'] = ""; }
    if (!isset($fileinfo['audio']['bitrate_mode'])) { $fileinfo['audio']['bitrate_mode'] = ""; }
    if (!isset($fileinfo['filesize'])) { $fileinfo['filesize'] = 0; }
    if (!isset($fileinfo['playtime_string'])) { $fileinfo['playtime_string'] = "0:00"; }
    if (!isset($fileinfo['fileformat'])) { $fileinfo['fileformat'] = ""; }
    if (!isset($fileinfo['video']['resolution_x'])) { $fileinfo['video']['resolution_x'] = ""; }
    if (!isset($fileinfo['video']['resolution_y'])) { $fileinfo['video']['resolution_y'] = ""; }

    return array('image'=>$image, 'imgtype'=>$imgtype, 'title'=>$title, 'artist'=>$artist, 'genre'=>$genre, 'track'=>$track, 'comment'=>$comment, 'year'=>$year, 'album'=>$album, 'audio'=>$fileinfo['audio'], 'size'=>$fileinfo['filesize'], 'duration'=>$fileinfo['playtime_string'], 'type'=>$fileinfo['fileformat'], 'width'=>$fileinfo['video']['resolution_x'], 'height'=>$fileinfo['video']['resolution_y']);
}

#################################################
#################################################

function gettaglist($weighted = false, $suggest_string = '') {
#	return array('loudblog');
	$tags_clean = array();
	$dosql = 'SELECT tags FROM '.$GLOBALS['prefix'].'lb_postings WHERE tags '. "LIKE '%$suggest_string%'" . ';';
    $tags = $GLOBALS['lbdata']->GetArray($dosql);

	foreach ($tags as $tagrow) {
		$tagrow = $tagrow['tags'];
		$tags_tmp = explode(' ', $tagrow);
		foreach ($tags_tmp as $tag_add) {
			if ($tag_add == '') continue;
			if (isset($tags_clean[$tag_add])) $tags_clean[$tag_add]++;
			else $tags_clean[$tag_add] = 1;
			}
	}
	ksort($tags_clean, SORT_STRING);
	if (!$weighted) return array_keys($tags_clean);
	else return weighttags($tags_clean);
}


#################################################
#################################################


function weighttags($tags_clean) {
	return $tags_clean;
}

#################################################
#################################################

//replaces underscores with spaces in tag and category names
 function stripusc($content)  {
   return str_replace("_"," ",$content);
 }
?>