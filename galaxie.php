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


$coords = isset($_GET['to']) ? $_GET['to'] : $_SESSION['coords'];
$laCoordParts = explode(":",$coords);

$laTplExport["g"] = $laCoordParts[0];
$laTplExport["s"] = $laCoordParts[1];

buildPage("galaxie.tpl", $laTplExport);
?>