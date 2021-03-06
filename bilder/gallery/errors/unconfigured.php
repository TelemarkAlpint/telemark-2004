<?php
// $Id: unconfigured.php,v 1.20 2004/02/24 17:28:49 mindless Exp $
?>
<?php 
	require(dirname(__FILE__) . "/configure_instructions.php") ;
	if (! defined("GALLERY_URL")) define ("GALLERY_URL","");
?>
<html>
<head>
  <title><?php echo _("Gallery Configuration Error") ?></title>
  <?php echo getStyleSheetLink() ?>
</head>
<body dir="<?php echo $gallery->direction ?>">

<center>
<p class="header"><?php echo _("Gallery has not been configured!") ?></p>

<p class="sitedesc">
<?php 
	echo _("Gallery must be configured before you can use it. First, you must put Gallery in Configuration Mode. Here's how:");
	echo configure("configure"); 
?>
</p>

<p>
<?php echo sprintf(_("Then start the %sConfiguration Wizard%s."), 
		'<a href="'. GALLERY_URL . 'setup/index.php">', '</a>'); 
	print "  ";
	include(dirname(__FILE__) . "/configure_help.php"); ?>
</p>
</center>
</body>
</html>
