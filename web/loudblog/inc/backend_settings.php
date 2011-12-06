<?php

//change the language if required by POST
if (isset($_POST['language'])) {
	$mylanguage = killevilcharacters(strip_tags($_POST['language']));
    include_once("lang/".$mylanguage.".php");
}

echo "<h1>".bla("hl_settings")."</h1>\n";

include ('inc/navigation.php');

//check the rights
if (!allowed(3,"")) 
{ die("<p class=\"msg\">".bla("msg_adminonly")."</p>"); }


//put the posted data into the databse
if ((isset($_GET['do'])) AND ($_GET['do'] == "save")) {


//take care of picture 1
if (isset($_FILES['itunes_image']) && $_FILES['itunes_image']['size']<>"0"){
    $newfilename = $GLOBALS['audiopath'] . "itunescover.jpg";
    move_uploaded_file($_FILES['itunes_image']['tmp_name'], $newfilename) 
        OR die ("<p class=\"msg\">".bla("msg_uploadbroken")."</p>");
    chmod ($newfilename, 0777);
}

//take care of picture 2
if ((isset($_FILES['feedimage'])) AND ($_FILES['feedimage']['size'] <> "0")) {
    $newfilename = $GLOBALS['audiopath'] . "rssimage.jpg";
    move_uploaded_file($_FILES['feedimage']['tmp_name'], $newfilename) 
        OR die ("<p class=\"msg\">".bla("msg_uploadbroken")."</p>");
    chmod ($newfilename, 0777);
}

//forms with a checkbox will not be posted if not checked :-(
if (!isset($_POST['countweb'])) { $_POST['countweb'] = "0"; }
if (!isset($_POST['countfla'])) { $_POST['countfla'] = "0"; }
if (!isset($_POST['countpod'])) { $_POST['countpod'] = "0"; }

//patented loopsave of post-data, wow!
foreach ($_POST as $setname => $setvalue) {

if (substr($setname,0,4) != "id3_") {
    $setvalue = htmlentities($setvalue, ENT_QUOTES, "UTF-8");
}

//write things from post-data into database
$dosql = "UPDATE ".$GLOBALS['prefix']."lb_settings SET    
          value = '" . $setvalue . "' 
          WHERE name = '" . $setname . "'";
$GLOBALS['lbdata']->Execute($dosql); 
}

}

//now begin with showing forms!!

$settings = getsettings ();

//create fresh version of the static xml feeds
if ($settings['staticfeed'] == "1") { include ('inc/staticfeeds.php'); }

?>

<form action="index.php?page=settings&amp;do=save" 
    method="post" enctype="multipart/form-data">


<div class="savebutton">
    <input type="submit" value="<?php echo bla("but_saveset"); ?>" />
</div>

<!--  ++++++++++++++++++++++++++++++++++  -->

<h2><?php echo bla("set_sec_meta"); ?></h2>
<table>

<tr>
    <td class="left"><?php echo bla("set_language"); ?>:</td>
    <td class="center">
        <select name="language">
        <?php
        //gets the filenames of all the files in the lang-folder. make a list.
        $langfolder = opendir('lang'); 

        while ($file = readdir($langfolder)) { 
            if (substr($file, -4) == ".php") { 
                $choosefile = substr($file, 0, -4); 
                echo "<option value=\"" . $choosefile . "\"";
                if ($choosefile == $settings['language']) {
                    echo "selected=\"selected\"";
                }
                echo ">". $choosefile . "</option>";
            }
        }
        closedir($langfolder);
        ?>
        </select>
    
    </td>
    <td class="right">
    <?php echo bla("set_languagehelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_name"); ?>:</td>
    <td class="center">
    <input name="sitename" type="text"
    value="<?php echo $settings['sitename']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_namehelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_slogan"); ?>:</td>
    <td class="center">
    <input name="slogan" type="text"
    value="<?php echo $settings['slogan']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_sloganhelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_desc"); ?>:</td>
    <td class="center">
    <textarea name="description" rows="4"><?php echo $settings['description']; ?></textarea>
    </td>
    <td class="right">
    <?php echo bla("set_deschelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_url"); ?>:</td>
    <td class="center">
    <input name="url" type="text"
    value="<?php echo $settings['url']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_urlhelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_template"); ?>:</td>
    <td class="center">
    <select name="template">
    <?php 
    //gets the filenames of all the files in the upload-folder. make a list.
$templatefolder = opendir('custom/templates'); 

while ($file = readdir($templatefolder)) { 
    if ( substr($file, 0, 1) != ".") { 
        $choosefile = $file; 
        echo "<option value=\"" . $choosefile . "\"";
        if ($choosefile == $settings['template']) { 
            echo " selected=\"selected\"";
        }
        echo "\">" . $choosefile . "</option>";
    }
}
closedir($templatefolder);
 ?>   
    </select>
    </td>
    <td class="right">
    <?php echo bla("set_templatehelp"); ?>
    </td>
</tr>


</table>

<!--  ++++++++++++++++++++++++++++++++++  -->

<h2><?php echo bla("set_sec_feed"); ?></h2>
<table>

<tr>
    <td class="left"><?php echo bla("set_feedauthor"); ?>:</td>
    <td class="center">
    <input name="itunes_author" type="text"
    value="<?php echo $settings['itunes_author']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_feedauthorhelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_feedmail"); ?>:</td>
    <td class="center">
    <input name="itunes_email" type="text"
    value="<?php echo $settings['itunes_email']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_feedmailhelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_copyright"); ?>:</td>
    <td class="center">
    <input name="copyright" type="text"
    value="<?php echo $settings['copyright']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_copyrighthelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_items"); ?>:</td>
    <td class="center">
    <input name="rss_postings" type="text"
    value="<?php echo $settings['rss_postings']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_itemshelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_itunesart"); ?>:</td>
    <td class="center">
    <input class="fileupper" type="file" name="itunes_image" accept="image/*" />
    </td>
    <td class="right">
    <a href="../audio/itunescover.jpg"><img class="coverart" src="../audio/itunescover.jpg" /></a><?php echo bla("set_itunesarthelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_rssimage"); ?>:</td>
    <td class="center">
    <input class="fileupper" type="file" name="feedimage" accept="image/*" />
    </td>
    <td class="right">
    <a href="../audio/rssimage.jpg"><img class="rssimage" src="../audio/rssimage.jpg" /></a><?php echo bla("set_rssimagehelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_explicit"); ?></td>
    <td class="center">
    <?php $temp = array ("", ""); 
    $temp[$settings['itunes_explicit']] = " checked=\"checked\""; ?>
    <input class="radio" name="itunes_explicit" type="radio" value="1"<?php echo $temp[1]." />".bla("yes"); ?>&nbsp;&nbsp;
    <input class="radio" name="itunes_explicit" type="radio" value="0"<?php echo $temp[0]." />".bla("no"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_explicithelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_itunescats"); ?>:</td>
    <td class="center">
    
    <?php 
    
        echo "<select class=\"itcats\" name=\"feedcat1\">";
        foreach ($itunescats as $long => $short) {
            echo "<option value=\"".$short."\"";
            if ($settings['feedcat1'] == $short) {
                echo "selected=\"selected\"";
            }
            echo ">".$long."</option>\n";
        }
        echo "</select>";
        
        echo "<select class=\"itcats\" name=\"feedcat2\">";
        foreach ($itunescats as $long => $short) {
            echo "<option value=\"".$short."\"";
            if ($settings['feedcat2'] == $short) {
                echo "selected=\"selected\"";
            }
            echo ">".$long."</option>\n";
        }
        echo "</select>";
        
        echo "<select class=\"itcats\" name=\"feedcat3\">";
        foreach ($itunescats as $long => $short) {
            echo "<option value=\"".$short."\"";
            if ($settings['feedcat3'] == $short) {
                echo "selected=\"selected\"";
            }
            echo ">".$long."</option>\n";
        }
        echo "</select>";
        
        echo "<select class=\"itcats\" name=\"feedcat4\">";
        foreach ($itunescats as $long => $short) {
            echo "<option value=\"".$short."\"";
            if ($settings['feedcat4'] == $short) {
                echo "selected=\"selected\"";
            }
            echo ">".$long."</option>\n";
        }
        echo "</select>";
        
        ?>
    </td>
    <td class="right">
    <?php echo bla("set_itunescatshelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_langloc"); ?>:</td>
    <td class="center">
        <select name="languagecode">
        
        <?php 
            
        foreach ($langs as $long => $short) {
        echo "<option value=\"".$short."\"";
        
        if ($settings['languagecode'] == $short) {
            echo "selected=\"selected\"";
        }
        
        echo ">".$long."</option>\n";
        }
        
        ?>
        </select>    
    </td>
    <td class="right">
    <?php echo bla("set_langlochelp"); ?>
    </td>
</tr>

<?php if (ini_get('allow_url_fopen')) {

echo "<tr>";
    echo "<td class=\"left\">".bla("set_staticfeed")."</td>";
    echo "<td class=\"center\">";
    $temp = array ("", "");
    $temp[$settings['staticfeed']] = " checked=\"checked\"";
    echo "<input class=\"radio\" name=\"staticfeed\" type=\"radio\" value=\"1\"".$temp[1]." />".bla("yes")."&nbsp;&nbsp;";
    echo "<input class=\"radio\" name=\"staticfeed\" type=\"radio\" value=\"0\"".$temp[0]." />".bla("no");
    echo "</td>";
    echo "<td class=\"right\">";
    echo bla("set_staticfeedhelp");
    echo "</td>";
echo "</tr>";
 echo "<tr>";
    echo "<td class=\"left\">".bla("set_staticfeeds_tags")."</td>";
    echo "<td class=\"center\">";
    $temp = array ("", "");
    $temp[$settings['staticfeeds_tags']] = " checked=\"checked\"";
    echo "<input class=\"radio\" name=\"staticfeeds_tags\" type=\"radio\" value=\"1\"".$temp[1]." />".bla("yes")."&nbsp;&nbsp;";
    echo "<input class=\"radio\" name=\"staticfeeds_tags\" type=\"radio\" value=\"0\"".$temp[0]." />".bla("no");
    echo "</td>";
    echo "<td class=\"right\">";
    echo bla("set_staticfeeds_tags_help");
    echo "</td>";
echo "</tr>";
  }   ?>



</table>

<!--  ++++++++++++++++++++++++++++++++++  -->

<h2><?php echo bla("set_sec_comments"); ?></h2>
<table>

<tr>
    <td class="left"><?php echo bla("set_acceptcomments"); ?></td>
    <td class="center">
    <?php $temp = array ("", ""); 
    $temp[$settings['acceptcomments']] = "checked=\"checked\""; ?>
    <input class="radio" name="acceptcomments" type="radio" value="1" <?php echo $temp[1]."/>".bla("yes"); ?>&nbsp;&nbsp;
    <input class="radio" name="acceptcomments" type="radio" value="0" <?php echo $temp[0]."/>".bla("no"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_acceptcommentshelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_preventspam"); ?></td>
    <td class="center">
    <?php $temp = array ("", ""); 
    $temp[$settings['preventspam']] = "checked=\"checked\""; ?>
    <input class="radio" name="preventspam" type="radio" value="1" <?php echo $temp[1]."/>".bla("yes"); ?>&nbsp;&nbsp;
    <input class="radio" name="preventspam" type="radio" value="0" <?php echo $temp[0]."/>".bla("no"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_preventspamhelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_spamquestion"); ?>:</td>
    <td class="center">
    	<input name="spamquestion" type="text" value="<?php echo $settings['spamquestion']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_spamquestionhelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_spamanswer"); ?>:</td>
    <td class="center">
    	<input name="spamanswer" type="text" value="<?php echo $settings['spamanswer']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_spamanswerhelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_badbehavior"); ?></td>
    <td class="center">
    <?php $temp = array ("", "");
    $temp[$settings['badbehavior']] = "checked=\"checked\""; ?>
    <input class="radio" name="badbehavior" type="radio" value="1" <?php echo $temp[1]."/>".bla("yes"); ?>&nbsp;&nbsp;
    <input class="radio" name="badbehavior" type="radio" value="0" <?php echo $temp[0]."/>".bla("no"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_badbehaviorhelp"); ?>
    </td>
</tr>

<tr>
	<td class="left"><?php echo bla("set_emergency_email"); ?>:</td>
	<td class="center">
		<input type="text" name="emergency_email" value="<?php echo $settings['emergency_email']; ?>" />
	</td>
	<td class="right">
    <?php echo bla("set_emergency_email_help"); ?>
    </td>
</tr>
<?php if (isset($GLOBALS['settings']['bb2_installed']))  {
 echo "<tr>";
    echo "<td class=\"left\">".bla("set_verbose")."</td>";
    echo "<td class=\"center\">";
    $temp = array ("", "");
    $temp[$settings['bb2_verbose']] = "checked=\"checked\"";
    echo "<input class=\"radio\" name=\"bb2_verbose\" type=\"radio\" value=\"1\"".$temp[1]."/>".bla("yes")."&nbsp;&nbsp;";
    echo "<input class=\"radio\" name=\"bb2_verbose\" type=\"radio\" value=\"0\"".$temp[0]."/>".bla("no");
    echo "</td>";
    echo "<td class=\"right\">";
    echo bla("set_verbosehelp");
    echo "</td>";
 echo "</tr>";
  echo "<tr>";
    echo "<td class=\"left\">".bla("set_strict")."</td>";
    echo "<td class=\"center\">";
    $temp = array ("", "");
    $temp[$settings['bb2_strict']] = "checked=\"checked\"";
    echo "<input class=\"radio\" name=\"bb2_strict\" type=\"radio\" value=\"1\"".$temp[1]."/>".bla("yes")."&nbsp;&nbsp;";
    echo "<input class=\"radio\" name=\"bb2_strict\" type=\"radio\" value=\"0\"".$temp[0]."/>".bla("no");
    echo "</td>";
    echo "<td class=\"right\">";
    echo bla("set_stricthelp");
    echo "</td>";
 echo "</tr>";
 
} ?>

</table>

<!--  ++++++++++++++++++++++++++++++++++  -->

<h2><?php echo bla("set_sec_various"); ?></h2>
<table>
<tr>
    <td class="left"><?php echo bla("set_html"); ?>:</td>
    <td class="center">
    <?php $temp = array ("", "", "", ""); 
    $temp[$settings['markuphelp']] = " checked=\"checked\""; ?>
    
    <input class="radio" name="markuphelp" type="radio" value="1" <?php echo $temp[1]; ?>/>Textile (<a href="http://www.textism.com/tools/textile/index.html"><?php echo bla("short_information"); ?></a>)<br />
    <input class="radio" name="markuphelp" type="radio" value="2" <?php echo $temp[2]; ?>/>Markdown (<a href="http://daringfireball.net/projects/markdown/"><?php echo bla("short_information"); ?></a>)<br />
    <input class="radio" name="markuphelp" type="radio" value="3" <?php echo $temp[3]; ?>/>BBCode (<a href="http://www.phpbb.com/phpBB/faq.php?mode=bbcode"><?php echo bla("short_information"); ?></a>)<br />
    <input class="radio" name="markuphelp" type="radio" value="0" <?php echo $temp[0]." />".bla("none"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_htmlhelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_hyperlinks"); ?>:</td>
    <td class="center">
    <input name="showlinks" type="text"
    value="<?php echo $settings['showlinks']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_hyperlinkshelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_dateformat"); ?>:</td>
    <td class="center">
    <input name="dateformat" type="text"
    value="<?php echo $settings['dateformat']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_dateformathelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_ping"); ?></td>
    <td class="center">
    <?php $temp = array ("", ""); 
    $temp[$settings['ping']] = "checked=\"checked\""; ?>
    <input class="radio" name="ping" type="radio" value="1" <?php echo $temp[1]."/>".bla("yes"); ?>&nbsp;&nbsp;
    <input class="radio" name="ping" type="radio" value="0" <?php echo $temp[0]."/>".bla("no"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_pinghelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_count").":"; ?></td>
    <td class="center">
    <?php $temp = array ($settings['countweb'], $settings['countfla'], $settings['countpod']);
    foreach ($temp as $i => $state) {
        if ($state == "1") { $temp[$i] = "checked=\"checked\""; }
        else { $temp[$i] = ""; }
    } ?>
    <input class="radio" name="countweb" type="checkbox" value="1" <?php echo $temp[0]."/>".bla("set_countweb"); ?><br />
    <input class="radio" name="countfla" type="checkbox" value="1" <?php echo $temp[1]."/>".bla("set_countfla"); ?><br />
    <input class="radio" name="countpod" type="checkbox" value="1" <?php echo $temp[2]."/>".bla("set_countpod"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_counthelp"); ?>
    </td>
</tr>

</table>


<!--  ++++++++++++++++++++++++++++++++++  -->

<h2><?php echo bla("set_sec_filename"); ?></h2>
<table>
<tr>
    <td class="left"><?php echo bla("set_rename"); ?></td>
    <td class="center">
    <?php $temp = array ("", ""); 
    $temp[$settings['rename']] = "checked=\"checked\""; ?>
    <input class="radio" name="rename" type="radio" value="1" <?php echo $temp[1]."/>".bla("yes"); ?>&nbsp;&nbsp;
    <input class="radio" name="rename" type="radio" value="0" <?php echo $temp[0]."/>".bla("no"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_renamehelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_custfile"); ?>:</td>
    <td class="center">
    <input name="filename" type="text"
    value="<?php echo $settings['filename']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_custfilehelp"); ?>
    </td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_filedemo"); ?>:</td>
    <td class="center">
    <code><?php echo $settings['filename']; ?>-2005-05-27-51816.mp3 
    </code>
    </td>
    <td class="right">
    <?php echo bla("set_filedemohelp"); ?>
    </td>
</tr>
</table>

<!--  ++++++++++++++++++++++++++++++++++  -->

<h2><?php echo bla("set_sec_id3"); ?></h2>
<table>
<tr>
    <td class="left"><?php echo bla("set_id3write"); ?></td>
    <td class="center">
    <?php $temp = array ("", ""); 
    $temp[$settings['id3_overwrite']] = "checked=\"checked\""; ?>
    <input class="radio" name="id3_overwrite" type="radio" value="1" 
        <?php echo $temp[1]."/>".bla("yes"); ?>&nbsp;&nbsp;
    <input class="radio" name="id3_overwrite" type="radio" value="0" 
        <?php echo $temp[0]."/>".bla("no"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_id3writehelp"); ?>
    </td>
</tr>

<tr>
    <td class="subset"><?php echo bla("set_id3album"); ?>:</td>
    <td class="center">
    <input name="id3_album" type="text"
    value="<?php echo $settings['id3_album']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_id3albumhelp"); ?>
    </td>
</tr>

<tr>
    <td class="subset"><?php echo bla("set_id3artist"); ?>:</td>
    <td class="center">
    <input name="id3_artist" type="text"
    value="<?php echo $settings['id3_artist']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_id3artisthelp"); ?>    </td>
</tr>

<tr>
    <td class="subset"><?php echo bla("set_id3genre"); ?>:</td>
    <td class="center">
    <input name="id3_genre" type="text"
    value="<?php echo $settings['id3_genre']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_id3genrehelp"); ?>
    </td>
</tr>

<tr>
    <td class="subset"><?php echo bla("set_id3comment"); ?>:</td>
    <td class="center">
    <textarea name="id3_comment" rows="4"><?php echo $settings['id3_comment']; ?></textarea>
    </td>
    <td class="right">
    <?php echo bla("set_id3commenthelp"); ?>    
    </td>
</tr>
</table>

<!--  ++++++++++++++++++++++++++++++++++  -->

<h2><?php echo bla("set_sec_ftp"); ?></h2>
<table>
<tr>
    <td class="left"><?php echo bla("set_useftp"); ?></td>
    <td class="center">
    <?php $temp = array ("", ""); 
    $temp[$settings['ftp']] = "checked=\"checked\""; ?>
    <input class="radio" name="ftp" type="radio" value="1" <?php echo $temp[1]."/>".bla("yes"); ?>&nbsp;&nbsp;
    <input class="radio" name="ftp" type="radio" value="0" <?php echo $temp[0]."/>".bla("no"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_useftphelp"); ?>
    </td>
</tr>

<tr>
    <td class="subset"><?php echo bla("set_ftpserver"); ?>:</td>
    <td class="center">
    <input name="ftp_server" type="text"
    value="<?php echo $settings['ftp_server']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_ftpserverhelp"); ?>
    </td>
</tr>

<tr>
    <td class="subset"><?php echo bla("set_ftpuser"); ?>:</td>
    <td class="center">
    <input name="ftp_user" type="text"
    value="<?php echo $settings['ftp_user']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_ftpuserhelp"); ?>
    </td>
</tr>

<tr>
    <td class="subset"><?php echo bla("set_ftppass"); ?>:</td>
    <td class="center">
    <input name="ftp_pass" type="password"
    value="<?php echo $settings['ftp_pass']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_ftppasshelp"); ?>
    </td>
</tr>

<tr>
    <td class="subset"><?php echo bla("set_ftppath"); ?>:</td>
    <td class="center">
    <input name="ftp_path" type="text"
    value="<?php echo $settings['ftp_path']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_ftppathhelp"); ?>
    </td>
</tr>
</table>


<!--  ++++++++++++++++++++++++++++++++++  -->

<h2><?php echo bla("set_sec_cgi"); ?></h2>
<table>
<tr>
    <td class="left"><?php echo bla("set_cgi"); ?></td>
    <td class="center">
    <?php $temp = array ("", ""); 
    $temp[$settings['cgi']] = "checked=\"checked\""; ?>
    <input class="radio" name="cgi" type="radio" value="1" <?php echo $temp[1]."/>".bla("yes"); ?>&nbsp;&nbsp;
    <input class="radio" name="cgi" type="radio" value="0" <?php echo $temp[0]."/>".bla("no"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_cgihelp1")." ".getmegabyte(uploadlimit()).bla("set_cgihelp2"); ?></td>
</tr>

<tr>
    <td class="left"><?php echo bla("set_cgiloc"); ?>:</td>
    <td class="center">
    <?php $temp = array ("", ""); 
    $temp[$settings['cgi_local']] = "checked=\"checked\""; ?>
    <input class="radio" name="cgi_local" type="radio" value="1" <?php echo $temp[1]."/>".bla("set_cgithis"); ?>&nbsp;&nbsp;
    <input class="radio" name="cgi_local" type="radio" value="0" <?php echo $temp[0]."/>".bla("set_cgiext"); ?>
    </td>
    <td class="right">
    <?php echo bla("set_cgilochelp"); ?>
    </td>
</tr>

<tr>
    <td class="subset"><?php echo bla("set_cgiurl"); ?>:</td>
    <td class="center">
    <input name="cgi_url" type="text"
    value="<?php echo $settings['cgi_url']; ?>" />
    </td>
    <td class="right">
    <?php echo bla("set_cgiurlhelp"); ?>
    </td>
</tr>
</table>

<div class="savebutton">
    <input type="submit" value="<?php echo bla("but_saveset"); ?>" />
</div>



</form>