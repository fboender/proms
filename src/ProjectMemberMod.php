<? 
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id       = Import ("project_id"       , "GP");
$projectmember_id = Import ("projectmember_id" , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsAuthorized($project_id, AUTH_PROJECTMEMBERS_MODIFY)) {
	Error("Access denied. You're not authorized to modify project members", "back");
}

if ($projectmember_id != "") {
	$qry_projectmember = "SELECT * FROM project_members WHERE id='".$projectmember_id."'";
	$rslt_projectmember = mysql_query ($qry_projectmember) or mysql_qry_error(mysql_error(), $qry_projectmember, __FILE__, __LINE__);
	$projectmember = mysql_fetch_assoc($rslt_projectmember);

	/* Create SELECTED assoc array for selectbox */
	if ($projectmember["rights"] & AUTH_PROJECT_MODIFY) { $select["AUTH_PROJECT_MODIFY"] = "SELECTED"; }
	if ($projectmember["rights"] & AUTH_PROJECTMEMBERS_MODIFY) { $select["AUTH_PROJECTMEMBERS_MODIFY"] = "SELECTED"; }
	if ($projectmember["rights"] & AUTH_TODO_CREATE) { $select["AUTH_TODO_CREATE"] = "SELECTED";  }
	if ($projectmember["rights"] & AUTH_TODO_MODIFY) { $select["AUTH_TODO_MODIFY"] = "SELECTED";  }
	if ($projectmember["rights"] & AUTH_BUG_MODIFY) { $select["AUTH_BUG_MODIFY"] = "SELECTED"; }
	if ($projectmember["rights"] & AUTH_FORUM_MODIFY) { $select["AUTH_FORUM_MODIFY"] = "SELECTED";  }
	if ($projectmember["rights"] & AUTH_RELEASE_ADD) { $select["AUTH_RELEASE_ADD"] = "SELECTED";  }
	if ($projectmember["rights"] & AUTH_RELEASE_MODIFY) { $select["AUTH_RELEASE_MODIFY"] = "SELECTED";  }
	if ($projectmember["rights"] & AUTH_FILE_ADD) { $select["AUTH_FILE_ADD"] = "SELECTED";  }
	if ($projectmember["rights"] & AUTH_FILE_MODIFY) { $select["AUTH_FILE_MODIFY"] = "SELECTED";  }
	if ($projectmember["rights"] & AUTH_FILE_OVERWRITE) { $select["AUTH_FILE_OVERWRITE"] = "SELECTED";  }
} else {
	/* Build additional query so only non-members are shown */
	$qry_members = "SELECT * FROM project_members WHERE project_id='".$project_id."'";
	$rslt_members = mysql_query ($qry_members) or mysql_qry_error(mysql_error(), $qry_members, __FILE__, __LINE__);
	$additional_query = "WHERE ";
	while ($member = mysql_fetch_assoc($rslt_members)) {
		$additional_query .= " id != ".$member["account_id"]." AND ";
	}
	$additional_query = substr($additional_query, 0, -4);
	Debug ($additional_query, __FILE__, __LINE__);
}

$accounts = ReadLookup ("accounts", "username");

?>
<table>
<form method="post" action="<?=$PHP_SELF?>">
	<?
	InputHidden   ("action"           , "ProjectMemberSave");
	InputHidden   ("project_id"       , $project_id);
	InputHidden   ("projectmember[id]", $projectmember["id"]);
	if ($projectmember_id == "") {
		InputDropDown ("User"         , "projectmember[account_id]"   , $projectmember["account_id"], "accounts", $additional_query);
	} else {
		?><tr><td><b>User</b></td><td><?=$accounts[$projectmember["account_id"]]?></td></tr><?
		InputHidden ("projectmember[account_id]", $projectmember["account_id"]);
	}

	/* Custom select box */
	?>
	<tr valign="top">
	<td><b>Rights</b></td>
	<td>
	<select name="projectmember[rights][]" size="8" multiple>
		<optgroup label="Project">
			<option value="<? echo AUTH_PROJECT_MODIFY; ?>" <?=$select["AUTH_PROJECT_MODIFY"]?>>Modify</option>
		</optgroup>
		<optgroup label="Project members">
			<option value="<? echo AUTH_PROJECTMEMBERS_MODIFY; ?>" <?=$select["AUTH_PROJECTMEMBERS_MODIFY"]?>>Modify</option>
		</optgroup>
		<optgroup label="Todo's">
			<option value="<? echo AUTH_TODO_CREATE; ?>" <?=$select["AUTH_TODO_CREATE"]?>>Create</option>
			<option value="<? echo AUTH_TODO_MODIFY; ?>" <?=$select["AUTH_TODO_MODIFY"]?>>Modify</option>
		</optgroup>
		<optgroup label="Bug's">
			<option value="<? echo AUTH_BUG_MODIFY; ?>" <?=$select["AUTH_BUG_MODIFY"]?>>Modify</option>
		</optgroup>
		<optgroup label="Forum">
			<option value="<? echo AUTH_FORUM_MODIFY; ?>" <?=$select["AUTH_FORUM_MODIFY"]?>>Modify</option>
		</optgroup>
		<optgroup label="Releases">
			<option value="<? echo AUTH_RELEASE_ADD; ?>" <?=$select["AUTH_RELEASE_ADD"]?>>Add</option>
			<option value="<? echo AUTH_RELEASE_MODIFY; ?>" <?=$select["AUTH_RELEASE_MODIFY"]?>>Modify</option>
		</optgroup>
		<optgroup label="Files">
			<option value="<? echo AUTH_FILE_ADD; ?>" <?=$select["AUTH_FILE_ADD"]?>>Add</option>
			<option value="<? echo AUTH_FILE_MODIFY; ?>" <?=$select["AUTH_FILE_MODIFY"]?>>Modify</option>
			<option value="<? echo AUTH_FILE_OVERWRITE; ?>" <?=$select["AUTH_FILE_OVERWRITE"]?>>Overwrite</option>
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
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectMemberList&project_id=<?=$project_id?>">&lt; Project members list</a> &nbsp;
