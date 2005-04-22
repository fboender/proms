<?  
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id      = Import ("project_id"      , "GP");
$todo            = Import ("todo"            , "GP");
$todo_id         = Import ("todo_id"         , "GP");
$todo_addanother = Import ("todo_addanother" , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}

if (isset($todo_id) && !empty($todo_id)) {
	if (!IsAuthorized($project_id, AUTH_TODO_MODIFY)) {
		Error ("You are not authorized to modify todos", "back");
	}
}
   
if (isset($todo_id)) {
	/* Update existing todo */
	$qry_update  = "UPDATE todos SET ";
	$qry_update .= "project_id='" . $project_id          ."', ";
	$qry_update .= "done='"       . $todo["done"]        ."', ";
	$qry_update .= "subject='"    . $todo["subject"]     ."', ";
	$qry_update .= "description='". $todo["description"] ."', ";
	$qry_update .= "user_id='"    . $todo["user_id"]     ."', ";
	$qry_update .= "priority='"   . $todo["priority"]    ."', ";
	if ($todo["part"] != "") {
		$qry_insert .= "part='"   . $todo["part"]        ."', ";
	}
	$qry_update .= "lastmoddate='"  .time()               ."' ";  
	$qry_update .= "WHERE id='".$todo_id."'";
	Debug ($qry_update, __FILE__, __LINE__);

	$rlst_update = mysql_query ($qry_update) or mysql_qry_error(mysql_error(), $qry_update, __FILE__, __LINE__);
} else {
	/* Insert new todo */

	/* Calculate next todo nr */
	$qry_todonr = "SELECT max(todo_nr) FROM todos WHERE project_id=".$project_id;
	$rslt_todonr = mysql_query($qry_todonr) or mysql_qry_error(mysql_error(), $qry_todonr, __FILE__, __LINE__);
	$row_todonr = mysql_fetch_row($rslt_todonr);
	$todo_nr = $row_todonr[0] + 1;
	
	$qry_insert  = "INSERT INTO todos SET ";
	$qry_insert .= "todo_nr='"    .$todo_nr            ."', ";
	$qry_insert .= "project_id='" .$project_id         ."', ";
	$qry_insert .= "subject='"    .$todo["subject"]    ."', ";
	$qry_insert .= "description='".$todo["description"]."', ";
	$qry_insert .= "user_id='"    .$user_id            ."', ";
	if ($todo["part"] != "") {
	$qry_insert .= "part='"       .$todo["part"]       ."', ";
	}
	$qry_insert .= "priority='"   .$todo["priority"]   ."', ";
	$qry_insert .= "lastmoddate='".time()              ."', ";
	$qry_insert .= "done='"       .$todo["done"]       ."'  ";
	
	Debug ($qry_insert, __FILE__, __LINE__);
	
	$rslt_insert = mysql_query ($qry_insert) or mysql_qry_error(mysql_error(), $qry_insert, __FILE__, __LINE__);
	$todo_id = mysql_insert_id();
	
	/* Notify people of this new bug */
	$notify["bcc"] = "Bcc: ";

	$qry_maintainer = "
		SELECT acc.fullname AS fullname, acc.email AS email FROM project_parts ppart 
		RIGHT JOIN accounts acc ON acc.id = ppart.maint_user_id 
		WHERE ppart.project_id='".$project_id."' AND ppart.id='".$todo["part"]."'";
	echo($qry_maintainer);
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
	$row_project = mysql_fetch_assoc($rslt_project);

	$notify["headers"] = "From: \"PROMS Todotracker for ".$row_project["shortname"]."\" <".PROMS_EMAIL.">\r\n";
	$notify["subject"] = $row_project["shortname"]." Todo #".$todo_nr." reported: ".stripslashes($todo["subject"]);
	
	$notify["body"]  = "Todo #".$todo_nr." was just reported via the PROMS Todotracker for project ".$row_project["shortname"]."\n\n";
	$notify["body"] .= "Subject: ".stripslashes($todo["subject"])."\nDescription:\n\n".stripslashes($todo["description"])."\n\n";
	$notify["body"] .= "You may follow this URL to view the bug's details: http://".$_SERVER["HTTP_HOST"].$PHP_SELF."?action=TodoOverview&project_id=".$project_id."&todo_id=".$todo_id;

	$sent = smtpmail (
		"\"PROMS Todo notifier\" <".PROMS_EMAIL.">",
		$notify["subject"],
		$notify["body"],
		$notify["headers"].$notify["bcc"]);

	if (!($sent === TRUE)) {
		Error("Email notification about this new todo couldn't be sent. Please alert the administrator.");
	}
}

if ($todo_addanother == "") {
	Refresh ("TodoOverview&project_id=$project_id&todo_id=$todo_id");
} else {
	Refresh ("TodoAdd&project_id=$project_id&todo_addanother=1&todo[part]=".$todo["part"]);
}
?>

