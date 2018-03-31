<?php
if(!isset($INC['random_funcs']))
{
  $INC['random_funcs'] = true;
  /**
* Generiert eine zufaellige Zeichenkette der Laenge $count
* @param int $chars Laenge
* @return String
*/
  function genrs($chars)
  {
    /**
  * Warum nur Kleinbuchstaben?
  * Ganz einfach: MySQL ist case-insensitive bei Vergleich mit =
  * und ich habe keinen Bock die ganze Zeit STRCMP() zu verwenden.
  */
    $c = "abcdefghijklmnopqrstuvwxy0123456789_-";
    $l = strlen($c)-1;
    $r = '';
    for($i=0;$i<$chars;$i++)
    {
      $rn = mt_rand(0,$l);
      $r .= substr($c,$rn,1);
    }
    return $r;
  }
}
?>