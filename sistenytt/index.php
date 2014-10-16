<!DOCTYPE HTML PUBLIC "-//SoftQuad Software//DTD HoTMetaL PRO 6.0::19990601::extensions to HTML 4.0//EN" "hmpro6.dtd">
<HTML>
  <HEAD>
	 <TITLE>NTNUI Telemark</TITLE>
	 <LINK REL="stylesheet" HREF="../style.css" TYPE="text/css">
  </HEAD>
  <BODY>
	 <TABLE BORDER="0" CELLPADDING="0" CELLSPACING="5" WIDTH="100%">
		<TR>
			<TD WIDTH="50%" VALIGN="top" height="100">
					<p><a href="innlogging.htm">Logg inn</a></p>
					<p align = "center">
					<iframe src="../bilder/dagens/dagens.php" marginwidth="1" marginheight="1" border="0" frameborder="0" scrolling = "no" target="_blank" width="100%" name="dagens" height="150"></iframe>
					<br><a href="ukas.htm"><i>Ukas bilde</i></a></p>
			            <p><B>Og vi registrerer:</B>
<?php
$fileName = "vi_registrerer.txt";
$fp = fopen($fileName, "r");
$inData = fread($fp, filesize($fileName));
fclose($fp);
$linjer = split("#", $inData);
$j = count($linjer)-1;
for($i = 0; $i<$j; $i++){
	print "<br> $linjer[$i]";
}

print "</p>";
print "<p>&nbsp;</p>";
print "</TD>";
print "<TD WIDTH=50% VALIGN=top>";
print "<P><B>Siste nytt:</B></P>";

$fileName = "siste_nytt.txt";
$fp = fopen($fileName, "r");
$inData = fread($fp, filesize($fileName));
fclose($fp);
$linjer = split("#", $inData);
$j = count($linjer)-1;
for($i = 0; $i<$j; $i++){
	print "$linjer[$i]";
}
?>

			</TD>
		</TR>
	</TABLE>
</BODY>
</HTML>