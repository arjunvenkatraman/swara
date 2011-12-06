<?php

//i'm here to build static files out of the php powered feeds
//first make the static feed for the "podcast.php" feed
makestaticfeed ("","rss.xml");

//then static feeds for each tag
if ($GLOBALS['settings']['staticfeeds_tags'] == "1")  {
  foreach ($catsdump as $cat) {
        $addstring = "?cat=" . trim(killentities($cat['name']));
        $filename = trim(killentities($cat['name']))."-rss.xml";
	      makestaticfeed ($addstring, $filename);
 }

 }
//copying the contents of the php file and put it into a static text file
   function makestaticfeed($addstring,$filename)  {

            $GetQuery = $GLOBALS['settings']['url']."/podcast.php".$addstring;
            $fp = fopen($GetQuery, 'r');
            $contents = '';
            while (!feof($fp)) {
                  $contents .= fread($fp, 512);
      }

    fclose($fp);
    $fp = fopen($GLOBALS['audiopath'].$filename, 'w');
    fwrite($fp, $contents, strlen($contents) );
    fclose($fp);

}
?>