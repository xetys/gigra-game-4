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

if(isset($_REQUEST["type"]))
{
    switch($_REQUEST["type"])
    {
        case "flotten":
            echo showFlotten($_REQUEST["coords"]);
            break;
        case "galaxie":
            echo showGalaxy($_POST["g"].":".$_POST["s"]);
            break;
        case "getmission":
            {
                $result = listMissions($_POST["fromc"],$_POST["toc"],$_POST["ship"]);
                if(is_array($result))
                    echo json_encode($result[0]);
                else
                    echo "[{error:".$result."}]";
                break;   
            }
        case "sendfleet":
            {
                //Datenaufbearbeiten
                $laSchiffe = $_POST["ship"];
                $lsFrom = $_POST["fromc"];
                $lsTo = $_POST["toc"];
                $lsMission = $_POST["mission"];
                $liSpeedSelect = (int)min(max($_POST["speed_select"],1),10);//int = aus 2.34343 wird 2, aus 121212 wird 10, aus -232 wird 1;
                $laRes = $_POST["res"];
                $laSpecialCommand = ikf2array($_POST["sc"]);
                
                echo sendFleet($lsFrom,$lsTo,$laSchiffe,$lsMission,$liSpeedSelect,$laRes,$laSpecialCommand);
                break;
            }
        case "sendprobes":
            {
                $laSchiffe = read_schiffe($_SESSION['coords']);
                $laSettings = getSettings(Uid());
                
                if(!isset($laSchiffe[3]) || $laSchiffe[3] == 0)
                    die("-30");
                
                $liSpioAnz = min($laSettings['spioanz'],$laSchiffe[3]);
                
                echo sendFleet($_POST["fromc"],$_POST["toc"],array("3" => $liSpioAnz),"spio",10,array(0,0,0,0),"");
                break;
                
            }
        case "reloadevents":
            {
                echo showFleetList();
                break;   
            }
        case "fleetback":
            {
                $lsFid = $_POST['fid'];
                die(fleetBack($lsFid,Uid()) ? "ok" : "notok");
                break;
            }
        case "getAksFleets":
            {
                $lsTo = $_POST["toc"];
                //AKS flotten
                $laAKSFleets = array();
                $lsQuery = "SELECT flotten.id,	users.name FROM flotten LEFT JOIN users ON flotten.userid = users.id WHERE typ = 'aks_lead' AND tthere != 0 AND userid = '{$_SESSION['uid']}'  AND toc = '{$lsTo}' UNION ALL ".
                            "SELECT 	f2.id ,	u2.name FROM users u1 LEFT JOIN users u2 ON u1.allianz = u2.allianz LEFT JOIN flotten f2 ON f2.userid = u2.id WHERE u1.id = '{$_SESSION['uid']}' AND f2.typ = 'aks_lead' AND f2.tthere != 0 AND f2.toc = '{$lsTo}'";
                $lodb->query($lsQuery);
                while($laRow = $lodb->fetch())
                {
                    $laAKSFleets[$laRow[0]] = $laRow[1];
                }
                
                if(count($laAKSFleets) == 0)
                    echo -1;
                else
                    echo json_encode($laAKSFleets);
                
                break;
            }
        case "cancelBauliste":
            {
                $sid =  $_POST['sid'];
                $loBauliste = new v3Bauliste($_SESSION["coords"],false);
                var_dump($loBauliste->remove($sid));
                
                break;
            }
        case "renamePlan":
            {
                $lsPlanName = $lodb->escape($_POST['name']);
                $lodb->query("UPDATE planets SET pname = '$lsPlanName' WHERE coords = '{$_SESSION['coords']}'");
                die("ok");
                break;
            }
            
        case "hpPlan":
            {
                hpPlanet();
                die("ok");
                break;
            }
        case "leavePlan":
            {
                leavePlanet($_SESSION['coords']);
                die("ok");
                break;
            }
        case "useBonusItem":
            {
                $lsId = $_POST['id'];
                if(!isset($_BONUSPACKS[$lsId]))
                    die('{"error":1,"text":"'.l('bonus_item_not_avaible').'"}');
                else
                {
                     $lbRet = useBonusItem($lsId);
                     
                     if($lbRet)
                        die('{"error":0,"text":"'.l('bonus_success').'"}');
                     else
                     {
                        die('{"error":1,"text":"'.l('bonus_item_not_avaible').'"}');
                     }
                }
            }
        case "buyBonusItem":
            {
                $lsId = $_POST['id'];
                if(!isset($_BONUSPACKS[$lsId]))
                    die('{"error":1,"text":"'.l('bonus_item_not_avaible').'"}');
                else
                {
                     $liRet = buyBonusItem($lsId);
                     
                     if($liRet == 1)
                        die('{"error":0,"text":"'.l('bonus_buyed').'"}');
                     else
                     {
                        $lsError = $liRet == -2 ?  l('bonus_not_enough_gigrons'): l('bonus_item_not_avaible');
                        die('{"error":1,"text":"'.$lsError.'"}');
                     }
                }
            }
        case "saveTarget":
            {
                $lsCoords = $lodb->escape($_POST["coords"]);   
                $lsComment = $lodb->escape($_POST['comment']);
                
                $laCount = $lodb->getOne("SELECT COUNT(*) FROM targets WHERE uid = '".Uid()."' AND coords = '{$lsCoords}'");
                if($laCount[0] == 0)
                    $lodb->query("INSERT INTO targets SET uid = '".Uid()."', coords = '$lsCoords', comment = '$lsComment'");
                
                echo showMyTargets();
                break;
            }
        case "deleteTarget":
            {
                $lsCoords = $lodb->escape($_POST["coords"]);  
                
                $lodb->query("DELETE FROM targets WHERE uid = '".Uid()."' AND coords = '$lsCoords'");
                
                echo showMyTargets();
                break;
            }
        case "planetInfo":
            {
                die(showPlanetInfo($_POST['coords']));
                break;
            }
        case "powerCollect":
            {
                $lsCoords = $_POST["coords"];
                $lsPrio = empty($_POST["prio"]) ? "0,1,2,3" : $_POST["prio"];
                $lsShipTypes = empty($_POST["ships"]) ? "12,13" : $_POST["ships"];
                
                $lbListOnly = empty($_POST["test"]) ? false : $_POST["test"] == '1' ? true : false;
                
                $laSend = powerCollect($lsCoords,$lsPrio,$lsShipTypes,$lbListOnly);
                if(!is_array($laSend))
                    die("-");
                die("<table width='100%'><tr><th>".l('high_coords')."</th><th>".l('res1')."</th><th>".l('res2')."</th><th>".l('res3')."</th><th>".l('res4')."</th></tr>".join($laSend)."</table>");
                break;
            }
        case "allFleetBack":
            {
                allFleetsBack();
                die("ok");
                break;   
            }
        case "test":
            {
                die("test");   
            }
        default:
            die("no valid type");
    }
}
else
{
        die("no valid type");
}
?>