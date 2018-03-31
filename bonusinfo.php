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

if(!isset($_BONUSPACKS[$_GET['id']]))
    die("error");

$laTplExport['bid'] = $_GET['id'];
$laTplExport['liCost'] = $_BONUSPACKS[$_GET['id']]['cost'];
$laItems = getBonusItems();
$laTplExport['avaible'] = isset($laItems[$_GET['id']]) && $laItems[$_GET['id']] > 0;
$laTplExport['buyable'] = getGigron() >= $_BONUSPACKS[$_GET['id']]['cost'];

echo fromTemplate("bonusinfo.tpl", $laTplExport);
?>