<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id  = Import ("project_id"  , "GP");
$todo_search = Import ("todo_search" , "SGP");
$todo_limit  = Import ("todo_limit"  , "SGP"   , array("top" =>"0"));
$todo_sort   = Import ("todo_sort"   , "SGP");

if ($todo_limit["project_id"] != $project_id) {	
	/* Reset top when viewing a different project*/
	$todo_limit["top"] = 0;
	$todo_limit["project_id"] = $project_id;
}

if (!is_numeric($todo_limit["top"])) {
	Error("List limit error", "exit");
}

$_SESSION["todo_search"] = $todo_search;
$_SESSION["todo_limit"] = $todo_limit;
$_SESSION["todo_sort"] = $todo_sort;

/* Retrieve project details */
if (isset($project_id)) {
$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
	$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
	$project = mysql_fetch_assoc($rslt_project);
}
 
/* Retrieve lookup tables */
$priorities = ReadLookup ("todo_priority", "title");
$parts = ReadLookup ("project_parts", "title", "WHERE project_id=".$project_id);
$todo_statuses = ReadLookup ("todo_statuses", "name");


/* Build a query which will match the search data to the database data */
if (isset($todo_search["todo_nr"]) && $todo_search["todo_nr"] != "") { 
	$qry_search .= "todo_nr = '" . $todo_search["todo_nr"] . "' AND "; 
}
if (isset($todo_search["containing"]) && $todo_search["containing"] != "")  { 

	$qry_search .= "( ";

	/* 'containing' field will be searched for in 2 database fields */
	$subject_parts = explode (' ',$todo_search["containing"]);
	$qry_search .= " (";
	foreach ($subject_parts as $subject_part) {
		$qry_search .= "subject LIKE '%" . $subject_part . "%' AND ";
	}
	$qry_search = substr($qry_search, 0, strlen($qry_search) - 4);
	$qry_search .= ") OR (";
	foreach ($subject_parts as $subject_part) {
		$qry_search .= " description LIKE '%" . $subject_part . "%' AND ";
	}
	$qry_search = substr($qry_search, 0, strlen($qry_search) - 4);
	$qry_search .= ") ";
	
	$qry_search .= ") AND ";
}

if (isset($todo_search["status"]) && $todo_search["status"] != "") { $qry_search .= "done = '" . $todo_search["status"] . "' AND "; }
if (isset($todo_search["priority"]) && $todo_search["priority"] != "") { $qry_search .= "priority = '" . $todo_search["priority"] . "' AND "; }
if (isset($todo_search["part"]) && $todo_search["part"] != "") { $qry_search .= "part = '" . $todo_search["part"] . "' AND "; }

if (isset($qry_search)) {
	/* Strip off last 'AND' */
	$qry_search = substr($qry_search, 0, strlen($qry_search) - 4);
}

/* Build default query which will be appended to count and limit queries */
$qry_results = "FROM todos WHERE project_id='".$project_id."'";
if (isset($qry_search)) { 
	$qry_results .= " AND (". $qry_search." ) "; 
}

if (isset($todo_sort) && array_key_exists("col", $todo_sort)) {
	$qry_results .= " ORDER BY '".$todo_sort["col"]."' ";
	if ($todo_sort["dir"] == "desc") {
		$qry_results .= " DESC ";
	} else {
		$qry_results .= " ASC ";
	}
}

/* Retrieve total number of results */
$qry_results_count = "SELECT count(id) ".$qry_results;
Debug ($qry_results_count, __FILE__, __LINE__);
$rslt_results_count = mysql_query($qry_results_count) or mysql_qry_error(mysql_error(), $qry_results_count, __FILE__, __LINE__);
$results = mysql_fetch_array($rslt_results_count);


/* Retrieve limited number of results */
$qry_todos = "SELECT * ".$qry_results." LIMIT ".$todo_limit["top"].",".TODOLIST_SHOW_NR_OF_TODOS;
Debug ($qry_todos, __FILE__, __LINE__);
$rslt_todos = mysql_query ($qry_todos) or mysql_qry_error(mysql_error(), $qry_todos, __FILE__, __LINE__);

?>
<form method="post" action="<?=$PHP_SELF?>">
	<input type="hidden" name="project_id" value="<?=$project_id?>">
	<input type="hidden" name="action" value="TodoList">
	<table>
		<tr>
			<td><b>Todo # :</b></td>
			<td>
				<input type="text" name="todo_search[todo_nr]" value="<?=$todo_search["todo_nr"]?>" size="5"> &nbsp;
				<b>Containing : </b><input type="text" name="todo_search[containing]" value="<?=$todo_search["containing"]?>"> &nbsp;
			</td>
		</tr>
		<tr>
			<td><b>Status :</b></td>
			<td>
				<select name="todo_search[status]">
					<option value=""></option>
					<?
					foreach ($todo_statuses as $key=>$value) {
						if ($todo_search["status"] == $key) {
							?><option value="<?=$key?>" selected> <?=$value?><?
						} else {
							?><option value="<?=$key?>"> <?=$value?><?
						}
					}
					?>
				</select> &nbsp;
				<b>Priority : </b><select name="todo_search[priority]">
				<option value="">
				<?
				if (is_array($priorities)) {
					foreach ($priorities as $id => $priority) {
						if ($todo_search["priority"] == $id) {
							?><option value="<?=$id?>" selected><?=$priority?><?
						} else {
							?><option value="<?=$id?>"><?=$priority?><?
						}
					}
				}
				?>
				</select> &nbsp;
				<b>Part : </b><select name="todo_search[part]">
				<option value="">
				<?
				if (is_array($parts)) {
					foreach ($parts as $id => $part) {
						if ($todo_search["part"] == $id) {
							?><option value="<?=$id?>" selected><?=$part?><?
						} else {
							?><option value="<?=$id?>"><?=$part?><?
						}
					}
				}
				?>
				</select> &nbsp; &nbsp;
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" class="action" value="Find">  &nbsp;
				<a class="action" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?>&todo_search[]=">All Todos</a> &nbsp;
			</td>
		</tr>
	</table>
</form>
<?
  

/* Build the search part of the url */
if ($todo_search["todo_nr"] != "")   { $url .= "&todo_search[todo_nr]="   .$todo_search["todo_nr"]; }
if ($todo_search["subject"] != "")  { 
	$subject_parts = explode ($todo_search["subject"], ' ');
	foreach ($subject_parts as $subject_part) {
		$url .= "&todo_search[subject]"   .$todo_search["subject"]; 
	}
}
if ($todo_search["status"] != "")   { $url .= "&todo_search[status]="   .$todo_search["status"]; }
if ($todo_search["priority"] != "") { $url .= "&todo_search[priority]=" .$todo_search["priority"]; }
if ($todo_search["part"] != "")     { $url .= "&todo_search[part]="     .$todo_search["part"]; }

$sort_dir["todo_nr"] = "asc";
$sort_dir["done"] = "asc";
$sort_dir["priority"] = "asc";
$sort_dir["part"] = "asc";
$sort_dir["subject"] = "asc";
$sort_dir["lastmoddate"] = "asc";

if ($todo_sort["dir"] == "asc") {
	$sort_arrow[$todo_sort["col"]] = "<img src=\"images/ico_sortarr_d.gif\" border=0>";
	$sort_dir[$todo_sort["col"]] = "desc";
} else {
	$sort_arrow[$todo_sort["col"]] = "<img src=\"images/ico_sortarr_u.gif\" border=0>";
	$sort_dir[$todo_sort["col"]] = "asc";
}

$nrrows_todos = mysql_num_rows($rslt_todos);
if ($nrrows_todos == 0) {
	?><p><i>No todos matching these criteria have been reported yet</i></p><?
} else {
	?>
	  <form method="post">
	  <input type="hidden" name="action" value="TodoDone">
	  <input type="hidden" name="project_id" value="<?=$project_id?>">

	<table cellspacing="1" cellpadding="3">
	<tr valign="top">
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?><?=$url?>&todo_sort[col]=todo_id&todo_sort[dir]=<?=$sort_dir["todo_nr"]?>">Todo # <?=$sort_arrow["todo_nr"]?></a></td>
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?><?=$url?>&todo_sort[col]=done&todo_sort[dir]=<?=$sort_dir["done"]?>">Status <?=$sort_arrow["done"]?></a></td>   
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?><?=$url?>&todo_sort[col]=priority&todo_sort[dir]=<?=$sort_dir["priority"]?>">Priority <?=$sort_arrow["priority"]?></a></td>
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?><?=$url?>&todo_sort[col]=part&todo_sort[dir]=<?=$sort_dir["part"]?>">Part <?=$sort_arrow["part"]?></a></td>
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?><?=$url?>&todo_sort[col]=subject&todo_sort[dir]=<?=$sort_dir["subject"]?>">Subject <?=$sort_arrow["subject"]?></a></td>  
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=TodoList&project_id=<?=$project_id?><?=$url?>&todo_sort[col]=lastmoddate&todo_sort[dir]=<?=$sort_dir["lastmoddate"]?>">Last mod date <?=$sort_arrow["lastmoddate"]?></a></td>
	<?
	if (IsAuthorized($project_id, AUTH_TODO_MODIFY)) {
		?><th class="head" colspan="2">&nbsp;</td><?
	}
	?>
	</tr>
	<?   
	     
	$row_color = "#d0d0d0";
	while ($row_todos = mysql_fetch_assoc($rslt_todos)) {
		if ($row_todos["done"] == 2) {
			$disabled = "disabled";
		} else {
			$disabled = "";
		}
		?>
		<tr valign="top" bgcolor="<?=$row_color?>">
		<td><a href="<?=$PHP_SELF?>?action=TodoOverview&project_id=<?=$project_id?>&todo_id=<?=$row_todos["id"]?>"><?=str_repeat("0", 4-strlen($row_todos["id"])).$row_todos["id"]?></a></td>
		<td><?=$todo_statuses[$row_todos["done"]]?></td>
		<td><?=$priorities[$row_todos["priority"]]?></td>
		<td><?=$parts[$row_todos["part"]]?></td>
		<td><?=htmlentities($row_todos["subject"])?></td>
		<td><?=date("d M 'y", $row_todos["lastmoddate"])?></td>
		<?
		if (IsAuthorized($project_id, AUTH_TODO_MODIFY)) {
			?>
			<td><a href="<?=$PHP_SELF?>?action=TodoMod&project_id=<?=$project_id?>&todo_id=<?=$row_todos["id"]?>"><img src="images/ico_edit.gif" alt="Edit" title="Edit this todo" border="0"></a></td>
			<td><input type="checkbox" name="todo[<?=$row_todos["id"]?>]" value="x" <?=$disabled?> style="border:0px;margin:0px;padding:0px;"></td>
			<?
		}
		?>
		</tr>
		<?   
		if ($row_color == "#d0d0d0") {
			$row_color = "#e0e0e0";
		} else {
			$row_color = "#d0d0d0";
		}
	}
	 
	?>
	<tr>
		<td colspan="7">
			<?
				$url_nav = $PHP_SELF."?action=TodoList&project_id=".$project_id.$url;
				ListNavigation ($url_nav."&todo_limit[project_id]=".$project_id."&todo_limit[top]=", $todo_limit["top"], TODOLIST_SHOW_NR_OF_TODOS, $results[0]);
			?>
		</td>
		<?
		if (IsAuthorized($project_id, AUTH_TODO_MODIFY)) {
			?>
			<td colspan="2">
				<input class="action" type="submit" value="Done">
			</td>
			<?
		}
		?>
	</tr>
	</table>
	</form>

	<b>Showing Todos</b>:
	<b><?=($todo_limit["top"]+1)?></b>
	to 
	<b><?=($todo_limit["top"]+$nrrows_todos)?></b>
	(<b><?=$results[0]?></b> total todos matching the criteria)<br><br>
	<?
}
 
?>
<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project details</a> &nbsp;
<?
	if (IsAuthorized($project_id, 'AUTH_TODO_CREATE')) {
		?>
		<a class="action" href="<?=$PHP_SELF?>?action=TodoAdd&project_id=<?=$project_id?>">Add Todo</a> &nbsp;
		<?
	}
?>
<?
  

