<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

/* AUTHORIZATION RIGHTS */
/* These constants apply to the function IsAuthorized($project_id, [AUTH_*]); */
define ("AUTH_PROJECT_MODIFY"       , 1);
define ("AUTH_PROJECTMEMBERS_MODIFY", 2);
define ("AUTH_TODO_CREATE"          , 4);
define ("AUTH_TODO_MODIFY"          , 8);
define ("AUTH_BUG_MODIFY"           , 16);
define ("AUTH_FORUM_MODIFY"         , 32);
define ("AUTH_RELEASE_ADD"          , 64);
define ("AUTH_RELEASE_MODIFY"       , 128);
define ("AUTH_FILE_ADD"             , 256);
define ("AUTH_FILE_MODIFY"          , 512);
define ("AUTH_FILE_OVERWRITE"       , 1024);

/* Email obfuscate array */
$emailobfuscate_search = array ( 
	".",
	"@",
);
$emailobfuscate_replace = array ( 
	" (DOT) ",
	" (AT) ",
);

/* Months array */
$months[0] = array (1, "January");
$months[1] = array (2, "Februari");
$months[2] = array (3, "March");
$months[3] = array (4, "April");
$months[4] = array (5, "May");
$months[5] = array (6, "June");
$months[6] = array (7, "July");
$months[7] = array (8, "August");
$months[8] = array (9, "September");
$months[9] = array (10, "October");
$months[10] = array (11, "November");
$months[11] = array (12, "December");

/* Module title array */
$module_title = array( 
	"AccountLogout"      => "Logout", 
	"AccountLogin"       => "Login", 
	"AccountOverview"    => "Account information", 
	"AccountCreate"      => "Sign up for account", 
	"AccountMod"         => "Modify account information", 
	"AccountSave"        => "Save account information", 
	"AccountDel"         => "Delete account", 
	"AccountList"        => "Account list", 
	
	"BugAdd"             => "Report new bug",
	"BugMod"             => "Modify bug report", 
	"BugSave"            => "Save bug", 
	"BugList"            => "Bugs list", 
	"BugOverview"        => "Bug information", 
	"BugDiscuss"         => "Discuss bug", 
	
	"ForumView"          => "Forums", 
	"ForumReply"         => "Forum post", 
	"ForumReplySave"     => "Save reply", 
	
	"ForumTopicList"     => "Forum topics", 
	"ForumTopicAdd"      => "Add forum topic",
	"ForumTopicMod"      => "Forum topic modify", 
	"ForumTopicDel"      => "Delete forum topic", 
	"ForumTopicSave"     => "Save forum topic", 
	
	"ProjectAdd"         => "New project", 
	"ProjectMod"         => "Modify project details", 
	"ProjectDel"         => "Delete project", 
	"ProjectSave"        => "Save project information", 
	"ProjectOverview"    => "Project overview", 
	"ProjectList"        => "Projects", 

	"ProjectPartList"    => "Project parts", 
	"ProjectPartAdd"     => "Add project part",
	"ProjectPartMod"     => "Modify project part", 
	"ProjectPartSave"    => "Save project part", 
	
	"ProjectMemberList"  => "Project members list", 
	"ProjectMemberAdd"   => "Add project member", 
	"ProjectMemberMod"   => "Modify project member", 
	"ProjectMemberSave"  => "Save project member information", 
	"ProjectMemberDel"   => "Delete project member", 

	"ProjectManage"      => "Manage project", 
	
	"ReleaseList"        => "Project releases", 
	"ReleaseOverview"    => "Release information", 
	"ReleaseAdd"         => "Add project release",
	"ReleaseMod"         => "Modify project release", 
	"ReleaseSave"        => "Save release information", 
	
	"SubsReleaseAdd"     => "Release subscription", 
	"SubsReleaseDel"     => "Release unsubscribe", 
	
	"TodoAdd"            => "New todo", 
	"TodoMod"		     => "Modify todo", 
	"TodoSave"           => "Save todo", 
	"TodoList"           => "Todo list", 
	"TodoOverview"       => "Todo information", 

	"FileList"           => "File list",
	"FileSave"           => "Save file",
	"FileAdd"            => "Add file",
	"FileMod"            => "Modify file information",
	"FileDel"            => "Delete file",

	"FileCategoryList"   => "File Categories",
	"FileCategorySave"   => "Save file category",
	"FileCategoryAdd"    => "Add file category",
	"FileCategoryMod"    => "Modify file category",
	"FileCategoryDel"    => "Delete file category",

);

$tabs = array(
	"Project" => array(
		"ProjectOverview"),
	"Releases" => array(
		"ReleaseList",
		"ReleaseOverview",
		"ReleaseAdd",
		"ReleaseMod",
		"ReleaseSave",
		"SubsReleaseAdd",
		"SubsReleaseDel"),
	"Files" => array(
		"FileList",
		"FileSave",
		"FileAdd",
		"FileMod",
		"FileDel"),
	"Bugs" => array(	
		"BugAdd",
		"BugMod",
		"BugSave",
		"BugList",
		"BugOverview",
		"BugDiscuss"),
	"Todo's" => array(
		"TodoAdd",       
		"TodoMod",       
		"TodoSave",       
		"TodoList",       
		"TodoOverview"),
	"Discussions" => array(
		"ForumView",
		"ForumReply",
		"ForumReplySave",
		"ForumTopicList",
		"ForumTopicAdd",
		"ForumTopicMod",
		"ForumTopicDel",
		"ForumTopicSave"),
	"Manage" => array(
		"ProjectManage",
		"ProjectPartList",
		"ProjectPartAdd",
		"ProjectPartMod",
		"ProjectPartSave",
		"ProjectMemberList",
		"ProjectMemberAdd",
		"ProjectMemberMod",
		"ProjectMemberSave",
		"ProjectMemberDel"),
);

$tab_links = array(
	"Project" => "ProjectOverview",
	"Releases" => "ReleaseList",
	"Files" => "FileList",
	"Bugs" => "BugList",
	"Todo's" => "TodoList",
	"Discussions" => "ForumView",
	"Manage" => "ProjectManage",
);

function CheckConfig() {
	if (
		!defined('PROMS_VERSION') ||
		!defined('PROMS_EMAIL') ||
		!defined('DB_DATABASE') ||
		!defined('DB_PASSWORD') ||
		!defined('DB_HOSTNAME') ||
		!defined('SMTP_HOSTNAME') ||
		!defined('SMTP_PORT')
	) {
		?>
		<html>
			<body>
				<h1>Configuration error</h1>
				<p>PROMS has not been configured properly. Please re-run the setup script or take a look at the <code>settings.php</code> file.</p>
			</body>
		</html>
		<?
		exit();
	}
}

/* Use this function to bring the value of a variable from the client
 * side/serverside into the current scope. This function tries to safely
 * implement a kind of register_globals.
 *
 * Params   : VarName  = The name of the variable to import.
 *            From     = Specifies the source from where to import the 
 *                       variable. I.e. cookies, GET, POST, etc. It should
 *                       be in the form of s string containing one or more 
 *                       of the chars in 'SPGC'. Last char in the string 
 *                       overrides previous sources.
 *            Default  = [optional] When no value is found in sources, 
 *                       assign this value.
 * Called by: -
 * Calls    : -
 * Pre      : Use of 'S' in From requires session_start() to already be called
 * Post     : -
 * Returns  : Contents of the variable as gotten from the last valid source
 *            described by $From. If VarName was not found in any of the 
 *            specified sources, this function returns 'undefined', and the
 *            the var to which assignment is done should also be undefined.
 * Notice   : The behaviour of an assignment from a function which doesn't 
 *            return anything is not specified I believe. Results may vary.
 */
function Import ($VarName, $From, $Default = "") {
	$i = $c = "";
	$VarValue = FALSE;

	for ($i = 0; $i < strlen($From); $i++) {
		$c = $From[$i];

		switch ($c) {
			case 'F' :
				if (array_key_exists($VarName, $_FILES)) {
					$VarValue = $_FILES[$VarName];
				}
				break;
			case 'S' :
				if (array_key_exists($VarName, $_SESSION)) {
					$VarValue = $_SESSION[$VarName];
				}
				break;
			case 'P' : 
				if (array_key_exists($VarName, $_POST)) {
					$VarValue = $_POST[$VarName]; 
				}
				break;
			case 'G' :
				if (array_key_exists($VarName, $_GET)) {
					$VarValue = $_GET[$VarName];
				}
				break;
			case 'C' : 
				if (array_key_exists($VarName, $_COOKIE)) {
					$VarValue = $_COOKIE[$VarName];
				}
				break;
			default: break;
		}
	}
	
	if ($VarValue === FALSE) {
		if ($Default != "") {
			return ($Default);
		} else {
			return; // Not defined
		}
	} else {
		return ($VarValue);
	}
}

function IsInclude () {
	/* Fake function so included files can check if they aren't called
	   directly */
}

function ThisUrl() {
	global $PHP_SELF;
	if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] == "off") {
		$this_url = "http://";
	} else {
		$this_url = "https://";
	}
	$this_url .= $_SERVER["SERVER_NAME"].$PHP_SELF;

	return($this_url);
}

function ThisBaseUrl() {
	global $PHP_SELF;

	if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] == "off") {
		$this_url = "http://";
	} else {
		$this_url = "https://";
	}
	$this_url .= $_SERVER["SERVER_NAME"].dirname($PHP_SELF)."/";

	return($this_url);
}

function InputFile ($title, $name) {
	?>
	<tr valign="top">
	<td>
		<b><?=$title?></b>
	</td>
	<td>
		<input name="<?=$name?>" type="file">
	</td>
	</tr>
	<?
}

function InputFileBrowse ($title, $name, $value="", $size="60") {
	global $project_id;

	?>
	<tr valign="top">
	<td>
		<b><?=$title?></b>
	</td>
	<td>
		<input name="<?=$name?>" value="<?=htmlspecialchars($value)?>" type="text" size="<?=$size?>">
		<input name="fileselect" type='button' value='Find file' onClick="window.open('<?=$PHP_SELF?>?action=FileBrowse&project_id=<?=$project_id?>&field_name=<?=$name?>', 'filebrowse', 'width=640,height=480,location=0,menubar=0,status=1,toolbar=0');">
	</td>
	</tr>
	<?
}

function InputText ($title, $name, $value="", $size="60") {
	?>
	<tr valign="top">
	<td>
		<b><?=$title?></b>
	</td>
	<td>
		<input name="<?=$name?>" value="<?=htmlspecialchars($value)?>" type="text" size="<?=$size?>">
	</td>
	</tr>
	<?
}

function InputHidden ($name, $value="", $size="60") {
	global $debug;
	if (isset($debug)) {
		?>
		<tr valign="top">
		<td>
			<b><?=$name?></b>
		</td>
		<td>
			<input name="<?=$name?>" value="<?=htmlspecialchars($value)?>" type="text" size="<?=$size?>">
		</td>
		</tr>
		<?
	} else {
		?>
		<input name="<?=$name?>" value="<?=htmlspecialchars($value)?>" type="hidden">
		<?
	}
}
function InputArea ($title, $name, $value="", $rows="15", $cols="60") {
	?>
	<tr valign="top">
	<td>
		<b><?=$title?></b>
	</td>
	<td>
		<textarea name="<?=$name?>" rows="<?=$rows?>" cols="<?=$cols?>"><?=$value?></textarea>
	</td>
	</tr>
	<?
}
function InputDropDown ($title, $name, $value, $lookuptable, $additionalquery="") {
	$qry_lookup = "SELECT * FROM $lookuptable ".$additionalquery;
	Debug ($qry_lookup, __FILE__, __LINE__);
	$rslt_lookup = mysql_query ($qry_lookup) or mysql_qry_error(mysql_error(), $qry_lookup, __FILE__, __LINE__);
	?>
	<tr valign="top">
	<td>
		<b><?=$title?></b>
	</td>
	<td>
		<select name="<?=$name?>">
		<?
			while ($row_inputdropdown = mysql_fetch_row($rslt_lookup)) {
				if ($value == $row_inputdropdown[0]) {
					$selected = "SELECTED";
				} else {
					$selected = "";
				}
				?><option value="<?=$row_inputdropdown[0]?>" <?=$selected?>><?=$row_inputdropdown[1]?></option><?
			}
		?>
		</select>
	</td>
	</tr>
	<?
}
function InputPassword ($title, $name, $value="", $size="60") {
	?>
	<tr valign="top">
	<td>
		<b><?=$title?></b>
	</td>
	<td>
		<input name="<?=$name?>" value="<?=htmlspecialchars($value)?>" type="password" size="<?=$size?>">
	</td>
	</tr>
	<?
}

function InputCheckbox ($title, $name, $checked) {
	if ($checked == 1) {
		$s = " CHECKED ";
	} else {
		$s = "";
	}
	
	?>
	<tr valign="top">
	<td>
		<b><?=$title?></b>
	</td>
	<td>
		<input type="checkbox" name="<?=$name?>" value="1" <?=$s?>>
	</td>
	</tr>
	<?
}

function InputDate ($title, $name, $timestamp="") {
	global $months;

	if ($timestamp == "") {
		$date = getdate(time());
	} else {
		$date = getdate($timestamp);
	}

	?>
	<tr valign="top">
	<td>
		<b><?=$title?></b>
	</td>
	<td>
		<select name="<?=$name?>[day]">
			<?
			for ($i = 1; $i <= 31; $i++) {
				if ($i == $date["mday"]) {
					$selected = "SELECTED";
				} else {
					$selected = "";
				}
				?><option value="<?=$i?>" <?=$selected?>><?=$i?></option><?
			}
			?>
		</select>
		<select name="<?=$name?>[month]">
			<?
			foreach ($months as $month) {
				if ($month[1] == $date["month"]) {
					$selected = "SELECTED";
				} else {
					$selected = "";
				}
				?><option value="<?=$month[0]?>" <?=$selected?>><?=$month[1]?></option><?
			}
			?>
		</select>
		<select name="<?=$name?>[year]">
			<?
			$from = date("Y", time());
			$to = $from - 10;
			for ($i = $from; $i >= $to; $i--) {
				if ($i == $date["year"]) {
					$selected = "SELECTED";
				} else {
					$selected = "";
				}
				?><option value="<?=$i?>" <?=$selected?>><?=$i?></option><?
			}
			?>
		</select>
	</td>
	</tr>
	<?
}

function InputSubmit ($title) {
	?>
	<tr valign="top">
	<td>
		&nbsp;
	</td>
	<td>
		<input class="action" name="" value="<?=$title?>" type="submit">
	</td>
	</tr>
	<?
}

function ReadLookup ($lookuptable, $column, $additionalquery = "") {
	$qry_lookup = "SELECT id,$column FROM $lookuptable ".$additionalquery;
	$rslt_lookup = mysql_query ($qry_lookup) or mysql_qry_error(mysql_error(), $qry_lookup, __FILE__, __LINE__);
	Debug ($qry_lookup, __FILE__, __LINE__);

	while ($row_lookup = mysql_fetch_assoc($rslt_lookup)) {
		$lookup[$row_lookup["id"]] = $row_lookup[$column];
	}

	return ($lookup);
}

function InitDatabase () {
	$db = @mysql_connect (DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
	if (!$db) {
		Error ("Couldn't connect to the MySQL server! Please check your settings (settings.php)", "exit");
	}
	if (!@mysql_select_db(DB_DATABASE, $db)) {
		Error ("Couldn't select the database. Please check your settings and database permissions", "exit");
	}
}

function InitSession () {
	session_start();
}

function InitDebug () {
	global $debug;

	$debug = 1;

	if (isset($debug)) {
		error_reporting(E_ALL - E_NOTICE);
	} else {
		// error_reporting(0); /* Disabled in unstable versions */
	}

	?>
	<script language="javascript">
		debug_win = window.open ('','debug_win','directories=no,location=no,menubar=no,status=no,toolbar=no,personalbar=no,resizable=yes,scrollbars=yes');
		debug_win.document.writeln ('<br><br><b><?=date("d M Y H:i:s")?></b><br>');
	</script>
	<?
}

function Debug ($errmsg, $file="??", $line="??") {
	global $debug;

	$file = substr($file, strrpos($file, "/"));
	
	if (isset($debug)) {
		?>
		<script language="javascript">
			debug_win.document.writeln('<font face=\"verdana\" color=\"#0000FF\"><?=$file?></font>, <font face=\"verdana\" color=\"#00A000\"><?=$line?></font> : <font face=\"courier\" color=\"#A00000\"><?=nl2br(htmlentities(addslashes($errmsg)))?></font><br>\n\r');
			debug_win.window.scrollBy(0,100);
		</script>
		<?
	}
}

function IsProjectOwner ($project_id) {
	global $_SESSION;
	
	/* Admin user always allowed */
	if ($_SESSION["user_id"] == 1) {
		return (true);
	}
	
	Debug ("Session user_id=".$_SESSION["user_id"], __FILE__, __LINE__);

	$qry_project = "SELECT owner FROM projects WHERE id='".$project_id."'";
	Debug ($qry_project, __FILE__, __LINE__);
	$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
	$project = mysql_fetch_assoc ($rslt_project);

	Debug ($project["owner"] . " - ".$_SESSION["user_id"], __FILE__, __LINE__);
	if ($project["owner"] == $_SESSION["user_id"]) {
		return (true);
	} else {
		return (false);
	}
}

function IsAccountOwner ($account_id) {
	global $_SESSION;

	/* Admin user always allowed */
	if ($_SESSION["user_id"] == 1) {
		return (true);
	}
	
	Debug ("Session user_id=".$_SESSION["user_id"], __FILE__, __LINE__);
	
	if ($_SESSION["user_id"] == $account_id) {
		return (true);
	} else {
		return (false);
	}
}

function IsLoggedIn() {
	global $_SESSION;

	if ($_SESSION["user_id"] != "") {
		return (true);
	}

	return (false);
}

function IsAdmin () {
	global $user_id, $_SESSION;
	
	Debug ("Session user_id=".$_SESSION["user_id"], __FILE__, __LINE__);

	if ($_SESSION["user_id"] == 1) {
		return (true); /* God in tha house */
	}

	return (false);
}

function IsProjectMember ($project_id) {
	global $_SESSION;

	if (IsAdmin() || IsProjectOwner($project_id)) {
		return (true);
	}
	if ($_SESSION["user_id"] == "") {
		return (false);
	}
	
	$qry_projectmember = "SELECT * FROM project_members WHERE project_id='".$project_id."' AND account_id='".$_SESSION["user_id"]."'";
	Debug ($qry_projectmember, __FILE__, __LINE__);
	$rslt_projectmember = mysql_query ($qry_projectmember) or mysql_qry_error(mysql_error(), $qry_projectmember, __FILE__, __LINE__);
	if (mysql_num_rows($rslt_projectmember) > 0) {
		return (true);
	} else {
		return (false);
	}
}

function IsAuthorized ($project_id, $rights) {
	global $_SESSION;

	if (IsAdmin() || IsProjectOwner($project_id)) {
		return (true);
	}
	if ($_SESSION["user_id"] == "") {
		return (false);
	}

	$qry_projectmember = "SELECT * FROM project_members WHERE project_id='".$project_id."' AND account_id='".$_SESSION["user_id"]."'";
	Debug ($qry_projectmember, __FILE__, __LINE__);
	$rslt_projectmember = mysql_query ($qry_projectmember) or mysql_qry_error(mysql_error(), $qry_projectmember, __FILE__, __LINE__);
	if (mysql_num_rows($rslt_projectmember) == 0) { /* Not even a member! Get outta here! */
		return (false);
	}
	$projectmember = mysql_fetch_assoc($rslt_projectmember);

	if ($projectmember["rights"] & $rights == $rights) { return (true); }
	return (false);
}

function EmailObfuscate ($emailaddress) {
	global $emailobfuscate_search, $emailobfuscate_replace;

	return (str_replace($emailobfuscate_search, $emailobfuscate_replace, $emailaddress));
}

function HeaderHtml ($action, $module_title) {
	if (!isset($include)) {
		?>
		<html>
		<head>
			<link rel="stylesheet" href="css/index.css" type="text/css" />
			<title>
				PROMS <? echo PROMS_VERSION ?> - <?=htmlentities($module_title[$action])?>
			</title>
		</head>
		<body>
		<?
	}
}

function HeaderTab ($action) {
	global $tabs, $tab_links;
	
	$project_id = Import("project_id", "GP");

	$show_tabs = false;

	if (!isset($include) && $action != "Documentation" && $action != "FileBrowse") {
		foreach ($tabs as $tab_title => $tab_action) {
			if (in_array($action, $tab_action)) {
				$show_tabs = true;
			}
		}

		if ($show_tabs) { 
			?>
			<center>
			<div class="tab">
				<?
				foreach ($tabs as $tab_title => $tab_action) {
					$url = $PHP_SELF."?action=".$tab_links[$tab_title]."&project_id=".$project_id;
					if (in_array($action, $tab_action)) {
						?><span class="tab_active"><a href="<?=$url?>"><?=$tab_title?></a></span><?
					} else { 
						?><span class="tab_inactive"><a href="<?=$url?>"><?=$tab_title?></a></span><?
					}
				}
				?>
			</div>
			<?
		}
		?>
			<div class="tab_contents">
		<?
	}
	
	return($show_tabs);
}
function FooterTab() {
	if (!isset($include) && $action != "Documentation" && $action != "FileBrowse") {
		?>
		</div>
		</center>
		<?
		return(true);
	} else {
		return(false);
	}
}

function HeaderPage ($action) {
	global $user_id, $action, $module_title, $PHP_SELF, $include;

	$project_id = Import ("project_id", "GP");
	
	if (!isset($include) && $action != "Documentation" && $action != "FileBrowse") {
		$accounts = ReadLookup("accounts", "username");
		
		if ($project_id != "") {
			$qry_project = "SELECT fullname FROM projects WHERE id='".$project_id."'";
			Debug ($qry_project, __FILE__, __LINE__);
			$rslt_project = mysql_query ($qry_project) or mysql_qry_error(mysql_error(), $qry_project, __FILE__, __LINE__);
			$project = mysql_fetch_assoc($rslt_project);
			$title = $project["fullname"];
		} else {
			$title = "PROMS Projects";
		}
		?>
			<div class="title">
				<table width="100%">
					<tr>
					<td>
						<font class="title">
						<?=htmlentities($title)?> &nbsp; &nbsp; <!--<i><font color="#A0B0FF"><?=htmlentities($module_title[$action])?></font> --></i>
						</font>
					</td>
					<td align="right">
						<a title="Help about this page" href="javascript:void(0);" OnClick="window.open('<?=$PHP_SELF?>?action=Documentation&topic=<?=$action?>','','height=600,width=400,scrollbars=yes');"><img src="images/ico_help.gif" align="middle" border="0" alt="User manual" title="User manual"></a>
					</td>
					</tr>
				</table>
			</div>
			<div class="nav_bar">
				<table width="100%" border=0 cellspacing=0 cellpadding=0>
					<td align="left">
						<b><?=htmlentities($module_title[$action])?></b>
					</td>
					<td align="right">
						<?
						if (isset($_SESSION["user_id"])) {
							?>Logged in as: <b><?=$accounts[$user_id]?></b> &nbsp; <?
							?> <a href="<?=$PHP_SELF?>?action=AccountLogout&oldaction=<?=$action?>">Logout</a> | <?
							?> <a href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$_SESSION["user_id"]?>">My Account</a><?
						} else {
							?> <a href="<?=$PHP_SELF?>?action=AccountLogin">Login</a> | <?
							?> <a href="<?=$PHP_SELF?>?action=AccountCreate">Sign up</a><?
						}
						?>&nbsp;
					</td>
				</table>
			</div>
		<?
	}
}

function HeaderContents($action) {
	if (!isset($include) && $action != "Documentation" && $action != "FileBrowse") {
		?><div style="margin: 20px;"><?
	}
}

function FooterContents() {
	if (!isset($include) && $action != "Documentation" && $action != "FileBrowse") {
		?></div><?
	}
}

function Error ($message, $operation="") {
	global $module_title, $action;
	?>
	<script langauge="javascript">
		alert ('<?=addslashes($message)?>');
		<?
		if ($operation == "back") {
			?>history.go(-1);<?
		}
		?>
	</script>
	<?
	FooterHtml();
	if ($operation == "exit" || $operation == "back") {
		exit();
	}
}

function Refresh ($Action) {
	global $PHP_SELF, $debug;

	if ($debug != 1) {
		?>
		<script language="javascript">
			location.href = "<?=$PHP_SELF?>?action=<?=$Action?>";
		</script>
		<?
	} else {
		?>
		<a href="<?=$PHP_SELF?>?action=<?=$Action?>"><?=$Action?></a>
		<?
	}
}

function FooterHtml() {
	/* Automatically focus the first visible form field */
	?>
	<script language="javascript">
		var i = 0;
		if (document.forms[0]) {
			while (document.forms[0].elements[i]) {
				if (document.forms[0].elements[i].type != "hidden") {
					document.forms[0].elements[i].focus();
					break;
				}
				i++;
			}
		}
	</script>
	</body>
	</html>
	<?
}

function FooterPage() {
}

function RowColor() {
	global $row_color;
	if ($row_color == "#F0F0F0" || $row_color == "") {
		$row_color = "#FFFFFF";
	} else {
		$row_color = "#F0F0F0";
	}

	return ($row_color);
}
function ForumBugTrack ($change, $project_id, $bug) {
	global $_SESSION;

	/* Find bug topic id for this project */
	$qry_bugstopicid = "SELECT id FROM forum WHERE project_id='".$project_id."' AND reply_to=0 AND subject='Bugs'";
	Debug ($qry_bugstopicid, __FILE__, __LINE__);
	$rslt_bugstopicid = mysql_query($qry_bugstopicid) or mysql_qry_error(mysql_error(), $qry_bugstopicid, __FILE__, __LINE__);
	$row_bugstopicid = mysql_fetch_assoc($rslt_bugstopicid);
	$bugstopicid = $row_bugstopicid["id"];
	if (!$bugstopicid) {
		Debug ("Creating a new BUGS thread for project ".$project_id, __FILE__, __LINE__);
		$qry_createbugstopic = "INSERT INTO forum SET project_id='".$project_id."', reply_to=0, user_id='".$_SESSION["user_id"]."', subject='Bugs', postdate='".time()."',lastpostdate='".time()."'";
		Debug ($qry_createbugstopic, __FILE__, __LINE__);
		$rslt_createbugstopic = mysql_query($qry_createbugstopic) or mysql_qry_error(mysql_error(), $qry_createbugstopic, __FILE__, __LINE__);
		$bugstopicid = mysql_insert_id();
	}

	/* Check if bug already has a forum thread */
	$qry_bugthreadid = "SELECT * FROM forum WHERE project_id='".$project_id."' AND subject LIKE 'Bug #".str_repeat("0", 4-strlen($bug["bug_nr"])).$bug["bug_nr"]."%';";
	Debug ($qry_bugthreadid, __FILE__, __LINE__);
	$rslt_bugthreadid = mysql_query($qry_bugthreadid) or mysql_qry_error(mysql_error(), $qry_bugthreadid, __FILE__, __LINE__);
	if (mysql_num_rows($rslt_bugthreadid) > 0) {
		/* Bug has a thread in the forum.*/
		$row_bugthreadid = mysql_fetch_assoc($rslt_bugthreadid);
		$bugthreadid = $row_bugthreadid["id"];
		Debug ("Bug ".$bug["bug_nr"]." already has a thread", __FILE__, __LINE__);
	} else {
		Debug ("Bug ".$bug["bug_nr"]." has no thread yet", __FILE__, __LINE__);
		/* Bug doesn't have a thread in the forum. Create one */
		$qry_newthread  = "INSERT into forum SET ";
		$qry_newthread .= "project_id='".$project_id       ."', ";
		$qry_newthread .= "reply_to='"  .$bugstopicid      ."', ";
		$qry_newthread .= "user_id='"   .$bug["user_id"]          ."', ";
		$qry_newthread .= "subject='Bug #".str_repeat("0", 4-strlen($bug["bug_nr"])).$bug["bug_nr"]." ".nl2br(addslashes($bug["subject"])) ."', ";
		$qry_newthread .= "contents='" .nl2br(addslashes($bug["description"]))."', ";
		$qry_newthread .= "postdate='".time()."', ";
		$qry_newthread .= "lastpostdate='".time()."'";
		Debug ($qry_newthread, __FILE__, __LINE__);
		$rslt_newthread = mysql_query ($qry_newthread) or mysql_qry_error(mysql_error(), $qry_newthread, __FILE__, __LINE__);
		$bugthreadid = mysql_insert_id();
	}

	/* Post bug change to forum */
	$qry_change = "INSERT INTO forum SET ";
	$qry_change .= "project_id='".$project_id."', ";
	$qry_change .= "reply_to='".$bugthreadid."', ";
	$qry_change .= "user_id='".$_SESSION["user_id"]."', ";
	$qry_change .= "subject='Bug tracker',";
	$qry_change .= "contents='".addslashes($change)."', ";
	$qry_change .= "postdate='".time()."'";

	$rslt_change = mysql_query($qry_change) or mysql_qry_error(mysql_error(), $qry_change, __FILE__, __LINE__);
}

function ListNavigation ($base_url, $top, $results_per_page, $results_total) {
	$arrow["<<"]["enabled" ] = "images/ico_arrow_ll_wh.gif";
	$arrow["<<"]["disabled"] = "images/ico_arrow_ll_wh_inact.gif";
	$arrow["<" ]["enabled" ] = "images/ico_arrow_l_wh.gif";
	$arrow["<" ]["disabled"] = "images/ico_arrow_l_wh_inact.gif";
	$arrow[">" ]["enabled" ] = "images/ico_arrow_r_wh.gif";
	$arrow[">" ]["disabled"] = "images/ico_arrow_r_wh_inact.gif";
	$arrow[">>"]["enabled" ] = "images/ico_arrow_rr_wh.gif";
	$arrow[">>"]["disabled"] = "images/ico_arrow_rr_wh_inact.gif";

	/*  <<  */
	if ($top > 0) {
		/* Enabled */
		$nav["<<"]["top_new"] = 0;
		$nav["<<"]["state"] = "enabled";
	} else {
		$nav["<<"]["state"] = "disabled";
	}

	/*  <  */
	if ($top > 0) {
		$nav["<"]["state"] = "enabled";

		if ( ($top - $results_per_page) <= 0) {
			$nav["<"]["top_new"] = 0;
		} else {
			$nav["<"]["top_new"] = $top - $results_par_page;
		}
	} else {
		$nav["<"]["state"] = "disabled";
	}
	
	/*  >  */
	if (($top + $results_per_page) < $results_total) {
		$nav[">"]["state"] = "enabled";
		$nav[">"]["top_new"] = $top + $results_per_page;
	} else {
		$nav[">"]["state"] = "disabled";
	}

	/*  >>  */
	if ( $top < ($results_total - $results_per_page) ) {
		$nav[">>"]["state"] = "enabled";
		$nav[">>"]["top_new"] = $results_total - $results_per_page;
	} else {
		$nav[">>"]["state"] = "disabled";
	}


	if ($nav["<<"]["state"] == "enabled") { ?><a href="<?=$base_url?><?=$nav["<<"]["top_new"]?>"><? } ?><img src="<?=$arrow["<<"][$nav["<<"]["state"]]?>" border="0" alt="[ << ]"></a> &nbsp; <? 
	if ($nav["<" ]["state"] == "enabled") { ?><a href="<?=$base_url?><?=$nav["<" ]["top_new"]?>"><? } ?><img src="<?=$arrow["<" ][$nav["<" ]["state"]]?>" border="0" alt="[ < ]" ></a> &nbsp; <? 
	if ($nav[">" ]["state"] == "enabled") { ?><a href="<?=$base_url?><?=$nav[">" ]["top_new"]?>"><? } ?><img src="<?=$arrow[">" ][$nav[">" ]["state"]]?>" border="0" alt="[ > ]" ></a> &nbsp; <? 
	if ($nav[">>"]["state"] == "enabled") { ?><a href="<?=$base_url?><?=$nav[">>"]["top_new"]?>"><? } ?><img src="<?=$arrow[">>"][$nav[">>"]["state"]]?>" border="0" alt="[ >> ]"></a> &nbsp; <? 
}

function mysql_qry_error ($error_msg, $qry, $file, $line) {
	global $debug;
	?>
	<center>
		<div class="qry_error">
			<div class="qry_error_title">Non-recoverable query error</div>
			<p align="justify">The program has generated a non-recoverable query error. This is not your fault and should not have happened. Any information you tried to save or delete has, most probably, <b>not been saved or deleted</b>.</p>
			<p align="justify">You may now choose to either go back to the page you came from, or send a bug report.</p>
			<p align="justify">If you choose to send a bug report, please enter any appropriate information into the form and submit it. If you wish you can first create an account for yourself, but it can also be submitted anonymously.</p>
			<?
			if (isset($debug) && $debug == 1) {
				?>
				<div class="qry_error_dev">
				<?
					?><p align="left">The error was identified as the following:</p><?
					?><p align="left"><font color="#FF0000">Error:&nbsp;</font><code><?=$error_msg?></code></p><?
					?><p align="left"><font color="#FF0000">Query:&nbsp;</font><code><?=$qry?></code></p><?
					?><p align="left"><font color="#FF0000">File:&nbsp;</font><code><?=$file?>:<?=$line?></code></p><?
					?>
					<form method="mailto">
						<input type="hidden" name="bugreport[error_msg]" value="<?=$error_msg?>">
						<input type="hidden" name="bugreport[qry]" value="<?=$qry?>">
						<input type="hidden" name="bugreport[file]" value="<?=$file?>">
						<input type="hidden" name="bugreport[line]" value="<?=$line?>">
					</form>
					<?
				?>
				</div>
				<?
			}
			?>
			<center>
				<a class="action" href="javascript: history.go(-1)">Back</a> &nbsp;
				<a class="action" href="http://projects.electricmonk.nl/index.php?action=BugAdd&project_id=2">Report bug</a> (at projects.electricmonk.nl) &nbsp;
			</center>
			<br>
		</div>
	<center>
	<?
	exit();
}

function bbcode($string){
	/* Copyright Justin Palmer (http://www.isolated-designs.net), LGPL
	   Modified by Ferry Boender on 18 sept 2004 
	*/
	$string = htmlspecialchars($string);
	$string = str_replace("\r", "", $string);
	$string = str_replace("\n", "<br />", $string);

	$patterns = array(
		'`\[li\](.+?)\[/li\]`is',
		'`\[quote\](.+?)\[/quote\]`is',
		'`\[code](.+?)\[/code\]`is',
		'`\[b\](.+?)\[/b\]`is',
		'`\[i\](.+?)\[/i\]`is',
		'`\[u\](.+?)\[/u\]`is',
		'`\[strike\](.+?)\[/strike\]`is',
		'`\[url=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/url\]`si',
		'`\[url\]([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\[/url\]`si',
		'`\[url\]((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\[/url\]`si',
	);

	$replaces =  array(
		'<li class="noblock">\1</li>',
		'<span class="quote">\1</span>',
		'<pre class="code">\\1</pre>',
		'<strong>\\1</strong>',
		'<em>\\1</em>',
		'<span style="border-bottom: 1px dotted">\\1</span>',
		'<strike>\\1</strike>',
		'<a href="\1\2">\6</a>',
		'<a href="\1\2">\1\2</a>',
		'<a href="http://\1">\1</a>',
	);

	$prev_string = "";
	while ($prev_string != $string) {
		$prev_string = $string;
		$string = preg_replace($patterns, $replaces , $string);
	}
	
	return(stripslashes($string));
}
function bbcode_strip($string){

	$string = htmlspecialchars($string);
	/* Strip useless newlines so [code] will appear correct */
	$string = str_replace("\r", "", $string);
	$string = str_replace("\n", "<br />", $string);

	$patterns = array(
		'`\[li\](.+?)\[/li\]`is',
		'`\[quote\](.+?)\[/quote\]`is',
		'`\[indent](.+?)\[/indent\]`is',
		'`\[code](.+?)\[/code\]`is',
		'`\[b\](.+?)\[/b\]`is',
		'`\[i\](.+?)\[/i\]`is',
		'`\[u\](.+?)\[/u\]`is',
		'`\[strike\](.+?)\[/strike\]`is',
		'`\[email\](.+?)\[/email\]`is',
		'`\[img\](.+?)\[/img\]`is',
		'`\[url=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/url\]`si',
		'`\[url\]([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\[/url\]`si',
		'`\[url\]((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\[/url\]`si',
	);

	$replaces =  array(
		'',
		'',
		'',
		'',
		'',
		'',
		'',
		'',
		'',
		'',
		'',
		'',
		'',
	);

	$prev_string = "";
	while ($prev_string != $string) {
		$prev_string = $string;
		$string = preg_replace($patterns, $replaces , $string);
	}
	
	return(stripslashes($string));
}

?>
