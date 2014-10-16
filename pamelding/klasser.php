<?php
include('deklarer.php');
class person{
	var $navn;
	var $epost;
	var $annet;
	var $passord;
	function Person($navn, $epost, $annet, $passord){
		$navn[0] = strtoupper($navn[0]);
		$this->navn = $navn;
		if (strrchr($epost, "@") != false &&
		    strrchr($epost, ".") != false) $this->epost = $epost;
		else $this->epost = "";
		$this->annet = $annet;
		$this->passord = $passord;
	}

	function skrivUt(){
		echo "<td> ", $this->navn, " </td><td> ", $this->epost, " </td><td> ", $this->annet, " </td>";
	}

	function hentLagringsData(){
		return "$this->navn###$this->epost###$this->annet###$this->passord";
	}

	function hentNavn(){
		return $this->navn;
	}

	function hentEpost(){
		return $this->epost;
	}

	function hentPassord(){
		return $this->passord;
	}

	function sjekkEpost($epost){
		if (strcmp($this->epost, $epost)==0){
			return true;
		}
		else {
			return false;
		}
	}

	function likPerson($navn, $epost){
		if (strcmp($this->epost, $epost)==0 &&
		    strcmp($this->navn, $navn)==0){
			return true;
		}
		else {
			return false;
		}

	}


	function sjekk($navn, $passord){
		global $key;
		global $enc;
		if (strcmp($this->passord, $enc->encrypt($key, $passord))==0 && strcmp($this->navn, $navn)==0){
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

	function finnEpost($epost){
		$end = count($this->personer);
		$i = 0;
		while ($i < $end){
			if ($this->personer[$i]->sjekkEpost($epost)){
				return $this->personer[$i];
			}
			$i++;
		}
		return null;
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

	function finnesPerson($navn, $epost){
		$funnet = false;
		$end = count($this->personer);
		$i = 0;
		while (!$funnet && $i < $end){
			if ($this->personer[$i]->likPerson($navn, $epost)){
				return true;
			}
			$i++;
		}
		return false;
	}
}
?>