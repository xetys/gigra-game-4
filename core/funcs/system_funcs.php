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

function redirect($asURL)
{
	header("Location:$asURL");
	die();
}
function dbg($type)
{
    if(defined("HANDLER_MODE"))
        echo var_export($type,true) . PHP_EOL;
    else
        echo "<pre>".var_export($type,true)."</pre>";
}
function lock($name)
{
    $f = fopen(ROOT_PATH."/tmp/{$name}.lock","w");
    fwrite($f,"1");
    fclose($f);
}
function unlock($name)
{
    unlink(ROOT_PATH."/tmp/{$name}.lock");
}
function isLocked($name)
{
    return file_exists(ROOT_PATH."/tmp/{$name}.lock");
}
function waitUnlock($name)
{
    $i = 0;
    while(isLocked($name))
    {
        if($i > 100) break;
        $i++;
        usleep(100000);
    } 
}
function nicenum($aiNum,$aiDec = 0)
{
    return number_format($aiNum,$aiDec,',','.');   
}
function fromTemplate($asTplName,$aaTplVars)
{
	$loTemplateSys = new Template(ROOT_PATH . "/tpl/", ROOT_PATH . "/tmp/");
	
	foreach($aaTplVars as $sKey => $loValue)
		$loTemplateSys->addVar($sKey, $loValue);
		
	return $loTemplateSys->fetch($asTplName);
}
/**
 * Gibt Den typen der Koords wieder
 *
 * @param string $coords
 * @return int
 */
function coordType($coords)
{
    $array = explode(":",$coords);
	return $array[3];
}
function coordFormat($coords,$on = 'galaxy')
{
    $raw_coords = $coords;
	$parts = explode(":", $coords);
	$type = array_pop($parts);
	$coords = join(":", $parts);
	switch ($type)
	{
		case 1:
			$typeText = l("planet");
			break;
		case 2:
			$typeText = l("moon");
			break;
        case 3:
            $typeText = l("tf");
            break;
		default:
			$typeText = "Planet";
			break;
	}
	
	$coords .= " ".$typeText;
	
    switch($on)
    {
        case "galaxy":
        default:
            return "<a href='galaxie.php?to=$parts[0]:$parts[1]'>".$coords."</a>";
        case 'change':
            return "<a href='?change=$raw_coords'>".$coords."</a>";
    }
}

function coordReform($coords,$type)
{
    $laCoords = explode(":",$coords);
    if(count($laCoords) == 4)
    {
        $laCoords[3] = "$type";
        $coords = join(":",$laCoords);
    }
    else if(count($laCoords) == 4)
    {
        $coords = join(":",$laCoords) .":$type";
    }
    else
        return false;
    
    return $coords;
}
function getHeader()
{
	global $_ACTCONF,$_RUNDE;
	startProfile("getHeader");
    
    $iNewMSG = 0;
    if(loggedIn())
    {
    	startProfile("msgCount");
    	$iNewMSG = getUnreadCount(); 
        endProfile('msgCount');
        startProfile("readRes_getHeader");
    	$laRes = read_res($_SESSION['coords']);
        endProfile("readRes_getHeader");
    }
    else
    {
        $laRes = array();
    }
    endProfile("getHeader");
    $host = $_ACTCONF['url'];
    if ($host[strlen($host) -1] == "/") {
        $host = substr($host, 0, strlen($host) - 1);
    }
	return fromTemplate("header.tpl", array(
		"title" => $_ACTCONF["name"],
		"gameURL" => $_ACTCONF["url"],
        "gameHost" => $host,
		"iNewMSG" => $iNewMSG,
        "sPHPSelf" => $_SERVER['PHP_SELF'],
        "actConf" => $_ACTCONF,
        "myURL" => "http://www.gigra-game.de/{$_RUNDE}/".Uid(),
        "laRes" => $laRes
		));
}
function userCount()
{
    $laRow = gigraDB::db_open()->getOne("SELECT COUNT(*) FROM users");   
    
    return $laRow[0];
}
function onPageLoad()
{
	global $_ACTCONF;
    startProfile("onPageLoad");
    if(($_ACTCONF["offline"]) && $_SERVER["PHP_SELF"] != "/offline.php" && !isAdmin())
    {
        redirect("offline.php");
    }
	
	if(loggedIn())
	{
        startProfile("onPageLoad_dbStart");
        $dbo = gigraDB::db_open();
        endProfile("onPageLoad_dbStart");
        /*
		//Umsiedlungsskript
		$UMSIEDLUNG = true;
		if($UMSIEDLUNG && isset($_SESSION['uid']) && ($_SERVER["PHP_SELF"] != '/umsiedlung.php' AND $_SERVER["PHP_SELF"] != '/galaxie.php'))
		{
			$blMustSettle = false;
			$count = $dbo->getOne("SELECT COUNT(*) FROM planets WHERE owner = '{$_SESSION[uid]}' AND SUBSTRING_INDEX(coords,':',1) > {$_ACTCONF["maxgala"]} AND coords NOT LIKE '%:2'");
			if($count[0] > 0)
				$blMustSettle = true;
			if($blMustSettle)
				redirect("umsiedlung.php");
		}*/
		
		//Sperrung
		startProfile("onPageLoad_Sperr");
		if(isGesperrt($_SESSION["uid"]) && $_SERVER["PHP_SELF"] != '/verwarnung.php' && $_SERVER["PHP_SELF"] != '/nachrichten.php' )
			redirect("verwarnung.php");
        endProfile("onPageLoad_Sperr");
        
        // hack check
        startProfile("onPageLoad_HackCheck");
        if($_SERVER['PHP_SELF'] != "/login.php")
        {
            if ($_SERVER['REMOTE_ADDR'] !== $_SESSION['last_ip'] ||$_SERVER['HTTP_USER_AGENT'] !== $_SESSION['last_useragent'])
            {
                session_destroy();
                unset($_SESSION);
                redirect("index.php");
            }
            
            $_SESSION['last_useragent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
        }
        endProfile("onPageLoad_HackCheck");
        startProfile("onPageLoad_Update");
        // weiter im programm, sag mir was und wer du bist
        if($_SERVER['PHP_SELF'] != "/nachrichten.php" && $_SERVER['QUERY_STRING'] != "ajax=1")
        {
            if(!isset($_SESSION["last_save"]))
                $_SESSION["last_save"] = time();
            $lsSessionID = session_id();
            if($_SESSION["last_save"]+30 < time())
            {
                $dbo->query("UPDATE users SET lastsession = '$lsSessionID', lastip='{$_SERVER['REMOTE_ADDR']}', lastpage='{$_SERVER['PHP_SELF']}', lastqry = '{$_SERVER['QUERY_STRING']}', lastclick = UNIX_TIMESTAMP() WHERE id = '{$_SESSION['uid']}'");
                $_SESSION["last_save"] = time();
            }
        }
        endProfile("onPageLoad_Update");
        startProfile("onPageLoad_PlanCheck");
        if(isset($_SESSION['coords']))
        {
            $x = $dbo->getOne("SELECT COUNT(*) FROM planets WHERE owner='$_SESSION[uid]' AND coords='$_SESSION[coords]'");
            
            if($x[0] != 1) //Besitzt der User diesen Planeten nicht mehr?
            {
                //ergo: Der aktuell ausgewaehlte Planet wurde gerade uebernommen
                $z = $dbo->getOne("SELECT COUNT(*) FROM planets WHERE owner='$_SESSION[uid]' AND coords='$_SESSION[mainplan]'");
                if($z[0] == 0) //User besitzt Hauptplanet nicht mehr
                {
                    //Einen der naechsten Planeten auswaehlen
                    $y = $dbo->getOne("SELECT coords FROM planets WHERE owner='$_SESSION[uid]'");
                    $_SESSION['coords'] = $y[0];
                    //Hauptplanet nicht mehr gueltig
                    $dbo->query("UPDATE users SET mainplanet='$y[0]' WHERE id='$_SESSION[uid]'");
                }
                else
                {
                    //User besitzt Hauptplanet: Dahin wechseln!
                    $_SESSION['coords'] = $_SESSION['mainplan'];
                }
              redirect("/v3.php?change=$_SESSION[coords]"); 
            }
            unset($x);
            unset($y);
        }
        else
        {
            $laRow = $dbo->getOne("SELECT mainplanet FROM users WHERE id = '{$_SESSION["uid"]}'");
            $_SESSION["coords"] = $laRow[0];
        }
        endProfile("onPageLoad_PlanCheck");
        //Planetenwechsel
        if(isset($_GET["change"]))
        {
            $laRow = $dbo->getOne("SELECT COUNT(*) FROM planets WHERE owner = '{$_SESSION["uid"]}' AND coords = '{$_GET["change"]}'");
            if($laRow[0] > 0)
            {
                //Kann den Planeten erfolgreich wechseln   
                $_SESSION["coords"] = $_GET["change"];
               
            }
        }
        
	}
    endProfile("onPageLoad");
}
function buildPage($asTplName,$aaTplVars)
{
	global $_ACTCONF;
	
    startProfile("buildPage");
	echo getHeader();
	echo fromTemplate($asTplName,$aaTplVars);
    echo fromTemplate("footer.tpl", array(
        "host" => $_SERVER['HTTP_HOST']
    ));
	endProfile("buildPage");
	 //session reinigen
    unset($_SESSION["can_forsch"]);
    unset($_SESSION["can_ship"]);
    unset($_SESSION["can_deff"]);
    unset($_SESSION["k"]);
    unset($_SESSION["f"]);
    unset($_SESSION["checkUmod"]);
    unset($_SESSION["canGoUmod"]);
    unset($_SESSION["accDel"]);
    unset($_SESSION['noobspeed']);
    unset($_SESSION['msg']);
    unset($_SESSION['timeprofiles']);
    unset($_SESSION["donequery"]);
    unset($_SESSION["read_res"]);
    gigraMC::clearCache("resource");
	die();
}

function simpleHeader()
{
    $laSHonPages = array(
        "/kb.php",
        "/kampfsim.php",
        "/sensorturm.php",
        "/imperium.php",
        "/e500.php",
        "/offline.php",
        "/dberror.php",
    );   
    
    return in_array($_SERVER['PHP_SELF'],$laSHonPages);
}

function Uid()
{
    return $_SESSION["uid"];
}
function whereAttacked()
{
    $lodb = gigraDB::db_open();
    
    $laPlans = array();
    $lodb->query("SELECT toc FROM flotten LEFT JOIN planets ON flotten.toc = planets.coords WHERE owner = '".Uid()."' AND typ IN ('ag','ag_p','aks','aks_lead','inva','dest') AND tthere > 0 AND tsee < UNIX_TIMESTAMP() GROUP BY toc");
    while($laRow = $lodb->fetch("row"))
        $laPlans[] = $laRow[0];
    
    return count($laPlans) > 0 ? $laPlans : false;
}
function isNotify()
{
    $lbRet = false;
    //Acc entfernern
    if(isInDeletion(Uid()) > 0)
        $lbRet = true;
    
    //Umod
    if(checkUmod(Uid()))
        $lbRet = true;
    //asperre
    if(getAngrriffSperre() > time())
        $lbRet = true;
    if(!isActivated())
        $lbRet = true;
    if(whereAttacked())
        $lbRet = true;
    return $lbRet;
}
function isAngriffSperre()
{
    return getAngrriffSperre() > time();
}

//Zeitmessungs funktionen
function startProfile($asName)
{
    if(!isset($_SESSION["timeprofiles"]))
        $_SESSION["timeprofiles"] = array();
    
    $_SESSION["timeprofiles"][$asName] = microtime(true);
}

function endProfile($asName)
{
    $_SESSION["timeprofiles"][$asName] = microtime(true) - $_SESSION["timeprofiles"][$asName];
}

function listProfiles()
{
    $laProfiles = isset($_SESSION["timeprofiles"]) ? $_SESSION["timeprofiles"] : array();
    
    foreach($laProfiles as $asName => $aiTime)
    {
        if($aiTime > 600)//nicht beendete Zeit
            continue;
        else
        {
            $lsSec = $asName != "querycount" ? "sec" : "";
            echo "$asName : ". round($aiTime,2) . "$lsSec<br>".PHP_EOL;
        }
    }
    
    foreach($_SESSION["donequery"] as $i => $lsQuery)
        echo ($i+1) . " $lsQuery <br>".PHP_EOL;
}

function keepChars($aaChars,$asString)
{
    $lsNewString = "";
    for($i=0;$i<strlen($asString);$i++)
    {
           if(in_array($asString[$i],$aaChars))
            $lsNewString .= $asString[$i];
    }
    
    return $lsNewString;
}


function killSession($asSessionId, $abResume = true)
{
    session_cache_limiter('nocache');
    if($abResume)
        $lsMySession = session_id();
    
    session_commit();
    session_id($asSessionId);
    session_start();
    
    //$_SESSION = array();
    
    //dbg($_SESSION);
    //die(session_id());    
    
    session_destroy();
    session_commit();
    
    //resume
    if($abResume)
    {
        session_id($lsMySession);
        session_start();
    }
}

function killUserSession($uid, $abResume = true)
{
    $laRow = gigraDB::db_open()->getOne("SELECT lastsession FROM users WHERE id = '$uid'");
    
    //if(is_array($laRow) and count($laRow) == 1)
        killSession($laRow[0], $abResume);
        
}

?>