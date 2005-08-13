<? 
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id", "GP");
$todo_id    = Import ("todo_id"   , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (isset($todo_id) && !empty($todo_id)) {
	if (!IsAuthorized($project_id, AUTH_TODO_MODIFY)) {
		Error ("You are not authorized to modify todos", "back");
	}
}

foreach ($todo as $key => $value) {
	$qry_del = "DELETE FROM todos WHERE id='".$key."'";
	Debug($qry_del, __FILE__, __LINE__);
	$rslt_del = mysql_query($qry_del) or mysql_qry_error(mysql_error(), $qry_del, __FILE__, __LINE__);
}

Refresh ("TodoList&project_id=$project_id");
