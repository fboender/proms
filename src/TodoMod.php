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

/* Retrieve project details */
$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
$project = mysql_fetch_assoc($rslt_project);

/* Retrieve bug information */
$qry_todo = "SELECT * FROM todos WHERE id='".$todo_id."'";
Debug($qry_todo, __FILE__, __LINE__);
$rslt_todo = mysql_query ($qry_todo) or mysql_qry_error(mysql_error(), $qry_todo, __FILE__, __LINE__);
$todo = mysql_fetch_assoc($rslt_todo);
  
?>
<table>
<form method="post" action="<?=$PHP_SELF?>">
<?
InputHidden   (                        "action"            , "TodoSave"); 
InputHidden   (                        "project_id"        , $project_id);
InputHidden   (                        "todo[user_id]"     , $todo["user_id"]);
InputHidden   (                        "todo_id"           , $todo["id"]);
InputDropDown ("Status",               "todo[done]"        , $todo["done"]    , "todo_statuses");
InputDropDown ("Priority",             "todo[priority]"    , $todo["priority"], "todo_priority");
InputDropDown ("Part",                 "todo[part]"        , $todo["part"]    , "project_parts", "WHERE project_id=".$project_id);
InputText     ("Subject",              "todo[subject]"     , $todo["subject"]);
InputArea     ("Description",          "todo[description]" , $todo["description"]);
InputSubmit   ("Save");
?>
</form> 
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?>">&lt; Todo list</a> &nbsp;
