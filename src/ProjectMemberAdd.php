<? 
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import("project_id", "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsAuthorized($project_id, AUTH_PROJECTMEMBERS_MODIFY)) {
	Error("Access denied. You're not authorized to modify project members", "back");
}

$qry_projectmember = "SELECT * FROM project_members WHERE id='".$project_id."'";
$rslt_projectmember = mysql_query ($qry_projectmember) or mysql_qry_error(mysql_error(), $qry_projectmember, __FILE__, __LINE__);
$projectmember = mysql_fetch_assoc($rslt_projectmember);

?>
<table>
<form method="post" action="<?=$PHP_SELF?>">
	<?
	InputHidden   (        "action"     , "ProjectMemberSave");
	InputHidden   (        "project_id" , $project_id);
	InputDropDown ("User", "projectmember[account_id]" , "" , "accounts");

	/* Custom select box */
	?>
	<tr valign="top">
	<td><b>Rights</b></td>
	<td>
	<select name="projectmember[rights][]" size="8" multiple>
		<optgroup label="Project">
			<option value="<? echo AUTH_PROJECT_MODIFY; ?>">Modify</option>
		</optgroup>
		<optgroup label="Project members">
			<option value="<? echo AUTH_PROJECTMEMBERS_MODIFY; ?>">Modify</option>
		</optgroup>
		<optgroup label="Todo's">
			<option value="<? echo AUTH_TODO_CREATE; ?>">Create</option>
			<option value="<? echo AUTH_TODO_MODIFY; ?>">Modify</option>
		</optgroup>
		<optgroup label="Bug's">
			<option value="<? echo AUTH_BUG_MODIFY; ?>">Modify</option>
		</optgroup>
		<optgroup label="Forum">
			<option value="<? echo AUTH_FORUM_MODIFY; ?>">Modify</option>
		</optgroup>
		<optgroup label="Releases">
			<option value="<? echo AUTH_RELEASE_ADD; ?>">Add</option>
			<option value="<? echo AUTH_RELEASE_MODIFY; ?>">Modify</option>
		</optgroup>
	</select>
	</td>
	</tr>
	<?

	InputSubmit ("Save");
	?>
</form>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?>">&lt; Bug list</a> &nbsp;
