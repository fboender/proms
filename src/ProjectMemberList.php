<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id", "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in.", "back");
}
if (!IsAuthorized($project_id, 'AUTH_PROJECTMEMBERS_MODIFY')) {
	Error ("Access denied. You're not authorized to modify bugs", "back");
}

/* Get project members */
$qry_projectmembers = "SELECT * FROM project_members WHERE project_id = '".$project_id."'";
Debug ($qry_projectmembers, __FILE__, __LINE__);
$rslt_projectmembers = mysql_query ($qry_projectmembers) or mysql_qry_error(mysql_error(), $qry_projectmembers, __FILE__, __LINE__);

$accounts = ReadLookup("accounts", "username");

?>
<table cellspacing="1" cellpadding="3">
<tr valign="top">
<th class="head">User</th>
<th class="head">Rights</th>
<th class="head">&nbsp;</th>
</tr>
<?

$row_color = "#d0d0d0";

if (mysql_num_rows($rslt_projectmembers) == 0) {
	?><tr valign="top" bgcolor="<?=$row_color?>"><td colspan="3">No project members</td></tr><?
} else {
	while ($projectmember = mysql_fetch_assoc($rslt_projectmembers)) {
		/* Determine rights */
		$rights = "";
		if ($projectmember["rights"] & AUTH_PROJECT_MODIFY) { $rights .= "Project Modify, "; }
		if ($projectmember["rights"] & AUTH_PROJECTMEMBERS_MODIFY) { $rights .= "Project members Modify, "; }
		if ($projectmember["rights"] & AUTH_TODO_CREATE) { $rights .= "Todo Create, "; }
		if ($projectmember["rights"] & AUTH_TODO_MODIFY) { $rights .= "Todo Modify, "; }
		if ($projectmember["rights"] & AUTH_BUG_MODIFY) { $rights .= "Bug Modify, "; }
		if ($projectmember["rights"] & AUTH_FORUM_MODIFY) { $rights .= "Forum Modify, "; }
		if ($projectmember["rights"] & AUTH_RELEASE_ADD) { $rights .= "Release Add, "; }
		if ($projectmember["rights"] & AUTH_RELEASE_MODIFY) { $rights .= "Release Modify, "; }
		if ($projectmember["rights"] & AUTH_FILE_ADD) { $rights .= "File Add, "; }
		if ($projectmember["rights"] & AUTH_FILE_MODIFY) { $rights .= "File Modify, "; }
		if ($projectmember["rights"] & AUTH_FILE_OVERWRITE) { $rights .= "File Overwrite, "; }
		$projectmember["rights"] = $rights;

		?>
		<tr valign="top" bgcolor="<?=$row_color?>">
		<td><a href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$projectmember["account_id"]?>"><?=$accounts[$projectmember["account_id"]]?></a></td>
		<td><?=$projectmember["rights"]?></td>
		<td>
			<a href="<?=$PHP_SELF?>?action=ProjectMemberMod&project_id=<?=$project_id?>&projectmember_id=<?=$projectmember["id"]?>"><img src="images/ico_edit.gif" border="0" alt="edit" title="Edit this member"></a>
			<a href="<?=$PHP_SELF?>?action=ProjectMemberDel&project_id=<?=$project_id?>&projectmember_id=<?=$projectmember["id"]?>"><img src="images/ico_del.gif" border="0" alt="delete" title="Delete this member"></a>
		</td>
		</tr>
		<?
		
		if ($row_color == "#d0d0d0") {
			$row_color = "#e0e0e0";
		} else {
			$row_color = "#d0d0d0";
		}
	}
}
?>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project Overview</a> &nbsp;
<a class="action" href="<?=$PHP_SELF?>?action=ProjectMemberMod&project_id=<?=$project_id?>">Member add</a> &nbsp;
