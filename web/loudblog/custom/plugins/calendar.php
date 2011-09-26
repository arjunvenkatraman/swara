<?php
function calendar($context) {

// Please change the following lines to display the calendar in your language!
// ---------------------------------------------------------------------------
	$displayDay[0] = 'mon';
	$displayDay[1] = 'tue';
	$displayDay[2] = 'wed';
	$displayDay[3] = 'thu';
	$displayDay[4] = 'fri';
	$displayDay[5] = 'sat';
	$displayDay[6] = 'sun';

	$displayMonth[0] = 'january';
	$displayMonth[1] = 'february';
	$displayMonth[2] = 'march';
	$displayMonth[3] = 'april';
	$displayMonth[4] = 'may';
	$displayMonth[5] = 'june';
	$displayMonth[6] = 'july';
	$displayMonth[7] = 'august';
	$displayMonth[8] = 'september';
	$displayMonth[9] = 'october';
	$displayMonth[10] = 'november';
	$displayMonth[11] = 'december';
	
// -------------------------------------------------------------------------------
// Loudblog plugin
// month-by-month Calendar
//
// Written by André Neubauer
//      http://www.digitalesdenken.de
//      andre.neubauer@gmx.de
//
// Configure #calendar in your style-sheet file to
// style this component
//
// Available attributes
//      firstDayOfWeek = defines the first day of the week
//                       use 0 for sunday, 1 for monday, ...
//      decoration = 0 for no decoration
//                   1 for displaying abbrevations of days and current month, year
//
// Released under the Gnu General Public License
// http://www.gnu.org/copyleft/gpl.html
// -------------------------------------------------------------------------------

	$attributes = getattributes($context);
	$content = '';

	// parse attributes
	if(isset($attributes['firstDayOfWeek'])) {
		$firstDayOfWeek = $attributes['firstDayOfWeek'];
	} else {
		$firstDayOfWeek = 0;
	}

	if(isset($attributes['decoration'])) {
		$decoration = $attributes['decoration'];
	} else {
		$decoration = 1;
	}


	// lookup for special request
	if(isset($_GET['date'])) {
		$date = $_GET['date'];
		$seperator = strpos($date, '-');
		$year = substr($date, 0, $seperator);
		$month = substr($date, $seperator + 1);
	} else {
		$month = date('m');
		$year = date('Y');
	}

	// first day of month
	$firstDay = mktime(0, 0, 0, $month, 1, $year);
	$dayOfWeek = (date('w', $firstDay) + 7 - $firstDayOfWeek) % 7;
	// last day of month
	$lastDay = mktime(0, 0, 0, $month + 1, 0, $year);
	$maxDays = date('d', $lastDay);

	// previous month
	$previousMonth = mktime(0, 0, 0, $month - 1, 1, $year);
	// next month
	$nextMonth = mktime(0, 0, 0, $month + 1, 1, $year);

	// get posts
	$sql = "SELECT title, posted FROM ".$GLOBALS['prefix']."lb_postings WHERE posted >= '" . date("Y-m-d H:i:s", $firstDay) . "' AND posted < '" . date("Y-m-d H:i:s", $nextMonth) . "';";
	$result = mysql_query($sql) OR die (mysql_error());

	// merge results
	$posts = array();
	while ($row = mysql_fetch_assoc($result)) {
		$key = str_replace("0", "", substr($stripped = strrchr ($row['posted'], "-"), 1, strpos($stripped, ' ')-1));
		if(array_key_exists($key, $posts)) {
			$posts[$key] = $posts[$key] . ", " . $row['title'];
		} else {
			$posts[$key] = $row['title'];
		}
	}

	$i = 1;
	$content .= "<table id=\"calendar\">\n<thead>\n";
	if($decoration == 1) {
		$content .= "<tr>\n<th colspan=\"7\">" . $displayMonth[$month - 1] . " " .$year . "</th></tr>\n<tr>\n";
		foreach($displayDay as $singleDay) {
			$content .= "<th>" . $singleDay . "</th>";
		}
	}
	$content .= "</thead>\n<tbody>\n";
	while($i <= $maxDays + $dayOfWeek) {
		$content .= "<tr>\n";
		for($j = 0; $j < 7; $j++) {
			if($i > $dayOfWeek && $i <= $maxDays + $dayOfWeek) {
				$current = $i - $dayOfWeek;

				if(array_key_exists($current, $posts)) {
					$content .= "<td><a href=index.php?date=" . date("Y-m-d", mktime(0, 0, 0, $month, $current, $year)) . " title=\"" . $posts[$current] . "\">" . $current . "</td>";
				} else {
					$content .= "<td>" . $current . "</td>";
				}
			} else {
				$content .= "<td>&nbsp;</td>";
			}
			$i++;
		}
		$content .= "</tr>";
	}
	$content .= "</tbody>\n<tfoot>\n";
	$content .= "<tr>\n";
	$content .= "<th colspan=3><a href=index.php?date=" . date("Y-m", $previousMonth) . "><<</a></th><th>&nbsp;</th>";
	$content .= "<th colspan=3><a href=index.php?date=" . date("Y-m", $nextMonth) . ">>></a></th>";
	$content .= "</tr>\n";
	$content .= "</tfoot>\n</table>\n";

	return $content;
}

?>