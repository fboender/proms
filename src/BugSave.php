<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$bug_id     = Import ("bug_id"     , "GP");
$bug        = Import ("bug"        , "GP");
$user_id    = Import ("user_id"    , "S");

/* Security check */
if (!IsLoggedIn() && BUGADD_ALLOW_ANONYMOUS != true) {
	Error ("Access denied. Must be logged in", "back");
}
if ($bug_id != "" && !IsAuthorized($project_id, AUTH_BUG_MOD)) {
	Error ("Access denied. You're not authorized to modify bugs", "back");
}

/* Check project id */
$qry_projectid = "SELECT * FROM projects WHERE id='".$project_id."'";
Debug ($qry_projectid, __FILE__, __LINE__);
$rslt_projectid = mysql_query ($qry_projectid) or mysql_qry_error (mysql_error(), $qry_projectid, __FILE__, __LINE__);
if (mysql_num_rows($rslt_projectid) != 1) {
	Error ("Invalid project id", "back");
}
if ($bug_id != "") {
	/* Update existing bug */
	
	/* Security check */
	if (!IsProjectOwner($project_id)) {
		Error ("Access denied. You're not the project owner", "back");
	}

	$severities = ReadLookup ("bugs_severity", "title");
	$statuses = ReadLookup ("bugs_statuses", "name");
	$projectparts = ReadLookup ("project_parts", "title", "WHERE project_id=".$project_id);
	
	/* Get old bug contents to compare with new for bug tracking */
	$qry_oldbug = "SELECT * FROM bugs WHERE id='".$bug_id."'";
	$rslt_oldbug = mysql_query ($qry_oldbug) or mysql_qry_error(mysql_error(), $qry_oldbug, __FILE__, __LINE__);
	$oldbug = mysql_fetch_assoc($rslt_oldbug);

	/* Compare old bug with new bug and build tracker message */
	$change = "";
	if ($oldbug["version"]  != $bug["version"])  { $change .= "Version was changed to '".$bug["version"]."'\n"; }
	if ($oldbug["part"]     != $bug["part"])     { $change .= "Part was changed to '".$projectparts[$bug["part"]]."'\n"; }
	if ($oldbug["severity"] != $bug["severity"]) { $change .= "Severity was changed to '".$severities[$bug["severity"]]."'\n"; }
	if ($oldbug["status"]   != $bug["status"])   { $change .= "Status was changed to '".$statuses[$bug["status"]]."'\n"; }
	if ($oldbug["subject"]  != $bug["subject"])  { $change .= "Subject was changed to '".$bug["subject"]."'\n"; }
	
	Debug ("Change: ".$change, __FILE__, __LINE__);

	if ($change != "") {
		/* Post bugtracking change to forum */
		ForumBugTrack ($change, $project_id, $oldbug);
	}

	$qry_update  = "UPDATE bugs SET ";
	$qry_update .= "project_id='"  .$project_id          ."', ";
	$qry_update .= "version='"     .$bug["version"]      ."', ";
	if ($bug["part"] != "") {
		$qry_update .= "part='".$bug["part"]."', ";
	}
	$qry_update .= "severity='"    .$bug["severity"]     ."', ";
	$qry_update .= "status='"      .$bug["status"]       ."', ";
	$qry_update .= "subject='"     .$bug["subject"]      ."', ";
	$qry_update .= "description='" .$bug["description"]  ."', ";
	$qry_update .= "reproduction='".$bug["reproduction"] ."', ";
	$qry_update .= "patch='"       .$bug["patch"]        ."', ";
	$qry_update .= "software='"    .$bug["software"]     ."', ";
	$qry_update .= "user_id='"     .$bug["user_id"]      ."', ";
	$qry_update .= "lastmoddate='" .time()               ."'  ";
	$qry_update .= "WHERE id='"    .$bug_id              ."'  ";
	Debug ($qry_update, __FILE__, __LINE__);
	
	$rslt_update = mysql_query ($qry_update) or mysql_qry_error(mysql_error(), $qry_update, __FILE__, __LINE__);
} else {
	/* Insert new bug */

	/* Calculate next bug # */
	$qry_bugnr = "SELECT max(bug_nr) FROM bugs WHERE project_id='".$project_id."'";
	Debug ($qry_bugnr, __FILE__, __LINE__);
	$rslt_bugnr = mysql_query($qry_bugnr) or mysql_qry_error(mysql_error(), $qry_bugnr, __FILE__, __LINE__);
	$row_bugnr = mysql_fetch_row($rslt_bugnr);
	$bug_nr = $row_bugnr[0] + 1;
	
	$qry_insert  = "INSERT INTO bugs SET ";
	$qry_insert .= "project_id='"  .$project_id         ."', ";
	$qry_insert .= "bug_nr='"      .$bug_nr             ."', ";
	$qry_insert .= "version='"     .$bug["version"]     ."', ";
	if ($bug["part"] != "") {
		$qry_insert .= "part='".$bug["part"]."', ";
	}
	$qry_insert .= "severity='"    .$bug["severity"]    ."', ";
	$qry_insert .= "subject='"     .$bug["subject"]     ."', ";
	$qry_insert .= "description='" .$bug["description"] ."', ";
	$qry_insert .= "reproduction='".$bug["reproduction"]."', ";
	$qry_insert .= "patch='"       .$bug["patch"]       ."', ";
	$qry_insert .= "software='"    .$bug["software"]    ."', ";
	if (isset($user_id)) {
		$qry_insert .= "user_id='".$user_id."', ";
	} else {
		$qry_insert .= "user_id=0, "; /* anonymous */
	}
	$qry_insert .= "reportdate='"  .time()              ."', ";
	$qry_insert .= "lastmoddate='" .time()              ."', ";

	if (IsAuthorized($project_id, AUTH_BUG_MOD)) {
		$qry_insert .= "status='".$bug["status"]."'";
	} else {
		$qry_insert .= "status=1";
	}

	Debug ($qry_insert, __FILE__, __LINE__);

	$rslt_insert = mysql_query ($qry_insert) or mysql_qry_error(mysql_error(), $qry_insert, __FILE__, __LINE__);
	$bug_id = mysql_insert_id ();

	/* Notify people of this new bug */
	$notify["bcc"] = "Bcc: ";

	$qry_maintainer = "
		SELECT acc.fullname AS fullname, acc.email AS email FROM project_parts ppart 
		RIGHT JOIN accounts acc ON acc.id = ppart.maint_user_id 
		WHERE ppart.project_id='".$project_id."' AND ppart.id='".$bug["part"]."'";
	Debug ($qry_maintainer, __FILE__, __LINE__);
	$rslt_maintainer = mysql_query($qry_maintainer) or mysql_qry_error(mysql_error(), $qry_maintainer, __FILE__, __LINE__);
	if (mysql_num_rows($rslt_maintainer) != 0) {
		/* Project part exists and has maintainer */
		$row_maintainer = mysql_fetch_assoc($rslt_maintainer);
		$notify["bcc"] .= "\"".$row_maintainer["fullname"]."\" <".$row_maintainer["email"].">";
	} else {
		/* No project part or maintainer. Send to owner */
		$qry_owner = "SELECT projects.shortname, accounts.fullname, accounts.email FROM projects, accounts WHERE projects.id='".$project_id."' AND accounts.id=projects.owner";
		Debug ($qry_owner, __FILE__, __LINE__);
		$rslt_owner = mysql_query($qry_owner) or mysql_qry_error(mysql_error(), $qry_owner, __FILE__, __LINE__);
		$row_owner = mysql_fetch_assoc($rslt_owner);
		$notify["bcc"] .= "\"".$row_owner["fullname"]."\" <".$row_owner["email"].">";
	}

	/* Get project information */
	$qry_project= "SELECT projects.shortname FROM projects WHERE projects.id='".$project_id."'";
	Debug ($qry_project, __FILE__, __LINE__);
	$rslt_project = mysql_query($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
	$row_project= mysql_fetch_assoc($rslt_project);

	$notify["headers"] = "From: \"PROMS Bugtracker for ".$row_project["shortname"]."\" <".PROMS_EMAIL.">\n";
	$notify["subject"] = $row_project["shortname"]." Bug #".$bug_nr." reported: ".stripslashes($bug["subject"]);
	
	$notify["body"]  = "Bug #".$bug_nr." was just reported via the PROMS bugtracker for project ".$row_project["shortname"]."\n\n";
	$notify["body"] .= "Subject: ".stripslashes($bug["subject"])."\nDescription:\n\n".stripslashes($bug["description"])."\n\n";
	$notify["body"] .= "You may follow this URL to view the bug's details: ".ThisUrl()."?action=BugOverview&project_id=".$project_id."&bug_id=".$bug_id;

	$sent = smtpmail (
		"\"PROMS Bug notifier\" <".PROMS_EMAIL.">",
		$notify["subject"],
		$notify["body"],
		$notify["headers"].$notify["bcc"]);

	if (!($sent === TRUE)) {
		Error("Email notification about this new bug couldn't be sent. Please alert the administrator.", "exit");
	}
}

Refresh ("BugOverview&project_id=$project_id&bug_id=$bug_id");
?>
