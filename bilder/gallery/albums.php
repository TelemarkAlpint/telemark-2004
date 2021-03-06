<?php
/*
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2004 Bharat Mediratta
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
 * $Id: albums.php,v 1.132.2.1 2004/04/11 23:01:58 jenst Exp $
 */
?>
<?php
// Hack prevention.
if (!empty($HTTP_GET_VARS["GALLERY_BASEDIR"]) ||
		!empty($HTTP_POST_VARS["GALLERY_BASEDIR"]) ||
		!empty($HTTP_COOKIE_VARS["GALLERY_BASEDIR"])) {
	print _("Security violation") ."\n";
	exit;
}

if (!isset($GALLERY_BASEDIR)) {
    $GALLERY_BASEDIR = './';
}

require(dirname(__FILE__) . '/init.php');

$gallery->session->offlineAlbums["albums.php"]=true;

/* Read the album list */
$albumDB = new AlbumDB(FALSE);
$gallery->session->albumName = "";
$page = 1;

/* If there are albums in our list, display them in the table */
list ($numPhotos, $numAccess, $numAlbums) = $albumDB->numAccessibleItems($gallery->user);

if (empty($gallery->session->albumListPage)) {
	$gallery->session->albumListPage = 1;
}
$perPage = $gallery->app->albumsPerPage;
$maxPages = max(ceil($numAlbums / $perPage), 1);

if ($gallery->session->albumListPage > $maxPages) {
	$gallery->session->albumListPage = $maxPages;
}

$pixelImage = '<img src="' . getImagePath('pixel_trans.gif') . '" width="1" height="1" alt="pixel_trans">';
$borderColor = $gallery->app->default["bordercolor"];

$navigator["page"] = $gallery->session->albumListPage;
$navigator["pageVar"] = "set_albumListPage";
$navigator["url"] = makeGalleryUrl("albums.php");
$navigator["maxPages"] = $maxPages;
$navigator["spread"] = 6;
$navigator["fullWidth"] = 100;
$navigator["widthUnits"] = "%";
$navigator["bordercolor"] = $borderColor;

$displayCommentLegend = 0;  // this determines if we display "* Item contains a comment" at end of page 

/*
** when direction is ltr(left to right) everything is fine)
** when rtl(right to left), like in hebrew, we have to switch the alignment at some places.
*/
if ($gallery->direction == 'ltr') {
	$left="left";
	$right="right";
}
else {
	$left="right";
	$right="left";
}
if (!$GALLERY_EMBEDDED_INSIDE) {
	doctype();
?>
<html>
<head>
  <title><?php echo $gallery->app->galleryTitle ?></title>
  <?php 
	echo getStyleSheetLink();
	/* prefetching/navigation */
  if ($navigator['page'] > 1) { ?>
      <link rel="top" href="<?php echo makeGalleryUrl('albums.php', array('set_albumListPage' => 1)) ?>" />
      <link rel="first" href="<?php echo makeGalleryUrl('albums.php', array('set_albumListPage' => 1)) ?>" />
      <link rel="prev" href="<?php echo makeGalleryUrl('albums.php', array('set_albumListPage' => $navigator['page']-1)) ?>" />
  <?php }
  if ($navigator['page'] < $maxPages) { ?>
      <link rel="next" href="<?php echo makeGalleryUrl('albums.php', array('set_albumListPage' => $navigator['page']+1)) ?>" />
      <link rel="last" href="<?php echo makeGalleryUrl('albums.php', array('set_albumListPage' => $maxPages)) ?>" />
  <?php } ?>
</head>
<body dir="<?php echo $gallery->direction ?>">
<?php }
	includeHtmlWrap("gallery.header");
	if (!$gallery->session->offline && !strcmp($gallery->app->showSearchEngine, "yes")) {
?>
<table width="100%" border="0" cellspacing="0" style="margin-bottom:2px">
<tr>
<?php
	if ($GALLERY_EMBEDDED_INSIDE =='phpBB2') {
		echo '<td class="nav"><a href="index.php">'. sprintf($lang['Forum_Index'], $board_config['sitename']) . '</a></td>';
}
?>
<td valign="middle" align="right">
<?php echo makeFormIntro("search.php"); ?>
<span class="search"> <?php echo _("Search") ?>: </span>
<input style="font-size:10px;" type="text" name="searchstring" value="" size="25">
</form>
</td>
</tr>
</table>

<?php
}
?>
<!-- admin section begin -->
<?php 
$adminText = "<span class=\"admin\">";
$toplevel_str= pluralize_n($numAlbums, ($numAccess != $numAlbums) ? _("1 top-level album") : _("1 album"), ($numAccess != $numAlbums) ? _("top-level albums") : _("albums"), _("No albums"));
$total_str= sprintf(_("%d total"), $numAccess); 
$image_str= pluralize_n($numPhotos, _("1 image"), _("images"), _("no images"));
$page_str= pluralize_n($maxPages, _("1 page"), _("pages"), _("no pages"));

if (($numAccess != $numAlbums) && $maxPages > 1) {
	$adminText .= sprintf(_("%s (%s), %s on %s"), $toplevel_str, $total_str, $image_str, $page_str);
}
else if ($numAccess != $numAlbums) {
	$adminText .= sprintf(_("%s (%s), %s"), $toplevel_str, $total_str, $image_str);
} else if ($maxPages > 1) {
	$adminText .= sprintf(_("%s, %s on %s"), $toplevel_str, $image_str, $page_str);
} else {
	$adminText .= sprintf(_("%s, %s"), $toplevel_str, $image_str);
}
$adminText .= "</span>";
$adminCommands = "<span class=\"admin\">";

if ($gallery->user->isLoggedIn() && !$gallery->session->offline && 
	! ($GALLERY_EMBEDDED_INSIDE_TYPE == 'phpBB2' && $gallery->user->uid == -1)) {

	$displayName = $gallery->user->getFullname();
	if (empty($displayName)) {
		$displayName = $gallery->user->getUsername();
	}
	$adminCommands .= sprintf(_("Welcome, %s"), $displayName) . "&nbsp;&nbsp;<br>";
}

if ($gallery->app->gallery_slideshow_type != "off") {
    	 $adminCommands .= "\n". '<a class="admin" href="' . makeGalleryUrl("slideshow.php",
	 array("set_albumName" => null)) .
	       	'">['._("slideshow") . ']</a>&nbsp;';
}
if ($gallery->user->isAdmin()) {
	$doc = galleryDocs('admin');
	if ($doc) {
		$adminCommands .= "\n$doc&nbsp;";
	}
}
if ($gallery->user->canCreateAlbums() && !$gallery->session->offline) { 
	$adminCommands .= "\n<a class=\"admin\" href=\"" . doCommand("new-album", array(), "view_album.php") . "\">[". _("new album") ."]</a>&nbsp;";
}

if ($gallery->user->isAdmin()) {
	if ($gallery->userDB->canModifyUser() ||
	    $gallery->userDB->canCreateUser() ||
	    $gallery->userDB->canDeleteUser()) {
		$adminCommands .= popup_link("[" . _("manage users") ."]", 
			"manage_users.php", false, true, 500, 500, 'admin')
			. '&nbsp;';
	}
}

if ($gallery->user->isLoggedIn() && !$gallery->session->offline) {
	if ($gallery->userDB->canModifyUser()) {
		$adminCommands .= popup_link("[". _("preferences") ."]", 
			"user_preferences.php", false, true, 500, 500, 'admin')
			. '&nbsp;';
	}
	
	if (!$GALLERY_EMBEDDED_INSIDE) {
		$adminCommands .= "<a class=\"admin\" href=\"". doCommand("logout", array(), "albums.php"). "\">[". _("logout") ."]</a>";
	}
} else {
	if (!$GALLERY_EMBEDDED_INSIDE) {
	        $adminCommands .= popup_link("[" . _("login") . "]", "login.php", false, true, 500, 500, 'admin');
		
            if (!strcmp($gallery->app->selfReg, 'yes')) {
                $adminCommands .= '&nbsp;';
                $adminCommands .= popup_link('[' . _("register") . ']', 'register.php', false, true, 500, 500, 'admin');
            }
	}
}

$adminCommands .= "</span>";
$adminbox["text"] = $adminText;
$adminbox["commands"] = $adminCommands;
$adminbox["bordercolor"] = $borderColor;
$adminbox["top"] = true;
includeLayout('navtablebegin.inc');
includeLayout('adminbox.inc');
includeLayout('navtablemiddle.inc');

echo "<!-- Begin top nav -->";

includeLayout('navigator.inc');
includeLayout('navtableend.inc');
includeLayout('ml_pulldown.inc');

echo "<!-- End top nav -->";

/* Display warnings about broken albums */
if (sizeof($albumDB->brokenAlbums) && $gallery->user->isAdmin()) {

    echo "\n<center><div style=\"margin:3px; width:60%; border-style:outset; border-width:5px; border-color:red\">";
    echo "\n<p class=\"head\"><u>". _("Attention Gallery Administrator!") ."</u></p>";

    echo sprintf(_("%s has detected the following %d invalid album(s) in your albums directory<br>(%s):"),
		    Gallery(), sizeof($albumDB->brokenAlbums), $gallery->app->albumDir);
    echo "\n<p>";
    foreach ($albumDB->brokenAlbums as $tmpAlbumName) {
	echo "<br>$tmpAlbumName\n";
    }
    echo "\n</p>". _("Please move it/them out of the albums directory.") ;
    echo "\n</p></div></center>\n";
}
?>

<!-- album table begin -->
<table width="100%" border="0" cellpadding=0 cellspacing=7>

<?php
$start = ($gallery->session->albumListPage - 1) * $perPage + 1;
$end = min($start + $perPage - 1, $numAlbums);
for ($i = $start; $i <= $end; $i++) {
        $gallery->album = $albumDB->getAlbum($gallery->user, $i);
	$isRoot = $gallery->album->isRoot(); // Only display album if it is a root album
	if($isRoot) {
		if (strcmp($gallery->app->showOwners, "no")) {
			$owner = $gallery->album->getOwner();
		}
        	$tmpAlbumName = $gallery->album->fields["name"];
        	$albumURL = makeAlbumUrl($tmpAlbumName);
?>     

  <!-- Begin Album Column Block -->
  <tr>
  <td height="1"><?php echo $pixelImage ?></td>
  <td height="1"><?php echo $pixelImage ?></td>
<?php
  if (!strcmp($gallery->app->showAlbumTree, "yes")) {
?>
  <td height="1"><?php echo $pixelImage ?></td>

<?php
  }
?>
  </tr>
  <tr>
  <!-- Begin Image Cell -->
  <td align="center" valign="middle">

<?php
      $gallery->html_wrap['borderColor'] = $borderColor;
      $gallery->html_wrap['borderWidth'] = 1;
      $gallery->html_wrap['pixelImage'] = getImagePath('pixel_trans.gif');
      $scaleTo = $gallery->app->highlight_size;
      list($iWidth, $iHeight) = $gallery->album->getHighlightDimensions($scaleTo);
      if (!$iWidth) {
	  $iWidth = $gallery->app->highlight_size;
	  $iHeight = 100;
      }
      $gallery->html_wrap['imageWidth'] = $iWidth;
      $gallery->html_wrap['imageHeight'] = $iHeight;
      $gallery->html_wrap['imageTag'] = $gallery->album->getHighlightTag($scaleTo,'', _("Highlight for Album: "). htmlentities(removeTags($gallery->album->fields["title"]),ENT_COMPAT));
      $gallery->html_wrap['imageHref'] = $albumURL;
      $gallery->html_wrap['frame'] = $gallery->app->gallery_thumb_frame_style;
      includeHtmlWrap('inline_gallerythumb.frame');
?>
  </td>
  <!-- End Image Cell -->
  <!-- Begin Text Cell -->
  <td align="<?php echo $left ?>" valign="top" class="albumdesc">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" align="center" class="mod_title">
      <tr valign="middle">
        <td class="leftspacer"></td>
        <td>
          <table cellspacing="0" cellpadding="0" border="0" class="mod_title_bg">
            <tr>
              <td class="mod_title_left"></td>
              <td nowrap class="title">
                <?php _("title") ?>
                <?php echo editField($gallery->album, "title", $albumURL) ?>
              </td>
              <td class="mod_title_right"></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="mod_titleunder_hl"></td>
      </tr>
    </table>

  <?php 
	include(dirname(__FILE__) . '/layout/adminAlbumCommands.inc');

	$description=editField($gallery->album, "description") ;
	if ($description != "") {
		echo "\n<div class=\"desc\">";
		echo "\n\t$description";
		echo "\n</div>";
  	}

	if (strcmp($gallery->app->showOwners, "no")) {
		echo "\n<div class=\"desc\">";
		echo _("Owner:") . " ";
		if (!$owner->getEmail()) {
			echo $owner->getFullName();
		} else {
			echo "<a href=\"mailto:" . $owner->getEmail() . "\">" . $owner->getFullName() . "</a>";
		}
		echo '</div>';
	}

	if ($gallery->user->isAdmin() || $gallery->user->isOwnerOfAlbum($gallery->album)) {
		echo _("url:") . '<a href="'. $albumURL . '">';
		if (!$gallery->session->offline) {
			echo breakString(urldecode($albumURL), 60, '&', 5);
		} else {
			echo $tmpAlbumName;
		}
		echo '</a>';

		if (ereg("album[[:digit:]]+$", $albumURL)) {
			if (!$gallery->session->offline) {
				echo '<br><span class="error">' .
				_("Hey!") .
				sprintf(_("%s this album so that the URL is not so generic!"), 
					popup_link(_("Rename"), "rename_album.php?set_albumName={$tmpAlbumName}&index=$i"));
				echo '</span>';
			}
		}

		if ($gallery->album->versionOutOfDate()) {
			if ($gallery->user->isAdmin()) {
  				echo '<br><span class="error">';
				echo _("Note:  This album is out of date!") ?> <?php echo popup_link("[" . _("upgrade album") ."]", "upgrade_album.php");
				echo '</span>';
			}
		}
	} 
	?>

  <br>
  <span class="fineprint">
   <?php echo sprintf(_("Last changed on %s."), $gallery->album->getLastModificationDate() )?>  
   <?php echo sprintf(_("This album contains %s." ), pluralize_n(array_sum($gallery->album->numVisibleItems($gallery->user)), _("1 item"), _("items"), _("no items")));
if (!($gallery->album->fields["display_clicks"] == "no") && 
	!$gallery->session->offline) {
?>
   <br><br><?php echo sprintf(_("This album has been viewed %s since %s."),
		   pluralize_n($gallery->album->getClicks(), _("1 time"), _("times") , _("0 times")),
		   $gallery->album->getClicksDate() );
}
$albumName=$gallery->album->fields["name"];
if ($gallery->user->canWriteToAlbum($gallery->album) &&
   (!($gallery->album->fields["display_clicks"] == "no"))) {
?>
<?php echo " ".popup_link("[" . _("reset counter") ."]", doCommand("reset-album-clicks", array("set_albumName" => $albumName), "albums.php"), 1) ?>

<?php
}
if($gallery->app->comments_enabled == 'yes') {
	$lastCommentDate = $gallery->album->lastCommentDate();
	print lastCommentString($lastCommentDate, $displayCommentLegend);
}
?>

  </span>
  </td>
<?php if (!strcmp($gallery->app->showAlbumTree, "yes")) { ?>
  <td align=left valign=top class="albumdesc">
   <?php echo printChildren($albumName); ?>
  </td>
<?php } ?>
  </tr>
  <!-- End Text Cell -->
  <!-- End Album Column Block -->

<?php
} // if isRoot() close
} // for() loop      
?>
</table>
<!-- album table end -->
<?php if ($displayCommentLegend) { //display legend for comments ?>
<span class=error>*</span><span class=fineprint> <?php echo _("Comments available for this item.") ?></span>
<br><br>
<?php } ?>
<!-- bottom nav -->
<?php
includeLayout('navtablebegin.inc');
includeLayout('navigator.inc');
includeLayout('navtableend.inc');
?>

<!-- gallery.footer begin -->
<?php

includeHtmlWrap("gallery.footer");
?>
<!-- gallery.footer end -->

<?php if (!$GALLERY_EMBEDDED_INSIDE) { ?>
</body>
</html>
<?php } ?>

