<?php
if(!isset($INC['bbcode_funcs']))
{
  $INC['bbcode_funcs'] = true;
  function bb_decode2html($text)
  {
    //$txt = htmlentities(($text),null,'utf-8');
    $txt = htmlentities($text,null);
    /*
    Fragen sinnlos. Ich checke das auch nicht mehr. Ein kleiner Erklaerungsversuch am beispiel [i]foo[/i]
    /(?sU)(\[[Ii]\])(.+)(\[\/[Ii]\])/
    nehmen wir ausseinander:
    (?sU):
     - Modificator s sorgt dafuer, dass fuer "." auch \n gematcht wird
     - Modificator U sorgt dafuer, dass das Regex nicht zu viel frisst (ungreedy)#
    (\[[Ii]\]):
     - Matcht [i] und [I]
    (.+):
     - Matcht 1-unendlich beliebigge zeichen (Dank Modificator s auf Zeilenumbrueche)
    (\[\/[Ii]\]):
     - Matcht auf [/i] und [/I]
    */
    $bbcodes = array(
    '/(?sU)(\[[Bb]\])(.+)(\[\/[Bb]\])/' => '<b style="font-weight:bold !important">\\2</b>',
    '/(?sU)(\[[Ii]\])(.+)(\[\/[Ii]\])/' => '<i>\\2</i>',
    '/(?sU)(\[[Uu]\])(.+)(\[\/[Uu]\])/' => '<u>\\2</u>',
    '/(?sU)(\[url=)(http:\/\/|ftp:\/\/)(.+)(\])(.+)(\[\/url\])/' => '<a href="\\2\\3">\\5</a>',
    '/(?sU)(\[img=)(http:\/\/|ftp:\/\/)(.+)(\])(\[\/img\])/' => '<img src="\\2\\3" />',
    '/(?sU)(\[color=)(red|blue|green|yellow|orange|lime)(\])(.+)(\[\/color\])/' => '<font color="\\2">\\4</font>',
    '/(?sU)(\[color=\#)([a-zA-Z0-9]*?)(\])(.+)(\[\/color\])/' => '<font color="#\\2">\\4</font>',
    '/(?sU)(\[size=)([0-9]*)(\])(.+)(\[\/size\])/' => '<span style="font-size: \\2">\\4</span>',
    '/(?sU)(\[align=)(left|right|center)(\])(.+)(\[\/align\])/' => '<p align="\\2">\\4</div>',
    '/(?s)(\[quote=)([^\]]+)(\])/' => '<div class="quote"><div class="author">Zitat: \\2</div>',
    '/(?s)(\[\/quote\])/' => '</div>'
    );
    //Array umformen
    foreach($bbcodes as $from => $to)
    {
      $bb_tags[] = $from;
      $bb_html[] = $to;
    }
    $txt = preg_replace($bb_tags, $bb_html, $txt);
    return nl2br($txt);
  }
  function bb_decode2textarea($text)
  {
   return htmlentities(stripslashes($text));
  }
}
?>