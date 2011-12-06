<?php include("/var/www/html/admin/password_protect.php"); ?>
<?php
	$playFileOnLoad = $_POST['play'];
	
	if (!isset($playFileOnLoad)) {
		$as = 0;
	} else {
		$as = 1;
	}

	$station = '12345';//$_GET['station'];
	
	$view = $_POST['view'];
	if (!isset($view)) { $view = 'all'; }
	
    $username="python";
    $password="rock+bait";
    $database="audiowikiIndia";

    mysql_connect(localhost,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");
	
	// How many adjacent pages should be shown on each side?
	$adjacents = 5;
	
	if ($view == "published") {
		$squery = "SELECT COUNT(*) as num FROM comments WHERE archived = 0 AND station = $station";
	}
	else if ($view == "new") {
		$squery = "SELECT COUNT(*) as num FROM comments WHERE archived = 2 AND station = $station";
	}
	else if ($view == "all") {
		$squery = "SELECT COUNT(*) as num FROM comments WHERE station = $station";
	}
	else {
		$squery = "SELECT COUNT(*) as num FROM comments WHERE station = $station";
	}
	
	$total_pages = mysql_fetch_array(mysql_query($squery));
	$total_pages = $total_pages[num];
	
	/* Setup vars for query. */
	$targetpage = "index.php"; 	//your file name  (the name of this file)
	$limit = 20; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
		
	if ($view == "published") {
		$query = "SELECT * FROM comments WHERE archived = 0 AND station = $station ORDER BY time DESC LIMIT $start, $limit";
	}
	else if ($view == "new") {
		$query = "SELECT * FROM comments WHERE archived = 2 AND station = $station ORDER BY time DESC LIMIT $start, $limit";
	}
	else if ($view == "all") {
		$query = "SELECT * FROM comments WHERE station = $station ORDER BY time DESC LIMIT $start, $limit";
	}
	else {
		$query = "SELECT * FROM comments WHERE station = $station ORDER BY time DESC LIMIT $start, $limit";
	}
	
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	
	
	if ($page == 0) $page = 1;					
	$prev = $page - 1;							
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$lpm1 = $lastpage - 1;				

	 
		
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage?page=$prev\">? previous</a>";
		else
			$pagination.= "<span class=\"disabled\">? previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage?page=$next\">next ?</a>";
		else
			$pagination.= "<span class=\"disabled\">next ?</span>";
		$pagination.= "</div>\n";		
	}

	?>
	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>CGNet Swara </title>
<link rel="Shortcut Icon" href="swaraicon.png" type="image/x-icon" />


<script type="text/javascript" src="Scripts/jquery.js"></script>
<script type="text/javascript" src="Scripts/thickbox.js"></script>
<script language='JavaScript' src='wimpy.js'></script>
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />


<style type="text/css">
<!--
.visibleDiv, #topLeft, #topRight, #bottomLeft, #bottomRight
{
	background-image: url(images/top_panel.png);
    position: fixed;
    border: solid 0px #000000;
    vertical-align: middle;
    text-align: center;
	width:100%;
}

#topLeft
{
    top: 0px;
    left: 0px;
}
#bottomLeft
{
	position: fixed;
	bottom: 0px;
}
body {
	background-image: url(images/top_panel.png);
	background-repeat:repeat-x;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
div.leftTitle {  
	padding-top: 1px;  
	padding-right: 0px;  
	padding-bottom: 2px;  
	padding-left: 10px;  
} 
div.rightTitle {  
	padding-top: 1px;  
	padding-right: 14px;  
	padding-bottom: 2px;  
	padding-left: 0px;  
} 
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
}
p.sample {
	font-family: sans-serif;
	font-style: normal;
	font-variant: normal;
	font-weight: bold;
	font-size: 50px;
	line-height: 100%;
	word-spacing: normal;
	letter-spacing: normal;
	text-decoration: none;
	text-transform: none;
	text-align: center;
	text-indent: 0ex;
	color: #FFFFFF;
}
a:link {
	color: #666;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #666;
}
a:hover {
	text-decoration: none;
	color: #666;
}
a:active {
	text-decoration: none;
	color: #666;
}
-->
</style>

<script type="text/javascript" src="Scripts/jquery-latest.pack.js"></script>
<script type="text/javascript" src="Scripts/thickbox.js"></script>

<script src="Scripts/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="Scripts/jquery.jeditable.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
$(function() {
	$(".edit").editable("update_description.php",
	   { 
	      indicator : "Updating...",
	      tooltip   : "Doubleclick to edit...",
	      event     : "dblclick",
	      style     : "inherit"
	    }
	 );
});
</script>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript" src="Scripts/audio-player.js"></script>  
<script type="text/javascript">  
            AudioPlayer.setup("images/player.swf", {  
                width: 300,
				transparentpagebg: "yes",
				remaining: 'yes',
				noinfo: 'yes',
				rtl: 'yes'
            });  
</script>

<SCRIPT language="JavaScript">
function OnSubmitForm()
{
	if(document.pressed == 'Archive')
	{
		document.myform.action ="archive.php";
		document.myform.submit();
	}
	if(document.pressed == 'Publish')
	{
		document.myform.action ="publish.php";
		document.myform.submit();
	}
	else  if(document.pressed == 'Delete')
	{
		document.myform.action ="delete.php";
		document.myform.submit();
	}
	return true;
}
</SCRIPT>
<SCRIPT language="JavaScript">
function unCheckAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = false ;
}
</script>

</head>

<body onload="unCheckAll(document.myform.selectedPost);" text="#333333" link="#666666" vlink="#666666" alink="#666666">
<body onload="unCheckAll(document.myform.selectedPost);" text="#333333" link="#666666" vlink="#666666" alink="#666666">
<div id="test" align="center">
<a href="javascript:;" onClick="wimpy_addTrack(true,'sounds/audiowikiIndia/web/1061.mp3', '', '', '', '');"> play </a>
</div>
<div id="wimpyTarget" align="center">
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="133" height="31" id="wimpy" align="center">
<param name="allowScriptAccess" value="always" />
<param name="movie" value="http://audiowiki.no-ip.org/admin/wimpy.swf" />
<param name="loop" value="false" />
<param name="menu" value="false" />
<param name="quality" value="high" />
<param name="scale" value="noscale" />
<param name="salign" value="lt" />
<param name="bgcolor" value="#A0A0A0" />
<param name="flashvars" value="wimpyApp=http://audiowiki.no-ip.org/admin/wimpy.php&wimpySkin=http://audiowiki.no-ip.org/admin/skins/skin_micro.xml&startPlayingOnload=yes&wimpySkin=http://audiowiki.no-ip.org/admin/skins/skin_micro.xml" />
<param name="wmode" value="opaque" />
<embed src="http://audiowiki.no-ip.org/admin/wimpy.swf" flashvars="wimpyApp=http://audiowiki.no-ip.org/admin/wimpy.php&wimpySkin=http://audiowiki.no-ip.org/admin/skins/skin_micro.xml&startPlayingOnload=yes&wimpySkin=http://audiowiki.no-ip.org/admin/skins/skin_micro.xml" loop="false" wmode="opaque" menu="false" quality="high" width="133" height="31" scale="noscale" salign="lt" name="wimpy" align="center" bgcolor="#94B2D1" allowScriptAccess="always" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</div> 

<script language="JavaScript"> 

	var SwaraWimpy = new Object();
	
	
	SwaraWimpy.wimpyWidth			= "133";
	SwaraWimpy.wimpyHeight			= "31";
	SwaraWimpy.wimpySkin			= "http://audiowiki.no-ip.org/admin/skins/skin_micro.xml";
	SwaraWimpy.startupLogo			= "swaraicon.png";
	SwaraWimpy.startPlayingOnload	= "";
	SwaraWimpy.bkgdColor			="#94B2D1" ;
	
	makeWimpyPlayer(SwaraWimpy);
	
</script>

<p class="sample"><img src="swara.jpg" width="322" height="142" alt="CGnet Swara" /></p>
<div class="visibleDiv" id="bottomLeft">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#666" style="color:#FFF">
  <tr>
    <td></td>
    <td>
    	  <INPUT TYPE="SUBMIT" name="Operation" onClick="document.pressed=this.value;return OnSubmitForm();" VALUE="Publish">
    	  <INPUT TYPE="SUBMIT" name="Operation" onClick="document.pressed=this.value;return OnSubmitForm();" VALUE="Archive">
    	  <INPUT TYPE="SUBMIT" name="Operation" onClick="document.pressed=this.value;return OnSubmitForm();" VALUE="Delete">
    </td>
    <td>
          <FORM  name="post_view" id="post_view" ACTION="index.php" METHOD=POST onSubmit="return dropdown(this.gourl)" style="border:0;">
          view:
          <select name="view" onchange="document.post_view.submit();"><option value="published" <?php if ($view=='published') {echo 'selected';} ?>>Published Posts</option>
            <option value="new" <?php if ($view=='new') {echo 'selected';} ?>>New Posts</option>
            <option value="all" <?php if ($view=='all') {echo 'selected';} ?>>All Posts</option>
          </select>
          </FORM>
    </td>
    <td align="right">
    <form enctype="multipart/form-data" action="uploadMp3.php?" method="POST">
	  <input name="uploadMp3" type="file" /> 
	    <input type="submit" value="Upload" />
	</form>
	</td>
  </tr>
</table>
</div>


<FORM name="myform" id="myform" onSubmit="return OnSubmitForm();" method="get">
<?php
if ($num == 0) {
	echo "<br><br><h1 align=\"center\">No posts!!<br> Call 8066932500 to add your thoughts.</h1>";
}
else {
	echo "<center><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
}
$i = 1;

		while($row = mysql_fetch_array($result))
		{
	
		if ($i % 2 == 1) {
		//Even row, background color is gray
		$color = "#E6E8FA";
		$i++;
	}
	else {
		//Odd row, background color is white
		$color = "#FFFFFF";
		$i++;
	}
	// 2 Unread
	// 1 Archived
	// 0 Live
	if ($row['archived'] == 2) { $status = "unread"; $textcolor = "color:#990000;"; $fontStyle="b";}
	if ($row['archived'] == 1) { $status = "archived"; $textcolor = "color:#B0B0B0;"; $fontStyle="p";}
	if ($row['archived'] == 0) { $status = "live"; $textcolor = "color:#484848;"; $fontStyle="p";}

/*
	echo 
	"<tr height=\"35\" bgcolor=\"" . $color . "\">
		<td width=\"2%\">
		</td>
		<td width=\"8%\">
        	<input type=\"checkbox\" name=\"selectedPost[]\" id=\"selectedPost\" value=\"".$row['id']."\"> <br />
		</td>
		<td width=\"52%\"><$fontStyle id=\"" . $row['id'] . "\" class=\"edit\" style=\"$textcolor display: inline\">". $row['description'] . "</$fontStyle>
		</td>
    		<td width=\"30%\">
				<p id=\"audioplayer_".$i."\">Alternative content</p>
           		<script type=\"text/javascript\">
            	AudioPlayer.embed(\"audioplayer_" . $i . "\", {soundFile: \"sounds/audiowikiIndia/web/".$row['id'].".mp3\"});
            	</script>
			</td>
		<td width=\"3%\"><a href=\"confirmDeleteIndia.php?id=".$row['id']."&fileDescription=".$row['description']."\">
			<img src=\"images/delete.png\" alt=\"delete comment\""."width=\"20\" height=\"20\" align=\"absmiddle\" /></a>
		</td>
    	<td width=\"5%\"><a href=\"replace.php?id=".$row['id']."&confirmed=0\">edit</a>
		</td>
  	</tr>";
*/

	echo 
	"<tr height=\"35\" bgcolor=\"" . $color . "\">
		<td width=\"3%\">
		</td>
		<td width=\"2%\">
        	<input type=\"checkbox\" name=\"selectedPost[]\" id=\"selectedPost\" value=\"".$row['id']."\"> <br />
		</td>
		<td width=\"80%\"><$fontStyle id=\"" . $row['id'] . "\" class=\"edit\" style=\"$textcolor display: inline\">". $row['description'] . "</$fontStyle>
		<td width=\"1%\">
		</td>
		</td>		
    		<td width=\"10%\">
				<a href=\"JavaScript:;\" onClick=\"wimpy_addTrack(true, 'sounds/audiowikiIndia/web/".$row['id'].".mp3', '', '', '', '');\"> play </a>
				<a href=\"confirmDeleteIndia.php?id=".$row['id']."&fileDescription=".$row['description']."\">delete </a>			
				
				<a href=\"replace.php?id=".$row['id']."&confirmed=0\">edit</a>
				
						</td>
		<td width=\"2%\">
		</td>
  	</tr>";
	
		}


 
	
		
mysql_close();
?>

<?=$pagination?>
<hr />

Click to go to the respective page --> <a href="http://audiowiki.no-ip.org/admin/index.php?page=1">1</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=2">2</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=3">3</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=4">4</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=5">5</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=6">6</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=7">7</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=8">8</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=9">9</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=10">10</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=11">11</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=12">12</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=13">13</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=14">14</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=15">15</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=16">16</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=17">17</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=18">18</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=19">19</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=20">20</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=21">21</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=22">22</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=23">23</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=24">24</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=25">25</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=26">26</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=27">27</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=28">28</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=29">29</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=30">30</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=31">31</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=32">32</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=33">33</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=34">34</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=35">35</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=36">36</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=37">37</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=38">38</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=39">39</a> | <a href="http://audiowiki.no-ip.org/admin/index.php?page=40">40</a> |
<hr />

</form>
</table>
<center>
<br />
<br />
<br />
<br />
</body>
</html>


	