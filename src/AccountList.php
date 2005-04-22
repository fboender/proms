<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

/* Security check */
if (!IsAdmin()) {
	Error ("Access denied. You are not an administrator", "back");
}

$qry_accounts = "SELECT * FROM accounts";
Debug ($qry_accounts, __FILE__, __LINE__);
$rslt_accounts = mysql_query ($qry_accounts) or mysql_qry_error(mysql_error(), $qry_accounts, __FILE__, __LINE__);

?>
<table cellspacing="1" cellpadding="3">
<tr valign="top">
<th class="head">Id</td>
<th class="head">Username</td>
<th class="head">Fullname</td>
<th class="head">E-mail</td>
<th class="head">Homepage</td>
</tr>
<?

$row_color = "#d0d0d0";
while ($account = mysql_fetch_assoc($rslt_accounts)) {
	?>
	<tr valign="top" bgcolor="<?=$row_color?>">
	<td><a href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$account["id"]?>"><?=str_repeat("0", 4 - strlen($account["id"]))?><?=$account["id"]?></a></td>
	<td><?=$account["username"]?></td>
	<td><?=$account["fullname"]?></td>
	<td><?=EmailObfuscate($account["email"])?></td>
	<td><?=$account["homepage"]?></td>
	</tr>
	<?
	
	if ($row_color == "#d0d0d0") {
		$row_color = "#e0e0e0";
	} else {
		$row_color = "#d0d0d0";
	}
}
?>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectList">&lt; Project List</a> &nbsp;
<a class="action" href="<?=$PHP_SELF?>?action=AccountCreate">New account</a>

