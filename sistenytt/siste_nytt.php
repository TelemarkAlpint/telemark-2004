<HTML>
  <HEAD>
	 <TITLE>NTNUI Telemark</TITLE>
	 <LINK REL="stylesheet" HREF="../style.css" TYPE="text/css">
  </HEAD>
  <BODY>
<?
if ($navn != ""){
	$filename = "siste_nytt.txt";
	$fp = fopen( $filename,"r");
	$OldData = fread($fp, filesize($filename));
	fclose( $fp );

	$dato = date('d M Y');
	$linjeskift = chr(13);
	$Input = "<p>*<span class=sistenyttdato>$dato - </span><span class = sistenyttinnlegg>$innlegg</span><p><P align=right class=sistenyttnavn>-$navn-</p>";

#This Line adds the 'GuestBook=' part to the front of the data that is stored in the text file.  This is important because without this the Flash movie would not be able to assign the variable 'GuestBook' to the value that is located in this text file

	$New = "$Input#$linjeskift$OldData";

#Opens and writes the file.

	$fp = fopen( $filename,"w+");
	fwrite($fp, $New, filesize($filename)+3000);
	fclose( $fp );
	print "<p>Innlegget er lagt til.</p>";
	print "<p><b>Innlegg:</b><br>";
	print $Input;
	print "</p>";
}
else print "<p>Fant ingen navn! <br>Innlegget ble ikke lagt til.</p>";
?>
<a href="index.php">fullført</a>
</body>
</html>
