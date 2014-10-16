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
 * $Id: low.inc,v 1.6 2004/03/21 17:18:01 jenst Exp $
 */

/*
 * DEVELOPERS.  PLEASE READ!!!
 *
 *    If you are changing any Javascript in this file please be very
 *    careful.  This is designed to work with the oldest, bodgiest
 *    browsers, so try not to introduce any new functions.  It's better
 *    to have reduced functionality here than have it broken on some
 *    browsers.  If you MUST add new JS, research it thoroughly to make
 *    sure it should work with ancient browsers, but better to only 
 *    change the JS to fix bugs.
 *
 *       Thanks
 */


?>
<?php
function slideshow_initialize() {
	global $defaultLoop, $defaultPause, $defaultFull, $defaultDir,
			$slide_index, $slide_pause, $slide_loop, $slide_full,
			$slide_dir, $borderColor, $borderwidth, $loop,
			$bgcolor, $title, $gallery;
	// default settings ---
	$defaultLoop = 0;
	$defaultPause = 3;
	$defaultFull = 0;
	$defaultDir = 1;
	
	if (!isset($slide_index)) {
	    $slide_index = 1;
	}
	if (!isset($slide_pause)) {
	    $slide_pause = $defaultPause;
	}
	if (!isset($slide_loop)) {
	    $slide_loop = $defaultLoop;
	}
	if (!isset($slide_full)) {
	    $slide_full = $defaultFull;
	}
	if (!isset($slide_dir)) {
	    $slide_dir = $defaultDir;
	}
	
	if ($slide_full && !$gallery->user->canViewFullImages($gallery->album)) {
	    $slide_full = 0;
	}
	
	$bgcolor = $gallery->album->fields['bgcolor'];
	$title = _("Slide Show for album") ." :: ". $gallery->album->fields["title"];
}

function makeSlideLowUrl($index, $loop, $pause, $full, $dir) {
	global $gallery;

    return makeGalleryUrl('slideshow.php',
	array('set_albumName' => $gallery->session->albumName,
	      'slide_index' => $index,
	      'slide_loop' => $loop,
	      'slide_pause' => $pause,
	      'slide_full' => $full,
	      'slide_dir' => $dir,
		  'mode' => 'low'));
}

function slideshow_body() {
	global $defaultLoop, $defaultPause, $defaultFull, $defaultDir,
			$slide_index, $slide_pause, $slide_loop, $slide_full,
			$slide_dir, $borderColor, $borderwidth, $loop,
			$bgcolor, $title, $gallery, $numPhotos, $numDisplayed,
			$index, $photo_count, $photo, $image, $thumbImage,
			$photoURL, $caption, $imageDir, $pixelImage, $number;
?>
<script language="JavaScript">
var timer; 
var current_location = <?php echo $slide_index ?>;
var next_location = <?php echo $slide_index ?>; 
var pics_loaded = 0;
var onoff = 0;
var timeout_value;
var images = new Array;
var photo_urls = new Array;
var photo_captions = new Array;
var loop = <?php echo $slide_loop ?>;
var full = <?php echo $slide_full ?>;
var direction = <?php echo $slide_dir ?>;
<?php

$numPhotos = $gallery->album->numPhotos(1);
$numDisplayed = 0; 

// A slideshow_length of 0 means "show all"
// as does a length which is greater than the number of photos
if ($number == 0 || $number > $numPhotos) {
        $number = $numPhotos; 
}       

// Find the correct starting point, accounting for hidden photos
$index = getNextPhoto(0);
$photo_count = 0;
while ($numDisplayed < $numPhotos && $numDisplayed < $number) { 
    if ($index > $numPhotos) {
	/*
	 * We went past the end -- this can happen if the last element is an
	 * album that we can't read.
	 */
	break;
    }
    $photo = $gallery->album->getPhoto($index);
    $numDisplayed++;

    // Skip movies and nested albums
    if ($photo->isMovie() || $photo->isAlbum()) {
	$index = getNextPhoto($index);
	continue;
    }

    $photo_count++;

    $image = $photo->image;
    if ($photo->image->resizedName) {
        $thumbImage = $photo->image->resizedName;
    } else {
        $thumbImage = $photo->image->name;
    }
    $photoURL = $gallery->album->getPhotoPath($index, $slide_full);

    // Now lets get the captions
    $caption = $gallery->album->getCaption($index);
    $caption .= $gallery->album->getCaptionName($index);

    $caption = str_replace("\"", " ", $caption);
    $caption = str_replace("\n", " ", $caption);
    $caption = str_replace("\r", " ", $caption);

    // Print out the entry for this image as Javascript
    print "photo_urls[$photo_count] = \"$photoURL\";\n";
    print "photo_captions[$photo_count] = \"$caption\";\n";

    // Go to the next photo
    $index = getNextPhoto($index);
}
?>
var photo_count = <?php echo $photo_count ?>; 

function stop() {
    onoff = 0;
    status = "<?php echo unhtmlentities(_("The slide show is stopped, Click [play] to resume.")) ?>";
    clearTimeout(timer);
}

function play() {
    onoff = 1;
    status = "<?php echo unhtmlentities(_("Slide show is running...")) ?>";
    wait_for_current_photo();
}

function toggleLoop() {
    if (loop) {
        loop = 0;
    } else {
        loop = 1;
    }
}

function reset_timer() {
    clearTimeout(timer);
    if (onoff) {
	timeout_value = document.TopForm.time.options[document.TopForm.time.selectedIndex].value * 1000;
	timer = setTimeout('go_to_next_page()', timeout_value);
    }
}

function wait_for_current_photo() {

    /* Show the current photo */
    if (!show_current_photo()) {

	/*
	 * The current photo isn't loaded yet.  Set a short timer just to wait
	 * until the current photo is loaded.
	 */
	 status = "<?php echo unhtmlentities(_("Picture is loading...")) ?>(" + current_location + " <?php echo unhtmlentities(_("of")) ?> " + photo_count +
		 ").  <?php echo unhtmlentities(_("Please Wait...")) ?>" ;

	clearTimeout(timer);
	timer = setTimeout('wait_for_current_photo()', 500);
	return 0;
    } else {
	preload_next_photo();
	reset_timer();
    }
}

function go_to_next_page() {

    var slideShowUrl = "<?php echo makeGalleryUrl('slideshow.php',
				array('set_albumName' => $gallery->session->albumName, 'mode' => 'low')); ?>";

    document.location = slideShowUrl + "&slide_index=" + next_location + "&slide_full=" + full
	+ "&slide_loop=" + loop + "&slide_pause=" + (timeout_value / 1000) 
	+ "&slide_dir=" + direction;
    return 0;
}

function preload_next_photo() {
    
    /* Calculate the new next location */
    next_location = (parseInt(current_location) + parseInt(direction));
    if (next_location > photo_count) {
	next_location = 1;
	if (!loop) {
	    stop();
	}
    }
    if (next_location < 1) {
	next_location = photo_count;
	if (!loop) {
	    stop();
	}
    }
    
    /* Preload the next photo */
    preload_photo(next_location);
}

function show_current_photo() {

    /*
     * If the current photo is not completely loaded don't display it.
     */
    if (!images[current_location] || !images[current_location].complete) {
	preload_photo(current_location);
	return 0;
    }
    
    return 1;
}

function preload_photo(index) {

    /* Load the next picture */
    if (pics_loaded < photo_count) {

	/* not all the pics are loaded.  Is the next one loaded? */
	if (!images[index]) {
	    images[index] = new Image;
	    images[index].src = photo_urls[index];
	    pics_loaded++;
	}
    } 
}

</Script>

<?php
}

function slideshow_controlPanel() {
	global $defaultLoop, $defaultPause, $defaultFull, $defaultDir,
			$slide_index, $slide_pause, $slide_loop, $slide_full,
			$slide_dir, $borderColor, $borderwidth, $loop,
			$bgcolor, $title, $gallery, $numPhotos, $numDisplayed,
			$index, $photo_count, $photo, $image, $thumbImage,
			$photoURL, $caption, $imageDir, $pixelImage;
?>
<form name="TopForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="modnavboxmid">
  <tr>
    <td colspan="3"><?php echo $pixelImage ?></td>
  </tr>
  <tr>
    <td height="25" width="1"><?php echo $pixelImage ?></td>
    <td width="5000" align="left" valign="middle">
    <span class=admin>
    &nbsp;<a class="admin" href="#" onClick='stop(); return false;'>[<?php echo _("stop") ?>]</a>
    <a class="admin" href="#" onClick='play(); return false;'>[<?php echo _("play") ?>]</a>
<?php
if ($slide_dir == 1) {
    echo "<a class=\"admin\" href=\"" . makeSlideLowUrl($slide_index, $slide_loop, $slide_pause, $slide_full, -1) 
	. "\">[". _("reverse direction") ."]</a>";
} else {
    echo "<a class=\"admin\" href=\"" . makeSlideLowUrl($slide_index, $slide_loop, $slide_pause, $slide_full, 1)
        . "\">[". _("forward direction") ."]</a>";
}
?>

<?php
if ($gallery->user->canViewFullImages($gallery->album)) {
    if ($slide_full) {
	    echo "<a class=\"admin\" href=\"" . makeSlideLowUrl($slide_index, $slide_loop, $slide_pause, 0, $slide_dir) 
	   . "\">[". _("normal size") ."]</a>";
    } else {
		echo "<a class=\"admin\" href=\"" . makeSlideLowUrl($slide_index, $slide_loop, $slide_pause, 1, $slide_dir)
	            . "\">[". _("full size") ."]</a>";
    }
}
?>
&nbsp;&nbsp;||
    &nbsp;<?php echo _('Delay:') . '&nbsp;' ?>
<?php echo 
drawSelect("time", array(1 => "1 ". _("second"),
		       	2 => "2 ". _("seconds"),
		       	3 => "3 ". _("seconds"),
		       	4 => "4 ". _("seconds"),
		       	5 => "5 ". _("seconds"),
		       	10 => "10 ". _("seconds"),
		       	15 => "15 ". _("seconds"),
		       	30 => "30 ". _("seconds"),
		       	45 => "45 ". _("seconds"),
		       	60 => "60 ". _("seconds")),
	       	$slide_pause, // default value
	       	1, // select size
	       	array('onchange' => 'reset_timer()', 'style' => 'font-size:10px;' ));
?>
    <?php if ($loop) { ?>
    &nbsp;<?php echo _('Loop') ?>:&nbsp;<input type="checkbox" name="loopCheck" <?php echo ($slide_loop) ? "checked" : "" ?> onclick='toggleLoop();'>
    <?php } ?>
    </span>
    </td>
    <td width="1"><?php echo $pixelImage ?></td>
  </tr>
  <tr>
    <td colspan="3"><?php echo $pixelImage ?></td>
  </tr>
</table>
</form>
<?php
}

function slideshow_image() {
	global $defaultLoop, $defaultPause, $defaultFull, $defaultDir,
			$slide_index, $slide_pause, $slide_loop, $slide_full,
			$slide_dir, $borderColor, $borderwidth, $loop,
			$bgcolor, $title, $gallery, $numPhotos, $numDisplayed,
			$index, $photo_count, $photo, $image, $thumbImage,
			$photoURL, $caption, $imageDir, $pixelImage;
?>
<div align="center">

<?php
if ($photo_count > 0) {
?>

<table width=1% border=0 cellspacing=0 cellpadding=0>
  <tr bgcolor="<?php echo $borderColor ?>">
    <td colspan=3 height=<?php echo $borderwidth ?>><?php echo $pixelImage ?></td>
  </tr>
  <tr>
    <td bgcolor="<?php echo $borderColor ?>" width=<?php echo $borderwidth ?>><?php echo $pixelImage ?></td>
    <script language="JavaScript">
    document.write("<td><img border=0 src="+photo_urls[<?php echo $slide_index ?>]+" name=slide></td>");
    </script>
    <td bgcolor="<?php echo $borderColor ?>" width=<?php echo $borderwidth ?>><?php echo $pixelImage ?></td>
  </tr>
  <tr bgcolor="<?php echo $borderColor ?>">
    <td colspan=3 height=<?php echo $borderwidth ?>><?php echo $pixelImage ?></td>
  </tr>
</table>
<br>

<script language="Javascript">
/* show the caption either in a nice div or an ugly form textarea */
document.write("<div class='modcaption'>" + "[" + current_location + " <?php echo _("of") ?> " + photo_count + "] " + photo_captions[<?php echo $slide_index ?>] + "</div>");

/* Start the show. */
play();

</script>

<?php
} else {
?>

<br><b><?php echo _("This album has no photos to show in a slide show.") ?></b>
<br><br>
<span class="admin">
<a href="<?php echo makeGalleryUrl("view_album.php",
array("set_albumName" => $gallery->session->albumName)) ?>">[<?php echo _("back to album") ?>]</a>
</span>

<?php
}
?> 

</div>

<?php } ?>