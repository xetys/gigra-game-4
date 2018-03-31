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
$laTargets = array();

$lodb->query("SELECT coords,comment FROM targets WHERE uid = '".Uid()."'");
    while($laTRow = $lodb->fetch())
        $laTargets[$laTRow['coords']] = coordFormat($laTRow['coords']) . " - " . $laTRow["comment"];


$laTplExport["laTargets"] = $laTargets;
echo fromTemplate("targetliste.tpl", $laTplExport);
die();
?>