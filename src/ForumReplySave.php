<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import("project_id" , "GP");
$topic_id   = Import("topic_id"   , "GP");
$thread_id  = Import("thread_id"  , "GP");
$reply      = Import("reply"      , "GP");
$newthread  = Import("newthread"  , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if ($topic_id == "" || $topic_id == 0) {
	if (!IsAuthorized($project_id, 'AUTH_FORUM_MODIFY')) {
		Error ("Access denied. You may not create new topics for this project", "back");
	}
}

if ($thread_id == "") {
	$newthread = 1;
	$thread_id = $topic_id;
}
if ($reply["subject"] == "Bug tracker") {
	Error ("You specified 'Bug tracker' as the subject. This subject is used internally by PROMS, and can't be specified by users. Please pick another subject.", "back");
}
if ($reply["notify"] != 1) {
	$reply["notify"] = 0;
}

$qry_insert  = "INSERT into forum SET ";
$qry_insert .= "project_id='"  .$project_id       ."', ";
$qry_insert .= "reply_to='"    .$thread_id        ."', ";
$qry_insert .= "user_id='"     .$user_id          ."', ";
$qry_insert .= "subject='"     .$reply["subject"] ."', ";
$qry_insert .= "contents='"    .$reply["contents"]."', ";
$qry_insert .= "postdate='"    .time()            ."', ";
$qry_insert .= "lastpostdate='".time()            ."', ";
$qry_insert .= "notify='"      .$reply["notify"]  ."'  ";

Debug ($qry_insert, __FILE__, __LINE__);
$rslt_insert = mysql_query ($qry_insert) or mysql_qry_error(mysql_error(), $qry_insert, __FILE__, __LINE__);

if ($newthread) {
	$new_thread_id = mysql_insert_id();
}

/* Update the last-post timer for this thread */
$qry_lastpostdate  = "UPDATE forum SET ";
$qry_lastpostdate .= "lastpostdate='".time()."' ";
$qry_lastpostdate .= "WHERE id='".$thread_id."'";
Debug ($qry_lastpostdate, __FILE__, __LINE__);
$rslt_lastpostdate = mysql_query ($qry_lastpostdate) or mysql_qry_error(mysql_error(), $qry_lastpostdate, __FILE__, __LINE__);

if ($newthread) {
	$thread_id = $new_thread_id;
}

/* Notify posters of new post */
$qry_project = "SELECT shortname FROM projects WHERE id='".$project_id."'";
Debug ($qry_project, __FILE__, __LINE__);
$rslt_project = mysql_query($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
$row_project = mysql_fetch_array($rslt_project);

$qry_parentpost = "SELECT subject FROM forum WHERE id='".$thread_id."'";
Debug ($qry_parentpost, __FILE__, __LINE__);
$rslt_parentpost = mysql_query($qry_parentpost) or mysql_qry_error(mysql_error(), $qry_parentpost, __FILE__, __LINE__);
$row_parentpost = mysql_fetch_array($rslt_parentpost);

/* Find all users that need to be notified */
$notify_users = array();

/* Project owner */
$qry_owner = "SELECT accounts.id, accounts.fullname, username, email FROM accounts LEFT JOIN projects p ON p.owner = accounts.id WHERE p.id='".$project_id."'";
Debug ($qry_owner);
$rslt_owner = mysql_query($qry_owner) or mysql_qry_error(mysql_error(), $qry_owner, __FILE__, __LINE__);
while ($row_owner = mysql_fetch_assoc($rslt_owner)) {
	$notify_users[$row_owner["id"]] = $row_owner;
}

/* Project members */
$qry_members = "SELECT accounts.id, accounts.fullname, username, email FROM accounts LEFT JOIN project_members ON project_members.account_id = accounts.id WHERE project_id='".$project_id."'";
Debug($qry_members, __FILE__, __LINE__);
$rslt_members = mysql_query($qry_members) or mysql_qry_error(mysql_error(), $qry_members, __FILE__, __LINE__);
while ($row_members = mysql_fetch_assoc($rslt_members)) {
	$notify_users[$row_members["id"]] = $row_members;
}

/* Users monitoring the thread */
$qry_notify = "SELECT DISTINCT accounts.id, accounts.fullname, accounts.fullname, accounts.email FROM accounts, forum WHERE forum.notify=1 AND (forum.reply_to='".$thread_id."' OR forum.id='".$thread_id."') AND forum.project_id='".$project_id."' AND accounts.id=forum.user_id";
Debug ($qry_notify, __FILE__, __LINE__);
$rslt_notify = mysql_query($qry_notify) or mysql_qry_error(mysql_error(), $qry_notify, __FILE__, __LINE__);
while ($row_notify = mysql_fetch_assoc($rslt_notify)) {
	$notify_users[$row_notify["id"]] = $row_notify;
}

if (count($notify_users) > 0) {
	/* Build message */
	$notify["bcc"] = "Bcc: ";
	foreach ($notify_users as $notify_user) {
		$notify["bcc"] .= "\"".$notify_user["fullname"]."\" <".$notify_user["email"].">,";
	}

	Debug ("Mail: ".$notify["bcc"], __FILE__, __LINE__);
	$notify["bcc"] = substr($notify["bcc"], 0, strlen($announcement["bcc"])-1);

	$notify["headers"] = "From: \"PROMS Forum notifier\" <".PROMS_EMAIL.">\r\n";
	$notify["subject"] = $row_project["shortname"]." Forum: Someone replied to the post \"".$row_parentpost["subject"]."\".";
	$notify["body"] = "Follow this URL to view the topic: \n\nhttp://".$_SERVER["HTTP_HOST"].$PHP_SELF."?action=ForumView&project_id=".$project_id."&topic_id=".$topic_id."&thread_id=".$thread_id;

	Debug ("Mail: Headers: ".$notify["headers"], __FILE__, __LINE__);
	Debug ("Mail: Subject: ".$notify["subject"], __FILE__, __LINE__);
	Debug ("Mail: Body: ".$notify["body"], __FILE__, __LINE__);
	Debug ("Mail: BCC: ".$notify["bcc"], __FILE__, __LINE__);

	$sent = smtpmail (
		"\"PROMS Forum notifier\" <".PROMS_EMAIL.">",
		$notify["subject"],
		$notify["body"],
		$notify["headers"].$notify["bcc"]);

	if (!($sent === TRUE)) {
		Error("Email notification about this new reply couldn't be sent. Please alert the administrator.");
	}
}

Refresh ("ForumView&project_id=".$project_id."&topic_id=".$topic_id."&thread_id=".$thread_id);
?>
