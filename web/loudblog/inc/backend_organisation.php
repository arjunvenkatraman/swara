<?php
echo "<h1>".bla("hl_organisation")."</h1>\n";

include ('inc/navigation.php');
include ('inc/functions_organisation.php');

//check the rights
if (!allowed(3,"")) 
{ die("<p class=\"msg\">".bla("msg_adminonly")."</p>"); }

//what do we get from url? go to appropiate function(s)!

if (!isset($_GET['do'])) { 
    showcatsandauthors (); }  

else { 

if ($_GET['do'] == "editauthor") { 
    showauthor ($_GET['id'], false); }
    
if ($_GET['do'] == "newauthor") { 
    showauthor (0, true); }
    
if ($_GET['do'] == "delauthor") {
    if ($_GET['id'] != $_SESSION['authorid']) {
        deleteauthor ($_GET['id']);
    } else { echo "<p class=\"msg\">".bla("msg_adminsuicide")."</p>"; }
    showcatsandauthors (); }
    
if ($_GET['do'] == "saveauthor") { 
    if (savepostedauthordata ($_GET['id'])) { showcatsandauthors (); }
    else { showauthor ($_GET['id'], false); }
    }
    
if ($_GET['do'] == "savecats") { 
    savepostedcats (); 
    showcatsandauthors ();
}


if ($_GET['do'] == "edittag")  {
   if (!isset($_POST['tagaction'])) {
   showcatsandauthors();
   message(bla("msg_no_action"));
                } else {
                if ($_POST['tagaction'] == "deletetag")  {
                                        if(isset($_POST['tagnames'])) {
                                        amendtags("","delete",$_POST['tagnames'],"");
                                        showcatsandauthors();
                                        } else {message(bla("msg_no_tag"));}
                }
                if (($_POST['tagaction'] == "amendtag")) {
                                         if ((isset($_POST['tagnames']))AND(isset($_POST['newtagname']))) {
                                         amendtags("","replace",$_POST['tagnames'],$_POST['newtagname']);
                                         showcatsandauthors();
                                         } else {message(bla("msg_no_tag"));}
                                         }
                if ($_POST['tagaction'] == "createcat")  {
                                        if ((isset($_POST['catname']))AND(isset($_POST['tagnames']))){
                                        catfromtag($_POST['catname'],$_POST['tagnames']);
                                        showcatsandauthors();
                                        } else {message(bla("msg_no_tag_cat"));}
                }
                }
}
if ($_GET['do'] == "tagfromcat")  {
                                        if((isset($_POST['catname']))AND(isset($_POST['newtagname']))) {
                                        $criteria = " WHERE (category1_id = ".$_POST['catname']." OR category2_id = ".$_POST['catname']." OR category3_id =".$_POST['catname']." OR category4_id = ".$_POST['catname'].") ";
                                        amendtags($criteria,"add","",$_POST['newtagname']);
                                        showcatsandauthors();
                                        } else {message(bla("msg_no_tag_cat"));}


}
}
