<?php /* $Id: commentbox.inc,v 1.14 2003/10/07 23:27:22 jefmcg Exp $ */ ?>
<?php global $comment,$index,$j; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="albumdesc">
  <tr>
  <td width="100%">
  <span class="admin">
  <?php echo _("From:") ?> <?php echo $comment->getName() ?> @ <?php echo removeTags($comment->getIPNumber())?> (<?php echo $comment->getDatePosted() ?>)
  </span>
</td>
</tr><tr>
<td width="100%" height="20" valign="top" class="albumdesc"><?php echo $comment->getCommentText() ?><td>
</tr>
<?php
if ($gallery->user->canWriteToAlbum($gallery->album)) {
	$url = "do_command.php?cmd=delete-comment&index=$index&comment_index=$j";
	$buf = '<span class="admin"> <a href="#" onClick="javascript:' . popup($url) . '">[' . _("delete comment") .']</a></span>';
	echo "<tr><td>$buf</td></tr>";
}
?>
<tr>
<td width=100%><img src="<?php echo getImagePath('pixel_trans.gif') ?>" width="1" height="6" alt=""></td>
</tr>
</table>