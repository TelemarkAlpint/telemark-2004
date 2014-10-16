<HTML>
  <HEAD>
	 <TITLE>NTNUI Telemark</TITLE>
	 <LINK REL="stylesheet" HREF="../style.css" TYPE="text/css">
  </HEAD>
  <BODY>

  <h1>Påmelding til Oppdalstur 8.-10. april 2005 (frist for påmelding er utsatt til fre 25.febr)</h1>
  <p><font color=red>Da er påmeldingslisten oppdatert igjen! Takket være backup på 
NTNU sin server måtte ikke alle melde seg på på nytt. Frist er nå ut fredag for påmelding!
</font></p>
   <form method="POST" action="index.php">
     <table border=0 cellpadding=0 bordercolor=#524545 cellspacing=0 width=100%>
       <tr>
         <td width="30%"><p><b>Navn:</b></td>
	  	<td  colspan="2" width="80%"><input type="text" name="navn" size="30"></td>
       </tr><tr>
	 <td><b>E-post:</b></td>
		<td colspan="2"><input type="text" name="epost" size="30"></td>
       </tr><tr>
	 <td><b>Sesongkort & bil:</b></td>
	  	<td><input type="text" name="annet" size="30" value="har ikke sesongkort & har ikke bil"></td>
	  	<td><small><b>(</b>har/har ikke sesongkort <br><b>&</b> har/har ikke bil<b>)</b><small></td>
       </tr><tr>
	 <td><b>Passord:</b></td>
	  	<td width="20%"><input type="password" name="passord" size="30"></td>
	  	<td width="50%" align="center">
	  		<input type="submit" value="Meld på" name="add">
	  		<input type="submit" value="Meld av" name="add">
	  	</td>
       </tr><tr>
  	 <td colspan="3"><small><a href="passord.php">Glemt passord?</a> (for evt avmelding seinere)</small><hr></td>
       </tr>
     </table>
   </form>

  <table border=0 cellpadding=1 cellspacing=1>
  <tr>
    <td><b>Nr</b></td>
    <td><b>Navn</b></td>
    <td><b>E-post</b></td>
    <td><b>Sesongkort & bil</b></td>
  </tr>
<?php
include('clsencrypt.php');
include('klasser.php');
include('deklarer.php');
$medlemmer = new Pameldt();

#kryptering
#$enc = new Encryption;
$nyPerson = new Person($navn, $epost, $annet, $enc->encrypt($key, $passord));

#legger til ny person fra inndata
$treff = $medlemmer->finnesPerson($navn, $epost);
if ($add == "Meld på") {
	if (strrchr($epost, "@") == false ||
	    strrchr($epost, ".") == false){
	    	$epost = "";
	}
	if($navn != "" &&
	   $epost != "" &&
	   $passord != "" &&
	   !$treff){
		$medlemmer->leggTil($nyPerson);
	}
	else{
		if ($treff) {
			echo "<p><font color=\"red\">Personen er allerede registrert</font></p>\n";
		}
		else {
			if($navn != "" ||
			   $epost != "" ||
			   $passord != ""){
			print "<p><font color=red>Du har ikke tastet inn alle verdier som trengs for registrering.</font></p>\n<p><font color=red>Vennligst fyll inn:\n<ul>\n";
			if ($navn == "") echo "  <li>navn</li>\n";
			if ($epost == "") echo "  <li>epost</li>\n";
			if ($passord == "") echo "  <li>passord</li>\n";
			echo "<ul>\n</font></p>\n";
			}
		}
	}
}
$index = $medlemmer->finn($navn, $passord);
if ($add == "Meld av"){
	if($navn != "" &&
	   $passord != "" &&
	   $index > -1){
		$medlemmer->slett($index);
		echo "<p><font color=red><i>", $navn, "</i> er slettet fra listen</font></p>";
	}
	else {
		echo "<p><font color=red><i>", $navn, "</i> er ikke slettet. <br>Navn eller passord er feil!</font></p>";
	}

}




$medlemmer->skrivUt();

?>
  </table>

<p><hr><small>Dette er en påmeldingsliste for NTNUI Telemark/Alpint. Du kan melde deg av og på så mange ganger du vil innen fristen. For å melde deg av må du taste rett navn og passord (store og små bokstaver må være rett).</small></p>
</body>
</html>

