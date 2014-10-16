<html>
<HEAD>
	<TITLE>NTNUI Telemark</TITLE>
	<LINK REL="stylesheet" HREF="../style.css" TYPE="text/css">
	<META HTTP-EQUIV=Refresh CONTENT="
<?
if ($passord == "styret" && $bruker == "styret")
	print "0;URL=innlogget.htm";
else
	print "0;URL=feil.htm";
?>
"></HEAD>
<body>
  <p>For å kunne skrive inn kommentarer og nyheter må du logge deg inn!</p>
  <form method="POST" action="login.php">
  	<p>
  	<b>Bruker:</b><br>
  	<input type="text" name="bruker" size="20"></p>
  	<p>
  	<b>Passord:</b><br>
  	<input type="password" name="passord" size="20"></p>
  	<p>
  	<input type="submit" value="Logg inn" name="B1"></p>
  </form>
</body>
</html>