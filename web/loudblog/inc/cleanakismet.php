<?PHP

include_once(realpath(dirname(__FILE__)."/../")."/custom/plugins/Akismet.class.php");

$user_name = "root";
$password = "Wmtp00lr!";
$database = "audiwikiswara";
$server = "127.0.0.1";
$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database, $db_handle);

if ($db_found) {

  $SQL = "SELECT * FROM lb_comments where posting_id>0";
  $result = mysql_query($SQL);
  $i = 0;

  while ($db_field = mysql_fetch_assoc($result)) {
    $akismet_APIKey = '6f6565096b88';# Akismet API Key Goes here
    $akismet_BlogURL = 'http://cgnetswara.org/';
    $akismet = new Akismet($akismet_BlogURL ,$akismet_APIKey);
    $akismet->setCommentAuthor($db_field['name']);
    $akismet->setCommentAuthorEmail($db_field['mail']);
    $akismet->setCommentAuthorURL($db_field['web']);
    $akismet->setCommentContent($db_field['message_input']);
    $akismet->setPermalink('http://cgnetswara.org/index.php?id='.$db_field['posting_id'].'#comments');

    if ($akismet->isCommentSpam()) 
      {
	print $i.". Comment ".$db_field['id']." is spam.<br>";
	if ($db_field['posting_id']>0)
	  {
	    $SQLB = "UPDATE lb_comments SET posting_id=-".$db_field['posting_id']." where id=".$db_field['id'];
	    $resultb = mysql_query($SQLB);
	  }
      }
    else 
      {
	print $i.". Comment ".$db_field['id']." is OK<br>";
      }
    $i = $i + 1;
    ob_flush();
    flush();
  }

  mysql_close($db_handle);

}
else {
  print "Database NOT Found ";
  mysql_close($db_handle);
}

?>