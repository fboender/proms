<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id       = Import ("project_id"       , "GP");
$projectmember_id = Import ("projectmember_id" , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in.", "back");
}
if (!IsAuthorized($project_id, 'AUTH_PROJECTMEMBERS_MODIFY')) {
	Error ("Access denied. You're not authorized to modify bugs", "back");
}

/* Delete user */
$qry_delmember = "DELETE FROM project_members WHERE id='".$projectmember_id."'";
Debug ($qry_delmember);
$rslt_delmember = mysql_query($qry_delmember) or mysql_qry_error(mysql_error(), $qry_delmember, __FILE__, __LINE__);

Refresh ("ProjectMemberList&project_id=".$project_id);
?>
