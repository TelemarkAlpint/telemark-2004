<?php

// $Id: adminbox.inc,v 1.15 2003/11/13 10:19:35 beckettmw Exp $
// Note that this box is assumed to be attached to a navigator and uses the navigator's
// width parameters .

global $adminbox, $navigator;
// If the border color is not passed in, we do a black/white combo
if ($adminbox["bordercolor"]) {
	$borderIn = $adminbox["bordercolor"];
} else {
	$borderIn = "#FFFFFF";
}
if (!isset($adminbox["commands"])) {
	$adminbox["commands"] = "";
}

$pixelImage = "<img src=\"" . getImagePath('pixel_trans.gif') . "\" width=\"1\" height=\"1\" alt=\"pixel_trans\">";

?>
<table width="<?php echo $navigator["fullWidth"] . $navigator["widthUnits"] ?>" border="0" cellspacing="0" cellpadding="0" class="modnavboxtop">
<?php
if ($adminbox["top"]) {
}
?>
  <tr> 
    <td class="admin" align="left" valign="middle" width="4000" height="18">&nbsp;<?php echo $adminbox["text"] ?></td>
    <td class="admin" align="right" valign="middle" width="3000" height="18">&nbsp;<?php echo $adminbox["commands"] ?>&nbsp;</td>
  </tr>
<?php
if (!$adminbox["top"]) {
}
?>
</table>    
