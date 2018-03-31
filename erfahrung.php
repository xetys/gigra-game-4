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
$laTplExport = array();

/*
incode brainstorm: skills

Krieg:
Trefferquote
Flugzeiten - level = -1%

Infra:
Planeten + 1
Bauzeiten + 1
Rohstoff

Forschung:
Forschzeit -5% Pro
Geheimes Schiff 1
Geheimes Schiff 2


*/


$laRow = $lodb->getOne("SELECT infra,forsch,krieg FROM erfahrung WHERE uid = '{$_SESSION['uid']}'");
$laSkills = getSkills($_SESSION["uid"]);
$laTplExport = $laSkills;

$liAllPoints = $laRow['infra'] + $laRow['forsch'] + $laRow['krieg'];

$laTplExport['all_level'] = getELevel($liAllPoints);
$liAllSP = $laTplExport['all_level'];
$laTplExport['all_points'] = $liAllPoints;
$laTplExport['all_points_left'] = getNextLevelSum($laTplExport["all_level"]) - $liAllPoints;

$laTplExport["infra_level"] = getELevel($laRow["infra"]);
$laTplExport["infra_left"] = getNextLevelSum($laTplExport["infra_level"]) - $laRow["infra"];
$liInfraSP = ($laSkills["infra_planeten"] + $laSkills["infra_bauzeit"] + $laSkills["infra_rohstoff"]);

$laTplExport["infra_skillpoints"] = $liInfraSP;



$laTplExport["krieg_level"] = getELevel($laRow["krieg"]);
$laTplExport["krieg_left"] = getNextLevelSum($laTplExport["krieg_level"]) - $laRow["krieg"];

$liKriegSP = ($laSkills["krieg_flugzeit"] + $laSkills["krieg_treffer"]);
$laTplExport["krieg_skillpoints"] = $liKriegSP;

$laTplExport["forsch_level"] = getELevel($laRow["forsch"]);
$laTplExport["forsch_left"] = getNextLevelSum($laTplExport["forsch_level"]) - $laRow["forsch"];

$liForschSP = ($laSkills["forsch_zeit"] + $laSkills["forsch_geheimschiff1"] + $laSkills["forsch_geheimschiff2"]);
$laTplExport["forsch_skillpoints"] = $liForschSP;



$liAllSP -= ($liInfraSP + $liForschSP + $liKriegSP);


$liMaxPoints = -1;

if(isset($_GET["add"]))
{
    $lsCheckField = "";
    $lsUpdateField = "";
    switch($_GET["add"])
    {
        case "infra_planeten":
            $lsCheckField = "infra_skillpoints";
            $lsUpdateField = "infra_planeten";
            break;
        case "infra_bauzeit":
            $lsCheckField = "infra_skillpoints";
            $lsUpdateField = "infra_bauzeit";
            break;
        case "infra_rohstoff":
            $lsCheckField = "infra_skillpoints";
            $lsUpdateField = "infra_rohstoff";
            break;
        
        case "krieg_flugzeit":
            $lsCheckField = "krieg_skillpoints";
            $lsUpdateField = "krieg_flugzeit";
            break;
        case "krieg_treffer":
            $lsCheckField = "krieg_skillpoints";
            $lsUpdateField = "krieg_treffer";
            break;
            
        case "forsch_zeit":
            $lsCheckField = "forsch_skillpoints";
            $lsUpdateField = "forsch_zeit";
            $liMaxPoints = 10;
            break;
        case "forsch_geheimschiff1":
            $lsCheckField = "forsch_skillpoints";
            $lsUpdateField = "forsch_geheimschiff1";
            break;
        case "forsch_geheimschiff2":
            $lsCheckField = "forsch_skillpoints";
            $lsUpdateField = "forsch_geheimschiff2";
            break;
    }
    
    if($liAllSP > 0 && ($liMaxPoints == -1 || ($laTplExport[$lsUpdateField] < $liMaxPoints)))
    {
        $lodb->query("UPDATE skills SET `{$lsUpdateField}` = `{$lsUpdateField}` + 1 WHERE uid = '{$_SESSION['uid']}'");
        $laTplExport[$lsCheckField]++;
        $liAllSP--;
        $laTplExport[$lsUpdateField]++;
        
    }
}

if(isset($_GET["sub"]))
{
    $lsCheckField = "";
    $lsUpdateField = "";
    $liUntilLevel = 0;
    switch($_GET["sub"])
    {
        case "forsch_geheimschiff1":
            $lsCheckField = "forsch_skillpoints";
            $lsUpdateField = "forsch_geheimschiff1";
            $liUntilLevel = 13;
            break;
        case "forsch_geheimschiff2":
            $lsCheckField = "forsch_skillpoints";
            $lsUpdateField = "forsch_geheimschiff2";
            $liUntilLevel = 15;
            break;
        case "forsch_zeit":
            $lsCheckField = "forsch_skillpoints";
            $lsUpdateField = "forsch_zeit";
            $liUntilLevel = 9000000;
            break;
    }
    
    if((strlen($lsCheckField) > 0 && $laTplExport[$lsUpdateField] < $liUntilLevel && $laTplExport[$lsUpdateField] > 0) || ($lsCheckField == "forsch_zeit" && $laTplExport["forsch_zeit"] > 0)) //das kann nur durch die Prüfung gesetzt worden sein
    {
        $lodb->query("UPDATE skills SET `{$lsUpdateField}` = `{$lsUpdateField}` - 1 WHERE uid = '{$_SESSION['uid']}'");
        //$laTplExport[$lsCheckField]++;
        $laTplExport['allSP'] = $liAllSP;
        $laTplExport[$lsUpdateField]--;
    }
}

$laTplExport['allSP'] = $liAllSP;

buildPage("erfahrung.tpl", $laTplExport);
?>