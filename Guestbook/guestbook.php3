<?
 


$script = basename( $PHP_SELF );
$tekstfil = "guestbook.txt";



function writeEntry( $a )
{
  global $tekstfil;
  $fp = fopen( "$tekstfil", "a" );

  if( count($a)>0 )
    $s="\n";
  for( $i=0;$i<count($a);$i++)
  {
    $s.= $a[$i];
    if( $i!=count($a)-1)
      $s.='%';
  }
  fputs( $fp, "$s" );
  fclose($fp);
}


switch( $a ):
  case( "write" ):
	showForm();

  
  break;
  case( "submit" ):

  if( isset($submit) )
  {
    
	 
		$newBody = removeHTML($body);

	$a = Array( date("d.m.y, H.i"), $navn, $epost, $newBody);
    
	if ($navn != "" && $newBody != "" )
		writeEntry( $a );

    header("Location: http://www.ntnui.no/telemark/Guestbook/guestbook.php3");
    exit;
  }

  break;

	default:
	showMessages($tekstfil);
endswitch;

?>


<?

function showForm(){
?>
<html>
<head>
	<title>ntnui - Telemark/Alpint</title>
	<link rel="stylesheet" href="../style.css" type="text/css">
<head>
<body>
<h1>Skriv i gjesteboka</h1>

<p>

<form method="post" action="<? echo( "$script?a=submit" ); ?>">
<table>
 <tr>
  <td><b>Navn</b></td><td><input type="text" name="navn" size="30" value=""></td><td />
 <tr>
 <tr>
   <td><b>Epost</b></td><td><input type="text" name="epost" size="30" value=""></td><td />
 <tr>
 <tr>
   <td valign="top"><b>Tekst</b></td><td>
   <TEXTAREA NAME="body" ROWS="10" COLS="40"></TEXTAREA></td><td></td>
 </tr>
 
  <td /><td align="left"><input name="submit" type="submit" value="Send"></td><td />
 </tr>
</table>
</form>


</form>
<a href="guestbook.php3">Avbryt</a>
<body>
<html>

<?

}





function showMessages($tekstfil){
?>
<html>
<head>
	<title>ntnui - Telemark/Alpint</title>
	<link rel="stylesheet" href="../style.css" type="text/css">
</head>
<body>
<table width="600"><tr><td>
<h1>Gjestebok</h1>
<p>
<center><b>
<A HREF="?a=write">Skriv ny hilsen</A></br></b>
</center>
<hr>
<?
echo writeTable($tekstfil)
?>
</td></tr></table>
</body>
</html>
<?


}



function writeTable( $file )
{
  // referanser til stylesheet-klasser
  $cls_header    = "header";
  $cls_rada      = "rada";
  $cls_radb      = "radb";

  $array = file( $file );
$array = array_reverse ($array);
  
  $s = '<table width="100%" cellspacing="0" cellpadding="1">
         <tr>
          <td>';  

  for( $i=0, $c=count($array); $i<$c; $i++ )
  {
    
    $line = trim( $array[$i] );

    if( preg_match("/%/", $line, $res) )
    { 
      $t = split( '%', $line );            
     
      
          $s .= '<table>
					<tr>
						<td>Fra: <b>'.$t[1].'</b> - <i>'.$t[0].'</i>
						</td>
					</tr>';
		if ($t[2] != ""){
		$s .= '<tr>
					<td>Epost: '.$t[2].'
					</td>
				</tr>';
		
		
		}
					
					
			$s .= '<tr>
						<td><BLOCKQUOTE><i>'.$t[3].'</i></BLOCKQUOTE>
						</td>
					</tr>
				</table>
				<hr>';
						
		
      
	  
	  }
      
           
      
    }
  

  $s .= '</td>
           </tr>
            </table>';

return $s;

}

function removeHTML($s){

$s = safeHTML($s);

$s = eregi_replace ("\n", "<br>", $s);
$s = eregi_replace ("<hr>", "<br>", $s);
$s = eregi_replace ("<hr />", "<br>", $s);
$s = eregi_replace ("javascript", "(skulleværtjavascript)", $s);

$s = eregi_replace ("%", "(prosent)", $s);



return $s;
}

function safeHTML($text) { 
$text = stripslashes($text); 
$text = strip_tags($text, '<br><b><i><u><a>'); 
$text = eregi_replace ("<a[^>]+href *= *([^ ]+)[^>]*>", "<a href=\\1>", $text); 
$text = eregi_replace ("<([b|i|u])[^>]*>", "<\\1>", $text); 
return $text; 
}





?>
