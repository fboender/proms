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
	/* Update an existing project */
	
	/* Get the old shortname so we can move the old files/... directory */
	$qry_shortname = "SELECT shortname FROM projects WHERE id='".$project_id."'";
	
	Debug($qry_shortname, __FILE__, __LINE__);
	
	$rslt_shortname = mysql_query($qry_shortname) or mysql_qry_error(mysql_error(), $qry_shortname, __FILE__, __LINE__);
	$row_shortname = mysql_fetch_row($rslt_shortname);
	
	$shortname = $row_shortname[0];
	
	/* Update an existing record */
	$qry_update = "UPDATE projects SET ";
	$qry_update .= "shortname='"  .basename($project["shortname"])  ."', ";
	$qry_update .= "fullname='"   .$project["fullname"]   ."', ";
	$qry_update .= "owner='"      .$project["owner"]      ."', ";
	$qry_update .= "description='".$project["description"]."', ";
	$qry_update .= "desc_short='" .$project["desc_short"] ."', ";
	$qry_update .= "progress='"   .$project["progress"]   ."', ";
	$qry_update .= "homepage='"   .$project["homepage"]   ."', ";
	$qry_update .= "license='"    .$project["license"]    ."', ";
	$qry_update .= "private='"    .$project["private"]    ."'  ";
	$qry_update .= "WHERE id='"   .$project_id            ."'  ";
	
	Debug($qry_update, __FILE__, __LINE__);

	$rslt_update = mysql_query ($qry_update) or mysql_qry_error(mysql_error(), $qry_update, __FILE__, __LINE__);
	if (mysql_affected_rows() != 0) {
		/** ERROR */
	}

	/* Create directory for the project in the files/ dir */
	if (basename($project["shortname"]) != $shortname) {
		if (!rename("files/".$shortname, "files/".basename($project["shortname"]))) {
			Error ("Can't move the directory for keeping the project's files. Please contact the system administrator about this problem");
		}
	}
} else {
	/* Create a new project */

	/* Check if project already exists */
	$qry_shortname = "SELECT shortname FROM projects WHERE shortname='".$project["shortname"]."'";
	Debug($qry_shortname, __FILE__, __LINE__);
	$rslt_shortname = mysql_query($qry_shortname) or mysql_qry_error(mysql_error(), $qry_shortname, __FILE__, __LINE__);
	if (mysql_num_rows($rslt_shortname) > 0) {
		Error("A project with this short name already exists. Please select another short name", "back");
	}

	/* Insert a new record */
	$qry_insert = "INSERT INTO projects SET ";
	$qry_insert .= "shortname='"  .basename($project["shortname"])  ."', ";
	$qry_insert .= "fullname='"   .$project["fullname"]   ."', ";
	$qry_insert .= "owner='"      .$project["owner"]      ."', ";
	$qry_insert .= "description='".$project["description"]."', ";
	$qry_insert .= "desc_short='" .$project["desc_short"] ."', ";
	$qry_insert .= "progress='"   .$project["progress"]   ."', ";
	$qry_insert .= "homepage='"   .$project["homepage"]   ."', ";
	$qry_insert .= "license='"    .$project["license"]    ."', ";
	$qry_insert .= "private='"    .$project["private"]    ."'  ";
	
	Debug ($qry_insert, __FILE__, __LINE__);

	$rslt_insert = mysql_query ($qry_insert) or mysql_qry_error(mysql_error(), $qry_insert, __FILE__, __LINE__);
	if (mysql_affected_rows() != 1) {
		/** ERROR */
	}
	$project_id = mysql_insert_id();

	/* Create directory for the project in the files/ dir. We can't use umask
	 * to directly set the rights because of problems with threaded servers.
	 * This means the directory _might_ be vulnerable for a _very_ short time
	 * between creation and chmodding. Nothing to worry about though. */
	if (!mkdir("files/".basename($project["shortname"]))) {
		Error ("Can't create a directory for keeping the project's files in. Please contact the system administrator about this problem");
	}
	if (!chmod("files/".basename($project["shortname"]), 0700)) {
		Error ("Couldn't set the permissions on the directory for keeping the project's files. Please contact the system administrator about this problem");
	}
}

if ($wizard == 1) {
	Refresh ("ProjectPartList&project_id=".$project_id."&wizard=1");
} else {
	Refresh ("ProjectOverview&project_id=".$project_id);
}
?>

