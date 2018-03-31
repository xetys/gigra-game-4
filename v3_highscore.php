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


$liStart = !isset($_GET['start']) ? 0 : $lodb->escape($_GET['start']);
$liStart = ($liStart - 1) - (($liStart - 1) % 100);// 1 - 100 = 100, 101 - 200 = 200 usw

$laPages = array("punkte","ally","infra","forschung","flotten","deff","level");

$lsPage = isset($_GET['page']) && in_array($_GET['page'],$laPages) ? $lodb->escape($_GET['page']) : "punkte";

switch($lsPage)
{
    case "punkte":
    default:
        $lsQuery = "SELECT      ".
                        "      u.id ".
                        "    , u.name ".
                        "    , vp.pgesamt as punkte".
                        "    , u.allianz ".
                        "    , a.tag ".
                        "    , (SELECT rank FROM user_chronik WHERE user_chronik.uid = vp.uid AND ctime > (UNIX_TIMESTAMP() - (3600 * 24 * 7)) ORDER BY ctime ASC LIMIT 1) as recentRank  ".
                        "    FROM v_punkte vp  ".
                        "    LEFT JOIN users u ON vp.uid = u.id  ".
                        "    LEFT JOIN allianz a ON u.allianz = a.id ".
                        "    ORDER BY pgesamt DESC";
        break;
    case "ally":
        $lsQuery = "SELECT ".
                    "     a.id ".
                    "   , a.tag ".
                    "   , a.name ".
                    "   , SUM( vp.pgesamt ) AS punkte ".
                    "   , COUNT( u.id ) AS member ".
                    "   ,(SUM( vp.pgesamt ) / COUNT( u.id )) AS ppm ".
                    "FROM allianzmember am ".
                    "LEFT JOIN users u ON am.id = u.id ".
                    "LEFT JOIN v_punkte vp ON vp.uid = u.id ".
                    "LEFT JOIN allianz a ON u.allianz = a.id ".
                    "AND u.allianz > '' ".
                    "GROUP BY a.id ".
                    "HAVING a.id IS NOT NULL ".
                    "ORDER BY punkte DESC";
        break;
    case "infra":
        $lsQuery = "SELECT u.id, u.name, vp.planeten as punkte, u.allianz, a.tag FROM v_punkte vp LEFT JOIN users u ON vp.uid = u.id LEFT JOIN allianz a ON u.allianz = a.id ORDER BY planeten DESC";
        break;
    case "forschung":
        $lsQuery = "SELECT u.id, u.name, vp.forschung as punkte, u.allianz, a.tag FROM v_punkte vp LEFT JOIN users u ON vp.uid = u.id LEFT JOIN allianz a ON u.allianz = a.id ORDER BY forschung DESC";
        break;
    case "flotten":
        $lsQuery = "SELECT u.id, u.name, vp.flotten as punkte, u.allianz, a.tag FROM v_punkte vp LEFT JOIN users u ON vp.uid = u.id LEFT JOIN allianz a ON u.allianz = a.id ORDER BY flotten DESC";
        break;
    case "deff":
        $lsQuery = "SELECT u.id, u.name, vp.verteidigung as punkte, u.allianz, a.tag FROM v_punkte vp LEFT JOIN users u ON vp.uid = u.id LEFT JOIN allianz a ON u.allianz = a.id ORDER BY verteidigung DESC";
        break;
    case "level":
        $lsQuery = "SELECT u.id, u.name, u.allianz, a.tag, e.infra, e.krieg, e.forsch, (e.infra+e.krieg+e.forsch) as egesamt FROM erfahrung e LEFT JOIN users u ON e.uid = u.id LEFT JOIN allianz a ON u.allianz = a.id ORDER BY egesamt DESC";
        break;
}

//Limit Setzen
$lsQuery .= " LIMIT $liStart, 100";


//SQL Abfeuern
$lodb->query($lsQuery);

//Daten holen
$laData = array();
while($laRow = $lodb->fetch("assoc"))
    $laData[] = $laRow;

//Ans TPL reichen
$laTplExport["start"] = $liStart;
$laTplExport["page"] = $lsPage;
$laTplExport["data"] = $laData;
$laTplExport['pageData'] = $lodb->getOne("SELECT (SELECT CEIL(COUNT(u.id)/100) FROM users u) as uPages,(SELECT CEIL(COUNT(a.id) / 100) FROM allianz a) AS aPages");

buildPage("v3_highscore.tpl", $laTplExport);
?>