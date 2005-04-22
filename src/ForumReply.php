<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$thread_id  = Import ("thread_id"  , "GP");
$project_id = Import ("project_id" , "GP");
$topic_id   = Import ("topic_id"   , "GP");
$reply      = Import ("reply"      , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}


if (isset($thread_id)) {
	/* Get info on thread to which we are replying */
	$users = ReadLookup ("accounts", "username");

	$qry_replyto = "SELECT * FROM forum WHERE id='".$thread_id."'";
	Debug ($qry_replyto, __FILE__, __LINE__);
	$rslt_replyto = mysql_query($qry_replyto) or mysql_qry_error(mysql_error(), $qry_replyto, __FILE__, __LINE__);
	$replyto = mysql_fetch_assoc($rslt_replyto);
	?>
	<table>
		<tr>
			<th class="head"><?=htmlentities($replyto["subject"])?></th>
			<th class="head" align="right">User: <i>
				<?
					if ($replyto["user_id"] == 0) {
						?>Anonymous<?
					} else
					if ($users[$replyto["user_id"]] == "") {
						?>Unknown<?
					} else {
						?><a class="action" href="<?=$PHP_SELF?>?action=AccountOverview&account_id=<?=$replyto["user_id"]?>"><?=$users[$replyto["user_id"]]?></a><?
					}
				?></i></th><th class="head">Date: <i><?=date("d M Y H:i", $replyto["postdate"])?></i></th></tr> 
				<tr bgcolor="#D0D0D0"><td colspan="3"><?=bbcode($replyto["contents"])?></td>
		</tr>
		<tr><td colspan="3"><div class="separator">&nbsp;</div></td></tr>
	</table>
	<?
}

?>
<table>
<form action="<?=$PHP_SELF?>">
<?
	InputHidden   ("action"     ,"ForumReplySave");
	InputHidden   ("project_id" ,$project_id);
	InputHidden   ("thread_id"  ,$thread_id);
	InputHidden   ("topic_id"   ,$topic_id);
	InputText     ("Subject"    ,"reply[subject]"   , $replyto["subject"]);
	InputArea     ("Message<sup>1</sup>"    ,"reply[contents]"  , $reply["contents"]);
	InputCheckBox ("Notify"     ,"reply[notify]"    , $reply["notify"]);
	InputSubmit   ("Reply");
?>
</form>
</table>

<p><sup>1</sup>: <a title="BBCode help" href="javascript:void(0);" OnClick="window.open('<?=$PHP_SELF?>?action=Documentation&topic=ForumReply','','height=600,width=400,scrollbars=yes');">BBCode</a> available.</p>
