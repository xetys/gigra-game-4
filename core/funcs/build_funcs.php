<?php
/**
* @deprecated
*/
if(!isset($INC['build_funcs']))
{
  $INC['build_funcs'] = true;
  /**
  * @deprecated
  */
  function update_forsch($uid)
  {
    /*
    $f = db_fonerow("SELECT f FROM forschung WHERE uid='$uid' AND f LIKE '%onbuild%'");
    if($f==false) return;
    $f = ikf2array($f[0]);
    //Auf 'Weiter' geklickt.
    //Wenn was beim Forschen war und fertig ist
    if($f != false && isset($f['finished']) && $f['finished']<time())
    {

      $f['f'.$f['onbuild']]++;  //Forschung um eine Stufe erhoehen
      unset($f['onbuild']);     //Nicht mehr im Bau
      unset($f['finished']);    //Zeit weg
      unset($f['coords']);
      mysql_query('UPDATE forschung SET f="'.mysql_escape_string(array2ikf($f)).'" WHERE uid="'.$uid.'"') or die(mysql_error());
    }
    */
  }
 /**
  * @deprecated
  */
  function update_build($coords)
  {
    /*
    $k = db_fonerow("SELECT b FROM buildings WHERE coords='$coords' AND b LIKE '%onbuild%'");
    if($k==false) return;
    $k = ikf2array($k[0]);

    if(isset($k['finished']) && $k['finished']<time())
    {
      $k['k'.$k['onbuild']]++;  //Gebaude um eine Stufe erhoehen
      unset($k['onbuild']);     //Nicht mehr im Bau
      unset($k['finished']);    //Zeit weg
      mysql_query('UPDATE buildings SET b="'.mysql_escape_string(array2ikf($k)).'" WHERE coords="'.$coords.'"') or die(mysql_error());
    }
    */
  }
}


function listReqs($type = "B",$id)
{
	global $_BAU,$_FORS,$_SHIP,$_VERT;
	
	$blBauBar = false;
	switch ($type)
	{
		case "B":
		default :
			{
				$sReq = $_BAU[$id][8];
				break;
			}
		case "F":
				$sReq = $_FORS[$id][8];
				break;
		case "S":
				$sReq = $_SHIP[$id][7];
				break;
		case "V":
				$sReq = $_VERT[$id][7];
				break;
	}
	
	$aReqs = array();
	
	$parts1 = explode("|", $sReq);
	foreach ($parts1 as $part)
	{
		$aTeil = array();
		$parts2 = ikf2array($part);
		$aTeil = $parts2;
		
		$aReqs[] = $aTeil;
	}
	
	return $aReqs;
}
function canSee($id)
{
    if($id < 100) return true;
    
    $laSkills = getSkills(Uid());
    if($laSkills["forsch_geheimschiff1"] >= 13 && $id == 101)
        return true;
    elseif($laSkills["forsch_geheimschiff2"] >= 15 && $id == 102)
        return true;
    else 
        return false;
}
function baubar($type = "B",$id,$k,$f)
{
	global $_BAU,$_FORS,$_SHIP,$_VERT;
	

    if($id > 100 && $type == "S")
    {
        $laSkills = getSkills(Uid());
        if($laSkills["forsch_geheimschiff1"] >= 13 && $id == 101)
            return true;
        elseif($laSkills["forsch_geheimschiff2"] >= 15 && $id == 102)
            return true;
        else 
            return false;
    }
	$blBauBar = false;
	switch ($type)
	{
		case "B":
		default :
			{
				$sReq = $_BAU[$id][8];
				break;
			}
		case "F":
				$sReq = $_FORS[$id][8];
				break;
		case "S":
				$sReq = $_SHIP[$id][7];
				break;
		case "V":
				$sReq = $_VERT[$id][7];
				break;
	}
	
	
	$parts1 = explode("|", $sReq);
	foreach ($parts1 as $part)
	{
		$parts2 = ikf2array($part);
		$blTeil = true;
		foreach ($parts2 as $key => $level)
		{
			$kType = substr($key, 0,1);
			$kVal = substr($key, 1);
			$o = $kType == "B" ? $k["k" . $kVal] : $f["f" . $kVal];
			if($o < $level)
				$blTeil = false;
		}
		if($blTeil)
			$blBauBar = true;
	}
	
	#var_dump($blBauBar);
	return $blBauBar;
}
?>
