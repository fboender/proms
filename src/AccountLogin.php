<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$account = Import("account" , "P");
$caller  = Import("caller"  , "GP"  , $_SERVER["HTTP_REFERER"]);

if ($caller != $_SERVER["HTTP_REFERER"]) {
	$caller = urldecode($caller);
}
if (strstr($caller, "AccountLogin") !== false) {
	/* User came from the login page. Make sure he's not redirected there
	   after logging in, to avoid confusion */
	$caller = $PHP_SELF."?action=ProjectList";
}

Debug ("Caller: ".$caller, __FILE__, __LINE__);

if ($account["username"] != "" && $account["password"] != "") {
	/* Log user in */
	
	/* Check username and password */
	$qry_account = "SELECT * FROM accounts WHERE username='".$account["username"]."'";
	Debug ($qry_account, __FILE__, __LINE__);
	$rslt_account = mysql_query ($qry_account) or mysql_qry_error(mysql_error(), $qry_account, __FILE__, __LINE__);

	if (mysql_num_rows($rslt_account) != 1) {
		Error ("Login failed. Unknown username");
		Refresh ("AccountLogin&caller=".urlencode($caller));
		exit();
	}
	$dbaccount = mysql_fetch_assoc($rslt_account);

	if ($dbaccount["disabled"] == 1) {
		Error ("Login failed. Your account has been disabled");
		Refresh ("AccountLogin&caller=".urlencode($caller));
		exit();
	}
	
	if ($dbaccount["password"] == $account["password"]) {
		$_SESSION["user_id"] = $dbaccount["id"];

		/* Login */
		if (!array_key_exists("user_id", $_SESSION)) {
			/* Session wasn't registered proparly */
			Error ("Login failed for unknown reasons. This is not your fault");
		} else {
			if ($_SESSION["user_id"] == 1) { /* ... */
				?>
				<font size="+2">Masssterrrrr...</font>
				<?
			}
			?>
			<script language="javascript">
			location.href="<?=addslashes($caller)?>"; 
			</script>
			<?
		}
	} else {
		Error ("Login failed. Wrong username and/or password");
		Refresh ("AccountLogin&caller=".urlencode($caller));
	}

} else {
	/* Present user with login form */
	?>
	<table>
	<form method="post" action="<?=$PHP_SELF?>">
		<?
		InputHidden ("caller", urlencode($caller));
		InputHidden ("action", "AccountLogin");
		InputText ("Username", "account[username]", "", 20);
		InputPassword ("Password", "account[password]", "", 20);
		InputSubmit ("Login");
		?>
	</form>
	</table>
	<a href="javascript:void(0);" OnClick="javascript: alert('Too bad')">Lost password?</a> <!-- Mwoahaha -->
	<?
}
?>


<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="javascript:history.go(-1);">&lt; Back</a> &nbsp;
<a class="action" href="<?=$PHP_SELF?>?action=AccountCreate&caller=<?=$_SERVER["HTTP_REFERER"]?>">Sign up</a>

