<HTML>
  <HEAD>
	 <TITLE>NTNUI Telemark</TITLE>
	 <LINK REL="stylesheet" HREF="../style.css" TYPE="text/css">
  </HEAD>
  <BODY>
  <p>Tast inn epostadressen du er registrert med.
  Passordet vil umiddelbart bli sendt til deg på epost.</p>
    <form method="POST" action="passord.php">
      <p><b>E-post: </b>
      <input type="text" name="epost" size="20"></p>
      <p><input type="submit" value="Send passord" name="B1"></p>
    </form>
    <p><font color="red">
<?php
include('clsencrypt.php');
include('klasser.php');
include('deklarer.php');

#Laster alle påmeldte
$medlemmer = new Pameldt();

#finner Persjonen med rett epostadresse
$rettPerson = $medlemmer->finnEpost($epost);
if ($rettPerson != null){
	$to = $rettPerson->hentEpost();
	$subject = "[NTNUI Telemark/Alpint] Passord";
	$body = "Dette er en epost fra NTNUI Telemark/Alpint sine nettsider.\n\nDu har glemt ditt passord på påmeldingssiden og sendt ønske om å motta passordet per epost.\n\nDitt passord er: ";

	$ukryptert = $enc->decrypt($key, $rettPerson->hentPassord());

	if (mail($to, $subject, "$body$ukryptert",
		     "From: telemark-web@list.stud.ntnu.no\r\n" .
		     "Reply-To: telemark-web@list.stud.ntnu.no\r\n" .
		     "X-Mailer: PHP/" . phpversion()))
	{
	   echo "Passordet er sendt til <i>", $to, "</i>";
	}
	else
	{
	   echo "Sending av epost feilet... Prøv igjen eller kontakt systemansvarlig!";
	}
}
else if ($epost != "") echo("Fant ikke epostadresse...");
?>
    </font></p>
    <p><a href="index.php">Gå tilbake</a></p>
  </body>
</html>