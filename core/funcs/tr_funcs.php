<?php
if(!isset($INC['tr_funcs']))
{
  $INC['tr_funcs'] = true;
  /**
* Formartiert eine Zeit in Sekunden huebsch.
* @param $z int Zeit in Sekunden
* @return String Huebsche Zeit
*/
  function format_zeit($z)
  {
    $z=max($z,0);
    $tage='';
    $z=(int)$z;
    if($z>86400)
    $tage=((int)($z/86400)).' Tag';
    if($z>172800)
    $tage.='e';
    if($z>86400)
    $tage.=', ';
    if($z>=3600)
    {
      return($tage.date('H:i:s',$z-3600));
    }
    else
    {
      return($tage.'00:'.date('i:s',$z));
    }
  }
}
?>
