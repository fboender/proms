<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$account_id = Import("account_id", "GP");

/* Passed data check */
if ($account_id == "") {
	Error ("No account ID was specified", "back");
}
if ($account_id == 0) {
	Error ("You are requesting information about an anonymous user. This is not possible", "back");
}

$qry_account = "SELECT * FROM accounts WHERE id='".$account_id."'";
Debug($qry_account, __FILE__, __LINE__);
$rslt_account = mysql_query ($qry_account) or mysql_qry_error(mysql_error(), $qry_account, __FILE__, __LINE__);
$account = mysql_fetch_assoc($rslt_account);
if (!$account) {
	Error ("Wrong account ID specified");
}

/* Get the projects this user owns */
$qry_ownedprojects = "SELECT * FROM projects WHERE owner='".$account_id."'";
Debug ($qry_ownedprojects, __FILE__, __LINE__);
$rslt_ownedprojects = mysql_query ($qry_ownedprojects) or mysql_qry_error(mysql_error(), $qry_ownedprojects, __FILE__, __LINE__);

?>
<table>
<tr><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
<tr valign="top"><th class="head">Username :</th><td><?=$account["username"]?></td></tr>
<tr valign="top"><th class="head">Full name :</th><td><?=$account["fullname"]?></td></tr>
<?
	if ($account["private"] == 1 && !IsAccountOwner($account["id"])) {
		?>
		</table>
		<br>
		This account is marked as private and therefor more information cannot be shown
		<?
	} else {
		?>
		<tr valign="top"><th class="head">E-mail address :</th><td><a href="mailto:<?=EmailObfuscate($account["email"])?>"><?=EmailObfuscate($account["email"])?></a></td></tr>
		<tr valign="top"><th class="head">Homepage address :</th><td><a href="<?=$account["homepage"]?>"><?=$account["homepage"]?></a></td></tr>
		<tr valign="top"><td colspan="2"><div class="separator">&nbsp;</div></td></tr>
		<tr valign="top">
			<th class="head">Projects:</th>
			<td>
				<?
				if (mysql_num_rows($rslt_ownedprojects) == 0) {
					?>None<?
				} else {
					while ($project = mysql_fetch_assoc($rslt_ownedprojects)) {
						?><a href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project["id"]?>"><?=$project["fullname"]?></a><br><?
					}
				}
				?>
			</td>
		</tr>
		</table>
		<?
	}
?>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<?
	if (IsAdmin()) {
		?><a class="nav" href="<?=$PHP_SELF?>?action=AccountList">&lt; Account list</a> &nbsp;<?
	} else {
		?><a class="nav" href="javascript:history.go(-1);">&lt; Back</a> &nbsp;<?
	}
	
	if (IsAccountOwner($account["id"])) {
		?><a class="action" href="<?=$PHP_SELF?>?action=AccountMod&account_id=<?=$account["id"]?>">modify</a> &nbsp;<?
	}
?>

