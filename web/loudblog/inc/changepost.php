<?php
//displays id of current post, link to preview of current post and edit links to next and previous posts
// find id of posting with next latest time/date
$dosql = "SELECT  id, title, posted FROM ".$GLOBALS['prefix']." lb_postings
	  WHERE posted = (SELECT MIN(posted) from ".$GLOBALS['prefix']."lb_postings
    WHERE posted > '".$fields['posted']. "')";

$nextresult = $GLOBALS['lbdata']->GetArray($dosql);

//find id of earlier posting
$dosql = "SELECT  id, title, posted FROM ".$GLOBALS['prefix']." lb_postings
          WHERE posted = (SELECT MAX(posted) from ".$GLOBALS['prefix']."lb_postings
                  WHERE posted < '".$fields['posted']. "')";

$previousresult = $GLOBALS['lbdata']->GetArray($dosql);

//find id of latest posting
$dosql = "SELECT  id, title, posted FROM ".$GLOBALS['prefix']." lb_postings
	   WHERE posted = (SELECT MAX(posted) from ".$GLOBALS['prefix']."lb_postings)";

$lastresult = $GLOBALS['lbdata'] -> GetArray($dosql);

//find caller ID
$dosql = "SELECT user FROM ".$GLOBALS['prefix']." lb_postings
           WHERE id = ".$edit_id;

$callerid = $GLOBALS['lbdata'] -> GetArray($dosql);

//display links on screen
echo "<h3>".bla("change_title")."</h3>\n";
  echo bla("change_post_id").$edit_id;
// show caller id
if (!empty($callerid)) {
  echo " | ".bla("change_caller_id").$callerid[0]['user'];
}
//if previews enabled, link to preview post in new tab
	if (!empty($settings['previews'])) {
	echo "  |  <a  href=\"../index.php?preview=1&amp;id=".$edit_id."\" target=\"_blank\" title= \"".bla("change_preview_title")."\">".bla("change_preview")."</a>";
    } 
//edit later post, if there is one
	if(!empty($nextresult))   {
	make_edit_link ($nextresult, bla("change_later")); }
//edit earlier post, if there is one
	if(!empty($previousresult))  {
  make_edit_link ($previousresult, bla("change_earlier"));
 }
//edit latest post
	if ((!empty($lastresult)) AND ($lastresult[0]['id'] != $edit_id))  {
  make_edit_link ($lastresult, bla("change_last"));
  }
//link to comments
  if (countcomments($edit_id) > 0)  {
    echo "  |  <a href=\"index.php?page=comments&amp;posting_id=".$edit_id."\" title=\"".countcomments($edit_id)." comments\">".bla("change_comments")."</a>";
  }


function make_edit_link ($posting, $whichone)  {
echo "  |  <a href = \"?page=record2&amp;do=edit&amp;id=".$posting[0]['id']."\" title = \"".$posting[0]['title']." : id=".$posting[0]['id']." : posted ".$posting[0]['posted']."\">".$whichone."</a>";
}
?>


