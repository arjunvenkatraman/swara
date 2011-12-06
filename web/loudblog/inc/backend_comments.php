<?php

//sorting-variables
if (isset($_GET['sort'])) { 
    $sortby = substr($_GET['sort'],1);
    $sortdir = substr($_GET['sort'],0,1); 
    if ($sortdir == "0") { $order = "ASC"; } else { $order = "DESC"; }
} else { 
    $_GET['sort'] = "1posted"; 
    $sortby = "posted"; 
    $order= "DESC"; 
}

//check the rights
if (!allowed(3,"")) 
{ die("<p class=\"msg\">".bla("msg_adminonly")."</p>"); }

include ('inc/navigation.php');

//offset and paging calculations
if (isset($_GET['nr'])) {
	$currsite = $_GET['nr'];
	$offset = ($currsite * $settings['showpostings']) - $settings['showpostings'];
} else { 
	$offset = 0;
	$currsite = 1; 
}
//do we want only the comments for a specified post?
$criterion = "";
if (isset($_GET['posting_id']))   {
  $posting_id = $_GET['posting_id'];
  $criterion = " WHERE posting_id = '".$posting_id."'";
}
//how many records will our database query return?
$dosql = " SELECT COUNT(id) FROM ".$GLOBALS['prefix']."lb_comments".$criterion;
$count = $GLOBALS['lbdata']->GetArray($dosql);
$total = $count[0]['COUNT(id)'];

$howmanypages = round($total / $settings['showpostings']);
if ($howmanypages < ($total / $settings['showpostings'])) { $howmanypages += 1; }

$pagingstring = "";

if ($howmanypages > 1) {
  $pagingstring = "<span class=\"pages\"> ".bla("com_pages")." : ";
	for ($i = 1; $i <= $howmanypages; $i++) {
		if ($i == $currsite) {
			$pagingstring .= "<span class=\"active\">".$i."</span> \n";		
		} else {
			$pagingstring .= "<a href=\"index.php".addToUrl("nr", $i)."\">".$i."</a> \n";
		}
	}
 $pagingstring = $pagingstring."</span>";
}

echo "<h1>".bla("hl_comments")."&nbsp;&nbsp;".$pagingstring."</h1>";

//include ('inc/navigation.php');



//delete data in filesystem and database, if required by url!
if ((isset($_GET['do'])) AND ($_GET['do'] == "x") AND (isset($_GET['id']))) {

    $dosql = "SELECT audio_file 
              FROM ".$GLOBALS['prefix']."lb_comments 
              WHERE id=" . $_GET['id'] . ";";
    $result = $GLOBALS['lbdata']->GetArray($dosql);
    $row = $result[0];
    if ($row['audio_file'] != "") {
        $deletepath = $GLOBALS['audiopath'] . $row['audio_file'];
        unlink ($deletepath);
    }
    $dosql = "DELETE FROM ".$GLOBALS['prefix']."lb_comments 
             WHERE id=" . $_GET['id'] . ";";
    $GLOBALS['lbdata']->Execute($dosql);

    echo "<p class=\"msg\">" . bla("msg_deletecomment") . $_GET['id'] . "!</p>";
}

//getting all sql-data needed for the table
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_comments";
if (isset($_GET['posting_id']))  {
  $dosql .= $criterion;
}
 $dosql .= " ORDER BY ".$sortby." ".$order;
$result = $GLOBALS['lbdata']->SelectLimit($dosql,$settings['showpostings'],$offset);
$showtable = $result->GetArray();
$rowcount = count($showtable);

//getting all posting-titles
$dosql = "SELECT id, title FROM ".$GLOBALS['prefix']."lb_postings;";
$posting = $GLOBALS['lbdata']->GetAssoc($dosql);

//getting current sorting order and direction from url
if (isset($_GET['sort'])) {
    $currsort = substr($_GET['sort'],1);
    $currdir = substr($_GET['sort'],0,1);
} else { $currsort = "posted"; $currdir = "0"; }

//default values for new url-requests
$dirpost = "1"; $dirname = "0"; $dirmess = "0"; $dirbelo = "1"; $dirtime = "0"; 

//make 0 to 1 and vice versa
function changedir($x) {
if ($x == "0") { return "1"; }
if ($x == "1") { return "0"; }
}

//a click on the active sorting order link changes the direction
if ($currsort == "posted") { $dirpost = changedir($currdir); }
if ($currsort == "name") { $dirname = changedir($currdir); }
if ($currsort == "message_input") { $dirmess = changedir($currdir); }
if ($currsort == "posting_id") { $dirbelo = changedir($currdir); }
if ($currsort == "audio_length") { $dirtime = changedir($currdir); }

//pink print for active sorting type
$pink = array ("posted"=>"", "name"=>"","message_input"=>"", "posting_id"=>"", "audio_length"=>"");
foreach ($pink as $key => $value) {
    if ($currsort == $key) {
        $pink[$key] = " class=\"pink\"";
    }
}


//table which should be coded way more beautiful
echo "<table>\n<tr>\n";
echo "<th><a".$pink['posted']." href=\"index.php".addToUrl("sort",$dirpost."posted")."\">".bla("com_date")."</a></th>\n";
echo "<th><a".$pink['name']." href=\"index.php".addToUrl("sort",$dirname."name")."\">".bla("com_name")."</a></th>\n";
echo "<th><a".$pink['message_input']." href=\"index.php".addToUrl("sort",$dirmess."message_input")."\">".bla("com_message")."</a></th>\n";
echo "<th><a".$pink['posting_id']." href=\"index.php".addToUrl("sort",$dirbelo."posting_id")."\">".bla("com_belong")."</a></th>\n";
echo "<th>".bla("com_play")."</th>\n";
echo "<th class=\"size\"><a".$pink['audio_length']." href=\"index.php".addToUrl("sort",$dirtime."audio_length")."\">".bla("com_time")."</a></th>\n";
echo "<th></th>\n</tr>\n\n";


//one table-row for each entry in the database
for ($i=0; $i<$rowcount; $i++) {

    echo "<tr class=\"".$showtable[$i]['id']."\">\n";

    //showing the date/time
    $dateformat = $settings['dateformat'];
    $showdate = date($dateformat , strtotime($showtable[$i]['posted']));
    echo "<td class=\"date\">" . $showdate . "</td>\n";
    
    //showing the commentators name
    echo "<td class=\"author\"><p>" . $showtable[$i]['name'] . "</p></td>\n";
    
    //generating the link
    $link = $settings['url'] . "/audio/" . $showtable[$i]['audio_file'];

    //showing the message
    $text = strip_tags($showtable[$i]['message_input']);
    if (substr($text,0,80) == $text) {
        $more = "";
    } else { $more = "&hellip;"; }
    echo "<td class=\"message\"><p>".substr($text,0,80).$more."</p></td>\n";
    
    //showing title of related posting
    echo "<td class=\"postings\"><a href=\"index.php?page=record2&amp;do=edit&id=".$showtable[$i]['posting_id']."\" title = \"".bla("com_edit")."\">".$posting[$showtable[$i]['posting_id']]."</a>";
    echo "</td>\n";

    
    //flash player for instant access! (only when file is an .mp3)
    echo "<td>\n";
    if ($showtable[$i]['audio_type'] == 1) {
        echo showflash ("backend/emff_list.swf?src=".$link, 90, 19);
    } else {
    
        //if its not an mp3, show a simple link!
        if ($showtable[$i]['audio_file'] != "") {
            echo "<a href=\"" . $link . "\">
                 ".getmediatypename($showtable[$i]['audio_type'])."</a>";
            echo "</td>\n";
        }
    }
    

    //showing length in minutes
    echo "<td class=\"size\">" . getminutes($showtable[$i]['audio_length']) . "</td>\n";


    //a beautiful button for deleting
    echo "<td class=\"right\">\n";
    
    if (allowed(1,$showtable[$i]['id'])) {
        echo "<form method=\"post\" enctype=\"multipart/form-data\" ";
        echo "action=\"index.php?page=comments&amp;do=x&amp;";
        echo "id=" . $showtable[$i]['id'] . "\" ";
        echo "onSubmit=\"return yesno('".bla("alert_deletecomment")."')\">\n";
        echo "<input type=\"submit\" value=\"".bla("but_delete")."\" />\n";
        echo "</form>\n";
    }
    echo "</td>\n";
    
    echo "</tr>\n\n";
}

echo "</table>";

?>

<script type="text/javascript">

$(document).ready(function() {

	$("table td.message p").click(function() {
		var thisID = $(this).parents("tr")[0].className;
		editable = '<form name="'+thisID+'"><fieldset><textarea name="msg"></textarea><br /><input type="submit" value="Save" /><input type="reset" class="cancel" value="Cancel" /></fieldset></form>';
		var thisP = $(this);
		var thisTD = $(this).parent();
		$(thisP).hide();
		$(thisTD).append(editable);
		var myRequest = "action=singleread&table=comments&colpick=message_input&rowpick=id&rowval="+thisID;
		$.ajax({ type: "POST", url: "ajax.php", data: myRequest, success: function(data){ 
			$(thisTD).find("textarea").append(data); 
		}});
		$(thisTD).find(".cancel").click(function() {
			var myCell = $(this).parents("td")[0];
			$(myCell).find("form").remove();
			$(myCell).find("p").show();
		});
		$(thisTD).find("form").submit(function() {
			var myCell = $(this).parent();
			var newText = this.getElementsByTagName("textarea")[0].value;
			var myRequest = "action=singleupdate&table=comments&colpick=message_input&rowpick=id&rowval="+thisID+"&colval="+newText;
			$.ajax({ type: "POST", url: "ajax.php", data: myRequest});
			var myRequest = "action=singleupdate&table=comments&colpick=message_html&rowpick=id&rowval="+thisID+"&colval="+newText+"&makehtml=1";
			$.ajax({ type: "POST", url: "ajax.php", data: myRequest});
			$(myCell).find("p").empty().append(newText).show();
			$(myCell).find("form").remove();
		});
	});	
	
	$("table td.author p").click(function() {
		var thisID = $(this).parents("tr")[0].className;
		editable = '<form name="'+thisID+'"><fieldset><input type="text" name="auth" class="text" /><br /><input type="submit" value="Save" /><input type="reset" class="cancel" value="Cancel" /></fieldset></form>';
		var thisP = $(this);
		var thisTD = $(this).parent();
		$(thisP).hide();
		$(thisTD).append(editable);
		var myRequest = "action=singleread&table=comments&colpick=name&rowpick=id&rowval="+thisID;
		$.ajax({ type: "POST", url: "ajax.php", data: myRequest, success: function(data){ 
			$(thisTD).find(".text").val(data); 
		}});	
		$(thisTD).find(".cancel").click(function() {
			var myCell = $(this).parents("td")[0];
			$(myCell).find("form").remove();
			$(myCell).find("p").show();
		});
		$(thisTD).find("form").submit(function() {
			var myCell = $(this).parent();
			var newText = this.getElementsByTagName("input")[0].value;
			var myRequest = "action=singleupdate&table=comments&colpick=name&rowpick=id&rowval="+thisID+"&colval="+newText;
			$.ajax({ type: "POST", url: "ajax.php", data: myRequest});
			$(myCell).find("p").empty().append(newText).show();
			$(myCell).find("form").remove();
		});
	});	
	
});

</script>





