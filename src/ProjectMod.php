<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id", "GP");
$wizard     = Import ("wizard", "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if ($project_id != "") {
	/* Modify existing project */
	if (!IsProjectOwner($project_id)) {
		Error ("Access denied. You're not the project owner", "back");
	}
} else {
	/* Add new project */
	if (!IsAdmin()) {
		Error ("Access denied. You're not the administrator", "back");
	}
}

if ($project_id != "") {
	$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
	Debug($qry_project, __FILE__, __LINE__);
	$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
	$project = mysql_fetch_assoc ($rslt_project);
}

if ($wizard == 1) {
	?>
	<h1>New project: Step 1</h1>
	<p>Fill in these basic details for the new project.</p>
	<?
}
?>
<table>
<form method="post" action="<?=$PHP_SELF?>">
<?
InputHidden ("action", "ProjectSave");
InputHidden ("wizard", $wizard);
InputHidden ("project_id", $project_id);
InputText ("Short name", "project[shortname]", @$project["shortname"]);
InputText ("Full name", "project[fullname]", @$project["fullname"]);
InputDropDown ("Owner", "project[owner]", @$project["owner"], "accounts");
InputArea ("Description", "project[description]", @$project["description"]);
InputText ("Short description", "project[desc_short]", @$project["desc_short"]);
InputText ("Progress", "project[progress]", @$project["progress"]);
InputText ("Homepage", "project[homepage]", @$project["homepage"]);
InputDropDown ("License", "project[license]", @$project["license"], "licenses");
InputSubmit ("Save");
?>
</form>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<?
	if ($action == "ProjectMod") {
		?><a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project Overview</a> &nbsp;<?
	} else {
		?><a class="nav" href="<?=$PHP_SELF?>?action=ProjectList">&lt; Project List</a> &nbsp;<?
	}
?>
