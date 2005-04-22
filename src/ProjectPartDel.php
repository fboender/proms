<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$part_id    = Import ("part_id"    , "GP");
$wizard     = Import ("wizard"     , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsProjectOwner($project_id)) {
	Error ("Access denied. You're not the project owner", "back");
}

$qry_partdel = "DELETE FROM project_parts WHERE id='".$part_id."'";
Debug ($qry_partdel, __FILE__, __LINE__);
$rslt_partdel = mysql_query ($qry_partdel) or mysql_qry_error(mysql_error(), $qry_partdel, __FILE__, __LINE__);

Refresh ("ProjectPartList&project_id=".$project_id."&wizard=".$wizard);
?>
