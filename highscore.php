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

$laTplExport = array();
$lodb = gigraDB::db_open();

///////////////////////
/*

-sprache
-inaktive herforheben 1woche
*/



$lidboffset = (isset($_POST['a']) && is_numeric($_POST['a'])) ? $_POST['a'] : 1;
$lidboffset = $lidboffset-1;
$lsHighscoretyp = isset($_POST['s']) ? $_POST['s'] : 'u';
$liAnzahl = 1;

switch($lsHighscoretyp)
{
    case 's':
        //Flotten
        $liAnzahl = $lodb->getOne('SELECT COUNT(*) as x FROM users');
        $liAnzahl = $liAnzahl['x'];
        $lsQuery = "SELECT users.name, lastlogin, flotten AS punkte,uid, allianz.tag as aname, allianz.id as aid ".
        "FROM user_punkte LEFT JOIN users ON uid=id LEFT JOIN allianz ON users.allianz = allianz.id ORDER BY punkte DESC LIMIT 100 OFFSET $lidboffset;";        
		break;
    case 'f':
        //Forschung
        $liAnzahl = $lodb->getOne('SELECT COUNT(*) as x FROM users');
        $liAnzahl = $liAnzahl['x'];
        $lsQuery = "SELECT users.name, lastlogin, forschung AS punkte,uid, allianz.tag as aname, allianz.id as aid ".
        "FROM user_punkte LEFT JOIN users ON uid=id LEFT JOIN allianz ON users.allianz = allianz.id  ORDER BY punkte DESC LIMIT 100 OFFSET $lidboffset;";        
		break;
    case 'd':
        //verteitigung
        $liAnzahl = $lodb->getOne('SELECT COUNT(*) as x FROM users');
        $liAnzahl = $liAnzahl['x'];
        $lsQuery = "SELECT users.name, lastlogin, verteidigung AS punkte,uid, allianz.tag as aname, allianz.id as aid ".
        "FROM user_punkte LEFT JOIN users ON uid=id LEFT JOIN allianz ON users.allianz = allianz.id  ORDER BY punkte DESC LIMIT 100 OFFSET $lidboffset;";        
    	break;
    case 'g':
        //Herrschafft
        $liAnzahl = $lodb->getOne('SELECT COUNT(*) as x FROM users');
        $liAnzahl = $liAnzahl['x'];
        $lsQuery = "SELECT name, lastlogin,IF(users.admin = 1,0,((SELECT COUNT(*) as c FROM planets WHERE owner=id) / (SELECT COUNT(*) as c FROM planets) * 100)) AS galaxie_besitz,id FROM users ORDER BY galaxie_besitz DESC LIMIT 100 OFFSET $lidboffset;";        
    	break;
    case 'l':
        //level
        $liAnzahl = $lodb->getOne('SELECT COUNT(*) as x FROM users');
        $liAnzahl = $liAnzahl['x'];
        $lsQuery = "SELECT u.name, u.lastlogin,IF(u.admin = 1,0,(infra+forsch+krieg)) as allp, infra,krieg,forsch FROM users u LEFT JOIN erfahrung e ON u.id = e.uid ORDER BY allp DESC LIMIT 100 OFFSET $lidboffset;";
    	break;
	case 'p':
        //Planeten
        $liAnzahl = $lodb->getOne('SELECT COUNT(*) as x FROM users;');
        $liAnzahl = $liAnzahl['x'];
        $lsQuery = "SELECT users.id, users.lastlogin, users.name, v_punkte.planeten FROM v_punkte LEFT JOIN users ON v_punkte.uid=users.id ORDER BY v_punkte.planeten DESC LIMIT 100 OFFSET $lidboffset";
		break;
    case 'pd':
        //Planeten
        $liAnzahl = $lodb->getOne('SELECT COUNT(*) as x FROM users;');
        $liAnzahl = $liAnzahl['x'];
        $lsQuery = "SELECT users.id, users.lastlogin, users.name, (SELECT v_punkte.planeten/COUNT(*) FROM planets WHERE owner=users.id) AS planeten FROM v_punkte LEFT JOIN users ON v_punkte.uid=users.id ORDER BY planeten DESC LIMIT 100 OFFSET $lidboffset";
		break;
    case 'a':
        //Allianzen
        $liAnzahl = $lodb->getOne('SELECT COUNT(*) as x FROM allianz');
        $liAnzahl = $liAnzahl['x'];
        $lsQuery = "SELECT
          ( 
            SELECT COUNT(users.id) FROM users
            WHERE users.allianz=allianz.id
          ) as c
          ,
          (
            SELECT COALESCE(SUM(
            (
              SELECT COALESCE((planeten + forschung + flotten + verteidigung),0) FROM user_punkte
                WHERE uid=users.id
            )),0) as p
            FROM users
            WHERE users.allianz=allianz.id
          ) as p
          ,allianz.tag
          ,allianz.id
          ,COALESCE((
            SELECT COALESCE(SUM(
            (
              SELECT COALESCE((planeten + forschung + flotten + verteidigung),0) FROM user_punkte
                WHERE uid=users.id
            )),0) as p
            FROM users
            WHERE users.allianz=allianz.id
          )/
          ( 
            SELECT COUNT(users.id) FROM users
            WHERE users.allianz=allianz.id
          ),0) as ds
          FROM allianz ORDER BY p DESC
          LIMIT 100 OFFSET $lidboffset";        
		break;
    case 'y':
        //Sonnensysteme
        $liAnzahl = $lodb->query("SELECT SUM(punkte) as p, SUBSTRING_INDEX(coords, ':', 2) as c,maxp,COUNT(punkte) as cp,SUM(punkte)/maxp as ds FROM planets,maxplanets WHERE SUBSTRING_INDEX(coords, ':', 2)=maxplanets.sys GROUP BY SUBSTRING_INDEX(coords, ':', 2)");
        $liAnzahl = $lodb->numrows($liAnzahl);
        $lsQuery = "SELECT SUM(punkte) as p, SUBSTRING_INDEX(coords, ':', 2) as c,maxp,COUNT(punkte) as cp,SUM(punkte)/maxp as ds FROM planets,maxplanets WHERE SUBSTRING_INDEX(coords, ':', 2)=maxplanets.sys GROUP BY SUBSTRING_INDEX(coords, ':', 2) ORDER BY p DESC LIMIT 100 OFFSET $lidboffset";        
    	break;        
    case 'u':
	default:
		//Spieler
        $liAnzahl = $lodb->getOne('SELECT COUNT(*) as x FROM users');
        $liAnzahl = $liAnzahl['x'];
        $lsQuery = "SELECT users.name, lastlogin, (planeten + forschung + flotten + verteidigung) AS punkte,uid,(SELECT rank FROM user_chronik WHERE user_chronik.uid = user_punkte.uid AND ctime > (UNIX_TIMESTAMP() - (3600 * 24 * 7)) ORDER BY ctime ASC LIMIT 1) as recentRank, allianz.tag as aname, allianz.id as aid ".
        "FROM user_punkte LEFT JOIN users ON uid=id LEFT JOIN allianz ON users.allianz = allianz.id ORDER BY punkte DESC LIMIT 100 OFFSET $lidboffset";
		break;
}
        $lodb->query($lsQuery);
        while($row = $lodb->fetch())
            $laHighscore[] = $row;


    //$laTplExport[''] = ;

    $lidboffset= $lidboffset+1;
    $laTplExport['lidboffset'] = $lidboffset;
    $laTplExport['laHighscore'] = $laHighscore;
    $laTplExport['lsHighscoretyp'] = $lsHighscoretyp;
    $laTplExport['liAnzahl'] = $liAnzahl;
    buildPage("highscore.tpl", $laTplExport);

?>