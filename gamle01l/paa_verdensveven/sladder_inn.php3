<?PHP

if($kommentar){

$sladder = "sladder.log";    //url til loggfil

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

$datoen = "<font class=\"dato\"> " . $dag . " " . $dagdato . $maned . $aar ;

if($navn){

	if($email != "email"){
		if($email != ""){
			$navnet = " av <a href=\"mailto:" . $email . "\" class=\"navn\">" . $navn . "</a><br></font>\n";
   	}else{
			$navnet = " av " . $navn . "<br></font>\n";
		}		
	}else{
	$navnet = " av " . $navn . "<br></font>\n";
	}
}else{
$navnet = "<br></font>\n";
}


$kommentar_inn = stripslashes(nl2br($kommentar));

$melding = $datoen . $navnet . $kommentar_inn . "<br><br><br>\n\n\n";


$fp = fopen($sladder, "r"); //opner loggfila for lesing
$gammelt = fread( $fp, filesize( $sladder ) );
fclose ($fp);

$inn = $melding . $gammelt;

$filep = fopen($sladder, "w"); //opner loggfila for lesing
fwrite ($filep, $inn); //skriv til loggfila
fclose ($filep);

}

?>


<SCRIPT language="JavaScript">
<!-- 
this.location.href = "spalten.php3";
// -->
</SCRIPT>


