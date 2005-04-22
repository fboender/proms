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

$qry_delete = "DELETE FROM subs_releases WHERE project_id='".$project_id."' AND user_id='".$user_id."'";
Debug ($qry_delete, __FILE__, __LINE__);
$rslt_delete = mysql_query ($qry_delete) or mysql_qry_error(mysql_error(), $qry_delete, __FILE__, __LINE__);

if (mysql_affected_rows() != 1) {
	/** ERROR */
}
?>
<br><br>
You will <b>no longer</b> recieve release announcements for this project.
<br><br>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project overview</a> &nbsp;
