<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id", "GP");

/* Security */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}

/* Check if project exists */
$qry_exists = "SELECT id FROM projects WHERE id='".$project_id."'";
$rslt_exists = mysql_query($qry_exists) or mysql_qry_error(mysql_error(), $qry_exists, __FILE__, __LINE__);
if (mysql_num_rows($rslt_exists) == 0) {
	Error("Non-existent project specified.", "back");
}

$qry_insert = "INSERT INTO subs_releases SET project_id='".$project_id."', user_id='".$user_id."'";
Debug ($qry_insert, __FILE__, __LINE__);
$rslt_insert = mysql_query ($qry_insert) or mysql_qry_error(mysql_error(), $qry_insert, __FILE__, __LINE__);
if (mysql_affected_rows() != 1) {
	/** ERROR */
}
?>
<br><br>
You will now start recieving release announcements for this project. They will be send to the
e-mail address specified for your account.<br><br>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project overview</a> &nbsp;
