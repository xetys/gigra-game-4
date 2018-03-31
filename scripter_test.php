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

$liSummand1 = rand(1,10);
$liSummand2 = rand(2,99);

$_SESSION["sum"] = $liSummand1 + $liSummand2;

$laTplExport["a"] = $liSummand1;
$laTplExport["b"] = $liSummand2;

buildPage("v3.tpl", $laTplExport);
?>