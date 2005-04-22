<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$wizard     = Import ("wizard"     , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsProjectOwner($project_id)) {
	Error ("Access denied. You're not the project owner", "back");
}

//$qry_parts = "SELECT * FROM project_parts WHERE project_id='".$project_id."'";
$qry_parts = "SELECT pp.*, acc.username as maintainer FROM project_parts pp LEFT JOIN accounts acc ON acc.id = pp.maint_user_id WHERE project_id='".$project_id."'";
Debug ($qry_parts, __FILE__, __LINE__);
$rslt_parts = mysql_query ($qry_parts) or mysql_qry_error(mysql_error(), $qry_parts, __FILE__, __LINE__);

if ($wizard == 1) {
	?>
	<h1>New project: Step 2</h1>
	<p>Now define the different parts which make up your project. These parts will be used when users report bugs and in the todo lists. You should define parts both for the end-user and the development team.</p>
	<?
}

?>
<table>
<tr>
<th class="head">Title</th>
<th class="head">Description</th>
<th class="head">Maintainer</th>
<th class="head">&nbsp;</th>
<th class="head">&nbsp;</th>
</tr>
<?
if (mysql_num_rows($rslt_parts) == 0) {
	?><tr><td>None yet</td></tr><?
}

$row_color = "#d0d0d0";

while ($row_parts = mysql_fetch_assoc($rslt_parts)) {
	?><tr bgcolor="<?=$row_color?>"><?
	?><td><?=$row_parts["title"]?></td><?
	?><td><?=$row_parts["description"]?></td><?
	?><td><?=$row_parts["maintainer"]?></td><?
	?><td><a href="<?=$PHP_SELF?>?action=ProjectPartMod&project_id=<?=$project_id?>&part_id=<?=$row_parts["id"]?>&wizard=<?=$wizard?>"><img src="images/ico_edit.gif" border="0" alt="Modify" title="Modify this part"></a></td><?
	?><td><a href="<?=$PHP_SELF?>?action=ProjectPartDel&project_id=<?=$project_id?>&part_id=<?=$row_parts["id"]?>&wizard=<?=$wizard?>"><img src="images/ico_del.gif" border="0" alt="Delete" title="Delete this part"></a></td><?
	?></tr><?

	if ($row_color == "#d0d0d0") {
		$row_color = "#e0e0e0";
	} else {
		$row_color = "#d0d0d0";
	}
}


?>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project Overview</a> &nbsp;
<a class="action" href="<?=$PHP_SELF?>?action=ProjectPartAdd&project_id=<?=$project_id?>&wizard=<?=$wizard?>">Add part</a> &nbsp;
<?

if ($wizard == 1) {
	?>
	<a class="nav" href="<?=$PHP_SELF?>?action=ForumTopicList&project_id=<?=$project_id?>&wizard=<?=$wizard?>">Step 3 &gt;</a> &nbsp;
	<?
}
