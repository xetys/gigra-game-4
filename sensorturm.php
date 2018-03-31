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

$lbError = false;
$lsError = '';
if(!checkSensorRange($_GET['scann']))
{
    $lbError = true;
    $lsError = l('sensor_out_of_range');
}
else if(!checkSensorRes())
{
    $lbError = true;
    $lsError = l('sensor_no_h2');
}
else
{

    $laFids = array();
    
    $laParts = explode(":",$_GET['scann']);
    unset($laParts[count($laParts)-1]);
    $lsToCoords = join(":",$laParts) . ":1";
    //du fragst dich jetzt sicher, TSCHÜSCH WAS DAS FÜR SCHEIßE JA? tjo, aber monde scannen is doof und cookie ist zu 120% der erster ders versucht^^
  
    $lsQuery = "SELECT id FROM flotten WHERE (fromc = '$lsToCoords' AND typ != 'stat') OR (fromc = '$lsToCoords' AND typ = 'stat' AND tthere != 0) or (toc = '$lsToCoords' AND typ != 'recy') ORDER BY tback ASC";
    #echo ($lsQuery);
    $lodb->query($lsQuery);
    while($laRow = $lodb->fetch())
        $laFids[] = $laRow[0];
    
    $laTplExport["laFids"] = $laFids;
}

$laTplExport["error"] = $lbError;
$laTplExport["errorText"] = $lsError;


#echo getHeader();
echo fromTemplate("sensorturm.tpl", $laTplExport);
die();
?>