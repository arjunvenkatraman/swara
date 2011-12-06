<?php
echo "<h1>".bla("hl_login")."</h1>\n";

//choosing an appropriate welcome-message
if (!isset($HTTP_SESSION_VARS['nickname']) OR !isset($_POST['nickname'])) 
    $addmessage = ""; 
if (isset($_POST['nickname'])) 
    $addmessage = bla("msg_wrongpassword");
if ((isset($_GET['do'])) AND ($_GET['do'] == "logout")) 
    $addmessage = bla("msg_logout");

//delete session, if still active
if (isset($SESSION['nickname'])) { session_unset(); session_destroy(); }
    
//simply put the message onto the screen    
echo "<p class=\"msg\">" . $addmessage . "</p>";

echo "<form id=\"loginform\" class=\"plain\"";
echo "action=\"index.php?page=record1\" method=\"post\">\n";
echo "<table>\n";
echo "<tr>\n<th><label for=\"nickname\">".bla("nickname")."</label></th>\n";
echo "<th><label for=\"password\">".bla("password")."</label></th>\n<th></th>\n</tr>\n";
echo "<tr>\n<td><input id=\"nickname\" type=\"text\" name=\"nickname\" /></td>\n";
echo "<td><input id=\"password\" type=\"password\" name=\"password\" /></td>\n";
echo "<td><input type=\"submit\" name=\"submit\" value=\"".bla("but_login")."\" /></td>\n";
echo "</tr>\n<tr>\n\t<td colspan=\"3\">";
echo "<input id=\"remember_me\" name=\"remember_me\" type=\"checkbox\" value=\"1\" ";
echo 'checked="checked"';
echo "/><label for=\"remember_me\">" . bla('remember_me')."</label></td>\n</tr>";
echo "</table>\n</form>\n";

?>