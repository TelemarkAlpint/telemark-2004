<?php /* $Id: commentdraw.inc,v 1.18 2004/03/22 15:17:26 jenst Exp $ */ ?>
<?php 

	global $commentdraw, $i, $index;

// If the border color is not passed in, we do a black/white combo
if ($commentdraw["bordercolor"]) {
        $borderIn = $commentdraw["bordercolor"];
} else {
        $borderIn = "#FFFFFF";
}

$pixelImage = "<img src=\"" . getImagePath('pixel_trans.gif') . "\" width=\"1\" height=\"1\" alt=\"\">";

?>
<!-- Begin Comment -->
<tr>
	<td width="15%"></td>
	<td width="70%" class="modcaption">
	<br><?php echo _("From:") ?> <b><?php echo $commentdraw["name"] ?></b> &nbsp;
<?php
  if ($gallery->user->isAdmin()) {
      echo "@ &nbsp;";
      echo $commentdraw["IPNumber"];
      echo "&nbsp;";
  }
?>
        (<?php echo $commentdraw["datePosted"] ?>)
	<br><?php echo $commentdraw["comment"] ?>
<?php
if ($gallery->user->canWriteToAlbum($gallery->album)) {
	$url = "do_command.php?cmd=delete-comment&index=$index&comment_index=$i";
	$buf = '<a href="#" onClick="javascript:' . popup($url) . '">['. _("delete comment") . ']</a>';
	echo "$buf";
}
?>
	<br><br>
	</td>
	<td width="15%"></td>
</tr>
<!-- End Comment -->
