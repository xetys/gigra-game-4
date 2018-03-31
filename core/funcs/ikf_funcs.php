<?php
if(!isset($INC['ikf_funcs']))
{
  $INC['ikf_funcs'] = true;
  /**
* iBlue's Komisches Format 2 Array
* Konvertiert ein IKF in ein Array
*
* @param ikf ikf
* @return Array
*
*/
  function ikf2array($ikf)  //iBlue's komisches Format in Array konvertieren
  {
    $ret = array();
    if($ikf == "")
    return $ret;
    $x   = explode(", ",$ikf);
    foreach($x as $y)
    {
      $z = explode("=",$y);
      $z[0] = str_replace('§%',', ',$z[0]);
      $z[1] = str_replace('§%',', ',$z[1]);
      $ret[$z[0]]=$z[1];
    }
    return $ret;
  }
  /**
* Array 2 iBlue's Komisches Format
* Konvertiert einen Array zurueck in ein IKF
* 
* @param Array $arr
* @return IKF
*/
  function array2ikf($arr)  //Array in iBlue's komiches Format konvertieren
  {
    if($arr == null || !is_array($arr)  || count($arr) == 0)
        return "";
    foreach($arr as $index => $element)
    {
      $index = str_replace(', ','§%',$index);
      $element = str_replace(', ','§%',$element);
      $x[] = "$index=$element";
    }
    return implode(", ",$x);
  }
  
  
  function mergeIkf($asIKF,$asIKF2)
  {
      $laIKF = ikf2array($asIKF);
      $laIKF2 = ikf2array($asIKF2);
      $laMerged = array();
      
      //Liste 1
      foreach($laIKF as $key => $value)
      {
        if(!isset($laMerged[$key]))
            $laMerged[$key] = $value;
        else
            $laMerged[$key] += $value;
      }
      
      //Liste 2
      foreach($laIKF2 as $key => $value)
      {
        if(!isset($laMerged[$key]))
            $laMerged[$key] = $value;
        else
            $laMerged[$key] += $value;
      }
      
      return array2ikf($laMerged);
  }
}
?>