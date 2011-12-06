<?php header("Content-Type: text/xml; charset=utf-8");

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
//version with Bad Behaviour commented out
//include database stuff and functions
include "loudblog/custom/config.php";
include "loudblog/inc/database/adodb.inc.php";
include "loudblog/inc/connect.php";
include "loudblog/inc/functions.php";

cleanmygets();

//create some important globals
if (!isset($db['host'])) { die("<br /><br />Cannot find a valid configuration file! <a href=\"install.php\">Install Loudblog now!</a>"); }
$GLOBALS['prefix'] = $db['pref'];
$GLOBALS['path'] = $lb_path;
$GLOBALS['audiopath'] = $lb_path . "/audio/";
global $cat_table;

//getting basic data
$settings = getsettings();
dumpdata();
itunescats();

// here comes bad behavior (COMMENTED OUT)...
//require_once $lb_path . '/loudblog/inc/bad_behavior.php';
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

//getting data from postings-table
$dosql  = "SELECT * FROM {$GLOBALS['prefix']}lb_postings WHERE "; 

//do we have only a single posting to show?
if (isset($_GET['id'])) {
	$requested_id = (int)($_GET['id']);
    $dosql .= "id = '".$requested_id."' AND ";
}

//posting must be "live" to be displayed
$dosql .= "status = '3' ";
    
//posting must not be published in the future
$dosql .= "AND posted < '".date("Y-m-d H:i:s")."' ";
    
//if category is set, filter postings which don't fit

if (isset($_GET['cat'])) {
    //which category-id do we request via url?
    $tempcatid = getcategoryidshort($_GET['cat']);
    if (filled($tempcatid)) {
        $dosql .= "AND (category1_id = ". $tempcatid . " ";
        $dosql .= "OR category2_id = ". $tempcatid . " ";
        $dosql .= "OR category3_id = ". $tempcatid . " ";
        $dosql .= "OR category4_id = ". $tempcatid . ") ";
    }
}

// if tag is set .....
    
if (isset($_GET['tag'])) {
	$tagsToShow = explode('+', $_GET['tag']);
	$tagSQL = array();
	$taglist = array();
	foreach ($tagsToShow as $tagToShow){ 
		$taglist[] = $tagToShow;
		$tagSQL[] = ' tags LIKE \'%'.$tagToShow.'%\'';
	}
	$dosql .= ' AND ('.join(' OR ', $tagSQL). ') ';

}
//ordering stuff
$dosql .= "ORDER BY posted DESC";

//getting results with a certain limit, but no offset
$result = $GLOBALS['lbdata']->SelectLimit($dosql, $settings['rss_postings'], 0);
$i = 0;

//building an array with the item data
while ($rows[$i] = $result->FetchRow()) { $i += 1; }
if (isset($rows[0]['posted'])) {
	$lasttime = strtotime($rows[0]['posted']);
} else { $lasttime = 0; }
unset($rows[$i]);

//build category string
if (isset($_GET['cat'])) {	
    $catname = ": ". $_GET['cat']; // .getcategory(getcategoryidshort($_GET['cat'])); 
} else { $catname = ""; }

//build a single posting string
if (isset($_GET['id'])) { 
    $postname = ": ".$rows[0]['title']; 
} else { $urlpost = ""; $postname = ""; }



//Ready to rock'n'roll? Let's start building the feed!



echo "<rss version=\"2.0\" xmlns:itunes=\"http://www.itunes.com/dtds/podcast-1.0.dtd\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\">\n\n";
echo "<channel>\n\n";

//building the right title for our little feed
echo "<title>" . html_to_xml($settings['sitename']);
echo $catname . $postname . "</title>\n";

//build current url
$atts = "";
if (isset($_GET['id'])) { $atts = "?id=".$_GET['id']; }
if (isset($_GET['cat'])) { $atts = "?cat=".$_GET['cat']; }
echo "<link>".$settings['url'].$atts."</link>\n";

//more information about the podcast
$desc = html_to_xml($settings['description']);
$tunsum = html_to_xml($settings['description']);
$slogan = html_to_xml($settings['slogan']);
echo "<itunes:subtitle>".$slogan."</itunes:subtitle>\n";
echo "<itunes:summary>".$tunsum."</itunes:summary>\n";
echo "<description>".$desc."</description>\n";
echo "<language>".$settings['languagecode']."</language>\n";

echo "<copyright>".html_to_xml($settings['copyright'])."</copyright>\n";
echo "<itunes:owner>\n";
echo "   <itunes:name>".html_to_xml($settings['itunes_author'])."</itunes:name>\n";
echo "   <itunes:email>".$settings['itunes_email']."</itunes:email>\n";
echo "</itunes:owner>\n";
echo "<managingEditor>".$settings['itunes_email']." (".html_to_xml($settings['itunes_author']).")</managingEditor>\n";
echo "<itunes:author>".html_to_xml($settings['itunes_author'])."</itunes:author>\n";

//regular RSS-image
echo "<image>\n";
echo "   <url>".$settings['url']."/audio/rssimage.jpg</url>\n";
echo "   <title>".html_to_xml($settings['sitename'])."</title>\n";
echo "   <link>".$settings['url']."</link>\n";
echo "</image>\n";

//huge iTunes-image (done correctly)
echo "<itunes:image href=\"".$settings['url']."/audio/itunescover.jpg\" />\n";

//metadata
echo "<pubDate>" . date("r") . "</pubDate>\n";
echo "<lastBuildDate>" . date("r", $lasttime) . "</lastBuildDate>\n";
echo "<generator>Loudblog</generator>\n\n";

//explicit or not?
if ($settings['itunes_explicit'] == "1") { $setexpl = "yes"; } else { $setexpl = "no"; }
echo "<itunes:explicit>".$setexpl."</itunes:explicit>\n\n";

//iTunes categories!
$allcats = array(
    $settings['feedcat1'],
    $settings['feedcat2'],
    $settings['feedcat3'],
    $settings['feedcat4']
);
foreach ($allcats as $thiscat) {
    $main = false;
    if ($thiscat != "00-00") {
        if (substr($thiscat,3) != "00") {
            $maincat = substr($thiscat,0,2) . "-00";
            echo "<itunes:category text=\"".$cat_table[$maincat]."\">\n";
            $main = true;
        } 
        echo "<itunes:category text=\"".$cat_table[$thiscat]."\" />\n";
        if ($main) { echo "</itunes:category>\n"; }
    }
}

//normal categories
foreach ($allcats as $thiscat) {
    if (filled($cat_table[$thiscat])) {
        echo "<category>".$cat_table[$thiscat]."</category>\n";
    }
}



//start the loop for postings listing now!



foreach ($rows as $fields) { 

    if ($fields['filelocal'] == 0) { $tempurl = $fields['audio_file']; }
    else { $tempurl = $settings['url'] . "/audio/" . $fields['audio_file']; }
    
    //redirect or direct link?
    if ($settings['countpod'] == "1") {     
        if ($fields['filelocal'] == "1") {
            $tempurl = $settings['url']. "/pod/".$fields['audio_file']; 
        } else { 
            $tempurl = $settings['url']. "/pod/".$tempurl;   
        }
    }


    if ((!isset($_GET['post'])) OR ($_GET['post'] == "1")) { 

        //start building the item
        echo "\n\n<item>\n";

        //some important general stuff
        echo "    <pubDate>".date("r", strtotime($fields['posted']))."</pubDate>\n";
        echo "    <title>".html_to_xml($fields['title'])."</title>\n";
        echo "    <link>".$settings['url']."/index.php?id=".$fields['id']."</link>\n";
        echo "    <guid>".$settings['url']."/index.php?id=".$fields['id']."</guid>\n";
        
        //author
        $postauthor = html_to_xml(getfullname($fields['author_id']));
        if (veryempty($postauthor)) { 
            $postauthor = html_to_xml(getnickname($fields['author_id']));
        }
        
        echo "    <dc:creator>".$postauthor."</dc:creator>\n";
        echo "    <itunes:author>".$postauthor."</itunes:author>\n";

		//explicit or not?
        if ($settings['itunes_explicit'] == "1") { $setexpl = "yes"; } else { $setexpl = "no"; }
        echo "    <itunes:explicit>".$setexpl."</itunes:explicit>\n";

        //comments
        if ($fields['comment_on'] == "1") {
            echo "    <comments>".$settings['url']."/index.php?id=".$fields['id']."#comments</comments>\n";
        }
        
        //show categories
        showcats ($fields);   
        
        //description and summary
        $desc = escaped_html($fields['message_html']);
        $itun = html_to_xml(strip_tags($fields['message_html']));
        
        if (strlen($itun) >= 255) {
	        $subtitle = substr($itun,0,255);
    	    $lastspace = strrpos($subtitle, " ");
        	$subtitle = substr($subtitle, 0, $lastspace+1);
        } else {
        	$subtitle = $itun;
        }
        
        echo "    <itunes:subtitle>".$subtitle."</itunes:subtitle>\n";
        echo "    <itunes:summary>".$itun."</itunes:summary>\n\n";

        //bodytext
        echo "    <description>" . $desc;
          
          
        //hyperlinks



        //resetting variable
        $linkrows = "";

        //getting data from links-table
        $dosql  = "SELECT * FROM {$GLOBALS['prefix']}lb_links "; 
        $dosql .= "WHERE posting_id='{$fields['id']}' ";
        $dosql .= "ORDER BY linkorder ASC;";
        $linkrows = $GLOBALS['lbdata']->GetArray($dosql);
        $tmp = "";
    
        if (isset($linkrows[0])) {
            $tmp = "\n\n    <ul>\n\n";
            //start loop for showing links
            foreach ($linkrows as $link) {
            	$linkdesc = html_to_xml($link['description']);
            	$linktitle = html_to_xml($link['title']);
            
                $tmp .= "    <li>\n<a href=\"{$link['url']}\" ";
                $tmp .= "title=\"".$linkdesc."\">";
                $tmp .= $linktitle."</a> :: ";
                $tmp .= $linkdesc."</a>\n    </li>\n\n";
            }
        $tmp .= "    </ul>\n\n";
        
        }
    
        //do we have an audio file? link to it here!
        if (filled($fields['audio_file'])) 
        {
            $tmp .= "<p><a href=\"$tempurl\">File Download ";
            $tmp .= "(".getminutes($fields['audio_length'])." min / ".getmegabyte($fields['audio_size'])." MB)</a></p>";
        }


        echo trim(escaped_html(html_entity_decode($tmp)));
        
        echo "</description>\n\n";
    
        

        //do we add an enclosure?
        if (filled($fields['audio_file'])) {
            echo "    <enclosure url=\"$tempurl\" ";
            echo "length=\"{$fields['audio_size']}\" ";
            echo "type=\"".mime_type($fields['audio_type'])."\" />\n";
            echo "    <itunes:duration>".getitunesduration($fields['audio_length'])."</itunes:duration>\n";
        }

        echo "</item>\n\n";
    }

// ------------------------------------------ ADDING COMMENTS --------

    //add comments to current posting
    if ((isset($_GET['com'])) AND (filled($_GET['com']))) {
    
        $i = 0;
        $comrows = "";
    
        //getting data from comments-table
        $dosql  = "SELECT * FROM {$GLOBALS['prefix']}lb_comments WHERE "; 
            
        //comment must belong to current posting
        $dosql .= "posting_id = '". $fields['id']."'";
        $dosql .= " ORDER BY posted ASC";
        $comrows = $GLOBALS['lbdata']->GetArray($dosql);
    
        foreach ($comrows as $comfields) {
            $i += 1;
            //start building the item
            echo "<item>\n";
    
            //some important and general information
            echo "    <guid>".$settings['url']."/index.php?id=".$fields['id']."#com".$comfields['id']."</guid>\n";
            echo "    <title>".html_to_xml($fields['title'])." (Comment #".$i.")</title>\n";
            echo "    <link>".$settings['url']."/index.php?id=".$fields['id']."#com".$comfields['id']."</link>\n";
    
            //show categories
            showcats ($fields);
        
            //commentator's name
            $author = html_to_xml($comfields['name']);
            echo "    <dc:creator>".$author."</dc:creator>\n";
            echo "    <itunes:author>".$author."</itunes:author>\n";
    
            //description
            $mess = escaped_html($comfields['message_html']);
            $tunmess = html_to_xml(strip_tags($comfields['message_html']));
            echo "    <description>".$mess."</description>\n";
            echo "    <itunes:summary>".$tunmess."</itunes:summary>\n";
    
            //bodytext
            echo "    <content:encoded>".html_to_xml($comfields['message_html']);              
            
            //preparing some variables            
            $audiourl = $settings['url']."/audio/".$comfields['audio_file'];
            if (!filled($comfields['audio_file'])) { $audiothere = false; } else { $audiothere = true; }
            $tmp = "";
              
            //do we have an audio file? link to it here!
            if ($audiothere) {
                $tmp .= "<p><a href=\"".$audiourl."\">File Download ";
                $tmp .= "(".getminutes($comfields['audio_length'])." min / ".getmegabyte($comfields['audio_size'])." MB)</a>";
            }
            
            echo trim(htmlspecialchars($tmp, ENT_QUOTES));
            echo "</content:encoded>\n";
        
            //date of publication   
            echo "    <pubDate>".date("r", strtotime($comfields['posted']))."</pubDate>\n\n";
    
            //do we add an enclosure?
            if ($audiothere) {
            
                echo "    <enclosure url=\"".$audiourl."\" ";
                echo "length=\"".$comfields['audio_size']."\" ";
                echo "type=\"".mime_type($comfields['audio_type'])."\" />\n";
                echo "    <itunes:duration>".getitunesduration($comfields['audio_length'])."</itunes:duration>\n";
            }
    
            echo "</item>\n\n";
        }
    }
}


echo "\n\n</channel>\n\n</rss>";


// ------------------------------------- FUNCTIONS -----------------------------

function showcats ($fields) {
    global $settings;
    global $cat_table;
   
    //iTunes Categories
    $allcats = array(
        $settings['feedcat1'],
        $settings['feedcat2'],
        $settings['feedcat3'],
        $settings['feedcat4']
    );
     
    $tunecats = ""; 
    $catsarray = array();
    /*if (filled($fields['category1_id'])) { 
        $tunecats .= urldecode(getcategory($fields['category1_id'])) . ", "; 
        $catsarray[1] = urldecode(getcategory($fields['category1_id'])); }
    if (filled($fields['category2_id'])) { 
        $tunecats .= urldecode(getcategory($fields['category2_id'])) . ", ";
        $catsarray[2] = urldecode(getcategory($fields['category2_id'])); }
    if (filled($fields['category3_id'])) { 
        $tunecats .= urldecode(getcategory($fields['category3_id'])) . ", ";
        $catsarray[3] = urldecode(getcategory($fields['category3_id'])); }
    if (filled($fields['category4_id'])) { 
        $tunecats .= urldecode(getcategory($fields['category4_id'])) . ", ";
        $catsarray[4] = urldecode(getcategory($fields['category4_id'])); }
	*/
	
	if (filled($fields['tags'])) {
		$tunecats = "displayTags";
		$catsarray = explode(' ', $fields['tags']);
		unset($catsarray[count($catsarray)-1]);
	}
    $regcats = "";
    
    if (filled($tunecats)) {
        foreach ($catsarray as $thiscat) {
            $regcats .= "    <category>".html_to_xml($thiscat)."</category>\n";
        }
    } else {	
        foreach ($allcats as $thiscat) {
            if (filled($cat_table[$thiscat])) {
            	$tunecats .= $cat_table[$thiscat].", ";
                $regcats .= "    <category>".$cat_table[$thiscat]."</category>\n";
            }
        }
    }   
    //trim the string
    $tunecats = (str_replace("&", "&amp;", trim(substr($tunecats, 0, strrpos($tunecats,",")))));
 	$tunecats = "    <itunes:keywords>".$tunecats."</itunes:keywords>\n";
 
 	echo $tunecats.$regcats;
    
}


//----------------------------------------

function filled($text) {
if ((trim($text) == "") OR ($text == "NULL") OR ($text == "0") OR ($text == "00-00")) 
{ return false; } else { return true; }
}

function escaped_html($text) {
$trans_tbl = array (
    "&"=>"&amp;",
    "<"=>"&lt;",
    ">"=>"&gt;",
    "&rsquo;"=>"&apos;"
);
return trim(strtr($text, $trans_tbl));
}

function html_to_xml ($text) {
//turns html entities into UTF-8 or XML entities

$trans_tbl = array (
    "&"=>"&amp;",
    "&#"=>"&#",
    "<"=>"&lt;",
    ">"=>"&gt;",
    "'" => "&apos;",
	'"' => "&quot;"
);

$htmlentities_utf8 = array(
'&nbsp;' => "\xc2\xa0",
'&iexcl;' => "\xc2\xa1",
'&cent;' => "\xc2\xa2",
'&pound;' => "\xc2\xa3",
'&curren;' => "\xc2\xa4",
'&yen;' => "\xc2\xa5",
'&brvbar;' => "\xc2\xa6",
'&sect;' => "\xc2\xa7",
'&uml;' => "\xc2\xa8",
'&copy;' => "\xc2\xa9",
'&ordf;' => "\xc2\xaa",
'&laquo;' => "\xc2\xab",
'&not;' => "\xc2\xac",
'&shy;' => "\xc2\xad",
'&reg;' => "\xc2\xae",
'&macr;' => "\xc2\xaf",
'&deg;' => "\xc2\xb0",
'&plusmn;' => "\xc2\xb1",
'&sup2;' => "\xc2\xb2",
'&sup3;' => "\xc2\xb3",
'&acute;' => "\xc2\xb4",
'&micro;' => "\xc2\xb5",
'&para;' => "\xc2\xb6",
'&middot;' => "\xc2\xb7",
'&cedil;' => "\xc2\xb8",
'&sup1;' => "\xc2\xb9",
'&ordm;' => "\xc2\xba",
'&raquo;' => "\xc2\xbb",
'&frac14;' => "\xc2\xbc",
'&frac12;' => "\xc2\xbd",
'&frac34;' => "\xc2\xbe",
'&iquest;' => "\xc2\xbf",
'&Agrave;' => "\xc3\x80",
'&Aacute;' => "\xc3\x81",
'&Acirc;' => "\xc3\x82",
'&Atilde;' => "\xc3\x83",
'&Auml;' => "\xc3\x84",
'&Aring;' => "\xc3\x85",
'&AElig;' => "\xc3\x86",
'&Ccedil;' => "\xc3\x87",
'&Egrave;' => "\xc3\x88",
'&Eacute;' => "\xc3\x89",
'&Ecirc;' => "\xc3\x8a",
'&Euml;' => "\xc3\x8b",
'&Igrave;' => "\xc3\x8c",
'&Iacute;' => "\xc3\x8d",
'&Icirc;' => "\xc3\x8e",
'&Iuml;' => "\xc3\x8f",
'&ETH;' => "\xc3\x90",
'&Ntilde;' => "\xc3\x91",
'&Ograve;' => "\xc3\x92",
'&Oacute;' => "\xc3\x93",
'&Ocirc;' => "\xc3\x94",
'&Otilde;' => "\xc3\x95",
'&Ouml;' => "\xc3\x96",
'&times;' => "\xc3\x97",
'&Oslash;' => "\xc3\x98",
'&Ugrave;' => "\xc3\x99",
'&Uacute;' => "\xc3\x9a",
'&Ucirc;' => "\xc3\x9b",
'&Uuml;' => "\xc3\x9c",
'&Yacute;' => "\xc3\x9d",
'&THORN;' => "\xc3\x9e",
'&szlig;' => "\xc3\x9f",
'&agrave;' => "\xc3\xa0",
'&aacute;' => "\xc3\xa1",
'&acirc;' => "\xc3\xa2",
'&atilde;' => "\xc3\xa3",
'&auml;' => "\xc3\xa4",
'&aring;' => "\xc3\xa5",
'&aelig;' => "\xc3\xa6",
'&ccedil;' => "\xc3\xa7",
'&egrave;' => "\xc3\xa8",
'&eacute;' => "\xc3\xa9",
'&ecirc;' => "\xc3\xaa",
'&euml;' => "\xc3\xab",
'&igrave;' => "\xc3\xac",
'&iacute;' => "\xc3\xad",
'&icirc;' => "\xc3\xae",
'&iuml;' => "\xc3\xaf",
'&eth;' => "\xc3\xb0",
'&ntilde;' => "\xc3\xb1",
'&ograve;' => "\xc3\xb2",
'&oacute;' => "\xc3\xb3",
'&ocirc;' => "\xc3\xb4",
'&otilde;' => "\xc3\xb5",
'&ouml;' => "\xc3\xb6",
'&divide;' => "\xc3\xb7",
'&oslash;' => "\xc3\xb8",
'&ugrave;' => "\xc3\xb9",
'&uacute;' => "\xc3\xba",
'&ucirc;' => "\xc3\xbb",
'&uuml;' => "\xc3\xbc",
'&yacute;' => "\xc3\xbd",
'&thorn;' => "\xc3\xbe",
'&yuml;' => "\xc3\xbf",
'&fnof;' => "\xc6\x92",
'&Alpha;' => "\xce\x91",
'&Beta;' => "\xce\x92",
'&Gamma;' => "\xce\x93",
'&Delta;' => "\xce\x94",
'&Epsilon;' => "\xce\x95",
'&Zeta;' => "\xce\x96",
'&Eta;' => "\xce\x97",
'&Theta;' => "\xce\x98",
'&Iota;' => "\xce\x99",
'&Kappa;' => "\xce\x9a",
'&Lambda;' => "\xce\x9b",
'&Mu;' => "\xce\x9c",
'&Nu;' => "\xce\x9d",
'&Xi;' => "\xce\x9e",
'&Omicron;' => "\xce\x9f",
'&Pi;' => "\xce\xa0",
'&Rho;' => "\xce\xa1",
'&Sigma;' => "\xce\xa3",
'&Tau;' => "\xce\xa4",
'&Upsilon;' => "\xce\xa5",
'&Phi;' => "\xce\xa6",
'&Chi;' => "\xce\xa7",
'&Psi;' => "\xce\xa8",
'&Omega;' => "\xce\xa9",
'&alpha;' => "\xce\xb1",
'&beta;' => "\xce\xb2",
'&gamma;' => "\xce\xb3",
'&delta;' => "\xce\xb4",
'&epsilon;' => "\xce\xb5",
'&zeta;' => "\xce\xb6",
'&eta;' => "\xce\xb7",
'&theta;' => "\xce\xb8",
'&iota;' => "\xce\xb9",
'&kappa;' => "\xce\xba",
'&lambda;' => "\xce\xbb",
'&mu;' => "\xce\xbc",
'&nu;' => "\xce\xbd",
'&xi;' => "\xce\xbe",
'&omicron;' => "\xce\xbf",
'&pi;' => "\xcf\x80",
'&rho;' => "\xcf\x81",
'&sigmaf;' => "\xcf\x82",
'&sigma;' => "\xcf\x83",
'&tau;' => "\xcf\x84",
'&upsilon;' => "\xcf\x85",
'&phi;' => "\xcf\x86",
'&chi;' => "\xcf\x87",
'&psi;' => "\xcf\x88",
'&omega;' => "\xcf\x89",
'&thetasym;' => "\xcf\x91",
'&upsih;' => "\xcf\x92",
'&piv;' => "\xcf\x96",
'&bull;' => "\xe2\x80\xa2",
'&hellip;' => "\xe2\x80\xa6",
'&prime;' => "\xe2\x80\xb2",
'&Prime;' => "\xe2\x80\xb3",
'&oline;' => "\xe2\x80\xbe",
'&frasl;' => "\xe2\x81\x84",
'&weierp;' => "\xe2\x84\x98",
'&image;' => "\xe2\x84\x91",
'&real;' => "\xe2\x84\x9c",
'&trade;' => "\xe2\x84\xa2",
'&alefsym;' => "\xe2\x84\xb5",
'&larr;' => "\xe2\x86\x90",
'&uarr;' => "\xe2\x86\x91",
'&rarr;' => "\xe2\x86\x92",
'&darr;' => "\xe2\x86\x93",
'&harr;' => "\xe2\x86\x94",
'&crarr;' => "\xe2\x86\xb5",
'&lArr;' => "\xe2\x87\x90",
'&uArr;' => "\xe2\x87\x91",
'&rArr;' => "\xe2\x87\x92",
'&dArr;' => "\xe2\x87\x93",
'&hArr;' => "\xe2\x87\x94",
'&forall;' => "\xe2\x88\x80",
'&part;' => "\xe2\x88\x82",
'&exist;' => "\xe2\x88\x83",
'&empty;' => "\xe2\x88\x85",
'&nabla;' => "\xe2\x88\x87",
'&isin;' => "\xe2\x88\x88",
'&notin;' => "\xe2\x88\x89",
'&ni;' => "\xe2\x88\x8b",
'&prod;' => "\xe2\x88\x8f",
'&sum;' => "\xe2\x88\x91",
'&minus;' => "\xe2\x88\x92",
'&lowast;' => "\xe2\x88\x97",
'&radic;' => "\xe2\x88\x9a",
'&prop;' => "\xe2\x88\x9d",
'&infin;' => "\xe2\x88\x9e",
'&ang;' => "\xe2\x88\xa0",
'&and;' => "\xe2\x88\xa7",
'&or;' => "\xe2\x88\xa8",
'&cap;' => "\xe2\x88\xa9",
'&cup;' => "\xe2\x88\xaa",
'&int;' => "\xe2\x88\xab",
'&there4;' => "\xe2\x88\xb4",
'&sim;' => "\xe2\x88\xbc",
'&cong;' => "\xe2\x89\x85",
'&asymp;' => "\xe2\x89\x88",
'&ne;' => "\xe2\x89\xa0",
'&equiv;' => "\xe2\x89\xa1",
'&le;' => "\xe2\x89\xa4",
'&ge;' => "\xe2\x89\xa5",
'&sub;' => "\xe2\x8a\x82",
'&sup;' => "\xe2\x8a\x83",
'&nsub;' => "\xe2\x8a\x84",
'&sube;' => "\xe2\x8a\x86",
'&supe;' => "\xe2\x8a\x87",
'&oplus;' => "\xe2\x8a\x95",
'&otimes;' => "\xe2\x8a\x97",
'&perp;' => "\xe2\x8a\xa5",
'&sdot;' => "\xe2\x8b\x85",
'&lceil;' => "\xe2\x8c\x88",
'&rceil;' => "\xe2\x8c\x89",
'&lfloor;' => "\xe2\x8c\x8a",
'&rfloor;' => "\xe2\x8c\x8b",
'&lang;' => "\xe2\x8c\xa9",
'&rang;' => "\xe2\x8c\xaa",
'&loz;' => "\xe2\x97\x8a",
'&spades;' => "\xe2\x99\xa0",
'&clubs;' => "\xe2\x99\xa3",
'&hearts;' => "\xe2\x99\xa5",
'&diams;' => "\xe2\x99\xa6",
'&quot;' => "\x22",
'&amp;' => "\x26",
'&lt;' => "\x3c",
'&gt;' => "\x3e",
'&OElig;' => "\xc5\x92",
'&oelig;' => "\xc5\x93",
'&Scaron;' => "\xc5\xa0",
'&scaron;' => "\xc5\xa1",
'&Yuml;' => "\xc5\xb8",
'&circ;' => "\xcb\x86",
'&tilde;' => "\xcb\x9c",
'&ensp;' => "\xe2\x80\x82",
'&emsp;' => "\xe2\x80\x83",
'&thinsp;' => "\xe2\x80\x89",
'&zwnj;' => "\xe2\x80\x8c",
'&zwj;' => "\xe2\x80\x8d",
'&lrm;' => "\xe2\x80\x8e",
'&rlm;' => "\xe2\x80\x8f",
'&ndash;' => "\xe2\x80\x93",
'&mdash;' => "\xe2\x80\x94",
'&lsquo;' => "\xe2\x80\x98",
'&rsquo;' => "\xe2\x80\x99",
'&sbquo;' => "\xe2\x80\x9a",
'&ldquo;' => "\xe2\x80\x9c",
'&rdquo;' => "\xe2\x80\x9d",
'&bdquo;' => "\xe2\x80\x9e",
'&dagger;' => "\xe2\x80\xa0",
'&Dagger;' => "\xe2\x80\xa1",
'&permil;' => "\xe2\x80\xb0",
'&lsaquo;' => "\xe2\x80\xb9",
'&rsaquo;' => "\xe2\x80\xba",
'&euro;' => "\xe2\x82\xac");

$text = trim(strtr($text, array_map('utf8_encode', array_flip(get_html_translation_table(HTML_ENTITIES)))));
$text = strtr($text, $htmlentities_utf8);
$text = strtr($text, $trans_tbl);
return $text;
}



//----------------------------------------


function itunescats() {
global $cat_table;
$cat_table = array_flip(array(
""=>"00-00",
"Arts"=>"01-00",
	"Design"=>"01-01",
	"Fashion &amp; Beauty"=>"01-02",
	"Food"=>"01-03",
	"Literature"=>"01-04",
	"Performing Arts"=>"01-05",
	"Visual Arts"=>"01-06",
"Business"=>"02-00",
	"Business News"=>"02-01",
	"Careers"=>"02-02",
	"Investing"=>"02-03",
	"Management &amp; Marketing"=>"02-04",
	"Shopping"=>"02-05",
"Comedy"=>"03-00",
"Education"=>"04-00",
	"Educational Technology"=>"04-01",
	"Higher Ed"=>"04-02",
	"K-12"=>"04-03",
	"Language Courses"=>"04-04",
	"Training"=>"04-05",
"Games &amp; Hobbies"=>"05-00",
	"Automotive"=>"05-01",
	"Aviation"=>"05-02",
	"Hobbies"=>"05-03",
	"Other Games"=>"05-04",
	"Video Games"=>"05-05",
"Government &amp; Organizations"=>"06-00",
	"Local"=>"06-01",
	"National"=>"06-02",
	"Non-Profit"=>"06-03",
	"Regional"=>"06-04",
"Health"=>"07-00",
	"Alternative Health"=>"07-01",
	"Fitness &amp; Nutrition"=>"07-02",
	"Self-Help"=>"07-03",
	"Sexuality"=>"07-04",
"Kids &amp; Family"=>"08-00",
"Music"=>"09-00",
"News &amp; Politics"=>"10-00",
"Religion &amp; Spirituality"=>"11-00",
	"Buddhism"=>"11-01",
	"Christianity"=>"11-02",
	"Hinduism"=>"11-03",
	"Islam"=>"11-04",
	"Judaism"=>"11-05",
	"Other"=>"11-06",
	"Spirituality"=>"11-07",
"Science &amp; Medicine"=>"12-00",
	"Medicine"=>"12-01",
	"Natural Sciences"=>"12-02",
	"Social Sciences"=>"12-03",
"Society &amp; Culture"=>"13-00",
	"History"=>"13-01",
	"Personal Journals"=>"13-02",
	"Philosophy"=>"13-03",
	"Places &amp; Travel"=>"13-04",
"Sports &amp; Recreation"=>"14-00",
	"Amateur"=>"14-01",
	"College &amp; High School"=>"14-02",
	"Outdoor"=>"14-03",
	"Professional"=>"14-04",
"Technology"=>"15-00",
	"Gadgets"=>"15-01",
	"IT News"=>"15-02",
	"Podcasting"=>"15-03",
	"Software How-To"=>"15-04",
"TV &amp; Film"=>"16-00"
));
}




?>