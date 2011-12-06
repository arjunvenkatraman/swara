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

//--------------------------------------------------------------------
//  GENERAL SITE TAGS
//--------------------------------------------------------------------
//tag tags amended to remove category variables
// tagcloud function added
// stripusc used to remove underscores from tag names and category names
//functions loop_postingstags and postingtag added 

#################################################
#################################################

function sitename () {
//returns the "name" of the website, taken from settings
global $settings;
return $settings['sitename'];
}

#################################################
#################################################

function link_website ($content) {
//generates a href-link to the url of the website, taken from settings
global $settings;
$return = "<a href=\"index.php\" title=\"" . $settings['sitename'] . "\">";
$return .= fullparse (stripcontainer ($content));
$return .= "</a>\n";
return $return;
}

#################################################
#################################################

function siteslogan () {
//returns the "slogan" of the website, taken from settings
global $settings;
return $settings['slogan'];
}

#################################################
#################################################

function sitedescription () {
//returns the "short description" of the website, taken from settings
global $settings;
return $settings['description'];
}

#################################################
#################################################

function link_login ($content) {
//generates a href-link to the admin-login
global $settings;
$return = "<a href=\"loudblog\" title=\"Login to Administration\">";
$return .= fullparse (stripcontainer ($content));
$return .= "</a>\n";
return $return;
}

#################################################
#################################################

function link_podcast ($content) {
//generates a href-link to the podcast-feed
global $currentid;
global $settings;


$att = getattributes($content);
$com = ""; $id = ""; $post = "";

if ((isset($att['comments'])) AND ($att['comments'] == "true"))
            { $com = "&amp;com=1"; }
if ((isset($att['single'])) AND ($att['single'] == "true"))
            { $id = "&amp;id=" . $currentid; }
if ((isset($att['posting'])) AND ($att['posting'] == "false"))
            { $post = "&amp;post=0"; }

$urlatts = "?" . substr($post.$com.$id, 5);
if ($urlatts == "?") { $urlatts = ""; }

if (($urlatts == "") AND ($settings['staticfeed'] == "1")) {
    $return = "<a href=\"audio/rss.xml\" title=\"Link to Podcast\">";
    $return .= fullparse (stripcontainer ($content));
    $return .= "</a>\n";
} else {
    $return = "<a href=\"podcast.php".$urlatts."\" title=\"Link to Podcast\">";
    $return .= fullparse (stripcontainer ($content));
    $return .= "</a>\n";
}

return $return;
}

#################################################
#################################################

function rssfeedhead () {
//returns a full <link>-tag to the podcast-feed (for the html-head)
global $settings;

if ($settings['staticfeed'] == "0") {
    $return = "<link rel=\"alternate\" type=\"application/rss+xml\"";
    $return .= " title=\"Podcast-Feed\" href=\"";
    $return .= $settings['url'] . "/podcast.php\" />";
} else {
    $return = "<link rel=\"alternate\" type=\"application/rss+xml\"";
    $return .= " title=\"Podcast-Feed\" href=\"";
    $return .= $settings['url'] . "/audio/rss.xml\" />";
}
return $return;
}

#################################################
#################################################

/**
 * generates a href-link to the previous page
 *
 * @param unknown_type $content
 * @return unknown
 */
function link_prev($content) {
    $here = (isset($_GET['page'])) ? $_GET['page']: 1;

    //on page 1 we don't show a previous page link!
    if (($here > 1) AND (!isset($_GET['id']))) {
        $return = '<a href="index.php'.addToUrl('page', ($here - 1)).'" title="previous page">';
        $return .= fullparse(stripcontainer($content));
        $return .= "</a>\n";
    } else {
        $return = '';
    }

    return $return;
}

#################################################
#################################################

/**
 * generates a href-link to the next page
 *
 * @param unknown_type $content
 * @return unknown
 */
function link_next ($content) {
    global $nextpage;

    $here = (isset($_GET['page'])) ? $_GET['page']: 1;

    if (!isset($_GET['id']) AND ($nextpage == true)) {
        $return  = '<a href="index.php'.addToUrl('page', ($here + 1)).'" title="next page">';
        $return .= fullparse(stripcontainer($content));
        $return .= "</a>\n";
    } else {
        $return = '';
    }
    return $return;
}

#################################################
#################################################

function currentcategory ($content) {
//returns the name of the currently listed category, if possible
global $cats;
$att = getattributes($content);
$return = "";

//getting category from url
if (isset($_GET['cat'])) {
    //getting some data from categories-table
    $dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_categories;";
    $result = $GLOBALS['lbdata']->GetArray($dosql);

    foreach ($result as $temp) {
        if (killentities($temp['name']) == $_GET['cat']) {
            $return = $temp['name'];
        }
    }
}

//getting first category from single postings
if (isset($_GET['id'])) {
    $dosql = "SELECT category1_id FROM ".$GLOBALS['prefix']."lb_postings
              WHERE id='" . $_GET['id'] . "';";
    $row = $GLOBALS['lbdata']->GetArray($dosql);

    $dosql = "SELECT name FROM ".$GLOBALS['prefix']."lb_categories
              WHERE id='" . $row[0]['category1_id'] . "';";
    $rowcat = $GLOBALS['lbdata']->GetArray($dosql);

    $return = $rowcat[0]['name'];
}

if (isset($att['short']) AND ($att['short'] == "true")) {
    $return = killentities($return);
}
return $return;
}


//--------------------------------------------------------------------
//  POSTING TAGS
//--------------------------------------------------------------------

#################################################
#################################################

/**
 * generates the permalink for the posting (works within postings-loop)
 *
 * @param unknown_type $content
 * @return unknown
 */
function link_permalink ($content) {
    global $settings, $currentid;

    if ((!isset($_GET['id'])) OR ($_GET['id'] != $currentid)) {
        $return = "<a href=\"index.php?id=" .$currentid. "\"
               title=\"Link to posting\">";
        $return .= fullparse (stripcontainer ($content));
        $return .= "</a>\n";
    } else {
        $return = fullparse(stripcontainer($content));
    }
    return $return;
}

#################################################
#################################################

function link_comments ($content) {
//generates the comments invitation link (works within postings-loop)
global $settings;
global $postings;
global $currentid;
if ($postings[$currentid]['comment_on'] == "1") {

    if (!isset($_GET['id'])) {
        $return = "<a href=\"index.php?id=" .$currentid. "#comments\"
                    title=\"Link to comments\">";
        $return .= fullparse (stripcontainer ($content));
        $return .= " (".countcomments($currentid).")</a>\n";
    } else {
        $return = fullparse (stripcontainer ($content));
        $return .= " (".countcomments($currentid).")\n";
    }
} else { $return = ""; }
return $return;
}

#################################################
#################################################

/**
 * returns the title of a posting (works within postings-loop)
 *
 * @return unknown
 */
function title() {
    global $postings, $currentid;
    return $postings[$currentid]['title'];
}

#################################################
#################################################

/**
 * returns the full message of a posting (works within postings-loop)
 *
 * @return unknown
 */
function message() {
    global $postings, $currentid;
    return $postings[$currentid]['message_html'];
}

#################################################
#################################################

function link_audio($content) {
//generates the link to the audiofile (works within postings-loop)
global $settings;
global $currentid;
global $postings;

//redirect or direct link?
if ($settings['countweb'] == "1") {
    $before = "get.php?web=";
} else {
    $before = "audio/";
}

if (!veryempty($postings[$currentid]['audio_file'])) {
    if ($postings[$currentid]['filelocal'] == 1) {
        $audio = $before . $postings[$currentid]['audio_file'];
    } else {
        $audio = $postings[$currentid]['audio_file'];
        if ($settings['countweb'] == "1") {
            $audio = $before.$audio;
        }
    }

    $return = "<a href=\"". $audio . "\" title=\"".$postings[$currentid]['title']."\">";
    $return .= fullparse (stripcontainer ($content));
    $return .= "</a>\n";
    return trim($return);
} else {
    return "";
}
}

#################################################
#################################################

function audiosize() {
//returns the filesize of an audio-file (works within postings-loop)
global $postings;
global $currentid;
return getmegabyte($postings[$currentid]['audio_size']);
}

#################################################
#################################################

/**
 * returns the length of an audio-file (works within postings-loop)
 *
 * @return unknown
 */
function audiolength() {
    global $postings, $currentid;
    return getminutes($postings[$currentid]['audio_length']);
}

#################################################
#################################################

function audiotype() {
//returns the type of an audio-file (works within postings-loop)
global $postings;
global $currentid;
return getmediatypename($postings[$currentid]['audio_type']);
}

#################################################
#################################################

function flashplayer($content) {
//puts emff-flash-app OR quicktime player on screen (works within postings-loop)
global $postings;
global $currentid;
global $settings;
$att = getattributes($content);
$audioexamine = "";
$return = "";

//decide: flash or quicktime
$thetype = $postings[$currentid]['audio_type'];

//redirect or direct link? Only Flash can be redirected for counting
if (($thetype == "1") AND ($settings['countfla'] == "1")) {
    $before = "get.php?fla=";
} else {
    if ($postings[$currentid]['filelocal'] == "1") {
        $before = "audio/";
        $audioexamine = $GLOBALS['audiopath'].$postings[$currentid]['audio_file'];
    } else {
        $before = "";
    }
}

if (!veryempty($postings[$currentid]['audio_file'])) {

//possible attributes and default-values
if (isset($att['width']))  { $width  = $att['width']; }  else { $width  = 200; }
if (isset($att['height'])) { $height = $att['height']; } else { $height = 62; }
if ($postings[$currentid]['filelocal'] == 1) {
    $audio = $before.$postings[$currentid]['audio_file'];
} else {
    $audio = $postings[$currentid]['audio_file'];
    if ($settings['countfla'] == "1") {
        $audio = $before.$audio;
    }
}

//if mp3, show flash!
if ($thetype == "1") {
    $return .= showflash(
    "loudblog/custom/templates/".$settings['template']."/emff.swf?src=".$audio,
    $width, $height);
}



//if quicktime audio
if (($thetype == "2") OR ($thetype == "5") OR ($thetype == "6") OR ($thetype == "9") OR ($thetype == "12") OR ($thetype == "14")) {

    //making an absolute link
    if ($postings[$currentid]['filelocal'] == "1") {
        $audio = $settings['url'] . "/" . $audio;
    }

    //Make width and height for showing Enhanced Podcast!
    if ($thetype == "14") {
        $fileinfo = getid3data($audioexamine,"front");
        if ($fileinfo['width'] != "") {
            $width = $fileinfo['width'];
            $height = $fileinfo['height'] + 16;
            $href = "loudblog/backend/clicktoplayvideo.mov";
        } else {
            $height = "16";
            $href = "loudblog/backend/clicktoplayaudio.mov";
        }
    } else {
        $height = "16";
        $href = "loudblog/backend/clicktoplayaudio.mov";
    }

   //build html-code for quicktime plugin (audio)
   $return .= showquicktime($href, $audio, $width, $height, "myself", "false");
}



//if quicktime video
if (($thetype == "7") OR ($thetype == "10") OR ($thetype == "13")) {

    //making an absolute link
    if ($postings[$currentid]['filelocal'] == "1") {
        $audio = $settings['url'] . "/" . $audio;
        $target = "myself";
    } else {
        $target = "quicktimeplayer";
    }

    //getting width and height from getid3()
    $fileinfo = getid3data($audioexamine,"front");

    //build html-code for quicktime plugin (video)
    $return .= showquicktime("loudblog/backend/clicktoplayvideo.mov",
                    $audio, $fileinfo['width'], ($fileinfo['height']+16), $target, "false");
}


return $return;

} else { return ""; }

}

#################################################
#################################################

function commentlimit() {
//returns the maximal size of an audio comment (works within postings-loop)
global $postings;
global $currentid;
if ($postings[$currentid]['comment_size'] > 0) {
    $tech = uploadlimit();
    $user = $postings[$currentid]['comment_size'];
    if ($tech <= $user) { $show = $tech; } else { $show = $user; }
    return getmegabyte($show);
} else {
    return "";
}
}

#################################################
#################################################

function author() {
//returns the author of a posting (works within postings-loop)
global $postings;
global $currentid;
return getnickname($postings[$currentid]['author_id']);
}

#################################################
#################################################

function authorfullname() {
//returns the full name of the author of a posting (works within postings-loop)
global $postings;
global $currentid;
return getfullname($postings[$currentid]['author_id']);
}

#################################################
#################################################

function loop_postingcats($content) {
//returns all defined categories (works within postings-loop)
global $postings;
global $currentid;
global $currentcat;

$content = stripcontainer($content);
$return = "";

for ($i=1; $i < 5; $i++) {
    $tempcat = "category".$i."_id";
    if ($postings[$currentid][$tempcat] != 0) {
        $currentcat = $postings[$currentid][$tempcat];
        $return .= fullparse ($content);
    }
}

$lastchars = strlen(strrchr($content,">"))-1;
if ($lastchars > 0) {
    $return = substr($return,0,-$lastchars);
}

return $return;
}

#################################################
#################################################
function loop_postingtags($content) {
//returns all tags attached to a posting (works within postings-loop)
global $postings;
global $currentid;
global $currenttag;

$content = stripcontainer($content);
$return = "";

$tags = $postings[$currentid]['tags'];
if(veryempty($tags)) {$return = "";}
else  {
$tags = explode(" ", $tags);

foreach ($tags as $tag) {
	//ignore empty tags and duplicates
	if($tag == '') continue;
	if(isset($temparray[$tag])) continue;
	$temparray[$tag] = 1;
	}
	$tagnames = array_keys($temparray);
	//sort the tags into alphabetical order
	sort($tagnames);
	foreach ($tagnames as $tig)  {
	$currenttag = $tig;
	$return .= fullparse ($content);
}
$lastchars = strlen(strrchr($content,">"))-1;
if ($lastchars > 0) {
    $return = substr($return,0,-$lastchars);
}

return $return;
}
}
##############################################
##############################################
function postingcat($content) {
//returns a category belonging to a posting (works within postings-loop)
global $currentcat;

$att = getattributes($content);
$tempname = getcategory($currentcat);
$templinkname = killentities($tempname);

if ((isset($att['link'])) AND ($att['link'] == "true")) {
    $return = "<a href=\"index.php?cat=".$templinkname."\"
           title=\"All postings of category".stripusc($tempname)."\">".stripusc($tempname)."</a>";
} else {
    $return = $tempname;
}

return $return;
}

#################################################
#################################################
function postingtag($content) {
//returns a tag belonging to a posting (works within postings-loop)
global $currenttag;

$att = getattributes($content);

$templinkname = killentities($currenttag);
$tag = stripusc($currenttag);
if ((isset($att['link'])) AND ($att['link'] == "true")) {
    $return = "<a href=\"index.php?tag=".$templinkname."\"
           title=\"All postings with tag".$tag."\">".$tag."</a>";
} else {
    $return = $tag;
}

return $return;
}
#############################################
#############################################

function posted($content) {
//returns the publishing-date/time of a posting (works within postings-loop)
global $postings;
global $currentid;
global $settings;
$att = getattributes($content);
if (isset($att['format'])) { $format = $att['format']; }
else { $format = $settings['dateformat']; }
return date($format, strtotime($postings[$currentid]['posted']));
}

#################################################
#################################################

function loop_postings ($content) {
//returns a certain number of postings
global $currentid;
global $postings;
global $nextpage;
global $howmanypages;
global $settings;
$att = getattributes($content);
$content = stripcontainer($content);

//preview postings from the admin pages
if ((isset($_GET['preview']))AND($_GET['preview']=="1")AND($settings['previews'] == "1"))
   {$preview = "true";}
             else  {$preview = "false";}

//possible attributes and default-values
if (isset($att['sort'])) { $sort = killevilcharacters($att['sort']); } 
		else { $sort = "posted"; }
if (isset($att['number'])) { $loops = $att['number']; } 
		else { $loops = 5; }
if (isset($att['order'])) { $order = strtoupper($att['order']); }
        else { $order = "DESC"; }
if (isset($att['forceloop'])) { $forceloop = $att['forceloop']; }
        else { $forceloop = "false"; }
if (isset($att['paging'])) { $paging = $att['paging']; }
        else { $paging = "true"; }
if (isset($att['static'])) { $static = $att['static']; }
        else { $static = "false"; }
if (isset($att['stickies'])) { $stickies = $att['stickies']; }
		else { $stickies = "true"; }
$return = "";
$howmanypages = "1";

//offset / splitting into pages
if ((isset($_GET['page'])) AND ($paging == "true")) {
    $start = $loops*($_GET['page']-1);
} else { $start = 0; }

//no request from url? show us a loop of postings!
if ((!isset($_GET['id'])) OR ($forceloop == "true") OR ($static == "true")) {

    //getting data from postings-table

    $trunk  = " FROM ".$GLOBALS['prefix']."lb_postings WHERE ";

    //showing postings from certain date
    if ((isset($_GET['date'])) AND ($static == "false")) {

        //analyzing the length of the date-string
        switch (strlen($_GET['date'])) {
        case 4:
            //show us a year!
            $from = $_GET['date'] . "-01-01 00:00:00";
            $to   = $_GET['date'] . "-12-31 23:59:59";
            $trunk .= "posted >= '".$from."' AND ";
            $trunk .= "posted <= '".$to . "' AND ";
            break;

        case 7:
            //show us a month!
            $from = $_GET['date'] . "-01 00:00:00";
            $to   = $_GET['date'] . "-31 23:59:59";
            $trunk .= "posted >= '".$from."' AND ";
            $trunk .= "posted <= '".$to . "' AND ";
            break;

        case 10:
            //show us a day!
            $from = $_GET['date'] . " 00:00:00";
            $to   = $_GET['date'] . " 23:59:59";
            $trunk .= "posted >= '".$from."' AND ";
            $trunk .= "posted <= '".$to."' AND ";
        }
    }

    //posting must be "live" to be displayed
    $trunk .= "status = '3' ";

    //posting must not be published in the future
    if ($static == "false") {
    	$trunk .= "AND posted < '".date("Y-m-d H:i:s")."' ";
    }

    //if tag is set, filter postings which doesn't fit
	// we switched to tags instead of categories in 0.7.0 and provided both in 0.8.0
	if (isset($_GET['tag']) AND ($static == "false")) {  
		$tagsToShow = explode('+', $_GET['tag']);
		$tagSQL = array();
		foreach ($tagsToShow as $tagToShow){ 
			$tagSQL[] = ' tags LIKE \'%'.$tagToShow.'%\'';
		}
		$trunk .= ' AND ('.join(' OR ', $tagSQL). ') ';

	}
	
    //if category is set, filter postings which doesn't fit
    if ( (isset($_GET['cat'])) AND ($static == "false")) {

        //which category-id do we request via url?
        $tempcatid = getcategoryidshort($_GET['cat']);
        if ($tempcatid != "") {
            $trunk .= "AND (category1_id = ". $tempcatid . " ";
            $trunk .= "OR category2_id = ". $tempcatid . " ";
           	$trunk .= "OR category3_id = ". $tempcatid . " ";
            $trunk .= "OR category4_id = ". $tempcatid . ") ";
        }
    }

	$trunk .= $stickies == 'false' ? "ORDER BY $sort $order" : "ORDER BY sticky DESC, $sort $order";
//count total number of posts and calculate the number of pages
//the global variable $howmanypages can be used to construct a paging plugin
 $countingquery = "SELECT COUNT(*)".$trunk ;
 $count = $GLOBALS['lbdata']->GetArray($countingquery);
 $total = $count[0]['COUNT(*)'];
 $howmanypages = round($total / $loops);
 if ($howmanypages < ($total / $loops)) { $howmanypages += 1; }

//now we execute the main query
 $dosql = "SELECT * ".$trunk;
	$tempp = $GLOBALS['lbdata']->SelectLimit($dosql, $loops+1, $start);
    $allrows = $tempp->GetArray();
    
	$i = 0;
    //use all results!
    foreach ($allrows as $temp) {
        $i += 1;
        if ($i <= $loops) {
            $currentid = $temp['id'];
            $postings[$currentid] = $temp;
            $return .= fullparse ($content);
        //if there is one more posting than requested, we can show a "next page"-button.
        } else {
            if ($paging == "true") { $nextpage = true; }
        }
    }


} else {   //ah, we want to show a single posting with a given id? no problem!

    //getting data from postings-table
    $dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_postings
              WHERE id='" . $_GET['id'] . "'";
		//are previews allowed?
	       if ($preview == "false")
            {$dosql .="AND posted < '".date("Y-m-d H:i:s")."' AND status='3'";}

               $temp = $GLOBALS['lbdata']->GetArray($dosql);

    $currentid = $temp[0]['id'];
    $postings[$currentid] = $temp[0];
    $return .= fullparse ($content);
}
return trim($return);
}

//--------------------------------------------------------------------
//  HYPERLINK TAGS
//--------------------------------------------------------------------

#################################################
#################################################

function link_hyperlink($content) {
//generates the url for an given hyperlink (works within hyperlinks-loop)
global $links;
global $currentlink;
$return = "<a href=\"". $links[$currentlink]['url'] . "\" title=\"" .
      $links[$currentlink]['description'] . "\">";
$return .= fullparse (stripcontainer ($content));
$return .= "</a>\n";
return $return;
}

#################################################
#################################################

function hyperlinkname() {
//returns the "name" of the a hyperlink (works within hyperlinks-loop)
global $links;
global $currentlink;
return $links[$currentlink]['title'];
}

#################################################
#################################################

function hyperlinkdescription() {
//returns the "description" of the a hyperlink (works within hyperlinks-loop)
global $links;
global $currentlink;
return $links[$currentlink]['description'];
}

#################################################
#################################################

function loop_hyperlinks($content) {
//returns all defined hyperlinks of a posting (works within postings-loop)
global $currentid;
global $postings;
global $links;
global $currentlink;

$att = getattributes($content);
$content = stripcontainer($content);

$return = "";

//getting some data from postings-table
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_links
          WHERE posting_id='".$currentid."' ORDER BY linkorder ASC;";
$result = $GLOBALS['lbdata']->GetArray($dosql);

foreach ($result as $temp) {
    $currentlink = $temp['id'];
    $links[$currentlink] = $temp;
    $return .= fullparse ($content);
}
return trim($return);
}

//--------------------------------------------------------------------
//  COMMENTING TAGS
//--------------------------------------------------------------------

#################################################
#################################################

function area_comments ($content) {
//only parse this area if comment stuff is to be shown
global $settings;
global $currentid;
global $postings;
global $tempfilename;

$tempfilename = "";
$return = "";
$freshfile = false;

//before we show stuff, we have to handle data from post or save things in database and so on...


//check if there is a new uploadad file and make a shorter meta data variable
if ((isset($_FILES['commentfile']))
    AND ($_FILES['commentfile']['error'] == "0")) {
    $freshfile = $_FILES['commentfile'];
}

//We are only previewing?
if (isset($_POST['commentpreview'])) {

    //add http:// to previewed urls
    if (substr($_POST['commentweb'],0,4) != "http") {
        $_POST['commentweb'] = "http://".$_POST['commentweb'];
    }

    //a new posted file has the highest priority
    if (($freshfile != false)
        AND (checksuffix($freshfile['name']))
        AND ($freshfile['size'] <= $postings[$currentid]['comment_size']))
    {
       	$tempfilename = freshaudioname(strrchr($freshfile['name'], "."), "temp");
       	//put the uploaded file into the desired directory
       	move_uploaded_file($freshfile['tmp_name'],
       		$GLOBALS['audiopath'].$tempfilename)
       	OR die ("<p>Error!</p>");

       	//change the chmod
       	chmod ($GLOBALS['audiopath'].$tempfilename, 0777);
    }
    else
    {
        //put previously uploaded file through to another preview
        if (isset($_POST['filethrough']))
        {
            $tempfilename = $_POST['filethrough'];
        }
        if ($freshfile['size'] > $postings[$currentid]['comment_size'])
        {
        	die("<p>Sorry! The file size of your audio comment is too big.<p>");
        }
    }
}

//oh, we are submitting? It's getting serious!
if (isset($_POST['commentsubmit'])) {

    //in dubio contra audio
    $audioexists = false;

    //do a lot of things, if we have got a new uploaded file
    if (($freshfile != false)
            AND (checksuffix($freshfile['name']))) {
        $filename = freshaudioname (strrchr($freshfile['name'], "."), "comment");

        //put the uploaded file into the desired directory
        move_uploaded_file($freshfile['tmp_name'], $GLOBALS['audiopath'].$filename)
            OR die ("<p>Error!</p>");
        $audioexists = true;

    //but we can take the previewed audio file, too...
    } else {
        if (isset($_POST['filethrough'])) {
            //rename audio file and get audio meta data
            $tempfilename = $_POST['filethrough'];
            $filename = freshaudioname (strrchr($tempfilename, "."), "comment");
            rename($GLOBALS['audiopath'].$tempfilename, $GLOBALS['audiopath'].$filename)
                OR die ("<p>Error!</p>");
            $audioexists = true;
        }
    }

    //there is an audio file?
    if ($audioexists) {
        //get metadata from getid3-class
        $id3 = getid3data($GLOBALS['audiopath'].$filename,"front");
    } else {
        //make empty values for audio data (cause we dont have audio data)
        $filename = "";
        $id3['duration'] = "0:00";
        $id3['size'] = 0;
    }

    //prepare non-audio data
    if ($_POST['commentname'] == "") { $name = "Anonymous"; }
        else { $name = htmlentities(strip_tags($_POST['commentname']), ENT_QUOTES, "UTF-8"); }
    $mail = strip_tags($_POST['commentmail']);
    $web = strip_tags($_POST['commentweb']);
    $ip = $_SERVER['REMOTE_ADDR'];
    $message_input = change_entities($_POST['commentmessage']);
    $message_html = trim(no_amp(makehtml(htmlentities($_POST['commentmessage'], ENT_QUOTES, "UTF-8"))));

    //write data into database (doesn't matter, with or without audio)
    $dosql = "INSERT INTO {$GLOBALS['prefix']}lb_comments
             (posting_id, posted, name, mail, web, ip, message_input, message_html,
            audio_file, audio_type, audio_length, audio_size)
            VALUES
            (
            '".$currentid."',
            '".date('Y-m-d H:i:s')."',
            '".$name."', '".$mail."', '".$web."', '".$ip."',
            '".$message_input."', '".$message_html."',
            '".$filename."',
            '".type_suffix($filename)."',
            '".getseconds($id3['duration'])."',
            '".$id3['size']."'
            );";
    
    //last controls before we put the data into the database
    $commentingokay = true;
	if ($settings['preventspam'] == "1") {
		if (isset($_POST['commentspam'])) {
			$givenanswer = trim(strtolower($_POST['commentspam']));
			$rightanswer = trim(strtolower($settings['spamanswer']));
			if ($givenanswer != $rightanswer) { 
				$commentingokay = false; 
				echo "<p style=\"font-size: 20px;\">Possible spam attack! Don't do this again!</p>\n";
			}			
		} else {
			$commentingokay = false;
			echo "<p style=\"font-size: 20px;\">Possible spam attack! (The administrator of this podcast has to deactivate anti spam or add an appropriate input field to the template.)</p>\n";
		}
	}
    if ($settings['acceptcomments'] == "0") { $commentingokay = false; }
    
    if ($commentingokay) {		
		//finally!!
		$GLOBALS['lbdata']->Execute($dosql);
		//sending an email to author of the posting
		notify ($postings[$currentid], $name, $mail, $web, $message_html);
		//looking for orphaned comments
		deleteorphans();
	}

}
//submitting actions are finished. thank you for your attention.

//do we show comments at all?
if ((isset($_GET['id'])) AND ($postings[$currentid]['comment_on'] == 1)) {
    $return .= "<div id=\"comments\">\n";
    $return .= fullparse (stripcontainer($content));
    $return .= "\n</div>";
} else { $return = ""; }

return $return;
}

#################################################
#################################################

function loop_comments ($content) {
//show a loop of all comments of a certain posting
global $currentid;
global $currentcomment;
global $comments;
global $tempfilename;
global $allcomm;

$att = getattributes($content);
if (isset($att['global'])) { $allcomm = $att['global']; }
else { $allcomm = "false"; }
if (isset($att['number'])) { $number = $att['number']; }
else { $number = 5; }


$content = trim(stripcontainer($content));
$return = "";

//do we get the comments of the current posting?
if ($allcomm == "false") {

    //getting some data from comments-table
    $dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_comments
              WHERE posting_id = ".$currentid." ORDER BY posted ASC;";
    $result = $GLOBALS['lbdata']->Execute($dosql);
    $comments = $result->GetArray();
    if ($comments == false) {
        $numbcom = 0;
    } else {
        $numbcom = count($comments);
    }

    //only here for previewing?
    if (isset($_POST['commentpreview'])) {

        if ($tempfilename != "") {
            $id3 = getid3data($GLOBALS['audiopath'].$tempfilename,"front");
            $tempfilesize = $id3['size'];
            $tempfilelength = getseconds($id3['duration']);
        } else {
            $tempfilesize = "0";
            $tempfilelength = "0";
        }

        $comments[$numbcom]['id'] = 0;
        $comments[$numbcom]['posting_id'] = $currentid;
        $comments[$numbcom]['posted'] = date('Y-m-d H:i:s');
        if ($_POST['commentname'] == "") {
            $comments[$numbcom]['name'] = "Anonymus";
        } else {
            $comments[$numbcom]['name'] =
            htmlentities(strip_tags($_POST['commentname']),
            ENT_QUOTES, "UTF-8");
        }
        $comments[$numbcom]['mail'] = strip_tags($_POST['commentmail']);
        $comments[$numbcom]['web'] = strip_tags($_POST['commentweb']);

        $comments[$numbcom]['ip'] = $_SERVER['REMOTE_ADDR'];
        $comments[$numbcom]['message_input'] = $_POST['commentmessage'];
        $comments[$numbcom]['message_html'] =
            "<p>[PREVIEW]</p> " . strip_tags(no_amp(makehtml(htmlentities($_POST['commentmessage'], ENT_QUOTES, "UTF-8"))));
        $comments[$numbcom]['audio_file'] = $tempfilename;
        $comments[$numbcom]['audio_size'] = $tempfilesize;
        $comments[$numbcom]['audio_length'] = $tempfilelength;
        $comments[$numbcom]['audio_type'] = type_suffix($tempfilename);
    }


//okay, we show a list af ALL recent comments
} else {

$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_comments ORDER BY posted DESC";
$result = $GLOBALS['lbdata']->SelectLimit($dosql,$number,0);
$comments = $result->GetArray();
if ($comments == false) { $comments = array(); }

}

//show every comment, one by one
$i = 0;
foreach ($comments as $thiscomment) {
   $currentcomment = $i;
   if ($allcomm == "false") {
       $return .= "<span id=\"com".$thiscomment['id']."\"></span>";
   }
   $return .= fullparse($content);
   $i += 1;
}

return $return;
}

#################################################
#################################################

function commentname () {
//returns the commentator's name on the comments list
global $currentcomment;
global $comments;
global $allcomm;

$link = "";
$name = trim(stripslashes($comments[$currentcomment]['name']));
$mail = trim(stripslashes($comments[$currentcomment]['mail']));
$web  = trim(stripslashes($comments[$currentcomment]['web']));

if ($allcomm == "false") {
//if ($mail != "") { $link = "mailto:".$mail; }
if (($web != "") AND ($web != "http://")) { $link = $web; }
if ($link != "") {
    $return = "<a href=\"".$link."\">".$name."</a>";
} else $return = $name;
} else $return = $name;

return $return;
}

#################################################
#################################################

function link_permacomment ($content) {
//generates the permalink for the current comment
global $settings;
global $comments;
global $currentcomment;

$return =  "<a href=\"index.php?id=" .$comments[$currentcomment]['posting_id'];
$return .= "#com".$comments[$currentcomment]['id']. "\"
           title=\"Permanent link to this comment\">";
$return .= fullparse (stripcontainer ($content));
$return .= "</a>\n";
return $return;
}

#################################################
#################################################

function commentparent () {
//returns the title of the related posting
global $currentcomment;
global $comments;
global $settings;

$dosql = "SELECT title FROM ".$GLOBALS['prefix']."lb_postings
          WHERE id = ".$comments[$currentcomment]['posting_id'].";";
$row = $GLOBALS['lbdata']->GetArray($dosql);

return $row[0]['title'];
}

#################################################
#################################################

function commentposted ($content) {
//returns the date on the comments list
global $currentcomment;
global $comments;
global $settings;
$att = getattributes($content);
if (isset($att['format'])) { $format = $att['format']; }
else { $format = $settings['dateformat']; }

return date($format, @strtotime($comments[$currentcomment]['posted']));
}

#################################################
#################################################

function commentmessage ($content) {
//returns the message on the comments list
global $currentcomment;
global $comments;
$att = getattributes($content);

if (isset($att['length'])) {
    return substr(strip_tags($comments[$currentcomment]['message_html']),0,$att['length'])."...";
} else { return $comments[$currentcomment]['message_html']; }
}

#################################################
#################################################

function link_commentfile ($content) {
//shows a link to the file on the comments list
global $currentcomment;
global $comments;

if ($comments[$currentcomment]['audio_file'] != "") {
    $return  = "<a href=\"audio/".$comments[$currentcomment]['audio_file'];
    $return .= "\" title=\"Link to audio file\">";
    $return .= fullparse (stripcontainer ($content));
    $return .= "</a>\n";
} else { $return = ""; }
return $return;
}

#################################################
#################################################

function commentsize () {
//returns the audio size on the comments list
global $currentcomment;
global $comments;
return getmegabyte($comments[$currentcomment]['audio_size']);
}

#################################################
#################################################

function commentlength () {
//returns the audio length on the comments list
global $currentcomment;
global $comments;
return getminutes($comments[$currentcomment]['audio_length']);
}
#################################################
#################################################

function commentflashplayer($content) {
//puts emff-flash-app on screen
global $currentcomment;
global $comments;
global $settings;
$att = getattributes($content);

if ($comments[$currentcomment]['audio_type'] == 1) {

//possible attributes and default-values
if (isset($att['width']))  { $width  = $att['width']; }  else { $width  = 200; }
if (isset($att['height'])) { $height = $att['height']; } else { $height = 62; }
$audio = "audio/" . $comments[$currentcomment]['audio_file'];

//build html-code
$return  = "<object type=\"application/x-shockwave-flash\" ";
$return .= "data=\"loudblog/custom/templates/" . $settings['template'];
$return .= "/emff_comments.swf?src=" . $audio . "\" ";
$return .= "width=\"$width\" height=\"$height\">\n";
$return .= "<param name=\"movie\" value=\"loudblog/custom/templates/";
$return .= $settings['template'] . "/emff_comments.swf?src=" . $audio . "\" />\n";
$return .= "</object>\n";

return $return;

} else { return ""; }
}


#################################################
#################################################

function area_makecomment ($content) {
//returns the form-tag with some hidden fields
global $settings;
global $currentid;
global $postings;
global $tempfilename;

if ((!isset($_POST['commentsubmit'])) && ($settings['acceptcomments'] == "1")) {
    $return = "<form method=\"post\"
               action=\"index.php?id=".$currentid."#comments\"
               enctype=\"multipart/form-data\">\n";
    $return .= fullparse (stripcontainer($content));

    //if a temporary audio comment file has been uploaded: send path!
    if ((isset($_POST['filethrough'])) OR
            ((isset($_POST['commentpreview'])) AND
            (isset($_FILES['commentfile'])) AND
            ($_FILES['commentfile']['error'] == "0"))) {
        $return .= "<input type=\"hidden\" ";
        $return .= "name=\"filethrough\" value=\"".$tempfilename."\" />\n\n";
    }
    $return .= "</form>";
} else { $return = ""; }

return $return;
}

#################################################
#################################################

function inputname () {
//returns the input field for commentor's name
if (isset($_POST['commentname'])) {
    $value = strip_tags(trim(change_entities(stripslashes($_POST['commentname']))));
} else { $value = ""; }
return "<input type=\"text\" name=\"commentname\" id=\"commentname\" value=\"".$value."\" />";
}

#################################################
#################################################

function inputmail () {
//returns the input field for commentor's mail address
if (isset($_POST['commentmail'])) {
    $value = strip_tags(trim(stripslashes($_POST['commentmail'])));
} else { $value = ""; }
return "<input type=\"text\" name=\"commentmail\" id=\"commentmail\" value=\"".$value."\" />";
}

#################################################
#################################################

function inputweb () {
//returns the input field for commentor's website
if (isset($_POST['commentweb'])) {
    $value = strip_tags(trim(stripslashes($_POST['commentweb'])));
} else { $value = ""; }
return "<input type=\"text\" name=\"commentweb\" id=\"commentweb\" value=\"".$value."\" />";
}

#################################################
#################################################

function inputmessage($content) {
//returns the textarea for commentor's message
//amended to add row and column attributes to text area - needed in valid xhtml
//lb:inputmessage now supports 2 optional attributes - cols="[no of columns]" and rows="[no of rows]"
//default values are 40 for cols and 30 for rows
$att = getattributes($content);
if (isset($att['cols'])) {$cols = $att['cols'];}
	else {$cols = "40";}
if (isset($att['rows']))  {$rows = $att['rows'];}
	else {$rows = "30";}

if (isset($_POST['commentmessage'])) {
      $value = strip_tags(trim(stripslashes($_POST['commentmessage'])));
} else { $value = ""; }
return "<textarea name=\"commentmessage\" id=\"commentmessage\" rows=\"".$rows."\" cols=\"".$cols."\">".$value."</textarea>";
}

#################################################
#################################################

function inputfile ($content) {
//returns the input field for commentor's audio file
global $currentid;
global $postings;
if ($postings[$currentid]['comment_size'] > 0) {
    return "<input type=\"file\" name=\"commentfile\" accept=\"audio/*\" id=\"commentfile\" />";
} else {
    return "<p>No audio file allowed</p>";
}
}

#################################################
#################################################

function inputspam () {
//returns the input field for the spam answer
global $settings;
if (isset($_POST['commentspam'])) {
    $value = strip_tags(trim(change_entities(stripslashes($_POST['commentspam']))));
} else { $value = ""; }
return "<input type=\"text\" name=\"commentspam\" id=\"commentspam\" value=\"".$value."\" />";
}

#################################################
#################################################

function spamquestion () {
//returns the spam question term from database
global $settings;
return $settings['spamquestion'];
}

#################################################
#################################################

function buttonpreview () {
//returns the preview button
return "<input type=\"submit\" name=\"commentpreview\" id=\"commentpreview\" value=\"preview\" />";
}

#################################################
#################################################

function buttonsend () {
//returns the send button, if all requirements are matched
if (
   (  (isset($_POST['commentmessage'])) AND (trim($_POST['commentmessage']) != "")   )
   OR
   (  (isset($_FILES['commentfile'])) AND ($_FILES['commentfile']['error'] == "0")  )
   OR
   (  (isset($_POST['filethrough'])) AND ($_POST['filethrough'] != "")   )
   ) {
    return "<input type=\"submit\" name=\"commentsubmit\"
            id=\"commentsubmit\" value=\"send comment\" />";
} else { return ""; }
}




//--------------------------------------------------------------------
//  CATEGORY TAGS
//--------------------------------------------------------------------

#################################################
#################################################

function link_category($content) {
//creates the link to the currently parsed category (works within category-loop)
global $currentcat;
global $cats;
global $settings;
$return = "<a href=\"index.php?cat=" .
           killentities($cats[$currentcat]['name']) . "\"
           title=\"" . $cats[$currentcat]['description']."\">";
$return .= fullparse (stripcontainer($content));
$return .= "</a>\n";
return $return;
}

#################################################
#################################################

function link_catfeed($content) {
//creates the link to the currently parsed category-rss-feed (works within category-loop)
global $currentcat;
global $cats;
global $settings;

if ($settings['staticfeed'] == "0") {
    $return = "<a href=\"podcast.php?cat=" .
               killentities($cats[$currentcat]['name']) . "\"
               title=\"" . $cats[$currentcat]['description']."\">";
    $return .= fullparse (stripcontainer($content));
    $return .= "</a>\n";
} else {
    $return = "<a href=\"audio/" .
               killentities($cats[$currentcat]['name']) . "-rss.xml\"
               title=\"" . $cats[$currentcat]['description']."\">";
    $return .= fullparse (stripcontainer($content));
    $return .= "</a>\n";
}
return $return;
}

#################################################
#################################################

function categoryname() {
//returns the name of a listed category (works within category-loop)
global $cats;
global $currentcat;
return $cats[$currentcat]['name'];
}

#################################################
#################################################

function categorydescription() {
//returns the description of a listed category (works within category-loop)
global $cats;
global $currentcat;
return $cats[$currentcat]['description'];
}

#################################################
#################################################

function loop_categories($content) {
//returns a loop-routine for all existing categories
global $currentcat;
global $cats;

$att = getattributes($content);
$content = stripcontainer($content);

//possible attributes and default-values
if (isset($att['sortby'])) { $sortby = $att['sortby']; }
        else { $sortby = "name"; }
if (isset($att['order'])) { $order = strtoupper($att['order']); }
        else { $order = "ASC"; }

$return = "";

//getting some data from categories-table
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_categories
          ORDER BY ".$sortby." ".$order.";";
$result = $GLOBALS['lbdata']->GetArray($dosql);

foreach ($result as $temp) {
    $currentcat = $temp['id'];
    $cats[$currentcat] = $temp;
    $return .= fullparse ($content);
}
return trim($return);
}

//--------------------------------------------------------------------
//  CONDITIONAL TAGS (THOSE WITH "IF")
//--------------------------------------------------------------------

#################################################
#################################################

function if_single ($content) {
//parse content only if a single posting is being shown
if (isset($_GET['id'])) {
    return fullparse (stripcontainer($content));
} else { return ""; }
}

#################################################
#################################################

function if_list ($content) {
//parse content only if a list of posting is being shown
if (!isset($_GET['id'])) {
    return fullparse (stripcontainer($content));
} else { return ""; }
}

#################################################
#################################################

function if_preventspam ($content) {
//parse content only if preventing spam is activated
global $settings;
if ($settings['preventspam'] == "1") {
	return fullparse (stripcontainer($content));
} else { return ""; }
}

#################################################
#################################################

function if_category ($content) {
//parse content only if a (certain) category or no category is being shown

$att = getattributes($content);
$return = "";
$nocat = true;

//checking the url for a category list request
if (isset($_GET['cat'])) {
    if (!isset($att['category'])) {
        $return = fullparse (stripcontainer($content));
    } else {
        $att['category'] = htmlentities($att['category'], ENT_QUOTES, "UTF-8");
        if (getcategoryidshort($_GET['cat']) == getcategoryid($att['category'])) {
            $return = fullparse (stripcontainer($content));
        }
    }
    $nocat = false;
}

//checking url for single posting
if (isset($_GET['id'])) {
    //checking if category is available
    $dosql = "SELECT category1_id, category2_id, category3_id, category4_id
              FROM ".$GLOBALS['prefix']."lb_postings
              WHERE id='" . $_GET['id'] . "';";
    $row = $GLOBALS['lbdata']->GetAssoc($dosql);
    $show = false;
    foreach ($row as $c => $id) {
        if (!isset($att['category'])) {
            if ($id != 0) {
                $return = fullparse (stripcontainer($content));
                $nocat = false;
                break;
            }
        } else {
            $att['category'] = htmlentities($att['category'], ENT_QUOTES, "UTF-8");
            if ($id == getcategoryid($att['category'])) {
                $return = fullparse (stripcontainer($content));
                $nocat = false;
                break;
            }
        }
    }
}

//the "no category" option at the end
if (($nocat) AND (isset($att['category'])) AND ($att['category'] == "false")) {
    $return = fullparse (stripcontainer($content));
}

return trim($return);
}

#################################################
#################################################

function if_audio ($content) {
//parse content only if audio file is attached
global $postings;
global $currentid;

if (!veryempty($postings[$currentid]['audio_file'])) {
    return trim(fullparse (stripcontainer($content)));
} else { return ""; }
}

#################################################
#################################################

function if_commentaudio ($content) {
//parse content only if audio file is attached to a comment
global $currentcomment;
global $comments;

if ($comments[$currentcomment]['audio_file'] != "") {
    return trim(fullparse (stripcontainer($content)));
} else { return ""; }
}

#################################################
#################################################

function if_comments ($content) {
//parse content only if comments are availabe for this posting
global $postings;
global $currentid;

if ($postings[$currentid]['comment_on'] == "1") {
    return trim(fullparse (stripcontainer($content)));
} else { return ""; }
}

#################################################
#################################################

/**
 * parse content only if attached file is a real mp3
 *
 * @param unknown_type $content
 * @return unknown
 */
function if_mp3 ($content) {
    global $postings;
    global $currentid;

    if ($postings[$currentid]['audio_type'] == 1) {
        return trim(fullparse (stripcontainer($content)));
    } else {
        return '';
    }
}

#################################################
#################################################

/**
 * prepare php codes within template to be executed later
 *
 * @param unknown_type $content
 * @return unknown
 */
function php ($content) {
	//Turn Loudblog php tags into secret separators
	global $phpseparator;
	global $php_use;
	
	$php_use = true;
	return $phpseparator.stripcontainer($content).$phpseparator;
}

#################################################
#################################################




//--------------------------------------------------------------------
// TAGS
//--------------------------------------------------------------------

#################################################
#################################################

function link_tag($content) {
//creates the link to the currently parsed tag (works within tags-loop)
global $currenttag;
global $alltags;
global $settings;
$return = "<a href=\"index.php?tag=" .
           killentities($alltags[$currenttag]) . "\"
           title=\"" . str_replace("­"," ",$alltags[$currenttag])."\">";
$return .= fullparse (stripcontainer($content));
$return .= "</a>\n";
return $return;
}

#################################################
#################################################

function link_tagfeed($content) {
//creates the link to the currently parsed tag-rss-feed (works within tags-loop)
global $currenttag;
global $alltags;
global $settings;

if (($settings['staticfeed'] == "0") OR ($settings['staticfeeds_tags'] == "0")) {
    $return = "<a href=\"podcast.php?tag=" .
               killentities($alltags[$currenttag]) . "\"
               title=\"" . stripusc($alltags[$currenttag])."\">";
    $return .= fullparse (stripcontainer($content));
    $return .= "</a>\n";
} else {
    $return = "<a href=\"audio/" .
               killentities($alltags[$currenttag]) . "-rss.xml\"
               title=\"" . stripusc($alltags[$currenttag])."\">";
    $return .= fullparse (stripcontainer($content));
    $return .= "</a>\n";
}
return $return;
}

#################################################
#################################################

function tagname() {
//returns the name of a listed tag (works within tags-loop)
global $alltags;
global $currenttag;
return stripusc($alltags[$currenttag]);
}

#################################################
#################################################

//function tagdescription() {
//returns the description of a listed category (works within category-loop)
//global $cats;
//global $currentcat;
//return $cats[$currentcat];
//}

#################################################
#################################################

function loop_tags($content) {
//returns a loop-routine for all existing tags
global $currenttag;
global $alltags;

$att     = getattributes($content);
$content = stripcontainer($content);

$tags = gettaglist();
$return = '';
foreach ($tags as $tag) {
	$currenttag = $tag;
	$alltags[$currenttag] = $tag;
	$return .= fullparse($content);
}
return trim($return);
}

#################################################
#################################################

function tagweight() {
global $currenttag;
$tags = gettaglist(true);
$f = 5;                    // maximum Font Size (we use CSS classes)
$t_i = $tags[$currenttag]; 
$t_min = 1; 
$t_max = max($tags); 

$s_i = $t_i > $t_min ? (  ($f * ($t_i-$t_min) )  /  ($t_max-$t_min)   ) : $t_min;

return (int) ceil($s_i);
}
#################################################
#################################################

function tagcloud()    {
//outputs a string containing all tags.
//Each tag is placed in a span, with a class representing its tag-weight.
//CSS can be used to style the classes tagweight1 a, tagweight2 a, etc up to tagweight5 a.

$weights = gettaglist(true);
$taglist = array_keys($weights);
$return="<p>";
foreach ($taglist as $tag)  {
        $f = 5;
        $t_i = $weights[$tag];
        $t_min = 1;
        $t_max = max($weights);

        $s_i = $t_i > $t_min ? (  ($f * ($t_i-$t_min) )  /  ($t_max-$t_min)   ) : $t_min;

        $tagweight =(int) ceil($s_i);
	  
        $return .= "<span class=\"tagweight".$tagweight."\"><a href=\"index.php?tag=".killentities($tag)."\" title=\"All postings tagged ".stripusc($tag)."\">".stripusc($tag)."</a></span> ";
        }
$return .="</p>";
return $return;

}
##################################################
##################################################

?>