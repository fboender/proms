<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.
*/

$project_id = Import ("project_id", "GP");
$release_id = Import ("release_id", "GP");

$qry_project = "SELECT shortname FROM projects WHERE id = '".$project_id."'";
$rslt_project = mysql_query($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
$project = mysql_fetch_assoc($rslt_project);

$qry_release = "SELECT * FROM project_releases WHERE id='".$release_id."'";
Debug ($qry_release, __FILE__, __LINE__);
$rslt_release = mysql_query ($qry_release) or mysql_qry_error(mysql_error(), $qry_release, __FILE__, __LINE__);
$release = mysql_fetch_assoc($rslt_release);

$statuses = ReadLookup ("release_status", "name");
$focuses = ReadLookup ("release_focus", "name");
?>
<br>
<table>
<tr><td colspan="2"><h2><?=$project["shortname"]?> v<?=$release["version"]?> <? if ($release["codename"] != "") { ?>'<?=$release["codename"]?>' <? } ?></h2></td></tr>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Version :</th><td><?=$release["version"]?></td></tr>
<?
if ($release["codename"] != "") {
	?><tr valign="top"><th class="head">Codename:</th><td><?=$release["codename"]?></td></tr><?
}
?>
<tr valign="top"><th class="head">Date:</th><td><?=date("d M Y H:i", $release["date"])?></td></tr>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Release focus:</th><td><?=$focuses[$release["release_focus_id"]]?></td></tr>
<tr valign="top"><th class="head">Release status:</th><td><?=$statuses[$release["status_id"]]?></td></tr>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Changes:</th><td><?=nl2br(htmlentities($release["changes"]))?></td></tr>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Source package:</th><td><? if ($release["url_source"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_source"]?>">Download</a><? } ?></td></tr>
<tr valign="top"><th class="head">Binary package:</th><td><? if ($release["url_bin"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_bin"]?>">Download</a><? } ?></td></tr>
<tr valign="top"><th class="head">Debian package:</th><td><? if ($release["url_deb"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_deb"]?>">Download</a><? } ?></td></tr>
<tr valign="top"><th class="head">Redhat package:</th><td><? if ($release["url_rpm"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_rpm"]?>">Download</a><? } ?></td></tr>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Changelog:</th><td><? if ($release["url_changelog"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_changelog"]?>">View</a><? } ?></td></tr>
<tr valign="top"><th class="head">Releasenotes:</th><td><? if ($release["url_releasenotes"] == "") { ?>N/A<? } else { ?><a href="<?=$release["url_releasenotes"]?>">View</a><? } ?></td></tr>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ReleaseList&project_id=<?=$project_id?>">&lt; Release List</a> &nbsp;
<?
	if (IsAuthorized($project_id, 'AUTH_RELEASE_MODIFY')) {
		?>
		<a class="action" href="<?=$PHP_SELF?>?action=ReleaseMod&project_id=<?=$project_id?>&release_id=<?=$release_id?>">Modify</a> &nbsp;
		<?
	}
?>
