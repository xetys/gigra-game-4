<?php

function ehLog($sMessage,$color = 'no')
{
	$aColorList = array(
		"black" =>	"\033[30m",
		"blue"  => 	"\033[34m",
		"green" =>	"\033[32m",
		"cyan"  =>	"\033[36m",
		"red" 	=> 	"\033[31m",
		"purple" => "\033[35m",
		"yellow" =>	"\033[1;33m",
		"no"		=> "\33[0m"
	);
    $lsDate = date("d.m.Y H:i:s");
	$sPath = "log/eventhandler_".date("d.m.Y").".log";
	
	$sPattern = "[%s] %s".PHP_EOL;
	
	$sLogMsg = sprintf($sPattern,$lsDate,$sMessage);
	
	$oF = fopen($sPath,"a");
	fwrite($oF,$sLogMsg);
	fclose($oF);
	print $aColorList[$color].$sLogMsg.$aColorList["no"];
}


function handle_event($asType,$asID)
{
    $lodb = gigraDB::db_open();
    
    if(in_array($asType,array("build","forsch","prod","vert")))
        $laRow = $lodb->getOne("SELECT `id`, `coords`, `uid`, `starttime`, `time`, `command`, `param`, `prio` FROM `events` WHERE id = '{$asID}'");
    else if(in_array($asType,array("fleet_there","fleet_back")))
        $laRow = $lodb->getOne("SELECT * FROM flotten WHERE id = '{$asID}'");
    else if($asType == "fleetSim")
    {
        $laRow = $lodb->getOne("SELECT `id`, `coords`, `uid`, `starttime`, `time`, `command`, `param`, `prio` FROM `events` WHERE id = '{$asID}'");
        $laRow = $lodb->getOne("SELECT * FROM flotten WHERE id = '{$laRow['param']}'");
    }
    
    switch($asType)
    {
        case "build":  
                handle_build($laRow["id"],$laRow["coords"],$laRow["uid"],$laRow["time"],$laRow["command"],$laRow["param"],$laRow["prio"]);
            break;
        case "forsch":
                handle_forsch($laRow["id"],$laRow["coords"],$laRow["uid"],$laRow["time"],$laRow["command"],$laRow["param"],$laRow["prio"]);
            break;
        case "prod":
        case "vert":
                ehLog("Diese Methode wird nicht mehr verwendet, die Klassen wurden entfernt!","red");
                //handle_prod($laRow["id"],$laRow["coords"],$laRow["uid"],$laRow["time"],$laRow["command"],$laRow["param"],$laRow["prio"]);
                break;
        //Flotten
        case "fleet_back":
            eh_procback($laRow);
            break;
        case "fleet_there":
            handleFleetEvent($laRow);
            break;
        case "fleetSim":
            handleFleetEvent($laRow,true);
            break;
        case "v3prod":
            $loBL = v3Bauliste::getFromID($asID);
            $loBL->updateAll();
            break;
        case "forceCron":
            do_crons();
            $lodb->query("DELETE FROM events WHERE id = '{$asID}'");
            break;
        case "resRecalc":
            $lodb->query("UPDATE rohstoffe SET boost_until = 0 WHERE coords = '{$asID}'");
            resRecalc($asID);
            ehLog("Rohstoffe auf {$asID} werden neu berechnet","green");
            break;
    }
}

function do_crons()
{
    recalcHighscore();
    deleteCron();
    if(date("H") == "10" && date("i") == "00")
        writeChronicle();
}


function killProcessAndChilds($pid,$signal) {
        exec("ps -ef| awk '\$3 == '$pid' { print  \$2 }'", $output, $ret);
        if($ret) return 'you need ps, grep, and awk';
        while(list(,$t) = each($output)) {
            if ( $t != $pid ) {
                killProcessAndChilds($t,$signal);
            }
        }
        //echo "killing ".$pid."\n";
        posix_kill($pid, 9);
    } 
?>