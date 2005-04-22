<?
/* 
Copyright (C), 2003 Ferry Boender. Released under the General Public License
For more information, see the COPYING file supplied with this program.                                                          
*/

$mime_types = array(
	"application" => "Binary data",
	"audio" => "Audio",
	"image" => "Image",
	"text" => "Text",
	"video" => "Video"
);

$project_id = Import ("project_id" , "GP");

$qry_file_cat = "SELECT * FROM file_categories WHERE project_id='".$project_id."'";
$rslt_file_cat = mysql_query($qry_file_cat) or mysql_qry_error(mysql_error(), $qry_file_cat, __FILE__, __LINE__);
if (mysql_num_rows($rslt_file_cat) > 0) {
	while ($row_file_cat = mysql_fetch_assoc($rslt_file_cat)) {
		?><b><?=$row_file_cat["title"]?></b><?
		if (IsAuthorized($project_id, AUTH_FILE_MODIFY)) {
			?><a href="<?=$PHP_SELF?>?action=FileCategoryMod&project_id=<?=$project_id?>&file_cat_id=<?=$row_file_cat["id"]?>"><img src="images/ico_edit.gif" alt="Edit" title="Edit this category's details" border="0"></a><?
		}
		?><br><p><?=$row_file_cat["description"]?></p><?
		
		$qry_files = "SELECT * FROM files WHERE category_id = '".$row_file_cat["id"]."'";
		$rslt_files = mysql_query($qry_files) or mysql_qry_error(mysql_error(), $qry_files, __FILE__, __LINE__);
		if (mysql_num_rows($rslt_files) > 0) {
			?>
			<p>
			<table>
				<tr>
					<th class="head">File</th>
					<th class="head">Version</th>
					<th class="head">Type</th>
					<th class="head">Size</th>
					<th class="head">Description</th>
					<?
					if (IsAuthorized($project_id, AUTH_FILE_MODIFY)) {
						?><th class="head">&nbsp;</th><?
					}
					?>
				</tr>
				<?
				while ($row_files = mysql_fetch_assoc($rslt_files)) {
					$row_files["type"] = $mime_types[substr($row_files["contenttype"], 0, strpos($row_files["contenttype"], '/'))];
					$row_files["filesize"] = @filesize("files/".$row_files["filename"]);
					if ($row_files["title"] == "") {
						$row_files["title"] = "[none]";
					}

					if (!file_exists("files/".$row_files["filename"])) {
						?><tr valign="top" bgcolor="#FF9090"><?
					} else {
						?><tr valign="top" bgcolor="<?=RowColor()?>"><?
					}
						?>
						<td><nobr><a href="files/<?=$row_files["filename"]?>" alt="Download" title="Download"><?=$row_files["title"]?></a></nobr></td>
						<td>
							<nobr>
							<?
							if ($row_files["version"] != "") {
								?>v<?=$row_files["version"]?><?
							} else {
								?>&nbsp;<?
							}
							?>
							</nobr>
						</td>
						<td><nobr><span title="<?=$row_files["contenttype"]?>"><?=$row_files["type"]?></span></nobr></td>
						<td align="right"><nobr><?=number_format($row_files["filesize"])?>  bytes</nobr></td>
						<td><?=$row_files["description"]?></td>
						<?
						if (IsAuthorized($project_id, AUTH_FILE_MODIFY)) {
							?><td><a href="<?=$PHP_SELF?>?action=FileMod&project_id=<?=$project_id?>&file_id=<?=$row_files["id"]?>"><img src="images/ico_edit.gif" alt="Edit" title="Edit this file's details" border="0"></a></td><?
						}
						?>
					</tr>
					<?
				}
				?>
			</table>
			</p>
			<?
		} else {
			?><p><i>No files in this category,</i></p><?
		}
	}
} else {
	?><p><i>No categories at this moment.</i></p><?
}
?>
<div class="actionseparator">&nbsp;</div>
&nbsp;
<a class="nav" href="<?=$PHP_SELF?>?action=ProjectOverview&project_id=<?=$project_id?>">&lt; Project overview</a> &nbsp;
<?
if (IsAuthorized($project_id, AUTH_PROJECT_MODIFY)) {
	?><a class="action" href="<?=$PHP_SELF?>?action=FileCategoryAdd&project_id=<?=$project_id?>">Add category</a> &nbsp;<?
}
if (IsAuthorized($project_id, AUTH_FILE_ADD)) {
	?><a class="action" href="<?=$PHP_SELF?>?action=FileAdd&project_id=<?=$project_id?>">Add file</a> &nbsp;<?
}

?>
