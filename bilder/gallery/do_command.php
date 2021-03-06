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
 * $Id: do_command.php,v 1.56 2004/03/16 14:36:02 jenst Exp $
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

if (!strcmp($cmd, "remake-thumbnail")) {
	if ($gallery->user->canWriteToAlbum($gallery->album)) {
?>
<html>
<head>
  <title><?php echo _("Rebuilding Thumbnails") ?></title>
  <?php echo getStyleSheetLink() ?>
</head>
<body dir="<?php echo $gallery->direction ?>">
<span class="popup">
<?php
		if ($gallery->session->albumName && isset($index)) {
			if (!strcmp($index, "all")) {
				$np = $gallery->album->numPhotos(1);
				echo ("<br> " . sprintf(_("Rebuilding %d thumbnails..."), $np));
				my_flush();
				for ($i = 1; $i <= $np; $i++) {
					echo("<br> ". sprintf(_("Processing image %d..."), $i));
					my_flush();
					set_time_limit($gallery->app->timeLimit);
					$gallery->album->makeThumbnail($i);
				}
			} else {
				echo ("<br> " . _("Rebuilding 1 thumbnail..."));
				my_flush();
				set_time_limit($gallery->app->timeLimit);
				$gallery->album->makeThumbnail($index);
			}
			$gallery->album->save();
			//-- this is expected to be loaded in a popup, so dismiss ---
			dismissAndReload();
		}
	}
} else if (!strcmp($cmd, "logout")) {
	$gallery->session->username = "";
	$gallery->session->language = "";
	header("Location: $return");
} else if (!strcmp($cmd, "hide")) {
	if ($gallery->user->canWriteToAlbum($gallery->album)) {
		$gallery->album->hidePhoto($index);
		$gallery->album->save();
	} else {
		$myAlbumName = $gallery->album->getAlbumName($index);
		if ($myAlbumName) {
			$myAlbum = new Album;
			$myAlbum->load($myAlbumName);
		}

		if ((isset($myAlbum) && $gallery->user->isOwnerOfAlbum($myAlbum)) || 
		    $gallery->album->isItemOwner($gallery->user->getUid(), $index)) {
			$gallery->album->hidePhoto($index);
			$gallery->album->save();
		}
	}
	//-- this is expected to be loaded in a popup, so dismiss ---
	dismissAndReload();
} else if (!strcmp($cmd, "show")) {
	if ($gallery->user->canWriteToAlbum($gallery->album)) {
		$gallery->album->unhidePhoto($index);
		$gallery->album->save();
	} else {
                $myAlbumName = $gallery->album->getAlbumName($index);
                if ($myAlbumName) {
                        $myAlbum = new Album;
                        $myAlbum->load($myAlbumName);
                }       
                        
                if ((isset($myAlbum) && $gallery->user->isOwnerOfAlbum($myAlbum)) ||
		    $gallery->album->isItemOwner($gallery->user->getUid(), $index)) {
			$gallery->album->unhidePhoto($index);
			$gallery->album->save();
		}
	}
	//-- this is expected to be loaded in a popup, so dismiss ---
	dismissAndReload();
} else if (!strcmp($cmd, "highlight")) {
	if ($gallery->user->canWriteToAlbum($gallery->album)) {
		$gallery->album->setHighlight($index);
		$gallery->album->save(array(i18n("Changed Highlight")));
	}
	//-- this is expected to be loaded in a popup, so dismiss ---
	dismissAndReload();
} else if (!strcmp($cmd, "new-album")) {
	if ($gallery->user->canCreateAlbums() ||
	    $gallery->user->canCreateSubAlbum($gallery->album)) {
		if (!isset($parentName)) {
			$parentName=null;
		}
		createNewAlbum($parentName);

		$url = addUrlArg($return, "set_albumName=" .
				 $gallery->session->albumName);
		header("Location: $url");
	} else {
	        header("Location: " . makeAlbumUrl());
	}
} else if (!strcmp($cmd, "reset-album-clicks")) {
	if ($gallery->user->canWriteToAlbum($gallery->album)) {
		$gallery->album->resetAllClicks();
		// this is a popup do dismiss and reload!
		dismissAndReload();
	} else {
	        header("Location: " . makeAlbumUrl());
	}

} else if (!strcmp($cmd, "delete-comment")) {
	if ($gallery->user->canWriteToAlbum($gallery->album)) {
		$comment=$gallery->album->getComment($index, $comment_index); 
		$gallery->album->deleteComment($index, $comment_index);
		$gallery->album->save(array(i18n("Comment \"%s\" by %s deleted from %s"),
					$comment->getCommentText(),
				       	$comment->getName(),
				       	makeAlbumURL($gallery->album->fields["name"], 
						$gallery->album->getPhotoId($index))));
		dismissAndReload();
	} else {
	        header("Location: " . makeAlbumUrl());
	}

} else if (!empty($return)) {
	// No command; Can be used to set a session variable
	header("Location: $return");
}
?>

<center>
<form>
<input type="button" value="<?php echo _("Dismiss") ?>" onclick='parent.close()'>
</form>

</span>
</body>
</html>
