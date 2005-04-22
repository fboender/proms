<? 
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id      = Import ("project_id"      , "GP");
$todo            = Import ("todo"            , "GP");
$todo_addanother = Import ("todo_addanother" , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}

$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
$project = mysql_fetch_assoc($rslt_project);

?>     
<br>   
<table>
<form method="post" action="<?=$PHP_SELF?>">
<?
InputHidden   ("action"           , "TodoSave");
InputHidden   ("project_id"       , $project_id);
InputHidden   ("todo[done]"       , "1");
InputDropDown ("Priority"         , "todo[priority]"    , $todo["priority"]  , "todo_priority");
InputDropDown ("Part"             , "todo[part]"        , $todo["part"]      , "project_parts"   , "WHERE project_id=".$project_id);
InputText     ("Subject"          , "todo[subject]"     , "");
InputArea     ("Description"      , "todo[description]" , "");
InputSubmit   ("Save");
InputCheckbox ("Add another todo" , "todo_addanother"   , $todo_addanother);
?>
</form> 
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?>">&lt; Todo list</a> &nbsp;
