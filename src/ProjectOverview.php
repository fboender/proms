<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id", "GP");

$licenses = ReadLookup("licenses", "name");

/* Retrieve project details */
/* FIXME: variable names not correct */
$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
Debug ($qry_project, __FILE__, __LINE__);
$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
$project = mysql_fetch_assoc($rslt_project);


/* Retrieve owner details */
/* FIXME: variable names not according to code-style */
$qry_owner = "SELECT * FROM accounts WHERE id='".$project["owner"]."'";
Debug ($qry_owner, __FILE__, __LINE__);
$rslt_owner = mysql_query ($qry_owner) or mysql_qry_error(mysql_error(), $qry_owner, __FILE__, __LINE__);
$owner = mysql_fetch_assoc($rslt_owner);

/* Build 'subscribe to releases' text */
if ($user_id) {
	/* Retrieve subscription information */
	$qry_subs_projectid = "SELECT project_id FROM subs_releases WHERE project_id='".$project_id."' AND user_id='".$user_id."'";
	$rslt_subs_projectid = mysql_query ($qry_subs_projectid) or mysql_qry_error(mysql_error(), $qry_subs_projectid, __FILE__, __LINE__);
	if (mysql_num_rows($rslt_subs_projectid) > 0) {
		$subsreleases = "<a href=\"".$PHP_SELF."?action=SubsReleaseDel&project_id=".$project["id"]."&user_id=".$user_id."\">Unsubscribe</a>";
	} else {
		$subsreleases = "<a href=\"".$PHP_SELF."?action=SubsReleaseAdd&project_id=".$project["id"]."&user_id=".$user_id."\">Subscribe</a>";
	}
} else {
	$subsreleases = "You need to be logged in in order to subscribe";
}

if ($project["progress"] == "" || $project["progress"] < 1) {
	/* Retrieve project progress */
	$qry_project_todos_total = "SELECT COUNT(*) FROM todos WHERE project_id='".$project_id."'";
	$rslt_project_todos_total = mysql_query($qry_project_todos_total) or mysql_qry_error(mysql_error(), $qry_project_todos_total, __FILE__, __LINE__);
	$project_todos_total = mysql_result($rslt_project_todos_total,0);
	$qry_project_todos_closed = "SELECT COUNT(*) FROM todos WHERE project_id='".$project_id."' AND done=1";
	$rslt_project_todos_closed = mysql_query($qry_project_todos_closed) or mysql_qry_error(mysql_error(), $qry_project_todos_closed, __FILE__, __LINE__);
	$project_todos_closed = mysql_result($rslt_project_todos_closed,0);
	if (!$project_todos_total) {
		$project_progress = "N/A";
	} else {
		$project_progress = sprintf("%1.1f",($project_todos_closed/$project_todos_total)*100);
	}
} else {
	$project_progress = $project["progress"];
}

/* Retrieve latest releases for this project */
$qry_releases = "SELECT * FROM project_releases WHERE project_id='".$project_id."' AND date < '".time()."' ORDER BY date DESC LIMIT 5";
Debug ($qry_releases, __FILE__, __LINE__);
$rslt_releases = mysql_query($qry_releases) or mysql_qry_error(mysql_error(), $qry_releases, __FILE__, __LINE__);

if ($project_progress != 0) {
	$progress_class = "progressbar";
} else {
	$progress_class = "";
}
?>
<table>
<p><?=nl2br($project["description"])?></p>
<div class="separator">&nbsp;</div>
<tr valign="top"><th><nobr>Owner</nobr></th><td>:</td><td><a href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$owner["id"]?>"><?=$owner["fullname"]?></a> </td></tr>
<tr valign="top"><th><nobr>License</nobr></th><td>:</td><td><?=$licenses[$project["license"]]?></td></tr>
<tr valign="top"><th><nobr>Progress</nobr></th><td>:</td><td><div class="progressborder"><div class="<?=$progress_class?>" style="width:<?=($project_progress/100)*300?>;"><font color="#FFFFFF" style="font-size: 11px;">&nbsp;<?=($project_progress)?><? if (is_numeric($project_progress)) { ?>%<? } ?></font></div></div></td></tr>
<tr valign="top"><th><nobr>Project Homepage</nobr></th><td>:</td><td><a href="<?=$project["homepage"]?>"><?=$project["homepage"]?></a></td></tr>
</table>

<div class="separator">&nbsp;</div>
<table>
<tr valign="top">
	<th>Latest releases :</th>
	<td>
		<table cellspacing="2" cellpadding="3" border="0">
		<tr valign="top">
			<th class="head">Version</th>
			<th class="head">Release date</th>
			<th class="head">Changes</th>
			<th>&nbsp; Download: </th>
			<th class="head">Source</th>
			<th class="head">Binary</th>
			<th class="head">Debian package</th>
			<th class="head">Redhat package</th>
		<?
			$row_color = "#d0d0d0";

			if (mysql_num_rows($rslt_releases) == 0) {
				?><tr bgcolor="<?=$row_color?>"><td colspan="8">There have not been any releases for this project yet.</td></tr><?
			} else {
				while ($release = mysql_fetch_assoc($rslt_releases)) {
					?>
					<tr valign="top" bgcolor="<?=$row_color?>">
						<th>
							<a href="<?=$PHP_SELF?>?action=ReleaseOverview&project_id=<?=$project["id"]?>&release_id=<?=$release["id"]?>"><?=$release["version"]?></a>
							<?
							if ($release["codename"] != "") {
								?>(<?=$release["codename"]?>)<?
							}
							?>
						</th>
						<td><?=date("d M Y", $release["date"])?></td>
						<td><? if ($release["url_changelog"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_changelog"]?>">Changes</a><? } ?> </td>
						<td bgcolor="#FFFFFF"> &nbsp; &nbsp; &nbsp; </td>
						<td><? if ($release["url_source"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_source"]?>">Source</a><? } ?> </td>
						<td><? if ($release["url_bin"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_bin"]?>">Binary</a><? } ?> </td>
						<td><? if ($release["url_deb"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_deb"]?>">Debian package</a><? } ?> </td>
						<td><? if ($release["url_rpm"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_rpm"]?>">Redhat package</a><? } ?> </td>
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
		<br>
		<a href="<?=$PHP_SELF?>?action=ReleaseList&project_id=<?=$project["id"]?>">All releases</a>
	</td>
</tr>
</table>

<div class="separator">&nbsp;</div>
<table>
<tr valign="top"><th><nobr>Subscribe to releases</nobr></th><td>:</td><td><?=$subsreleases?></td></tr>
<tr valign="top"><th><nobr>Bugs</nobr></th><td>:</td><td><a href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project["id"]?>">Bugs</a></td></tr>
<tr valign="top"><th><nobr>Todos</nobr></th><td>:</td><td><a href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project["id"]?>">Todos</a></td></tr>
<tr valign="top"><th><nobr>Forums</nobr></th><td>:</td><td><a href="<?=$PHP_SELF?>?action=ForumView&project_id=<?=$project["id"]?>">Discussions</a></td></tr>
<tr valign="top"><th><nobr>Files</nobr></th><td>:</td><td><a href="<?=$PHP_SELF?>?action=FileList&project_id=<?=$project["id"]?>">Files</a></td></tr>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectList">&lt; Project list</a> &nbsp;
<?
	if (IsAuthorized($project_id, 'AUTH_PROJECT_MODIFY')) {
		?>
		<a class="action" href="<?=$PHP_SELF?>?action=ProjectMod&project_id=<?=$project_id?>">Modify project</a> &nbsp;
		<a class="action" href="<?=$PHP_SELF?>?action=ProjectDel&project_id=<?=$project_id?>">Delete project</a> &nbsp;
		<a class="action" href="<?=$PHP_SELF?>?action=ProjectPartList&project_id=<?=$project_id?>">Modify parts</a> &nbsp;
		<?
	}
	if (IsAuthorized($project_id, 'AUTH_PROJECTMEMBERS_MODIFY')) {
		?>
		<a class="action" href="<?=$PHP_SELF?>?action=ProjectMemberList&project_id=<?=$project_id?>">Modify members</a> &nbsp;
		<?
	}
?>

