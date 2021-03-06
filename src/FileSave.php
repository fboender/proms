<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import("project_id" , "GP");
$file_id    = Import("file_id", "GP");
$file       = Import("file", "P");

if (!isset($file_id)) {
	$file_up    = Import("file_up", "F", array());
	if (count($file_up) == 0) {
		Error("You must upload a file", "back");
	}
}

/* Get project shortname */
$qry_shortname = "SELECT shortname FROM projects WHERE id='".$project_id."'";
Debug($qry_shortname, __FILE__, __LINE__);
$rslt_shortname = mysql_query($qry_shortname) or mysql_qry_error(mysql_error(), $qry_shortname, __FILE__, __LINE__);
$row_shortname = mysql_fetch_row($rslt_shortname);
$shortname = $row_shortname[0];

if ($file_id != "") {
	/* Update */
	if (!IsAuthorized($project_id, 'AUTH_FILE_MODIFY')) {
		Error ("Access denied. You're not authorized to modify files", "back");
	}

	$qry_file = "UPDATE files SET ";
	$qry_file .= "title= '"      .$file["title"]      ."', ";
	$qry_file .= "version= '"    .$file["version"]    ."', ";
	$qry_file .= "description= '".$file["description"]."', ";
	$qry_file .= "adddate= '"    .time()              ."', ";
	$qry_file .= "category_id= '".$file["category_id"]."', ";
	$qry_file .= "contenttype= '".$file_up["type"]       ."', ";
	$qry_file .= "project_id= '" .$project_id         ."'  ";
	$qry_file .= "WHERE id='"    .$file_id            ."'  ";

	Debug ($qry_file, __FILE__, __LINE__);
	$rslt_file = mysql_query($qry_file) or mysql_qry_error(mysql_error(), $qry_file, __FILE__, __LINE__);
} else {
	/* New file */
	if (!IsAuthorized($project_id, 'AUTH_FILE_ADD')) {
		Error ("Access denied. You're not authorized to modify files", "back");
	}

	if ($file_up["name"] != "") {
		/* Before PROMS v0.11, creating a new project did not create a
		 * directory in files/ (because files weren't yet supported). Here, we
		 * check and possibly create it if needed. */
		if (!file_exists("files/".$shortname)) {
			umask(0);
			mkdir("files/".$shortname, 0700);
		}

		if (file_exists("files/".$shortname."/".$file_up["name"]) === true) {
			/* Already exists */
			Error("A file with this filename already exists. Please change the filename (NOT the title) and try uploading it again.", "back");
		}

		if (!move_uploaded_file($file_up["tmp_name"], "files/".$shortname."/".$file_up["name"])) {
			Error ("Can't move uploaded file. Presumably the rights are not set correct for the upload directory. Please contact the system administrator about this problem", "exit");
		}
	}

	$qry_file = "INSERT INTO files SET ";
	$qry_file .= "filename='"   .$file_up["name"]       ."', ";
	$qry_file .= "title='"      .$file["title"]      ."', ";
	$qry_file .= "version='"    .$file["version"]    ."', ";
	$qry_file .= "description='".$file["description"]."', ";
	$qry_file .= "adddate='"    .time()              ."', ";
	$qry_file .= "category_id='".$file["category_id"]."', ";
	$qry_file .= "contenttype='".$file_up["type"]       ."', ";
	$qry_file .= "project_id='" .$project_id         ."'  ";

	Debug ($qry_file, __FILE__, __LINE__);

	$rslt_file = mysql_query($qry_file) or mysql_qry_error(mysql_error(), $qry_file, __FILE__, __LINE__);

	Debug($qry_file, __FILE__, __LINE__);
}

Refresh ("FileList&project_id=".$project_id);

?>
