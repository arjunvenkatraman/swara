<?php

function player()    {
   //puts the 1 Pixel Out flash player OR quicktime player on screen (works within postings-loop)
   global $postings;
   global $currentid;
   global $settings;   
   $audioexamine = "";
   $return = "";
   
   //decide: flash or quicktime
   $thetype = $postings[$currentid]['audio_type'];
   
   //redirect or direct link? Only Flash can be redirected for counting
   if (($thetype == "1") AND ($settings['countfla'] == "1")) {
       $before = "get.php?fla=";
   } else {
       if ($postings[$currentid]['filelocal'] == "1") {
           $before = "audio/";
           $audioexamine = $GLOBALS['audiopath'].$postings[$currentid]['audio_file'];
       } else {
           $before = "";
       }
   }
   
   if (!veryempty($postings[$currentid]['audio_file'])) {
   
   //possible attributes and default-values
   if (isset($att['width']))  { $width  = $att['width']; }  else { $width  = 200; }
   if (isset($att['height'])) { $height = $att['height']; } else { $height = 62; }
   if ($postings[$currentid]['filelocal'] == 1) {
       $audio = $before.$postings[$currentid]['audio_file']; 
   } else { 
       $audio = $postings[$currentid]['audio_file']; 
       if ($settings['countfla'] == "1") {
           $audio = $before.$audio;
       }
   }
	//if mp3 
	if ($thetype == "1") {    
	$pre = "0x";

	//....................this is the bit where you can change the colours.............
	//..............alter the hexadecimal colour values only (eg FFFFFF)...............
	//..............note that the hexadecimal values do not have a # at the front ..... 
	//...take care not to alter anything else (eg the inverted commas or the semicolons)
	

	$bg = $pre."FFFFFF";  //background
	$leftbg = $pre."e4e5d4";  //left background
	$lefticon = $pre."809ab1";   //left icon
	$rightbg = $pre."49647d";   //right background
	$rightbghover = $pre."191970";  //right background (hover)
	$righticon = $pre."e4e5d4";  //right icon
	$righticonhover = $pre."809ab1";   //right icon (hover)
	$text = $pre."666666";  //text
	$slider = $pre."191970";  //slider
	$track = $pre."6495ED";   //progress track
	$border = $pre."666666";   //progress track border
	$loader = $pre."e4e5d4";  //loader bar
	$bgcolor = "#"."FFFFFF";   //page background

	//....................don't change anything below this line............

	//where can we find the audio file and the player
	$soundFile = $settings['url']."/".$audio;
	$playerlocation = $settings['url']."/loudblog/custom/templates/".$settings['template']."/player.swf";
	
	//build the string containing the parameters to be passed to the audio player
	$paramstring = "playerID=1&bg=".$bg."&leftbg=".$leftbg."&lefticon=".$lefticon."&rightbg=".$rightbg."&rightbghover=".$rightbghover."&righticon=".$righticon."&righticonhover=".$righticonhover."&text=".$text."&slider=".$slider."&track=".$track."&border=".$border."&loader=".$loader."&soundFile=".$soundFile;
	//and convert special characters to html entities
	$flashvalue = htmlspecialchars($paramstring);
	//build up the code for displaying the player line by line
	$firstline = "<object type=\"application/x-shockwave-flash\" data=\"".$playerlocation."\" width=\"290\" height=\"24\" id=\"audioplayer1\">";
	$secondline = "<param name=\"movie\" value=\"".$playerlocation."\" />";
	$thirdline = "<param name=\"FlashVars\" value=\"".$flashvalue."\" />";
	$fourthline = "<param name=\"quality\" value=\"high\" />";
	$fifthline = "<param name=\"menu\" value=\"false\" />";
	$sixthline = "<param name=\"wmode\" value=\"transparent\" /></object>";
	
	//and stick them together
	$return = $firstline.$secondline.$thirdline.$fourthline.$fifthline.$sixthline;
	}

	   
   //if quicktime audio
   if (($thetype == "2") OR ($thetype == "5") OR ($thetype == "6") OR ($thetype == "9") OR ($thetype == "12") OR ($thetype == "14")) {
   
       //making an absolute link
       if ($postings[$currentid]['filelocal'] == "1") {
           $audio = $settings['url'] . "/" . $audio;
       }
       
       //Make width and height for showing Enhanced Podcast!
       if ($thetype == "14") {
           $fileinfo = getid3data($audioexamine,"front");
           if ($fileinfo['width'] != "") {
               $width = $fileinfo['width'];
               $height = $fileinfo['height'] + 16;
               $href = "loudblog/backend/clicktoplayvideo.mov";
           } else {
               $height = "16";
               $href = "loudblog/backend/clicktoplayaudio.mov";
           }
       } else {
           $height = "16";
           $href = "loudblog/backend/clicktoplayaudio.mov";
       }
   
      //build html-code for quicktime plugin (audio)
      $return .= showquicktime($href, $audio, $width, $height, "myself", "false");
   }
   
   
   
   //if quicktime video
   if (($thetype == "7") OR ($thetype == "10") OR ($thetype == "13")) {
   
       //making an absolute link
       if ($postings[$currentid]['filelocal'] == "1") {
           $audio = $settings['url'] . "/" . $audio;
           $target = "myself";
       } else {
           $target = "quicktimeplayer";
       }
   
       //getting width and height from getid3()
       $fileinfo = getid3data($audioexamine,"front");
   
       //build html-code for quicktime plugin (video)
       $return .= showquicktime("loudblog/backend/clicktoplayvideo.mov", 
                       $audio, $fileinfo['width'], ($fileinfo['height']+16), $target, "false");
   }
   
   
   return $return;
   
   } else { return ""; }
   
   }
	?>