<?php
echo "<h1>".bla("hl_stats")."</h1>\n";

include ('inc/navigation.php');
include ('inc/functions_organisation.php');

//check the rights
if (!allowed(3,"")) 
{ die("<p class=\"msg\">".bla("msg_adminonly")."</p>"); }


//adding "post" data to "get" data
if (isset($_POST['show'])) {
    $_GET['show'] = $_POST['show'];
} else { $_GET['show'] = "tenpost"; }

//sorting-variables
if (isset($_GET['sort'])) { 
    $sortby = substr($_GET['sort'],1);
    $sortdir = substr($_GET['sort'],0,1);
    if ($sortdir == "0") { $order = "ASC"; } else { $order = "DESC"; }
} else { 
    $_GET['sort'] = "1posted"; 
    $sortby = "posted"; 
    $order= "DESC"; 
    $sortdir = 1;
}

//default values for new url-requests
$dirpost = "0"; $dirtitle = "0"; $dirweb = "1"; $dirfla = "1"; $dirpod = "1"; $dirall = "1";

//a click on the active sorting order link changes the direction
if ($sortby == "posted") { $dirpost = changedir($sortdir); }
if ($sortby == "title") { $dirtitle = changedir($sortdir); }
if ($sortby == "countweb") { $dirweb = changedir($sortdir); }
if ($sortby == "countfla") { $dirfla = changedir($sortdir); }
if ($sortby == "countpod") { $dirpod = changedir($sortdir); }
if ($sortby == "countall") { $dirall = changedir($sortdir); }

//make 0 to 1 and vice versa
function changedir($x) {
if ($x == "0") { return "1"; }
if ($x == "1") { return "0"; }
}

//build the list for choosing time period
$showsel = array
    ("tenpost"=>"","oneweek"=>"","onemonth"=>"","threemonth"=>"","oneyear"=>"","allpost"=>"");
if (isset($_GET['show'])) {
    foreach ($showsel as $show => $value) {
        if ($_GET['show'] == $show) {
            $showsel[$show] = "selected=\"selected\"";
        }
    }
}    
echo "<form name=\"showform\" action=\"index.php".addToUrl("page","stats")."\" method=\"post\" enctype=\"multipart/form-data\">\n";
echo "  <select id=\"chooser\" onChange=\"document.showform.submit()\" name=\"show\">\n";
echo "      <option ".$showsel['tenpost']." value=\"tenpost\">".bla("sta_tenpost")."</option>\n";
echo "      <option ".$showsel['oneweek']." value=\"oneweek\">".bla("sta_oneweek")."</option>\n";
echo "      <option ".$showsel['onemonth']." value=\"onemonth\">".bla("sta_onemonth")."</option>\n";
echo "      <option ".$showsel['threemonth']." value=\"threemonth\">".bla("sta_threemonth")."</option>\n";
echo "      <option ".$showsel['oneyear']." value=\"oneyear\">".bla("sta_oneyear")."</option>\n";
echo "      <option ".$showsel['allpost']." value=\"allpost\">".bla("sta_allpost")."</option>\n";
echo "  </select>\n";
echo "</form>\n\n";

//some calculations for the query
if (isset($_GET['show'])) {
    switch ($_GET['show']) {
        case "tenpost" : 
            $showlimit = 10; 
            $showdate = "1900-01-01 00:00:00"; 
            break;
        case "oneweek" : 
            $showlimit = 99999; 
            $showperiod = 604800;
            $showdate = date("Y-m-d H:i:s", time()-$showperiod);
            break;
        case "onemonth" : 
            $showlimit = 99999;
            $showperiod = 2678400; 
            $showdate = date("Y-m-d H:i:s", time()-$showperiod); 
            break;
        case "threemonth" : 
            $showlimit = 99999; 
            $showperiod = 7776000; 
            $showdate = date("Y-m-d H:i:s", time()-$showperiod); 
            break;
        case "oneyear" : 
            $showlimit = 99999; 
            $showperiod = 31536000;
            $showdate = date("Y-m-d H:i:s", time()-$showperiod); 
            break;
        case "allpost" : 
            $showlimit = 99999; 
            $showdate = "1900-01-01 00:00:00"; 
            break;
    }
} else {
    $showlimit = 10; 
    $showdate = "1900-01-01 00:00:00";
}

//getting all sql-data needed for the table
$dosql = "SELECT id, posted, title, countweb, countfla, countpod, countall 
          FROM ".$GLOBALS['prefix']."lb_postings 
          WHERE posted > '".$showdate."' ORDER BY ".$sortby." ".$order;
$result = $GLOBALS['lbdata']->SelectLimit($dosql,$showlimit,0);
$showtable = $result->GetArray();

//pink print for active sorting type
$pink = array("posted"=>"","title"=>"","countweb"=>"","countfla"=>"","countpod"=>"","countall"=>"");
foreach ($pink as $key => $value) {
    if ($sortby == $key) {
        $pink[$key] = " class=\"pink\"";
    }
}

//table which should be coded way more beautiful
echo "<table>\n<tr>\n";

echo "  <th><a" . $pink['posted'] . " href=\"index.php";
echo addToUrl("sort",$dirpost . "posted")."\">";
echo "<span>".bla("sta_date")."</span></a></th>\n";

echo "  <th><a" . $pink['title']." href=\"index.php";
echo addToUrl("sort",$dirtitle . "title") . "\">";
echo bla("sta_title")."</a></th>\n";

echo "  <th class=\"number\"><a" . $pink['countweb'] . " href=\"index.php";
echo addToUrl("sort",$dirweb . "countweb") . "\">";
echo bla("sta_web")."</a></th>\n";

echo "  <th class=\"number\"><a" . $pink['countfla'] . " href=\"index.php";
echo addToUrl("sort",$dirfla . "countfla") . "\">";
echo bla("sta_fla") . "</a></th>\n";

echo "  <th class=\"number\"><a" . $pink['countpod'] . " href=\"index.php";
echo addToUrl("sort",$dirpod . "countpod") . "\">";
echo bla("sta_pod")."</a></th>\n";

echo "  <th class=\"number\"><a" . $pink['countall'] . " href=\"index.php";
echo addToUrl("sort",$dirall . "countall") . "\">";
echo bla("sta_all")."</a></th>\n";

echo "</tr>\n\n";

//showing numbers in a table
foreach ($showtable as $value) {
    echo "<tr>\n";
    
    echo "  <td class=\"date\">";
    echo date($settings['dateformat'], strtotime($value['posted']))."</td>\n";
    
    echo "  <td><a href=\"index.php?page=record2&amp;do=edit&amp;";
    echo "id=" . $value['id'] . "\">".$value['title']."</a></td>\n";
    
    echo "  <td class=\"number\">".$value['countweb']."</td>\n";
    echo "  <td class=\"number\">".$value['countfla']."</td>\n";
    echo "  <td class=\"number\">".$value['countpod']."</td>\n";
    echo "  <td class=\"number\">".$value['countall']."</td>\n";
    
    echo "</tr>\n\n";
}

//calculating data for figure
$figwidth = 710;
$figheight= 156;

//On 10 postings we have a flexible time period to show
if (($_GET['show'] == "tenpost") OR ($_GET['show'] == "allpost")) {
    $endtime = "1900-01-01 00:00:00";
    $starttime = "2500-12-31 23:59:59";
    foreach ($showtable as $value) {
        if ($value['posted'] < $starttime) { $starttime = $value['posted']; }
        if ($value['posted'] > $endtime)   { $endtime   = $value['posted']; }
    }
    $showperiod = strtotime($endtime) - strtotime($starttime);
    if ($showperiod == 0) $showperiod = 1;

//On other conditions the time period is given by request
} else {
    $starttime = $showdate;
    $endtime = date("Y-m-d H:i:s");
}

//getting highest counting value
$maxcount = 1;
foreach ($showtable as $value) {
    if ($value['countall'] > $maxcount){ $maxcount  = $value['countall']; }
}

//making a beautiful figure
echo "<div id=\"figure\">\n";
echo '<div id="legend">'.bla("sta_webloads").' | <span id="brown">'.bla("sta_webplays").'</span> | <span id="grey">'.bla("sta_feedloads").'</span></div>';

//get the position values
$factorx = $figwidth / $showperiod;
$factory = $figheight / $maxcount;
foreach ($showtable as $row) {
    $secs = strtotime($row['posted']) - strtotime($starttime);
    $x = round($secs * $factorx) + 10;   
    $hweb = round($row['countweb'] * $factory);
    $hfla = round($row['countfla'] * $factory);
    $hpod = round($row['countpod'] * $factory);
    $h = $hweb + $hfla + $hpod + 2;
    $yfla = $hweb + 1;
    $ypod = $hweb + $hfla + 2;
    $cdate = date('M\<\b\r \/\>d',strtotime($row['posted']));
    $ctitle= $row['title'];
    
    //show how to make a cool layout using only CSS
    echo "  <div class=\"column\" style=\"left: ". $x . "px; height: ".$h."px;\">\n";
    echo "      <div class=\"cweb\" style=\"height: ".$hweb."px;\"></div>\n";
    echo "      <div class=\"cfla\" style=\"height: ";
    echo $hfla."px; bottom: ".$yfla."px;\"></div>\n";
    echo "      <div class=\"cpod\" style=\"height: ";
    echo $hpod."px; bottom: ".$ypod."px;\"></div>\n";
    echo "      <div class=\"cdate\"><span>".$cdate."</span></div>\n";
    echo "      <div class=\"title\"><span>".$ctitle."</span></div>\n";
    echo "  </div>\n";
}


echo "</div>\n\n";

echo "</table>";


?>