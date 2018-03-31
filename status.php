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
define("GIGRA_MUSTSESSION",false);

include 'core/core.php';

$lodb = gigraDB::db_open();
$laTplExport = array();

$laRow = $lodb->getOne("SELECT 
    (SELECT COUNT(*) FROM users) as userCount,
    (SELECT COUNT(*) FROM users WHERE lastclick + (3600*24) > UNIX_TIMESTAMP()) as userActive,
    (SELECT COUNT(*) FROM users WHERE lastclick + (3600*24*3) > UNIX_TIMESTAMP()) as userPassive,
    (SELECT COUNT(*) FROM users WHERE lastclick + (3600*24*14) < UNIX_TIMESTAMP()) as userInactive,
    (SELECT COUNT(*) FROM users WHERE lastclick + (600) > UNIX_TIMESTAMP()) as userOnline,
    (SELECT COUNT(*) FROM planets WHERE destructed = 0) as planetCount,
    (SELECT COUNT(*) FROM planets WHERE destructed = 0 AND owner = '0') as planetLeave,
    (SELECT (SELECT SUM(r1) + SUM(r2) + SUM(r3) + SUM(r4) + SUM(tf1) + SUM(tf2) + SUM(tf3) + SUM(tf4)  as rohstoffe FROM rohstoffe) + (SELECT SUM(load1) + SUM(load2) + SUM(load3) + SUM(load4) FROM flotten)) as Rohstoffe
");
$laStatus = array(
     "status_uni" => $_ACTCONF['name'],
     "status_galaxies" => $_ACTCONF['maxgala'],
     "status_systems" => $_ACTCONF['maxsys'],
     "status_buildspeed" => $_ACTCONF['speed_build'],
     "status_resspeed" => $_ACTCONF['speed_res'],
     "status_fleetspeed" => $_ACTCONF['speed_fleet'],
     "status_noobspeed" => $_ACTCONF["noobspeed"],
     "status_resources" => $laRow['Rohstoffe'],
     
     "status_maxuser" => $_ACTCONF['maxuser'],
     "status_usercount" => $laRow['userCount'],
     "status_active" => $laRow['userActive'],
     "status_passive" => $laRow['userPassive'],
     "status_inactive" => $laRow['userInactive'],
     "status_online" => $laRow['userOnline'],
     "status_planets" => $laRow['planetCount'],
     "status_leaved" => $laRow['planetLeave'],
);

if(!isAdmin())
{
    unset($laStatus['status_maxuser']);
    unset($laStatus['status_usercount']);
    unset($laStatus['status_active']);
    unset($laStatus['status_passive']);
    unset($laStatus['status_inactive']);
    unset($laStatus['status_online']);
    unset($laStatus['status_planets']);
    unset($laStatus['status_leaved']);
}
$laTplExport['list'] = $laStatus;
if(isset($_GET['only']) && isset($laStatus[$_GET['only']]))
    die($laStatus[$_GET['only']]);


if(isset($_GET['serialized']) && $_GET['serialized'] == 1)
    die(serialize($laStatus));
if(isset($_GET['extern']) && $_GET['extern'] == 1)
    die(fromTemplate("status.tpl", $laTplExport));
buildPage("status.tpl", $laTplExport);
?>