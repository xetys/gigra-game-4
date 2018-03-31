<?php
/**
 * 
 * Gigra Refact V3
 * @copyright 2011 (c) stytex.de 
 * @author David Steiman @ stytex.de
 * 
 * 2011: This code was rewritten completely for replacing Empire Space Source 
 * with new Source based on template system an mutlilanguage solution.
 * 
 * 
 * All rights reserved to David Steiman and under the rights of stytex.de
 */

define("FL_NO_MISSIONS",-10);
define("FL_ME_IN_UMOD",-15);
define("FL_TARGET_IN_UMOD",-16);
define("FL_NO_PLANET",-20);
define("FL_NO_SHIPS",-30);
define("FL_INVALID_SHIP",-40);

define("FL_INVALID_MISSION",-45);
define("FL_INVALID_RES",-50);
define("FL_NOT_ENOUGH_RES",-60);
define("FL_NOT_ENOUGH_CAPA",-70);
define("FL_NOT_ENOUGH_FUEL",-80);
define("FL_AKS_NOT_IN_TIME",-90);
define("FL_SUCCESS",-100);

/**
   * Alogorithmus um Flugzeit und Verbrauch zu berechnen
  */
function calc_flugzeit($fromc,$toc,$sselect,$speed,$verbr,$ladekap)
{
    global $_ACTCONF;
    
    
    $a=$toc[0];
    $b=$toc[1];
    $c=$toc[2];
    $p=$sselect;
    $m=0;
    $h=0;
    $d="-";
    $en="";
    $d=round(abs(($a-$fromc[0])*20000))+round((2700+5*abs(($b-$fromc[1])*19)))+round((1000+abs(($c-$fromc[2])*5)));
    if($a<1|$a>$_ACTCONF["maxgala"]|$b<1|$b>$_ACTCONF["maxsys"]|$c<0|$c>199)
    {
      return -3; //Koordinaten nicht vorhanden
    }
    $e=round($verbr*$d/35000*(($p/10)+1)*(($p/10)+1))+1;
    $s=round((35000/$p*sqrt($d*10/$speed))/$_ACTCONF["speed_fleet"]);
    //Balancefix
    $s /= 10;
    
    //Bonus
    $s = $s * getFlugzeitBonus($_SESSION["uid"]);
    
    
    if($e>$ladekap){return -2; /*Nicht genug Ladekapazitaet*/}
    else
    {
      return array($s/*Flugzeit*/,$e/*Verbrauch*/);
    }
}

function getFlyTime($fromc,$toc,$sselect,$speed,$verbr,$ladekap)
{
    global $_ACTCONF;
    
    
    $fG = $fromc[0];
    $fS = $fromc[1];
    $fP = $fromc[2];
    
    $tG = $toc[0];
    $tS = $toc[1];
    $tP = $toc[2];
    
    if($tG<1|$tG>$_ACTCONF["maxgala"]|$tS<1|$tS>$_ACTCONF["maxsys"]|$tP<0|$tP>20)
    {
      return -3; //Koordinaten nicht vorhanden
    }
    
    //Erst Distanz erreichnen
    $liDistance = 5;
    if ($fG - $tG != 0) {
		$liDistance = abs($fG - $tG) * 20000;
	} else if ($fS - $tS != 0) {
		$liDistance = abs($fS - $tS) * 5 * 19 + 2700;
	} else if ($fP - $tP != 0) {
		$liDistance = abs($fP - $tP) * 5 + 1000;
	} else {
		$liDistance = 5;
	}
    
    //Flugzeit
    $liFlyTime = max(round((3500 / ($sselect * 0.1) * pow($liDistance * 10 / $speed, 0.5) + 10) / $_ACTCONF["speed_fleet"]), 5);
    //$liFlyTime = round($liFlyTime/5); //Balancing
    
    $liFlyTime = $liFlyTime * getFlugzeitBonus(Uid());
    
    $liConsumption = round($verbr*$liDistance/35000*(($sselect/10)+1)*(($sselect/10)+1))+1;
    
    
    return array($liFlyTime,$liConsumption);
}
function checkShips($asFrom,$aaSchiffe)
{
    $laSchiffe = read_schiffe($asFrom);
    
    //Sind Schiffe vorhanden?
    $liShipCount = 0;
    foreach($aaSchiffe as $lsID => $liCount)
    {
        $liCount = abs($liCount);//keine Minus Zahlen
        $liShipCount += $liCount;
        if(!isset($laSchiffe[$lsID]) || $liCount > $laSchiffe[$lsID])
            return FL_INVALID_SHIP;
    }
    if($liShipCount < 1)
        return FL_NO_SHIPS;
    else
        return 1;
}
function listMissions($asFrom,$asTo,$aaSchiffe)
{
    global $_ACTCONF;
    
    if(checkUMOD(Uid()))
        return FL_ME_IN_UMOD;
    $liCheckShips = checkShips($asFrom,$aaSchiffe);
    if($liCheckShips != 1)
        return $liCheckShips;
    
    $loDB = gigraDB::db_open();
    $lodb = &$loDB;
    
    //Check Planet From
    $loDB->query("SELECT 1 FROM planets WHERE coords = '{$asFrom}' AND owner = '{$_SESSION['uid']}'");
    if($loDB->numrows() == 0)
        return FL_NO_PLANET;
    //Check Planet To
    $lbPlanetSettled = true;
    $lbPlanetExists = true;
    $lbMyPlanet = false;
    $laRow = $loDB->getOne("SELECT owner,destructed FROM planets WHERE coords = '$asTo'");
    if(!$laRow)
    {
        $lbPlanetSettled = false;
        //es konnte kein Planet gefunden werden.....aber das bedeutet nicht, das es den nicht gibt
        $laCoordParts = explode(":",$asTo);
        //gueltige eingabe?
        if($laCoordParts[0] < 1 || $laCoordParts[0] > $_ACTCONF["maxgala"])
        {
            $lbPlanetExists = false;
            return FL_NO_PLANET;
        }
        if($laCoordParts[1] < 1 || $laCoordParts[1] > $_ACTCONF["maxsys"])
        {
            
            $lbPlanetExists = false;
            return FL_NO_PLANET;
        }
        $lsSystem = $laCoordParts[0] .":". $laCoordParts[1];
        //gib dieses System?
        $x = $lodb->getOne("SELECT maxp FROM maxplanets WHERE sys='$lsSystem'");
		if($x == false)
		{
		   $maxp = mt_rand(3,20);
		   $lodb->query("INSERT INTO maxplanets SET maxp='$maxp', sys='$lsSystem'");
		}
		else
			$maxp = $x[0];
        if($laCoordParts[2] < 1 || $laCoordParts[2] > $maxp)
        {
            $lbPlanetExists = false;
        }
    }
    else
    {
        $lsOwner = $laRow[0];
        if($lsOwner == $_SESSION["uid"])
            $lbMyPlanet = true;
    }
    
    //umod target?
    $lbTargetUmod = false;
    if(checkUMOD($lsOwner))
        $lbTargetUmod = true;
    //auswerten
    if(!$lbPlanetExists)
        return FL_NO_PLANET;
        
    $laMissions = array();
    $laInfos = array();
    //Standard Missionen
    
    if(coordType($asTo) == 3)
    {
        //6. Recycling
        if(isset($aaSchiffe[2]) && $aaSchiffe[2] > 0 && coordType($asTo) == 3)
            $laMissions[] = "recy";   
    }
    else
    {
        if(isset($lsOwner) && $lsOwner != '0' && !$lbTargetUmod)
        {
            $laRow = $lodb->getOne("SELECT (SELECT pgesamt FROM v_punkte p1 WHERE p1.uid = '$_SESSION[uid]'), (SELECT pgesamt FROM v_punkte p2 WHERE p2.uid = '{$lsOwner}')");
            $liMyPoints = $laRow[0];
            $liToPoints = $laRow[1];
            
            //Check noob
            $lbNoobschutz = checkNoobschutz($liMyPoints, $liToPoints);
            
            //1. Angriff
            if($lsOwner != $_SESSION["uid"])
            {
                
                if($lbNoobschutz)
                    $laInfos[] = "noob";
                    
                //ist der benutzer inaktiv??
                if(isInactive($lsOwner))
                    $lbNoobschutz = false;
                
                //UMod
                $lbUmode = checkUMOD($lsOwner);
                if($lbUmode)
                    $laInfos[] = "umod";
                
                
                //checkKrieg
                $lbKrieg = inWar($asTo);
                if($lbKrieg)
                    $laInfos[] = "krieg";
                
                //TODO: BND und NAP
                $lbBND = false;
                if($lbBND)
                    $laInfos[] = "bnd";
                $lbNAP = false;
                if($lbNAP)
                    $laInfos[] = "nap";
                
                $lbProbeRaid = count($aaSchiffe) == 1 && isset($aaSchiffe[3]) && $aaSchiffe[3] > 0;
                
                if(
                    (
                        (
                            !$lbNoobschutz || ($lbNoobschutz && $lbKrieg )
                        ) 
                        && !$lbUmode 
                        && !$lbBND 
                        && !$lbNAP 
                        && !isAngriffSperre()
                        && !isGesperrtUmod($lsOwner)
                    ) 
                    || isAdmin() 
                    )
                {
                    //if(!$lbProbeRaid)
                    //{
                        $laMissions[] = "ag_p";   
                        $laMissions[] = "aks_lead";   
                        $laMissions[] = "aks";   
                    //}
                    
                    //if(isset($aaSchiffe[8]) && $aaSchiffe[8] > 0 && coordType($asTo) == 1 && canGetMorePlanet(Uid()))
                    //    $laMissions[] = "inva";
                        
                    if(isset($aaSchiffe[14]) && $aaSchiffe[14] > 0)
                        $laMissions[] = "dest";
                        
                    //5. Spionage
                    if(isset($aaSchiffe[3]) && $aaSchiffe[3] > 0)
                        $laMissions[] = "spio";
                }
            }
            //2. Transport
            if(!(count($aaSchiffe) == 1 && isset($aaSchiffe[3]) && $aaSchiffe[3] > 0))
            {
                $laMissions[] = "trans";
            }
            
            //3. Stationieren
            $laMissions[] = "stat";
            
            //4. Halten
            $laMissions[] = "hold";
            
            
            
        }
        else if(isset($lsOwner) && $lsOwner != '0' && $lbTargetUmod)
        {
            //6. Recycling
            if(isset($aaSchiffe[2]) && $aaSchiffe[2] > 0)
                $laMissions[] = "recy";
            else
                return FL_TARGET_IN_UMOD;
        }
        else
        {
            //7. Kolonisieren
            if(isset($aaSchiffe[7]) && $aaSchiffe[7] > 0 && coordType($asTo) == 1 && canGetMorePlanet(Uid()) && $laRow[1] == 0)
                $laMissions[] = "kolo";
        }
        
        if(isAdmin())
        {
            $laMissions[] = "ag_p";   
            $laMissions[] = "aks_lead";   
            $laMissions[] = "aks";   
        }
    }
    
    if(count($laMissions) == 0)
        return FL_NO_MISSIONS;
    else
    {
        return array($laMissions,$laInfos);
    }
}


function sendFleet($asFrom,$asTo,$aaSchiffe,$asMission,$aiSelect,$aaRes,$aaSpecialCommand)
{
    global $_SHIP;
    //check Schiffe und Mission
    $oCheckMission = listMissions($asFrom,$asTo,$aaSchiffe);
    
    if(!is_array($oCheckMission)) //dann hat der wohl mist gebaut, zu viele schiffe, keine schiffe, sonstige cheats
        return $oCheckMission;
    else
    {
        $laMissions = $oCheckMission[0];
        
        //Frage: ist die Mission mit den moeglichen stimmig?
        if(!in_array($asMission,$laMissions))
            return FL_INVALID_MISSION;
    }
    //Erneute Schiffkorrektur, sicher ist sicher ;)
    foreach($aaSchiffe as $lsID => $liCount)
        $aaSchiffe[$lsID] = abs($liCount);
        
    //looos gehts, schiff auswertung
    $liSpeed = -1;
    $liCapa = 0;
    $liConsum = 0;
    $f = getForschung();
    
    $laSchiffe = read_schiffe($asFrom);//neue Flotte
    foreach($aaSchiffe as $lsID => $liCount)
    {
        //$liSpeed = $liSpeed == -1 ? $_SHIP[$lsID][11] * sqrt($f['f'.$_SHIP[$lsID][13]] / 10 + 1) : min($_SHIP[$lsID][11] * sqrt($f['f'.$_SHIP[$lsID][13]] / 10 + 1),$liSpeed);
        $liSpeed = $liSpeed == -1 ? $_SHIP[$lsID][11] * (1 + $f['f'.$_SHIP[$lsID][13]] / 10) : min($_SHIP[$lsID][11] * (1 + $f['f'.$_SHIP[$lsID][13]] / 10),$liSpeed);
        
        $liConsum += $_SHIP[$lsID][12] * $liCount;
        $liCapa += $liCount * ($_SHIP[$lsID][10] * ((20 + $f['f10'])/20));
        
        //neue flotte gleich mit setzen
        $laSchiffe[$lsID] -= $liCount;
        if($laSchiffe[$lsID] == 0)
            unset($laSchiffe[$lsID]);
    }
    
    //Berechne allet
    $laFlyData = getFlyTime(explode(":",$asFrom),explode(":",$asTo),$aiSelect,$liSpeed,$liConsum,$liCapa);
    $laRes = read_res($asFrom);

    
    //check Rohstoffe
    //ersma schoen machen, also KEINE scheiss negativ ressis
    foreach($aaRes as $i => $res)
    {    
        if(!is_numeric($res))
            return FL_INVALID_RES;
        else
        {
            $aaRes[$i] = abs($res);
            //nun checken wa mal, ob wa soviel haben und ob soviel rein passt
            if($aaRes[$i] > $laRes[$i])
                return FL_NOT_ENOUGH_RES;
            
            if($laFlyData == -2 || ($i == 3 && ($aaRes[$i] + $laFlyData[1]) > $laRes[$i]))//Wasserstoff
                return FL_NOT_ENOUGH_FUEL;
                
            if($aaRes[$i]-1 > $liCapa)
                return FL_NOT_ENOUGH_CAPA;
                
            $liCapa -= $aaRes[$i];
        }
    }
    
    $liTSee = 0;
    $liTThere = time() + $laFlyData[0];
    $liTBack = time() + ($laFlyData[0] * 2);
    $liFlyTime = $laFlyData[0];
    $liTHold = 0;
    $lsParentFleet = "";
    
    
    $lodb = gigraDB::db_open();
    //spezial geschichten
    switch($asMission)
    {
        //Aks joinen und erstellen
        case "aks":   
            //1. Hole Fleet
            $lsParentFleet = $aaSpecialCommand["join"];
            $laRow = $lodb->getOne("SELECT tthere,tback,flytime FROM flotten WHERE typ = 'aks_lead' AND id = '$lsParentFleet'");
            if(!$laRow)
                return FL_INVALID_MISSION;
            else
            {
                //2. Geht das mit der Zeit klar?   
                if($liTThere <= $laRow[0])
                {
                    //Erklärung: flotte ist schneller da, also holt sie die angreifende Flotte ein, fliegt aber genauso lahm zurück dann
                    $liTThere = $laRow[0];
                    $liTBack = $liTThere + $liFlyTime;
                    $liFlyTime = $laRow[2];
                }
                else
                {
                    if(($laRow[2] / $liFlyTime) < 0.6)
                    {
                        //sorry dude, bist zu langsam
                        return FL_AKS_NOT_IN_TIME;
                    }
                    else
                    {
                        //alle Flotten bremsen
                        $lodb->query("UPDATE flotten SET tthere = '{$liTThere}', flytime = '{$liFlyTime}' WHERE id = '$lsParentFleet' OR parentfleet = '$lsParentFleet'");
                    }
                }
            }
            break;
        //Haltezeit
        case "hold":
            $liHoldTime = isset($aaSpecialCommand["holdtime"]) ? (int)$aaSpecialCommand["holdtime"] : 0;
            $liHoldTime = $liHoldTime * 3600;
            $liTBack += $liHoldTime;
            $liTHold = $liTThere + $liHoldTime;
            
            break;
    }
    
    //Abschluss
    
    $lsSchiffePlanet = array2ikf($laSchiffe); //die bleiben
    $lsSchiffeFlotte = array2ikf($aaSchiffe); //die fliegen
    
    //
    $aaRes[0] = is_numeric( $aaRes[0] ) ? $aaRes[0] : 0;
    $aaRes[1] = is_numeric( $aaRes[1] ) ? $aaRes[1] : 0;
    $aaRes[2] = is_numeric( $aaRes[2] ) ? $aaRes[2] : 0;
    $aaRes[3] = is_numeric( $aaRes[3] ) ? $aaRes[3] : 0;
    
    //Tarnbomber funktion
    if(isset($aaSchiffe[6]) && in_array($asMission,array("ag","ag_p","aks","aks_lead")))
    {
        if(count($aaSchiffe) == 1)
        {
            $laRow = $lodb->getOne("SELECT f FROM planets LEFT JOIN forschung ON planets.owner = forschung.uid WHERE coords = '$asTo'");
            $laMyF = getForschung(Uid());
            $laHisF = ikf2array($laRow['f']);
            $liMySpio = isset($laMyF['f8']) ? $laMyF['f8'] : 0;
            $liHisSpio = isset($laHisF['f8']) ? $laHisF['f8'] : 0;
            
            $liSpioDiff = max(0,$liHisSpio - $liMySpio);
            $liDifferTime = max(60,300 * $liSpioDiff);
            $liTSee = $liTThere - $liDifferTime;
        }
    }
    
    //Flotte
    $lsFid = uniqid();
    $lsQueryFlotte = "INSERT INTO flotten SET ";
    $lsQueryFlotte .=                       "id = '$lsFid', ";
    $lsQueryFlotte .=                       "userid = '{$_SESSION["uid"]}', ";
    $lsQueryFlotte .=                       "fromc = '{$asFrom}', ";
    $lsQueryFlotte .=                       "toc = '{$asTo}', ";
    $lsQueryFlotte .=                       "typ = '{$asMission}', ";
    $lsQueryFlotte .=                       "schiffe = '{$lsSchiffeFlotte}', ";
    $lsQueryFlotte .=                       "tthere = '{$liTThere}', ";
    $lsQueryFlotte .=                       "thold = '{$liTHold}', ";
    $lsQueryFlotte .=                       "tback = '{$liTBack}', ";
    $lsQueryFlotte .=                       "flytime = '{$liFlyTime}', ";
    $lsQueryFlotte .=                       "tsee = '{$liTSee}', ";
    $lsQueryFlotte .=                       "load1 = '{$aaRes[0]}', ";
    $lsQueryFlotte .=                       "load2 = '{$aaRes[1]}', ";
    $lsQueryFlotte .=                       "load3 = '{$aaRes[2]}', ";
    $lsQueryFlotte .=                       "load4 = '{$aaRes[3]}',";
    $lsQueryFlotte .=                       "fuel = '{$laFlyData[1]}',";
    $lsQueryFlotte .=                       "parentfleet = '{$lsParentFleet}';";
                                            
    //Planet
    $lsQueryPlanet = "UPDATE schiffe SET s = '{$lsSchiffePlanet}' WHERE coords = '{$asFrom}';";
    
    //FEUER
    $aaRes[3] += $laFlyData[1];
    sub_res($aaRes,$asFrom);
    $lodb->query($lsQueryFlotte);
    $lodb->query($lsQueryPlanet);
    fleetSendLog($lsSchiffeFlotte,$liTThere,$liTBack,$asFrom,$asTo,$aaRes,$asMission);
    
    return FL_SUCCESS;
}

function fleetSendLog($asSchiffe,$aiThere,$aiBack,$asFromC,$asToC,$aaRes,$asTyp)
{
    $lsText = "Eine Flotte mit den Schiffen $asSchiffe wurde von $asFromC nach $asToC gestartet. Sie soll um " . date("d.m.Y H:i:s",$aiThere) . " ankommen und um " . date("d.m.Y H:i:s",$aiBack) . " wiederkehren. Rohstoffe: ".var_export($aaRes,true) . ", Mission: $asTyp";
    gigraDB::db_open()->query("INSERT INTO log SET uid = '".Uid()."', time = UNIX_TIMESTAMP(), entry = '$lsText'");   
}
function fleetArriveLog($uid,$asSchiffe,$aiThere,$aiBack,$asFromC,$asToC,$aaRes,$asTyp)
{
    if($aiThere > 0)
        $lsText = "Eine Flotte mit den Schiffen $asSchiffe ist von $asFromC nach $asToC angekommen. Sie sollte um " . date("d.m.Y H:i:s",$aiThere) . " ankommen und um " . date("d.m.Y H:i:s",$aiBack) . " wiederkehren. Rohstoffe: ".var_export($aaRes,true) . ", Mission: $asTyp";
    else
        $lsText = "Eine Flotte mit den Schiffen $asSchiffe ist von $asFromC nach $asToC wiedergekehrt. Sie sollte um " . date("d.m.Y H:i:s",$aiBack) . " wiederkehren. Rohstoffe: ".var_export($aaRes,true) . ", Mission: $asTyp";
    gigraDB::db_open()->query("INSERT INTO log SET uid = '$uid', time = UNIX_TIMESTAMP(), entry = '$lsText'");   
}

function fleetBack($asFid,$asUserId)
{
    $lodb = gigraDB::db_open();

    $laRow = $lodb->getOne("SELECT UNIX_TIMESTAMP() + (UNIX_TIMESTAMP() - (tthere - (tback-(IF(thold > 0,(thold-tthere),0))-tthere))) as new_tback, (IF(tthere = 0,0,(tthere - UNIX_TIMESTAMP()) / flytime) * fuel) as fuelBack FROM flotten WHERE id = '$asFid' AND userid = '$asUserId' AND tthere > 0");
    
    if(is_array($laRow))
    {
        $lodb->query("UPDATE flotten SET tthere=0,tback = $laRow[0], parentfleet = '', load4 = load4 + {$laRow[1]} WHERE id = '$asFid' or parentfleet = '$asFid'");
        return true;   
    }
    else
    {
        return false;
    }
}

function powerCollect($asTo,$asPrio="0,1,2,3",$asShipTypes="12,13",$abListOnly = false)
{
    global $_SHIP;
    $lodb = gigraDB::db_open();
    
    //Dein Plani, ja?
    $laRow =$lodb->getOne("SELECT COUNT(*) FROM planets WHERE owner = '".Uid()."' AND coords = '$asTo'");
    if($laRow[0] == 1)
    {
        $laReport = array();
        $laForsch = getForschung(Uid());
        
        //Grosses Rohstoffzusammenzieh Massakaaaaaaaaaaaaaaaaaaa
        $lodb->query("SELECT coords FROM planets WHERE owner = '".Uid()."' AND coords != '$asTo'");
        $laSelSum = array(0,0,0,0);
        
        while($laRow = $lodb->fetch("row"))
        {
            $lsCoords = $laRow[0];
            
            //Rohstoffe
            $laRes = read_res($lsCoords);
            //Schiffe 
            $laShips = read_schiffe($lsCoords);
            
            //Rohstoffsumme
            $liSum = $laRes[0] + $laRes[1] + $laRes[2] + $laRes[3];
            $liTmp = $liSum;
            
            //berechne schiffe
            $laShipTypes = explode(",",$asShipTypes);
            
            $liCapaLeft = 0;
            $laSelectedShips = array();
            foreach($laShipTypes as $lsID)
            {
                if(isset($laShips[$lsID]) && $laShips[$lsID] > 0)
                {
                    $liCapa = ($_SHIP[$lsID][10] * ((20 + $laForsch['f10'])/20));
                    $liCapaAll = $laShips[$lsID] * $liCapa;
                    if($liCapaAll > $liTmp)
                    {
                        //wir brauchen nicht alle Schiffe
                        $laSelectedShips[$lsID] = ceil($liTmp / $liCapa);
                        //und vorbei
                        break;
                    }
                    else
                    {
                        //Wir haben zu wenige oder exakt ausreichende Schiffe, nehme alle
                        $laSelectedShips[$lsID] = $laShips[$lsID];
                        $liTmp -= $liCapaAll;//soviel muss noch mitgenommen werden
                    }
                }
            }
            //alles vorbei, nun schauen wir mal ueberhaupt schiffe in der liste haben
            if(count($laSelectedShips) == 0)
                continue;//nächster Planet
            
            $liSpeed = -1;
            $liConsum = 0;
            $liCapa = 0;
            foreach($laSelectedShips as $lsID => $liCount)
            {
                $liSpeed = $liSpeed == -1 ? $_SHIP[$lsID][11] * sqrt($laForsch['f'.$_SHIP[$lsID][13]] / 10 + 1) : min($_SHIP[$lsID][11] * sqrt($laForsch['f'.$_SHIP[$lsID][13]] / 10 + 1),$liSpeed);
        
                $liConsum += $_SHIP[$lsID][12] * $liCount;
                $liCapa += $liCount * ($_SHIP[$lsID][10] * ((20 + $laForsch['f10'])/20));
            }
            $laFlyData = getFlyTime(explode(":",$lsCoords),explode(":",$asTo),10,$liSpeed,$liConsum,$liCapa);
            
            //Nun füllen wir mal alles mit Rohstoffen
            $laSelectedRes = explode(",",$asPrio);
            $laSelRes = array();
            
            //h2Check
            if($laFlyData[1] > $laRes[3])
                continue;//zu wenig h2
            else
                $laRes[3] -= $laFlyData[1];
            
            $liCapaLeft = $liCapa;
            foreach($laSelectedRes as $lsResId)
            {

                $laSelRes[$lsResId] = floor(min($laRes[$lsResId],$liCapaLeft));
                $liCapaLeft -= $laSelRes[$lsResId];
            }
            
            
            //wir sind bereit zum Start!
            $liRet = $abListOnly ? FL_SUCCESS : sendFleet($lsCoords,$asTo,$laSelectedShips,"trans",10,$laSelRes,array());
            
            if($liRet == FL_SUCCESS)
            {
                $laReport[] =   "<tr><td>".coordFormat($lsCoords) . 
                                "</td><td>".nicenum($laSelRes[0]) . " / ".nicenum($laRes[0]).
                                "</td><td>".nicenum($laSelRes[1]) . " / ".nicenum($laRes[1]).
                                "</td><td>".nicenum($laSelRes[2]) . " / ".nicenum($laRes[2]).
                                "</td><td>".nicenum($laSelRes[3]) . " / ".nicenum($laRes[3]).
                                "</td></tr>";
                
                $laSelSum[0] += $laSelRes[0];
                $laSelSum[1] += $laSelRes[1];
                $laSelSum[2] += $laSelRes[2];
                $laSelSum[3] += $laSelRes[3];
                
            }
        }
        $laReport[] =   "<tr><td>&Sigma;" .
                                "</td><td>".nicenum($laSelSum[0]) . 
                                "</td><td>".nicenum($laSelSum[1]) .
                                "</td><td>".nicenum($laSelSum[2]) .
                                "</td><td>".nicenum($laSelSum[3]) .
                                "</td></tr>";
        
        return $laReport;
    }
    return -1;
}
function allFleetsBack()
{
    $lodb = gigraDB::db_open();
    
    $lodb->query("SELECT id,userid FROM flotten WHERE userid = '".Uid()."'");
    while($laRow = $lodb->fetch())
        fleetBack($laRow[0],$laRow[1]);
}