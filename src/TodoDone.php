<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$todo       = Import ("todo"       , "GP");

if (!IsProjectMember($project_id)) {
	Error ("Unauthorized", "back");
}

foreach ($todo as $key => $value) {
	$qry_markdone = "UPDATE todos SET done=2 WHERE id='".$key."'";
	Debug ($qry_markdone, __FILE__, __LINE__);
	$rslt_markdone = mysql_query ($qry_markdone) or mysql_qry_error(mysql_error(), $qry_markdone, __FILE__, __LINE__);
}

Refresh ("TodoList&project_id=".$project_id);
?>
