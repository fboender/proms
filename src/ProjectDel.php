<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id", "GP");

/* Security check */
if (!IsAdmin() && !IsProjectOwner($project_id)) {
	Error ("Access denied. You're not an Administrator", "back");
}


if (isset($project_id)) {
	$qry_project = "DELETE FROM projects WHERE id='".$project_id."'";
	Debug($qry_project, __FILE__, __LINE__);
	$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);

	$qry_bugs = "DELETE FROM bugs WHERE project_id='".$project_id."'";
	$rslt_bugs = mysql_query($qry_bugs) or mysql_qry_error(mysql_error(), $qry_bugs, __FILE__, __LINE__);

	$qry_forum = "DELETE FROM forum WHERE project_id='".$project_id."'";
	$rslt_forum = mysql_query($qry_forum) or mysql_qry_error(mysql_error(), $qry_forum, __FILE__, __LINE__);

	$qry_members = "DELETE FROM project_members WHERE project_id='".$project_id."'";
	$rslt_members = mysql_query($qry_members) or mysql_qry_error(mysql_error(), $qry_members, __FILE__, __LINE__);

	$qry_parts = "DELETE FROM project_parts WHERE project_id='".$project_id."'";
	$rslt_parts = mysql_query($qry_parts) or mysql_qry_error(mysql_error(), $qry_parts, __FILE__, __LINE__);

	$qry_releases = "DELETE FROM project_releases WHERE project_id='".$project_id."'";
	$rslt_releases = mysql_query($qry_releases) or mysql_qry_error(mysql_error(), $qry_releases, __FILE__, __LINE__);

	$qry_subscriptions = "DELETE FROM subs_releases WHERE project_id='".$project_id."'";
	$rslt_subscriptions = mysql_query($qry_subscriptions) or mysql_qry_error(mysql_error(), $qry_subscriptions, __FILE__, __LINE__);

	$qry_todos = "DELETE FROM todos WHERE project_id='".$project_id."'";
	$rslt_todos = mysql_query($qry_todos) or mysql_qry_error(mysql_error(), $qry_todos, __FILE__, __LINE__);

	$qry_bugs = "DELETE FROM bugs WHERE project_id='".$project_id."'";
	$rslt_bugs = mysql_query($qry_bugs) or mysql_qry_error(mysql_error(), $qry_bugs, __FILE__, __LINE__);
} else {
	Error ("No project was specified.");
}

Refresh ("ProjectList");
