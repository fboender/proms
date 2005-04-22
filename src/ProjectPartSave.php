<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$part       = Import ("part"       , "GP");
$part_id    = Import ("part_id"    , "GP");
$wizard     = Import ("wizard"     , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsProjectOwner($project_id)) {
	Error ("Access denied. You're not the project owner", "back");
}

if (isset($part_id) && $part_id != "") {
	/* Update */
	$qry_update  = "UPDATE project_parts SET ";
	$qry_update .= "title='"        .$part["title"]        ."', ";
	$qry_update .= "description='"  .$part["description"]  ."', ";
	$qry_update .= "maint_user_id='".$part["maint_user_id"]."'  ";
	$qry_update .= "WHERE id='"     .$part_id              ."'  ";

	Debug($qry_update, __FILE__, __LINE__);
	$rslt_update = mysql_query($qry_update) or mysql_qry_error(mysql_error(), $qry_update, __FILE__, __LINE__);
} else {
	/* Insert new */
	$qry_insert  = "INSERT INTO project_parts SET ";
	$qry_insert .= "title='"        .$part["title"]        ."', ";
	$qry_insert .= "description='"  .$part["description"]  ."', ";
	$qry_insert .= "maint_user_id='".$part["maint_user_id"]."', ";
	$qry_insert .= "project_id='"   .$project_id           ."'  ";

	Debug($qry_insert, __FILE__, __LINE__);
	$rslt_insert = mysql_query($qry_insert) or mysql_qry_error(mysql_error(), $qry_insert, __FILE__, __LINE__);
}

Refresh ("ProjectPartList&project_id=".$project_id."&wizard=".$wizard);
?>
