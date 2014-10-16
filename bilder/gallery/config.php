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
 * $Id: gpl.txt,v 1.6 2004/02/03 05:03:38 beckettmw Exp $
 */
?>
<?php
/* 
 * Protect against very old versions of 4.0 (like 4.0RC1) which  
 * don't implicitly create a new stdClass() when you use a variable 
 * like a class. 
 */ 
if (!isset($gallery)) { 
        $gallery = new stdClass(); 
}
if (!isset($gallery->app)) { 
        $gallery->app = new stdClass(); 
}

/* Version  */
$gallery->app->config_version = '80';

/* Features */
$gallery->app->feature["zip"] = 1;
$gallery->app->feature["rewrite"] = 1;
$gallery->app->feature["mirror"] = 1;

/* Constants */
$gallery->app->galleryTitle = "ntnui - Telemark/Alpint";
$gallery->app->albumDir = "/var/www/bilder/albums";
$gallery->app->tmpDir = "/var/www/bilder/gallery/temp/";
$gallery->app->photoAlbumURL = "/bilder/gallery";
$gallery->app->albumDirURL = "/bilder/albums";
// optional <i>watermarkDir</i> missing
$gallery->app->movieThumbnail = "/var/www/bilder/gallery/images/movie.thumb.jpg";
$gallery->app->mirrorSites = "www.ntnui.no/telemark/bilder.htm";
$gallery->app->embedded_inside_type = "";
$gallery->app->geeklog_dir = "/path/to/geeklog/public_html";
$gallery->app->graphics = "ImageMagick";
$gallery->app->pnmDir = "/var/www/bilder/gallery/netpbn";
$gallery->app->pnmtojpeg = "ppmtojpeg";
$gallery->app->ImPath = "/usr/bin";
$gallery->app->autorotate = "no";
$gallery->app->jpegImageQuality = "90";
$gallery->app->showAlbumTree = "yes";
$gallery->app->highlight_size = "150";
$gallery->app->showOwners = "yes";
$gallery->app->albumsPerPage = "100";
$gallery->app->showSearchEngine = "yes";
$gallery->app->skinname = "bblue";
$gallery->app->gallery_thumb_frame_style = "simple_book";
$gallery->app->zipinfo = "/usr/bin/zipinfo";
$gallery->app->unzip = "/usr/bin/unzip";
$gallery->app->use_exif = "/store/bin/jhead";
$gallery->app->cacheExif = "no";
$gallery->app->use_jpegtran = "/usr/bin/jpegtran";
$gallery->app->default_language = "en_US";
$gallery->app->ML_mode = "3";
$gallery->app->available_lang[] = "en_US";
$gallery->app->available_lang[] = "no_NO";
$gallery->app->available_lang[] = "zh_TW";
$gallery->app->available_lang[] = "vi_VN";
$gallery->app->available_lang[] = "de_DE";
$gallery->app->available_lang[] = "pt_PT";
$gallery->app->available_lang[] = "pt_BR";
$gallery->app->available_lang[] = "it_IT";
$gallery->app->available_lang[] = "es_ES";
$gallery->app->available_lang[] = "sv_SE";
$gallery->app->available_lang[] = "fi_FI";
$gallery->app->available_lang[] = "ru_RU.koi8r";
$gallery->app->available_lang[] = "af_ZA";
$gallery->app->available_lang[] = "ja_JP";
$gallery->app->available_lang[] = "fr_FR";
$gallery->app->available_lang[] = "bg_BG";
$gallery->app->available_lang[] = "lt_LT";
$gallery->app->available_lang[] = "is_IS";
$gallery->app->available_lang[] = "ko_KR";
$gallery->app->available_lang[] = "da_DK";
$gallery->app->available_lang[] = "zh_TW.utf8";
$gallery->app->available_lang[] = "he_IL.utf8";
$gallery->app->available_lang[] = "nl_NL";
$gallery->app->available_lang[] = "cs_CZ.iso-8859-2";
$gallery->app->available_lang[] = "ca_ES";
$gallery->app->available_lang[] = "hu_HU";
$gallery->app->available_lang[] = "zh_CN";
$gallery->app->available_lang[] = "tr_TR";
$gallery->app->available_lang[] = "uk_UA";
$gallery->app->available_lang[] = "sl_SI";
$gallery->app->available_lang[] = "en_GB";
$gallery->app->show_flags = "no";
$gallery->app->dateString = "%x";
$gallery->app->dateTimeString = "%c";
$gallery->app->emailOn = "yes";
$gallery->app->adminEmail = "telemark-galleri@stud.ntnu.no";
$gallery->app->senderEmail = "telemark-galleri@list.stud.ntnu.no";
$gallery->app->emailSubjPrefix = "[Telemark-galleri]";
// optional <i>emailGreeting</i> missing
$gallery->app->selfReg = "yes";
$gallery->app->selfRegCreate = "yes";
$gallery->app->multiple_create = "yes";
$gallery->app->email_notification[] = "email";
$gallery->app->email_notification[] = "logfile";
$gallery->app->gallery_slideshow_type = "ordered";
$gallery->app->gallery_slideshow_length = "0";
$gallery->app->gallery_slideshow_loop = "yes";
$gallery->app->slideshowMode = "high";
$gallery->app->comments_enabled = "yes";
$gallery->app->comments_indication = "both";
$gallery->app->comments_indication_verbose = "no";
$gallery->app->comments_anonymous = "no";
$gallery->app->comments_display_name = "!!FULLNAME!! (!!USERNAME!!)";
$gallery->app->timeLimit = "30";
$gallery->app->debug = "no";
$gallery->app->use_flock = "yes";
$gallery->app->expectedExecStatus = "0";
$gallery->app->sessionVar = "gallery_session";
$gallery->app->userDir = "/var/www/bilder/albums/.users";
$gallery->app->devMode = "no";
$gallery->app->adminCommentsEmail = "no";
$gallery->app->adminOtherChangesEmail = "no";
$gallery->app->maximumAlbumDepth = "50";

/* Defaults */
$gallery->app->default["bordercolor"] = "black";
$gallery->app->default["border"] = "1";
$gallery->app->default["font"] = "arial";
$gallery->app->default["cols"] = "4";
$gallery->app->default["rows"] = "7";
$gallery->app->default["thumb_size"] = "150";
$gallery->app->default["resize_size"] = "800";
$gallery->app->default["resize_file_size"] = "0";
$gallery->app->default["max_size"] = "800";
$gallery->app->default["max_file_size"] = "0";
$gallery->app->default["useOriginalFileNames"] = "yes";
$gallery->app->default["add_to_beginning"] = "no";
$gallery->app->default["fit_to_window"] = "yes";
$gallery->app->default["use_fullOnly"] = "no";
$gallery->app->default["print_photos"]["shutterfly"]["donation"] = "no";
$gallery->app->default["returnto"] = "yes";
$gallery->app->default["display_clicks"] = "yes";
$gallery->app->default["extra_fields"] = "Beskrivelse";
$gallery->app->default["showDimensions"] = "no";
$gallery->app->default["item_owner_modify"] = "yes";
$gallery->app->default["item_owner_delete"] = "yes";
$gallery->app->default["item_owner_display"] = "yes";
$gallery->app->default["voter_class"] = "Logged in";
$gallery->app->default["poll_type"] = "critique";
$gallery->app->default["poll_scale"] = "5";
$gallery->app->default["poll_hint"] = "Stem på dette bildet";
$gallery->app->default["poll_show_results"] = "yes";
$gallery->app->default["poll_num_results"] = "3";
$gallery->app->default["poll_orientation"] = "vertical";
$gallery->app->default["poll_nv_pairs"][0]["name"] = "Perfekt";
$gallery->app->default["poll_nv_pairs"][0]["value"] = "5";
$gallery->app->default["poll_nv_pairs"][1]["name"] = "Veldig bra";
$gallery->app->default["poll_nv_pairs"][1]["value"] = "4";
$gallery->app->default["poll_nv_pairs"][2]["name"] = "Bra";
$gallery->app->default["poll_nv_pairs"][2]["value"] = "3";
$gallery->app->default["poll_nv_pairs"][3]["name"] = "Middels";
$gallery->app->default["poll_nv_pairs"][3]["value"] = "2";
$gallery->app->default["poll_nv_pairs"][4]["name"] = "Dårlig";
$gallery->app->default["poll_nv_pairs"][4]["value"] = "1";
$gallery->app->default["poll_nv_pairs"][5]["name"] = "";
$gallery->app->default["poll_nv_pairs"][5]["value"] = "";
$gallery->app->default["poll_nv_pairs"][6]["name"] = "";
$gallery->app->default["poll_nv_pairs"][6]["value"] = "";
$gallery->app->default["poll_nv_pairs"][7]["name"] = "";
$gallery->app->default["poll_nv_pairs"][7]["value"] = "";
$gallery->app->default["poll_nv_pairs"][8]["name"] = "";
$gallery->app->default["poll_nv_pairs"][8]["value"] = "";
$gallery->app->default["slideshow_type"] = "ordered";
$gallery->app->default["slideshow_recursive"] = "no";
$gallery->app->default["slideshow_loop"] = "yes";
$gallery->app->default["slideshow_length"] = "0";
$gallery->app->default["album_frame"] = "shadows";
$gallery->app->default["thumb_frame"] = "solid";
$gallery->app->default["image_frame"] = "solid";
?>
