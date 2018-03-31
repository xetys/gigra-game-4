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



function getRessBonus($uid,$coords)
{
    $liGrundFaktor = 1;
    $laSkills = getSkills($uid);
    
    //Skill
    $liGrundFaktor = $liGrundFaktor + ($laSkills["infra_rohstoff"] * 0.01);
    
    
    //Booster
    $laRow = gigraDB::db_open()->getOne("SELECT boost_percent,boost_until FROM rohstoffe WHERE coords = '$coords'",3600,"boost");
    if($laRow[1] > time())
        $liGrundFaktor += ($laRow[0]/100);
    
    
    return $liGrundFaktor;
}

function getBauzeitBonus($uid)
{
    $laSkills = getSkills($uid);
    
    //Skill
    //$liGrundFaktor = $liGrundFaktor - ($laSkills["infra_bauzeit"] * 0.01);
    $liProzent = $laSkills['infra_bauzeit'];
    
    //bauzeitbonus
    $laRow = gigraDB::db_open()->getOne("SELECT bau_percent, bau_until FROM planets WHERE coords = '".$_SESSION['coords']."'",3600,"bauzeit");
    if($laRow[1] > time())
        $liProzent += $laRow[0];
    
    $liGrundFaktor = 1 / (1 + ($liProzent/100));
    
    return $liGrundFaktor;
}

function getForschzeitBonus($uid)
{
    $liGrundFaktor = 1;
    $laSkills = getSkills($uid);
    
    //Skill
    //$liSkillFaktor = 1 - ($laSkills["forsch_zeit"] * 0.05);
    $liProzent = ($laSkills["forsch_zeit"]) * 5;
    $liSkillFaktor = 1;
    
    //bauzeitbonus
    $laRow = gigraDB::db_open()->getOne("SELECT forsch_percent, forsch_until FROM user_gigron WHERE uid = '$uid'",3600,"forschzeit");
    if($laRow[1] > time())
        $liProzent += $laRow[0];
    
    $liGrundFaktor = 1 / (1 + ($liProzent/100));
    
    return $liGrundFaktor * $liSkillFaktor;
}

function getFlugzeitBonus($uid)
{
    $liGrundFaktor = 1;
    $laSkills = getSkills($uid);
    
    //Skill
    $liGrundFaktor = $liGrundFaktor - ($laSkills["krieg_flugzeit"] * 0.01);
    
    
    
    return $liGrundFaktor;
}
function getGigron()
{
    $laRow = gigraDB::db_open()->getOne("SELECT (gigron_found + gigron_buyed) as gigrons FROM user_gigron WHERE uid = '".Uid()."'");
    return $laRow[0];
}
function subGigron($aiAmount)
{
    $lodb = gigraDB::db_open();
    $laRow = $lodb->getOne("SELECT gigron_buyed,gigron_found FROM user_gigron WHERE uid = '".Uid()."'");
    
    //haste soviel überhaupt?
    if($aiAmount > ($laRow[0] + $laRow[1]))
        return false;
    
    //erst gekaufte
    $laRow[0] = max(0,$laRow[0] - $aiAmount);
    $liRest = $aiAmount - $laRow[0];
    //nun die gefunden
    if($liRest > 0)
    {   
        //falls wir cheaten wollen, rauskicken
        if($liRest > $laRow[1])
            return false;
        $laRow[1] = max(0,$laRow[1] - $liRest);   
    }
    
    //schreiben
    $lodb->query("UPDATE user_gigron SET gigron_buyed = '{$laRow[0]}', gigron_found = '{$laRow[1]}' WHERE uid = '".Uid()."'");
    return true;
    
}
function getBonusItems()
{
    $lodb = gigraDB::db_open();
    
    $laRow = $lodb->getOne("SELECT items FROM user_gigron WHERE uid = '".Uid()."'");
    return ikf2array($laRow[0]);
    
}
function setBonusItems($aaItems)
{
    $lsItems = array2ikf($aaItems);
    gigraDB::db_open()->query("UPDATE user_gigron SET items = '$lsItems' WHERE uid = '".Uid()."'");
}
function buyBonusItem($itemID)
{
    global $_BONUSPACKS;
    $lodb = gigraDB::db_open();
    
    if(!isset($_BONUSPACKS[$itemID]))
        return -1;
    $liCost = $_BONUSPACKS[$itemID]['cost'];
    
    //check
    if(getGigron() < $liCost)
        return -2;//Nicht genug Gigronen
    else
    {
        //kaufen kaufen kaufen^^
        $laItems = getBonusItems();
        $laItems[$itemID]++;
        setBonusItems($laItems);
        
        if(subGigron($liCost))
            return 1;//is gekooft
        else
            return -2;//Nicht genug Gigronen
    }
    
}
function useBonusItem($itemID)
{
    global $_BONUSPACKS;
    $lodb = gigraDB::db_open();
    
    $laRow = $lodb->getOne("SELECT items FROM user_gigron WHERE uid = '".Uid()."'");
    $laItems = ikf2array($laRow[0]);
    
    if(!isset($laItems[$itemID]) || $laItems[$itemID] == 0)
        return false;
    
    $lbRet = false;
    gigraMC::clearCache("boost");
    gigraMC::clearCache("bauzeit");
    gigraMC::clearCache("forschzeit");
    switch($_BONUSPACKS[$itemID]["type"])
    {
        case "resboost":
            {
                $liPercent =    $_BONUSPACKS[$itemID]["percent"];
                $liUntil = time() + $_BONUSPACKS[$itemID]["duration"];
                
                $lodb->query("UPDATE rohstoffe SET boost_percent = '$liPercent', boost_until = '$liUntil' WHERE coords = '{$_SESSION['coords']}'");
                resRecalc($_SESSION['coords']);
                $lbRet = true;
                break;
            }
        case "buildspeed":
            {
                $liPercent =    $_BONUSPACKS[$itemID]["percent"];
                $liUntil = time() + $_BONUSPACKS[$itemID]["duration"];
                
                $lodb->query("UPDATE planets SET bau_percent = '$liPercent', bau_until = '$liUntil' WHERE coords = '{$_SESSION['coords']}'");
                
                $lbRet = true;
                break;
            }
        case "researchspeed":
            {
                $liPercent =    $_BONUSPACKS[$itemID]["percent"];
                $liUntil = time() + $_BONUSPACKS[$itemID]["duration"];
                
                $lodb->query("UPDATE user_gigron SET forsch_percent = '$liPercent', forsch_until = '$liUntil' WHERE uid = '".Uid()."'");
                
                $lbRet = true;
                break;
            }
        case "battleboost":
            {
                $liPercent =    $_BONUSPACKS[$itemID]["percent"];
                $liUntil = time() + $_BONUSPACKS[$itemID]["duration"];
                
                $lodb->query("UPDATE user_gigron SET kampf_percent = '$liPercent', kampf_until = '$liUntil' WHERE uid = '".Uid()."'");
                
                $lbRet = true;
                break;
            }
    }
    if($lbRet)
    {
        $laItems[$itemID]--;
        if($laItems[$itemID] == 0)
            unset($laItems[$itemID]);
        setBonusItems($laItems);
    }
    
    return $lbRet;
}

function earnGigrons($uid,$aiAmount)
{
    $lodb = gigraDB::db_open();
    
    $laGVF = $lodb->getOne("SELECT gvf FROM v_gvf WHERE uid = '".$uid."'");
    
    $liEarnFaktor = min(1,max(0,round($laGVF[0],3)));
    
    $aiAmount *= $liEarnFaktor;
    
    
    $lodb->query("UPDATE user_gigron SET gigron_found = gigron_found + $aiAmount WHERE uid = '{$uid}'");
    
    //nachricht senden
    send_cmd_msg($uid,"-",array("x" => 28,"a" => $aiAmount),time());
}

function goodPoints($asUid,$aiAmount,$aiDays)
{
   $lodb = gigraDB::db_open();
   
   $lodb->query("INSERT INTO user_gvf (uid,good_points,valid_until) VALUES('$asUid',$aiAmount,".(time() + (3600 * 24 * $aiDays)).");");
}
function badPoints($asUid,$aiAmount,$aiDays)
{
   $lodb = gigraDB::db_open();
   
   $lodb->query("INSERT INTO user_gvf (uid,bad_points,valid_until) VALUES('$asUid',$aiAmount,".(time() + (3600 * 24 * $aiDays)).");");
}