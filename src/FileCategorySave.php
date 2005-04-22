<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$file_cat = Import("file_cat", "GP");

if ($file_cat["id"] != "") {
	/* Update */
	$qry_update = "UPDATE file_categories SET title='".$file_cat["title"]."', description='".$file_cat["description"]."' WHERE id='".$file_cat["id"]."'";
	Debug($qry_update, __FILE__, __LINE__);
	$rslt_update = mysql_query($qry_update) or mysql_qry_error(mysql_error(), $qry_update, __FILE__, __LINE__);

} else {
	/* Insert new */
	$qry_insert = "INSERT INTO file_categories SET title='".$file_cat["title"]."', description='".$file_cat["description"]."', project_id='".$project_id."'";
	Debug($qry_insert, __FILE__, __LINE__);
	$rslt_insert = mysql_query($qry_insert) or mysql_qry_error(mysql_error(), $qry_insert, __FILE__, __LINE__);
}

Refresh ("FileList&project_id=".$project_id);

?>
