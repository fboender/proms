<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$project_id = Import ("project_id" , "GP");

?>
<div style="margin-left: 10px;">
	<b>Project</b>
	<div style="margin-left: 40px; margin-bottom: 20px;">
		<br>General project information.<br><br>
		<?
		if (IsAuthorized($project_id, 'AUTH_PROJECT_MODIFY')) {
			?>
			<a href="<?=$PHP_SELF?>?action=ProjectMod&project_id=<?=$project_id?>">Modify project</a> &nbsp;<br>
			<a href="<?=$PHP_SELF?>?action=ProjectPartList&project_id=<?=$project_id?>">Modify parts</a> &nbsp;<br>
			<?
		}
		if (IsAuthorized($project_id, 'AUTH_PROJECTMEMBERS_MODIFY')) {
			?>
			<a href="<?=$PHP_SELF?>?action=ProjectMemberList&project_id=<?=$project_id?>">Modify members</a> &nbsp;<br>
			<?
		}
		?>
	</div>
	<b>Releases</b>
	<div style="margin-left: 40px; margin-bottom: 20px;">
		<br>Releases made of this project.<br><br>
		<?
		if (IsAuthorized($project_id, 'AUTH_RELEASE_ADD')) {
			?>
			<a href="<?=$PHP_SELF?>?action=ReleaseAdd&project_id=<?=$project_id?>">New release</a> &nbsp;<br>
			<?
		}
		?>
	</div>
	<b>Files</b>
	<div style="margin-left: 40px; margin-bottom: 20px;">
		<br>Files that belong to this project, like documentation, READMEs, changelogs, designs, logos, sourcecode, binaries, etc.<br><br>
		<?
		if (IsAuthorized($project_id, AUTH_PROJECT_MODIFY)) {
			?><a href="<?=$PHP_SELF?>?action=FileCategoryAdd&project_id=<?=$project_id?>">Add category</a> &nbsp;<br><?
		}
		if (IsAuthorized($project_id, AUTH_FILE_ADD)) {
			?><a href="<?=$PHP_SELF?>?action=FileAdd&project_id=<?=$project_id?>">Add file</a> &nbsp;<br><?
		}
		?>
	</div>
	<b>Discussions</b>
	<div style="margin-left: 40px; margin-bottom: 20px;">
		<br>Dicusssions about this project.<br><br>
		<?
		if (IsAuthorized($project_id, 'AUTH_FORUM_MODIFY')) {
			?>
			<a href="<?=$PHP_SELF?>?action=ForumTopicList&project_id=<?=$project_id?>">Edit topics</a> &nbsp;<br>
			<?
		}
		?>
	</div>
</div>
<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project Overview</a> &nbsp;
<?
