<?php

//sorting variables
if (isset($_GET['sort'])) { 
    $sortby = substr($_GET['sort'],1);
    $sortdir = substr($_GET['sort'],0,1); 
    if ($sortdir == "0") { $order = "ASC"; } else { $order = "DESC"; }
} else { 
    $_GET['sort'] = "1posted"; 
    $sortby = "posted"; 
    $order= "DESC"; 
}

//offset and paging calculations
if (isset($_GET['nr'])) {
	$currsite = $_GET['nr'];
	$offset = ($currsite * $settings['showpostings']) - $settings['showpostings'];
} else { 
	$offset = 0;
	$currsite = 1; 
}

$numbsite = round(countrows("lb_postings") / $settings['showpostings']);
if ($numbsite < countrows("lb_postings") / $settings['showpostings']) { $numbsite += 1; }

$pagingstring = "";
	
if ($numbsite > 1) {
  $pagingstring = "<span class=\"pages\">Pages :";
	for ($i = 1; $i <= $numbsite; $i++) {
		if ($i == $currsite) {
			$pagingstring .= "<span class=\"active\">".$i."</span> \n";		
		} else {
			$pagingstring .= "<a href=\"index.php".addToUrl("nr", $i)."\">".$i."</a> \n";
		}
	}
 $pagingstring = $pagingstring."</span>";
}
	
echo "<h1>".bla("hl_postings")."&nbsp;&nbsp;". $pagingstring ."</h1>\n";

include ('inc/navigation.php');


//delete data in filesystem and database, if required by url!
if ((isset($_GET['do'])) AND ($_GET['do'] == "x") AND (isset($_GET['id']))) {


    //delete posting
    $dosql = "SELECT filelocal, audio_file 
              FROM ".$GLOBALS['prefix']."lb_postings 
              WHERE id='" . $_GET['id'] . "'";
    $result = $GLOBALS['lbdata']->GetArray($dosql);
    if (isset($result[0])) {
    
    	$row = $result[0];
    
		if ($row['filelocal'] == 1) {
			$deletepath = $GLOBALS['audiopath'] . $row['audio_file'];
			@unlink ($deletepath);
		}
		$dosql = "DELETE FROM ".$GLOBALS['prefix']."lb_postings 
				 WHERE id = '" . $_GET['id'] . "'";
		$GLOBALS['lbdata']->Execute($dosql);
		
		
		//delete related comments
		$dosql = "SELECT audio_file 
				  FROM ".$GLOBALS['prefix']."lb_comments 
				  WHERE posting_id = '" . $_GET['id'] . "'";
		$result = $GLOBALS['lbdata']->GetArray($dosql);
		foreach ($result as $row) {
			$deletepath = $GLOBALS['audiopath'] . $row['audio_file'];
			@unlink ($deletepath);
		}
	
		$dosql = "DELETE FROM ".$GLOBALS['prefix']."lb_comments 
				 WHERE posting_id = '" . $_GET['id'] . "'";
		$GLOBALS['lbdata']->Execute($dosql);
	
		echo "<p class=\"msg\">".bla("msg_deleteposting")."" . $_GET['id'] . "!</p>";
	}
}



//getting all sql-data needed for the table
$dosql = "SELECT 
            id, author_id, posted, title, filelocal, audio_file, audio_type, audio_size, audio_length, status, sticky 
          FROM 
            ".$GLOBALS['prefix']."lb_postings 
          ORDER 
			BY sticky DESC,
            ".$sortby." ".$order;
$result = $GLOBALS['lbdata']->SelectLimit($dosql, $settings['showpostings'], $offset);
$showtable = $result->GetArray();

$rowcount = count($showtable);

//table which should be coded way more beautiful
echo "<table>\n<tr>\n";

//getting current sorting order and direction from url
if (isset($_GET['sort'])) {
    $currsort = substr($_GET['sort'],1);
    $currdir = substr($_GET['sort'],0,1);
} else { $currsort = "posted"; $currdir = "0"; }

//default values for new url-requests
$dirpost = "1"; $dirauth = "0"; $dirtitl = "0"; $diraudi = "1"; $dirstat = "0"; 

//make 0 to 1 and vice versa
function changedir($x) {
if ($x == "0") { return "1"; }
if ($x == "1") { return "0"; }
}

//a click on the active sorting order link changes the direction
if ($currsort == "posted") { $dirpost = changedir($currdir); }
if ($currsort == "author_id") { $dirauth = changedir($currdir); }
if ($currsort == "title") { $dirtitl = changedir($currdir); }
if ($currsort == "audio_length") { $diraudi = changedir($currdir); }
if ($currsort == "status") { $dirstat = changedir($currdir); }

//pink print for active sorting type
$pink = array ("posted"=>"", "author_id"=>"","title"=>"", "audio_length"=>"", "status"=>"");
foreach ($pink as $key => $value) {
    if ($currsort == $key) {
        $pink[$key] = " class=\"pink\"";
    }
}

//generates the links
echo "<th><a".$pink['posted']." href=\"index.php".addToUrl("sort",$dirpost."posted")."\">".bla("post_date")."</a></th>\n";
echo "<th><a".$pink['author_id']." href=\"index.php".addToUrl("sort",$dirauth."author_id")."\">".bla("post_author")."</a></th>\n";
//echo "<th>".bla("post_sticky")."</th>\n";
echo "<th><a".$pink['title']." href=\"index.php".addToUrl("sort",$dirtitl."title")."\">".bla("post_title")."</a></th>\n";
echo "<th>".bla("post_play")."</th>\n";
echo "<th><a".$pink['audio_length']." href=\"index.php".addToUrl("sort",$diraudi."audio_length")."\">".bla("post_length")."</a></th>\n";
echo "<th><a".$pink['status']." href=\"index.php".addToUrl("sort",$dirstat."status")."\">".bla("post_status")."</a></th>\n";
echo "<th></th>\n</tr>\n\n";


//one table-row for each entry in the database
for ($i=0; $i<$rowcount; $i++) {

    echo "<tr>\n";

    //showing the date/time
    $dateformat = $settings['dateformat'];
    $showdate = date($dateformat , strtotime($showtable[$i]['posted']));
    echo "<td>" . $showdate . "</td>\n";
    
    //showing the author
    $tempauth = getnickname($showtable[$i]['author_id']);
    if ($tempauth == $_SESSION['nickname']) { $tempauth = "<b>".bla("post_yourself")."</b>"; }
    echo "<td>" . $tempauth . "</td>\n";

    //generating the link
    if ($showtable[$i]['filelocal'] == 1)
    $link = $settings['url'] . "/audio/" . $showtable[$i]['audio_file'];
    else $link = $showtable[$i]['audio_file'];

    //is sticky?
    //echo "<td>";
    //echo $showtable[$i]['sticky']>0 ? '<strong>!!</strong>' : ' ';
	//echo "</td>\n";

    //showing the title
    echo "<td>"; 
    echo "<a href=\"index.php?page=record2&amp;do=edit&amp;";
    echo "id=" . $showtable[$i]['id'] . "\">\n";
    echo $showtable[$i]['title'] . "</a>";
    echo " id = ".$showtable[$i]['id'];
    if ($showtable[$i]['sticky']>0) {
      echo "[Sticky]"; }
    if ((countcomments($showtable[$i]['id'])) > 0)   {
       echo " <a href=\"index.php?page=comments&amp;posting_id=".$showtable[$i]['id']."\" title = \"Link to comments\">[Comments]</a>";
    }
     echo "</td>\n";
    
    //flash player for instant access! (only when file is an .mp3)
    echo "<td>\n";
    
    $type = $showtable[$i]['audio_type'];
    
    //is it MP3? Then show us the flash player!!
    if ($type == 1) {
        echo showflash("backend/emff_list.swf?src=".$link, 90, 19);
        
    //ist it Quicktime-compatible? Show us the Quicktime plugin!!
    } else {
        if (($type == "2") OR 
            ($type == "5") OR 
            ($type == "6") OR 
            ($type == "9") OR 
            ($type == "12") OR 
            ($type == "14") OR 
            ($type == "10") OR 
            ($type == "13") OR 
            ($type == "7")) {
        $src = $settings['url']."/loudblog/backend/clicktoplayback.mov";
        $target = "myself";
        
        //ist it a video or enhanced podcast? Link to external player!
        if (($type == "14") OR 
            ($type == "10") OR 
            ($type == "13") OR 
            ($type == "7")) {  
            $target = "quicktimeplayer";
        }      
        
        //build html-code for quicktime plugin (audio)
        echo showquicktime ($src, $link, 90, 20, $target, "false");
    } else {
    
        //if its not an mp3 or quicktime, show a simple link!
        if ($showtable[$i]['audio_file'] != "") {
            echo "<a href=\"" . $link . "\">
                 ".getmediatypename($showtable[$i]['audio_type'])."</a>";
            echo "</td>\n";
        }
        }
    }
    
    //showing audio length in minutes
    echo "<td>" . getminutes($showtable[$i]['audio_length']) . "</td>\n";





    //the status radio buttons
    $temp = $showtable[$i]['status'];
    echo "<td>\n";
    if ($temp == 1) { echo "<span style=\"color:#dd0067;\">".bla("draft")."</span>"; }
    if ($temp == 2) { echo "<span style=\"color:#090;\">".bla("finished")."</span>"; }
    if ($temp == 3) { echo bla("onair"); }
    
    echo "</td>\n";


    //a beautiful button for deleting
    echo "<td class=\"right\">\n";
    
    if (allowed(1,$showtable[$i]['id'])) {
        echo "<form method=\"post\" enctype=\"multipart/form-data\" ";
        echo "action=\"index.php?page=postings&amp;do=x&amp;";
        echo "id=" . $showtable[$i]['id'] . "\" ";
        echo "onSubmit=\"return yesno('".bla("alert_deleteposting")."')\">\n";
        echo "<input type=\"submit\" value=\"".bla("but_delete")."\" />\n";
        echo "</form>\n";
    }
    echo "</td>\n";
    
    echo "</tr>\n\n";
}

echo "</table>";


?>