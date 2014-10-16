<html>

<head>
	<title>ntnui - Telemark/Alpint</title>
	<link rel="stylesheet" href="../../style.css" type="text/css">
	<base target="_blank">
</head>
<?php
//$path is the directory where the images are stored
//$ehight and $width are the display size for the actual image
//$comment is the actual comment text
function display_latest_image($path2, $path, $height,$width,$comment)
{
if (is_dir("$path") ) {
	$time=time();
	$diff=0;
	$prevdiff=0;
	$handle=opendir($path);
	while (false!==($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
			$siste = strlen($file)-1;
			$slutt = $file[$siste-2].$file[$siste-1].$file[$siste];
			if ($slutt !="dat" &&
				$slutt !="bak" &&
				$slutt !="ock") {
					$isPicture = true;
				}
			else {
				$isPicture = false;
			}
			if ($isPicture){
				$bokstaver = $file[$siste-6].$file[$siste-5].$file[$siste-4];
				if ($bokstaver !="umb" && $bokstaver !="ght"){
					$diff = ($time - filectime("$path/$file"));
					if ($diff < $prevdiff OR $prevdiff==0) {
						$image="$file";
					}
				}
			}
		}
	}
	closedir($handle);
}
echo "\r\n<div align=center><a href=../albums/dagens/$image border=0><img src=../albums/dagens/$image height=$height% border = 0></a></br>";
echo "</div>";
}

display_latest_image("../dagens", "/var/www/bilder/albums/dagens", 100, 100, "Edgar");

?>
</body>

</html>