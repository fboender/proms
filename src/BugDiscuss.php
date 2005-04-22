<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.
*/
/*
	Topic  = The main forum topics for each project. Each project should have a 'bugs' 
	         topic. If it doesn't exist yet, this script will create one automatically
	Thread = A topic can sustain multiple threads. Each thread discusses one issue which 
	         belongs a certain topic. Every user can create a new thread in a topic.
*/

$bug_id     = Import ("bug_id"     , "GP");
$project_id = Import ("project_id" , "GP");
$bug        = Import ("bug"        , "GP");

/* Security check follows later */

/* Passed data check */
if ($bug_id == "") {
	Error ("No bug specified.", "back");
}

/* Retrieve bug information */
$qry_bug = "SELECT * FROM bugs WHERE id='".$bug_id."'";
Debug ($qry_bug, __FILE__, __LINE__);
$rslt_bug = mysql_query($qry_bug) or mysql_qry_error(mysql_error(), $qry_bug, __FILE__, __LINE__);
$bug = mysql_fetch_assoc($rslt_bug);

/* Find 'Bugs' topic */
$qry_bugstopicid = "SELECT id FROM forum WHERE project_id='".$project_id."' AND reply_to=0 AND subject='Bugs'";
Debug ($qry_bugstopicid, __FILE__, __LINE__);
$rslt_bugstopicid = mysql_query($qry_bugstopicid) or mysql_qry_error(mysql_error(), $qry_bugstopicid, __FILE__, __LINE__);

$row_bugstopicid = mysql_fetch_assoc($rslt_bugstopicid);
$bugstopicid = $row_bugstopicid["id"];

if (!$bugstopicid) {
	/* The forum doesn't have a topic for bugs yet */
	if (!IsLoggedIn()) {
		Error ("Access denied. Must be logged in to create a discussion.", "back");
	}
	$qry_createbugstopic = "INSERT INTO forum SET project_id='".$project_id."', reply_to=0, user_id='".$user_id."', subject='Bugs', postdate='".time()."', lastpostdate='".time()."'";
	$rslt_createbugstopic = mysql_query($qry_createbugstopic) or mysql_qry_error(mysql_error(), $qry_createbugstopic, __FILE__, __LINE__);
	$bugstopicid = mysql_insert_id();
}
	
/* Check if bug already has a forum thread */
$qry_bugthreadid = "SELECT * FROM forum WHERE project_id='".$project_id."' AND subject LIKE 'Bug #".str_repeat("0", 4-strlen($bug["bug_nr"])).$bug["bug_nr"]."%';";
Debug ($qry_bugthreadid, __FILE__, __LINE__);
$rslt_bugthreadid = mysql_query($qry_bugthreadid) or mysql_qry_error(mysql_error(), $qry_bugthreadid, __FILE__, __LINE__);

if (mysql_num_rows($rslt_bugthreadid) > 0) {
	/* Bug has a thread in the forum.*/
	$row_bugthreadid = mysql_fetch_assoc($rslt_bugthreadid);
	$bugthreadid = $row_bugthreadid["id"];
} else {
	if (!IsLoggedIn()) {
		Error ("Access denied. Must be logged in to create a discusion.", "back");
	}

	/* Bug doesn't have a thread in the forum. Create one */
	$qry_newthread  = "INSERT into forum SET ";
	$qry_newthread .= "project_id='".$project_id       ."', ";
	$qry_newthread .= "reply_to='"  .$bugstopicid      ."', ";
	$qry_newthread .= "user_id='"   .$bug["user_id"]          ."', ";
	$qry_newthread .= "subject='Bug #".str_repeat("0", 4-strlen($bug["bug_nr"])).$bug["bug_nr"]." ".nl2br(addslashes($bug["subject"])) ."', ";
	$qry_newthread .= "contents='" .nl2br(addslashes($bug["description"]))."', ";
	$qry_newthread .= "postdate='".time()."', ";
	$qry_newthread .= "lastpostdate='".time()."' ";
	Debug ($qry_newthread, __FILE__, __LINE__);
	$rslt_newthread = mysql_query ($qry_newthread) or mysql_qry_error(mysql_error(), $qry_newthread, __FILE__, __LINE__);
	$bugthreadid = mysql_insert_id();
}

Refresh ("ForumView&project_id=".$project_id."&topic_id=".$bugstopicid."&thread_id=".$bugthreadid);
?>
