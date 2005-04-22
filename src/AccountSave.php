<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$account_id = Import("account_id" , "GP");
$caller     = Import("caller"    , "P");
$account    = Import("account"    , "P");

/* Security check */
if ($account_id != "") { /* Only check of not new account */
	if (!IsAccountOwner($account_id)) {
		Error ("Access denied. You are not this account's owner", "back");
	}
}

if ($account["private"] != 1) {
	$account["private"] = 0;
}
if ($account["disabled"] != 1) {
	$account["disabled"] = 0;
}

if ($account_id != "") {
	/* Update an existing record */

	/* Check if passwords match */
	if ($account["password"] != $account["password2"]) {
		Error ("The passwords do not match. Please try again.", "back");
	}

	$qry_account = "UPDATE accounts SET ";
	if (IsAdmin()) {
		$qry_account .= "disabled='".$account["disabled"]."', ";
	}
	$qry_account .= "password='".$account["password"]."', ";
	$qry_account .= "fullname='".$account["fullname"]."', ";
	$qry_account .= "email='"   .$account["email"]   ."', ";
	$qry_account .= "homepage='".$account["homepage"]."', ";
	$qry_account .= "private='" .$account["private"] ."'  ";
	$qry_account .= "WHERE id='".$account_id         ."'  ";
	
	Debug ($qry_account, __FILE__, __LINE__);
	
	$rslt_account = mysql_query ($qry_account) or mysql_qry_error(mysql_error(), $qry_account, __FILE__, __LINE__);
	Refresh ("AccountOverview&account_id=".$account_id."&caller=".$caller);
} else {
	/* Insert a new record */
	
	/* Check if username already in use */
	$qry_double = "SELECT username FROM accounts WHERE username='".$account["username"]."'";
	$rslt_double = mysql_query($qry_double) or mysql_qry_error(mysql_error(), $qry_double, __FILE__, __LINE__);
	if (mysql_num_rows($rslt_double) > 0) {
		Error("Username already in use, try another", "back");
	}
	
	/* Check if passwords match */
	if ($account["password"] != $account["password2"]) {
		Error ("The passwords do not match. Please try again.", "back");
	}

	$qry_account = "INSERT INTO accounts SET ";
	$qry_account .= "username='".$account["username"]."', ";
	$qry_account .= "password='".$account["password"]."', ";
	$qry_account .= "fullname='".$account["fullname"]."', ";
	$qry_account .= "email='"   .$account["email"]   ."', ";
	$qry_account .= "private='" .$account["private"] ."', ";
	$qry_account .= "homepage='".$account["homepage"]."'  ";
	
	Debug ($qry_account, __FILE__, __LINE__);

	$rslt_account = mysql_query ($qry_account) or mysql_qry_error(mysql_error(), $qry_account, __FILE__, __LINE__);

	$account_id = mysql_insert_id();

	?>
	<p>The account for <b><?=$account["username"]?></b> has been succesfully created. You can now continue to the <a href="<?=$PHP_SELF?>?action=AccountLogin&caller=<?=urlencode($caller)?>">Login</a> page to log yourself in.</p>
	<?
}
?>

