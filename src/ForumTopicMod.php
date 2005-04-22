<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");
$topic_id   = Import ("topic_id"   , "GP");
$wizard     = Import ("wizard"     , "GP");
$topic      = Import ("topic"      , "GP");

/* Security check */
if (!IsLoggedIn()) {
	Error ("Access denied. Must be logged in", "back");
}
if (!IsProjectOwner($project_id)) {
	Error ("Access denied. You're not the project owner", "back");
}

if (isset($topic_id)) {
	$qry_topic = "SELECT * FROM forum WHERE id='".$topic_id."'";
	Debug ($qry_topic, __FILE__, __LINE__);
	$rslt_topic = mysql_query($qry_topic) or mysql_qry_error(mysql_error(), $qry_topic, __FILE__, __LINE__);
	$topic = mysql_fetch_assoc($rslt_topic);
	
}
?>
<table>
<form action="<?=$PHP_SELF?>">
<?
	InputHidden ("action"     , "ForumTopicSave");
	InputHidden ("project_id" , $project_id);
	InputHidden ("topic_id"   , $topic_id);
	InputHidden ("wizard"     , $wizard);
	InputText   ("Subject"    , "topic[subject]" , $topic["subject"]);
	InputArea   ("Description", "topic[contents]", $topic["contents"]);
	InputSubmit ("Save");
?>
</form>
</table>

<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ForumTopicList&project_id=<?=$project_id?>&wizard=<?=$wizard?>">&lt; Forum Topics</a> &nbsp;
