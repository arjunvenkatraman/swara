<?php
function counter() {
   //returns a count of downloads of an audio or video file (works within postings-loop)
  global $postings;
  global $currentid;
  $thetype = $postings[$currentid]['audio_type'];
  $count =  $postings[$currentid]['countall'];
//is it an audio or a video file?  
if (($thetype == "1") OR ($thetype =="2")OR($thetype=="5")OR($thetype=="6")OR($thetype=="9")OR($thetype=="12")OR($thetype=="14"))
 {  $type ="audio ";   }
else
{ if(($thetype=="7")OR($thetype=="10")OR($thetype=="13"))
     { $type = "video ";  }
else
   { $type = "";  } }
//if counter shows one or more downloads, compile text
if ($count > 0)
	{ $return = "This ".$type."file has been downloaded ".$count." times";  }
	else
	{ $return = "";  }
return $return;
   }
?>