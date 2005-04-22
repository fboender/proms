<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$topic_id   = Import ("topic_id"   , "GP");
$thread_id  = Import ("thread_id"  , "GP");
$project_id = Import ("project_id" , "GP");


/* FIXME: Clean this file up. The three if()'s can be made smaller/unified */
	$users = ReadLookup ("accounts", "username");
	
	if (!isset($topic_id) && !isset($thread_id)) {
		/* get main threads */
		$qry_topics = "SELECT id,subject,contents FROM forum WHERE project_id='".$project_id."' AND reply_to=0";
		Debug ($qry_topics, __FILE__, __LINE__);
		$rslt_topics = mysql_query($qry_topics) or mysql_qry_error(mysql_error(), $qry_topics, __FILE__, __LINE__);

		if (mysql_num_rows($rslt_topics) == 0) {
			?><p><i>No forum topics are available</i></p><?
		} else {
			?>
			<p>The following discussion topics are available</p>

			<?
			while ($row_topics = mysql_fetch_assoc($rslt_topics)) {
				if ($row_topics["contents"] == "") {
					$row_topics["contents"] = "No description for this topic";
				}
				?>
				<b><?=$row_topics["subject"]?></b>
				<?
				if (IsAuthorized($project_id, AUTH_FORUM_MODIFY)) {
					?><a href="<?=$PHP_SELF?>?action=ForumTopicMod&project_id=<?=$project_id?>&topic_id=<?=$row_topics["id"]?>"><img src="images/ico_edit.gif" alt="Edit" title="Edit this category's details" border="0"></a><?
				}
				?>
				<br><br>
				<div style="width: 50%; margin-left: 25px; padding: 10px; background-color: #F0F0F0;">
					<?=bbcode($row_topics["contents"])?>
				</div>
				<div style="margin-left: 25px; padding: 10px;">
					<a href="<?=$PHP_SELF?>?action=ForumView&project_id=<?=$project_id?>&topic_id=<?=$row_topics["id"]?>">View posts</a>
				</div>
				
				<?
			}
		}

		?>
		<div class="actionseparator">&nbsp;</div>
		&nbsp;
		<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project Overview</a> &nbsp;
		<?
		if (IsAuthorized($project_id, 'AUTH_FORUM_MODIFY')) {
			?>
			<a class="action" href="<?=$PHP_SELF?>?action=ForumTopicList&project_id=<?=$project_id?>">Edit topics</a> &nbsp;
			<?
		}
		
	} else
	if (!isset($thread_id)) {
		/* Get all threads belonging under thread_id */
		$qry_threads = "SELECT * FROM forum WHERE reply_to='".$topic_id."' ORDER BY lastpostdate DESC";
		Debug($qry_threads, __FILE__, __LINE__);
		$rslt_threads = mysql_query($qry_threads) or mysql_qry_error(mysql_error(), $qry_threads, __FILE__, __LINE__);

		?>
		<table>
			<tr>
				<th class="head">Subject</th>
				<th class="head">By user</th>
				<th class="head">Date</th>
				<th class="head">Last post</th>
			</tr>
			<?
			$row_color = "#d0d0d0";
			if (mysql_num_rows($rslt_threads) == 0) {
				?>
				<tr bgcolor="<?=$row_color?>"><td colspan="4">No posts</td></tr>
				<?
			}
			while ($row_threads = mysql_fetch_assoc($rslt_threads)) {
				?>
				<tr bgcolor="<?=$row_color?>">
				<td><a href="<?=$PHP_SELF?>?action=ForumView&project_id=<?=$project_id?>&topic_id=<?=$topic_id?>&thread_id=<?=$row_threads["id"]?>"><?=htmlentities($row_threads["subject"])?></a></td>
				<?
					if ($row_threads["user_id"] == 0) {
						?><td>Anonymous</td><?
					} else
					if ($users[$row_threads["user_id"]] == "") {
						?><td>Unknown</td><?
					} else {
						?><td><a href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$row_threads["user_id"]?>"><?=$users[$row_threads["user_id"]]?></a></td><?
					}
				?>
				<td><?=date("d M Y H:i", $row_threads["postdate"])?></td>
				<td><?=date("d M Y H:i", $row_threads["lastpostdate"])?></td>
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
		
		<div class="actionseparator">&nbsp;</div>
		&nbsp;
		<a class="nav" href="<?=$PHP_SELF?>?action=ForumView&project_id=<?=$project_id?>">&lt; Discussion topics</a> &nbsp;
		<?
		if (IsLoggedIn()) {
			?><a class="action" href="<?=$PHP_SELF?>?action=ForumReply&project_id=<?=$project_id?>&topic_id=<?=$topic_id?>">Start new thread</a> <?
		}
	} else {
		$qry_thread = "SELECT * FROM forum WHERE id='".$thread_id."'";
		Debug($qry_thread, __FILE__, __LINE__);
		$rslt_thread = mysql_query($qry_thread) or mysql_qry_error(mysql_error(), $qry_thread, __FILE__, __LINE__);
		$row_thread = mysql_fetch_assoc($rslt_thread);
		
		$qry_replies = "SELECT * FROM forum WHERE reply_to='".$thread_id."'";
		Debug($qry_replies, __FILE__, __LINE__);
		$rslt_replies = mysql_query($qry_replies) or mysql_qry_error(mysql_error(), $qry_replies, __FILE__, __LINE__);

		$row_color = "#d0d0d0";
		?>
		<table>
			<tr>
				<th class="head"><?=htmlentities($row_thread["subject"])?></th>
				<th class="head" align="right">User: <i>
					<?
					if ($row_thread["user_id"] != 0 && !empty($row_thread["user_id"])) {
						?><a class="action" href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$row_thread["user_id"]?>"><?=$users[$row_thread["user_id"]]?></a><?
					} else {
						?>Anonymous<?
					}
					?></i></th><th class="head">Date: <i><?=date("d M Y H:i", $row_thread["postdate"])?></i></th></tr> <tr bgcolor="<?=$row_color?>"><td colspan="3"><?=bbcode($row_thread["contents"])?></td>
			</tr>
			<tr><td colspan="3"><div class="separator">&nbsp;</div></td></tr>
			<?
				while ($row_replies = mysql_fetch_assoc($rslt_replies)) {
					if ($row_replies["subject"] == "Bug tracker") {
						$row_color = "#d0ffd0";
					} else {
						if ($row_color == "#d0d0d0") {
							$row_color = "#e0e0e0";
						} else {
							$row_color = "#d0d0d0";
						}
					}

					?><tr><th class="head"><div class="head"><?=htmlentities($row_replies["subject"])?></div></th><th class="head" align="right">User: <i><?
					
					if ($row_replies["user_id"] != 0 && !empty($row_replies["user_id"])) {
						?><a class="action" href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$row_replies["user_id"]?>"><?=$users[$row_replies["user_id"]]?></a><?
					} else {
						?>Anonymous<?
					}

					?></i></th><th class="head">Date: <i><?=date("d M Y H:i", $row_replies["postdate"])?></i></th></tr><?
					?><tr bgcolor="<?=$row_color?>"><td colspan="3"><?=bbcode($row_replies["contents"])?></td></tr><?
					?><tr><td colspan="3"><div class="separator">&nbsp;</div></td></tr><?
				}
			?>
		</table>
		
		<div class="actionseparator">&nbsp;</div>
		&nbsp;
		<?
			if (substr($row_thread["subject"], 0, 3) == "Bug") {
				$bug_nr = (int) substr($row_thread["subject"], 5, 4);
				$qry_bug_id = "SELECT id FROM bugs WHERE bug_nr='".$bug_nr."' AND project_id='".$project_id."'";
				Debug ($qry_bug_id, __FILE__, __LINE__);
				$rslt_bug_id = mysql_query($qry_bug_id) or mysql_qry_error(mysql_error(), $qry_bug_id, __FILE__, __LINE__);
				$row_bug_id = mysql_fetch_assoc($rslt_bug_id);
				$bug_id = $row_bug_id["id"];
				?>
				<a class="nav" href="<?=$PHP_SELF?>?action=BugOverview&project_id=<?=$project_id?>&bug_id=<?=$bug_id?>">&lt; Bug overview</a> &nbsp;
				<?
			}
			if (substr($row_thread["subject"], 0, 4) == "Todo") {
				$todo_nr = (int) substr($row_thread["subject"], 6, 4);
				$qry_todo_id = "SELECT id FROM todos WHERE todo_nr='".$todo_nr."' AND project_id='".$project_id."'";
				Debug ($qry_todo_id, __FILE__, __LINE__);
				$rslt_todo_id = mysql_query($qry_todo_id) or mysql_qry_error(mysql_error(), $qry_todo_id, __FILE__, __LINE__);
				$row_todo_id = mysql_fetch_assoc($rslt_todo_id);
				$todo_id = $row_todo_id["id"];
				?>
				<a class="nav" href="<?=$PHP_SELF?>?action=TodoOverview&project_id=<?=$project_id?>&todo_id=<?=$todo_id?>">&lt; Todo overview</a> &nbsp;
				<?
			}
			
		?><a class="nav" href="<?=$PHP_SELF?>?action=ForumView&project_id=<?=$project_id?>&topic_id=<?=$topic_id?>">&lt; Threads</a> &nbsp; <?
		if (IsLoggedIn()) {
			?><a class="action" href="<?=$PHP_SELF?>?action=ForumReply&project_id=<?=$project_id?>&topic_id=<?=$topic_id?>&thread_id=<?=$thread_id?>">Reply</a><?
		}
	}
?>
