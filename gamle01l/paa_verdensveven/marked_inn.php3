<?PHP

if($kommentar){

$marked = "marked.log";    //url til loggfil

$day = date("w");
$dag = "";
$maned = "";

if($day == 0) { $dag = "Søndag"; } 
if($day == 1) { $dag = "Mandag"; }
if($day == 2) { $dag = "Tirsdag"; }
if($day == 3) { $dag = "Onsdag"; }
if($day == 4) { $dag = "Torsdag"; }
if($day == 5) { $dag = "Fredag"; }
if($day == 6) { $dag = "Lørdag"; }

$month = date("m");

if($month == 1) { $maned = "januar"; }
if($month == 2) { $maned = "februar"; }
if($month == 3) { $maned = "mars"; }
if($month == 4) { $maned = "april"; }
if($month == 5) { $maned = "mai"; }
if($month == 6) { $maned = "juni"; }
if($month == 7) { $maned = "juli"; } 
if($month == 8) { $maned = "august"; }
if($month == 9) { $maned = "september"; }
if($month == 10) { $maned = "oktober"; }
if($month == 11) { $maned = "november"; }
if($month == 12) { $maned = "desember"; }

$dagdato = date("d."); 
$aar = date(" Y, H:i");

$datoen = "<font class=\"mini\">Dato: " . $dag . " " . $dagdato . 
$maned . $aar . "</font><br><hr>\n</td></tr>";

if($selg_kjop == "kjop"){
$forst = "<TR><TD><font class=\"forst\">Kjøpes: </font>";
}else{
$forst = "<TR><TD><font class=\"forst\">Selges: </font>";
}

if ($overskrift){
$andre = "<font class=\"markedover\">" . $overskrift . "</font><br><br> \n";
}else{
$andre = "\n";
}



if($navn){

	if($email != "email"){
		if($email != ""){
			$navnet = " <br><br><font 
class=\"mini\">Innlagt av: </font> <a href=\"mailto:" . $email . 
"\" class=\"navn\">" . $navn . "</a><br></font>\n";
   	}else{
			$navnet = " <br><br><font 
class=\"mini\">Innlagt av: </font> " . $navn . "<br></font>\n";
		}		
	}else{
	$navnet = " <br><br><font class=\"mini\">Innlagt av: 
</font> " . $navn . "<br></font>\n";
	}
}else{
$navnet = "<br><br>\n";
}

$kommentar_inn = stripslashes(nl2br($kommentar));

$melding = $forst . $andre . $kommentar_inn . $navnet . $datoen . 
"\n\n\n";


$fp = fopen($marked, "r"); //opner loggfila for lesing
$gammelt = fread( $fp, filesize( $marked ) );
fclose ($fp);

$inn = $melding . $gammelt;

$filep = fopen($marked, "w"); //opner loggfila for lesing
fwrite ($filep, $inn); //skriv til loggfila
fclose ($filep);

}

?>


<SCRIPT language="JavaScript">
<!-- 
this.location.href = "marked.php3";
// -->
</SCRIPT>


