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


function showNews()
{
     $lodb = gigraDB::db_open();
     $laTplExport = array();
     
     //news
     $oNewsRow = $lodb->getOne("SELECT news_datum, news_titel, news_text FROM news ORDER BY news_datum DESC LIMIT 1");
     
     $laTplExport["lsNewsDate"] = date("d.m.Y",$oNewsRow[0]);
     $laTplExport["lsNewsTitle"] = $oNewsRow[1];
     $laTplExport["lsNewsText"] = $oNewsRow[2];
     
     return fromTemplate("news.tpl",$laTplExport);
}

function showChat()
{
    $lodb = gigraDB::db_open();
    $laTplExport = array();
    
    $userPreText = $lodb->getOne("SELECT users.name,tag FROM users LEFT JOIN allianz ON users.allianz = allianz.id WHERE users.id = '{$_SESSION['uid']}'");
    $laTplExport["lsPreText"] = "{$userPreText[0]};[{$userPreText[0]}][{$userPreText[1]}]";
     
    return fromTemplate("chat.tpl",$laTplExport);
}

function showChat2()
{
    //$lodb = gigraDB::db_open();
    $laTplExport = array();
    
    //$userPreText = $lodb->getOne("SELECT users.name,tag FROM users LEFT JOIN allianz ON users.allianz = allianz.id WHERE users.id = '{$_SESSION['uid']}'");
    $laTplExport["lsUserName"] = $_SESSION['name'];
     
    return fromTemplate("chat2.tpl",$laTplExport);
}

function showInfoBox()
{
    return fromTemplate("infoBox.tpl",array());
}

function showOverview()
{
    $lodb = gigraDB::db_open();
    $laTplExport = array();
    
    
    $plan_param = $lodb->getOne("SELECT * FROM planets WHERE coords='$_SESSION[coords]' LIMIT 1");      //Planetendaten laden
    
    $plani_type = coordType($_SESSION['coords']);
    $laTplExport["plani_type"] = $plani_type;
    
    $durchm =  $plan_param['dia'];                                                                      //Durchmesser
    $laTplExport["liDia"] = $durchm;
    $laTplExport["lsPlanetName"]  =  $plan_param['pname'];                                              //Planetenname
    
    if($plani_type == 2) 
    {
        $planetenbild = 'design/Planeten/mond.png';                                                           //Planetenbild vom Grafikpfad
    }
    else
    {
      $planetenbild = 'design/Planeten/'.$plan_param["pbild"];                                                   //Planetenbild vom Grafikpfad
    }
    
    $planetenbild = str_replace(".gif",".png",$planetenbild);
    
    $laTplExport["lsPlanetBild"] = $planetenbild;
    
    
    //Formel fuer Temperatur
    $laTemps = getMinMaxTemp($plan_param['temp'],$plan_param['dia']);
    
    $laTplExport["liTempFrom"] = nicenum($laTemps[0]);                                         //Niedrige Temperatur
    $laTplExport["liTempTo"] = nicenum($laTemps[1]);                                       //Hohe     Temperatur
    unset($kelvin);
    unset($bereich);
    $_SESSION['coords'] = $plan_param['coords'];                                                        //Koordinaten gleich in der
                                                                                                        // Session speichern. (siehe
                                                                                                        // oben)
    $ppunkte = $plan_param['punkte'];                                                                   //Punkte auf diesem Planeten
    
    
    $row = $lodb->getOne("SELECT 
                            planeten                                                                            AS pgesamt, 
                            forschung                                                                           AS pforsch, 
                            flotten                                                                             AS pflotte, 
                            verteidigung                                                                        AS pvert, 
                            (SELECT COUNT(punkte) AS c FROM planets WHERE owner=uid)                            AS panz,
                            (SELECT (infra + forsch + krieg) FROM erfahrung e WHERE e.uid = user_punkte.uid)    AS levelpunkte 
                        FROM user_punkte 
                        WHERE uid = '{$_SESSION['uid']}'");
    
    extract($row);
    $spielerlevel = getELevel($levelpunkte);
    $laTplExport["liLevel"] = $spielerlevel;
    
    
    $laTplExport["liAllXP"] = getNextLevelSum($spielerlevel);
    $laTplExport["liHasXP"] = $levelpunkte;
    
    $punkte_row = $lodb->getOne("SELECT rank FROM user_punkte WHERE uid = '{$_SESSION['uid']}'");
    
    $laTplExport["liScore"] = ($pgesamt + $pforsch + $pflotte + $pvert);
    $laTplExport["liRank"] = $punkte_row[0];
    
    $userCount = $lodb->getOne("SELECT COUNT(*) FROM users");
    $laTplExport["liUserCount"] = $userCount[0];
    
    //Boni
    $laSkills = getSkills(Uid());
    $laTplExport['resbonus_skill'] = $laSkills['infra_rohstoff'];
    $laTplExport['baubonus_skill'] = $laSkills['infra_bauzeit'];
    $laTplExport['forschbonus_skill'] = $laSkills['forsch_zeit'] * 5;
    
    $laRow = $lodb->getOne("SELECT boost_percent, boost_until FROM rohstoffe WHERE coords = '{$_SESSION['coords']}'");
    if($laRow[1] > time())
    {
        $laTplExport['boost_left'] = $laRow[1] - time();
        $laTplExport['boost_percent'] = $laRow[0];
    }
    $laRow = $lodb->getOne("SELECT forsch_percent,forsch_until,kampf_percent,kampf_until,bau_percent,bau_until FROM planets,user_gigron WHERE planets.owner = user_gigron.uid AND planets.coords = '{$_SESSION['coords']}'");
    
    if($laRow['bau_until'] > time())
    {
        $laTplExport['bau_left'] = $laRow['bau_until'] - time();
        $laTplExport['bau_percent'] = $laRow['bau_percent'];
    }
    if($laRow['forsch_until'] > time())
    {
        $laTplExport['forsch_left'] = $laRow['forsch_until'] - time();
        $laTplExport['forsch_percent'] = $laRow['forsch_percent'];
    }
    if($laRow['kampf_until'] > time())
    {
        $laTplExport['kampf_left'] = $laRow['kampf_until'] - time();
        $laTplExport['kampf_percent'] = $laRow['kampf_percent'];
    }
    
    return fromTemplate("overview.tpl",$laTplExport);
    
}

function showPlaneten()
{
    $lodb = gigraDB::db_open();
    $lodb2 = gigraDB::db_open();
    $laTplExport = array();
    
    $laPlaneten = array();
    
    $lodb->query("SELECT coords,pname,pbild FROM v_planets WHERE owner='{$_SESSION["uid"]}' ORDER BY gal,sys,plan,type");
    
    while($laRow = $lodb->fetch())
    {
        $laRow[2] = coordType($laRow[0]) == 2 ? "mond.png" : str_replace(".gif",".png",$laRow[2]);
        $laEvents = $lodb2->getOne("SELECT group_concat(distinct e.param order by e.prio asc separator ', ') FROM events e WHERE coords = '{$laRow[0]}' AND command = 'build'  GROUP BY e.coords");
        $laEvents = ikf2array($laEvents[0]);
        $laPlaneten[] = array("coords" => $laRow[0],"events" => $laEvents, "name" => $laRow[1], "bild" => "design/Planeten/".$laRow[2]);
    }
    
	foreach ($laPlaneten as $key => $laPlanet)
	{
		$laTemp = explode (":",$laPlanet["coords"]);
		$laCoords[$laTemp[0]][$laTemp[1]][$laTemp[2]] = $laPlaneten[$key];
	}
	//$laPlaneten = array();
	
    /*
    ksort($laCoords);
	foreach ($laCoords as $key => $bla)
	{
		ksort($bla);
		foreach ($bla as $key1 => $bla1)
		{
			ksort($bla1);
			foreach ($bla1 as $key2 => $bla2)
			{
				$laPlaneten[]= $bla2;
			}
		}
		
	}
    // Das ganze würde aus so gehen, schau ins Query
    */
    
    $laTplExport["laPlaneten"] = $laPlaneten;
 
    return fromTemplate("planeten.tpl",$laTplExport);
}

function showFlotten($asCoords = null)
{
    global $_SHIP;
    
    $ldbo = gigraDB::db_open();
    if($asCoords == null)
        $asCoords = $_SESSION["coords"];
    $f = getForschung();
    
    //is that mine?
    $laRow = $ldbo->getOne("SELECT 1 FROM planets WHERE coords = '$asCoords' AND owner = '{$_SESSION["uid"]}'");
    if(!$laRow)
        die("no fleet for not your planet");
    $laSchiffe = read_schiffe($asCoords);
    
    //datenaufbereiten
    $laS2 = array();
    foreach($laSchiffe as $lsID => $liCount)
    {
        //$liSpeed = $_SHIP[$lsID][11] * sqrt($f['f'.$_SHIP[$lsID][13]] / 10 + 1);
        $liSpeed = $_SHIP[$lsID][11] * (1 + ($f['f'.$_SHIP[$lsID][13]] / 10));
        
        
        $liConsum = $_SHIP[$lsID][12];
        $liCapa = $_SHIP[$lsID][10] * ((20 + $f['f10'])/20);
        
        $laS2[$lsID] = array("count" => $liCount, "speed" => $liSpeed, "consum" => $liConsum, "capa" => $liCapa);
    }
    
    //einmal meine coords bitte, alle, mit kraeutersosse ohne zwiebeln, zum mitnehmen
    $ldbo->query("SELECT coords FROM planets WHERE owner = '{$_SESSION["uid"]}' ORDER BY coords ASC");
    $laCoords = array();
    while($laRow = $ldbo->fetch())
        $laCoords[$laRow[0]] = coordFormat($laRow[0]);
    
    $lsSelected = isset($_POST['coords']) ? $_POST["coords"] : $_SESSION["coords"];
    
    $laRow = $ldbo->getOne("SELECT p.coords, p.pname, p.pbild, u.name FROM planets p LEFT JOIN users u ON p.owner = u.id  WHERE p.coords = '{$asCoords}'");
    $laRow["pbild"] = str_replace(".gif",".png",$laRow["pbild"]);
    
    $laLocalRes = read_res($asCoords);
    
    //Targets
    $laTargets = array();
    $ldbo->query("SELECT coords,comment FROM targets WHERE uid = '".Uid()."'");
    while($laTRow = $ldbo->fetch())
        $laTargets[$laTRow['coords']] = coordFormat($laTRow['coords']) . " - " . $laTRow["comment"];
    
    return fromTemplate("flotten.tpl",array("laTargets" => $laTargets, "laPlanet" => $laRow, "laSchiffe" => $laS2, "laCoords" => $laCoords,"lsSelected" => $lsSelected,"coAr" => explode(":",$lsSelected), "laRes" => $laLocalRes));
}
function showGalaxy($asCoords)
{
    $lodb = gigraDB::db_open();
    
    $laCoordParts = explode(":",$asCoords);
    $lsSystem = keepChars(array("0","1","2","3","4","5","6","7","8","9",":"),$laCoordParts[0] .":". $laCoordParts[1]);
    
    $laPositions = array();
    
    $x = $lodb->getOne("SELECT maxp FROM maxplanets WHERE sys='$lsSystem'");
	if($x == false)
	{
	   $maxp = mt_rand(3,20);
	   $lodb->query("INSERT INTO maxplanets SET maxp='$maxp', sys='$lsSystem'");
	}
	else
		$maxp = $x[0];
        
    for($i = 1; $i<=$maxp;$i++)
    {
        $lsCoords = "$lsSystem:$i:1";
        
        //getTF($lsCoords);
        $lsQuery = "SELECT p.coords, p.pname, p.pbild, p.dia, p.destructed, u.id as uid, u.name, up.rank, u.lastclick, up.pgesamt, u.allianz as allyid, a.tag, r1 as tf1, r2 as tf2, r3 as tf3, r4 as tf4,(SELECT COUNT(*) FROM itemqueue si WHERE si.coords = p.coords AND (r1 + r2 + r3 + r4) > 0) as pendingCount FROM planets p LEFT JOIN rohstoffe r ON r.coords = CONCAT(SUBSTRING_INDEX(p.coords,':',3),':3') LEFT JOIN users u ON p.owner = u.id LEFT JOIN v_punkte up ON u.id = up.uid LEFT JOIN allianz a ON u.allianz = a.id WHERE p.coords = '{$lsCoords}'";
        $laRow = $lodb->getOne($lsQuery);
        
        if($laRow['pendingCount'] > 0)
        {
            $laReadRes = read_res($lsCoords,true);
            
            $laRow['tf1'] = $laReadRes['tf1'];
            $laRow['tf2'] = $laReadRes['tf2'];
            $laRow['tf3'] = $laReadRes['tf3'];
            $laRow['tf4'] = $laReadRes['tf4'];
            
            //$laRow['pname'] .= " ({$laRow['pendingCount']})";
        }
        
        
        
        if(!$laRow)
        {
            #echo "$lsCoords niemand da <br>";
            $laPositions[$i] = $lsCoords;   
        }
        else
        {
            if($laRow['destructed'] == 1)
                $laPositions[$i] = -1;
            else if($laRow["uid"] == '')
                $laPositions[$i] = $lsCoords;
            else
            {
                //Nun gehts los
                $laRow["pbild"] = str_replace(".gif",".png",$laRow["pbild"]);
                $laRow["coAr"] = explode(":",$laRow["coords"]);
                $laRow["myPlanet"] = $laRow['uid'] == Uid();
                $laPositions[$i] = $laRow;
                
                //Mond
                $laRow2 = $lodb->getOne("SELECT p.dia,tf1,tf2,tf3,tf4 FROM planets p LEFT JOIN rohstoffe r ON p.coords = r.coords WHERE p.coords = '$lsSystem:$i:2'");
                if(is_array($laRow2))
                {
                    $laPositions[$i]["mond"] = $laRow2;
                    //TFs mit anzeigen
                    $laPositions[$i]['tf1'] += $laRow2['tf1'];
                    $laPositions[$i]['tf2'] += $laRow2['tf2'];
                    $laPositions[$i]['tf3'] += $laRow2['tf3'];
                    $laPositions[$i]['tf4'] += $laRow2['tf4'];
                }
                
            }
        }
    }
    
    $laParts = explode(":",$asCoords);
    $lsRangeCoords = count($laParts) < 4 ? "$laParts[0]:$laParts[1]:1:1" : $asCoords;
    
    $lbInRange = (coordType($_SESSION['coords']) == 2 and checkSensorRange($lsRangeCoords));
        
    
    return fromTemplate("galaxy_part.tpl",array("laPos" => $laPositions, "inRange" => $lbInRange));
}

function showFleetList()
{
    $laTplExport = array();
    $lodb = gigraDB::db_open();
    
    $lsQuery =  "SELECT id, tback, '' as parentfleet FROM flotten LEFT JOIN planets ON flotten.toc = planets.coords WHERE userid = '{$_SESSION["uid"]}' OR owner = '{$_SESSION["uid"]}' AND tsee < UNIX_TIMESTAMP() UNION ALL ".
                "SELECT f2.id, f2.tback, '' as parentfleet FROM flotten f INNER JOIN flotten f2 ON f.id = f2.parentfleet AND f2.parentfleet > '' WHERE f.typ = 'aks_lead' AND f.userid = '{$_SESSION["uid"]}' AND f2.tsee < UNIX_TIMESTAMP() UNION ALL ".
                "SELECT f2.id, f2.tback,f2.parentfleet FROM flotten f1 INNER JOIN flotten f2 ON f1.parentfleet = f2.parentfleet AND f2.parentfleet > ''  WHERE f1.typ = 'aks' AND f1.userid = '{$_SESSION["uid"]}' AND f2.tsee < UNIX_TIMESTAMP() ".
                "ORDER BY tback ASC";
    $laFids = array();
    $lodb->query($lsQuery);
    while($laRow = $lodb->fetch())
    {
        if(!in_array($laRow[0],$laFids))
        {
            $laFids[] = $laRow[0];
            if(isset($laRow[2]) && !empty($laRow[2]) && !in_array($laRow[2],$laFids))
                $laFids[] = $laRow[2];
        }
    }
    
    $laTplExport["laFids"] = $laFids;
    
    return fromTemplate("fleet_list.tpl",$laTplExport);
}

function showFleetInfo($fid,$abSensor = false)
{
    global $_SHIP;
    $lodb = gigraDB::db_open();
    $laTplExport = array();
    
    $laRow = $lodb->getOne("SELECT userid,forschung.f, fromc,toc,typ,schiffe,tthere,thold,tback,flytime,tsee,p1.pbild as frombild, p2.pbild as tobild,(SELECT name FROM users WHERE id = p1.owner) AS fromuname, (SELECT name FROM users WHERE id = p2.owner) AS touname,load1,load2,load3,load4 FROM flotten JOIN planets p1 ON flotten.fromc = p1.coords LEFT JOIN planets p2 ON flotten.toc = p2.coords LEFT JOIN forschung ON flotten.userid = forschung.uid WHERE id = '$fid'");
    if(!$laRow)
        return '';
    $laTplExport = $laRow;//ersma allet rin
    
    $blMyFleet = false;
    if($laRow["userid"]==$_SESSION["uid"])
        $blMyFleet = true;
    //sichtbare flotte?
    $liOneWay = $blMyFleet ? 0 : 1;
    $blVisible = true;
    
    $lsColor = "";
    switch($laRow['typ'])
    {
        default:
        case "trans":
            $lsColor = '#00FF00';
            break;
        case 'kolo':
            $lsColor = '#1B60E0';
            $liOneWay = 1;
            break;
        case 'ag':
        case 'ag_p':
        case 'aks':
        case 'aks_lead':
        {
            $lsColor = $blMyFleet ? '#FFEA00' : '#FF0000';
            break;
        }
        case 'stat':
            $lsColor = '#00FFBF';
            $liOneWay = 1;
            break;
        case 'spio':
            $lsColor = '#FF9D00';
            break;
        case 'inva':
            $lsColor = '#8B00D1';
            break;
        case 'recy':
            $lsColor = '#BBFF00';
            if(!$blMyFleet)
                $blVisible = false;
            break;
        case 'dest':
            $lsColor = '#545454';
            break;            
    }
    
    
    //zurueckfliegende flotten aus
    if(!$blMyFleet && $laRow["tthere"] == 0)
        $blVisible = false;
    
    if($abSensor)
    {
        $blVisible = true;
        if($laRow['typ'] == 'stat' && $laRow["tthere"] == 0)
            $blVisible = false;
    }
    
    $laSchiffe = ikf2array($laRow['schiffe']);
    /*
    if(isset($laSchiffe[6]) && !$blMyFleet && !$abSensor)
    {
        if(count($laSchiffe) == 1)
        {
            $laMyF = getForschung(Uid());
            $laHisF = ikf2array($laRow['f']);
            $liMySpio = isset($laMyF['f8']) ? $laMyF['f8'] : 0;
            $liHisSpio = isset($laHisF['f8']) ? $laHisF['f8'] : 0;
            
            $liSpioDiff = max(0,$liMySpio - $liHisSpio);
            $liDifferTime = max(60,300 * $liSpioDiff);
      	    if($laRow['tthere'] - $liDifferTime >= time())
	  		    $blVisible = false;
        }
        unset($laSchiffe[6]);
    }
    
    */
    
    if(!$blVisible)
        return '';
    
    //Schiffe
    
    $laTplExport["schiffe"] = $laSchiffe;
    $laTplExport["myfleet"] = $blMyFleet;
    $laTplExport["sensor"] = $abSensor;
    $laTplExport["oneway"] = $liOneWay;
    $laTplExport["color"] = $lsColor;
    $laTplExport["fid"] = uniqid();
    $laTplExport["realfid"] = $fid;
    $laTplExport["mission"] = $laRow["typ"];
    $laTplExport["fromc"] = $laRow["fromc"];
    if(coordType($laRow['fromc']) == 2)
        $laRow["frombild"] = "mond.png";
    else if(coordType($laRow['fromc']) == 3)
        $laRow["frombild"] = "debris.png";
    $laTplExport["frombild"] = str_replace(".gif",".png",$laRow["frombild"]);
    $laTplExport["fromuname"] = $laRow["fromuname"];
    $laTplExport["toc"] = $laRow["toc"];
    if($laRow["tobild"] == '')
        $laRow["tobild"] = "kein_planet.png";
    if(coordType($laRow['toc']) == 2)
        $laRow["tobild"] = "mond.png";
    else if(coordType($laRow['toc']) == 3)
        $laRow["tobild"] = "debris.png";
    $laTplExport["tobild"] = str_replace(".gif",".png",$laRow["tobild"]);
    $laTplExport["touname"] = $laRow["touname"];
    $laTplExport["lsTThere_formated"] = date("d.m.Y H:i:s",$laRow["tthere"]);
    $laTplExport["lsTBack_formated"] = date("d.m.Y H:i:s",$laRow["tback"]);
    $laTplExport['res1'] = $laRow['load1'];
    $laTplExport['res2'] = $laRow['load2'];
    $laTplExport['res3'] = $laRow['load3'];
    $laTplExport['res4'] = $laRow['load4'];
    
    
    return fromTemplate("fleet_detail.tpl",$laTplExport);   
}


function showPlanetInfo($coords)
{
    $lodb = gigraDB::db_open();
    $laTplExport = array();
    
    $lsQuery = "SELECT p.coords, p.pname, p.pbild, p.dia, u.id as uid, u.name, up.rank, u.lastclick, up.pgesamt, u.allianz as allyid, a.tag FROM planets p LEFT JOIN rohstoffe r ON p.coords = r.coords LEFT JOIN users u ON p.owner = u.id LEFT JOIN v_punkte up ON u.id = up.uid LEFT JOIN allianz a ON u.allianz = a.id WHERE p.coords = '{$coords}'";
    $laRow = $lodb->getOne($lsQuery);
    
    //Bild austauschen
    if(coordType($coords) == 2)
        $laRow["pbild"] = "mond.png";
    else if(coordType($coords) == 3)
    {
            $laRow["pbild"] = "debris.png";
            $laRow['pname'] = "";
    }        
    $laRow["pbild"] = str_replace(".gif",".png",$laRow["pbild"]);
    
    $laTplExport = $laRow;
    return fromTemplate("planet_info.tpl",$laTplExport);
}


function showKB($aaKampf,$asFromC,$asToC,$aiTime,$abHide = false, $asTitle = '')
{
      $laTplExport = array();
      
      $laTplExport["k"] = $aaKampf;
      $laTplExport["ks"] = $aaKampf["kampf"];
      
      $laTplExport["fromc"] = $asFromC;
      $laTplExport["asToC"] = $asToC;
      $laTplExport["date"] = date("d.m.Y",$aiTime);
      $laTplExport["time"] = date("H:i:s",$aiTime);
      $laTplExport["hide"] = $abHide;
      $laTplExport['title'] = $asTitle == '' ? l('kb_kb') : strip_tags($asTitle);
      
      
      
      
      return fromTemplate("kb.tpl",$laTplExport);
}
function getAngrriffSperre()
{
    global $_ACTCONF;
    
    return $_ACTCONF['angriffsperre'];
}
function showNotifies()
{
    $laList = array();
    
    if(isInDeletion(Uid()) > 0)
        $laList[] = l("notify_acc_delete",date("d.m.Y",isInDeletion(Uid())),date("H:i:s",isInDeletion(Uid())));
    if(checkUMOD(Uid()))
    {
        $laRow = gigraDB::db_open()->getOne("SELECT umod FROM users WHERE id = '" . Uid() . "'");
        $liUntil= $laRow[0] + (3600 * 48);  
        $laList[] = l("notify_umod_until",date("d.m.Y",$liUntil),date("H:i:s",$liUntil));
        
    }
    if(getAngrriffSperre() > time())
    {
        $laList[] = l('asperre',date("d.m.Y",getAngrriffSperre()),date("H:i:s",getAngrriffSperre()));
    }
    if(!isActivated())
        $laList[] = l('reg_not_activated');
    
    if($laPlans = whereAttacked())
    {
        foreach($laPlans as $k => $v)
            $laPlans[$k] = coordFormat($v,'change');
        $lsAttackText = "";
        if(count($laPlans) > 1)
        {
            
            $lsLastPlan = array_pop($laPlans);
            $lsPlanList = join(", ",$laPlans);
            
            $lsPlanText = l('attack_on',$lsPlanList." ".l('attack_and')." ".$lsLastPlan);
        }
        else
            $lsPlanText = l('attack_on',$laPlans[0]);
        
        $laList[] = $lsPlanText;
    }
    
    
    return fromTemplate("notifybox.tpl",array("text" => join("<br>",$laList)));
}

function showSpio($asID)
{
    $laRow = gigraDB::db_open()->getOne("SELECT toc,b FROM bericht WHERE id = '{$asID}'");
    
    if(!$laRow)
        return "error";    
    $laData = unserialize($laRow['b']);
    
    $laTplExport['spyData'] = $laData['spio'];
    $laTplExport['chance'] = $laData['chance'];
    $laTplExport['coords'] = $laRow['toc'];
    
    
    return fromTemplate("spiobericht.tpl",$laTplExport);
}

function showBonusPacks()
{
    global $_BONUSPACKS;
    $laTplExport = array();
    
    $laItems = getBonusItems();
    foreach($_BONUSPACKS as $id => $unbrauchbarescheisse)
        if(!isset($laItems[$id])) $laItems[$id] = 0; 
    $laTplExport['bonusitems'] = $laItems;
    $laTplExport['bonuspacks'] = $_BONUSPACKS;
    
    return fromTemplate("bonus.tpl",$laTplExport);
}

function question($id,$asText)
{
    return '<a id="question_'.$id.'" href="ask.php?id='.$id.'&width=680&height=300&modal=true" class="thickbox">'.$asText.'</a>';   
}


function showTutorialList()
{
    $lodb = gigraDB::db_open();
    
    $lsPage = $_SERVER['PHP_SELF'];
    
    $lodb->query("SELECT jquery_path,html FROM tutorial WHERE onpage = '{$lsPage}'");
    $laTuts = array();
    while($laRow = $lodb->fetch())
        $laTuts[] = array($laRow[0],$laRow[1]);
 
    
    return fromTemplate("tutList.tpl",array("list" => $laTuts));
}

function showMyTargets()
{
     $lodb = gigraDB::db_open();
     
     $lsHTML = "";
     $lodb->query("SELECT coords,comment FROM targets WHERE uid = '".Uid()."'");
     while($laTRow = $lodb->fetch())
     {
         $lsHTML .= "<tr>".PHP_EOL;
         $lsHTML .= "<td>".coordFormat($laTRow['coords'])." - ".$laTRow['comment']."</td>".PHP_EOL;
         $lsHTML .= "<td><a href=\"javascript:deleteTarget('".$laTRow['coords']."')\"><div class='global_cancel'></div></a></td>".PHP_EOL;
         $lsHTML .= "</tr>".PHP_EOL;
     }
     
     return $lsHTML;
}
function showInfo($asID,$ajax = false)
{
    global $_BAU,$_FORS,$_SHIP, $_VERT,$_ACTCONF;
    
    $lodb = gigraDB::db_open();
    $laPlanRow = $lodb->getOne("SELECT temp,dia FROM planets WHERE coords = '{$_SESSION['coords']}'");
    
    $laTemps = getMinMaxTemp($laPlanRow[0],$laPlanRow[1]);
    
    $k = getBuildings($_SESSION['coords']);
    $f = getForschung(Uid());
    
    
    $laTplExport = array();
    
    $lbObjNotFound = false;
    
    $lsID = strtolower($asID);
    
    
    $lsTyp = substr($lsID,0,1);
    $liID = substr($lsID,1);
    
    $CONST = array();
    switch($lsTyp)
    {
        case "b":
            $CONST = $_BAU;
            break;
        case "f":
            $CONST = $_FORS;
            break;
        case "s":
            $CONST = $_SHIP;
            break;
        case "v":
            $CONST = $_VERT;
            break;
    }
    
    if(!isset($CONST[$liID]))
        $lbObjNotFound = true;
    else
    {
        $CONST = $CONST[$liID];
        $laTplExport['obj_typ'] = $lsTyp;
        $laTplExport['obj_id'] = $liID;
        $laTplExport["img"] = "design/items/{$lsID}.gif";
        $laTplExport["name"] = l("item_".$lsID);
        $laTplExport["text"] = l("itemtext_".$lsID);
        
        //Produktion + schildgeb
        if(in_array($lsID,array("b3","b4","b5","b6","b7","b8","b15","b16")))
        {
            $laProdList = array();
            $laConsList = array();
            
            $lsTmp = str_replace("b","k",$lsID);//b1  => k1
            $liStart = !isset($k[$lsTmp]) || $k[$lsTmp] <= 3 ? 1 : $k[$lsTmp] - 2; 
            $liStart = isset($_REQUEST['mineStart']) ? (int)$_REQUEST['mineStart'] : $liStart;
            $laTplExport['start'] = $liStart;
            $liRate = $ajax ? 50 : 10;
            
            for($i = $liStart; $i < $liStart+$liRate;$i++)
            {
                switch($liID)
                {
                    case 3:
                        $liProd = getEisenProd($i) * $_ACTCONF['speed_res'];
                        $lsRes = "res1";
                        $liEner = getEnergieVerbrMinen($liProd);
                        break;
                    case 4:
                        $liProd = getTitanProd($i) * $_ACTCONF['speed_res'];
                        $lsRes = "res2";
                        $liEner = getEnergieVerbrMinen($liProd);
                        break;
                    case 5: 
                        $liProd = getWasserProd($i,$laTemps[1]) * $_ACTCONF['speed_res'];
                        $lsRes = "res3";
                        $liEner = getEnergieVerbrMinen($liProd);
                        break;
                    case 6: 
                        $liProd = getChemFabVerbr($i) * $_ACTCONF['speed_res'];
                        $lsRes = "res4";
                        $liEner = getEnergieVerbrChemFab($liProd);
                        break;
                    case 7:
                        $liProd = getErwChemFabVerbr($i) * $_ACTCONF['speed_res'];
                        $lsRes = "res4";
                        $liEner = getEnergieVerbrErwChemFab($liProd);
                        break;
                    case 8:
                        $liProd = getEnergieKW($i) * $_ACTCONF['speed_res'];
                        $lsRes = "energy";
                        break;
                    case 15:
                        $liTech = isset($f['f15']) ? $f['f15'] : 0;
                        $liProd = shieldPower($i,$liTech);
                        $lsRes = "energy";
                        break;
                    case 16:
                        $liTech = isset($f['f6']) ? $f['f6'] : 0;
                        $liProd = shieldEnergyPerHour($i,$liTech);
                        $lsRes = "energy";
                        break;
                }
                
                $liTeiler = 1;
                if($liID == 6)
                    $liTeiler = 5;
                else if($liID == 7)
                    $liTeiler = 2;
                
                $laProdList[$i] = array("r" => $lsRes , "prod" => $liProd/$liTeiler);
                if($liEner > 0)
                    $laConsList[$i]["energy"] = $liEner;
                if($liID == 6 || $liID == 7)
                    $laConsList[$i]["res3"] = $liProd;
                
    
            }
            $laTplExport["prodlist"] = $laProdList;
            $laTplExport["conslist"] = $laConsList;
            
        }
        //Laderaum
        if(in_array($lsID,array("b9","b10","b11","b12")))
        {
            $laCapa = array();
            
            $lsTmp = str_replace("b","k",$lsID);//b1  => k1
            $liStart = !isset($k[$lsTmp]) || $k[$lsTmp] <= 3 ? 1 : $k[$lsTmp] - 2; 
            
            for($i = $liStart; $i < $liStart+10;$i++)  
                $laCapa[$i] = sf($i);
            
            $laTplExport["rescapa"] = $laCapa;
        }
        if($lsID == 'b20')
        {
            $laTplExport["sensorrange"] = getSensorRange();
        }
        //Schiffe
        if($lsTyp == "s" || $lsTyp == "v")
        {
            //Rapidfire
            //ersma das einfache, gegen wen
            $laRFAgainst = $CONST['rf'];//mhm, das war ja wirklich einfach
            $laRFVAgainst = $CONST['rfv'];
            //nun aber das komplizierte^^
            $laRFFrom = array();
            $laRFVFrom = array();
            foreach($_SHIP as $id => $data)
                if(isset($data[$lsTyp == "v" ? "rfv" : "rf"][$liID]))
                    $laRFFrom[$id] = $data[$lsTyp == "v" ? "rfv" : "rf"][$liID];//mhm....doch net soo schwer^^
            foreach($_VERT as $id => $data)
                if(isset($data[$lsTyp == "v" ? "rfv" : "rf"][$liID]))
                    $laRFVFrom[$id] = $data['rf'][$liID];//mhm....doch net soo schwer^^
            
            $laTplExport["rf_against"] = $laRFAgainst;
            $laTplExport["rfv_against"] = $laRFVAgainst;
            $laTplExport["rf_from"] = $laRFFrom;
            $laTplExport["rfv_from"] = $laRFVFrom;
            
            //Rest werte
            $f['f5'] = isset($f['f5']) ? $f['f5'] : 0;
            $f['f6'] = isset($f['f6']) ? $f['f6'] : 0;
            $f['f9'] = isset($f['f9']) ? $f['f9'] : 0;
            
            $laTplExport['ang'] = $CONST[8];
            $laTplExport['ang_plus'] = ($CONST[8] * (1 + $f['f5'] / 10)) - $CONST[8];
            $laTplExport['deff'] = $CONST[9];
            $laTplExport['deff_plus'] = ($CONST[9] * (1 + $f['f6'] / 10)) - $CONST[9];
            $liStruc = ($CONST[1]+$CONST[2])/10;;
            $laTplExport['struc'] = $liStruc;
            $laTplExport['struc_plus'] = ($liStruc * (1 + $f['f9'] / 10)) - $liStruc;
            
            if($lsTyp == "s")
            {
                $laTplExport['capa'] = $CONST[10];
                $laTplExport['speed'] = $CONST[11];
                $laTplExport['consum'] = $CONST[12];
                $laTplExport['engine'] = l('item_f'.$CONST[13]);
                if($liID == 15)
                {
                       $laTplExport['energie'] = getEnergieSat(1,$laPlanRow['temp']);
                }
            }
        }
    }
    
    if($ajax)
    {
        $laTplExport["ajax"] = true;
        return fromTemplate("info.tpl", $laTplExport);
    }
    else
        buildPage("info.tpl", $laTplExport);
}
?>
