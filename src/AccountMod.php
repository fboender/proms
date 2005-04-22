<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$account_id = Import("account_id" , "GP");
// $caller     = Import ("caller"    , "GP");

/* Security check */
if (!IsAccountOwner($account_id)) {
	Error ("Access denied. You are not this account's owner", "back");
}

/* Passed data check */
if (isset($account_id)) {
	/* Check to see if account exists */
	$qry_account = "SELECT * FROM accounts WHERE id='".$account_id."'";
	Debug ($qry_account, __FILE__, __LINE__);
	$rslt_account = mysql_query ($qry_account) or mysql_qry_error(mysql_error(), $qry_account, __FILE__, __LINE__);
	$account = mysql_fetch_assoc($rslt_account);
	if (!$account) {
		Error ("Wrong account ID specified", "back");
	}
}

if (isset($account_id)) {
	?><h3><?=$account["username"]?></h3><?
}

$caller = urlencode ($_SERVER["HTTP_REFERER"]);

?>
<table>
<form method="post" action="<?=$PHP_SELF?>">
<?
InputHidden   ("action"    , "AccountSave");
if (isset($account_id)) {
	InputHidden   ("account_id", $account_id);
}
InputHidden   ("caller"    , @$caller);
if (!isset($account_id)) {
	InputText     ("Username*"  , "account[username]", @$account["username"]);
}
InputPassword ("Password*"  , "account[password]", @$account["password"]);
InputPassword ("Password (repeat)*"  , "account[password2]", @$account["password"]);
?><tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr><?
InputText     ("Fullname*"  , "account[fullname]", @$account["fullname"]);
InputText     ("Email*"     , "account[email]"   , @$account["email"]);
InputText     ("Homepage"  , "account[homepage]", @$account["homepage"]);
InputCheckbox ("Private"   , "account[private]",  @$account["private"]);

if (IsAdmin()) {
	?><tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr><?
	InputCheckbox ("Disabled", "account[disabled]", @$account["disabled"]);
}

InputSubmit   ("Save");
?>
</form>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<?
	if (isset($account_id)) {
		?><a class="nav" href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$account_id?>">&lt; Account overview</a> &nbsp;<?
	} else {
		?><a class="nav" href="javascript: history.go(-1);">&lt; Back</a> &nbsp;<?
	}
?>
