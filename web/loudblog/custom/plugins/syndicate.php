<?php 

function syndicate($content) {
//gets data from an rss- or atom-feed and builds an unordered list
//This is based on MagPieRSS and a nice php-script by Richard Eriksson

$att = getattributes($content);

if (isset($att['number'])) { $number = $att['number']; } else { $number = 10; }

if (isset($att['url'])) { 

    $return = "<ul>";
    require_once "loudblog/inc/magpierss/rss_fetch.inc"; 
    $yummy = fetch_rss($att['url']);
    $yummyitems = array_slice($yummy->items, 0, $number);
    foreach ($yummyitems as $yummyitem) {
        $return .= '<li>';
        $return .= '<a href="';
        $return .= $yummyitem['link'];
        $return .= '">';
        $return .= $yummyitem['title'];
        $return .= '</a>';
        $return .= '</li>';
    }
    $return .= "</ul>";
}

return $return;
}


?>