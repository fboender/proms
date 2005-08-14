<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

include_once("settings.php");
include_once("inc_common.php");
include_once("inc_smtp.php");

CheckConfig();

InitDatabase();
InitSession();
//InitDebug(); /* Uncomment to turn on debugging */

$action = Import ("action", "GP", "ProjectList");
$user_id = Import ("user_id", "S");
$project_id = Import ("project_id", "GP");
$PHP_SELF = $_SERVER["PHP_SELF"];

/* If the user tries to do anything with a private project, give an error */
if (isset($project_id) && $project_id != "") {
	if (IsPrivate($project_id) && !IsProjectMember($project_id)) {
		Error ("Access denied. You may not view this project.", "exit");
	}
}

HeaderHtml ($action, $module_title);
HeaderPage ($action);
if (!HeaderTab ($action)) {
	HeaderContents($action);
}

/* Action determination */
switch ($action) {
	case "AccountLogout"      : include("AccountLogout.php");      break;
	case "AccountLogin"       : include("AccountLogin.php");       break;
	case "AccountOverview"    : include("AccountOverview.php");    break;
	case "AccountCreate"      : include("AccountMod.php");         break;
	case "AccountMod"         : include("AccountMod.php");         break;
	case "AccountSave"        : include("AccountSave.php");        break;
	case "AccountDel"         : include("AccountDel.php");         break;
	case "AccountList"        : include("AccountList.php");        break;
	
	case "BugList"            : include("BugList.php");            break;
	case "BugOverview"        : include("BugOverview.php");        break;
	case "BugAdd"             : include("BugMod.php");             break;
	case "BugMod"             : include("BugMod.php");             break;
	case "BugSave"            : include("BugSave.php");            break;
	case "BugDiscuss"         : include("BugDiscuss.php");         break;
	
	case "ForumView"          : include("ForumView.php");          break;
	case "ForumReply"         : include("ForumReply.php");         break;
	case "ForumReplySave"     : include("ForumReplySave.php");     break;
	
	case "ForumTopicList"     : include("ForumTopicList.php");     break;
	case "ForumTopicAdd"      : include("ForumTopicMod.php");      break;
	case "ForumTopicMod"      : include("ForumTopicMod.php");      break;
	case "ForumTopicDel"      : include("ForumTopicDel.php");      break;
	case "ForumTopicSave"     : include("ForumTopicSave.php");     break;
	
	case "ProjectList"        : include("ProjectList.php");        break;
	case "ProjectAdd"         : include("ProjectMod.php");         break;
	case "ProjectMod"         : include("ProjectMod.php");         break;
	case "ProjectDel"         : include("ProjectDel.php");         break;
	case "ProjectSave"        : include("ProjectSave.php");        break;
	case "ProjectOverview"    : include("ProjectOverview.php");    break;
	
	case "ProjectPartList"    : include("ProjectPartList.php");    break;
	case "ProjectPartAdd"     : include("ProjectPartMod.php");     break;
	case "ProjectPartMod"     : include("ProjectPartMod.php");     break;
	case "ProjectPartSave"    : include("ProjectPartSave.php");    break;
	case "ProjectPartDel"     : include("ProjectPartDel.php");     break;
	
	case "ProjectMemberList"  : include("ProjectMemberList.php");  break;
	case "ProjectMemberAdd"   : include("ProjectMemberAdd.php");   break;
	case "ProjectMemberMod"   : include("ProjectMemberMod.php");   break;
	case "ProjectMemberSave"  : include("ProjectMemberSave.php");  break;
	case "ProjectMemberDel"   : include("ProjectMemberDel.php");   break;
	
	case "ProjectManage"      : include("ProjectManage.php");      break;

	case "ReleaseList"        : include("ReleaseList.php");        break;
	case "ReleaseOverview"    : include("ReleaseOverview.php");    break;
	case "ReleaseAdd"         : include("ReleaseMod.php");         break;
	case "ReleaseMod"         : include("ReleaseMod.php");         break;
	case "ReleaseSave"        : include("ReleaseSave.php");        break;
	
	case "SubsReleaseAdd"     : include("SubsReleaseAdd.php");     break;
	case "SubsReleaseDel"     : include("SubsReleaseDel.php");     break;
	
	case "TodoAdd"            : include("TodoAdd.php");            break;
	case "TodoMod"            : include("TodoMod.php");            break;
	case "TodoDel"            : include("TodoDel.php");            break;
	case "TodoSave"           : include("TodoSave.php");           break;
	case "TodoList"           : include("TodoList.php");           break;
	case "TodoOverview"       : include("TodoOverview.php");       break;
	case "TodoDiscuss"        : include("TodoDiscuss.php");        break;
	case "TodoDone"           : include("TodoDone.php");           break;
	
	case "FileBrowse"         : include("FileBrowse.php");         break;
	case "FileList"           : include("FileList.php");           break;
	case "FileSave"           : include("FileSave.php");           break;
	case "FileAdd"            : include("FileMod.php");            break;
	case "FileMod"            : include("FileMod.php");            break;
	case "FileDel"            : include("FileDel.php");            break;

	case "FileCategoryList"   : include("FileCategoryList.php");   break;
	case "FileCategorySave"   : include("FileCategorySave.php");   break;
	case "FileCategoryAdd"    : include("FileCategoryMod.php");    break;
	case "FileCategoryMod"    : include("FileCategoryMod.php");    break;
	case "FileCategoryDel"    : include("FileCategoryDel.php");    break;

	case "Documentation"      : include("Documentation.php");      break;
	
	default                   : echo ("invalid action received"); include ("ProjectList.php");        break;
}

if (!FooterTab($action)) {
	FooterContents($action);
}
FooterPage ();
FooterHtml ();
?>
