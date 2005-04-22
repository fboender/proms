<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$bug_id     = Import ("bug_id"     , "GP");

/* Retrieve project details */
if (isset($project_id)) {
	$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
	$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
	$project = mysql_fetch_assoc($rslt_project);
}

$qry_bug = "SELECT * FROM bugs WHERE id='".$bug_id."'";
Debug ($qry_bug, __FILE__, __LINE__);
$rslt_bug = mysql_query ($qry_bug) or mysql_qry_error(mysql_error(), $qry_bug, __FILE__, __LINE__);
$row_bug = mysql_fetch_assoc($rslt_bug);

$severities = ReadLookup ("bugs_severity", "title");
$statuses = ReadLookup ("bugs_statuses", "name");
$projectparts = ReadLookup ("project_parts", "title", "WHERE project_id=".$row_bug["project_id"]);
$users = ReadLookup ("accounts", "username");
$users[0] = "Anonymous";

/* Check if this bug is being discussed */
$bug["bugtracker"] = FALSE;
$bug["discussed"] = "This bug is <b>not</b> being discussed.";

$qry_discuss = "SELECT id FROM forum WHERE project_id='".$project_id."' AND subject LIKE 'Bug #".str_repeat("0", 4-strlen($row_bug["bug_nr"])).$row_bug["bug_nr"]."%'";
Debug ($qry_discuss, __FILE__, __LINE__);
$rslt_discuss = mysql_query($qry_discuss) or mysql_qry_error(mysql_error(), $qry_discuss, __FILE__, __LINE__);
if (mysql_num_rows($rslt_discuss) > 0) {
	$row_discuss = mysql_fetch_assoc($rslt_discuss);
	
	$qry_bugtracker = "SELECT id FROM forum WHERE reply_to='".$row_discuss["id"]."' AND subject LIKE 'Bug tracker'";
	$rslt_bugtracker = mysql_query ($qry_bugtracker) or mysql_qry_error (mysql_error(), $qry_bugtracker, __FILE__, __LINE__);
	if (mysql_num_rows($rslt_bugtracker) > 0) {
		$bug["bugtracker"] = TRUE;
	}

	$qry_replies = "SELECT id FROM forum WHERE reply_to='".$row_discuss["id"]."' AND subject NOT LIKE 'Bug tracker'";
	$rslt_replies = mysql_query ($qry_replies) or mysql_qry_error (mysql_error(), $qry_replies, __FILE__, __LINE__);
	if (mysql_num_rows($rslt_replies) > 0) {
		$bug["discussed"] = "A discussion for this bug has been started.";
	}
} 

$qry_bugtrack = "SELECT id FROM forum WHERE project_id='".$project_id."' AND subject LIKE 'Bug #".str_repeat("0", 4-strlen($row_bug["bug_nr"])).$row_bug["bug_nr"]."%'";
?>
<br>

<font size="+1">Bug #<?=str_repeat("0", 4-strlen($row_bug["bug_nr"])).$row_bug["bug_nr"]?> : <?=nl2br(htmlentities($row_bug["subject"]))?><br></font>
<b>By user : </b> <a href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$row_bug["user_id"]?>"><?=$users[$row_bug["user_id"]]?></a>

<br><br>
<table>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Status :</th>       <td><?=$statuses[$row_bug["status"]]?></td></tr>
<tr valign="top"><th class="head">Severity :</th>     <td><?=$severities[$row_bug["severity"]]?></td></tr>
<tr valign="top"><th class="head">Part :</th>         <td><?=$projectparts[$row_bug["part"]]?></td></tr>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Report date :</th>  <td><?=date("d M Y H:i", $row_bug["reportdate"])?></td></tr>
<tr valign="top"><th class="head"><nobr>Last mod date :</nobr></th><td><?=date("d M Y H:i", $row_bug["lastmoddate"])?></td></tr>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Description :</th>  <td bgcolor="#e0e0e0"><? if ($row_bug["description"]  == "") { ?><i>N/A</i><? } else { ?><?=nl2br(htmlentities($row_bug["description"] ))?><? } ?></td></tr>
<tr valign="top"><th class="head">Reproduction :</th> <td>                  <? if ($row_bug["reproduction"] == "") { ?><i>N/A</i><? } else { ?><?=nl2br(htmlentities($row_bug["reproduction"]))?><? } ?></td></tr>
<tr valign="top"><th class="head">Software :</th>     <td bgcolor="#e0e0e0"><? if ($row_bug["software"]     == "") { ?><i>N/A</i><? } else { ?><?=nl2br(htmlentities($row_bug["software"]    ))?><? } ?></td></tr>
<tr valign="top"><th class="head">Patch :</th>        <td>                  <? if ($row_bug["patch"]        == "") { ?><i>N/A</i><? } else { ?><?=nl2br(htmlentities($row_bug["patch"]       ))?><? } ?></td></tr>
<tr valign="top"><th></th><td><br><?=$bug["discussed"]?></td></tr>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?>">&lt; Bug List</a> &nbsp;
<?
if ($bug["bugtracker"] == TRUE) {
	?><a class="action" href="<?=$PHP_SELF?>?action=BugDiscuss&project_id=<?=$project_id?>&bug_id=<?=$row_bug["id"]?>">Bug tracker</a> &nbsp;<?
}
?>
<a class="action" href="<?=$PHP_SELF?>?action=BugDiscuss&project_id=<?=$project_id?>&bug_id=<?=$row_bug["id"]?>">Discuss this bug</a> &nbsp;
<a class="action" href="<?=$PHP_SELF?>?action=BugAdd&project_id=<?=$project_id?>">Report new bug</a> &nbsp;

<?
if (IsAuthorized($project_id, 'AUTH_BUG_MODIFY')) {
	?>
	<a class="action" href="<?=$PHP_SELF?>?action=BugMod&project_id=<?=$project_id?>&bug_id=<?=$row_bug["id"]?>">Modify bug</a>
	<?
}
?>
