<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$file_id = Import ("file_id", "GP");

/* Security check */
if (!IsAuthorized($project_id, 'AUTH_FILE_MODIFY') && !IsAuthorized($project_id, 'AUTH_FILE_ADD')) {
	Error ("Access denied. You are not authorized to add or modify files.", "back");
}
	
/* Get project shortname */
$qry_shortname = "SELECT shortname FROM projects WHERE id='".$project_id."'";
Debug($qry_shortname, __FILE__, __LINE__);
$rslt_shortname = mysql_query($qry_shortname) or mysql_qry_error(mysql_error(), $qry_shortname, __FILE__, __LINE__);
$row_shortname = mysql_fetch_row($rslt_shortname);
$shortname = $row_shortname[0];

/* Get file information */
$qry_file = "SELECT * FROM files WHERE id='".$file_id."'";
$rslt_file = mysql_query($qry_file) or mysql_qry_error(mysql_error(), $qry_file, __FILE__, __LINE__);
$row_file = mysql_fetch_assoc($rslt_file);

if (!unlink ("files/".$shortname."/".$row_file["filename"])) {
	Error("Couldn't remove the file. Please contact the administrator", "exit");
}

$qry_del = "DELETE FROM files WHERE id='".$file_id."'";
$rslt_del = mysql_query($qry_del) or mysql_qry_error(mysql_error(), $qry_del, __FILE__, __LINE__);

Refresh ("FileList&project_id=".$project_id);

?>
