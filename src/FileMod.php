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
	
/* Check if any categories exist */
$qry_cat = "SELECT id FROM file_categories WHERE project_id='".$project_id."'";
$rslt_cat = mysql_query($qry_cat) or mysql_qry_error(mysql_error(), $qry_cat, __FILE__, __LINE__);
if (mysql_num_rows($rslt_cat) == 0) {
	Error ("There are no file categories yet. Please create a category before uploading a file", "back");
}

/* Retrieve file details */
if (isset($file_id) && $file_id != "") {
	if (!IsAuthorized($project_id, AUTH_FILE_MODIFY)) {
		Error ("Access denied. You're not authorized to modify files", "back");
	}
	
	$qry_file = "SELECT * FROM files WHERE id='".$file_id."'";
	$rslt_file = mysql_query($qry_file) or mysql_qry_error(mysql_error(), $qry_file, __FILE__, __LINE__);
	$file = mysql_fetch_assoc($rslt_file);
	if (!$file) {
		Error ("Wrong file ID specified", "back");
	}
}

?>
<table>
<form enctype="multipart/form-data" method="post" action="<?=$PHP_SELF?>">
<?
InputHidden("action", "FileSave");
InputHidden("project_id", $project_id);
if (isset($file_id)) {
	InputHidden ("file_id", $file_id);
}

InputDropDown("Category", "file[category_id]", $file["category_id"], "file_categories", "WHERE project_id=".$project_id);
if (!isset($file_id)) {
	InputFile ("Upload", "file_up");
}
InputText("Title", "file[title]", @$file["title"]);
InputText("Version<sup>1</sup>", "file[version]", @$file["version"]);
InputArea("Description", "file[description]", @$file["description"], 13, 50);

InputSubmit("Save");

?>
</form>
</table>
<p><sup>1</sup> It is not necessary to prefix the version number with i.e. a 'v'.</p>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=FileList&project_id=<?=$project_id?>">&lt; Files</a> &nbsp;
<?

