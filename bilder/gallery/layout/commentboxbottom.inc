<?php /* $Id: commentboxbottom.inc,v 1.12 2003/10/16 22:53:12 jefmcg Exp $ */ ?>
<?php global $embeddedAlbum, $id;
if(!$embeddedAlbum == 1)
{
?>
</td>
</tr>
</table>
<?php
        $url = "add_comment.php?set_albumName={$gallery->album->fields['name']}&id=$id";
        $buf = "<span class=editlink>";
	$buf .= '<br><a href="#" onClick="javascript:' . popup($url) . '">[' . _("add comment") .']</a>';
        $buf .= "</span>";
        echo "<br>$buf<br>";
?>
<br>
</td>
<td width="20">&nbsp;</td>
<?php } ?>
</tr>
</table>
<br>
