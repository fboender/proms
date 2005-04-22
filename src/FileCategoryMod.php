<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$file_cat_id = Import ("file_cat_id", "GP");

/* Security check */

/* Retrieve file category details */
if (isset($file_cat_id)) {
	$qry_cat_file = "SELECT * FROM file_categories WHERE id='".$file_cat_id."'";
	$rslt_cat_file = mysql_query($qry_cat_file) or mysql_qry_error(mysql_error(), $qry_cat_file, __FILE__, __LINE__);
	$file_cat = mysql_fetch_assoc($rslt_cat_file);
	if (!$file_cat) {
		Error ("Wrong category ID specified", "back");
	}
}

?>
<table>
<form enctype="multipart/form-data" method="post" action="<?=$PHP_SELF?>">
<?
InputHidden("action", "FileCategorySave");
InputHidden("project_id", $project_id);
if (isset($file_cat_id)) {
	InputHidden ("file_cat[id]", $file_cat_id);
}

InputText("Title", "file_cat[title]", @$file_cat["title"]);
InputArea("Description", "file_cat[description]", @$file_cat["description"], 13, 50);

InputSubmit("Save");

?>
</form>
</table>
<?

?>
<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=FileList&project_id=<?=$project_id?>">&lt; Files</a> &nbsp;
<?

