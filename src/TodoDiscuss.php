<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$todo_id    = Import ("todo_id"    , "GP");
$project_id = Import ("project_id" , "GP");

/* Security check follows later */

$qry_todo = "SELECT * FROM todos WHERE id='".$todo_id."'";
Debug ($qry_todo, __FILE__, __LINE__);
$rslt_todo = mysql_query($qry_todo) or mysql_qry_error(mysql_error(), $qry_todo, __FILE__, __LINE__);
$todo = mysql_fetch_assoc($rslt_todo);

/* Find 'Todos' topic */
$qry_todostopicid = "SELECT id FROM forum WHERE project_id='".$project_id."' AND reply_to=0 AND subject='Todos'";
Debug ($qry_todostopicid, __FILE__, __LINE__);
$rslt_todostopicid = mysql_query($qry_todostopicid) or mysql_qry_error(mysql_error(), $qry_todostopicid, __FILE__, __LINE__);
$row_todostopicid = mysql_fetch_assoc($rslt_todostopicid);
$todostopicid = $row_todostopicid["id"];
if (!$todostopicid) {
	if (!IsLoggedIn()) {
		Error ("Access denied. Must be logged in to create a discussion.", "back");
	}
	$qry_createtodostopic = "INSERT INTO forum SET project_id='".$project_id."', reply_to=0, user_id='".$user_id."', subject='Todos', postdate='".time()."',lastpostdate='".time()."'";
	$rslt_createtodostopic = mysql_query($qry_createtodostopic) or mysql_qry_error(mysql_error(), $qry_createtodostopic, __FILE__, __LINE__);
	$todostopicid = mysql_insert_id();
}
	
/* Check if todo already has a forum thread */
$qry_todothreadid = "SELECT * FROM forum WHERE subject LIKE 'Todo #".str_repeat("0", 4-strlen($todo["todo_nr"])).$todo["todo_nr"]."%';";
Debug ($qry_todothreadid, __FILE__, __LINE__);
$rslt_todothreadid = mysql_query($qry_todothreadid) or mysql_qry_error(mysql_error(), $qry_todothreadid, __FILE__, __LINE__);
if (mysql_num_rows($rslt_todothreadid) > 0) {
	/* Todo has a thread in the forum.*/
	$row_todothreadid = mysql_fetch_assoc($rslt_todothreadid);
	$todothreadid = $row_todothreadid["id"];
} else {
	/* Todo doesn't have a thread in the forum. Create one */
	if (!IsLoggedIn()) {
		Error ("Access denied. Must be logged in to create a discussion.", "back");
	}
	$qry_newthread  = "INSERT into forum SET ";
	$qry_newthread .= "project_id='"   .$project_id                                ."', ";
	$qry_newthread .= "reply_to='"     .$todostopicid                              ."', ";
	$qry_newthread .= "user_id='"      .$todo["user_id"]                           ."', ";
	$qry_newthread .= "subject='Todo #".str_repeat("0", 4-strlen($todo["todo_nr"])).$todo["todo_nr"] . " " . nl2br(addslashes($todo["subject"])) . "', ";
	$qry_newthread .= "contents='"     .nl2br(addslashes($todo["description"]))    ."', ";
	$qry_newthread .= "postdate='"     .time()                                     ."', ";
	$qry_newthread .= "lastpostdate='" .time()                                     ."'  ";
	Debug ($qry_newthread, __FILE__, __LINE__);
	$rslt_newthread = mysql_query ($qry_newthread) or mysql_qry_error(mysql_error(), $qry_newthread, __FILE__, __LINE__);
	$todothreadid = mysql_insert_id();
}

Refresh ("ForumView&project_id=".$project_id."&topic_id=".$todostopicid."&thread_id=".$todothreadid);
?>
