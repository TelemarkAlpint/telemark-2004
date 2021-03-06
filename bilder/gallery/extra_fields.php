<?php
/*
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2004 Bharat Mediratta
 *
 * This file Copyright (C) 2003-2004 Joan McGalliard
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * $Id: extra_fields.php,v 1.16 2004/03/04 00:49:47 jenst Exp $
 */
?>
<?php
// Hack prevention.
if (!empty($HTTP_GET_VARS["GALLERY_BASEDIR"]) ||
		!empty($HTTP_POST_VARS["GALLERY_BASEDIR"]) ||
		!empty($HTTP_COOKIE_VARS["GALLERY_BASEDIR"])) {
	print _("Security violation"). "\n";
	exit;
}

if (!isset($GALLERY_BASEDIR)) {
    $GALLERY_BASEDIR = './';
}

require(dirname(__FILE__) . '/init.php');

// Hack check
if (!$gallery->user->canWriteToAlbum($gallery->album)) {
	exit;
}

if (!empty($save)) {
	$count=0;
	if (!isset($extra_fields))
	{
		$extra_fields = array();
	}

	for ($i = 0; $i < sizeof($extra_fields); $i++) {
	    if (get_magic_quotes_gpc()) {
		$extra_fields[$i] = stripslashes($extra_fields[$i]);
	    }
	    $extra_fields[$i] = str_replace('"', '&quot;', $extra_fields[$i]);
	}
	
	$num_fields=$num_user_fields+num_special_fields($extra_fields);
	$gallery->album->setExtraFields($extra_fields);
	if ($num_fields > 0 && !$gallery->album->getExtraFields())
	{
		$gallery->album->setExtraFields(array());
	}
	if (sizeof ($gallery->album->getExtraFields()) < $num_fields)
	{
		$gallery->album->setExtraFields( array_pad(
			$gallery->album->getExtraFields(), $num_fields, 
			"untitled field"));
	}
	if (sizeof ($gallery->album->getExtraFields()) > $num_fields)
	{
		$gallery->album->setExtraFields(
			array_slice($gallery->album->getExtraFields(), 
			0, $num_fields));
	}
	if (!empty($setNested)) 
	{
		$gallery->album->setNestedExtraFields();
	}
	$gallery->album->save(array(i18n("Custom fields modified")));

	reload();
}

?>
<html>
<head>
  <title><?php echo _("Configure Custom Fields") ?></title>
  <?php echo getStyleSheetLink() ?>
</head>
<body dir="<?php echo $gallery->direction ?>">

<center>
<?php echo _("Configure Custom Fields") ?>

<p>
<?php echo makeFormIntro("extra_fields.php", array(
				"name" => "theform", 
				"method" => "POST")); 
?>
<input type="hidden" name="save" value="1">


<?php $num_user_fields=sizeof($gallery->album->getExtraFields()) - 
	num_special_fields($gallery->album->getExtraFields()); ?>

<table>

<?php
$extra_fields=$gallery->album->getExtraFields();

// Translate the first "Title" in the line below only
?>
<tr>
	<td><?php echo _("Title") ?></td>
	<td align="right"><input type="checkbox" name="extra_fields[]" value="Title" 
		<?php print in_array("Title", $extra_fields) ?  "checked" : ""; ?> ></td>
</tr>
<?php
foreach (automaticFieldsList() as $automatic => $printable_automatic) {
	if ($automatic === "EXIF" && (($gallery->album->fields["use_exif"] !== "yes") || !$gallery->app->use_exif)) {
		continue;
	}
?>
	<tr><td><?php print $printable_automatic ?></td>
	<td align="right"><input type="checkbox" name="extra_fields[]" value="<?php print $automatic ?>"
	<?php print in_array($automatic, $extra_fields) ?  "checked" : ""; 
	?> > </td></tr>
<?php
}
?>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td colspan="2">
	<?php echo _("Number of user defined custom fields") ?> 
	<input type="text" size="4" name="num_user_fields" value="<?php echo $num_user_fields ?>">
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<?php
$i=0;

foreach ($extra_fields as $value)
{
	if (in_array($value, array_keys(automaticFieldsList())))
		continue;
	if (!strcmp($value, "Title"))
		continue;
	print "\n<tr>";
	print "\n\t<td>". _("Field").($i+1).": </td>";
	print "\n\t<td align=\"right\"><input type=\"text\" name=\"extra_fields[]\" value=\"".$value."\"></td>";
	print "\n</tr>";
	$i++;
}

function num_special_fields($extra_fields)
{
	global $special_fields;
	$num_special_fields=0;
	foreach (array_keys(automaticFieldsList()) as $special_field) {
		if (in_array($special_field, $extra_fields))
			$num_special_fields++;
	}
	if (in_array("Title", $extra_fields)) {
		$num_special_fields++;
	}

	return $num_special_fields;  
}
?>
</table>
<p>
	<input type="checkbox" name="setNested" value="1"><?php echo _("Apply to nested Albums") ?>.
</p>
<p>
	<input type="submit" name="apply" value="<?php echo _("Apply") ?>">
	<input type="reset" value="<?php echo _("Undo") ?>">
	<input type="button" name="close" value="<?php echo _("Close") ?>" onclick='parent.close()'>
</p>
</form>
</body>
</html>
