<?php
include ('inc/functions_record.php');

echo '<p><img src="backend/wait.gif" alt="Waiting for pinging" /> '.bla("pinging").' &hellip;</p>';
echo '<form action="" method="get"><fieldset>';
echo '<input type="submit" value="'.bla("but_cancel").'" onclick="window.close();" />';
echo '</fieldset></form>';
echo '<iframe src="ping.php"></iframe>';

?>