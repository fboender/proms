<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$topic      = Import ("topic"      , "GP");
$topic_id   = Import ("topic_id"   , "GP");
$wizard     = Import ("wizard"     , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsProjectOwner($project_id)) {
	Error ("Access denied. You're not the project owner", "back");
}

if ($topic_id != "") {
	$qry_update  = "UPDATE forum SET ";
	$qry_update .= "subject='"     .$topic["subject"] ."',";
	$qry_update .= "contents='"    .$topic["contents"]."',";
	$qry_update .= "lastpostdate='".time()            ."' ";
	$qry_update .= "WHERE id='"    .$topic_id         ."' ";

	Debug ($qry_update, __FILE__, __LINE__);
	$rslt_update = mysql_query ($qry_update) or mysql_qry_error(mysql_error(), $qry_update, __FILE__, __LINE__);
} else {
	$qry_insert  = "INSERT into forum SET ";
	$qry_insert .= "project_id='"  .$project_id       ."', ";
	$qry_insert .= "reply_to=0, ";
	$qry_insert .= "user_id=' "    .$user_id          ."', ";
	$qry_insert .= "subject='"     .$topic["subject"] ."', ";
	$qry_insert .= "contents='"    .$topic["contents"]."', ";
	$qry_insert .= "postdate='"    .time()            ."', ";
	$qry_insert .= "lastpostdate='".time()            ."'  ";

	Debug ($qry_insert, __FILE__, __LINE__);
	$rslt_insert = mysql_query ($qry_insert) or mysql_qry_error(mysql_error(), $qry_insert, __FILE__, __LINE__);
}

Refresh ("ForumTopicList&project_id=".$project_id."&wizard=".$wizard);
?>
