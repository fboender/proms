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

if ($wizard == 1) {
	?>
	<h1>New project: Step 3</h1>
	<p>You should now define the various topics for the discussion forum. People will have to post their message's under one of these topics which should keep the discussion forum a little bit more organised.</p>
	<?
}

/* get main threads */
$qry_topics = "SELECT id,subject FROM forum WHERE project_id='".$project_id."' AND reply_to=0";
Debug ($qry_topics, __FILE__, __LINE__);
$rslt_topics = mysql_query($qry_topics) or mysql_qry_error(mysql_error(), $qry_topics, __FILE__, __LINE__);

if (mysql_num_rows($rslt_topics) == 0) {
	?><p><i>No discussion topics have been created yet</i></p><?
} else {
	?>
	<table>
		<tr><th class="head">Discussion topics</th><th class="head">&nbsp;</th></tr>
		<?
		$row_color = "#d0d0d0";
		while ($row_topics = mysql_fetch_assoc($rslt_topics)) {
			?>
			<tr bgcolor="<?=$row_color?>">
			<td><a href="<?=$PHP_SELF?>?action=ForumTopicMod&project_id=<?=$project_id?>&topic_id=<?=$row_topics["id"]?>"><?=htmlentities($row_topics["subject"])?></a></td>
			<td><a href="<?=$PHP_SELF?>?action=ForumTopicDel&project_id=<?=$project_id?>&topic_id=<?=$row_topics["id"]?>"><img src="images/ico_del.gif" border="0" alt="delete" title="Delete this topic"></a></td>
			</tr>
			<?
			if ($row_color == "#d0d0d0") {
				$row_color = "#e0e0e0";
			} else {
				$row_color = "#d0d0d0";
			}
		}
		?>
	</table>
	<?
}
?>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<?
	if ($wizard == 1) {
		?><a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project Overview</a> &nbsp;<?
	} else {
		?><a class="nav" href="<?=$PHP_SELF?>?action=ForumView&project_id=<?=$project_id?>">&lt; Forums</a> &nbsp;<?
	}
?>
<a class="action" href="<?=$PHP_SELF?>?action=ForumTopicAdd&project_id=<?=$project_id?>&wizard=<?=$wizard?>">New topic</a> &nbsp;
<?
if ($wizard == 1) {
	?>
	<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">Finish &gt;</a> &nbsp;
	<?
}
?>
