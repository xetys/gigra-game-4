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

$laRow = $lodb->getOne("SELECT pname,pbild,(SELECT COUNT(*) FROM users WHERE users.mainplanet = planets.coords) as `hp` FROM planets WHERE coords = '{$_SESSION['coords']}'");

$laTplExport = $laRow;

$laTplExport['hp'] = isMoon() ? false : $laTplExport['hp'] == 0;
$laTplExport['pbild'] = isMoon() ? "mond.png" : str_replace(".gif",".png",$laTplExport['pbild']);
$laTplExport["gigrania"] = isGigrania($_SESSION["coords"]);

echo fromTemplate("planetsettings.tpl", $laTplExport);
die();
?>