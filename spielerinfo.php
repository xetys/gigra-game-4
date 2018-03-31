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


$row = $lodb->getOne("SELECT planeten AS pgesamt, forschung AS pforsch, flotten AS pflotte, verteidigung AS pvert, (SELECT COUNT(punkte) AS c FROM planets WHERE owner=uid) AS panz FROM user_punkte WHERE uid = '{$_SESSION['uid']}'");
##extract($row);
$laTplExport = $row;

//trefferquote
$laSkills = getSkills($_SESSION["uid"]);

$minTreQuo = round(expSaet($laSkills["krieg_treffer"]),1);

$laTplExport["minTreQuo"] = $minTreQuo;

//max planeten, kampfwerte
$f = getForschung($_SESSION["uid"]);


$f5 = isset($f['f5']) ? $f['f5'] : 0;
$f6 = isset($f['f6']) ? $f['f6'] : 0;
$f7 = isset($f['f7']) ? $f['f7'] : 0;
$f9 = isset($f['f9']) ? $f['f9'] : 0;

$angProz = (1 + ($f5 * 0.1)) * 100;
$defProz = (1 + ($f9 * 0.1)) * 100;

$laTplExport["angProz"] = $angProz;
$laTplExport["defProz"] = $defProz;

$pcount = getMaxPlanets($_SESSION["uid"]);

$laTplExport["phave"] = getPlanetCount($_SESSION["uid"]);
$laTplExport["pmax"] = $pcount;

//Verwarnungen
$verwarn = getVerwarnung($_SESSION[uid]);

$laTplExport["verwarn"] = $verwarn;
//Save Status!! muahaha
/*
    5 = 10% der eigenen Rohstoffe sind klaubar
	4 = 30% 
	3 = 50%
	2 = 65%
	1 = 80%
	0 = >80%
*/
//erst die rohstoffe
$resSaved = 0;
$resNot = 0;

$rowKlaubar = $lodb->getOne("SELECT (SUM(r1)+SUM(r2)+SUM(r3)+SUM(r4)) as klaubar FROM planets LEFT JOIN rohstoffe ON planets.coords = rohstoffe.coords WHERE owner = '{$_SESSION[uid]}'");
$rowRessInFLotte = $lodb->getOne("SELECT COALESCE(SUM(load1)+SUM(load2)+SUM(load3)+SUM(load4),0) as nichklaubar FROM flotten WHERE userid = '$_SESSION[uid]'");

$resSaved += $rowRessInFLotte[0];
$resNot += $rowKlaubar[0];

//nun die schiffe
$lodb->query("SELECT s FROM planets LEFT JOIN schiffe ON schiffe.coords = planets.coords WHERE planets.owner = '$_SESSION[uid]'");
while ($row = $lodb->fetch())
{
	$schiffe = ikf2array($row[0]);
	foreach ($schiffe as $sid => $sc)
		$resNot += ($_SHIP[$sid][1] + $_SHIP[$sid][2] + $_SHIP[$sid][3] + $_SHIP[$sid][4]) * $sc;
}

$lodb->query("SELECT schiffe FROM flotten WHERE userid = '$_SESSION[uid]'");
while ($row = $lodb->fetch())
{
	$schiffe = ikf2array($row[0]);
	foreach ($schiffe as $sid => $sc)
		$resSaved += ($_SHIP[$sid][1] + $_SHIP[$sid][2] + $_SHIP[$sid][3] + $_SHIP[$sid][4]) * $sc;
}
$resSum = $resSaved + $resNot;


$klauQuotient = $resNot / $resSum;
$saveStat = 0;
if($klauQuotient > 0.8)
	$saveStat = 0;
else if($klauQuotient > 0.65 && $klauQuotient <= 0.8)
	$saveStat = 1;
else if($klauQuotient > 0.5 && $klauQuotient <= 0.65)
	$saveStat = 2;
else if($klauQuotient > 0.30 && $klauQuotient <= 0.5)
	$saveStat = 3;
else if($klauQuotient > 0.1 && $klauQuotient <= 0.3)
	$saveStat = 4;
else if($klauQuotient < 0.1)
	$saveStat = 5;
	
$savedProz = round($resSaved/$resSum * 100,1);

$laTplExport["saveStat"] = $saveStat;
$laTplExport["savedProz"] = $savedProz;


$laTplExport["kurse"] = getHandelsKurse();

buildPage("spielerinfo.tpl",$laTplExport);