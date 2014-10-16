<HTML>
  <HEAD>
	 <TITLE>NTNUI Telemark</TITLE>
	 <LINK REL="stylesheet" HREF="../style.css" TYPE="text/css">
  </HEAD>
  <BODY>
<?

	$filename = "vi_registrerer.txt";
	$fp = fopen( $filename,"r");
	$OldData = fread($fp, filesize($filename));
	fclose( $fp );


	$linjer = split(chr(13), $innlegg);
	$j = count($linjer);
	$linjeskift = chr(13);
	$Input = "";
	$utskrift = "";
	for($i = 0; $i<$j; $i++){
		if (strlen($linjer[$i]) > 1){
			$Input = "$Input$linjer[$i]#$linjeskift";
			$utskrift = "$utskrift$linjer[$i]<br>";
		}
	}


#This Line adds the 'GuestBook=' part to the front of the data that is stored in the text file.  This is important because without this the Flash movie would not be able to assign the variable 'GuestBook' to the value that is located in this text file
	$filFil = "$Input$OldData";

#Opens and writes the file.

	$fp = fopen( $filename,"w+");
	fwrite($fp, $filFil, filesize($filename)+3000);
	fclose( $fp );
		print chr(13);
		print "<p>Innlegget er lagt til.</p>";
		print "<p><b>Og vi registrerer:</b><br>";
		print $utskrift;
		print "</p>";
?>
<a href="index.php">fullført</a>
</BODY>
</HTML>