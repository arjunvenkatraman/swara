<?php


// ----------------------------------------------------------------


function upload_browser($update_id) {

global $settings;
deleteupdatedfile ($update_id);

//checks the uploaded file
if ($_FILES['fileupload']['error'] != "0") { die("<p class=\"msg\">".bla("msg_uploadbroken")."</p>"); }

//do we rename the audio file?
if ($settings['rename'] == 1) { 

    //generate filename from posting date, if we update the file
    if ($update_id) {
        $dosql= "SELECT posted FROM ".$GLOBALS['prefix']."lb_postings WHERE id = '". $update_id ."'";
        $result = $GLOBALS['lbdata']->GetArray($dosql);
        $newfilename = buildaudioname(strrchr($_FILES['fileupload']['name'], "."), $settings['filename'], $result[0]['posted']);
        
    //get a fresh filename if we have a new posting
    } else {
        $newfilename = freshaudioname(strrchr($_FILES['fileupload']['name'], "."), $settings['filename']); 
    }

//if we don't rename, we will at least tune the filename
} else { 
    $newfilename = tunefilename($_FILES['fileupload']['name']); 
}

$newfilepath = $GLOBALS['audiopath'] . $newfilename; 

//put the uploaded file into the desired directory
move_uploaded_file($_FILES['fileupload']['tmp_name'], $newfilepath) 
OR die ("<p class=\"msg\">".bla("msg_uploadbroken")."</p>");
//change the chmod
chmod ($newfilepath, 0777);

//make a valid temp-title
$temptitle = stripsuffix(htmlspecialchars($_FILES['fileupload']['name'], ENT_QUOTES));

//big question: are we just updating or creating a new file? 
if (!$update_id) {

//insert a new row to the database and fill it with some nice data
$dosql = "INSERT INTO {$GLOBALS['prefix']}lb_postings
         (author_id, title, posted, filelocal,  
         audio_file, audio_type, audio_size, status, 
         countweb, countfla, countpod, countall)
         VALUES
         (
         '{$_SESSION['authorid']}',
         '$temptitle',
         '" . date('Y-m-d H:i:s') . "',
         '1',
         '$newfilename',
         '" . type_suffix ($newfilepath) . "',
         '" . $_FILES['fileupload']['size'] . "',
         '1','0','0','0','0'
         )";
$GLOBALS['lbdata']->Execute($dosql);

//add default id3 tags, if needed
defaultid3tags(($GLOBALS['audiopath'].$newfilename), $temptitle);

echo "<p class=\"msg\">".bla("msg_uploadsuccess")."</p>";

} else {

//update an existing row in the database
$dosql = "UPDATE {$GLOBALS['prefix']}lb_postings SET

         author_id = '{$_SESSION['authorid']}',
         filelocal = '1',  
         audio_file= '$newfilename',
         audio_type= '" . type_suffix ($newfilepath) . "',
         audio_length= '',
         audio_size= '{$_FILES['fileupload']['size']}' 
         
         WHERE id = '$update_id'";
$GLOBALS['lbdata']->Execute($dosql);

//add default id3 tags, if needed
defaultid3tags($GLOBALS['audiopath'].$newfilename, gettitlefromid($update_id));

echo "<p class=\"msg\">".bla("msg_uploadsuccess")."</p>";

}

//get id for editing data after finishing this function
$dosql = "SELECT id FROM {$GLOBALS['prefix']}lb_postings WHERE audio_file='". $newfilename ."'";
$row = $GLOBALS['lbdata']->GetArray($dosql);

return $row[0]['id'];
}


// ----------------------------------------------------------------

function cgi_copy($update_id) {
//This function is heavily based on Christian Rotzoll's CGI Upload-Relais. Thanks for the kind support and those great video tutorials!

global $settings;

//where is the CGI-script located?
if ((isset($settings['cgi_local'])) AND ($settings['cgi_local'] == 1)) {
    $downloadscript = $GLOBALS['path']."/loudblog/modules/cgi-bin/download.cgi";
} else { $downloadscript = $settings['cgi_url']."/download.cgi"; }

//getting upload-id from cgi-script

if (isset($_GET['uploadid'])) {

     $uploadid = htmlspecialchars($_GET['uploadid']);

} else { die (bla("msg_uploadbroken")); }

//check if file with this id exists on server
if ($wp = fopen($downloadscript."?request=vorhanden&id=".$uploadid,"r")) {

     $upload_existent = fread($wp,2048);

     fclose($wp);

     $upload_existent = trim($upload_existent);

} else { 
    die(bla("msg_uploadsuccess")."$downloadscript?request=vorhanden&id=$uploadid".") cannot be executed.");
}

//get filename from cgi-script

if ($wp = fopen("$downloadscript?request=filename&id=$uploadid","r")) {

    $oldfilename = fread($wp,2048);

    fclose($wp);

    $oldfilename = trim($oldfilename);

} else { 
    die(bla("msg_uploadbroken")." $downloadscript?request=vorhanden&id=$uploadid".") ".bla("msg_noexecute"));
}
    

//do we rename the audio file?
if ($settings['rename'] == 1) { 

    //generate filename from posting date, if we update the file
    if ($update_id) {
        $dosql= "SELECT posted FROM ".$GLOBALS['prefix']."lb_postings WHERE id = '". $update_id ."'";
        $result = $GLOBALS['lbdata']->GetArray($dosql);
        $newfilename = buildaudioname(strrchr($oldfilename, "."), $settings['filename'], $result[0]['posted']);
        
    //get a fresh filename if we have a new posting
    } else {
        $newfilename = freshaudioname(strrchr($oldfilename, "."), $settings['filename']); 
    }

//if we don't rename, we will at least tune the filename
} else { 
    $newfilename = tunefilename($oldfilename);
}

$newfilepath = $GLOBALS['audiopath'] . $newfilename; 

//copy the file from cgi to ... whatever
$sourcefile = fopen ("$downloadscript?request=filedata&id=$uploadid", "rb") 
    OR die("<p class=\"msg\">".bla("msg_nourl")."</p>");

$destfile = fopen ($newfilepath, "wb");
$eof = false;
$filesize = 0;

//copies the file in fragments of 1024 bytes
do {

    $file = fread ($sourcefile, 1024) OR $eof = true;
    $filesize = $filesize + 1024;

    fwrite ($destfile, $file) OR fclose($destfile);
} while ($eof==false);
fclose($sourcefile);

//change the chmod
chmod ($newfilepath, 0777);

//make a valid temp-title
$temptitle = stripsuffix(htmlspecialchars($oldfilename, ENT_QUOTES));

//big question: are we just updating or creating a new file? 
if (!$update_id) {

//insert a new row to the database and fill it with some nice data
$dosql = "INSERT INTO {$GLOBALS['prefix']}lb_postings
         (author_id, title, posted, filelocal, 
         audio_file, audio_type, audio_size, status, 
         countweb, countfla, countpod, countall)
         VALUES
         (
         '{$_SESSION['authorid']}',
         '$temptitle',
         '" . date('Y-m-d H:i:s') . "',
         '1',
         '$newfilename',
         '" . type_suffix($newfilename) . "',
         '$filesize',
         '1','0','0','0','0'
         )";
$GLOBALS['lbdata']->Execute($dosql);

//add default id3 tags, if needed
defaultid3tags(($GLOBALS['audiopath'].$newfilename), $temptitle);

//if the parser gets until here, all should be good
echo "<p class=\"msg\">".$oldfilename." - ".bla("msg_uploadsuccess")."</p>";

} else {

//update an existing row in the database
$dosql = "UPDATE {$GLOBALS['prefix']}lb_postings SET

         author_id = '{$_SESSION['authorid']}',
         filelocal = '1',  
         audio_file= '$newfilename',
         audio_type= '" . type_suffix($newfilename) . "',
         audio_length= '',
         audio_size= '$filesize'  
         
         WHERE id = '$update_id'";
$GLOBALS['lbdata']->Execute($dosql);

//add default id3 tags, if needed
defaultid3tags(($GLOBALS['audiopath'].$newfilename), gettitlefromid($update_id));

}

//get id for editing data after finishing this function
$dosql = "SELECT id FROM {$GLOBALS['prefix']}lb_postings 
          WHERE audio_file='$newfilename';";
$row = $GLOBALS['lbdata']->GetArray($dosql);

return $row[0]['id'];
}


// ----------------------------------------------------------------

function link_web($update_id) {

global $settings;
deleteupdatedfile ($update_id);

$filetype = type_suffix($_POST['linkurl']);
$filename = stripsuffix(extractfilename($_POST['linkurl']));
$fileatts = remote_fileatts($_POST['linkurl']);


//big question: are we just updating or creating a new file? 
if (!$update_id) {

//insert a new row to the database and fill it with some nice data
$dosql = "INSERT INTO {$GLOBALS['prefix']}lb_postings
         (author_id, title, posted, filelocal, audio_size, audio_length, 
         audio_type, audio_file, status, 
         countweb, countfla, countpod, countall)
         VALUES
         (
         '{$_SESSION['authorid']}',
         '".$filename."',
         '" . date('Y-m-d H:i:s') . "', '0',
         '".$fileatts['size']."', '".$fileatts['length']."',
         '$filetype',
         '".urldecode($_POST['linkurl'])."',
         '1','0','0','0','0'
         )";
$GLOBALS['lbdata']->Execute($dosql);

//if the parser gets until here, all should be good
echo "<p class=\"msg\">".bla("msg_linksuccess")."</p>";

} else {

//update an existing row in the database
$dosql = "UPDATE {$GLOBALS['prefix']}lb_postings SET

         author_id = '{$_SESSION['authorid']}',
         filelocal = '0',  
         audio_file = '" . urldecode($_POST['linkurl']) . "',
         audio_size = '".$fileatts['size']."',
         audio_length = '".$fileatts['length']."',
         audio_type = '" . $filetype . "' 
         
         WHERE id = '" . $update_id . "'";
$GLOBALS['lbdata']->Execute($dosql);

}


//get id for editing data after finishing this function
$dosql = "SELECT id FROM {$GLOBALS['prefix']}lb_postings 
          WHERE audio_file = '". urldecode($_POST['linkurl']) ."'";
$row = $GLOBALS['lbdata']->GetArray($dosql);

return $row[0]['id'];
}

// ----------------------------------------------------------------

function fetch_web($update_id) {

global $settings;
deleteupdatedfile ($update_id);


//do we rename the audio file?
if ($settings['rename'] == 1) { 

    //generate filename from posting date, if we update the file
    if ($update_id) {
        $dosql= "SELECT posted FROM ".$GLOBALS['prefix']."lb_postings WHERE id = '". $update_id ."'";
        $result = $GLOBALS['lbdata']->GetArray($dosql);
        $newfilename = buildaudioname(strrchr($_POST['linkurl'], "."), $settings['filename'], $result[0]['posted']);
        
    //get a fresh filename if we have a new posting
    } else {
        $newfilename = freshaudioname(strrchr($_POST['linkurl'], "."), $settings['filename']); 
    }

//if we don't rename, we will at least tune the filename
} else { 
    $newfilename = extractfilename($_POST['linkurl'], true);
    $newfilename = tunefilename($newfilename);
}

$newfilepath = $GLOBALS['audiopath'] . $newfilename; 

//copy the file from the url to ... whatever
$sourcefile = fopen ($_POST['linkurl'], "rb") 
    OR die("<p class=\"msg\">".bla("msg_nourl")."</p>");

$destfile = fopen ($newfilepath, "wb");
$eof = false;
$filesize = 0;

//copies the file in fragments of 1024 bytes
do {

$file = fread ($sourcefile, 1024) OR $eof = true;
$filesize = $filesize + 1024;

fwrite ($destfile, $file) OR fclose($destfile);
} while ($eof==false);
fclose($sourcefile);

//change the chmod
chmod ($newfilepath, 0777);

//make a valid temp-title
$temptitle = stripsuffix(htmlspecialchars(extractfilename($_POST['linkurl'], false), ENT_QUOTES));

//big question: are we just updating or creating a new file? 
if (!$update_id) {

//insert a new row to the database and fill it with some nice data
$dosql = "INSERT INTO {$GLOBALS['prefix']}lb_postings
         (author_id, title, posted, filelocal, 
         audio_file, audio_type, audio_size, status, 
         countweb, countfla, countpod, countall)
         VALUES
         (
         '{$_SESSION['authorid']}',
         '$temptitle',
         '" . date('Y-m-d H:i:s') . "',
         '1',
         '$newfilename',
         '" . type_suffix($newfilename) . "',
         '$filesize',
         '1','0','0','0','0'
         )";
$GLOBALS['lbdata']->Execute($dosql);

//add default id3 tags, if needed
defaultid3tags(($GLOBALS['audiopath'].$newfilename), $temptitle);

//if the parser gets until here, all should be good
echo "<p class=\"msg\">".bla("msg_copysuccess")."</p>";

} else {

//update an existing row in the database
$dosql = "UPDATE {$GLOBALS['prefix']}lb_postings SET

         author_id = '{$_SESSION['authorid']}',
         filelocal = '1',  
         audio_file= '$newfilename',
         audio_type= '" . type_suffix($newfilename) . "',
         audio_length= '',
         audio_size= '$filesize'  
         
         WHERE id = '$update_id'";
$GLOBALS['lbdata']->Execute($dosql);

//add default id3 tags, if needed
defaultid3tags(($GLOBALS['audiopath'].$newfilename), gettitlefromid($update_id));

}

//get id for editing data after finishing this function
$dosql = "SELECT id FROM {$GLOBALS['prefix']}lb_postings 
          WHERE audio_file='$newfilename'";
$row = $GLOBALS['lbdata']->GetArray($dosql);

return $row[0]['id'];
}

// ----------------------------------------------------------------

function copy_ftp ($update_id) {

global $settings;
deleteupdatedfile ($update_id);

//no file in upload-folder? error!
if ($_POST['filename'] == "") { die("<p class=\"msg\">".bla("msg_noaudio")."</p>"); }
$oldfilename = $_POST['filename'];


//do we rename the audio file?
if ($settings['rename'] == 1) { 

    //generate filename from posting date, if we update the file
    if ($update_id) {
        $dosql= "SELECT posted FROM ".$GLOBALS['prefix']."lb_postings WHERE id = '". $update_id ."'";
        $result = $GLOBALS['lbdata']->GetArray($dosql);
        $newfilename = buildaudioname(strrchr($_POST['filename'], "."), $settings['filename'], $result[0]['posted']);
        
    //get a fresh filename if we have a new posting
    } else {
        $newfilename = freshaudioname(strrchr($_POST['filename'], "."), $settings['filename']); 
    }

//if we don't rename, we will at least tune the filename
} else { 
    $newfilename = tunefilename(urldecode($oldfilename));
}

//copy the file and delete the old one
$oldpath = $GLOBALS['uploadpath'] . urldecode($oldfilename);
$newfilepath = $GLOBALS['audiopath'] . $newfilename; 
copy ($oldpath, $newfilepath);
unlink ($oldpath);

//change the chmod
chmod ($newfilepath, 0777);

$filesize = filesize ($newfilepath);

//make a valid temp-title
$temptitle = stripsuffix(htmlspecialchars(urldecode($oldfilename), ENT_QUOTES));

//big question: are we just updating or creating a new file? 
if (!$update_id) {

//insert a new row to the database and fill it with some nice data
$dosql = "INSERT INTO {$GLOBALS['prefix']}lb_postings
         (author_id, title, posted, 
         filelocal, audio_file, audio_type, audio_size, status, 
         countweb, countfla, countpod, countall)
         VALUES
         ('{$_SESSION['authorid']}', '$temptitle', '".date('Y-m-d H:i:s')."',
        '1', '$newfilename', '" . type_suffix($newfilename) . "', 
        '$filesize', '1','0','0','0','0')";
$GLOBALS['lbdata']->Execute($dosql);

//add default id3 tags, if needed
defaultid3tags(($GLOBALS['audiopath'].$newfilename), $temptitle);

//if the parser gets until here, all should be good
echo "<p class=\"msg\">".$newfilename." - ".bla("msg_copysuccess")."</p>";

} else {

//update an existing row in the database
$dosql = "UPDATE {$GLOBALS['prefix']}lb_postings SET

         author_id = '{$_SESSION['authorid']}',
         filelocal = '1',  
         audio_file= '$newfilename',
         audio_type= '" . type_suffix($newfilename) . "',
         audio_length= '',
         audio_size= '$filesize'  
         WHERE id = '$update_id'";
$GLOBALS['lbdata']->Execute($dosql);

//add default id3 tags, if needed
defaultid3tags(($GLOBALS['audiopath'].$newfilename), gettitlefromid($update_id));

}

//get id for editing data after finishing this function
$dosql = "SELECT id FROM {$GLOBALS['prefix']}lb_postings 
          WHERE audio_file='$newfilename'";
$result = $GLOBALS['lbdata']->GetArray($dosql);

return $result[0]['id'];

}

// ----------------------------------------------------------------

function nofile($update_id) {

global $settings;
deleteupdatedfile ($update_id);

$tempdate = date('Y-m-d H:i:s');

//big question: are we just updating or creating a new file? 
if (!$update_id) {

//insert a new row to the database and fill it with some nice data
$dosql = "INSERT INTO {$GLOBALS['prefix']}lb_postings
         (author_id, title, posted, filelocal, status, 
         countweb, countfla, countpod, countall)
         VALUES
         (
         '{$_SESSION['authorid']}',
         'New Posting',
         '" . date('Y-m-d H:i:s') . "',
         '0',
         '1','0','0','0','0'
         )";
$GLOBALS['lbdata']->Execute($dosql);

//if the parser gets until here, all should be good
echo "<p class=\"msg\">".bla("msg_plainsuccess")."</p>";

} else {

//update an existing row in the database
$dosql = "UPDATE {$GLOBALS['prefix']}lb_postings SET

         author_id = '{$_SESSION['authorid']}',
         filelocal = '0',  
         audio_file= '',
         audio_size= '',
         audio_length= '',
         audio_type= '0' 
         
         WHERE id = '$update_id'";
$GLOBALS['lbdata']->Execute($dosql);

}


//get id for editing data after finishing this function
$dosql = "SELECT id FROM {$GLOBALS['prefix']}lb_postings 
          WHERE posted='". $tempdate ."'";
$row = $GLOBALS['lbdata']->GetArray($dosql);
return $row[0]['id'];
}

// ----------------------------------------------------------------

function deleteupdatedfile ($id) {
//deletes the old file when it is to be updated

global $settings;
if ($id != false) {
$dosql = "SELECT audio_file, filelocal FROM {$GLOBALS['prefix']}lb_postings 
          WHERE id = '".$id."'";
$row = $GLOBALS['lbdata']->GetArray($dosql);

$filepath = $GLOBALS['audiopath'] . $row[0]['audio_file'];
if ($row[0]['filelocal'] == "1") unlink($filepath);
}
}



// ----------------------------------------------------------------

function defaultid3tags($filename, $title) {
//takes the uploaded file and changes id3 tagging

global $settings;

if ($settings['id3_overwrite'] == "1") {

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
    $image = $GLOBALS['audiopath']."itunescover.jpg"; 
    $fd = @fopen($image, 'rb');
    $APICdata = @fread($fd, filesize($image));
    @fclose ($fd);

    //write data
    $TagData['title'][]  = $title;
    $TagData['artist'][]  = $settings['id3_artist'];
    $TagData['album'][]  = $settings['id3_album'];
    $TagData['genre'][]  = $settings['id3_genre'];
    $TagData['year'][]  = date('Y');
    $TagData['comment'][]  = $settings['id3_comment'];
    $TagData['attached_picture'][0]['data'] = $APICdata;
    $TagData['attached_picture'][0]['picturetypeid'] = '3'; 
    $TagData['attached_picture'][0]['description'] = $settings['sitename'];
    $TagData['attached_picture'][0]['mime'] = 'image/jpeg';
    
    $tagwriter->tag_data = $TagData;
    if ($tagwriter->WriteTags()) {
    } else {
    echo "<p class=\"msg\">Error writing Tags</p>";
    }
}

}



?>