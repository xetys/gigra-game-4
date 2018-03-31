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

define("GIGRA_INTERN", true);
define("GIGRA_MUSTSESSION",true);

include 'core/core.php';

$lodb = gigraDB::db_open();

if(isset($_GET['do']))
{
    $lsID = $lodb->escape($_GET['do']);
    $laRow = $lodb->getOne("SELECT u.id, u.name,p.pgesamt,u.werbestatus FROM users u LEFT JOIN v_punkte p ON u.id = p.uid WHERE u.id = '$lsID'");
    if(!(!$laRow))
    {
        $status = $laRow[3];
        $lbPermaActive = permaActive($laRow[0]);
        if($status == 0 && $lbPermaActive)
        {
            //juhu, kriegst cash
            earnGigrons(Uid(),150000);
            $lodb->query("UPDATE users SET werbestatus = 2 WHERE id = '{$laRow[0]}'");
        }
        
        if($status == 2 && $laRow[2] >= $_ACTCONF["noobschutz"])
        {
            //juhu, kriegst cash
            earnGigrons(Uid(),$laRow[2]);
            $lodb->query("UPDATE users SET werbestatus = 4 WHERE id = '{$laRow[0]}'");
        }
        
    }
}

$laTplExport = array();

$laTplExport["myURL"] = "http://www.gigra-game.de/{$_RUNDE}/".Uid();

$laGVF = $lodb->getOne("SELECT gvf FROM v_gvf WHERE uid = '".Uid()."'");

$liGVF = round($laGVF[0] * 100,1);

$laTplExport["gvf"] = $liGVF;

//haben wir geworbene spieler
$lodb->query("SELECT u.id, u.name,p.pgesamt,u.werbestatus FROM users u LEFT JOIN v_punkte p ON u.id = p.uid WHERE u.werberid = '".Uid()."'");
$laPlayers = array();
while($laRow = $lodb->fetch())
{   
    $status =  $laRow[3];
    $lbPermaActive = permaActive($laRow[0]);
    if($status == 0 && $lbPermaActive)
        $status = 1;
    if($status == 2 && $laRow[2] >= $_ACTCONF["noobschutz"])
        $status = 3;
    
    $laPlayers[] = array("id" => $laRow[0],"permaActive" => $lbPermaActive, "name" => $laRow[1], "punkte" => $laRow[2], "noob" => $laRow[2] < $_ACTCONF["noobschutz"],"status" => $status);
    
}

$laTplExport["players"] = count($laPlayers) > 0 ? $laPlayers : false;

buildPage("earngigron.tpl", $laTplExport);
?>