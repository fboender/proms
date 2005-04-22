<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id", "GP");

$statuses = ReadLookup ("release_status", "name");
$focuses = ReadLookup ("release_focus", "name");

$qry_releases = "SELECT id, version, codename, date, release_focus_id, status_id FROM project_releases WHERE project_id='".$project_id."'";
if (!IsProjectMember($project_id)) {
	$qry_releases .= " AND date < '".time()."'";
}
$qry_releases .= " ORDER BY status_id, date DESC";

Debug ($qry_releases, __FILE__, __LINE__);
$rslt_releases = mysql_query ($qry_releases) or mysql_qry_error(mysql_error(), $qry_releases, __FILE__, __LINE__);

$row_color = "#d0d0d0";

if (mysql_num_rows($rslt_releases) == 0) {
	?><p><i>No releases have been made yet</i></p><?
}

$release_status_id = 0;

while ($release = mysql_fetch_assoc($rslt_releases)) {

	if ($release["status_id"] != $release_status_id) {

		if ($release_status_id != 0) {
			?></table><?
		}

		?>
		<h2><?=$statuses[$release["status_id"]]?></h2>
		<table cellspacing="1" cellpadding="3">
		<tr valign="top">
		<th class="head">Version</td>
		<th class="head">Release date</td>
		<th class="head">Focus</td>
		<?
		if (IsAuthorized($project_id, AUTH_RELEASE_MODIFY)) {
			?><th class="head">&nbsp;</td><?
		}
		?>
		</tr>
		<?

		$release_status_id = $release["status_id"];
		
	}
	
	?>
	<tr valign="top" bgcolor="<?=$row_color?>">
	<td>
		<a href="<?=$PHP_SELF?>?action=ReleaseOverview&project_id=<?=$project_id?>&release_id=<?=$release["id"]?>"><?=$release["version"]?></a> 
		<?
		if ($release["codename"] != "") {
			?>(<?=$release["codename"]?>)<?
		}
		?>
	</td>
	<td><?=date("d M Y H:i", $release["date"])?></td>
	<td><?=$statuses[$release["status_id"]]?></td>
	<?
	if (IsAuthorized($project_id, AUTH_RELEASE_MODIFY)) {
		?><td><a href="<?=$PHP_SELF?>?action=ReleaseMod&project_id=<?=$project_id?>&release_id=<?=$release["id"]?>"><img src="images/ico_edit.gif" border="0" alt="edit" title="Edit this release"></a></td><?
	}
	?>
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
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project details</a> &nbsp;
<?
	/* Build 'subscribe to releases' text */
	if ($user_id) {
		/* Retrieve subscription information */
		$qry_subs_projectid = "SELECT project_id FROM subs_releases WHERE project_id='".$project_id."' AND user_id='".$user_id."'";
		$rslt_subs_projectid = mysql_query ($qry_subs_projectid) or mysql_qry_error(mysql_error(), $qry_subs_projectid, __FILE__, __LINE__);
		if (mysql_num_rows($rslt_subs_projectid) > 0) {
			?><a class="action" href="<?=$PHP_SELF?>?action=SubsReleaseDel&project_id=<?=$project["id"]?>&user_id=<?=$user_id?>">Unsubscribe</a>&nbsp;<?
		} else {
			?><a class="action" href="<?=$PHP_SELF?>?action=SubsReleaseAdd&project_id=<?=$project["id"]?>&user_id=<?=$user_id?>">Subscribe</a>&nbsp;<?
		}
	}

	if (IsAuthorized($project_id, 'AUTH_RELEASE_ADD')) {
		?>
		<a class="action" href="<?=$PHP_SELF?>?action=ReleaseAdd&project_id=<?=$project_id?>">New release</a> &nbsp;
		<?
	}
?>
