<html>
<head>
	 <LINK REL="stylesheet" HREF="../style.css" TYPE="text/css">
</head>
<body>
<p><?
$file  =fopen("count.txt", "r");
$count = fread($file, 1024);
fclose($file);
$count = explode("=", $count);
$count[1] = $count[1] + 1;
$file = fopen("count.txt", "w+");
$Today = (date ("l dS of F Y ( h:i:s A )",time()));
fwrite($file, "count=".$count[1]);
fclose($file);
print "<font size=1>Besøk siden 12. nov 04:</font><br><b>".$count[1];
?></b></p>
</body>
</html>