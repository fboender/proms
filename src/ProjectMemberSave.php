<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id    = Import ("project_id"    , "GP");
$project       = Import ("project"       , "GP");
$projectmember = Import ("projectmember" , "GP");

if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsAuthorized($project_id, AUTH_PROJECTMEMBERS_MODIFY)) {
	Error("Access denied. You're not authorized to modify project members", "back");
}

/* Calculate int value for all selected rights */
$rights_value = 0;
if (count($projectmember["rights"]) > 0) {
	foreach ($projectmember["rights"] as $right) {
		$rights_value += $right;
	}
	$projectmember["rights"] = $rights_value;
}

if ($projectmember["id"] != "") {
	/* Update current member */
	
	$qry_projectmember = "UPDATE project_members SET ";
	$qry_projectmember .= "project_id='".$project_id                 ."', ";
	$qry_projectmember .= "account_id='".$projectmember["account_id"]."', ";
	$qry_projectmember .= "rights='"    .$projectmember["rights"]    ."'  ";
	$qry_projectmember .= "WHERE id='"  .$projectmember["id"]        ."'  ";

	Debug ($qry_projectmember, __FILE__, __LINE__);
	$rslt_projectmember = mysql_query($qry_projectmember) or mysql_qry_error(mysql_error(), $qry_projectmember, __FILE__, __LINE__);
} else {
	/* Insert new member */
	
	$qry_projectmember = "INSERT INTO project_members SET ";
	$qry_projectmember .= "project_id='".$project_id                 ."', ";
	$qry_projectmember .= "account_id='".$projectmember["account_id"]."', ";
	$qry_projectmember .= "rights='"    .$projectmember["rights"]    ."'  ";

	Debug ($qry_projectmember, __FILE__, __LINE__);
	$rslt_projectmember = mysql_query($qry_projectmember) or mysql_qry_error(mysql_error(), $qry_projectmember, __FILE__, __LINE__);
}
Refresh ("ProjectMemberList&project_id=".$project_id);
?>
