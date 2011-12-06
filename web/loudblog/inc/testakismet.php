<?php
  // Akismet Comment Spam Prevention -->
include_once(realpath(dirname(__FILE__)."/../")."/custom/plugins/Akismet.class.php");
$akismet_APIKey = '6f6565096b88';# Akismet API Key Goes here
$akismet_BlogURL = $settings['url'];
$akismet = new Akismet($akismet_BlogURL ,$akismet_APIKey);
$akismet->setCommentAuthor('Bill Thies');
$akismet->setCommentAuthorEmail('bthies@gmail.com');
$akismet->setCommentAuthorURL('');
$akismet->setCommentContent('I agree with this post.  It makes a number of good points.  Thank you for posting it.');
/*
$akismet->setCommentAuthor('lkjasdlkj alkdsj flkasd jaks jdk fakjsd fsd');
$akismet->setCommentAuthorEmail('lkajs dlkjf alksdj f @  s. asd fj.c');
$akismet->setCommentAuthorURL('');
$akismet->setCommentContent(' jalsd jlkas djlka sdjlkf ajslkd flkajs fd jalskd jflka sdf.');
$akismet->setPermalink($settings['url']."/index.php?id={$currentid}#comments");
*/
print ($akismet->isCommentSpam());
?>
