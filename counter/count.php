<?

$file  =fopen("count.txt", "r");
$count = fread($file, 1024);
fclose($file);
$count = explode("=", $count);
$count[1] = $count[1] + 1;
$file = fopen("count.txt", "w+");
$Today = (date ("l dS of F Y ( h:i:s A )",time()));
fwrite($file, "count=".$count[1]);
fclose($file);
print "count=".$count[1];

?>