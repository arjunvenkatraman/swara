<?php header("Content-type: text/html; charset=utf-8");session_start();
// sleep(1);
include "inc/accesscheck.php";

$tags = gettaglist();

$q = isset($_GET['q']) ? $_GET['q'] : '';
foreach($tags as $tag) {
	if(eregi("^".$q, $tag)) {
		echo $tag."\r\n";
	}
}
?>