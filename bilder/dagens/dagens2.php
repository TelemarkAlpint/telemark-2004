<html>
	<head>
		<title>Tilbakemeldinger</title>
			<LINK REL=STYLESHEET TYPE="text/css">
			<STYLE TYPE="text/css">
				table {
					border-collapse: collapse;
					}
		    </STYLE>

	</head>
<body>
<h3>Tilbakemelding rotasjonslegemer</h3>
<table border=1 cellpadding=0 cellspacing=0 bordercolor=#111111 width=100%>
  <tr>
    <th width="3%">Nr</td>
    <th width="10%">Brukernavn</td>
    <th width="5%">Spm 1</td>
    <th width="5%">Spm 2</td>
    <th width="5%">Spm 3</td>
    <th width="5%">Spm 4</td>
    <th width="5%">Spm 5</td>
    <th width="5%">Spm 6</td>
    <th width="55%">Kommentarer</td>
  </tr>
<?php
$fileName = "rotasjon.txt";
$fp = fopen($fileName, "r");
$data = fread($fp, 10000);
fclose($fp);
$DataArray = split(".:::.", $data);
$j = count($DataArray)-1;
for($i = 0; $i<$j; $i++){
	print "<tr><td>";
	print $i +1;
	print "</td>";
	print $DataArray[$i];
	print "</tr>";
}
print "</table>";

?>
</body>


</html>