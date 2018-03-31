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


//Aktuelle
$liPage = isset($_GET["page"]) ? (int)$lodb->escape($_GET["page"]) : 1;

$liStartFrom = ($liPage - 1) * 10;// 1 -> 0, 2 -> 10, 3 -> 20 usw
$laTplExport["page"] = $liPage;

$laRow = $lodb->getOne("SELECT COUNT(id) FROM bericht WHERE is_public = 1");
$laTplExport["pages"] = ceil($laRow[0] / 10); // 1 => 0.1 -> 1, 6 => 0.6 -> 1, 11 => 1.1 => 2 usw

$laLastKBs = array();
$lodb->query("SELECT id, a_lost, v_lost, winner, time, IF(title = '','".l('kb_kb')."',title) as title FROM bericht WHERE is_public = 1 AND time < UNIX_TIMESTAMP() - 7200 ORDER BY time DESC LIMIT $liStartFrom, 10");

while($laRow = $lodb->fetch("assoc"))
    $laLastKBs[] = $laRow;
    
$laTplExport["last"] = $laLastKBs;


//Top10
$laTopKBs = array();
$lodb->query("SELECT id, a_lost, v_lost, winner, time, IF(title = '','".l('kb_kb')."',title) as title FROM bericht WHERE is_public = 1 AND time < UNIX_TIMESTAMP() - 7200 ORDER BY (a_lost + v_lost) DESC LIMIT 10");

while($laRow = $lodb->fetch("assoc"))
    $laTopKBs[] = $laRow;
    
$laTplExport["top10"] = $laTopKBs;



buildPage("topkbs.tpl", $laTplExport);
?>