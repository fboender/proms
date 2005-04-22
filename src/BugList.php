<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$bug_search = Import ("bug_search" , "SGP");
$bug_limit  = Import ("bug_limit"  , "SGP"   , array("top" =>"0") );
$bug_sort   = Import ("bug_sort"   , "SGP");

if ($bug_limit["project_id"] != $project_id) {
	/* Reset top when viewing a different project */
	$bug_limit["top"] = 0;
	$bug_limit["project_id"] = $project_id;
}
$_SESSION["bug_search"] = $bug_search;
$_SESSION["bug_limit"] = $bug_limit;
$_SESSION["bug_sort"] = $bug_sort;

/* Passed data check */
if ($bug_search["bug_nr"] != "") {
	$bug_search["bug_nr"] = (int) $bug_search["bug_nr"];
}
if (!is_numeric($bug_limit["top"])) {
	Error("List limit error","exit");
}

/* Retrieve project details */
if (isset($project_id)) {
	$qry_project = "SELECT * FROM projects WHERE id='".$project_id."'";
	$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
	$project = mysql_fetch_assoc($rslt_project);
}

/* Retrieve lookup tables */
$severities = ReadLookup ("bugs_severity", "title");
$statuses = ReadLookup ("bugs_statuses", "name");
$parts = ReadLookup ("project_parts", "title", "WHERE project_id=".$project_id);
$versions = ReadLookup ("project_releases", "version", "WHERE project_id=".$project_id);

/* Build a query which will match the search data to the database data */
if (isset($bug_search["bug_nr"]) && $bug_search["bug_nr"] != "")   { $qry_search .= "bug_nr='" . $bug_search["bug_nr"] . "' AND "; }
if (isset($bug_search["containing"]) && $bug_search["containing"] != "")  { 

	$qry_search .= "( ";
	
	/* 'containing' field will be searched for in 4 database fields */
	$subject_parts = explode (' ',$bug_search["containing"]);
	$qry_search .= " (";
	foreach ($subject_parts as $subject_part) {
		$qry_search .= "subject LIKE '%" . $subject_part . "%' AND ";
	}
	$qry_search = substr($qry_search, 0, strlen($qry_search) - 4);
	$qry_search .= ") OR (";
	foreach ($subject_parts as $subject_part) {
		$qry_search .= "description LIKE '%" . $subject_part . "%' AND ";
	}
	$qry_search = substr($qry_search, 0, strlen($qry_search) - 4);
	$qry_search .= ") OR (";
	foreach ($subject_parts as $subject_part) {
		$qry_search .= "reproduction LIKE '%" . $subject_part . "%' AND ";
	}
	$qry_search = substr($qry_search, 0, strlen($qry_search) - 4);
	$qry_search .= ") OR (";
	foreach ($subject_parts as $subject_part) {
		$qry_search .= "software LIKE '%" . $subject_part . "%' AND ";
	}
	$qry_search = substr($qry_search, 0, strlen($qry_search) - 4);
	$qry_search .= ") ";
	
	$qry_search .= ") AND ";
}
if (isset($bug_search["status"]) && $bug_search["status"] != "") { $qry_search .= "status = '" . $bug_search["status"] . "' AND "; }
if (isset($bug_search["severity"]) && $bug_search["severity"] != "") { $qry_search .= "severity = '" . $bug_search["severity"] . "' AND "; }
if (isset($bug_search["part"]) && $bug_search["part"] != "") { $qry_search .= "part = '" . $bug_search["part"] . "' AND "; }
if (isset($bug_search["version"]) && $bug_search["version"] != "") { $qry_search .= "version = '" . $bug_search["version"] . "' AND "; }

if (isset($qry_search)) {
	/* Strip off last 'AND' */
	$qry_search = substr($qry_search, 0, strlen($qry_search) - 4);
}

/* Build default query which will be appended to count and limit queries */
$qry_results = "FROM bugs WHERE project_id='".$project_id."' ";
if (isset($qry_search)) { 
	$qry_results .= " AND (". $qry_search." ) "; 
}

if (isset($bug_sort) && array_key_exists("col", $bug_sort)) {
	$qry_results .= " ORDER BY '".$bug_sort["col"]."' ";
	Debug ($bug_sort["dir"]);
	if ($bug_sort["dir"] == "desc") {
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
$qry_bugs = "SELECT * ".$qry_results." LIMIT ".$bug_limit["top"].",".BUGLIST_SHOW_NR_OF_BUGS;
Debug ($qry_bugs, __FILE__, __LINE__);
$rslt_bugs = mysql_query ($qry_bugs) or mysql_qry_error(mysql_error(), $qry_bugs, __FILE__, __LINE__);

?>
	<form method="post" action="<?=$PHP_SELF?>">
		<input type="hidden" name="project_id" value="<?=$project_id?>">
		<input type="hidden" name="action" value="BugList">
		<input type="hidden" name="bug_limit[top]" value="0">

		<table>
		<tr>
			<td><b>Bug # :</b></td>
			<td>
				<input type="text" name="bug_search[bug_nr]" value="<?=$bug_search["bug_nr"]?>" size="5"> &nbsp;
				<b>Containing : </b><input type="text" name="bug_search[containing]" value="<?=$bug_search["containing"]?>">
			</td>
		</tr>
		<tr>
			<td><b>Status :</b></td>
			<td><select name="bug_search[status]">
				<option value="">
				<?
					if (is_array($statuses)) {
						foreach ($statuses as $id => $status) {
							if ($bug_search["status"] == $id) {
								?><option value="<?=$id?>" selected><?=$status?><?
							} else {
								?><option value="<?=$id?>"><?=$status?><?
							}
						}
					}
				?>
				</select> &nbsp;
				<b>Severity : </b><select name="bug_search[severity]">
				<option value="">
				<?
					if (is_array($severities)) {
						foreach ($severities as $id => $severity) {
							if ($bug_search["severity"] == $id) {
								?><option value="<?=$id?>" selected><?=$severity?><?
							} else {
								?><option value="<?=$id?>"><?=$severity?><?
							}
						}
					}
				?>
				</select> &nbsp;
				<b>Part : </b><select name="bug_search[part]">
				<option value="">
				<?
					if (is_array($parts)) {
						foreach ($parts as $id => $part) {
							if ($bug_search["part"] == $id) {
								?><option value="<?=$id?>" selected><?=$part?><?
							} else {
								?><option value="<?=$id?>"><?=$part?><?
							}
						}
					}
				?>
				</select> &nbsp; 
				<b>Version : </b><select name="bug_search[version]">
				<option value="">
				<?
					if (is_array($versions)) {
						foreach ($versions as $id => $version) {
							if ($bug_search["version"] == $version) {
								?><option value="<?=$version?>" selected><?=$version?><?
							} else {
								?><option value="<?=$version?>"><?=$version?><?
							}
						}
					}
				?>
				</select> &nbsp; &nbsp;<br>
			</td>
		</tr>
		<td>&nbsp;</td<td><input type="submit" class="action" value="Find">  &nbsp;
		<a class="action" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?>&bug_search[]=">All bugs</a> &nbsp;</td>
	</table>
	</form>
<?

/* Build the filter part of the url */
if ($bug_search["bug_nr"] != "")   { 
	$url .= "&bug_search[bug_nr]="   .$bug_search["bug_nr"]; 
}
if ($bug_search["subject"] != "")  { 
	$subject_parts = explode ($bug_search["subject"], ' ');
	foreach ($subject_parts as $subject_part) {
		$url .= "&bug_search[subject]"   .$bug_search["subject"]; 
	}
}
if ($bug_search["status"]   != "") { $url .= "&bug_search[status]="   .$bug_search["status"];   }
if ($bug_search["severity"] != "") { $url .= "&bug_search[severity]=" .$bug_search["severity"]; }
if ($bug_search["part"]     != "") { $url .= "&bug_search[part]="     .$bug_search["part"];     }


$sort_dir["bug_nr"] = "asc";
$sort_dir["status"] = "asc";
$sort_dir["severity"] = "asc";
$sort_dir["part"] = "asc";
$sort_dir["subject"] = "asc";
$sort_dir["reportdate"] = "asc";
$sort_dir["lastmoddate"] = "asc";

if ($bug_sort["dir"] == "asc") {
	$sort_arrow[$bug_sort["col"]] = "<img src=\"images/ico_sortarr_d.gif\" border=0>";
	$sort_dir[$bug_sort["col"]] = "desc";
} else {
	$sort_arrow[$bug_sort["col"]] = "<img src=\"images/ico_sortarr_u.gif\" border=0>";
	$sort_dir[$bug_sort["col"]] = "asc";
}

$nrrows_bugs = mysql_num_rows($rslt_bugs);
if ($nrrows_bugs == 0) {
	?><p><i>No bugs matching these criteria have been reported yet</i></p><?
} else {
	?>
	<table cellspacing="1" cellpadding="3">
	<tr valign="top">
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?><?=$url?>&bug_sort[col]=bug_nr&bug_sort[dir]=<?=$sort_dir["bug_nr"]?>"     >Bug #         <?=$sort_arrow["bug_nr"]     ?></a></td>
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?><?=$url?>&bug_sort[col]=status&bug_sort[dir]=<?=$sort_dir["status"]?>"     >Status        <?=$sort_arrow["status"]     ?></a></td>
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?><?=$url?>&bug_sort[col]=severity&bug_sort[dir]=<?=$sort_dir["severity"]?>"   >Severity      <?=$sort_arrow["severity"]   ?></a></td>
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?><?=$url?>&bug_sort[col]=part&bug_sort[dir]=<?=$sort_dir["part"]?>"       >Part          <?=$sort_arrow["part"]       ?></a></td>
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?><?=$url?>&bug_sort[col]=subject&bug_sort[dir]=<?=$sort_dir["subject"]?>"    >Subject       <?=$sort_arrow["subject"]    ?></a></td>
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?><?=$url?>&bug_sort[col]=reportdate&bug_sort[dir]=<?=$sort_dir["reportdate"]?>" >Report date   <?=$sort_arrow["reportdate"] ?></a></td>
	<th class="head"><a class="head" href="<?=$PHP_SELF?>?action=BugList&project_id=<?=$project_id?><?=$url?>&bug_sort[col]=lastmoddate&bug_sort[dir]=<?=$sort_dir["lastmoddate"]?>">Last mod date <?=$sort_arrow["lastmoddate"]?></a></td>
	<?
	if (IsAuthorized($project_id, AUTH_BUG_MODIFY)) {
		?><th class="head"></b></td><?
	}
	?>
	</tr>
	<?
	
	while ($row_bugs = mysql_fetch_assoc($rslt_bugs)) {
		?>
		<tr valign="top" bgcolor="<?=RowColor()?>">
		<td><a href="<?=$PHP_SELF?>?action=BugOverview&project_id=<?=$project_id?>&bug_id=<?=$row_bugs["id"]?>"><?=str_repeat("0", 4-strlen($row_bugs["bug_nr"])).$row_bugs["bug_nr"]?></a></td>
		<td><?=$statuses[$row_bugs["status"]]?></td>
		<td><?=$severities[$row_bugs["severity"]]?></td>
		<td><?=$parts[$row_bugs["part"]]?></td>
		<td><?=$row_bugs["subject"]?></td>
		<td><?=date("d M y", $row_bugs["reportdate"])?></td>
		<td><?=date("d M y", $row_bugs["lastmoddate"])?></td>
		<?
		if (IsAuthorized($project_id, AUTH_BUG_MODIFY)) {
			?><td><a href="<?=$PHP_SELF?>?action=BugMod&project_id=<?=$project_id?>&bug_id=<?=$row_bugs["id"]?>"><img src="images/ico_edit.gif" border="0" alt="edit" title="Edit this bug"></a></td><?
		}
		?>
		</tr>
		<?
	}
	
	?>
	<tr>
		<td colspan="7">
			<? 
				$url_nav = $PHP_SELF."?action=BugList&project_id=".$project_id.$url;
				ListNavigation ($url_nav."&bug_limit[project_id]=".$project_id."&bug_limit[top]=", $bug_limit["top"], BUGLIST_SHOW_NR_OF_BUGS, $results[0]); 
			?>
		</td>
	</tr>
	</table>
	<br>
	<b>Showing bugs</b>: 
	<b><?=($bug_limit["top"]+1)?></b> 
	to 
	<b><?=($bug_limit["top"]+$nrrows_bugs)?></b>
	(<b><?=$results[0]?></b> total bugs matching the criteria)<br><br>
	<?
}

?>
<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project details</a> &nbsp;
<a class="action" href="<?=$PHP_SELF?>?action=BugAdd&project_id=<?=$project_id?>">Report bug</a> &nbsp;
<?

