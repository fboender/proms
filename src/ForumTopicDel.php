<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$topic_id   = Import ("topic_id"   , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsProjectOwner($project_id)) {
	Error ("Access denied. You're not the project owner", "back");
}

/* Passed data check */
if (!isset($topic_id) || !isset($project_id)) {
	Error ("Data error");
}

$qry_topicdel = "DELETE FROM forum WHERE project_id='".$project_id."' AND id='".$topic_id."'";
$rslt_topicdel = mysql_query ($qry_topicdel) or mysql_qry_error(mysql_error(), $qry_topicdel, __FILE__, __LINE__);

Refresh ("ForumTopicList&project_id=".$project_id);
?>
