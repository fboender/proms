<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id", "GP");
$release_id = Import ("release_id", "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsProjectOwner($project_id) && !IsAuthorized($project_id, 'AUTH_PROJECT_ADD')) {
	Error ("Access denied. You're not the project owner", "back");
}


if (isset($release_id)) {
	$qry_release = "SELECT * FROM project_releases WHERE id='".$release_id."'";
	Debug ($qry_release, __FILE__, __LINE__);
	$rslt_release = mysql_query ($qry_release) or mysql_qry_error(mysql_error(), $qry_release, __FILE__, __LINE__);
	$release = mysql_fetch_assoc ($rslt_release);
	if (!$release) {
		Error ("Invalid release ID specified", "back");
	}
}

?>
<table>
<form method="post" action="<?=$PHP_SELF?>">
<?
InputHidden ("action", "ReleaseSave");
InputHidden ("project_id", $project_id);
InputHidden ("release[id]", $release["id"]);
InputText ("Version", "release[version]", @$release["version"]);
InputText ("Codename", "release[codename]", @$release["codename"]);
InputDate ("Date", "release[date]", @$release["date"]);
InputDropDown ("Release focus", "release[release_focus_id]", @$release["release_focus_id"], "release_focus");
InputDropDown ("Release status", "release[status_id]", @$release["status_id"], "release_status");
InputArea ("Changes", "release[changes]", @$release["changes"]);
InputFileBrowse ("URL Source package", "release[url_source]", @$release["url_source"]);
InputFileBrowse ("URL Binary package", "release[url_bin]", @$release["url_bin"]);
InputFileBrowse ("URL Debian package", "release[url_deb]", @$release["url_deb"]);
InputFileBrowse ("URL RPM package", "release[url_rpm]", @$release["url_rpm"]);
InputFileBrowse ("URL Changelog", "release[url_changelog]", @$release["url_changelog"]);
InputFileBrowse ("URL Releasenote", "release[url_releasenotes]", @$release["url_releasenotes"]);
InputSubmit ("Save");
?>
</form>
</table>
