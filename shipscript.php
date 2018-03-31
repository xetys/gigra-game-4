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

set_time_limit(0);
ignore_user_abort(true);

define("GIGRA_INTERN", true);
define("GIGRA_MUSTSESSION",true);

include 'core/core.php';

$lodb = gigraDB::db_open();
$lodb2 = gigraDB::db_open();
$lodb3 = gigraDB::db_open();


//Umwandlungsfaktoren

function newCountShip($id,$count)
{
    $laSchiffFaktor = array(
       1  => 0.1,
        2  => 1,
        3  => 0.5,
        4  => 0.1,
        5  => (5 / 12),
        6  => (8 / 25),
        7  => (41 / 22),
        8  => 1,
        9  => (60 / 105),
        10 => (5 / 13),
        11 => (22 / 18),
        12 => 1,
        13 => 1,
        14 => 1,
        15 => 1,
        16 => 1,
        101 => (275 / 90),
        102 => 1,
    );
    
    return ceil($count * $laSchiffFaktor[$id]);
}
function newCountDeff($id,$count)
{
    $laDeffFaktor = array(
        1  => 0.3,
        2  => (3 / 5),
        3  => (44 / 150),
        4  => (104 / 1000),
        5  => (12 / 80),
        6  => (6005 / 14000),
        7  => (67 / 180),
        8  => (9002 / 22500),
    );
    
    return ceil($count * $laDeffFaktor[$id]);
}
function newCountArray($aaShips,$type = "S")
{
    foreach($aaShips as $id => $c)
        $aaShips[$id] = $type == "V" ? newCountDeff($id,$c) :  newCountShip($id,$c);
    
    return $aaShips;
}

//alle Plansen kriegen
$lodb->query("SELECT coords FROM planets");
while($laRow = $lodb->fetch())
{
    //Alle Planeten mit Produktion und Bestand
    $lsCoords = $laRow[0];
    
    //Schiffe
    $laShips = read_schiffe($lsCoords);
    $laNewShips = newCountArray($laShips);
    $lodb2->query("UPDATE schiffe SET s = '".array2ikf($laNewShips)."' WHERE coords = '$lsCoords'");
    
    //Deff
    $laShips = read_vert($lsCoords);
    $laNewShips = newCountArray($laShips,"V");
    $lodb2->query("UPDATE verteidigung SET v = '".array2ikf($laNewShips)."' WHERE coords = '$lsCoords'");
    
    //Produktion
    $lodb2->query("SELECT id, sid, count, typ FROM produktion WHERE coords = '$lsCoords'");
    while($laProdRow = $lodb2->fetch())
    {
        $lsID = $laProdRow["sid"];
        $liCount = $laProdRow["count"];
        $lsTyp = $laProdRow["typ"];
        
        $liNewCount = $lsTyp == "S" ? newCountShip($lsID,$liCount) : newCountDeff($lsID,$liCount);
        
        $lodb3->query("UPDATE produktion SET count = '".$liNewCount."' WHERE id = '".$laProdRow["id"]."'");
    }
    $loBL = new v3Bauliste($lsCoords,true);
    $loBL->retime("S");//zeiten neu laden
    $loBL->retime("V");//zeiten neu laden
    
}    
?>