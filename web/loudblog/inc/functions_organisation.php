<?php

function savepostedcats () {

global $settings;
$countcat = max_id("lb_categories");
    
//delete or update existing categories
for ($j=1; $j<=$countcat; $j++) {
    
    //preparing looped post-array-things
    $tempcat = "cat" . $j; 
    $tempdesc = "desc" . $j;
    $tempdel = "del" . $j;
    
    //delete categories, if requested
    if (isset($_POST[$tempdel])) {
        $dosql = "DELETE FROM ".$GLOBALS['prefix']."lb_categories 
                  WHERE id = '" . $j . "';";
        $GLOBALS['lbdata']->Execute($dosql);
        
    } else {
        
        //or update existing categories    
        if (isset($_POST[$tempcat])) {
        
            $putcat = htmlentities($_POST[$tempcat], ENT_QUOTES, "UTF-8");
            $putdes = htmlentities($_POST[$tempdesc], ENT_QUOTES, "UTF-8");
            
            $dosql = "UPDATE ".$GLOBALS['prefix']."lb_categories SET
                      name        = '" . $putcat . "',
                      description = '" . $putdes . "'
                      WHERE id = '" . $j . "';";
            $GLOBALS['lbdata']->Execute($dosql);

        }
    }
}

//add new categories
if (isset($_POST['newcat']) AND ($_POST['newcat'] != "")) {

    $putcat = htmlentities($_POST['newcat'], ENT_QUOTES, "UTF-8");
    $putdes = htmlentities($_POST['newdesc'], ENT_QUOTES, "UTF-8");
    
    $dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_categories
                (name, description)
                VALUES (
                '" . $putcat . "', '" . $putdes . "' );";
    $GLOBALS['lbdata']->Execute($dosql);
}

echo "<p class=\"msg\">";
echo bla("msg_successcatsupdate")."</p>";

}

// ----------------------------------------------------------------

function savepostedauthordata ($editid) {

global $settings;
$message = "";
$return = true;
$message = bla("msg_savesuccess");
$changepass = false;


//preparing posted data for saving
$putnick = htmlentities($_POST['nickname'], ENT_QUOTES, "UTF-8");
$putreal = htmlentities($_POST['realname'], ENT_QUOTES, "UTF-8");
$putmail = htmlentities($_POST['mail'], ENT_QUOTES, "UTF-8");
if (isset($_POST['edit_own']))    $putright1 = "1"; else $putright1 = "0";
if (isset($_POST['publish_own'])) $putright2 = "1"; else $putright2 = "0";
if (isset($_POST['edit_all']))    $putright3 = "1"; else $putright3 = "0";
if (isset($_POST['publish_all'])) $putright4 = "1"; else $putright4 = "0";
if (isset($_POST['admin']))       $putright5 = "1"; else $putright5 = "0";

//you cannot degrade yourself, if you're an administrator!!
if (($putright5 == "0") AND ($editid == getuserid($_SESSION['nickname']))) {
    $putright5 = "1";
    $message = bla("msg_admindegrade");
    $return = false;
}

//prepare password-change
if (($_POST['password'] != "default") 
AND ($_POST['password'] == $_POST['password2'])) {

    $putpass = "password = '" . md5($_POST['password']) . "',";
    $return = true;
    $changepass = true;

} else { 
    $putpass = "";
    if ($_POST['password'] != $_POST['password2']) {
    $message = bla("msg_errorpassconfirm");
    $return = false;
    }
}
    
$dosql = "UPDATE ".$GLOBALS['prefix']."lb_authors SET
          " . $putpass . "
          nickname    = '" . $putnick . "',
          realname    = '" . $putreal . "',
          mail        = '" . $putmail . "',
          edit_own    = '" . $putright1 . "',
          publish_own = '" . $putright2 . "',
          edit_all    = '" . $putright3 . "',
          publish_all = '" . $putright4 . "',
          admin       = '" . $putright5 . "'
          
          WHERE id = '" . $editid . "';";
$GLOBALS['lbdata']->Execute($dosql);

echo "<p class=\"msg\">". $message . "</p>";

//set fresh cookies, if user is editing his own data
if ($editid == getuserid($_SESSION['nickname'])) {
    $_SESSION['nickname'] = $putnick;
    if ($changepass) {
        $_SESSION['password'] = md5($_POST['password']);
    }
}

return $return;

}

// ----------------------------------------------------------------

function deleteauthor ($delid) {

global $settings;
//delete author from database
$dosql = "DELETE FROM ".$GLOBALS['prefix']."lb_authors 
          WHERE id = '". $delid . "';";
$GLOBALS['lbdata']->Execute($dosql);
}         


// ----------------------------------------------------------------

function showauthor ($editid, $new) {

global $settings;
if ($new) {
    $tempdate = date('Y-m-d H:i:s');

    //insert a new row to the database and fill it with empty data
    $dosql = "INSERT INTO ".$GLOBALS['prefix']."lb_authors
             (joined, nickname, realname, mail, password,
             edit_own, publish_own, edit_all, publish_all, admin)   
             VALUES
             (
             '".$tempdate."', '".$_POST['newnick']."', 
             '".$_POST['newname']."', '".$_POST['newmail']."', 
             '', '1', '0', '0', '0', '0'
             )";
    $GLOBALS['lbdata']->Execute($dosql);

    //finding the id of the new entry
    $dosql = "SELECT id FROM ".$GLOBALS['prefix']."lb_authors 
              WHERE joined = '".$tempdate."';";
    $row = $GLOBALS['lbdata']->GetArray($dosql);
    $editid = $row[0]['id'];
}

//getting data for requested author-id from authors-table
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_authors 
          WHERE id = '" . $editid . "';";
$result = $GLOBALS['lbdata']->GetArray($dosql);
$author = $result[0];

echo "<div id=\"authordetails\">\n";
echo "<h2>".bla("org_authordetails")." ".$author['nickname']."</h2>\n";

//start the form
echo "<form action=\"index.php?page=organisation&amp;do=saveauthor&amp;id=". $editid;
echo "\" method=\"post\" enctype=\"multipart/form-data\">\n\n";

echo "<table>\n\n";

//showing date/time of joining
$dateformat = $settings['dateformat'];
$showdate = date($dateformat , strtotime($author['joined']));
echo "<tr><td class=\"left\">".bla("org_joined").":</td><td>" . $showdate . "</td></tr>\n";

//nickname
echo "<tr><td class=\"left\">".bla("org_nickname").":</td><td>";
echo "<input type=\"text\" name=\"nickname\" value=\"";
echo $author['nickname'] . "\" /></td></tr>\n";

//real name
echo "<tr><td class=\"left\">".bla("org_fullname").":</td><td>";
echo "<input type=\"text\" name=\"realname\" value=\"";
echo $author['realname'] . "\" /></td></tr>\n";

//email-adress
echo "<tr><td class=\"left\">".bla("org_mail").":</td><td>";
echo "<input type=\"text\" name=\"mail\" value=\"";
echo $author['mail'] . "\" /></td></tr>\n";

//show the author's publication-rights
echo "<tr><td class=\"left\">".bla("org_rightshort1").":</td>\n<td class=\"explain\">";
echo "<input name=\"edit_own\" type=\"checkbox\" ";
echo checker($author['edit_own']) . " /> ";
echo bla("org_right1")."</td></tr>\n";

echo "<tr><td class=\"left\">".bla("org_rightshort2").":</td>\n<td class=\"explain\">";
echo "<input name=\"publish_own\" type=\"checkbox\" ";
echo checker($author['publish_own']) . " /> ";
echo bla("org_right2")."</td></tr>\n";

echo "<tr><td class=\"left\">".bla("org_rightshort3").":</td>\n<td class=\"explain\">";
echo "<input name=\"edit_all\" type=\"checkbox\" ";
echo checker($author['edit_all']) . " /> ";
echo bla("org_right3")."</td></tr>\n";

echo "<tr><td class=\"left\">".bla("org_rightshort4").":</td>\n<td class=\"explain\">";
echo "<input name=\"publish_all\" type=\"checkbox\" ";
echo checker($author['publish_all']) . " /> ";
echo bla("org_right4")."</td></tr>\n";

echo "<tr><td class=\"left\">".bla("org_rightshort5").":</td>\n<td class=\"explain\">";
echo "<input name=\"admin\" type=\"checkbox\" ";
echo checker($author['admin']) . " /> ";
echo bla("org_right5")."</td></tr>\n";


//password with password-confirm
if ($new) { $hiddenpass = ""; } else { $hiddenpass = "default"; }

echo "<tr><td class=\"left\">".bla("org_changepass1").":</td>\n<td>";
echo "<input type=\"password\" name=\"password\" value=\"";
echo $hiddenpass . "\" /></td></tr>\n";

echo "<tr><td class=\"left\">".bla("org_changepass2").":</td>\n<td>";
echo "<input type=\"password\" name=\"password2\" value=\"";
echo $hiddenpass . "\" /></td></tr>\n";

//update-button
echo "<tr><td class=\"left\"></td><td>";
echo "<input type=\"submit\" name=\"update\" value=\"".bla("but_save")."\" /></td></tr>\n";

echo "</table>";

//finish the form
echo "</form>";

echo "</div>";

}

// ----------------------------------------------------------------

function showcatsandauthors () {

global $settings;

//-------------------- authors-list ----------

echo "<div id=\"authors\">\n";
echo "<h2>".bla("org_editauthors")."</h2>\n\n";

//starting the table
echo "<table>\n\n";
echo "<tr><th>".bla("org_nickname")."</th><th>".bla("org_fullname")."</th><th>".bla("org_mail")."</th>";
echo "<th>".bla("org_rights")."</th><th></th></tr>";

//getting all data from authors-table
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_authors ORDER BY id";
$result = $GLOBALS['lbdata']->GetArray($dosql);
$i = 1;
foreach ($result as $row) {

echo "<tr>\n";

/*
//showing date/time of joining
$dateformat = $settings['dateformat'];
$showdate = date($dateformat , strtotime($row['joined']));
echo "<td>" . $showdate . "</td>\n";
*/

echo "<td><a href=\"index.php?page=organisation&amp;do=editauthor&amp;id=".$row['id'];
echo "\">" . $row['nickname'] . "</a></td>\n";
echo "<td><a href=\"index.php?page=organisation&amp;do=editauthor&amp;id=".$row['id'];
echo "\">" . $row['realname'] . "</a></td>\n";
echo "<td><a href=\"mailto:".$row['mail']."\">".$row['mail']."</a></td>\n";

//show the author's publication-rights
echo "<td>\n";
echo "<input type=\"checkbox\" disabled=\"disabled\" ";
echo checker($row['edit_own']) . " title=\"".bla("org_right1")."\" />\n";
echo "<input type=\"checkbox\" disabled=\"disabled\" ";
echo checker($row['publish_own']) . " title=\"".bla("org_right2")."\" />\n";
echo "<input type=\"checkbox\" disabled=\"disabled\" ";
echo checker($row['edit_all']) . " title=\"".bla("org_right3")."\" />\n";
echo "<input type=\"checkbox\" disabled=\"disabled\" ";
echo checker($row['publish_all']) . " title=\"".bla("org_right4")."\" />\n";
echo "<input type=\"checkbox\" disabled=\"disabled\" ";
echo checker($row['admin']) . " title=\"".bla("org_right5")."\" />\n</td>\n";


//a simple delete button
echo "<td class=\"right\">\n";
echo "<form method=\"post\" enctype=\"multipart/form-data\" 
      action=\"index.php?page=organisation&amp;do=delauthor&amp;id=".$row['id'];
echo "\" onSubmit=\"return yesno('".bla("alert_deleteauthor")."')\">\n";
echo "<input type=\"submit\" value=\"".bla("but_delete")."\" />\n</form>\n</td>\n";


echo "</tr>\n\n";

$i += 1;
}

//button for new author
echo "<form method=\"post\" enctype=\"multipart/form-data\" 
      action=\"index.php?page=organisation&amp;do=newauthor\">";
      echo "<tr>\n";
echo "<td><input type=\"text\" name=\"newnick\" value=\"\" /></td>\n";
echo "<td><input type=\"text\" name=\"newname\" value=\"\" /></td>\n";
echo "<td><input type=\"text\" name=\"newmail\" value=\"\" /></td>\n";
echo "<td></td>\n<td class=\"right\">\n";
echo "<input type=\"submit\" value=\"".bla("but_new")."\" />\n";

echo "</td>\n</tr>\n</form>\n</table>\n";
echo "</div>\n\n\n";


//-------------------- categories ----------

echo "<div id=\"categories\">\n";
echo "<h2>".bla("org_editcats")."</h2>\n\n";

//getting all data from category-table
$dosql = "SELECT * FROM ".$GLOBALS['prefix']."lb_categories ORDER BY id;";
$result = $GLOBALS['lbdata']->GetArray($dosql);
$i = 1;
foreach ($result as $row) {
    $cats[$i] = $row;
    $i += 1;
}

//start the form
echo "<form action=\"index.php?page=organisation&amp;do=savecats\"";
echo " method=\"post\" enctype=\"multipart/form-data\">\n\n";

//show all items in each list
echo "<table>\n";
echo "<tr><th>".bla("org_catname")."</th><th>".bla("org_catdesc")."</th><th></th></tr>";

$i = 1;
foreach ($cats as $showcat) {

    //show category
    echo "<tr>\n<td>\n";
    echo "<input class=\"cat\" type=\"text\" value=\"" . $cats[$i]['name'];
    echo "\" name=\"cat" . $cats[$i]['id'] . "\" />\n</td>\n<td>";
    
    //show description
    echo "<input class=\"desc\" type=\"text\" value=\"" . $cats[$i]['description'];
    echo "\" name=\"desc" . $cats[$i]['id'] . "\" />\n</td>\n";
    
    //show delete button
    echo "<td class=\"right\"><input onClick=\"return yesno('".bla("msg_deletecategory")."')\" type=\"submit\" value=\"".bla("but_delete")."\" ";
    echo "name=\"del" . $cats[$i]['id'] . "\" />\n</td>\n</tr>\n\n";
    
    $i += 1;
}

//show a new category, which is to be filled
echo "<tr>\n<td>";
echo "<input class=\"cat\" name=\"newcat\" type=\"text\" value=\"\" /></td>\n";
echo "<td><input class=\"desc\"name=\"newdesc\" type=\"text\" value=\"\" />";
echo "</td><td class=\"right\"><<< ".bla("org_addnew")."&nbsp;</td>\n";
echo "</tr>\n";
echo "<tr class=\"last\"><td colspan=\"2\"></td>";
echo "<td class=\"right\"><input type=\"submit\" value=\"".bla("but_saveall")."\" /></td>";

echo "</table>";



echo "</form>\n";

// -----                Tags   -----------------------//
echo "\n\n<h2>".bla("org_cattag")."</h2>\n\n";

echo "<table><tr><th>".bla("org_cat")."</th><th>".bla("org_newtag")."</th><th></th></tr>";
echo "<form action=\"index.php?page=organisation&amp;do=tagfromcat\"";
echo " method=\"post\" enctype=\"multipart/form-data\">\n\n";
echo "<tr><td>";
echo "<select name=\"catname\" class=\"cat\">";
$i = 1;

foreach ($cats as $showcat) {
echo "<option value=\"".$cats[$i]['id']."\">".$cats[$i]['name']."</option>";
$i+=1;
      }
echo "</select>";
echo "</td><td>";
echo "<input type=\"text\" name=\"newtagname\" value=\"\" class=\"tag\" />";
echo "</td><td>";
echo "<input type=\"submit\" value=\"Make tag\" />";
echo "</td></tr>";
echo "</table>";

echo "</form>";
echo "<p>".bla("org_cattag_help")."</p>\n";
echo "\n\n<h2>".bla("org_managetags")."</h2>\n\n";
echo "<table><tr><th>".bla("org_tag")."</th><th>".bla("org_action")."</th><th>".bla("org_newname")."</th></tr>";
echo "<form action=\"index.php?page=organisation&amp;do=edittag\"";
echo " method=\"post\" enctype=\"multipart/form-data\">\n\n";
echo "<tr><td>";
echo "<select name=\"tagnames\" class=\"cat\">";
$taglist = gettaglist();
foreach($taglist as $tag)  {
  echo "<option value=\"".$tag."\">".$tag."</option>\n";
}
echo "</td><td><input type=\"radio\" name=\"tagaction\" value=\"deletetag\"> ".bla("org_deletetag")."</td><td></td></tr>\n";

echo "<tr><td></td><td><input type=\"radio\" name=\"tagaction\" value=\"amendtag\"> ".bla("org_replacetag")." >>></td>\n";

echo "<td><input type=\"text\" name=\"newtagname\" class=\"tag\" /></td></tr>";
echo "<tr><td></td><td><input type=\"radio\" name=\"tagaction\" value=\"createcat\"> ".bla("org_tagtocat")." >>></td>\n";
echo "<td><select name=\"catname\" class=\"tag\">";
$i = 1;

foreach ($cats as $showcat) {
echo "<option value=\"".$cats[$i]['id']."\">".$cats[$i]['name']."</option>";
$i+=1;
      }
echo "</select></td></tr>";
echo "<tr><td></td><td></td><td><input onClick=\"return yesno('".bla("org_confirmchange")."')\" type=\"submit\" value=\"Submit\" /></td></tr>";
echo "</form></table>\n";
echo "<p>".bla("org_managetags_help")."</p>";
echo "</div>\n\n";

} 

function amendtags($criteria,$action,$oldtag,$newtag)   {
 if ($action == "add") {$message = bla("msg_addtag_1").$newtag.bla("msg_addtag_2");}
 if ($action ==  "delete") {$message = bla("msg_deletetag").$oldtag."'";}
 if ($action == "replace") {$message = bla("msg_replacetag_1").$oldtag.bla("msg_replacetag_2").$newtag."'";}
 if (($action == "replace")AND(veryempty($newtag))) {$message = bla("msg_no_tag");}
 if (($action == "add")AND(veryempty($newtag))) {$message = bla("msg_no_tag");}
//we want to add, delete or replace $oldtag - so lets get tag info for all posts
  $dosql = "SELECT id, tags FROM ".$GLOBALS['prefix']."lb_postings".$criteria;
  $tagarray = $GLOBALS['lbdata'] -> GetArray($dosql);
  $i = 0;
//go through the posts one by one
   foreach ((array)$tagarray as $tagline)  {
              $newtagline = "";
              $tags = explode(" ",$tagarray[$i]['tags']);
//if 'add' and $newtag is not already present, add it
                    if (($action == "add")AND(!in_array($newtag,$tags)))
                                {$newtagline = $tagline['tags']." ".$newtag;
                                }
                                else {
                                foreach ($tags as $t)  {
//if 'delete' and we find $oldtag, then delete it
                                        if (($action == "delete")AND($t == $oldtag))
                                        {continue;}
//if 'replace' and we find $oldtag, replace it with $newtag (unless $newtag is an empty string)
                                        if (($action == "replace")AND($t == $oldtag)AND(!veryempty($newtag)))
                                        {$t = $newtag;}
                                        $newtagline.= " ".$t;
                                                      }
                                        }
//replace the old tag line in the array with the new tag line
           $tagarray[$i]['tags'] = trim($newtagline);
//go back for the next line
           $i +=1;
         }
//and post the new taglines in the database
 foreach ((array)$tagarray as $tagline)   {
	   $tagline['tags'] = htmlentities($tagline['tags'], ENT_QUOTES, "UTF-8");
         $dosql = "UPDATE ".$GLOBALS['prefix']."lb_postings SET tags = '".$tagline['tags']."' WHERE id = '".$tagline['id']."'";
         if (!$GLOBALS['lbdata']->Execute($dosql)) {$message = bla("msg_database_error");}
       }
 message($message);

 }
 function catfromtag($category,$newtag) {
   $message = bla("msg_cat_1").$newtag.bla("msg_cat_2");
//we want to add every post with tag $newtag to the category $category
//get information about tags and categories for all posts
   $dosql = "SELECT id, tags, category1_id, category2_id, category3_id, category4_id FROM ".$GLOBALS['prefix']."lb_postings";
   $tagarray = $GLOBALS['lbdata'] ->GetArray($dosql);
   $i = 0;
//examine the information about each post
      foreach ($tagarray as $tagline)   {
//assume that we should add this posting to $category
             $include = "yes";
//is $newtag in the tag-string? No? Then we don't put this post into $category
             $tags = explode(" ",$tagline['tags']);
             if(!in_array($newtag,$tags))  {
                   $include = "no"; }
//and is the post already in $category? Yes? Then we don't want to do it again
                   else { $catarray = array_slice($tagline,2,4);
                          if (in_array($category,$catarray))  {
                            $include = "no";   }
                        }
            if ($include == "yes")   {
//find the first empty category slot - category1_id, category2_id etc
          for ($j = 1, $where = 0; ($where == 0)AND($j <5); $j++)  {
              $catlocation = "category".$j."_id";
              $where = $j*($tagline[$catlocation] == 0);
               }
//if there are no empty category slots, then we can't add this post to $category
       if ($where == 0)  {$include = "no";} }
       if($include == "yes")  {
//write $category into the database in the empty category_id slot which we have found
       $dosql = "UPDATE ".$GLOBALS['prefix']."lb_postings
                set category".$where."_id = '".$category."' WHERE id = '".$tagline['id']."'";
       if (!$GLOBALS['lbdata'] -> Execute($dosql)) $message = bla("msg_database_error");
     }
//and go back for the next posting
   $i++;
          }
 message($message);

}

function message($content)   {
  //a very crude way to blank out any existing message
echo "<p class=\"msg\">                                                                          </p>";
  //and a slightly more elegant way of putting a new message on the page
echo "<p class=\"msg\">".$content."</p>";
}

