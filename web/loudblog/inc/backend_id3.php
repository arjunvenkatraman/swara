<?php

include ('inc/functions_record.php');
global $fields;

$edit_id = $_GET['id'];

//update stuff, if requested by url
if ((isset($_GET['do'])) AND ($_GET['do'] == "save")) { 
    echo "<h1>".bla("hl_editid3").saveid3($edit_id)."</h1>";
} else {
    //gets the filename from the posting-id we want to edit
    $dosql = "SELECT title, audio_file, filelocal FROM ".$GLOBALS['prefix']."lb_postings WHERE id = '". $edit_id ."'";
    $result = $GLOBALS['lbdata']->GetArray($dosql);
    $fields = $result[0];
    
    echo "<h1>".bla("hl_editid3")."</h1>";
}



if ($fields['filelocal'] != 1) { 
    echo "<p class=\"msg\">".bla("msg_changeremoteid3")."</p>"; } 
else {



$id3data = getid3data($GLOBALS['audiopath'].$fields['audio_file'],"back");


echo "<form action=\"index.php?page=id3&amp;do=save&amp;id=". $edit_id;
echo "\" accept-charset=\"utf-8\" method=\"post\" enctype=\"multipart/form-data\">";

echo "<table summary=\"ID3 tags of this audio posting\">\n\n";

echo "<tr>\n";

echo "<td class=\"text\">".bla("id3_title").":</td>\n";
echo "<td><input type=\"text\" name=\"id3title\" 
      value=\"".$id3data['title']."\" /></td>\n\n";
      
echo "<td class=\"right text\">".bla("id3_artist").":</td>\n";
echo "<td class=\"right\"><input type=\"text\" name=\"id3artist\" 
      value=\"".$id3data['artist']."\" /></td>\n";

echo "</tr>\n\n";
echo "<tr>\n";

echo "<td class=\"text\">".bla("id3_album").":</td>\n";
echo "<td><input type=\"text\" name=\"id3album\" 
      value=\"".$id3data['album']."\" /></td>\n\n";
      
echo "<td class=\"right text\">".bla("id3_year").":</td>\n";
echo "<td class=\"right\"><input type=\"text\" name=\"id3year\" 
      value=\"".$id3data['year']."\" /></td>\n";

echo "</tr>\n\n";
echo "<tr>\n";

echo "<td class=\"text\">".bla("id3_track").":</td>\n";
echo "<td><input type=\"text\" name=\"id3track\" 
      value=\"".$id3data['track']."\" /></td>\n\n";
      
echo "<td class=\"right text\">".bla("id3_genre").":</td>\n";
echo "<td class=\"right\"><input type=\"text\" name=\"id3genre\" 
      value=\"".$id3data['genre']."\" /></td>\n";

echo "</tr>\n\n";
echo "<tr>\n";
      
echo "<td class=\"text\">".bla("id3_comment").":</td>\n";
echo "<td><textarea name=\"id3comment\">". 
     $id3data['comment']."</textarea></td>\n";
     
//preparing ID3 image
$tempfile = $GLOBALS['audiopath']."temp_image".$id3data['imgtype'];
if ($tempfileconn = @fopen($tempfile, 'wb')) {
    fwrite($tempfileconn, $id3data['image']);
    fclose($tempfileconn);
}
  
echo "<td class=\"right text\">".bla("id3_image").":</td>\n\n<td>\n";
echo "<img src=\"../audio/temp_image".$id3data['imgtype']."\" width=\"100\" height=\"100\" />\n";
echo "<div class=\"help\">".bla("id3_imagehelp")."</div>\n";
echo "<input id=\"imagechooser\" type=\"file\" name=\"image\" accept=\"image/*\" />\n";
echo "</td>";

echo "</tr>\n\n";

echo "</table>\n";
echo "<div id=\"save\"><input type=\"submit\" value=\"".bla("id3_updatebutton")."\" />";
echo "<input onClick=\"window.close();\" type=\"submit\" value=\"".bla("but_close")."\" /></div>";
echo "</form>";

}

/* --------------------------------------- */

function saveid3($update_id) {
global $settings;
global $fields;

//getting the filename from the id
$dosql = "SELECT title, id, filelocal, audio_file FROM ".$GLOBALS['prefix']."lb_postings 
          WHERE id='" . $update_id . "';";
$result = $GLOBALS['lbdata']->GetArray($dosql);
$fields = $result[0];


//Warning if remote file is to be changed :-)
if ($fields['filelocal'] != "1") { 
    echo "<p>".bla("msg_changeremoteid3")."</p>";
} else {

//get old values
$olddata = getid3data($GLOBALS['audiopath'].$fields['audio_file'],"back");

//change posted ID3-data
$filename = $GLOBALS['audiopath'] . $fields['audio_file'];

// Initialize getID3 engine
require_once('inc/id3/getid3.php');
$getID3 = new getID3;
$getID3->encoding = 'UTF-8';   

require_once('inc/id3/write.php');
$tagwriter = new getid3_writetags;
$tagwriter->filename   = $filename;
$tagwriter->tagformats = array('id3v2.3');
$tagwriter->overwrite_tags = true; 
$tagwriter->remove_other_tags = true; 
$tagwriter->tag_encoding = 'UTF-8';

//prepare image
if (isset($_FILES['image']) && $_FILES['image']['size']<>"0") {
    $image = $_FILES['image']['tmp_name']; 
    $mime = $_FILES['image']['type'];
    $fd = @fopen($image, 'rb');
    $APICdata = @fread($fd, filesize($image));
    @fclose ($fd);
} else {
    $APICdata = $olddata['image'];
    $mime = "image/jpeg";
    switch($olddata['imgtype']) {
        case ".gif" : $mime = "image/gif";
        case ".png" : $mime = "image/png"; 
    }
}
    
//populate data array 
$TagData['title'][]  = $_POST['id3title'];
$TagData['artist'][]  = $_POST['id3artist'];
$TagData['album'][]  = $_POST['id3album'];
$TagData['track'][]  = $_POST['id3track'];
$TagData['genre'][]  = $_POST['id3genre'];
$TagData['year'][]  = $_POST['id3year'];
$TagData['comment'][]  = $_POST['id3comment'];
$TagData['attached_picture'][0]['data'] = $APICdata;
$TagData['attached_picture'][0]['picturetypeid'] = '3'; 
$TagData['attached_picture'][0]['description'] = $settings['sitename'];
$TagData['attached_picture'][0]['mime'] = $mime;

$tagwriter->tag_data = $TagData;

// write tags 
if ($tagwriter->WriteTags()) { 
    return " &mdash; ".bla("id3_success");

} else {
	return " &mdash; ".bla("id3_failure");
	    	if (!empty($tagwriter->warnings)) { 
        echo bla("msg_somewarnings").'<br />'.implode('<br><br>', $tagwriter->warnings); }

}
}
}

?>
