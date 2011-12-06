<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">



<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <meta http-equiv="content-language" content="en" />
    <title><?php echo pagetitle(); ?></title>

    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="LoudBlog" />

    <meta name="author" content="ONLINETRUST new media" />
    
    <link rel="stylesheet" type="text/css" href="backend/screen.css" />
	<link rel="stylesheet" type="text/css" href="backend/autocomplete.css" />
    <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="backend/ie.css"  />
    <![endif]-->
    <script src="backend/jquery.js" type="text/javascript"></script>
    <script src="backend/functions.js" type="text/javascript"></script>
	<script src="backend/autocomplete.js" type="text/javascript"></script>

<!--	<script src="backend/compressed.js" type="text/javascript"></script> -->
</head>





<body id="<?php 

if (!$access) { 
    echo "login\" onLoad=\"document.loginform.nickname.focus();";    
}
else {
    if (isset($_GET['page'])) { echo killevilcharacters($_GET['page']); }
    else { echo "postings"; }
} 

?>">

<div id="wrapper">



