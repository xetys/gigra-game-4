<?php

function checkNoobschutz($punkteAtter,$punkteDeffer)
{
	global $_ACTCONF;
	if($punkteDeffer > $_ACTCONF['noobschutz'] or $punkteDeffer > $punkteAtter)
    {
		return false;
    }
	else 
	{
		return $punkteAtter > $punkteDeffer*$_ACTCONF['noobfaktor'];
	}
}

function checkUMOD($uid)
{
    if(!isset($_SESSION["checkUmod"]) || $uid != Uid())
	{
        $row = gigraDB::db_open()->getOne("SELECT umod FROM users WHERE id = '$uid'");
	    $umod = $row[0];
        if($uid == Uid())
            $_SESSION["checkUmod"] = $umod;
	}
    else
        $umod = $_SESSION["checkUmod"];
        
    
    return $umod > 0;
}

function isInactive($uid)
{
    $laRow = gigraDB::db_open()->getOne("SELECT lastclick FROM users WHERE id = '{$uid}'");
    
    return $laRow[0] < time() - (3600 * 24 * 7);
}

function canGoUmod($uid)
{
    if(!isset($_SESSION['canGoUmod']))
    {
        $laRow = gigraDB::db_open()->getOne("SELECT (COUNT(events.id) + COUNT(produktion.id) + COUNT(flotten.id)) as activities  FROM planets  LEFT JOIN events ON events.coords = planets.coords LEFT JOIN flotten ON toc = planets.coords OR fromc = planets.coords LEFT JOIN produktion ON produktion.coords = planets.coords   WHERE owner = '{$uid}'");
        $liUmod = $laRow[0];
        $_SESSION["canGoUmod"] = $liUmod;
    }
    else
        $liUmod = $_SESSION["canGoUmod"];
    
    return $liUmod == 0;
}

function canLeaveUmod($uid)
{
    $laRow = gigraDB::db_open()->getOne("SELECT umod FROM users WHERE id = '{$uid}'");
    
    return !$laRow ? false : $laRow[0] + (3600 * 48) < time();
}

function isInDeletion($uid)
{
    if(!isset($_SESSION["accDel"]))
    {
        $laRow = gigraDB::db_open()->getOne("SELECT time FROM loeschen WHERE uid = '{$uid}'");
        $_SESSION["accDel"] = $laRow;
    }
    else
        $laRow = $_SESSION["accDel"];
        
    if(!$laRow)
        return 0;
    else
        return $laRow[0];
}

function unsetDeletion($uid)
{
    gigraDB::db_open()->query("DELETE FROM loeschen WHERE uid = '{$uid}'");
}
function setDeletion($uid)
{
    unsetDeletion($uid);
    gigraDB::db_open()->query("INSERT INTO loeschen SET uid = '{$uid}', time = UNIX_TIMESTAMP() + (3600 * 24 * 7)");
}

function isNoob($uid = null)
{
    global $_ACTCONF;
    $uid = $uid == null ? $_SESSION['uid'] : $uid;
    if($uid == null) return false;
    
    $laRow = gigraDB::db_open()->getOne("SELECT pgesamt FROM v_punkte WHERE uid = '{$uid}'");
    
    return $laRow[0] < $_ACTCONF['noobschutz'];
    
    
}

function getNoobSpeed($uid)
{
    global $_ACTCONF;
    if(!defined("HANDLER_MODE"))
        $uid = $uid == null ? $_SESSION['uid'] : $uid;
    
    //im frontend
    if(isset($_SESSION['uid']))
    {
        if(!isset($_SESSION['noobspeed']))
            $_SESSION['noobspeed'] = isNoob($uid) ? $_ACTCONF['noobspeed'] : 1;
        return $_SESSION['noobspeed'];
    }
    //Handler/Backend
    else
        return isNoob($uid) ? $_ACTCONF['noobspeed'] : 1;
}
function isActivated()
{
    return isset($_SESSION['active']) ? $_SESSION['active'] == 1 : false;
}

function isAdmin()
{
    return isset($_SESSION['admin']) && $_SESSION['admin'] > 0;
}

?>