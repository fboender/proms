<? 
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$bug_id     = Import ("bug_id"     , "GP");
$bug        = Import ("bug"        , "GP");

/* Security check */
if (!IsLoggedIn() && BUGADD_ALLOW_ANONYMOUS != true) {
	Error ("Access denied. Must be logged in", "back");
}
if ($bug_id != "" && !IsAuthorized($project_id, AUTH_BUG_MOD)) {
	Error ("Access denied. You're not authorized to modify bugs", "back");
}

/* Retrieve project details */
$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
$project = mysql_fetch_assoc($rslt_project);
	
/* Retrieve project release versions */
$qry_versions = "SELECT id, version FROM project_releases WHERE project_id='".$project_id."' AND date < '".time()."' ORDER BY date DESC";
$rslt_versions = mysql_query($qry_versions) or mysql_qry_error(mysql_error(), $qry_versions, __FILE__, __LINE__);
Debug($qry_versions, __FILE__, __LINE__);

if ($bug_id != "") {
	/* Retrieve bug information */
	$qry_bug = "SELECT * FROM bugs WHERE id='".$bug_id."'";
	Debug($qry_bug, __FILE__, __LINE__);
	$rslt_bug = mysql_query ($qry_bug) or mysql_qry_error(mysql_error(), $qry_bug, __FILE__, __LINE__);
	$bug = mysql_fetch_assoc($rslt_bug);
}

if ($bug_id == "") {
	?>
	<br>
	You may first want to read a document about <a href="http://www.chiark.greenend.org.uk/~sgtatham/bugs.html">How To Report Bugs Effectively</a><br><br>
	<?
}

?>
<form method="post" action="<?=$PHP_SELF?>">
<table>
<?
InputHidden("action", "BugSave");
InputHidden("project_id", $project_id);
InputHidden("bug[user_id]", $bug["user_id"]);
InputHidden("bug_id", $bug["id"]);

?>
<tr>
	<td><b>Version</b></td>
	<td>
		<select name="bug[version]">
			<?
			while ($version = mysql_fetch_assoc($rslt_versions)) {
				if ($bug["version"] == $version["version"]) { 
					$select = "SELECTED";
				} else {
					$select = "";
				}
				?><option value="<?=$version["version"]?>" <?=$select?>><?=$version["version"]?><?
			}
			?>
		</select>
	</td>
</tr>

<?
InputDropDown("Part", "bug[part]", $bug["part"], "project_parts", "WHERE project_id=".$project_id);
InputDropDown("Severity", "bug[severity]", $bug["severity"], "bugs_severity");
if ($bug_id != "" || IsAuthorized($project_id, 'AUTH_BUG_MODIFY')) {
	InputDropDown("Status", "bug[status]", $bug["status"], "bugs_statuses");
}
InputText("Subject", "bug[subject]", $bug["subject"]);
?>
</table>
<div class="separator">&nbsp;</div>
<table>
	<tr valign="top">
	<td>
		<table>
		<?
		InputArea("Description", "bug[description]", $bug["description"], "13", "50");
		InputArea("Patch", "bug[patch]", $bug["patch"], "13", "50");
		InputSubmit("Save");
		?>
		</table>
	</td>
	<td>
		<table>
		<?
		InputArea("Reproduction", "bug[reproduction]", $bug["reproduction"], "13", "50");
		InputArea("Software", "bug[software]", $bug["software"], "13", "50");
		?>
		</table>
	</td>
	</tr>
</table>
</form>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?>">&lt; Bug list</a> &nbsp;
