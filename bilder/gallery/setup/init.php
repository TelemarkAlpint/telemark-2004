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
 * $Id: init.php,v 1.40 2004/03/21 16:51:34 jenst Exp $
 */
?>
<?php
/*
 * Turn down the error reporting to just critical errors for now.
 * In v1.2, we know that we'll have lots and lots of warnings if
 * error reporting is turned all the way up.  We'll fix this in v2.0
 */


if (isset($gallery->app->devMode) && $gallery->app->devMode == "yes") {
        error_reporting(E_ALL);
} else {
        error_reporting(E_ALL & ~E_NOTICE);
}

/* emulate part of register_globals = on */
/*
 * Prevent hackers from overwriting one HTTP_ global using another one.  For example,
 * appending "?HTTP_POST_VARS[sensitive_Var]=xxx" to the url would cause extract
 * to overwrite HTTP_POST_VARS when it extracts HTTP_GET_VARS
 */
$scrubList = array('HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_COOKIE_VARS', 'HTTP_POST_FILES');
foreach ($scrubList as $outer) {
    foreach ($scrubList as $inner) {
	unset(${$outer}[$inner]);
    }
}
extract($HTTP_GET_VARS);
extract($HTTP_POST_VARS);
extract($HTTP_COOKIE_VARS);

/* load necessary functions */
if (stristr (__FILE__, '/var/lib/gallery/setup')) {
        /* Gallery runs on a Debian System */
	require ('/usr/share/gallery/util.php');
} else {
	require (dirname(dirname(__FILE__)) . '/util.php');
}

/* define the constants */
	get_GalleryPathes();

if (getOS() == OS_WINDOWS) {
	require(GALLERY_BASE . '/platform/fs_win32.php');
} else {
	require(GALLERY_BASE . '/platform/fs_unix.php');
}
      
	include (GALLERY_BASE . '/config.php');
	require (GALLERY_BASE . '/Version.php');

/* Set Language etc. */
	initLanguage();

if (getOS() == OS_WINDOWS && fs_file_exists("SECURE")) {
		echo _("Gallery is in secure mode and cannot be configured. If you want to configure it, you must run the <b>configure.bat</b> script in the gallery directory then reload this page.");
		exit;
}



/* We do this to get the config stylesheet */
	$GALLERY_OK=false;

/* 
 * Turn off magic quotes runtime as they interfere with saving and
 * restoring data from our file-based database files
 */
set_magic_quotes_runtime(0);

/*
 * Init prepend file for setup directory.
 */

$tmp = $HTTP_SERVER_VARS["PHP_SELF"];
if (!$tmp) {
	$tmp = $HTTP_ENV_VARS["PHP_SELF"];
}
if (!$tmp) {
	$tmp = getenv("SCRIPT_NAME");
}

$GALLERY_URL = dirname(dirname($tmp));
// Make sure GALLERY_URL doesn't end in a slash
$GALLERY_URL = ereg_replace("\/$", "", $GALLERY_URL);

$MIN_PHP_MAJOR_VERSION = 4;

if (!empty($init_mod_rewrite)) {
	$GALLERY_REWRITE_OK = 1;
	if (strstr($init_mod_rewrite, "ampersandbroken")) {
		$GALLERY_REWRITE_SEPARATOR = "\&";
	} else {
		$GALLERY_REWRITE_SEPARATOR = "&";
	}
} else {
	$GALLERY_REWRITE_OK = 0;
}

?>
