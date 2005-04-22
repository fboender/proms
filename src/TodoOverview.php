<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$todo_id    = Import("todo_id"     , "GP");

/* Retrieve project details */
if (isset($project_id)) {
	$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
	$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
	$project = mysql_fetch_assoc($rslt_project);
}

$qry_todo = "SELECT * FROM todos WHERE id='".$todo_id."'";

Debug ($qry_todo, __FILE__, __LINE__);
$rslt_todo = mysql_query ($qry_todo) or mysql_qry_error(mysql_error(), $qry_todo, __FILE__, __LINE__);
$row_todo = mysql_fetch_assoc($rslt_todo);

$users = ReadLookup ("accounts", "username");
$priorities = ReadLookup ("todo_priority", "title");
$projectparts = ReadLookup ("project_parts", "title", "WHERE project_id=".$row_todo["project_id"]);
$todo_statuses = ReadLookup ("todo_statuses", "name");
   
?>  
<br>

<font size="+1">Todo #<?=str_repeat("0", 4-strlen($row_todo["todo_nr"])).$row_todo["todo_nr"]?> : <?=htmlentities($row_todo["subject"])?><br></font>
<b>By user : </b> <a href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$row_todo["user_id"]?>"><?=$users[$row_todo["user_id"]]?></a>

<br><br>
<table>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Status :</th><td><?=$todo_statuses[$row_todo["done"]]?></td></tr>
<tr valign="top"><th class="head">Priority :</th><td><?=$priorities[$row_todo["priority"]]?></td></tr>
<tr valign="top"><th class="head">Part :</th><td><?=$projectparts[$row_todo["part"]]?></td></tr>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Last mod date :</th><td><?=date("d M Y H:i", $row_todo["lastmoddate"])?></td></tr>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Description :</th><td bgcolor="#e0e0e0"><?=nl2br(htmlentities($row_todo["description"]))?></td></tr>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?>">&lt; Todo List</a> &nbsp;
<a class="action" href="<?=$PHP_SELF?>?action=TodoDiscuss&project_id=<?=$project_id?>&todo_id=<?=$row_todo["id"]?>">Discuss this todo</a> &nbsp;
<?
	if (IsAuthorized($project_id, 'AUTH_TODO_CREATE')) {
		?>
		<a class="action" href="<?=$PHP_SELF?>?action=TodoAdd&project_id=<?=$project_id?>">Add Todo</a> &nbsp;
		<?
	}
	if (IsAuthorized($project_id, 'AUTH_TODO_MODIFY')) {
		?>
		<a class="action" href="<?=$PHP_SELF?>?action=TodoMod&project_id=<?=$project_id?>&todo_id=<?=$row_todo["id"]?>">Modify todo</a> &nbsp;
		<a class="action" href="<?=$PHP_SELF?>?action=TodoDone&project_id=<?=$project_id?>&todo[<?=$row_todo["id"]?>]=x">Done</a> &nbsp;
		<?
	}
?>
