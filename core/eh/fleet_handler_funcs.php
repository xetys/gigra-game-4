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



/**
* Schickt Flotte zurueck
*/
function eh_procback($row)
{
    fleetArriveLog($row['userid'],$row['schiffe'],$row['tthere'],$row['tback'],$row['fromc'],$row['toc'],array((int)$row['load1'],(int)$row['load2'],(int)$row['load3'],(int)$row['load4']),$row['typ']);
    
    $db = gigraDB::db_open();
    if(($row['load1']+$row['load2']+$row['load3']+$row['load4'])>0)  //Was geladen:
    {
      //Umformen
      $res = array();
      $res[0] = $row['load1'];
      $res[1] = $row['load2'];
      $res[2] = $row['load3'];
      $res[3] = $row['load4'];
      //Einzahlen
      add_res($res,$row[2]);
    }
    
    //Schiffe einzahlen
    add_schiffe($row['fromc'],ikf2array($row['schiffe']));
    
    //Flotte entfernen
    $db->query("DELETE FROM flotten WHERE id='$row[0]'");
    //Nachricht: Schiffe sind zurueckgekehrt
    if(!isset($res))
        $msg['x'] = 12;
    else
    {
      $msg['x'] = 11;
      $msg[0] = $res[0];
      $msg[1] = $res[1];
      $msg[2] = $res[2];
      $msg[3] = $res[3];
    }
    if($row['typ'] != "spio")
        send_cmd_msg_eh($row[1],$row[2],$msg,$row[6]);
    //echo "Flotte $row[0] ist nach $row[2] zurueckgekehrt ".((isset($res))?"(Ladung: $res[0],$res[1],$res[2],$res[3])":"(Ohne Ladung)")."\n";
    ehLog("Flotte $row[0] ist nach $row[2] zurueckgekehrt ".((isset($res))?"(Ladung: $res[0],$res[1],$res[2],$res[3])":"(Ohne Ladung)"),"green");
}


function handleFleetEvent($row,$simulate = false)
{
    $lodb = gigraDB::db_open();
    //Logging
    fleetArriveLog($row['userid'],$row['schiffe'],$row['tthere'],$row['tback'],$row['fromc'],$row['toc'],array((int)$row['load1'],(int)$row['load2'],(int)$row['load3'],(int)$row['load4']),$row['typ']);
    
    //Angriff
    if($row[4] == 'ag' || $row[4] == 'ag_p' || $row[4] == 'inva' || $row[4] == 'dest' || $row[4] == 'aks_lead') //Angriff oder Angriff+Pluendern, Invasion oder Vernichten
    {
      StartBattle($row,$simulate);
      ehLog("Starte Angriff [{$row["fromc"]}] -> [{$row["toc"]}]","yellow");
    }
    else if($row[4] == 'kolo') //Kolonisation
    {
      eh_proc_kolo($row);
      ehLog("Starte Kolonisierung [{$row["fromc"]}] -> [{$row["toc"]}]","yellow");
    }
    else if($row[4] == 'trans') //Transport
    {
      eh_proc_trans($row);
      ehLog("Starte Transport [{$row["fromc"]}] -> [{$row["toc"]}]","yellow");
    }
    else if($row[4] == 'stat')  //Stationieren
    {
      eh_proc_stat($row); 
      ehLog("Starte Stationierung [{$row["fromc"]}] -> [{$row["toc"]}]","yellow");
    }
    else if($row[4] == 'recy') //Recycle
    {
      eh_proc_recy($row); 
      ehLog("Starte Abbau [{$row["fromc"]}] -> [{$row["toc"]}]","yellow");
    }
    else if($row[4] == 'spio') //Spionage
    {
      eh_proc_spio($row); 
      ehLog("Starte Spionage [{$row["fromc"]}] -> [{$row["toc"]}]","yellow");
    }
    else if($row[4] == 'asteroid')
    {
      eh_proc_asteroid($row); 
      ehLog("Starte Ricos Hirnsinnige Schiesse wenn die jmd iwann mal brauchen wird [{$row["fromc"]}] -> [{$row["toc"]}]","yellow");
    }
    else if($row[4] == 'hold')
    {
    	$lodb->query("UPDATE flotten SET tthere = '0' WHERE id = '$row[0]'");
        ehLog("Starte Halten [{$row["fromc"]}] -> [{$row["toc"]}]","yellow");
    }
    else if($row[4] == 'aks')
    {
    	//my_query("UPDATE flotten SET tthere = '0' WHERE id = '$row[0]'");
    }
    else
    {
      ehLog("Ignoriere Unbekannten Flottentyp [{$row["fromc"]}] -> [{$row["toc"]}]","red");
    }
    
    
    //sind wir hier angekommen? dann is alles tuttn
    ehLog("Mission Abgeschlossen","green");
}



 /**
*Simuliert Angriff (+evtl. Invasion)
*/function StartBattle($row,$simulate = false)
{
    global $_SHIP, $_VERT, $_ACTCONF;
    
    $lodb = gigraDB::db_open();
    
    //Variablen vorbereiten
    $laAttIDs = array();
    $laDeffIDs = array();
    
    $laAttFIDs = array();
    $laDeffFIDs = array();
    
    $laAttShips = array();
    $laDeffShips = array();
    $laDeffTowers = array();
    
    $laAttTechs = array();
    $laDeffTechs = array();
    
    $laDeffBuildings = array();
    
    $laAttData = array();
    $laDeffData = array();
    
    $laAttPunkte = array();
    $laDeffPunkte = array();
    
    //Planeten checken
    $lsTargetCoords = $row["toc"];
    
    $laRow = $lodb->getOne("SELECT owner FROM planets WHERE coords = '".$lsTargetCoords."'");
    if(!$laRow)
    {
        //nix tun
    }
    else
    {
        $lsOwner = $laRow[0];
        
        $laDeffIDs[1] = $lsOwner;
        $laDeffFIDs[1] = false;//fliegt nicht
        
        
        //Alle Angreifer einlesen
        $laAttIDs[1] = $row['userid'];
        $laAttFIDs[1] = $row['id'];
        $laAttShips[1] = ikf2array($row['schiffe']);
        $laAttTechs[1] = getForschung($laAttIDs[1]);
        //weitere infos
        $laRow = $lodb->getOne("SELECT users.name,krieg_treffer,IF(kampf_until > UNIX_TIMESTAMP(),kampf_percent,0) as kampf_percent,pgesamt,(flotten+verteidigung) as pmil FROM users "
                              ."LEFT JOIN skills ON users.id = skills.uid "
                              ."LEFT JOIN user_gigron ON user_gigron.uid = users.id "
                              ."LEFT JOIN v_punkte ON v_punkte.uid = users.id "
                              ."WHERE users.id = '{$laAttIDs[1]}'");
        $laAttTechs[1]["klevel"] = $laRow["krieg_treffer"];
        
        $laAttPunkte[1] = $laRow['pmil'];
        
        $liBonus = (1 + ($laRow["kampf_percent"] / 100));
        $laAttTechs[1]["bonus"] = $liBonus;
        
        
        $laAttData[1] = array(
                    "name" => $laRow["name"],
                    "coords" => $row["fromc"],
                    "f" => $laAttTechs[1],
                    "bonus" => $laAttTechs[1]["bonus"]
                );
        
        //Mitflieger
        $lodb->query("SELECT flotten.id,userid,schiffe,fromc,krieg_treffer,IF(kampf_until > UNIX_TIMESTAMP(),kampf_percent,0) as kampf_percent,users.name, pgesamt,(flotten+verteidigung) as pmil  "
                    ."FROM flotten "."LEFT JOIN  skills ON flotten.userid = skills.uid "
                    ."LEFT JOIN user_gigron ON flotten.userid = user_gigron.uid "
                    ."LEFT JOIN users ON flotten.userid = users.id "
                    ."LEFT JOIN v_punkte ON v_punkte.uid = users.id "
                    ."WHERE parentfleet = '{$laAttFIDs[1]}' AND parentfleet != ''");
        while($laRow = $lodb->fetch("assoc"))
        {
            $liNextId = count($laAttFIDs)+1;
            $laAttIDs[$liNextId] = $laRow['userid'];
            $laAttFIDs[$liNextId] = $laRow['id'];
            $laAttPunkte[$liNextId] = $laRow['pmil'];
            $laAttShips[$liNextId] = ikf2array($laRow['schiffe']);
            $laAttTechs[$liNextId] = getForschung($laAttIDs[$liNextId]);
            
            $laAttTechs[$liNextId]["klevel"] = $laRow["krieg_treffer"];
            $liBonus = (1 + ($laRow["kampf_percent"] / 100));
            
            $laAttTechs[$liNextId]["bonus"] = $liBonus;
            
            $laAttData[$liNextId] = array(
                    "name" => $laRow["name"],
                    "coords" => $laRow["fromc"],
                    "f" => $laAttTechs[$liNextId],
                    "bonus" => $laAttTechs[$liNextId]["bonus"]
                );
        }
        
        
        //Alle deffer einlesen
        $laDeffShips[1] = read_schiffe($lsTargetCoords);
        $laDeffTowers[1] = read_vert($lsTargetCoords);
        $laDeffTechs[1] = getForschung($laDeffIDs[1]);
        $laDeffBuildings[1] = getBuildings($lsTargetCoords);
        
        //weitere infos
        $laRow = $lodb->getOne("SELECT users.name,krieg_treffer,IF(kampf_until > UNIX_TIMESTAMP(),kampf_percent,0) as kampf_percent,pgesamt,(flotten+verteidigung) as pmil FROM users "
                              ."LEFT JOIN skills ON users.id = skills.uid "
                              ."LEFT JOIN user_gigron ON user_gigron.uid = users.id "
                              ."LEFT JOIN v_punkte ON v_punkte.uid = users.id "
                              ."WHERE users.id = '{$laDeffIDs[1]}'");
        $laDeffTechs[1]["klevel"] = $laRow["krieg_treffer"];
        $liBonus = (1 + ($laRow["kampf_percent"] / 100));
        $laDeffTechs[1]["bonus"] = $liBonus;
        
        $laDeffData[1] = array(
                    "name" => $laRow["name"],
                    "coords" => $row["toc"],
                    "f" => $laDeffTechs[1],
                    "bonus" => $laDeffTechs[1]["bonus"]
                );
        
        $laDeffPunkte[1] = $laRow['pmil'];
        
        //Halter
        $lodb->query("SELECT flotten.id,userid,schiffe,fromc,krieg_treffer,IF(kampf_until > UNIX_TIMESTAMP(),kampf_percent,0) as kampf_percent,users.name,pgesamt,(flotten+verteidigung) as pmil  "
                    ."FROM flotten "."LEFT JOIN  skills ON flotten.userid = skills.uid "
                    ."LEFT JOIN user_gigron ON flotten.userid = user_gigron.uid "
                    ."LEFT JOIN users ON flotten.userid = users.id "
                    ."LEFT JOIN v_punkte ON v_punkte.uid = users.id "
                    ."WHERE typ = 'hold' AND toc = '$lsTargetCoords' AND tthere = 0 AND thold > UNIX_TIMESTAMP()");
        while($laRow = $lodb->fetch("assoc"))
        {
            $liNextId = count($laDeffIDs)+1;
            $laDeffIDs[$liNextId] = $laRow['userid'];
            $laDeffFIDs[$liNextId] = $laRow['id'];
            
            $laDeffPunkte[$liNextId] = $laRow['pmil'];
            
            $laDeffShips[$liNextId] = ikf2array($laRow['schiffe']);
            $laDeffTechs[$liNextId] = getForschung($laDeffIDs[$liNextId]);
            
            $laDeffTechs[$liNextId]["klevel"] = $laRow["krieg_treffer"];
            
            $liBonus = (1 + ($laRow["kampf_percent"] / 100));
            $laDeffTechs[$liNextId]["bonus"] = $liBonus;
            
            $laDeffData[$liNextId] = array(
                    "name" => $laRow["name"],
                    "coords" => $laRow["fromc"],
                    "f" => $laDeffTechs[$liNextId],
                    "bonus" => $laDeffTechs[$liNextId]["bonus"]
                );
        }
        
        //AntiSchnorrerSystem :) wie hoch ist dein Kampfbeitrag?
        $laAttAProz = array();
        $liAttGesamt = 0;
        foreach($laAttShips as $liID => $laShips)
        {
            foreach($laShips as $liSID => $liCount)
            {
                if(!isset($laAttAProz[$liID]))
                    $laAttAProz[$liID] = $_SHIP[$liSID][8] * $liCount;
                else
                    $laAttAProz[$liID] += $_SHIP[$liSID][8] * $liCount;
                
                $liAttGesamt += $_SHIP[$liSID][8] * $liCount;
            }
        }
        //und nochmal :P
        foreach($laAttAProz as $liID => $liLocalAtt)
            $laAttAProz[$liID] = $liLocalAtt / $liAttGesamt;
        
        //Deffer
        $laDeffAProz = array();
        $liAttGesamt = 0;
        foreach($laDeffShips as $liID => $laShips)
        {
            foreach($laShips as $liSID => $liCount)
            {
                if(!isset($laDeffAProz[$liID]))
                    $laDeffAProz[$liID] = $_SHIP[$liSID][8] * $liCount;
                else
                    $laDeffAProz[$liID] += $_SHIP[$liSID][8] * $liCount;
                $liAttGesamt += $_SHIP[$liSID][8] * $liCount;
            }
        }
        //und nochmal :P
        foreach($laDeffAProz as $liID => $liLocalAtt)
            $laDeffAProz[$liID] = $liLocalAtt / $liAttGesamt;
                     
                
        
        
        //Kampf rechnen
        $liStartTime = microtime(true);
        $laBattleRet = battle($laAttTechs,$laAttShips,$laDeffBuildings,$laDeffTechs,$laDeffShips,$laDeffTowers);
        $liEndTime = microtime(true) - $liStartTime;
        
        
        
        $laReport = array();
        
        $laReport["atter_data"] = $laAttData;
        $laReport["deffer_data"] = $laDeffData;
        
        $laReport["kampf"] = $laBattleRet["runde"]; //Hier der gesammte Kampfprotokoll
        $laReport["winner"] = $laBattleRet["winner"];
        
        $lbWin = $laBattleRet["winner"] == "a";
        
        //Verluste berechnen + TF
        $laReport["a_lost"] = 0;
        $laReport["v_lost"] = 0;
        
        $laAttLost = array();
        $laDeffLost = array();
        
        $laTF = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
        $liTFSum = 0;
        
        //Gesamtkapa fuer spaetere Beutberechung
        $liKapa = 0;
        $laRepaired = array();
        $laTowers = array();
        
        $liKapaFaktor = isset($laAttTechs[1]["f10"]) ? 1 + ($laAttTechs[1]["f10"] * 0.1) : 1;
        
        foreach($laAttShips as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $lsID => $liCount)
            {
                if(!isset($laBattleRet["sa"][$liSpieler][$lsID]))
                    $liLost = $liCount;//Keine Schiffe dieser Klasse gefunden, die sind denn wohl tot
                else
                    $liLost = $liCount - $laBattleRet["sa"][$liSpieler][$lsID];
                
                //sicher is sicher
                if($liLost == $liCount)
                    unset($laBattleRet["sa"][$liSpieler][$lsID]);
                
                //Verluste
                $laReport["a_lost"] += ($_SHIP[$lsID][1] + $_SHIP[$lsID][2] + $_SHIP[$lsID][3] + $_SHIP[$lsID][4]) * $liLost;
                
                if(!isset($laAttLost[$liSpieler])) $laAttLost[$liSpieler] = 0;
                $laAttLost[$liSpieler] += ($_SHIP[$lsID][1] + $_SHIP[$lsID][2] + $_SHIP[$lsID][3] + $_SHIP[$lsID][4]) * $liLost / 1000;
                
                $laTF[1] += $_SHIP[$lsID][1] * $_ACTCONF["debris_faktors"][0] * $liLost;
                $liTFSum += $_SHIP[$lsID][1] * $_ACTCONF["debris_faktors"][0] * $liLost;
                $laTF[2] += $_SHIP[$lsID][2] * $_ACTCONF["debris_faktors"][1] * $liLost;
                $liTFSum += $_SHIP[$lsID][2] * $_ACTCONF["debris_faktors"][1] * $liLost;
                $laTF[3] += $_SHIP[$lsID][3] * $_ACTCONF["debris_faktors"][2] * $liLost;
                $liTFSum += $_SHIP[$lsID][3] * $_ACTCONF["debris_faktors"][2] * $liLost;
                $laTF[4] += $_SHIP[$lsID][4] * $_ACTCONF["debris_faktors"][3] * $liLost;
                $liTFSum += $_SHIP[$lsID][4] * $_ACTCONF["debris_faktors"][3] * $liLost;
                
                if($liSpieler == 1 && $liLost < $liCount && $lsID != 3) //Nur der anfuehrer beutet, schiffe die noch da sind, sonden beuten nicht
                    $liKapa += $_SHIP[$lsID][10] * $laBattleRet["sa"][$liSpieler][$lsID] * $liKapaFaktor;
            }
        }
        foreach($laDeffShips as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $lsID => $liCount)
            {
                if(!isset($laBattleRet["sv"][$liSpieler][$lsID]))
                    $liLost = $liCount;//Keine Schiffe dieser Klasse gefunden, die sind denn wohl tot
                else
                    $liLost = $liCount - $laBattleRet["sv"][$liSpieler][$lsID];
                
                //sicher is sicher
                if($liLost == $liCount)
                    unset($laBattleRet["sv"][$liSpieler][$lsID]);
                
                //Verluste
                $laReport["v_lost"] += ($_SHIP[$lsID][1] + $_SHIP[$lsID][2] + $_SHIP[$lsID][3] + $_SHIP[$lsID][4]) * $liLost;
                
                if(!isset($laDeffLost[$liSpieler])) $laDeffLost[$liSpieler] = 0;
                
                $laDeffLost[$liSpieler] += ($_SHIP[$lsID][1] + $_SHIP[$lsID][2] + $_SHIP[$lsID][3] + $_SHIP[$lsID][4]) * $liLost / 1000;
                
                //Reparatur von SolSats
                if($lsID == 15)
                {
                    $liRepaired = floor((rand(50,80) / 100) * $liLost);
                    if($liRepaired > 0)
                        $laRepaired[$lsID] = $liRepaired;
                    
                    if($liLost == $liCount)
                        $laBattleRet["sv"][$liSpieler][$lsID] = $liRepaired;
                    else
                        $laBattleRet["sv"][$liSpieler][$lsID] += $liRepaired;
                        
                    $liLost -= $liRepaired;
                }
                
                $laTF[1] += $_SHIP[$lsID][1] * $_ACTCONF["debris_faktors"][0] * $liLost;
                $liTFSum += $_SHIP[$lsID][1] * $_ACTCONF["debris_faktors"][0] * $liLost;
                $laTF[2] += $_SHIP[$lsID][2] * $_ACTCONF["debris_faktors"][1] * $liLost;
                $liTFSum += $_SHIP[$lsID][2] * $_ACTCONF["debris_faktors"][1] * $liLost;
                $laTF[3] += $_SHIP[$lsID][3] * $_ACTCONF["debris_faktors"][2] * $liLost;
                $liTFSum += $_SHIP[$lsID][3] * $_ACTCONF["debris_faktors"][2] * $liLost;
                $laTF[4] += $_SHIP[$lsID][4] * $_ACTCONF["debris_faktors"][3] * $liLost;
                $liTFSum += $_SHIP[$lsID][4] * $_ACTCONF["debris_faktors"][3] * $liLost;
            }
        }
        foreach($laDeffTowers as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $lsID => $liCount)
            {
                if(!isset($laBattleRet["vv"][$liSpieler][$lsID]))
                    $liLost = $liCount;//Keine Schiffe dieser Klasse gefunden, die sind denn wohl tot
                else
                    $liLost = $liCount - $laBattleRet["vv"][$liSpieler][$lsID];
                
                //sicher is sicher
                if($liLost == $liCount)
                    unset($laBattleRet["vv"][$liSpieler][$lsID]);
                
                //Verluste
                $laReport["v_lost"] += ($_VERT[$lsID][1] + $_VERT[$lsID][2] + $_VERT[$lsID][3] + $_VERT[$lsID][4]) * $liLost;
                
                //Reparatur
                $liRepaired = floor((rand(50,80) / 100) * $liLost);
                if($liRepaired > 0)
                    $laRepaired[$lsID] = $liRepaired;
                
                if($liLost == $liCount)
                    $laBattleRet["vv"][$liSpieler][$lsID] = $liRepaired;
                else
                    $laBattleRet["vv"][$liSpieler][$lsID] += $liRepaired;
                    
                $liLost -= $liRepaired;
                
                
                if($_ACTCONF["defense_to_debris"])
                {
                    $laTF[1] += $_VERT[$lsID][1] * $_ACTCONF["debris_faktors"][0] * $liLost;
                    $liTFSum += $_VERT[$lsID][1] * $_ACTCONF["debris_faktors"][0] * $liLost;
                    $laTF[2] += $_VERT[$lsID][2] * $_ACTCONF["debris_faktors"][1] * $liLost;
                    $liTFSum += $_VERT[$lsID][2] * $_ACTCONF["debris_faktors"][1] * $liLost;
                    $laTF[3] += $_VERT[$lsID][3] * $_ACTCONF["debris_faktors"][2] * $liLost;
                    $liTFSum += $_VERT[$lsID][3] * $_ACTCONF["debris_faktors"][2] * $liLost;
                    $laTF[4] += $_VERT[$lsID][4] * $_ACTCONF["debris_faktors"][3] * $liLost;
                    $liTFSum += $_VERT[$lsID][4] * $_ACTCONF["debris_faktors"][3] * $liLost;
                }
            }
        }
        if(count($laRepaired) > 0)
            $laReport['repaired'] = $laRepaired;
        //Mond
        $laReport["mond"] = 0;
        $laReport["mondchance"] = 0;
        
        
        
        if($liTFSum > 100000 && !$simulate)
        {
            $liMondChance = min(round($liTFSum,-5) / 100000,40);
    		$laReport["mondchance"] = $liMondChance;
    		$lsMoonCoords = substr($lsTargetCoords,0,-1) . "2"; //1:1:1:1 -> 1:1:1: -> 1:1:1:2
    		$laHasMoon = $lodb->getOne("SELECT coords FROM planets WHERE coords = '$lsMoonCoords'");
    		if(chanceDecide($liMondChance) && !$laHasMoon)
    		{
    			//Mond wird nun erstellt
    			$liDia = rand(200,249) * $liMondChance;
                createPlanet($laDeffIDs[1],$lsMoonCoords,$liDia);
                  
    		  	$laReport["mond"] = 1;
                
                //Mond mampft 30% des TFs
                $liTFLeftFaktor = 0.7;
                
                foreach($laTF as $tfKey => $tfVal)
                    $laTF[$tfKey] = $liTFLeftFaktor * $tfVal;
    		}
        }
        
        $laReport['tf'] = $laTF;
        
        if($simulate)
        {
            $liMondChance = min(round($liTFSum,-5) / 100000,40);
            $laReport["mondchance"] = $liMondChance;
            
            dbg("Berechnungszeit: $liEndTime");
            //BerichtID suchen
            do
            {
              $lsReportID = uniqid();
              $lodb->query("SELECT id FROM bericht WHERE id='$lsReportID'");
            }
            while($lodb->numrows() != 0);
            
            //Und rein damit
            $lodb->query("INSERT INTO bericht SET id='$lsReportID', time='$row[5]', fromc='$row[2]', toc='$row[3]', b='".serialize($laReport)."'");
            
            
            //Nur rechnen, nicht schreiben
            $lodb->query("INSERT INTO log SET uid = 'FLEETSIM', entry = 'Dauer: $liEndTime, KB:'$lsReportID");
            dbg("report: $lsReportID");
            $lodb->query("DELETE FROM events WHERE command = 'fleetSim'");
            return;
        }
        
        //$lsTFCoords = substr($row[3],0,-1)."1";
        //$lodb->query("UPDATE rohstoffe SET tf1=tf1+$laTF[1], tf2=tf2+$laTF[2], tf3=tf3+$laTF[3], tf4=tf4+$laTF[4] WHERE coords='$lsTFCoords'");
        
        addTF($row[3],$laTF[1],$laTF[2],$laTF[3],$laTF[4]);
        
        
        $laBeute = array(0,0,0,0);
        
        if($lbWin)
        {
            //Invasion
            $laReport["inva"] = 0;
            if(isset($laBattleRet['sa'][1][8]) && $laBattleRet['sa'][1][8] > 0 && $row['typ'] == "inva")
            {
                //Tjo... Planet uebernehmen(wenn es nicht der lezte ist)
                $laPlanetRow = $lodb->getOne("SELECT COUNT(*) FROM planets WHERE owner='".$laDeffIDs[1]."'");
                $liInvaChance = min($laBattleRet['sa'][1][8] / 10,80);
                if($laPlanetRow[0] > 1 && chanceDecide($liInvaChance))
                {
                    invadePlanet($lsTargetCoords,$laAttIDs[1]);
                    
                    $laReport['inva'] = 1;
                }
                else
                {
                    //Lezter Planet oder keine Wahrscheinlichkeit
                    $laReport['inva'] = -1;
                }
                $laReport['inva_chance'] = $liInvaChance;
            }
            else
                $laReport['inva'] = 0;
            
            //Zerstörung
            $laReport["dest"] = 0;
            $liPlanetPercent = $liFleetDeathPercent = $liShipDeath = $liPlanetDeath = 0;
            if($row["typ"] == "dest")
            {
                $laPlanetCount = $lodb->getOne("SELECT COUNT(*) FROM planets WHERE owner='$lsOwner'");
            	if($laPlanetCount[0] > 1)
            	{
            		
        	    	$liAnzahl = $laBattleRet['sa'][1][14];
        		    if($liAnzahl > 0)
        		    {
			            $laPlanetRow = $lodb->getOne("SELECT dia FROM planets WHERE coords='$lsTargetCoords'");
			            $liDia = $laPlanetRow[0];
			
            			//Formel zur Verncihtung, wir haben $anzahl Lunas doer Rips, je nach der Spielekonzeption =)
            			//Neuster Stand seit dem 4.Ferbruar 2008
            			/*Mondzerst�rung:(dia/(anz^2)/anz/((dia/1000)^2))*(anz^3,5)/40*/
            			//$liPlanetPercent = round(min(($liDia/(pow($liAnzahl,2))/$liAnzahl/(pow(($liDia/1000),2)))*(pow($liAnzahl,3.5))/40, 100));
                        $liPlanetPercent = round(max(0,min(99.9,(100 - sqrt(min($liDia,9990))) * $liAnzahl)),1);
            			/*Schiffzerst�rung:(dia/(anz^2)/anz)*(anz^2)/50*/
                        //$liFleetDeathPercent = floor(min(($liDia/(pow($liAnzahl,2))/$liAnzahl)*(pow($liAnzahl,2))/50, 100));
                        $liFleetDeathPercent = round(sqrt($liDia) / 2,2);
			
            
                        if(chanceDecide($liPlanetPercent,false))
            			//if($liPlanetPercent * 100 >= rand(1,10000))
            				$liPlanetDeath=1;
            			else
            				$liPlanetDeath=0;
                            
                        if(chanceDecide($liFleetDeathPercent,false))
			            //if($liFleetDeathPercent * 100 >= rand(1,10000))
    				        $liShipDeath = 1;
                        else
                            $liShipDeath = 0;
			
			            //Berechnung beendet, beginne Aktion
            			if($liPlanetDeath==1)
            			{
            				destroyPlanet($lsTargetCoords);
            				$laReport["dest"] = 1;
            			}
		            }
                }
            }
            
            //Beute
            $laRes = read_res($lsTargetCoords);
            
            //ersma 50%
            for($i=0;$i<4;$i++) 
                $laRes[$i] = $laRes[$i]/2;
            
            $liResSum = ($laRes[0] + $laRes[1] + $laRes[2] + $laRes[3]);
            $liLadefaktor = min(($liKapa/$liResSum),1);
            
            $laPluRes = array();
            for($i=0;$i<4;$i++)
            {
                $laBeute[$i] = round(($laRes[$i]) * $liLadefaktor);
                
                $laReport['pr'.$i] = $laBeute[$i];
                $laPluRes[$i] = $laBeute[$i];
            }
            //beute abziehen
            sub_res($laPluRes, $lsTargetCoords);
            
        }
        //Abschluss
        //0-count-cleaning
        foreach($laBattleRet["sa"] as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $liID => $liCount)
            {
                if($laBattleRet["sa"][$liSpieler][$liID] < 1)
                    unset($laBattleRet["sa"][$liSpieler][$liID]);
            }
        }
        foreach($laBattleRet["sv"] as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $liID => $liCount)
            {
                if($laBattleRet["sv"][$liSpieler][$liID] < 1)
                    unset($laBattleRet["sv"][$liSpieler][$liID]);
            }
        }
        foreach($laBattleRet["vv"] as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $liID => $liCount)
            {
                if($laBattleRet["vv"][$liSpieler][$liID] < 1)
                    unset($laBattleRet["vv"][$liSpieler][$liID]);
            }
        }
        
        //angreifer setzen
        foreach($laBattleRet["sa"] as $liSpieler => $laSchiffe)
        {
            if(count($laSchiffe) == 0 || (isset($liShipDeath) && $liShipDeath == 1))//Kaputt   
            {
                $lodb->query("DELETE FROM flotten WHERE id = '".$laAttFIDs[$liSpieler]."'");
            }
            else
            {
                if($liSpieler == 1)
                {
                    $lodb->query("UPDATE flotten SET tthere = 0, schiffe = '".array2ikf($laSchiffe)."', load1 = load1 + $laBeute[0], load2 = load2 + $laBeute[1], load3 = load3 + $laBeute[2], load4 = load4 + $laBeute[3] WHERE id = '".$laAttFIDs[$liSpieler]."'");   
                }
                else
                    $lodb->query("UPDATE flotten SET tthere = 0, schiffe = '".array2ikf($laSchiffe)."' WHERE id = '".$laAttFIDs[$liSpieler]."'");   
            }
        }
        
        //deffer schiffe setzen
        foreach($laBattleRet["sv"] as $liSpieler => $laSchiffe)
        {
            if(count($laSchiffe) == 0 && $liSpieler == 1)//Kaputt   
            {
                $lodb->query("UPDATE schiffe SET s = '' WHERE coords = '$lsTargetCoords'");
            }
            else if(count($laSchiffe) == 0 && $liSpieler > 1)//Kaputt   
            {
                $lodb->query("DELETE FROM flotten WHERE id = '".$laDeffFIDs[$liSpieler]."'");
            }
            else
            {
                if($liSpieler == 1)
                {
                    $lodb->query("UPDATE schiffe SET s = '".array2ikf($laSchiffe)."' WHERE coords = '$lsTargetCoords'");   
                }
                else
                    $lodb->query("UPDATE flotten SET tthere = 0, schiffe = '".array2ikf($laSchiffe)."' WHERE id = '".$laDeffFIDs[$liSpieler]."'");   
            }
        }
        
        //deffer towers setzen
        foreach($laBattleRet["vv"] as $liSpieler => $laSchiffe)
        {
            if(count($laSchiffe) == 0 && $liSpieler == 1)//Kaputt   
            {
                $lodb->query("UPDATE verteidigung SET v = '' WHERE coords = '$lsTargetCoords'");
            }
            else
            {
                if($liSpieler == 1)
                {
                    $lodb->query("UPDATE verteidigung SET v = '".array2ikf($laSchiffe)."' WHERE coords = '$lsTargetCoords'");   
                }
            }
        }
        
        //War der angriff Ehrenvoll?
        /**
        * 1. man bekommt 1 - 10 Punkte pro 10% der zestörten gegnerischen Einheiten 1 Punkt, 
        * 2. hatte der Gegner weniger asls 50% deiner mili punkte, war der Kampf unehrenhaft
        *
        *
        **/
        dbg($laAttLost);
        dbg($laDeffLost);
        foreach($laAttIDs as $liID => $lsUid)
        {
            $liMyMiliPoints  = $laAttPunkte[$liID];
            //nun zu jedem deffer mal rechnen
            foreach($laDeffPunkte as $liGegnerID => $liMiliPunkte)
            {
                //angreifer -> deffer
                $lbHonourfull = $liMyMiliPoints == 0 ? false : ($liMiliPunkte / $liMyMiliPoints) > 0.5;
                $liPoints = $liMiliPunkte == 0 ? 0 : floor(($laDeffLost[$liGegnerID] / $liMiliPunkte) * 10);
                
                if($liPoints > 0)
                {
                    dbg("Att $liID vs $liGegnerID was " . ($lbHonourfull ? "hofu" : "unhofu"));
                    dbg("$liPoints earned");
                    if($lbHonourfull)
                        goodPoints($lsUid,$liPoints,10);
                    else
                        badPoints($lsUid,$liPoints,20);
                }
                
                //deffer -> angreifer
                $lbHonourfull = $liMiliPunkte == 0 ? false : ($liMyMiliPoints / $liMiliPunkte) > 0.5;
                $liPoints = $liMyMiliPoints == 0 ? 0 : floor(($laAttLost[$liID] / $liMyMiliPoints) * 10);
                
                $lsUidDeffer = $laDeffIDs[$liGegnerID];
                
                dbg("Deff $liGegnerID vs $liID was " . ($lbHonourfull ? "hofu" : "unhofu"));
                if($liPoints > 0)
                {
                    dbg("$liPoints earned");
                    if($lbHonourfull)
                        goodPoints($lsUidDeffer,$liPoints,10);
                    else
                        badPoints($lsUidDeffer,$liPoints,20);
                }
            }
        }
        
        
        //Gigronen verdienen
        $laAttUniqIDs = array();
        $laDeffUniqIDs = array();
        
        $laDoneUsers = array();
        
      
        foreach ($laAttIDs as $liID => $lsUid)
        {
            //Du kriegst Gigronen WENN du noch nix gekriegt hast und deine flotte mindestens 5% der Angriffskraft beitrug!
            
            if(!in_array($lsUid,$laDoneUsers) && $laAttAProz[$liID] > 0.05)
            {
                $liEarn = floor($laReport['v_lost'] / ($laAttPunkte[$liID] * 0.01));
                if($liEarn < 1) continue;//0 gigronen machen dich nicht reich^^
                earnGigrons($lsUid,$liEarn);
                $laAttUniqIDs[] = $lsUid;
                $laDoneUsers[] = $lsUid;
            }
        }
        
        foreach($laDeffIDs as $liID => $lsUid)
        {
            
            if(!in_array($lsUid,$laDoneUsers) && ($liID == 1 || $laDeffAProz[$liID] > 0.05))
            {
                $liEarn = floor($laReport['a_lost'] / ($laDeffPunkte[$liID] * 0.01));
                if($liEarn < 1) continue;//0 gigronen machen dich nicht reich^^
                earnGigrons($lsUid,$liEarn);
                $laDeffUniqIDs[] = $lsUid;
                $laDoneUsers[] = $lsUid;
            }
        }
        
        
        
        //Kriegserfahrung
        if($laBattleRet["winner"] == "a")
        {   
            if(count($laAttUniqIDs) > 0)
            {
                $lsUsers = "'".join("','", $laAttUniqIDs)."'";   
            	$liKriegsPunkte = count($laDeffIDs) + ($laReport['inva'] == 1 ? 3 : 0) + ($laReport['dest'] == 1 ? 10 : 0) + ($laReport['mond'] == 1 ? 5 : 0);
            	$laReport["a_krieg"] = $liKriegsPunkte;
            	$lodb->query("UPDATE erfahrung SET krieg=krieg+$liKriegsPunkte WHERE uid IN ({$lsUsers})");
            }
        }
        else if($laBattleRet["winner"] == "v")
        {
            if(count($laDeffUniqIDs) > 0)
            {
            	$lsUsers = "'".join("','", $laDeffUniqIDs)."'";
            	#echo "$users \n";
            	$liKriegsPunkte = count($laAttIDs) + ($laReport['mond'] == 1 ? 5 : 0);
            	$laReport["v_krieg"] = $liKriegsPunkte;
            	$lodb->query("UPDATE erfahrung SET krieg=krieg+$liKriegsPunkte WHERE uid IN ({$lsUsers})");
            }
        }
        
        //BerichtID suchen
        do
        {
          $lsReportID = uniqid();
          $lodb->query("SELECT id FROM bericht WHERE id='$lsReportID'");
        }
        while($lodb->numrows() != 0);
        
        //Und rein damit
        $lodb->query("INSERT INTO bericht SET id='$lsReportID', time='$row[5]', fromc='$row[2]', toc='$row[3]', b='".serialize($laReport)."', a_lost = '{$laReport['a_lost']}', v_lost = '{$laReport['v_lost']}', winner = '{$laReport['winner']}'");
        
        //sicher ist sicher
        
        /*
        $f = fopen(ROOT_PATH . "/tmp/battle_{$lsReportID}.txt","w");
        fwrite($f,serialize($laReport));
        fclose($f);
        */
        
        //Nachrichten versenden
        $laMsg = array();
        $laMsg['x'] = 10;
        $laMsg['winner'] = $laReport['winner'];
        $laMsg['a_lost'] = $laReport['a_lost'];
        $laMsg['v_lost'] = $laReport['v_lost'];
        
        
        $laMsg['id'] = $lsReportID;
        if($row['typ'] == 'dest')
        {
            $laMsg['x'] = 24;
        	$laMsg['pt'] = $liPlanetDeath;
        	$laMsg['ptc'] = $liPlanetPercent;
        	$laMsg['st'] = $liShipDeath;
        	$laMsg['stc'] = $liFleetDeathPercent;
        }
        foreach ($laAttIDs as $lsUID)
        {
        	send_cmd_msg_eh($lsUID,$row[3],$laMsg,$row[5]);
            $lodb->query("INSERT INTO bericht_recht (user_id,bericht_id) VALUES('{$lsUID}','{$lsReportID}')");
        }
        
       
        $laMsg['x'] = 13;
        $laMsg['winner'] = $laReport['winner'];
        $laMsg['a_lost'] = $laReport['a_lost'];
        $laMsg['v_lost'] = $laReport['v_lost'];
        
        if(isset($laReport['inva']) && $laReport['inva'] > 0)
        {
          $laMsg['x'] = 16;
        }
        if($row['typ'] == 'dest')
        {
        	$laMsg['x'] = 25;
        	$laMsg['pt'] = $liPlanetDeath;
            $laMsg['ptc'] = $liPlanetPercent;
        	$laMsg['st'] = $liShipDeath;
        	$laMsg['stc'] = $liFleetDeathPercent;
        }
        foreach ($laDeffIDs as $lsUID)
        {
        	send_cmd_msg_eh($lsUID,$row[3],$laMsg,$row[5]);
            $lodb->query("INSERT INTO bericht_recht (user_id,bericht_id) VALUES('{$lsUID}','{$lsReportID}')");
        }
    }
    
    
   
}
  function eh_proc_ag_agp_inva_aks($row)
  {
     
  	$db = gigraDB::db_open();
    global $NOW;
    global $_SHIP;
    
    $iDefferCount = 0;
    $iAtterCount = 1;
    
    //Forschungsdaten angreifer
    $fa = $db->getOne("SELECT f FROM forschung WHERE uid='$row[1]'");
    $fa = ikf2array($fa[0]);

    //Schiffe Angreifer
    $sa = ikf2array($row[7]);

    //Konstruktion Verteidiger
    $kv = $db->getOne("SELECT * FROM gebaeude WHERE coords='$row[3]'");

    //Forschung Verteidiger
    $fv = $db->getOne("SELECT f,uid FROM forschung,planets WHERE planets.owner=forschung.uid AND planets.coords='$row[3]' LIMIT 1;");
    $uidv = $fv[1];  //Wir kriegen gleich noch die UID des Verteidigers
    if(!$uidv or $uidv == '' or $uidv == null)
    	$uid = '';
    else 
    	$iDefferCount++;
    $fv = ikf2array($fv[0]);
    //Schiffe Verteidiger
    $sv = read_schiffe($row[3],$kv,$NOW);
    //Verteidigung
    $vv = read_vert($row[3],$kv,$NOW);
    unset($sv['starttime']); unset($sv['prod']);
    unset($vv['starttime']); unset($vv['prod']);

    //Kampf simulieren
    //echo "---\n";
    //To AKS
    $fa = array(1 => $fa);
    $sa = array(1 => $sa);
    $kv = array(1 => $kv);
    $fv = array(1 => $fv);
    $sv = array(1 => $sv);
    $vv = array(1 => $vv);
    $saveTowers = $vv;
    
    
    $aks_deffer = array();
    $aks_deffer[1] = $uidv;
    $aks_deffer_fleet = array();
    $aks_deffer_coords = array(1 => $row[3]);
    $defferCount = 1;
	//AKS: Verteidiger rufen
	$AKS_V_res = $db->query("SELECT * FROM flotten WHERE typ = 'hold' AND toc = '$row[3]' AND tthere = 0 AND thold > UNIX_TIMESTAMP()");
	$db2 = gigraDB::db_open();
	while ($AKS_V_row = $db->fetch())
	{
		$defferCount++;
		$iDefferCount++;
		$aks_deffer_coords[$defferCount] = $AKS_V_row[2];
		//Forschung Verteidiger
	    $fv_ = $db2->getOne("SELECT f,uid FROM forschung,planets WHERE planets.owner=forschung.uid AND planets.coords='$AKS_V_row[2]' LIMIT 1;");
	    $aks_deffer[$defferCount] = $fv_[1];  //Wir kriegen gleich noch die UID des Verteidigers
	    $aks_deffer_fleet[$defferCount] = $AKS_V_row[0];
	    echo "Spieler: $fv_[1] ist dem Kampf beigetreten und schliesst sich der Verteidigungsfront an.\n";
	    $fv[$defferCount] = ikf2array($fv_[0]);
	    //Schiffe = $row['schiffe']
	    $sv[$defferCount] = ikf2array($AKS_V_row['schiffe']);
	    $vv[$defferCount] = array();
	}
	$aks_atter = array(1 => $row[1]);
	$aks_atter_fleet = array(1 => $row[0]);
	$aks_atter_coords = array(1 => $row[2]);
	//AKS: Angreifer beziehen
	$db->query("SELECT id FROM flotten WHERE parentfleet = '$row[0]'");
	while($aks_verband_row = $db->fetch())
	{
		$iAtterCount++;
		
		$fid = $aks_verband_row[0];
	
		$fa_ = $db2->getOne("SELECT schiffe,f,uid,fromc FROM flotten LEFT JOIN forschung ON flotten.userid = forschung.uid WHERE flotten.id = '$fid' LIMIT 1;");
		$aks_atter[$iAtterCount] = $fa_[2];
		$aks_atter_coords[$iAtterCount] = $fa_[3];
    	        echo "Spieler: $fa_[2] ist dem Kampf beigetreten und schliesst sich der Angreiferfront an.\n";
		$aks_atter_fleet[$iAtterCount] = $fid;
		
		$fa[$iAtterCount] = ikf2array($fa_[1]);
		$sa[$iAtterCount] = ikf2array($fa_[0]);
		
	}

	//Daten schreiben
	$atter_data = array(0 => null);
	foreach ($aks_atter as $a => $uid)
	{
		$rowData = $db->getOne("SELECT name,krieg_treffer,IF(kampf_until > UNIX_TIMESTAMP(),kampf_percent,0) FROM users LEFT JOIN skills ON users.id = skills.uid LEFT JOIN user_gigron ON users.id = user_gigron.uid WHERE id = '$uid'");
		$f_data = $fa[$a];
		$fa[$a]['klevel'] = $rowData[1];
        $liBonus = (1 + ($rowData[2] / 100));
        $fa[$a]["bonus"] = $liBonus;
        
		$coords_data = $aks_atter_coords[$a];
		$atter_data[] = array("name" => $rowData[0], "f" => $f_data, "coords" => $coords_data,"bonus" => $liBonus);
	}	
	$deffer_data = array();
	//erster
	$rowData = $db->getOne("SELECT name FROM users WHERE id = '$uidv'");
	$deffer_data[1] = array("name" => $rowData[0],"f" => $fv[1], "coords" => $row[3]);
	$defferCount = 0;
	foreach ($aks_deffer as $a => $uid)
	{
		$defferCount++;
		$rowData = $db->getOne("SELECT name,krieg_treffer,IF(kampf_until > UNIX_TIMESTAMP(),kampf_percent,0) FROM users LEFT JOIN skills ON users.id = skills.uid LEFT JOIN user_gigron ON users.id = user_gigron.uid WHERE id = '$uid'");
		$f_data = $fv[$defferCount];
		
		$fv[$a]['klevel'] = $rowData[1];
        $liBonus = (1 + ($rowData[2] / 100));
		$fv[$a]["bonus"] = $liBonus;
        
		$coords_data = $aks_deffer_coords[$defferCount];
		$deffer_data[$defferCount] = array("name" => $rowData[0], "f" => $f_data, "coords" => $coords_data,"bonus" => $liBonus);
	}
	#var_dump($bericht);
    $ret = battle($fa,$sa,$kv,$fv,$sv,$vv);
	
    
    //eh_debugprintaks($ret);
    //echo "---\n";
	#var_dump($ret);
    $bericht = array();
    
	$bericht["atter_data"] = $atter_data;
	$bericht["deffer_data"] = $deffer_data;
    
    $bericht["kampf"] = $ret["runde"]; //Hier der gesammte Kampfprotokoll
    $bericht["winner"] = $ret["winner"];
    $lbWin = $ret["winner"] == "a";
    
    $bericht["a_lost"] = 0;
    $bericht["v_lost"] = 0;
    
    $loss = array("sa" => array(), "sv" => array(), "vv" => array());
    //Verluste berechnen
    foreach ($sa as $a => $data)
    {
    	foreach ($data as $sa_sid => $sa_sc)
    	{
    		if(!isset($loss["sa"][$sa_sid]))
    			$loss["sa"][$sa_sid] = 0;
    		if(isset($ret["sa"][$a][$sa_sid]))
    			$loss["sa"][$sa_sid] += $sa_sc - $ret["sa"][$a][$sa_sid];
    		else 
    			$loss["sa"][$sa_sid] += $sa_sc;
    	}
    }
    foreach ($sv as $a => $data)
    {
    	foreach ($data as $sv_sid => $sv_sc)
    	{
    		if(isset($ret["sv"][$a][$sv_sid]))
    			$loss["sv"][$sv_sid] += $sv_sc - $ret["sv"][$a][$sv_sid];
    		else 
    			$loss["sv"][$sv_sid] += $sv_sc;
    	}
    }
    foreach ($vv as $a => $data)
    {
    	foreach ($data as $vv_sid => $vv_sc)
    	{
    		if(!isset($loss["vv"][$vv_sid]))
    			$loss["vv"][$vv_sid] = 0;
    		if(isset($ret["vv"][$a][$vv_sid]))
    			$loss["vv"][$vv_sid] += $vv_sc - $ret["vv"][$a][$vv_sid];
    		else 
    			$loss["vv"][$vv_sid] += $vv_sc;
    	}
    }
    $laderaum = 0;
    $sacount = 0;
    foreach ($ret["sa"] as $a => $data)
    {
    	foreach ($data as $sa_sid => $sa_sid)
    	{
    		$fa[$a]['f10'] = isset($fa[$a]['f10']) ? $fa[$a]['f10'] : 0;
    		$laderaum += $_SHIP[$sa_sid][10] * $sa_sc * ((20 + $fa[$a]['f10'])/20);
    		$sacount += $sa_sc; 
    	}
    }
    //TF
    /*
    TF-Regeln:
    Von 100% der zerstoerten einzelnen Rohstoffeanteile der Schiffe gehen
    	70% Eisen
    	50% Titan
    	80% Wasser
    	10% Wasserstoff
    ins TF
    Also bei einem Schiff das 1000 von allen 4 Rohstoffarten kostet kommen
    	700 Eisen
    	500 Titan
    	800 Wasser und
    	100 Wasserstoff
    	ins TF
    */
    $TF = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
    foreach ($loss["sa"] as $t => $c)
    {
    	$TF[1] += $_SHIP[$t][1] * $c * 0.7; 
    	$TF[2] += $_SHIP[$t][2] * $c * 0.5; 
    	$TF[3] += $_SHIP[$t][3] * $c * 0.8; 
    	$TF[4] += $_SHIP[$t][4] * $c * 0.1;
    	
    	$bericht["a_lost"] += ($_SHIP[$t][1] + $_SHIP[$t][2] + $_SHIP[$t][3] + $_SHIP[$t][4]) * $c;
    }
    foreach ($loss["sv"] as $t => $c)
    {
    	$TF[1] += $_SHIP[$t][1] * $c * 0.7; 
    	$TF[2] += $_SHIP[$t][2] * $c * 0.5; 
    	$TF[3] += $_SHIP[$t][3] * $c * 0.8; 
    	$TF[4] += $_SHIP[$t][4] * $c * 0.1;
    	
    	$bericht["v_lost"] += ($_SHIP[$t][1] + $_SHIP[$t][2] + $_SHIP[$t][3] + $_SHIP[$t][4]) * $c;
    }
    foreach ($loss["vv"] as $t => $c)
    {
    	/*
    	$TF[1] += $_SHIP[$t][1] * $c * 0.7; 
    	$TF[2] += $_SHIP[$t][2] * $c * 0.5; 
    	$TF[3] += $_SHIP[$t][3] * $c * 0.8; 
    	$TF[4] += $_SHIP[$t][4] * $c * 0.1;
    	*/ //keine def ins TF
    	
    	$bericht["v_lost"] += ($_SHIP[$t][1] + $_SHIP[$t][2] + $_SHIP[$t][3] + $_SHIP[$t][4]) * $c;
    }
    
    //Gigronen verdienen!
    $liAtterGigron = floor($bericht['v_lost'] / 100);
    $liDefferGigron = floor($bericht['a_lost'] / 100);
    if($liAtterGigron > 100)
    {
        foreach ($aks_atter as $lsUid)
            earnGigrons($lsUid,$liAtterGigron);
    }
    if($liDefferGigron)
    {
        foreach($aks_deffer as $lsUid)
            earnGigrons($lsUid,$liDefferGigron);
    }
    $bericht["tf"] = $TF;
    $bericht["mond"] = 0;
    $mond_chance = 0;
    $bericht["mondchance"] = $mond_chance;
	//Mond?
	if(array_sum($TF)>100000)
	{
		$mond_chance = min(round(array_sum($TF),-5) / 100000,40);
		$bericht["mondchance"] = $mond_chance;
		$m_coord = substr($row[3],0,-1) . "2"; //1:1:1:1 -> 1:1:1: -> 1:1:1:2
		$hasMoon = $db->getOne("SELECT coords FROM planets WHERE coords = '$m_coord'");
		if(rand(1,100) < $mond_chance && !$hasMoon)
		{
			//Mond wird nun erstellt, dabei aber TF geloescht
			$dia = rand(100,250) * $mond_chance;
  			$temp = mt_rand(-273,500);
  			
            createPlanet($aks_deffer[1],$m_coord);
            /*
  			$db->query("INSERT INTO planets SET coords = '$m_coord', owner = '$aks_deffer[1]', pname = 'Mond', temp = '$temp', dia = '$dia', pbild = 'mond.jpg', punkte = 0");
		  	$NOW = time();
		  	$db->query("INSERT INTO rohstoffe SET coords='$m_coord',r1=0,r2=0,r3=0,r4=0,u1=$NOW");
		  	$db->query("INSERT INTO gebaeude SET coords='$m_coord', k1=1");
		  	$db->query("INSERT INTO schiffe SET coords='$m_coord',s=''");
		  	$db->query("INSERT INTO verteidigung SET coords='$m_coord',v=''");*/
              
		  	$bericht["mond"] = 1;
              
            $iTFLeftFaktor = 0.7;
            
            foreach($TF as $tfKey => $tfVal)
                $TF[$tfKey] = $iTFLeftFaktor * $tfVal;
		}
	}
    $lsTFCoords = substr($row[3],0,-1)."1";
    $db->query("UPDATE rohstoffe SET tf1=tf1+$TF[1], tf2=tf2+$TF[2], tf3=tf3+$TF[3], tf4=tf4+$TF[4] WHERE coords='$lsTFCoords'");
    
   $bericht["dest"] = 0;
    if($row[4] == "dest" && $lbWin)
    {
    	$x = $db->getOne("SELECT COUNT(*) FROM planets WHERE owner='$uidv'");
    	if($x[0]==1)
    	{
    		//OOOoooooooooooooooooh der Spieler hat nur noch diesen letzten kleinen Planeten, den wollen wir ihm doch nicht wegnehmen oder?
			$row[4] = "ag";//Angriff!    		
    	}
    	else 
    	{
	    	$anzahl = $ret['sa'][1][14];
		if($anzahl > 0)
		{
		    	echo "Anzahl: ".$anzahl;
			$prow = $db2->getOne("SELECT dia FROM planets WHERE coords='$row[3]'");
			$dia = $prow[0];
			$mess_ang = "Ihre Flotte hat sich den Weg durch den Orbit erk&auml;mpft und beginnt mit der Zerst&ouml;rung."; //Das ist die Nachricht die der Angreifer bekommt.
			//Formel zur Verncihtung, wir haben $anzahl Lunas doer Rips, je nach der Spielekonzeption =)
			//Neuster Stand seit dem 4.Ferbruar 2008
			/*Mondzerst�rung:(dia/(anz^2)/anz/((dia/1000)^2))*(anz^3,5)/40*/
			$planet_proz = round(min(($dia/(pow($anzahl,2))/$anzahl/(pow(($dia/1000),2)))*(pow($anzahl,3.5))/40, 100));
			/*Schiffzerst�rung:(dia/(anz^2)/anz)*(anz^2)/50*/
			$proz_rip = round(min(($dia/(pow($anzahl,2))/$anzahl)*(pow($anzahl,2))/50, 100));
			
			$planettot1 = rand(1, 100);
			$schifftot1 = rand(1, 100);
			if($planet_proz>=$planettot1)
				{$planettot=1;}
			else
				{$planettot=0;}
			if($proz_rip>=$schifftot1)
				{$schifftot=1;}
			else
				{$schifftot=0;}
			//Berechnung beendet, beginne Aktion
			if($planettot==1)
			{
				destroyPlanet($row['toc']);
				$bericht["dest"] = 1;
			}
		}
    	}
    }
    $bericht["inva"] = 0;
    if(isset($ret['sa'][1][8]) && $ret['sa'][1][8] > 0 && $row[4] == "inva" && $lbWin)
    {
      //Tjo... Planet uebernehmen
      $x = $db->getOne("SELECT COUNT(*) FROM planets WHERE owner='$uidv'");
      $liInvaChance = min($ret['sa'][1][8] / 10,80);
      if($x[0]!=1 && rand(1,100) < $liInvaChance)
      {
        invadePlanet($row[3],$row[1]);
        
        $bericht['inva'] = 1;
        
        $sacount=-1; //Dafuer sorgen, das Flotte geloescht wird (keine Schiffe mehr drinne)
      }
      else
      {
        //Lezter Planet oder keine Wahrscheinlichkeit
        $bericht['inva'] = -1;
      }
      $bericht['inva_chance'] = $liInvaChance;
    }
    else
        $bericht['inva'] = 0;
    #$bericht['score'] = $ret['score'];
    //echo "AGP: row4 $row[4] sacount $sacount berichtinva $bericht[inva]\n";
    if($sacount>0  && $lbWin/*&& !isset($bericht['inva']) */)
    {
      $res = read_res($row[3]);
      for($ii=0;$ii<4;$ii++) $res[$ii] = $res[$ii]/2;
      $resSum = ($res[0] + $res[1] + $res[2] + $res[3]);
      $ladefaktor = min(($laderaum/$resSum),1);
      for($i=0;$i<4;$i++)  //Piraten! (fnord)
      {
        //$maxp = $res[$i]-(2000+400*$kv['k'.(8+$i)]*$kv['k'.(8+$i)]);  //Maximal Pluenderbar
        $plu[$i] = round(($res[$i]) * $ladefaktor); // maximal 50% pluenderbar
        //$plu[$i] = max(min($laderaum,$maxp),0);
        $bericht['pr'.$i] = $plu[$i];
      }
      sub_res($plu,$row[3]);
        $ar[0] = 0;
        $ar[1] = 0;
        $ar[2] = 0;
        $ar[3] = 0;
      //Flotte updaten
      $db->query("UPDATE flotten SET load1='$plu[0]+$ar[0]',load2='$plu[1]+$ar[1]',load3='$plu[2]+$ar[2]',load4='$plu[3]+$ar[3]' WHERE id='$row[0]'");
    }
	
    
    if(isset($schifftot) && $schifftot==1)
    {
      $db->query("DELETE FROM flotten WHERE id='$row[0]'");
    }
    else
    {
      //tthere auf 0, Rohstoffe einladen, ab nach Hause.
      
      foreach ($ret['sa'] as $a => $data)
      {
	      if(count($data)>0)
      		$q = "UPDATE flotten SET tthere=0, schiffe='".array2ikf($data)."' WHERE id='$aks_atter_fleet[$a]'";
      	  else 
      	  	$q = "DELETE FROM flotten WHERE id='$aks_atter_fleet[$a]'";
	      echo $q.PHP_EOL;
          echo $a.PHP_EOL;
	      $db->query($q);
      }
    }
    if($bericht['inva'])
    {
      foreach($ret['sa'] as $i => $s)
      {
        if($s <= 0)  //Muell: Keine Schiffe dieser Klasse da -> weg damit!
        unset($ret['sa'][$i]);
      }
    }
    $repairedTowers = array();
    //Schiffe u. Tuerme d. Verteidigers vernichten
    if($bericht["v_lost"]>0)
    {
      //Zuerst die Schiffe...
      $svn = read_schiffe($row[3],$kv,$NOW);
      foreach($svn as $i => $s)
      {
        if($i == 15)
        {
                $alive = isset($ret["sv"][1][$i]) ? $ret["sv"][1][$i] : 0; 
              	$destructed = (int)$svn[$i] - (int)$alive;
              	$repair = rand(20,50) / 100;
              	$destructed2 = floor($destructed * $repair);//das was wirklich weg ist
              	
              	$bleibtStehen = ($svn[$i] - $destructed2);//repariere 50%
              	if($bleibtStehen == 0)
              		unset($svn[$i]);
              	else 
              	{
              		if($destructed - $destructed2 > 0)
              			$repairedTowers[$i] = ($destructed - $destructed2);
              		$svn[$i] = $bleibtStehen;
              	}
        }
        else
        {
            if(isset($ret["sv"][1][$i]))
            	$svn[$i] = $ret["sv"][1][$i];
            else
            	unset($svn[$i]);  //Muellabfuhr
        }
      }
      $db->query("UPDATE schiffe SET s='".array2ikf($svn)."' WHERE coords='$row[3]'");
      //...dann die Tuerme
      $vvn = read_vert($row[3],$kv,$NOW);
      
      foreach($vvn as $i => $s)
      {
      	$alive = isset($ret["vv"][1][$i]) ? $ret["vv"][1][$i] : 0; 
      	$destructed = (int)$vvn[$i] - (int)$alive;
      	$repair = rand(20,50) / 100;
      	$destructed2 = floor($destructed * $repair);//das was wirklich weg ist
      	
      	$bleibtStehen = ($vvn[$i] - $destructed2);//repariere 50%
      	if($bleibtStehen == 0)
      		unset($vvn[$i]);
      	else 
      	{
      		if($destructed - $destructed2 > 0)
      			$repairedTowers[$i] = ($destructed - $destructed2);
      		$vvn[$i] = $bleibtStehen;
      	}
      }
      if(count($repairedTowers) > 0)
      	$bericht["repaired"] = $repairedTowers;
      $db->query("UPDATE verteidigung SET v='".array2ikf($vvn)."' WHERE coords='$row[3]'");
      //AKS
      foreach ($aks_deffer as $a => $uid)
      {
      	if(count($ret["sv"][$a + 2])>0)
      	{
      		$schiffe = array2ikf($ret["sv"][$a + 2]);
      		$db->query("UPDATE flotten SET schiffe = '$schiffe', tthere = 0 WHERE id = '$aks_deffer_fleet[$a]'");
      	}
      	else 
      	{
      		$db->query("DELETE FROM flotten WHERE id = '$aks_deffer_fleet[$a]'");
      	}
      	
      }
    }
    //Erfahrung Angreifer
    #echo "Winner :{$ret["winner"]}, $iDefferCount Verteidis und $iAtterCount Atter\n";
    if($ret["winner"] == "a")
    {
    	$users = "'".join("','", $aks_atter)."'";
    	$kriegsPunkte = $iDefferCount + ($bericht['inva'] == 1 ? 3 : 0) + ($bericht['dest'] == 1 ? 10 : 0) + ($bericht['mond'] == 1 ? 5 : 0);
    	$bericht["a_krieg"] = $kriegsPunkte;
    	##echo "UPDATE erfahrung SET krieg=krieg+$kriegsPunkte WHERE uid IN ({$users})";
    	$db->query("UPDATE erfahrung SET krieg=krieg+$kriegsPunkte WHERE uid IN ({$users})");
    }
    else if($ret["winner"] == "v")
    {
    	$users = "'".join("','", $aks_deffer)."'";
    	#echo "$users \n";
    	$kriegsPunkte = $iAtterCount + ($bericht['mond'] == 1 ? 5 : 0);
    	$bericht["v_krieg"] = $kriegsPunkte;
    	$db->query("UPDATE erfahrung SET krieg=krieg+$kriegsPunkte WHERE uid IN ({$users})");
    }
    
    //BerichtID suchen
    do
    {
      $id = genrs(12);
      $db->query("SELECT id FROM bericht WHERE id='$id'");
    }
    while($db->numrows() != 0);
    //Und rein damit
    $db->query("INSERT INTO bericht SET id='$id', time='$row[5]', fromc='$row[2]', toc='$row[3]', b='".serialize($bericht)."'");
    //Nachricht mit Link zum Bericht verschicken
    $msg['x'] = 10;
    $msg['id'] = $id;
    if($row[4] == 'dest')
    {
    	$msg['x'] = 24;
    	$msg['pt'] = $planettot;
    	$msg['ptc'] = $planet_proz;
    	$msg['st'] = $schifftot;
    	$msg['stc'] = $proz_rip;
    }
    foreach ($aks_atter as $uid)
    	send_cmd_msg_eh($uid,$row[3],$msg,$row[5]);
    $msg['x'] = 13;
    if($bericht['inva'])
    {
      $msg['x'] = 16;
      $db->query("UPDATE schiffe SET s='".array2ikf($sa)."' WHERE coords='$row[3]'"); //Restliche Schiffe auf Planeten
      $sacount = 0;
    }
    if($row[4] == 'dest')
    {
    	$msg['x'] = 25;
    	$msg['pt'] = $planettot;
    	$msg['ptc'] = $planet_proz;
    	$msg['st'] = $schifftot;
    	$msg['stc'] = $proz_rip;
    }
    #send_cmd_msg_eh($uidv,$row[3],$msg,$row[5]);
    foreach ($aks_deffer as $uid)
    {
    	
    	send_cmd_msg_eh($uid,$row[3],$msg,$row[5]);
    }
    #$db->query("DELETE FROM verband WHERE id = '$aks_verband_id'");
    echo "Generierte Bericht: ID $id\n";
    if($row[6] < $NOW && $sacount>0)
    {
      $row = $db->getOne("SELECT * FROM flotten WHERE id='$row[0]'");
      //Flotte sollte schon laengst zurueck sein!
      eh_procback($row);
    }
    
    
    //db frei
    unset($db);
    unset($db2);
  }  
  
  
  
  
  function eh_proc_kolo($row)
  {
    //global $NOW
    $NOW = time();
    $db = gigraDB::db_open();
    $laShips = ikf2array($row['schiffe']);
    $lsCoords = $row['toc'];
    $laRow = $db->getOne("SELECT owner FROM planets WHERE coords = '{$row[3]}'");
    
    if(!$laRow)
    {
        //Lege Plani an 
        createPlanet($row['userid'],$row['toc']);
        $laShips[7]--;
        if($laShips[7] == 0)
            unset($laShips[7]);
        //Waren Ressis mit dabei?
        if(($row['load1']+$row['load2']+$row['load3']+$row['load4'])>0)  //Was geladen:
        {
          //Umformen
          $res = array();
          $res[0] = $row['load1'];
          $res[1] = $row['load2'];
          $res[2] = $row['load3'];
          $res[3] = $row['load4'];
          //Einzahlen
          add_res($res,$row['toc']);
        }
        
        //Flotte weg
        $db->query("DELETE FROM flotten WHERE id = '{$row[0]}'");
        add_schiffe($lsCoords,$laShips);
        
        //Nachricht
        $msg['x'] = 14;
        $msg['c'] = $lsCoords;
        send_cmd_msg_eh($row[1],$row[3],$msg,$row[5]);
        return;
    }
    else
    {
        if($laRow[0] == '0')
        {
            //Übernehme Planet   
            $lsCoordsMoon = coordReform($lsCoords,2);
            $db->query("UPDATE planets SET owner = '{$row['userid']}' WHERE coords = '{$lsCoords}' OR coords = '{$lsCoordsMoon}'");
            $laShips[7]--;
            if($laShips[7] == 0)
                unset($laShips[7]);
            //Waren Ressis mit dabei?
            if(($row['load1']+$row['load2']+$row['load3']+$row['load4'])>0)  //Was geladen:
            {
              //Umformen
              $res = array();
              $res[0] = $row['load1'];
              $res[1] = $row['load2'];
              $res[2] = $row['load3'];
              $res[3] = $row['load4'];
              //Einzahlen
              add_res($res,$row['toc']);
            }
            //Flotte weg
            $db->query("DELETE FROM flotten WHERE id = '{$row[0]}'");
            add_schiffe($lsCoords,$laShips);
            
            //Nachricht
            $msg['x'] = 14;
            $msg['c'] = $lsCoords;
            send_cmd_msg_eh($row[1],$row[3],$msg,$row[5]);
            return;
            
        }
        else
        {
            //Nach Hause   
            
            $msg['x'] = 15;
            $msg['c'] = $lsCoords;
            send_cmd_msg_eh($row[1],$row[3],$msg,$row[5]);
            $db->query("UPDATE flotten SET tthere=0 WHERE id='$row[0]'");
        }
    }
    if($row[6] < $NOW && $sacount>0)
    {
        //Flotte sollte schon laengst zurueck sein!
        eh_procback($row);
    }
    unset($db);
  }
  
  
  function eh_proc_trans($row)
  {
     $db = gigraDB::db_open();
    global $NOW;
    $res[0] = $row[9];
    $res[1] = $row[10];
    $res[2] = $row[11];
    $res[3] = $row[12];
    add_res($res,$row[3]); //Rohstoffe abliefern
    //Laderaum leeren und Flotte auf Heimweg
    $db->query("UPDATE flotten SET tthere=0, load1=0,load2=0,load3=0,load4=0 WHERE id='$row[0]'");


    //To UID rauskriegen
    $x = $db->getOne("SELECT owner FROM planets WHERE coords='$row[3]'");

    //Noch Nachricht senden
    $msg['x'] = 17;
    $msg['c'] = $row[2];
    $msg = array_merge($msg,$res);
    send_cmd_msg_eh($x[0],$row[3],$msg,$row[5]);
    unset($db);
  }

  function eh_proc_stat($row)
  {
    global $NOW;
	
    $db = gigraDB::db_open();
    
    //is der planet denn da?
    $laRow = $db->getOne("SELECT owner FROM planets WHERE coords = '{$row['toc']}'");
    if(!$laRow)
    {
        $db->query("UPDATE flotten SET tthere = 0 WHERE id = '$row[0]'");   
        return;
    }
	if(($row[9]+$row[10]+$row[11]+$row[12])>0)  //Was geladen:
    {
      //Umformen
      $res[0] = $row[9];
      $res[1] = $row[10];
      $res[2] = $row[11];
      $res[3] = $row[12];
      //Einzahlen
      add_res($res,$row["toc"]);
    }
    
    add_schiffe($row["toc"],ikf2array($row["schiffe"]));
    
    $db->query("DELETE FROM flotten WHERE id='$row[0]'"); //Flotte loeschen
    //Nachricht senden
    $msg['x'] = 18;
    
    if($laRow[0] != '0')
        send_cmd_msg_eh($laRow[0],$row[3],$msg,$row[5]);
    
    unset($db);
  }
  
    /**
* Recycling
*/
function eh_proc_recy($row)
{
      
    global $_SHIP;
    $lodb = gigraDB::db_open();
    
    $laSchiffe = ikf2array($row[7]);
   
    $lbHasRecycler = isset($laSchiffe[2]) && $laSchiffe[2] > 0;
    
    $lsMainCoords = substr($row[3],0,-2);
    
    $laTf = getTF($row['toc']);
	$f = getForschung($row['userid']);
    $liKapaTech = isset($f['f10']) ? $f['f10'] : 0;
    
    $laRc = array(0,0,0,0);
    
    if($lbHasRecycler)
    {
        $liCount = $laSchiffe[2];
        $liTfSum = array_sum($laTf);
        if($liTfSum == 0)
        {
      	    $lodb->query("UPDATE flotten SET tthere=0 WHERE id='$row[0]'");
      	    return;
        }
        $liRecLadeRaum = $_SHIP[2][10] * $liCount * ((20 + $liKapaTech)/20);
        
        //so undu nu mal bitte auch die VORHANDENEN SCHEISS RESSIS mit einberechnen, kack ogamer ey die immer ins TF saven
        $liUsed = $row['load1'] + $row['load2'] + $row['load3'] + $row['load4'];
        $liRecLadeRaum -= $liUsed;
        
        if($liRecLadeRaum < 0 ) $liRecLadeRaum = 0;
      
      
        if($liRecLadeRaum >= $liTfSum)
        {
            //Alles abbaun
            $laRc = array($laTf[0],$laTf[1],$laTf[2],$laTf[3]);
        }
        else
        {
            $liLadefaktor = min(($liRecLadeRaum/$liTfSum),1);//maximal 1 und minimal laderaum/gesamtTF
            
            $ntf = array();
            for($j=0;$j<4;$j++)
            {
                $laRc[$j] = round($laTf[$j] * $liLadefaktor);
                
            }
        }
	  
      
        subTF($row['toc'],$laRc[0],$laRc[1],$laRc[2],$laRc[3]);
        
        //Flotte updaten
    
        $lodb->query("UPDATE flotten SET load1=load1 + $laRc[0],load2=load2 + $laRc[1],load3= load3 + $laRc[2],load4=load4 +$laRc[3], tthere=0 WHERE id='$row[0]'");
        //nachricht
    	$msg['x'] = 8;
        $msg['c'] = $row['toc'];
        $msg['cap'] = $liRecLadeRaum;
    	$msg[0] = $laRc[0];
    	$msg[1] = $laRc[1];
    	$msg[2] = $laRc[2];
    	$msg[3] = $laRc[3];
    	
    	send_cmd_msg_eh($row[1],$row[2],$msg,$row['tthere']);
    }
    else
    {
        $lodb->query("UPDATE flotten SET tthere=0 WHERE id='$row[0]'");
    }
}
  
    /**
*Fuehrt eine Spionage aus
*/
  function eh_proc_spio($row)
  {
      global $_BAU, $_FORS, $_SHIP, $_VERT, $RESNAME;
  	$db = gigraDB::db_open();
  	
  	$laSpio = array();
  	//noetige Werte fuer Spionage sind
  	$iSpioTechLevelA = 0;
  	$iSpioTechLevelV = 0;
  	
  	$iPlayerLevelA = 0;
  	$iPlayerLevelV = 0;
  	
  	$iChanceToDefense = 0;
  	
  	$iFleetCountV = 0;
  	$iSpyCountA = 0;
  	
  	$iSpyLevel = 0;//10 = Schiffe, 20 = Gebs, 30 = Rohstoffe, 40 = Forschungen
  	
  	//Werte aufbereiten
  	$Ks = "k".join(",k",range(1, 21));
  	$aPlanet = $db->getOne("SELECT owner,$Ks FROM planets LEFT JOIN gebaeude ON planets.coords = gebaeude.coords WHERE planets.coords = '{$row['toc']}'");
  	$aRess = read_res($row['toc']);
  	
  	$aShips = $db->getOne("SELECT s FROM schiffe WHERE coords = '{$row['toc']}'");
  	$aShips = ikf2array($aShips[0]);
  	foreach ($aShips as $iCount)
  		$iFleetCountV += $iCount;
  	
  	$aDeff = $db->getOne("SELECT v FROM verteidigung WHERE coords = '{$row['toc']}'");
  	$aDeff = ikf2array($aDeff[0]);
  	
  	$aSonden = ikf2array($row["schiffe"]);
  	$iSpyCountA = $aSonden[3];
  	$aUser = $db->getOne("SELECT f,(infra+forsch+krieg) AS levelpts,name FROM users LEFT JOIN forschung ON forschung.uid = users.id LEFT JOIN erfahrung ON erfahrung.uid = users.id WHERE users.id = '{$row['userid']}'");
    $lsSpioName = $aUser['name'];
  	$aForsch = ikf2array($aUser[0]);
  	$iPlayerLevelA = getELevel(intval($aUser[1]));
  	$iSpioTechLevelA = isset($aForsch['f8']) ? $aForsch['f8'] : 0;
  	
  	if(strlen($aPlanet[0]) == 0)
  	{
  		$iPlayerLevelV = 0;
  		$iSpioTechLevelV = 0;
  	}
  	else 
  	{
  		$sOwnerID = $aPlanet[0];
  		$aUser = $db->getOne("SELECT f,(infra+forsch+krieg) AS levelpts FROM users LEFT JOIN forschung ON forschung.uid = users.id LEFT JOIN erfahrung ON erfahrung.uid = users.id WHERE users.id = '$sOwnerID'");
  		$aForsch = ikf2array($aUser[0]);
  		$iPlayerLevelV = getELevel(intval($aUser[1]));
  		$iSpioTechLevelV = isset($aForsch['f8']) ? $aForsch['f8'] : 0;
          
        $laSkills = getSkills($sOwnerID);
  	}
  	
  	//Errechne SpyLevel und Chance auf Abwehr
  	$iSumA = $iSpioTechLevelA;
  	$iSumV = $iSpioTechLevelV;
    
  	$iDiff = $iSumV - $iSumA;
    
    /*
  	if($iDiff < 4)
  		$iSpyLevel = 40;
  	else if($iDiff >= 8)
  		$iSpyLevel = 10;
  	else if($iDiff >= 6)
  		$iSpyLevel = 20;
  	else if($iDiff >= 4)
  		$iSpyLevel = 30;
    */
          
    //   09 03 2012 - neues System
    $lbShips = $lbDeff = $lbBuildings = $lbResearch = $lbSkill = false;
    $liMinAmount = ($iSpioTechLevelA > $iSpioTechLevelV) ? -1 * pow($iDiff, 2) : pow($iDiff, 2);
    
    $lbBuildings = true;
    $lbRess = $iSpyCountA >= $liMinAmount;
    $lbShips = $iSpyCountA >= $liMinAmount + 1;
    $lbDeff = $iSpyCountA >= $liMinAmount + 3;
    $lbResearch = $iSpyCountA >= $liMinAmount + 5;
    $lbSkill = $iSpyCountA >= $liMinAmount + 7;
    
    
  	
  	$liOgSpioDiff= ($iSpioTechLevelA - $iSpioTechLevelV)*abs($iSpioTechLevelA - $iSpioTechLevelV)-1+$iSpyCountA;
    
    //SpionageSchiffgewicht
    $liOGSpioGewicht = 0;

    $liOgSFG = ($iSpyCountA*$_SHIP[3][2])/1000/400;
    
    $iChanceToDefense=sqrt(pow(2,(0-$liOgSpioDiff+$iSpyCountA-1)))*($liOgSFG*sqrt($iFleetCountV)*5);
    
    $iChanceToDefense=rand(0,$iChanceToDefense*100)/100;
    
    
    
    $iChanceToDefense = min(100,max(0,round($iChanceToDefense * 100)));
    
  	//Erstelle Bericht

  	if($lbBuildings)
  	{
  		$laSpio[] = array("heading" => l('spio_gebs'));
  		foreach ($aPlanet as $k => $lvl)
  		{
  			if($k > 0 && $lvl > 0)
  				$laSpio[] = array(l('item_b'.$k),$lvl);
  		}
  	}
  	if($lbRess)
  	{
  		$laSpio[] = array("heading" => l('spio_res'));
  		for($i=0;$i<4;$i++)
  		{
            $resKey = "res".($i + 1);
  			$laSpio[] = array(l($resKey),number_format($aRess[$i],0,",","."));
  		}
  	}
    if($lbShips)
  	{
  		$laSpio[] = array("heading" => l('spio_ships'));
  		foreach ($aShips as $k => $count)
  		{
  			$laSpio[] = array(l('item_s'.$k),$count,'s'.$k);
  		}
  	}
    if($lbDeff)
    {
  		$laSpio[] = array("heading" => l('spio_def'));
  		foreach ($aDeff as $k => $count)
  		{
  			$laSpio[] = array(l('item_v'.$k),$count,'v'.$k);
  		}
  	}
  	if($lbResearch && strlen($aPlanet[0]) != '0')
  	{  	
  		$laSpio[] = array("heading" => l('spio_research'));
  		foreach ($aForsch as $k => $lvl)
  		{
  			if(substr($k, 0,1) == "f")
  				$laSpio[] = array(l('item_'.$k),$lvl,'f'.$k);
  		}
  	}
    if($lbSkill && strlen($aPlanet[0]) != '0')
    {  	
  		$laSpio[] = array("heading" => l('exp_skills'));
  		foreach (array("krieg_flugzeit","krieg_treffer","infra_planeten","infra_rohstoff","infra_bauzeit","forsch_zeit","forsch_geheimschiff1","forsch_geheimschiff2") as $lsSkill)
  		{
            if($lsSkill == "krieg_treffer")
  		        $laSpio[] = array(l('exp_'.$lsSkill),$laSkills[$lsSkill],'kl');
            else
                $laSpio[] = array(l('exp_'.$lsSkill),$laSkills[$lsSkill]);
  		}
  	}
  	
  	//Schreibe bericht
  	$b = serialize(array("spio" => $laSpio, "chance" => round($iChanceToDefense)));
  	
  	//BerichtID suchen
    do
    {
      $id = genrs(12);
      $db->query("SELECT id FROM bericht WHERE id='$id'");
    }
    while($db->numrows() != 0);
  	$db->query("INSERT INTO bericht SET id='$id', time='$row[5]', fromc='$row[2]', toc='$row[3]', b='$b', typ='spio'");
    //Nachricht mit Link zum Bericht verschicken
    $msg['x'] = 27;
    $msg['id'] = $id;
    
	
    send_cmd_msg_eh($row["userid"],$row[3],$msg,$row[5]);
    
    $msg['x'] = 26;
    $msg['f'] = $row["fromc"];
    $msg['t'] = $row["toc"];
    $msg['name'] = $lsSpioName;
    
    if(strlen($aPlanet[0]) != 0)
    	send_cmd_msg_eh($sOwnerID,$row[3],$msg,$row[5]);
    	
    //Kampf?
    $db2 = gigraDB::db_open();
    if(rand(1,100) <= $iChanceToDefense)
    {
    	StartBattle($row);
    }
    else 
    {
    	$db2->query("UPDATE flotten SET tthere = 0 WHERE id = '$row[0]'");
    }
    

  	
    if($row[6] < time())
    {
      $row = $db->getOne("SELECT * FROM flotten WHERE id='$row[0]'");
      //Flotte sollte schon laengst zurueck sein!
      eh_procback($row);
    }
    unset($db);
    unset($db2);
  }
  