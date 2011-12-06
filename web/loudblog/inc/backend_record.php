<?php

echo "<h1>".bla("hl_rec1")."</h1>";

include ('inc/navigation.php'); 

//check url whether we want to update the file of an existing posting
if ((isset($_GET['do'])) AND ($_GET['do'] == "update")) {
    $update = true;
    $update_id = $_GET['id'];
} else {
    $update_id = false;
    $update = false;
}

?>

<!--  NO FILE FOR UPLOADING  -->

<div class="method">
<form method="post" action="index.php?page=record2&amp;do=nofile<?php
if ($update_id) echo "&amp;id=" . $update_id;

echo '" enctype="multipart/form-data">';
echo '<h2>'.bla("rec1_nofile").'</h2>';
echo '<div class="data"><p>'.bla("rec1_filelater").'</p></div>';
echo '<input id="butt_nofile" type="submit" value="'.bla("but_nextstep").'" />';

?>

</form>
</div>

<!--  BROWSER-UPLOAD (PHP OR CGI)  -->

<div class="method">

<?php

//do we use a CGI-script for uploading?
if ((isset($settings['cgi'])) AND ($settings['cgi'] == 1)) {

    //where is the CGI-script located?
    if ((isset($settings['cgi_local'])) AND ($settings['cgi_local'] == 1)) {
        $tempaddress = "modules/cgi-bin/upload.cgi";
    } else { $tempaddress = $settings['cgi_url']."/upload.cgi"; }

    echo "<form method=\"post\" action=\"".$tempaddress."\" enctype=\"multipart/form-data\" onSubmit=\"return saythis('".bla("alert_patience")."')\">\n";
    
    echo "<h2>".bla("rec1_browser")."</h2>\n<div class=\"data\">\n";
    echo "<input id=\"choosefile\" type=\"file\" name=\"file\" size=\"20\" accept=\"audio/*\" />\n";
    
    echo "</div>\n";
    echo "<input type=\"submit\" value=\"get file\" />\n";
    echo "<input type=\"hidden\" name=\"callback_script\" value=\"".$settings['url']."/loudblog/index.php?page=record2&amp;do=cgi";
    
    if ($update) { echo "&amp;id=" . $update_id; }
    
    echo "\" />\n</form>\n";

} else {  //okay, we use the classic php method for uploading!

    echo "<form method=\"post\" action=\"index.php?page=record2&amp;do=browser";
    
    if ($update) { echo "&amp;id=" . $update_id; }
    
    echo "\" enctype=\"multipart/form-data\" onSubmit=\"return saythis('".bla("alert_patience")."')\">\n";
    echo "<h2>".bla("rec1_browser")." <small>(<".getmegabyte(uploadlimit())."MB)</small></h2>\n";
    echo "<div class=\"data\">\n";
    echo "<input button=\"hallo\" id=\"choosefile\" type=\"file\" name=\"fileupload\" size=\"20\" accept=\"audio/*\" />";
    echo "</div>\n<input type=\"submit\" value=\"".bla("but_getfile")."\" />\n</form>";
}

?>

</div>

<?php

if ($settings['ftp'] == 1) {

echo "<!--  UPLOAD VIA FTP  -->";

echo "\n\n<div class=\"method\">\n";
echo "<h2>".bla("rec1_ftp")."</h2>\n";
echo "<div class=\"data\">\n";

//not forget the id, if we are updating a file
if (isset($_GET['id'])) { $idlink = "&id=".$_GET['id']; } else { $idlink = ""; }

//the JAVA FTP method
echo "<a href=\"index.php?page=javaload".$idlink."\" ";
echo "onclick=\"link_popup(this,500,380); return false\" ";
echo "title=\"".bla("rec1_zupload")."\">";
echo bla("rec1_javaftp")."</a>\n | ";

echo "<a href=\"ftp://" . $settings['ftp_user'] . ":";
echo $settings['ftp_pass']. "@" . $settings['ftp_server'];
echo $settings['ftp_path']; 
echo "\" target=\"_blank\" title=\"".bla("rec1_usedefaultftp")."\">";
echo bla("rec1_defaultftp")."</a>\n";

echo "</div>\n</div>\n\n";

}

?>

<!--  SEARCH FTP FOLDER  -->

<div class="method">
<form method="post" action="index.php?page=record2&amp;do=transfer<?php
if ($update) echo "&amp;id=" . $update_id;
echo "\" enctype=\"multipart/form-data\">\n\n";
echo "<h2>".bla("rec1_searchfolder")."</h2>\n\n";

echo "<div class=\"data\">";
echo "<select class=\"datainput\" name=\"filename\">";
echo "<option value=\"\">".bla("but_choosefile")."</option>";
echo "<option value=\"\">--------</option>";

//gets the filenames of all the files in the upload-folder. make a list.
$uploadfolder = opendir('../upload'); 

while ($file = readdir($uploadfolder)) { 
    if ( substr($file, 0, 1) != ".") { 
        $choosefile = $file; 
        echo "<option value=\"" . urlencode($choosefile) . "\">";
        echo $choosefile . "</option>";
    }
}
closedir($uploadfolder);
?>
</select>
</div>
<input type="submit" value="<?php echo bla("but_getfile"); ?>" />
</form>
</div>

<!--  WEB TRANSFER  -->

<div class="method">
<form method="post" action="index.php?page=record2&amp;do=web<?php
if ($update) echo "&amp;id=".$update_id."";
echo "\" enctype=\"multipart/form-data\">\n\n";
echo "<h2>".bla("rec1_getweb")."</h2>\n\n";
echo "<div class=\"data\">\n";
echo "<input onfocus=\"this.value='';\" class=\"datainput\" type=\"text\" name=\"linkurl\" value=\"".bla("rec1_urlfield")."\" />\n<br />\n";
echo "<div class=\"bottomspace\"></div>\n";
echo "<input type=\"radio\" name=\"method\" value=\"link\" checked=\"checked\" />".bla("rec1_linkfile")."&nbsp;&nbsp;";

if (ini_get('allow_url_fopen')) {
    echo "<input type=\"radio\" name=\"method\" value=\"copy\" />".bla("rec1_copyfile");
}
?>
</div>
<input type="submit" value="<?php echo bla("but_getfile"); ?>" />
</form>
</div>


