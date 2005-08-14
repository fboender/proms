<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

/* Retrieve all project id's */
$qry_project_ids = "SELECT id, private FROM projects";
$rslt_project_ids = mysql_query ($qry_project_ids) or mysql_qry_error(mysql_error(), $qry_project_ids, __FILE__, __LINE__);

if (mysql_num_rows($rslt_project_ids) > 0) {
	/* Walk through all projects */
	while ($row_project_ids = mysql_fetch_assoc($rslt_project_ids)) {
		/* Do not list private projects if the user isn't a member of that project */
		if ($row_project_ids["private"] == '0' || IsProjectMember($row_project_ids["id"])) {
			/* Retrieve project details */
			$qry_project = "SELECT * FROM projects WHERE id='".$row_project_ids["id"]."'";
			$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
			$project = mysql_fetch_assoc($rslt_project);


			/* Retrieve owner details */
			$qry_project_owner = "SELECT * FROM accounts WHERE id='".$project["owner"]."'";
			$rslt_project_owner = mysql_query ($qry_project_owner) or mysql_qry_error(mysql_error(), $qry_project_owner, __FILE__, __LINE__);
			$project_owner = mysql_fetch_assoc($rslt_project_owner);
			Debug ($qry_project_owner, __FILE__, __LINE__);
			
			if ($project["progress"] == "" || $project["progress"] < 1) {
				/* Retrieve project progress */
				$qry_project_todos_total = "SELECT COUNT(*) FROM todos WHERE project_id='".$project["id"]."'";
				Debug ($qry_project_todos_total, __FILE__, __LINE__);
				$rslt_project_todos_total = mysql_query($qry_project_todos_total) or mysql_qry_error(mysql_error(), $qry_project_todos_total, __FILE__, __LINE__);
				$project_todos_total = mysql_result($rslt_project_todos_total,0);
				
				$qry_project_todos_closed = "SELECT COUNT(*) FROM todos WHERE project_id='".$project["id"]."' AND done=1";
				Debug ($qry_project_todos_closed, __FILE__, __LINE__);
				$rslt_project_todos_closed = mysql_query($qry_project_todos_closed) or mysql_qry_error(mysql_error(), $qry_project_todos_closed, __FILE__, __LINE__);
				$project_todos_closed = mysql_result($rslt_project_todos_closed,0);
				
				Debug($project_todos_total, __FILE__, __LINE__);
				if (!$project_todos_total) {
					$project_progress = "N/A";
				} else {
					$project_progress = sprintf("%1.1f",($project_todos_closed/$project_todos_total)*100);
				}
			} else {
				$project_progress = $project["progress"];
			}
			Debug($project_progress, __FILE__, __LINE__);
			
			/* Retrieve latest version */
			$qry_latestversion = "SELECT id, version, date FROM project_releases WHERE project_id = '".$project["id"]. "' AND date < '".time()."' ORDER BY date DESC LIMIT 1";
			Debug ($qry_latestversion, __FILE__, __LINE__);
			$rslt_latestversion = mysql_query ($qry_latestversion) or mysql_qry_error(mysql_error(), $qry_latestversion, __FILE__, __LINE__);
			$latestversion = mysql_fetch_assoc($rslt_latestversion);
			
			/* Show project in list */
			?>
			<table>
			<tr valign="top"><td colspan="4"><div class="project_fullname"><a class="project_fullname" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project["id"]?>"><?=$project["fullname"]?></a></div></td></tr>
			<?
				if ($project["desc_short"] != "") {
					?><tr valign="top"><td><b>About</b></td><td>:</td><td><?=$project["desc_short"]?></td></tr><?
				}
			?>
			<tr valign="top"><td><b><nobr>Latest version</nobr></b></td><td>:</td>
				<td>
				<?
				if ($latestversion != "") {
					?><a href="<?=$PHP_SELF?>?action=ReleaseOverview&project_id=<?=$project["id"]?>&release_id=<?=$latestversion["id"]?>"><?=$latestversion["version"]?></a> (<?=date("d M Y", $latestversion["date"])?>)<?
				} else {
					?>No releases yet<?
				}
				?>
				</td>
			</tr>
			<tr valign="top">
				<td><b><nobr>Progress</nobr></b></td>
				<td>:</td>
				<td>
					<div class="progressborder"><?
						if ($project_progress != 0) {
							?><div class="progressbar" style="width:<?=($project_progress/100)*300?>;"><?
						} else {
							?><div><?
						}
						?><font color="#FFFFFF" style="font-size:11px;">&nbsp;<?=($project_progress)?><?
						if (is_numeric($project_progress)) { 
							?>%<? 
						} 
						?></font></div>
					</div>
				</td>
			</tr>
			<tr valign="top"><td><b><nobr>Details</nobr></b></td><td>:</td>
				<td>
					<a href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project["id"]?>">Project page</a> &nbsp;
					<a href="<?=$project["homepage"]?>">Homepage</a> &nbsp; 
					<a href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$project["owner"]?>">Author</a> &nbsp;
				</td>
			</tr>
			</table>
			<?
		}
	} /* Next project */
} else {
	?><p>No projects are registered at this moment</p><?
}

?>
<div class="actionseparator">&nbsp;</div>
<div style="float: left;">
&nbsp;
<?
if (IsAdmin()) {
	?>
	<a class="action" href="<?=$PHP_SELF?>?action=ProjectAdd&wizard=1">New project</a> &nbsp;
	<a class="action" href="<?=$PHP_SELF?>?action=AccountList">Account list</a> &nbsp;
	<?
}
?>
</div>
<div style="float: right">
<a href="http://www.electricmonk.nl/index.php?page=PROMS"><b>PROMS</b></a>, Copyright &copy; 2002-2004 by Ferry Boender. Released under the GPL license. Version <b>%%VERSION</b>
</div>
