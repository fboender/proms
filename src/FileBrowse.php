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
$field_name = Import ("field_name" , "GP");

?><div style='margin: 10px 10px 10px 10px;'><?

$qry_file_cat = "SELECT * FROM file_categories WHERE project_id='".$project_id."'";
$rslt_file_cat = mysql_query($qry_file_cat) or mysql_qry_error(mysql_error(), $qry_file_cat, __FILE__, __LINE__);
if (mysql_num_rows($rslt_file_cat) > 0) {
	while ($row_file_cat = mysql_fetch_assoc($rslt_file_cat)) {
		?><b><?=$row_file_cat["title"]?></b><?

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
				</tr>
				<?
				while ($row_files = mysql_fetch_assoc($rslt_files)) {
					$row_files["type"] = $mime_types[substr($row_files["contenttype"], 0, strpos($row_files["contenttype"], '/'))];
					$row_files["filesize"] = filesize("files/".$row_files["filename"]);
					if ($row_files["title"] == "") {
						$row_files["title"] = "[none]";
					}
					?>
					<tr valign="top">
						<td><a href="javascript:top.opener.document.forms[0].elements['<?=$field_name?>'].value='<? echo(ThisBaseUrl()."files/".$row_files["filename"]);?>'; window.close();" alt="Select file" title="Select this file"><?=$row_files["title"]?></a></td>
						<td>
							<?
							if ($row_files["version"] != "") {
								?>v<?=$row_files["version"]?><?
							} else {
								?>&nbsp;<?
							}
							?>
						</td>
						<td><span title="<?=$row_files["contenttype"]?>"><?=$row_files["type"]?></span></td>
						<td align="right"><?=number_format($row_files["filesize"])?>  bytes</td>
						<td><?=$row_files["description"]?></td>
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

?></div><?
?>
