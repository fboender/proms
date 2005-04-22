<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$project    = Import ("project"    , "GP");
$wizard     = Import ("wizard"     , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if ($project_id != "") {
	if (!IsProjectOwner($project_id)) {
		Error ("Access denied. You're not the project owner", "back");
	}
} else {
	if (!IsAdmin()) {
		Error ("Access denied. You're not the administrator", "back");
	}
}

/* Passed data check */
if ($project["progress"] == "") {
	$project["progress"] = 0;
}

if ($project_id != "") {
	/* Update an existing record */
	$qry_update = "UPDATE projects SET ";
	$qry_update .= "shortname='"  .$project["shortname"]  ."', ";
	$qry_update .= "fullname='"   .$project["fullname"]   ."', ";
	$qry_update .= "owner='"      .$project["owner"]      ."', ";
	$qry_update .= "description='".$project["description"]."', ";
	$qry_update .= "desc_short='" .$project["desc_short"] ."', ";
	$qry_update .= "progress='"   .$project["progress"]   ."', ";
	$qry_update .= "homepage='"   .$project["homepage"]   ."', ";
	$qry_update .= "license='"    .$project["license"]    ."'  ";
	$qry_update .= "WHERE id='"   .$project_id            ."'  ";
	
	Debug($qry_update, __FILE__, __LINE__);

	$rslt_update = mysql_query ($qry_update) or mysql_qry_error(mysql_error(), $qry_update, __FILE__, __LINE__);
	if (mysql_affected_rows() != 0) {
		/** ERROR */
	}
} else {
	/* Insert a new record */
	$qry_insert = "INSERT INTO projects SET ";
	$qry_insert .= "shortname='"  .$project["shortname"]  ."', ";
	$qry_insert .= "fullname='"   .$project["fullname"]   ."', ";
	$qry_insert .= "owner='"      .$project["owner"]      ."', ";
	$qry_insert .= "description='".$project["description"]."', ";
	$qry_insert .= "desc_short='" .$project["desc_short"] ."', ";
	$qry_insert .= "progress='"   .$project["progress"]   ."', ";
	$qry_insert .= "homepage='"   .$project["homepage"]   ."', ";
	$qry_insert .= "license='"    .$project["license"]    ."'  ";
	
	Debug ($qry_insert, __FILE__, __LINE__);

	$rslt_insert = mysql_query ($qry_insert) or mysql_qry_error(mysql_error(), $qry_insert, __FILE__, __LINE__);
	if (mysql_affected_rows() != 1) {
		/** ERROR */
	}
	$project_id = mysql_insert_id();
}

if ($wizard == 1) {
	Refresh ("ProjectPartList&project_id=".$project_id."&wizard=1");
} else {
	Refresh ("ProjectOverview&project_id=".$project_id);
}
?>

