<?php


function computeExp($id)
{
	include_once 'gigraDB.php';
	$db = gigraDB::db_open();
	//InfraPunte
	$lsQuery = "SELECT sum(k1+k2+k3+k4+k5+k6+k7+k8+k9+k10+k11+k12+k13+k14+k15+k16+k17+k18+k19+k20+k21) FROM `users` LEFT JOIN planets ON users.id = planets.owner LEFT JOIN gebaeude g on planets.coords = g.coords WHERE users.id = '$id' GROUP BY users.id";
	$row = $db->getOne($lsQuery);
	$iInfra = $row[0];
	//forschung
	$lsQuery = "SELECT f FROM forschung WHERE uid = '$id'";
	$row = $db->getOne($lsQuery);
	$forsch = ikf2array($row[0]);
	$iForsch = 0;
	foreach ($forsch as $f => $p)
		$iForsch += $p;
		
	$lsInsertQuery = "INSERT INTO erfahrung SET uid='$id', infra = $iInfra, forsch = $iForsch";
	$db->query($lsInsertQuery);
}
function loggedIn()
{
	return isset($_SESSION['uid']) ? true : false;
}

function getVerwarnung($uid)
{
    $lodb = gigraDB::db_open();
    
    $row = $lodb->getOne("SELECT COALESCE(SUM(wertigkeit),0) FROM verwarnung WHERE uid = '$uid' AND verwarndat > (UNIX_TIMESTAMP() - (3600 * 24 * 28 * 6))",60);
	
	return $row[0];
}

function getVerwarnungMore($uid)
{
    $lodb = gigraDB::db_open();
    
	$row = $lodb->getOne("SELECT COALESCE(SUM(wertigkeit),0),(SELECT COUNT(*) FROM verwarnung v2 WHERE v2.uid = verwarnung.uid AND v2.read = 0) as unread FROM verwarnung WHERE uid = '$uid' AND verwarndat > (UNIX_TIMESTAMP() - (3600 * 24 * 28 * 6))",60);
	
	return $row;
}

function verwarnStatus($uid)
{
    $lodb = gigraDB::db_open();
    
	//Regel 1. Verwarnung = nix, böse bböse böse! 2. Verwarnung = 1 Tag Sperre ohne UMod, 3. 1 woche umod, 4. 2 wochen, 5 raus
	
	$aRows = getVerwarnungMore($uid);
	$iVerwarns = $aRows[0];
	$iUnread = $aRows[1];
	
	
	$ret = array("vw" => $iVerwarns, "sperr" => false, "umod" => false,"del" => false, "free" => -1);
	if($iUnread > 0)
		$ret["sperr"] = true;
	
	if($iVerwarns > 1) //ab hier gibs konsequenzen
	{
		$lastVerwarnDat = $lodb->getOne("SELECT MAX(verwarndat) FROM verwarnung WHERE uid = '$uid'");
		$lastVerwarnDat = $lastVerwarnDat[0];
		if($lastVerwarnDat > (time() - (3600 * 24)) && $iVerwarns >= 2)
		{
			$ret["sperr"] = true;
			$ret["free"] = $lastVerwarnDat + (3600*24);
		}
		if($lastVerwarnDat > (time() - (3600 * 24 * 7)) && $iVerwarns >= 3)
		{
			$ret["sperr"] = true;
			$ret["umod"] = true;
			$ret["free"] = $lastVerwarnDat + (3600*24 * 7);
		}
		if($lastVerwarnDat > (time() - (3600 * 24 * 14)) && $iVerwarns >= 4)
		{
			$ret["sperr"] = true;
			$ret["umod"] = true;
			$ret["free"] = $lastVerwarnDat + (3600*24*14);
		}
		if($iVerwarns >= 5)
		{
			$ret["sperr"] = true;
			$ret["umod"] = true;
			$ret["del"] = true;
			$ret["free"] = -1;
		}
	}
	
	return $ret;
}
function isGesperrt($uid)
{
	$verwarn = verwarnStatus($uid);
	return $verwarn["sperr"];
}
function isGesperrtUmod($uid)
{
    $verwarn = verwarnStatus($uid);
	return $verwarn["sperr"] && $verwarn["umod"];
}
function getPlanetCount($uid)
{
    $laRow = gigraDB::db_open()->getOne("SELECT COUNT(*) FROM planets p WHERE p.owner = '$uid' AND p.coords LIKE '%:1'");
    //$laRow = gigraDB::db_open()->getOne("SELECT (SELECT COUNT(*) FROM planets p WHERE p.owner = '$uid' AND p.coords LIKE '%:1')+(SELECT COUNT(*) FROM flotten f WHERE userid = '$uid' AND typ IN ('kolo','inva'))");
    
    return $laRow ? $laRow[0] : 0;
}
function getMaxPlanets($uid)
{
    
    $f = getForschung($uid);
    $laSkills = getSkills($uid);
    
    $liPlancount = 1;
    
    //Plani Verwaltung
    $liPlaVerw = isset($f['f14']) ? $f['f14'] : 0;
    $liPlancount += ($liPlaVerw * 2);
    
    //Skill
    //$liPlanSkill = $laSkills["infra_planeten"];
    //$liPlancount += $liPlanSkill;
    
    return $liPlancount;
    
}
function canGetMorePlanet($uid)
{
    return (getPlanetCount($uid) < getMaxPlanets($uid));
}
function getForschung($uid = null)
{
    if(defined("HANDLER_MODE"))
    {
        if($uid == null) return false;
        $laRow = gigraDB::db_open()->getOne("SELECT f FROM forschung WHERE uid = '{$uid}'");
        $f = ikf2array($laRow[0]);
        
        return $f;
    }
    if($uid == null)
        $uid = $_SESSION["uid"];
    if(!isset($_SESSION["f"]))
        $_SESSION["f"] = array();
        
    if(!isset($_SESSION["f"][$uid]))
    {
        $laRow = gigraDB::db_open()->getOne("SELECT f FROM forschung WHERE uid = '{$uid}'");
        $f = ikf2array($laRow[0]);
        $_SESSION["f"][$uid] = $f;
    }
    
    return $_SESSION["f"][$uid];
}

function getSkills($uid)
{
    $lodb = gigraDB::db_open();
    
    $laRow = $lodb->getOne("SELECT * FROM skills WHERE uid = '{$uid}'");
    
    if(!$laRow)
    {
        $lodb->query("INSERT INTO skills SET uid = '{$uid}'");
        return getSkills($uid);
    }
    else
        return $laRow;
}

function getGigronen($uid)
{
    $lodb = gigraDB::db_open();
    
    $laRow = $lodb->getOne("SELECT gigron_found, gigron_buyed FROM user_gigron WHERE uid = '{$uid}'");
    if(!$laRow)
    {
        $lodb->query("INSERT INTO user_gigron SET uid = '{$uid}', gigron_found = 0, gigron_buyed = 0");
        $laRow = array(0,0);
    }
    
    return $laRow[0] + $laRow[1];
}

function allMinesDown($uid)
{
    $lodb = gigraDB::db_open();
    
    $lodb->query("SELECT coords FROM planets WHERE owner = '{$uid}'");
    while($laRow = $lodb->fetch())
    {
        changeProd($laRow[0],array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0));
    }
}
function allMinesUp($uid)
{
    $lodb = gigraDB::db_open();
    
    $lodb->query("SELECT coords FROM planets WHERE owner = '{$uid}'");
    while($laRow = $lodb->fetch())
    {
        changeProd($laRow[0],array(1 => 10, 2 => 10, 3 => 10, 4 => 10, 5 => 10));
    }
}
function changeNick($uid,$asNewNick)
{
    $lodb = gigraDB::db_open();
    
    if(!$lodb->getOne("SELECT 1 FROM users WHERE id = '{$uid}' AND lastnamechange + (3600 * 24 * 7) > UNIX_TIMESTAMP() OR name = '{$asNewNick}'"))
    {
        $lodb->query("UPDATE users SET name = '{$asNewNick}', lastnamechange = UNIX_TIMESTAMP() WHERE id = '{$uid}'");
        return true;
    }
    else
        return false;
}

function changeMail($uid,$asMail)
{
    $lodb = gigraDB::db_open();
    
    if(!$lodb->getOne("SELECT 1 FROM users WHERE id = '{$uid}' AND lastmailchange + (3600 * 24 * 7) > UNIX_TIMESTAMP() OR email = '{$asMail}'"))
    {
        $lodb->query("UPDATE users SET email = '{$asMail}', lastmailchange = UNIX_TIMESTAMP() WHERE id = '{$uid}'");
        return true;
    }
    else
        return false;
}

function permaActive($uid)
{
    $laRow = gigraDB::db_open()->getOne("SELECT COUNT(*) FROM user_chronik WHERE wasActive = 1 AND uid = '$uid'");
    
    return !$laRow ? false : $laRow[0] >= 7;
}


function getSettings($uid)
{
    $laRow = gigraDB::db_open()->getOne("SELECT * FROM einstellungen WHERE uid = '$uid'");   
    return $laRow;
}

function powerUp($asUserId)
{
    $lodb = gigraDB::db_open();
    
    $lsQuery = "UPDATE rohstoffe SET boost_percent = 2000, boost_until = UNIX_TIMESTAMP() + (3600 * 24 * 7) WHERE coords IN(select coords from planets where owner = '{$asUserId}')";
    $lodb->query($lsQuery);
    
    $lsQuery = "UPDATE user_gigron SET forsch_percent = 2000, forsch_until = UNIX_TIMESTAMP() + (3600 * 24 * 7) WHERE uid = '{$asUserId}' ";
    $lodb->query($lsQuery);
    
    $lsQuery = "UPDATE planets SET bau_percent = 2000, bau_until = UNIX_TIMESTAMP() + (3600 * 24 * 7) WHERE owner = '{$asUserId}'";
    $lodb->query($lsQuery);
    
}
function powerDown($asUserId)
{
    $lodb = gigraDB::db_open();
    
    $lsQuery = "UPDATE rohstoffe SET boost_percent = 0, boost_until = 0 WHERE coords IN(select coords from planets where owner = '{$asUserId}') AND boost_percent = 2000";
    $lodb->query($lsQuery);
    
    $lsQuery = "UPDATE user_gigron SET forsch_percent = 0, forsch_until = 0 WHERE uid = '{$asUserId}' AND forsch_percent = 2000";
    $lodb->query($lsQuery);
    
    $lsQuery = "UPDATE planets SET bau_percent = 0, bau_until = 0 WHERE owner = '{$asUserId}' AND bau_percent = 2000";
    $lodb->query($lsQuery);
    
}

?>