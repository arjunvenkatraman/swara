<?php

echo "<div id=\"navi\">\n";
echo "<h2>Navigation</h2>\n";
echo "<ul>\n";

echo "  <li id=\"tab_record\">";
echo "<a href=\"index.php?page=record1\">".bla("tab_recording")."</a></li>\n";

echo "  <li id=\"tab_postings\"><a href=\"index.php?page=postings\">";
echo bla("tab_postings")."</a></li>\n";

if (allowed(3,"")) {

echo "  <li id=\"tab_comments\"><a href=\"index.php?page=comments\">";
echo bla("tab_comments")."</a></li>\n";

echo "  <li id=\"tab_stats\">";
echo "<a href=\"index.php?page=stats\">".bla("tab_stats")."</a></li>\n";

echo "  <li id=\"tab_organisation\">";
echo "<a href=\"index.php?page=organisation\">".bla("tab_organisation")."</a></li>\n";

echo "  <li id=\"tab_settings\">";
echo "<a href=\"index.php?page=settings\">".bla("tab_settings")."</a></li>\n";

}

echo "<li><a href=\"index.php?do=logout\">".bla("footer_logout")."</a></li>";


echo "</ul>\n";
echo "</div>\n";

