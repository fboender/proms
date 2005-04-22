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

if (isset($part_id)) {
	$qry_part = "SELECT * FROM project_parts WHERE id='".$part_id."'";
	Debug ($qry_part, __FILE__, __LINE__);
	$rslt_part = mysql_query($qry_part) or mysql_qry_error(mysql_error(), $qry_part, __FILE__, __LINE__);
	$part = mysql_fetch_assoc($rslt_part);
}

/* FIXME: The results of this query are never used?! */
$qry_parts = "SELECT id,title FROM project_parts WHERE project_id='".$project_id."'";
Debug ($qry_parts, __FILE__, __LINE__);
$rslt_parts = mysql_query ($qry_parts) or mysql_qry_error(mysql_error(), $qry_parts, __FILE__, __LINE__);

/* Get members of this project */
$qry_members = 
	"SELECT accounts.id, username FROM accounts ".
	"LEFT JOIN project_members AS pm ON pm.account_id = accounts.id ".
	"WHERE pm.project_id='".$project_id."'";
Debug ($qry_members, __FILE__, __LINE__);
$rslt_members = mysql_query($qry_members) or mysql_qry_error(mysql_error(), $qry_members, __FILE__, __LINE__);

/* Modifier */
?>
<table>
<form>
<?
InputHidden("action", "ProjectPartSave");
InputHidden("project_id", $project_id);
InputHidden("wizard", $wizard);
InputHidden("part_id", $part["id"]);
InputText ("Title", "part[title]", $part["title"]);

?><tr valign="top"><td><b>Maintainer</b></td><td><select name="part[maint_user_id]"><?

/* First add the owner to the dropdown */
$qry_owner = "SELECT acc.id, username FROM projects proj LEFT JOIN accounts acc ON acc.id = proj.owner where proj.id='".$project_id."'";
$rslt_owner = mysql_query($qry_owner) or mysql_qry_error(mysql_error(), $qry_owner, __FILE__, __LINE__);
$row_owner = mysql_fetch_assoc($rslt_owner);
if ($row_owner["id"] == $part["maint_user_id"]) {
	$selected = "selected";
} else {
	$selected = "";
}
?><option value="<?=$row_owner["id"]?>" <?=$selected?>><?=$row_owner["username"]?></option><?

/* Now add the members */
while ($row_members = mysql_fetch_assoc($rslt_members)) {
	if ($row_members["id"] != $row_owner["id"]) { /* Skip project owner */
		if ($row_members["id"] == $part["maint_user_id"]) {
			$selected = "selected";
		} else {
			$selected = "";
		}

		?><option value="<?=$row_members["id"]?>" <?=$selected?> ><?=$row_members["username"]?></option><?
	}
}
?></select></td></tr><?
InputArea ("Description", "part[description]", $part["description"]);
InputSubmit ("Save");
?>
</form>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectPartList&project_id=<?=$project_id?>&wizard=<?=$wizard?>">&lt; Project Parts</a> &nbsp;
<?

