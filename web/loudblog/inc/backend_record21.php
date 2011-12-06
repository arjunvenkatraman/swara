<?php
//bug in comments section removed
//lines 172 to 180 - Loudblog no longer puts new audio file name in database if rename fails
echo "<h1>".bla("hl_rec2")."</h1>";

include ('inc/functions_record.php');
include ('inc/navigation.php');



 error_reporting(E_ALL);
//where did we get the audio file from? go to appropriate function!
//and don't come back without the id of the posting we will edit later!

if (isset($_GET['do'])) {

 $source = "./test.txt";
   $destination = "./copy.txt";
                 
   

if ($_GET['do'] == "browser") {
    if (isset($_GET['id'])){
		$edit_id = upload_browser($_GET['id']);
		$oldDir = "/sounds/";
		$newDir = "/sounds/original/";
		$oldFile = $oldDir . $edit_id . ".wav";
		$newFile = $newDir . $edit_id . ".wav";		
		/*echo "<a href=$oldFile>oldFil</a>";
		echo "</br>";
		echo "<a href=$newFile>newFile</a>";
		echo "</br>";*/
		rename($oldFile, $newFile);
		exec("/usr/local/bin/lame audio/$edit_id.mp3 --decode sounds/$edit_id.wav", $output = array(),$result);		
		exec("sox -V sounds/$edit_id.wav -r 8000 -c 1 sounds/$edit_id.raw");
	/*if ($result !== 0) {
		echo 'Command failed!<br>';
		print_r($command_output);
		die();
	}

	echo 'success!';
	print_r($output);  */ 
			
									
		
		}
    else $edit_id = upload_browser(false);
}

if ($_GET['do'] == "cgi") {
    if (isset($_GET['id'])) $edit_id = cgi_copy($_GET['id']);
    else $edit_id = cgi_copy(false);
}
    
if (($_GET['do'] == "web") AND ($_POST['method'] == "link")) {
    if (isset($_GET['id'])) $edit_id = link_web($_GET['id']);
    else $edit_id = link_web(false); 
}
    
if (($_GET['do'] == "web") AND ($_POST['method'] == "copy")) {
    if (isset($_GET['id'])) $edit_id = fetch_web($_GET['id']);
    else $edit_id = fetch_web(false); 
}
//in following line, replaced "ftp" with "transfer" to avoid problems where mod security is installed
if ($_GET['do'] == "transfer") {
    if (isset($_GET['id'])) $edit_id = copy_ftp($_GET['id']);
    else $edit_id = copy_ftp(false); 
}

if ($_GET['do'] == "nofile") {
    if (isset($_GET['id'])) $edit_id = nofile($_GET['id']);
    else $edit_id = nofile(false); 
}


//oh, we only got here from the postings-page to edit some posting?
//oh, we only got back here from the id3edit-page?
if ($_GET['do'] == "edit")
$edit_id = $_GET['id']; 

//we want to save some changes? here we go!
if ($_GET['do'] == "save") {
    $edit_id = $_GET['id']; 
    
    //build the date string
    if (!isset($_POST['now'])) {
    $posted = $_POST['post1'] . "-" . $_POST['post2'] . "-" .
              $_POST['post3'] . " " . $_POST['post4'] . ":" .
              $_POST['post5'] . ":00";
    } else { $posted = date("Y-m-d H:i:s"); }

  //sticky postings
    if (!isset($_POST['sticky']))
    $sticks = "0";
    else $sticks = "1";

    //make a valid temp-title and put textile onto the posted bodytext
    $temptitle = htmlentities($_POST['title'], ENT_QUOTES, "UTF-8");
    $tempmess = htmlentities($_POST['message'], ENT_QUOTES, "UTF-8");
    
    //extract duration- and size integer from input 
    $pieces = explode (" ", $_POST['audio_length']);
    $lengthint = round ($pieces[0]);
    $pieces2 = explode (" ", $_POST['audio_size']);
    $sizeint = round ($pieces2[0]) * 1024 * 1024;
    
    //use preferred html-helper tool
    $temphtml = makehtml($_POST['message']);
    
    //get the data for comment-options
    if ($_POST['comment_on'] == "on") $comments = "1"; else $comments = "0";    
    
    //get the old posting date (for comparing later on)
    $dosql = "SELECT posted FROM ".$GLOBALS['prefix']."lb_postings 
          WHERE id = '". $edit_id ."'";
    $result = $GLOBALS['lbdata']->GetArray($dosql);
    $olddate = $result[0]['posted'];
    
    //get the tag string ready for posting
	$_POST['tags'] = str_replace(',', ' ', $_POST['tags']);
	$tags = htmlentities($_POST['tags'], ENT_QUOTES, "UTF-8");

//write things from post-data into database
    $dosql = "UPDATE ".$GLOBALS['prefix']."lb_postings SET
    
              title         = '" . $temptitle . "',
              message_input = '" . $tempmess . "',
              message_html  = '" . $temphtml . "',
              posted        = '" . $posted . "',
              comment_on    = '" . $comments . "',
              audio_length  = '" . $lengthint . "', 
              audio_size    = '" . $sizeint . "', 
              comment_size  = '" . $_POST['comment_size'] . "',
		  category1_id  = '" . $_POST['cat1'] . "',
		  category2_id  = '" . $_POST['cat2'] . "',
		  category3_id  = '" . $_POST['cat3'] . "',
	        category4_id  = '" . $_POST['cat4'] . "',
              tags  	    = '" . $tags . "',
		  sticky	    = '" . $sticks . "' ,
              audio_type    = '" . $_POST['audio_type'] . "',
              author_id     = '"  .$_POST['author']."',
              status        = '" . $_POST['status'] . "'

              WHERE id = '" . $edit_id . "'";
    $GLOBALS['lbdata']->Execute($dosql);

$dosql = "UPDATE ".$GLOBALS['prefix']."lb_settings SET
		       value  = '" . $_POST['previews'] . "'
           WHERE name = 'previews'";
           $GLOBALS['lbdata']->Execute($dosql);


    //deleting links from database
    $dosql = "DELETE FROM ".$GLOBALS['prefix']."lb_links
              WHERE posting_id = '" . $edit_id . "'";
    $GLOBALS['lbdata']->Execute($dosql);

    //put posted links into database
    for ($i = 0; $i< $settings['showlinks']; $i++) {
        $temptit = "linktit" . $i;
        $tempurl = "linkurl" . $i;
        $tempdes = "linkdes" . $i;

        $writetit = htmlentities($_POST[$temptit], ENT_QUOTES, "UTF-8");
        $writedes = htmlentities($_POST[$tempdes], ENT_QUOTES, "UTF-8");
        $writeurl = htmlentities($_POST[$tempurl], ENT_QUOTES, "UTF-8");

        if (strrpos($writeurl, "://") == false) {
        	$writeurl = "http://".$writeurl;
        }

        if ($_POST[$tempurl] != "") {
        $dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_links
                 (posting_id, linkorder, title, url, description)
                 VALUES
                 (
                 '" . $edit_id . "', '" . $i . "',
                 '" . $writetit . "', '" . $writeurl . "', '" . $writedes . "'
                 )";
        $GLOBALS['lbdata']->Execute($dosql);
        }
    }

    //ping via xml-rpc if possible and if posting is "live"
    if (
    ($settings['ping'] == "1") AND
    (ini_get('allow_call_time_pass_reference') == "1") AND
    ($_POST['status'] == 3)
    ) {
        echo '<script type="text/javascript">active_popup("index.php?page=ping","ping", 170,35);</script>';
    }

    //create fresh version of the static xml feeds
    if ($settings['staticfeed'] == "1") { include ('inc/staticfeeds.php'); }
}


//gets all the data from the posting-id we want to edit
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_postings
          WHERE id = '". $edit_id ."'";
$result = $GLOBALS['lbdata']->GetArray($dosql);
$fields = $result[0];


//if posting date has changed, try to rename audio file, too
$newdate = $fields['posted'];
if (isset($olddate)
AND ($newdate != $olddate)
AND ($fields['filelocal'] == "1")
AND ($settings['rename'] == "1")) {
    $suffix = strrchr($fields['audio_file'], ".");
    $newname = buildaudioname($suffix, $settings['filename'], $newdate);
    //rename the file
    $oldpath = $GLOBALS['audiopath'] . $fields['audio_file'];
    $newpath = $GLOBALS['audiopath'] . $newname;

    if(rename ($oldpath, $newpath))  {
    $fields['audio_file'] = $newname;
    $dosql = "UPDATE ".$GLOBALS['prefix']."lb_postings SET
        posted =     '" . $newdate . "',
        audio_file = '" . $newname . "'
        WHERE id = '" . $edit_id . "'";
    $GLOBALS['lbdata']->Execute($dosql);
    }
}


// --------------------------------------------------------------------------
$settings = getsettings();

include "changepost.php";

//show us the (pre-filled) forms to change the details!!!
echo "<form action=\"index.php?page=record2&amp;do=save&amp;id=". $edit_id;
echo "\" method=\"post\" enctype=\"multipart/form-data\">";


?>

<div id="leftcolumn">

<!--                                      title  -->
<?php
echo "  <h3>".bla("rec2_title")."</h3>\n";
echo "  <input id=\"title\" type=\"text\" name=\"title\"";
echo readonly($edit_id);
echo " value=\"" . urldecode($fields['title']) . "\" />\n\n";
?>


<!--                                      text message  -->
<?php
echo "  <h3>".bla("rec2_message")."</h3>\n";
echo "  <textarea " . readonly($edit_id) . " name=\"message\">";
echo trim($fields['message_input']);
echo "</textarea>";
?>


<!--                                      tags          -->
<script>

function selectItem(li) {

}

function formatItem(row) {
	return row;
}

$(document).ready(function() {
	$("#suggest").autocomplete('autocomplete.php', { minChars:1, matchSubset:1, matchContains:1, cacheLength:10, onItemSelect:selectItem, formatItem:formatItem, selectOnly:1, mode:"multiple",multipleSeparator:" " });
});
</script>

<?php
echo "<h3>".bla("rec2_tags")."</h3>\n";

echo "<input id=\"suggest\" type=\"text\" class=\"tags\" name=\"tags\" ";
echo readonly($edit_id);
echo "value=\"".urldecode($fields['tags'])."\"/>";

?>

<!--                                      categories  -->
<?php
echo "<h3>".bla("rec2_cats")."</h3>\n";

//getting all data from category-table
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_categories ORDER BY id";
$cats = $GLOBALS['lbdata']->GetArray($dosql);

//show four lists with categories
for ($i=1; $i<5; $i++) {
    echo "<select class=\"category\" ". readonly($edit_id);
    echo " name=\"cat" . $i . "\">\n";
    echo "<option value=\"\">---</option>\n";

    //show all items in each list
    $j = 0;
    foreach ($cats as $showcat) {
        echo "<option value=\"" . $cats[$j]['id'] . "\"";
        $temp = "category" . ($i) . "_id";
        if ($fields[$temp] == $cats[$j]['id']) echo " selected";
        echo ">" . urldecode($cats[$j]['name']) . "</option>\n";
        $j += 1;
    }
    echo "</select>\n";
}?>
<!--      author     -->
 <?php if(getuserrights("admin"))  {
    $dosql = "SELECT id, nickname FROM ".$GLOBALS['prefix']."lb_authors";
    $authorarray = $GLOBALS['lbdata'] -> GetArray($dosql);
    foreach ($authorarray as $author)  {
      if($author['id'] == $fields['author_id']) {
                            $me = $author['nickname'];
                            $my_id = $author['id'];
      }
    }
    echo "<h3>".bla("rec2_author")."</h3>\n\n";
    echo "<select name = \"author\" class = \"author\">\n";
    echo "<option value =\"".$my_id."\">".$me."</option>";
    foreach ($authorarray as $author)  {
      if ($author['id'] == $my_id) { continue;}
      echo "<option value = \"".$author['id']."\">".$author['nickname']."</option>\n"; }
    echo"</select><br />";


} else  {
        echo "<input type=\"hidden\" name=\"author\" value=\"".$fields['author_id']."\">";
} ?>

<!--                                      sticky posting  -->
<?php
#echo "<h3>".bla("rec2_sticky")."</h3>\n";
#echo "<select name=\"sticky\" class=\"sticky\">";

#echo "<option class=\"sticky\" value=\"1\" ";
#if (urldecode($fields['sticky'])) echo 'selected="selected"';
#echo " >" . bla("yes");

#echo "<option class=\"sticky\" value=\"0\" ";
#if (!urldecode($fields['sticky'])) echo 'selected="selected"';
#echo " >" . bla("no");

#echo "</option>";
#echo "</select>";
#echo readonly($edit_id);
#echo "value=\"".urldecode($fields['sticky'])."\"/>";
?>
</div>

<div id="rightcolumn">


<!--                                      file  -->

<?php
//$tempauth = getnickname($fields['author_id']);
//if ($tempauth == $_SESSION['nickname']) { $tempauth = "yourself"; }

if (!empty($fields['audio_file']) AND ($fields['audio_file'] != "NULL")) {

echo "<h3>".bla("rec2_audio");
//echo " (".bla("rec2_by")." ".$tempauth.")";
echo "</h3>\n";

//do we have a local file? then show detailled information!
if ($fields['filelocal'] == 1) {

//showing and linking to audiofile. local or not?
$link = $settings['url'] . "/audio/" . $fields['audio_file'];
$type = $fields['audio_type'];



//can...only...play...mp3...with...flash...player
if ($fields['audio_type'] == 1) {
    echo showflash ("backend/emff_rec2.swf?src=".$link, 295, 9);
	
	
	
}

//can...play...several...other...formats...with...quicktime
if (($type == "2") OR
    ($type == "5") OR
    ($type == "6") OR
    ($type == "9") OR
    ($type == "12") OR
    ($type == "14") OR
    ($type == "10") OR
    ($type == "13") OR
    ($type == "7")) {
    $src = $settings['url']."/loudblog/backend/clicktoplayback2.mov";

        $target = "myself";

        //ist it a video or enhanced podcast? Link to external player!
        if (($type == "14") OR
            ($type == "10") OR
            ($type == "13") OR
            ($type == "7")) {
            $target = "quicktimeplayer";
        }
    //build html-code for quicktime plugin (audio)
    echo showquicktime ($src, $link, 295, 16, $target, "false");
}


//preparing data
$id3 = getid3data($GLOBALS['audiopath'].$fields['audio_file'],"back");

//showing data for that mp3 file
echo "<table id=\"audiodata\">\n";
echo "<tr><td>".bla("rec2_filename")."</td><td><a href=\"" . $link . "\">";
echo $fields['audio_file'] . "</a></td></tr>\n";

echo "<tr><td>".bla("rec2_sizedur")."</td><td>".getmegabyte($id3['size'])."MB / ". $id3['duration'].bla("short_minutes")."</td></tr>\n";

echo "<tr><td>".bla("rec2_quality")."</td><td>";
echo ($id3['audio']['bitrate'] / 1000)."kb/s (";
echo strtoupper($id3['audio']['bitrate_mode']).") / ";
echo ($id3['audio']['sample_rate'] / 1000)."kHz / ";
echo ($id3['audio']['channelmode']);
echo "</td></tr>\n";

echo "<tr><td>".bla("rec2_id3")."</td><td>".$id3['title'];
echo " (".$id3['track'].")</td></tr>\n";
echo "</table>\n";

//button for manipulating id3-tags
if ((allowed(1,$edit_id)) AND ($fields['audio_type'] == "1")) {
    echo "<input href=\"index.php?page=id3&amp;id={$fields['id']}\" ";
    echo "class=\"audiobutton\" value=\"".bla("but_editid3")."\" type=\"button\" ";
    echo "onClick=\"link_popup(this,780,400); return false\" />";
}

//button for changing the audio file
if (allowed(1,$edit_id)) {
    echo "<input class=\"audiobutton right\" value=\"".bla("but_changeaudio")."\" type=\"button\"";
    echo " onClick=\"self.location.href='index.php?page=record1&amp;do=update&amp;id={$fields['id']}'\" />";
}


//sending size/length/type information for database
echo "<input type=\"hidden\" name=\"audio_length\" value=\"".getseconds($id3['duration'])."\" />";
echo "<input type=\"hidden\" name=\"audio_size\" value=\"".getmegabyte($id3['size'])."\" />";
echo "<input type=\"hidden\" name=\"audio_type\" value=\"".type_suffix($fields['audio_file'])."\" />";

} else {
//we have only a link to a remote file? show other data!

$link = $fields['audio_file'];
$type = $fields['audio_type'];

//can...only...play...mp3...with...flash...player
if ($type == "1") {
    echo showflash("backend/emff_rec2.swf?src=".$link, 295, 9);
}

//can...play...several...other...formats...with...quicktime
if (($type == "2") OR
    ($type == "5") OR
    ($type == "6") OR
    ($type == "9") OR
    ($type == "12") OR
    ($type == "14") OR
    ($type == "10") OR
    ($type == "13") OR
    ($type == "7")) {
    $src = $settings['url']."/loudblog/backend/clicktoplayback2.mov";

    $target = "myself";

        //ist it a video or enhanced podcast? Link to external player!
        if (($type == "14") OR
            ($type == "10") OR
            ($type == "13") OR
            ($type == "7")) {
            $target = "quicktimeplayer";
        }
    //build html-code for quicktime plugin (audio)
    echo showquicktime($src, $link, 295, 16, $target, "false");
}

//showing plain link
echo "<table id=\"audiodata\">\n";
echo "<tr><td><a href=\"" . $link . "\">";
echo wordwrap($fields['audio_file'], 50, "<br />", 1) . "</a></td></tr>\n";
echo "</table>\n";

echo "<input type=\"hidden\" name=\"audio_type\" value=\"".type_suffix($fields['audio_file'])."\" />";

?>

<hr />


<!--                                      size  -->
<div class="rec2size">
<?php
echo "<h3>".bla("rec2_size")."</h3>\n";
if ($fields['filelocal'] == 1) {
echo "<input ". readonly($edit_id) . "type=\"text\" readonly=\"readonly\" ";
echo "value=\"" . getmegabyte($fields['audio_size']) . " MB\" />";
echo "<input type=\"hidden\" name=\"audio_size\" ";
echo "value=\"" . getmegabyte($fields['audio_size']) . "\" />";
} else {
echo "<input ". readonly($edit_id) . "type=\"text\" name=\"audio_size\"";
echo "value=\"" . getmegabyte($fields['audio_size']) . " MB\" />";
}
?>

</div>


<!--                                      length  -->

<div class="rec2size">
<?php
echo "<h3>".bla("rec2_dur")."</h3>\n";
echo "<input " . readonly($edit_id) . "type=\"text\" name=\"audio_length\" value=\"" . $fields['audio_length']." ".bla("short_seconds")."\" />"; ?>
</div>


<div class="fileinfo right">
<?php
echo "<h3>".bla("rec2_audiofile")."</h3>";

//button for changing the audio file

if (allowed(1,$edit_id)) {
    echo "<input class=\"audiobutton change\" value=\"".bla("but_changeaudio")."\" type=\"button\"";
    echo " onClick=\"self.location.href='index.php?page=record1&amp;do=update&amp;id=";
    echo $fields['id'] . "'\" />";
}
echo "</div>\n";


}

echo "\n\n<hr />\n\n";

} else {
// We have no audio file at all? Huh? Okay then...

echo "<h3>".bla("rec2_noaudio")."</h3>\n";
echo "<div class=\"fileinfo left\">\n";
echo "<p>".bla("rec2_audiolater")."</p>\n";
echo "</div>\n\n";

//show hidden input fields with zero values
echo "<input type=\"hidden\" name=\"audio_length\" value=\"0\" />";
echo "<input type=\"hidden\" name=\"audio_size\" value=\"0\" />";
echo "<input type=\"hidden\" name=\"audio_type\" value=\"0\" />";

echo "<div class=\"fileinfo right\">";
if (allowed(1,$edit_id)) {
    echo "<input class=\"audiobutton change\" value=\"".bla("but_addaudio")."\" type=\"button\"";
    echo " onClick=\"self.location.href='index.php?page=record1&amp;do=update&amp;id=";
    echo $fields['id'] . "'\" />";
}
echo "</div>";
echo "<hr />";
}

?>

<!--                                      comments  -->


<?php
//preparing preselection of the comments-on/off-switch
if ($fields['comment_on'] == 1) { $temp1 = 'checked="checked"'; $temp2 = ''; }
                          else { $temp1 = ''; $temp2 = 'checked="checked" '; }

//preparing preselection of the comments-size-menue
$tempcommsize = array(
    "0"=>bla("rec2_noaudioallowed"),
    "204800"=>"200 KB",
    "512000"=>"500 KB",
    "1048576"=>"1 MB",
    "1572864"=>"1.5 MB",
    "2097152"=>"2 MB",
    "5242880"=>"5 MB",
    "10485760"=>"10 MB",
    "999999999"=>bla("rec2_nolimit")
)
?>

<div class="fileinfo left">
<?php echo "<h3>".bla("rec2_comments")."</h3>";

echo "<input ".readonly($edit_id)." class=\"radio\" type=\"radio\" name=\"comment_on\" value=\"on\" ".$temp1." />".bla("on")."&nbsp;&nbsp;\n";

echo "<input ".readonly($edit_id)." class=\"radio\" type=\"radio\" name=\"comment_on\" value=\"off\" ".$temp2." />".bla("off");
?>

</div>


<div class="fileinfo right">
<?php
echo "<h3>".bla("rec2_sizelimit")."</h3>";
echo "<select ".readonly($edit_id)." name=\"comment_size\">";

//generating dropdownmenue with comment-sizes
foreach ($tempcommsize as $tempsize => $tempshow) {
    echo "<option " . readonly($edit_id) . "value=\"".$tempsize."\"";
    if ($tempsize == $fields['comment_size']) echo " selected";
    echo ">".$tempshow."</option>\n";
}
?>
</select>
</div>

<hr />




<!--                                      status -->

<?php
//very un-elegant preparing for showing the current status
$temp = array ("", "", "", "");
$temp[$fields['status']] = "checked=\"checked\"";

echo "<h3>".bla("rec2_status")."</h3>";
?>
<input type="radio" name="status" value="1"
        <?php echo readonly($edit_id).$temp[1]." />".bla("draft"); ?> &nbsp;&nbsp;

<input type="radio" name="status" value="2"
        <?php echo readonly($edit_id) . $temp[2]." />".bla("finished"); ?> &nbsp;&nbsp;


<input type="radio" name="status" value="3"

        <?php if (!allowed(2,$fields['id'])) { echo "readonly=\"readonly\""; }
        echo $temp[3]." />".bla("onair"); ?>



<hr />

<!....PREVIEW SETTING......-->
<?php
echo "<h3>".bla("rec2_preview")."</h3>";

 $temp = array ("", "");
    $temp[$settings['previews']] = "checked=\"checked\""; ?>
    <input class="radio" name="previews" type="radio" value="1" <?php echo $temp[1]."/>Yes"; ?>&nbsp;&nbsp;
    <input class="radio" name="previews" type="radio" value="0" <?php echo $temp[0]."/>No"; ?>


<hr />


</div>

<div id="postsave">



<!--                                      Posted  -->

<?php echo "<h3>".bla("rec2_posttime")."</h3>"; ?>
<div id="date">



<input id="year" type="text" name="post1" maxlength="4" value="<?php
    echo date("Y", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>
<input type="text" name="post2" maxlength="2" value="<?php
    echo date("m", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>
<input type="text" name="post3" maxlength="2" value="<?php
    echo date("d", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>
<h4> <?php echo bla("at"); ?> </h4>
<input type="text" name="post4" maxlength="2" value="<?php
    echo date("H", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>
<h4>:</h4>
<input type="text" name="post5" maxlength="2" value="<?php
    echo date("i", strtotime($fields['posted']));
    echo "\" " . readonly($edit_id); ?>/>

<h4> <?php echo bla("rec2_setnow"); ?> </h4>
<input <?php echo readonly($edit_id); ?> id="now" type="checkbox" name="now" />
<?php if (urldecode($fields['sticky']))
{$checked = 'checked = "checked"';}
else {$checked = '';} ?>
<h4> <?php echo bla("rec2_sticks"); ?> </h4>
<input <?php echo readonly($edit_id); ?> id="sticky" type="checkbox" name="sticky" <?php echo $checked; ?> />
<br/>



</div>




<!--                                      submit-button  -->
<div class="submit">
<?php
if (allowed(1,$edit_id)) {
echo "<input class=\"save\" type=\"submit\" value=\"".bla("but_saveall")."\" />";
}
?>
</div>

</div>


<div id="hyperlinks">



<!--                                      links  -->
<?php

//getting existing links from database
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_links 
          WHERE posting_id = '" . $edit_id . "' 
          ORDER BY linkorder ASC";
$links = $GLOBALS['lbdata']->GetArray($dosql);

echo "<table class=\"plain topspace\">\n<tr>\n<th>".bla("rec2_linkurl")."</th>\n";
echo "<th>".bla("rec2_linkname")."</th>\n<th>";
echo bla("rec2_linkdesc")."</th>\n</tr>\n";

for ($i = 0; $i < $settings['showlinks']; $i++) {
    
    //no entry in database? generate empty data!
    if (!isset($links[$i]['linkorder'])) {
        $links[$i]['url'] = "";
        $links[$i]['title'] = "";
        $links[$i]['description'] = "";
    }
 
    //show the link-forms
    echo "<tr>";
    
    echo "<td class=\"left\"><input ". readonly($edit_id) . " type=\"text\" value=\"" . $links[$i]['url'];
    echo "\" name=\"linkurl" . $i . "\" /></td>\n";
    
    echo "<td class=\"center\"><input ". readonly($edit_id) . " type=\"text\" value=\"" . $links[$i]['title'];
    echo "\" name=\"linktit" . $i . "\" /></td>\n";
    
    echo "<td class=\"right\"><input ". readonly($edit_id) . " type=\"text\" value=\"" . $links[$i]['description'];
    echo "\" name=\"linkdes" . $i . "\" /></td>\n";
    
    echo "</tr>";
}

?>
</table>

</div>



<!--                                      submit-button  -->
<div class="submit">

<?php
if (allowed(1,$edit_id)) {
echo "<input class=\"save\" type=\"submit\" value=\"".bla("but_saveall")."\" />";
}
?>
</div>



</form>

<?php 


    
}

//no audio file? error message!
else { 

echo "<p class=\"msg\">".bla("msg_noaudio")."</p>\n\n"; 

}


?>