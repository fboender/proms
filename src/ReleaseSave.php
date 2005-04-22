<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$release    = Import ("release"    , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsProjectOwner($project_id)) {
	Error ("Access denied. You're not the project owner", "back");
}

$now = getdate(time());
$release["date"] = mktime($now["hours"], $now["minutes"], $now["seconds"], $release["date"]["month"], $release["date"]["day"], $release["date"]["year"]);

if ($release["id"] != "") {
	/* Update an existing release entry */

	$qry_release = "UPDATE project_releases SET ";
	$qry_release .= "project_id='"      .$project_id                 ."', ";
	$qry_release .= "version='"         .$release["version"]         ."', ";
	$qry_release .= "codename='"        .$release["codename"]        ."', ";
	$qry_release .= "release_focus_id='".$release["release_focus_id"]."', ";
	$qry_release .= "status_id='"       .$release["status_id"]       ."', ";
	$qry_release .= "changes='"         .$release["changes"]         ."', ";
	$qry_release .= "url_source='"      .$release["url_source"]      ."', ";
	$qry_release .= "url_bin='"         .$release["url_bin"]         ."', ";
	$qry_release .= "url_deb='"         .$release["url_deb"]         ."', ";
	$qry_release .= "url_rpm='"         .$release["url_rpm"]         ."', ";
	$qry_release .= "url_changelog='"   .$release["url_changelog"]   ."', ";
	$qry_release .= "url_releasenotes='".$release["url_releasenotes"]."', ";
	$qry_release .= "date='"            .$release["date"]            ."'  ";
	$qry_release .= "WHERE id='"        .$release["id"]              ."'  ";

	Debug ($qry_release, __FILE__, __LINE__);

	$rslt_release = mysql_query ($qry_release) or mysql_qry_error(mysql_error(), $qry_release, __FILE__, __LINE__);

	$release_id = $release["id"];
} else {
	/* Insert a new entry */
	$qry_release = "INSERT INTO project_releases SET ";
	$qry_release .= "project_id='"      .$project_id                 ."', ";
	$qry_release .= "version='"         .$release["version"]         ."', ";
	$qry_release .= "codename='"        .$release["codename"]        ."', ";
	$qry_release .= "release_focus_id='".$release["release_focus_id"]."', ";
	$qry_release .= "status_id='"       .$release["status_id"]       ."', ";
	$qry_release .= "changes='"         .$release["changes"]         ."', ";
	$qry_release .= "url_source='"      .$release["url_source"]      ."', ";
	$qry_release .= "url_bin='"         .$release["url_bin"]         ."', ";
	$qry_release .= "url_deb='"         .$release["url_deb"]         ."', ";
	$qry_release .= "url_rpm='"         .$release["url_rpm"]         ."', ";
	$qry_release .= "url_changelog='"   .$release["url_changelog"]   ."', ";
	$qry_release .= "url_releasenotes='".$release["url_releasenotes"]."', ";
	$qry_release .= "date='"            .$release["date"]            ."'  ";

	Debug ($qry_release, __FILE__, __LINE__);

	$rslt_release = mysql_query ($qry_release) or mysql_qry_error(mysql_error(), $qry_release, __FILE__, __LINE__);
	$release_id = mysql_insert_id();

	/* Send out announcement */
	$qry_subsreleases ="SELECT accounts.fullname, accounts.email FROM accounts, subs_releases WHERE accounts.id=subs_releases.user_id and subs_releases.project_id='".$project_id."'";
	$rslt_subsreleases = mysql_query ($qry_subsreleases) or mysql_qry_error(mysql_error(), $qry_subsreleases, __FILE__, __LINE__);
	
	if (mysql_num_rows($rslt_subsreleases) > 0) {
		/* Get project information */
		$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
		$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
		$project = mysql_fetch_array($rslt_project);

		/* Get recipients */
		$announcement["bcc"] = "Bcc: ";
		while ($row_subsreleases = mysql_fetch_array($rslt_subsreleases)) {
			$announcement["bcc"] .= "\"".$row_subsreleases["fullname"]."\" <".$row_subsreleases["email"].">,";
		}
		$announcement["bcc"] = substr($announcement["bcc"], 0, strlen($announcement["bcc"])-1);
		Debug (htmlentities($announcement["bcc"]), __FILE__, __LINE__);

		/* Headers */
		$announcement["headers"] = "From: \"PROMS Announcement list\" <".PROMS_EMAIL.">\r\n";
		
		/* Subject */
		$announcement["subject"] = $project["fullname"]." version ".$release["version"]." released.";
		
		/* Build body */
		$announcement["body"]  = $project["fullname"]." version ".$release["version"];
		if ($project["codename"] != "") {
			$announcement["body"] .= " (codename '".$release["codename"]."')";
		}
		$announcement["body"] .= " has been released.\r\n\r\n";
		$announcement["body"] .= "The changes in this release are:\r\n".$release["changes"]."\r\n\r\n";
		$announcement["body"] .= "You may visit this project's homepage at : ".$project["homepage"]."\r\n";
		if ($release["url_source"] != "") { $announcement["body"] .= "You may download the source from : ".$release["url_source"]."\r\n"; }
		if ($release["url_bin"] != "") { $announcement["body"] .= "You may download the binaries from : ".$release["url_bin"]."\r\n"; }
		if ($release["url_deb"] != "") { $announcement["body"] .= "You may download the Debian package from : ".$release["url_deb"]."\r\n"; }
		if ($release["url_rpm"] != "") { $announcement["body"] .= "You may download the Redhat package from : ".$release["url_rpm"]."\r\n"; }
		$announcement["body"] .= "\r\n";
		if ($release["url_changelog"] != "") { 	$announcement["body"] .= "Full changelog for this release is at : ".$release["url_changelog"]."\r\n"; }
		if ($release["url_releasenotes"] != "") { $announcement["body"] .= "Releasenotes for this release are at : ".$release["url_releasenotes"]."\r\n"; }
		$announcements["body"] = substr($announcement["body"],0, strlen($announcement["body"])-4);
		
		$sent = smtpmail (
			"\"PROMS Announcement list\" <".PROMS_EMAIL.">", 
			$announcement["subject"],
			$announcement["body"],
			$announcement["headers"].$announcement["bcc"]);

		if (!($sent === TRUE)) {
			Error("Email notification about this new release couldn't be sent. Please alert the administrator.");
		}
		
	}
}
Refresh ("ReleaseOverview&project_id=$project_id&release_id=$release_id");
?>
