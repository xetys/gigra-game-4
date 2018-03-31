<?php

function getNextBattleId()
{
   return uniqid("",true);
}

function ksDecode($asResult)
{
    return json_decode($asResult,true);
}
function NotLowerZero($x)
{
    return max($x,0);
}
function gigraID2ksId($aaShips = array(),$aaDef = array())
{
    global $_SHIP, $_VERT;
    
    $lsIDs = "";
    foreach($_SHIP as $lsID => $data)
        if(isset($aaShips[$lsID]))
            $lsIDs .= " ".NotLowerZero($aaShips[$lsID]);
        else
            $lsIDs .= " 0";
    if(count($aaDef) > 0)
    {
        foreach($_VERT as $lsID => $data)
        if(isset($aaDef[$lsID]))
            $lsIDs .= " ".NotLowerZero($aaDef[$lsID]);
        else
            $lsIDs .= " 0";
    }
    
    return $lsIDs;
}

function ksId2gigraID($aaUnits = array())
{
    $laShips = array();
    $laDeff = array();
    
    foreach($aaUnits as $id => $liCount)
    {
        if($id > 100 && $id < 117)
        {
            $laShips[$id-100] = $liCount;
        }
        else if($id == 117)
        {
            $laShips[101] = $liCount;
        }
        else if($id == 118)
        {
            $laShips[102] = $liCount;
        }
        else if($id > 200)
        {
            $laDeff[$id-200] = $liCount;
        }
    }
    
    
    return array($laShips,$laDeff);
}

function battle($fa,$sa,$kv,$fv,$sv,$vv,$maxrounds=6)
{
    global $_SHIP,$_VERT;
    //1. Werte fuers C-KS vorbereiten
    $liNextBattleId = getNextBattleId();
    $lsSource = "";
    $lsSource .= "Rapidfire = 1\n";
    $lsSource .= "FID = 0\n";
    $lsSource .= "DID = 0\n";
    $lsSource .= "Attackers = ".count($sa)."\n";
    $lsSource .= "Defenders = ".count($sv)."\n";
    
    //Forschungen
    foreach ($sv as $a => $data)
    {
        $fv[$a]['f5'] = isset($fv[$a]['f5']) && !empty($fv[$a]['f5']) ? $fv[$a]['f5'] : 0;
 		$fv[$a]['f6'] = isset($fv[$a]['f6']) && !empty($fv[$a]['f6']) ? $fv[$a]['f6'] : 0;
 		$fv[$a]['f7'] = isset($fv[$a]['f7']) && !empty($fv[$a]['f7']) ? $fv[$a]['f7'] : 0;
 		$fv[$a]['f9'] = isset($fv[$a]['f9']) && !empty($fv[$a]['f9']) ? $fv[$a]['f9'] : 0;
        
 		$fv[$a]['klevel'] = isset($fv[$a]['klevel']) ? $fv[$a]['klevel'] : 0;
 		
 		$laTechsDeffer[$a]["ang"] = 1 + (($fv[$a]['f5'] * 0.05 ) + ($fv[$a]['f6'] * 0.05 ) +  ($fv[$a]['f7'] * 0.05 ));
 		$laTechsDeffer[$a]["deff"] = 1 + ($fv[$a]['f9'] * 0.1);
         
        $laTechsDeffer[$a]["ang"] *= $fv[$a]["bonus"];
        $laTechsDeffer[$a]["deff"] *= $fv[$a]["bonus"];
 		
 		$laTechsDeffer[$a]["quote"] = expSaet($fv[$a]['klevel']);
         
        $laTechsDeffer[$a]["quote"] = min(100,$laTechsDeffer[$a]["quote"]*$fv[$a]["bonus"]);
 	}
 	foreach ($sa as $a => $data)
 	{
 		$fa[$a]['f5'] = isset($fa[$a]['f5']) && !empty($fa[$a]['f5']) ? $fa[$a]['f5'] : 0;
 		$fa[$a]['f6'] = isset($fa[$a]['f6']) && !empty($fa[$a]['f6']) ? $fa[$a]['f6'] : 0;
 		$fa[$a]['f7'] = isset($fa[$a]['f7']) && !empty($fa[$a]['f7']) ? $fa[$a]['f7'] : 0;
 		$fa[$a]['f9'] = isset($fa[$a]['f9']) && !empty($fa[$a]['f9']) ? $fa[$a]['f9'] : 0;
 		$fa[$a]['klevel'] = isset($fa[$a]['klevel']) ? $fa[$a]['klevel'] : 0;
 		
 		$laTechsAtter[$a]["ang"] = 1 + (($fa[$a]['f5'] * 0.05 ) + ($fa[$a]['f6'] * 0.05 ) +  ($fa[$a]['f7'] * 0.05 ));
 		$laTechsAtter[$a]["deff"] = 1 + ($fa[$a]['f9'] * 0.1);
         
         $laTechsAtter[$a]["ang"] *= $fa[$a]["bonus"];
         $laTechsAtter[$a]["deff"] *= $fa[$a]["bonus"];
 		
 		$laTechsAtter[$a]["quote"] = expSaet($fa[$a]['klevel']);  
        $laTechsAtter[$a]["quote"] = min(100,$laTechsAtter[$a]["quote"]*$fa[$a]["bonus"]);
 	}
    
    //Angreifer
    $liPlayerNum = 0;
    foreach($sa as $liSpieler => $laShips)
    {
        //Forschung 
        $liWaffen = $fa[$liSpieler]['f5'];
        $liSchilde = $fa[$liSpieler]['f6'];
        $liHuelle = $fa[$liSpieler]['f9'];
        $liHitQuote = round($laTechsAtter[$liSpieler]["quote"]);
        $liBonusPercent = $fa[$liSpieler]["bonus"] * 100;
        
        $lsSchiffe = gigraID2ksId($laShips);
        $lsSource .= "Attacker{$liPlayerNum} = ($liBonusPercent $liWaffen $liSchilde $liHuelle $liHitQuote".$lsSchiffe.")\n";
        $liPlayerNum++;
    }
    
    //Verteidiger
    $liPlayerNum = 0;
    foreach($sv as $liSpieler => $laShips)
    {
        //Forschung 
        $liWaffen = $fv[$liSpieler]['f5'];
        $liSchilde = $fv[$liSpieler]['f6'];
        $liHuelle = $fv[$liSpieler]['f9'];
        $liHitQuote = round($laTechsDeffer[$liSpieler]["quote"]);
        $liBonusPercent = $fv[$liSpieler]["bonus"] * 100;
        
        $lsSchiffe = $liPlayerNum == 0 ?  gigraID2ksId($laShips,$vv[$liSpieler]) : gigraID2ksId($laShips);
        
        $lsSource .= "Defender{$liPlayerNum} = ($liBonusPercent $liWaffen $liSchilde $liHuelle $liHitQuote".$lsSchiffe.")\n";
        $liPlayerNum++;
    }

    
    $bf = fopen ( "battledata/battle_".$liNextBattleId.".txt", "w" );
    
    fwrite ( $bf, $lsSource );
    fclose ( $bf );
    //echo $liNextBattleId;die();
    
    //2. warten bis C-KS fertig ist
    for($attempt=1;$attempt<=2;$attempt++)
    {
        //system(ROOT_PATH.'/core/c_combat/battle "battle_id='.$liNextBattleId.'"');
        
        $lsResult = GiCoSys_ComputeBattle($lsSource);
    
        //$lsResult = file_get_contents( "battleresult/battle_".$liNextBattleId.".txt" );
        $laRet = ksDecode($lsResult);
        
        if(!$laRet) die($lsResult);
        if($laRet != false)
            break;
        sleep(1);
    }
    
    //die($lsResult);
    
    //3. Konvertierung ins Gigra KS format
    
    $laRunden = array();
    
    //Erste Runde - was haben wir alles da?
    $laRunden[1] = array();
    $sa_n = $sa;
    $sv_n = $sv;
    $vv_n = $vv;
    foreach($sa as $liSpieler => $laSchiffe)
    {   
        if(!isset($laRunden[1]["a"]))
            $laRunden[1]["a"] = array();
            
        if(!isset($laRunden[1]["a"][$liSpieler]))
            $laRunden[1]["a"][$liSpieler] = array();

        foreach($laSchiffe as $lsID => $liCount)
        {
            if($liCount > 0)
            {
                    
                if(!isset($laRunden[1]["a"][$liSpieler][$lsID]))
                    $laRunden[1]["a"][$liSpieler][$lsID] = array();
                
                $laRunden[1]["a"][$liSpieler][$lsID]['c'] = $liCount;
                $laRunden[1]["a"][$liSpieler][$lsID]['a'] = $_SHIP[$lsID][8] * $laTechsAtter[$liSpieler]["ang"];
                $laRunden[1]["a"][$liSpieler][$lsID]['v'] = $_SHIP[$lsID][9] * $laTechsAtter[$liSpieler]["deff"];
                $laRunden[1]["a"][$liSpieler][$lsID]['h'] = ($_SHIP[$lsID][1]+$_SHIP[$lsID][2])/10 * $laTechsAtter[$liSpieler]["deff"];
            }
        }
    }
    
    $laRunden[1]["sv"] = array( 1 => array());
    foreach($sv as $liSpieler => $laSchiffe)
    {
        $laRunden[1]["sv"][$liSpieler] = array();        
        foreach($laSchiffe as $lsID => $liCount)
        {
            if($liCount > 0)
            {
                    
                if(!isset($laRunden[1]["sv"][$liSpieler][$lsID]))
                    $laRunden[1]["sv"][$liSpieler][$lsID] = array();
                
                $laRunden[1]["sv"][$liSpieler][$lsID]['c'] = $liCount;
                $laRunden[1]["sv"][$liSpieler][$lsID]['a'] = $_SHIP[$lsID][8] * $laTechsDeffer[$liSpieler]["ang"];
                $laRunden[1]["sv"][$liSpieler][$lsID]['v'] = $_SHIP[$lsID][9] * $laTechsDeffer[$liSpieler]["deff"];
                $laRunden[1]["sv"][$liSpieler][$lsID]['h'] = ($_SHIP[$lsID][1]+$_SHIP[$lsID][2])/10 * $laTechsDeffer[$liSpieler]["deff"];
            }
        }
        if($liSpieler == 1)
        {
            foreach($vv[1] as $lsID => $liCount)
            {
                if($liCount > 0)
                {
                    if(!isset($laRunden[1]["vv"]))
                        $laRunden[1]["vv"] = array();
                        
                    if(!isset($laRunden[1]["vv"][$liSpieler]))
                        $laRunden[1]["vv"][$liSpieler] = array();
                        
                    if(!isset($laRunden[1]["vv"][$liSpieler][$lsID]))
                        $laRunden[1]["vv"][$liSpieler][$lsID] = array();
                    
                    $laRunden[1]["vv"][$liSpieler][$lsID]['c'] = $liCount;
                    $laRunden[1]["vv"][$liSpieler][$lsID]['a'] = $_VERT[$lsID][8] * $laTechsDeffer[$liSpieler]["ang"];
                    $laRunden[1]["vv"][$liSpieler][$lsID]['v'] = $_VERT[$lsID][9] * $laTechsDeffer[$liSpieler]["deff"];
                    $laRunden[1]["vv"][$liSpieler][$lsID]['h'] = ($_VERT[$lsID][1]+$_VERT[$lsID][2])/10 * $laTechsDeffer[$liSpieler]["deff"];
                }
            }
        }
    }
    

    //Runden auswertung
    $liMaxRound = $maxrounds;
    $liDoneRounds = 1;
    foreach($laRet['rounds'] as $liRunde => $laRunde)
    {
        if(++$liDoneRounds > $liMaxRound)
            break;
        $sa_n = array();
        $sv_n = array();
        $vv_n = array();
    
        $liRunde = $liRunde + 2;
        $laRunden[$liRunde] = array();
        //Kampfzeile zur lezten runde
        $laRunden[$liRunde-1]["a_ang"] = $laRunde["apower"];
        $laRunden[$liRunde-1]["a_def"] = $laRunde["dabsorb"];
 		$laRunden[$liRunde-1]["a_schuss"] = $laRunde["ashoot"];
 		$laRunden[$liRunde-1]["a_tref"] = $laRunde["ashit"];
 		
 		$laRunden[$liRunde-1]["v_ang"] = $laRunde["dpower"];
 		$laRunden[$liRunde-1]["v_def"] = $laRunde["aabsorb"];
 		$laRunden[$liRunde-1]["v_schuss"] = $laRunde["dshoot"];
 		$laRunden[$liRunde-1]["v_tref"] = $laRunde["dshit"];
        
        //Umforumg
        foreach($laRunde["attackers"] as $liSpieler => $laSchiffe)
        {
            if(!isset($laRunden[$liRunde]["a"]))
                $laRunden[$liRunde]["a"] = array();
            
                
            $liSpieler = $liSpieler + 1;
            
            //schreibe in neue array
            if(!isset($sa_n[$liSpieler]))
                $sa_n[$liSpieler] = array();
            
                        
            if(!isset($laRunden[$liRunde]["a"][$liSpieler]))
                $laRunden[$liRunde]["a"][$liSpieler] = array();
            
            $laSchiffe = ksId2gigraID($laSchiffe);
            
            foreach($laSchiffe[0] as $lsID => $liCount)
            {
                if($liCount > 0)
                {
                    
                        
                    if(!isset($laRunden[$liRunde]["a"][$liSpieler][$lsID]))
                        $laRunden[$liRunde]["a"][$liSpieler][$lsID] = array();
                    
                    $laRunden[$liRunde]["a"][$liSpieler][$lsID]['c'] = $liCount;
                    $laRunden[$liRunde]["a"][$liSpieler][$lsID]['a'] = $_SHIP[$lsID][8] * $laTechsAtter[$liSpieler]["ang"];
                    $laRunden[$liRunde]["a"][$liSpieler][$lsID]['v'] = $_SHIP[$lsID][9] * $laTechsAtter[$liSpieler]["deff"];
                    $laRunden[$liRunde]["a"][$liSpieler][$lsID]['h'] = ($_SHIP[$lsID][1]+$_SHIP[$lsID][2])/10 * $laTechsAtter[$liSpieler]["deff"];
                    
                    
                        
                    $sa_n[$liSpieler][$lsID] = $liCount;
                }
            }
        }
        
        foreach($laRunde["defenders"] as $liSpieler => $laSchiffe)
        {
            if(!isset($laRunden[$liRunde]["sv"]))
                        $laRunden[$liRunde]["sv"] = array();
                        
            $liSpieler = $liSpieler + 1;
            
            //schreibe in neue array
            if(!isset($sv_n[$liSpieler]))
                $sv_n[$liSpieler] = array();    
            
            if(!isset($laRunden[$liRunde]["sv"][$liSpieler]))
                        $laRunden[$liRunde]["sv"][$liSpieler] = array();
            
            $laSchiffe = ksId2gigraID($laSchiffe);
            
            foreach($laSchiffe[0] as $lsID => $liCount)
            {
                if($liCount > 0)
                {
                    
                        
                    if(!isset($laRunden[$liRunde]["sv"][$liSpieler][$lsID]))
                        $laRunden[$liRunde]["sv"][$liSpieler][$lsID] = array();
                    
                    $laRunden[$liRunde]["sv"][$liSpieler][$lsID]['c'] = $liCount;
                    $laRunden[$liRunde]["sv"][$liSpieler][$lsID]['a'] = $_SHIP[$lsID][8] * $laTechsDeffer[$liSpieler]["ang"];
                    $laRunden[$liRunde]["sv"][$liSpieler][$lsID]['v'] = $_SHIP[$lsID][9] * $laTechsDeffer[$liSpieler]["deff"];
                    $laRunden[$liRunde]["sv"][$liSpieler][$lsID]['h'] = ($_SHIP[$lsID][1]+$_SHIP[$lsID][2])/10 * $laTechsDeffer[$liSpieler]["deff"];
                    
                        
                    $sv_n[$liSpieler][$lsID] = $liCount;
                }
            }
            if(isset($laSchiffe[1]))
            {
                //schreibe in neue array
                if(!isset($vv_n[$liSpieler]))
                    $vv_n[$liSpieler] = array();
                foreach($laSchiffe[1] as $lsID => $liCount)
                {
                    if($liCount > 0)
                    {
                        if(!isset($laRunden[$liRunde]["vv"]))
                            $laRunden[$liRunde]["vv"] = array();
                            
                        if(!isset($laRunden[$liRunde]["vv"][$liSpieler]))
                            $laRunden[$liRunde]["vv"][$liSpieler] = array();
                            
                        if(!isset($laRunden[$liRunde]["vv"][$liSpieler][$lsID]))
                            $laRunden[$liRunde]["vv"][$liSpieler][$lsID] = array();
                        
                        $laRunden[$liRunde]["vv"][$liSpieler][$lsID]['c'] = $liCount;
                        $laRunden[$liRunde]["vv"][$liSpieler][$lsID]['a'] = $_VERT[$lsID][8] * $laTechsDeffer[$liSpieler]["ang"];
                        $laRunden[$liRunde]["vv"][$liSpieler][$lsID]['v'] = $_VERT[$lsID][9] * $laTechsDeffer[$liSpieler]["deff"];
                        $laRunden[$liRunde]["vv"][$liSpieler][$lsID]['h'] = ($_VERT[$lsID][1]+$_VERT[$lsID][2])/10 * $laTechsDeffer[$liSpieler]["deff"];
                        
                        
                            
                        $vv_n[$liSpieler][$lsID] = $liCount;
                    }
                }
            }
        }
        
    }
    
    
    
    //4. ausgabe
    $laWinner = array("awon" => "a", "dwon" => "v", "draw" => "n");
    $lsWinner = $laWinner[$laRet['result']];
    
    /*
    echo "<pre>";
    var_export($laRunden);
    die();*/
    return array("runde" => $laRunden,"sa" => $sa_n, "sv" => $sv_n, "vv" => $vv_n,"winner" => $lsWinner, "battleID" => $liNextBattleId);
}
?>