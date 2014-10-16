<HTML>
  <HEAD>
	 <TITLE>NTNUI Telemark</TITLE>
	 <LINK REL="stylesheet" HREF="../style.css" TYPE="text/css">
  </HEAD>
  <BODY>
   <form method="POST" action="index.php">
     <table border=0 cellpadding=0 bordercolor=#524545 cellspacing=0 width=100%>
       <tr>
         <td width="20%"><p><b>Navn:</b></td>
	  	<td  colspan="2" width="80%"><input type="text" name="navn" size="20"></td>
       </tr><tr>
	 <td><b>E-post:</b></td>
		<td colspan="2"><input type="text" name="epost" size="20"></td>
       </tr><tr>
	 <td><b>Annet:</b></td>
	  	<td colspan="2"><input type="text" name="annet" size="20"></td>
       </tr><tr>
	 <td><b>Passord*:</b></td>
	  	<td width="20%"><input type="password" name="passord" size="20"></td>
	  	<td width="60%" align="center"><input type="submit" value="Send" name="B1"></td>
       </tr><tr>
  	 <td colspan="3"><hr></td>
       </tr>
     </table>
   </form>
  
  <table border=0 cellpadding=0 bordercolor=#524545 cellspacing=0 width=100%>
  <tr>
    <td width><b>Nr</b></td>
    <td width><b>Navn</b></td>
    <td width><b>E-post</b></td>
    <td width><b>Annet</b></td>
  </tr>
<?php
include('clsencrypt.php');
class person{
	var $navn;
	var $epost;
	var $annet;
	var $passord;
	function Person($navn, $epost, $annet, $passord){
		$this->navn = $navn;
		if (strrchr($epost, "@") != false &&
		    strrchr($epost, ".") != false) $this->epost = $epost;
		else $this->epost = "";
		$this->annet = $annet;
		$this->passord = $passord;
	}
	
	function skrivUt(){
		echo "<td>", $this->navn, "</td><td>", $this->epost, "</td><td>", $this->annet, "</td>";
	}
	
	function hentLagringsData(){
		return "$this->navn###$this->epost###$this->annet###$this->passord";
	}
	
	function hentNavn(){
		return $this->navn;
	}
	
	function sjekk($navn, $passord){
		$enc = new Encryption;
		$cryptKey = "this is the new shit in php";
				
		if (strcmp($this->passord, $enc->encrypt($cryptkey, $passord))==0 && strcmp($this->navn, $navn)==0){
			return true;
		}
		else {
			return false;
		}
	}
}

class Pameldt{
	var $personer;
	var $filnavn;
	
	function Pameldt(){
		#leser fra fila
		$this->filnavn = "data.txt";
		$fp = fopen($this->filnavn, "r");
		$inData = fread($fp, filesize($this->filnavn));
		fclose($fp);
		$pers = split(".:::.", $inData);
		
		#legger til alle personene
		for($i = 0; $i < count($pers); $i++){
			$e = split("###", $pers[$i]);
			$nyPerson = new Person($e[0], $e[1], $e[2], $e[3]);
			$this->leggTil($nyPerson);
		}
		$this->sorter();
	}
	
	function lagre(){
		for($i = 0; $i < count($this->personer); $i++){
			$pers[] = $this->personer[$i]->hentLagringsData();
		}
		$lagring = implode(".:::.", $pers);
		$fp = fopen($this->filnavn, "w+");
		fwrite($fp, $lagring, strlen($lagring)+10);
		fclose($fp);
	}
	
	function skrivUt(){
		for($i = 0; $i < count($this->personer); $i++){
			echo "    <tr><td>", $i+1, "</td>";
			$this->personer[$i]->skrivUt();
			echo "</tr>\n";
		}
	}
	
	function leggTil($person){
		$this->personer[] = $person;
		$this->sorter();
		$this->lagre();
	}
	
	function sorter(){
		sort($this->personer);
	}
	
	function slett($index){
		$lengde = count($this->personer);
		array_splice($this->personer, $index, 1);
		$this->lagre();
	}
	
	function finn($navn, $passord){
		$funnet = false;
		$end = count($this->personer);
		$i = 0;
		while (!$funnet && $i < $end){
			$funnet = $this->personer[$i]->sjekk($navn, $passord);
			$i++;
		}
		if ($funnet){
			return $i-1;
		}
		else return -1;
	}
}
$medlemmer = new Pameldt();

#kryptering
$enc = new Encryption;
$cryptKey = "this is the new shit in php";

$nyPerson = new Person($navn, $epost, $annet, $enc->encrypt($cryptkey, $passord));

#legger til en person
$treff = $medlemmer->finn($navn, $passord);
if (strrchr($epost, "@") == false ||
    strrchr($epost, ".") == false){
    	$epost = "";
}

if($navn != "" &&
   $epost != "" &&
   $passord != "" &&
   $treff == -1){
	$medlemmer->leggTil($nyPerson);
}
else{
	if ($treff > -1) {
		echo "<p><font color=\"red\">Personen finnes fra før og blir dermed <b>slettet</b>.<br>\n\"", $nyPerson->hentNavn(), "\" er slettet fra listen men kan meldes på igjen senere.</font></p>\n";
		$medlemmer->slett($treff);
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
$medlemmer->skrivUt();

?>
  </table>

<p><hr><small>Dette er en påmeldingsliste for NTNUI Telemark/Alpint. Du kan slette å legge deg til så mange ganger du vil innen fristen. For å slette seg selv må du taste samme navn og passord som ved registrering.</small></p>
<p><small>*Du må ikke glemme passordet hvis du vil ha mulighet til å melde deg av listen seinere. Det er ingen måte å få vite passordet på et seinere tidspunkt.</small></p>
</body>
</html>

