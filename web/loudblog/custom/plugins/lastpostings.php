<?php
function lastpostings($content) {
//returns a list of postings starting with the most recent
global $currentid;
global $postings;
global $settings;

//lb:lastpostings can have 5 attributes - number (no of postings to show), 
//cat (category), alpha (to show list in alphabetical order of titles),
//posted (to show posting date/time alongside post title), and 
//author (to show author name)

//so lets get those attributes
$att="";
$att = getattributes($content);

//some initial values
$return = "";
$start = 0; 
$order = "DESC";

//possible attributes and default-values
if (isset($att['number'])) { $loops = $att['number']; } else { $loops = 5; }
if (isset($att['cat'])) {$cat = $att['cat']; }
if (isset($att['alpha'])){$alpha = $att['alpha']; } else {$alpha = "false";}
if (isset($att['posted'])){$posted = $att['posted']; } else {$posted = "false";}
if (isset($att['author'])){$author = $att['author']; } else {$author = "false";}   

//start building the data-base query
    $dosql  = "SELECT * FROM ".$GLOBALS['prefix']."lb_postings WHERE ";    
        
//posting must be "live" to be displayed
    $dosql .= "status = '3' ";
    
//posting must not be published in the future
    $dosql .= "AND posted < '".date("Y-m-d H:i:s")."' ";
    
//if category is set, filter postings which don't fit
    if (isset($att['cat'])) {
    
        //which category-id do we request?
        $tempcatid = getcategoryidshort($cat);    
        if ($tempcatid != "") {
            $dosql .= "AND (category1_id = ". $tempcatid . " ";
            $dosql .= "OR category2_id = ". $tempcatid . " ";
            $dosql .= "OR category3_id = ". $tempcatid . " ";
            $dosql .= "OR category4_id = ". $tempcatid . ") ";
        }
    }
    
//if alpha is "true", order the output alphabetically
	if ($alpha == "true") {
    $dosql .= "ORDER BY title ".'ASC';}
//otherwise order it in descending date order ie most recent first
	else {
    $dosql .= "ORDER BY posted ".$order;}
    
//we get the data and put it in an array       
    $tempp = $GLOBALS['lbdata']->SelectLimit($dosql, $loops, $start);
    $allrows = $tempp->GetArray(); 
        
//make html for post titles as an unstructured list
   $return = "<ul>";
//loop through the array
  foreach ($allrows as $temp)  {	
	$currentid = $temp['id'];
	$postings[$currentid] = $temp;
//each list item starts with the posting name with a link to the posting
	$return .= "<li><a href=\"?id=";
	$return .= $currentid;
	$return .= "\">";
	$return .=$postings[$currentid]['title'];
	$return .="</a>";
//if posted is "true", add the posting date
	if ($posted == "true") {
		$format = $settings['dateformat'];
		$date = date($format, strtotime($postings[$currentid]['posted']));
		$return.= " posted ".$date;}
//if author is "true", add the author name
	if ($author == "true") {
		$name = getnickname($postings[$currentid]['author_id']);
		$return .= ", by ".$name;  }
	$return .="</li>";
	}
//and close the unstructured list
	$return .="</ul>";
    
return $return;
}
?>
