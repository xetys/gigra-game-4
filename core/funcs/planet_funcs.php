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


function createPlanet($id,$coords = false,$aiDia = -1)
{
    global $_ACTCONF;
	
	$lodb = gigraDB::db_open();
	if(!$coords)
	{
		$aRow = $lodb->getOne("SELECT coords FROM planets ORDER BY RAND() LIMIT 1");
		if(!$aRow)
			$coords = "1:1:1:1";
		else
		{
			$bFoundPlanet = false;
			do 
			{
				$tokens = explode(":", $aRow[0]);
				$sG = $tokens[0]; //Gleiche galaxie
				$sS = max(rand($tokens[1] - 2, $tokens[1] + 2),1);
				if($sS > $_ACTCONF["maxsys"])
				{
					$sG++;//naechste galaxy
					$sS = 1;
				}
				
				
				$x = $lodb->getOne("SELECT maxp FROM maxplanets WHERE sys='$sG:$sS'");
				if($x == false)
				{
				   $maxp = mt_rand(3,20);
				   $lodb->query("INSERT INTO maxplanets SET maxp='$maxp', sys='$sG:$sS'");
				}
				else
					$maxp = $x[0];
				$sP = rand(1,$maxp);
				
				$sCoords = "$sG:$sS:$sP:1";
				
				$iPlanetsOnThisPos = $lodb->getOne("SELECT COUNT(*) FROM planets WHERE coords = '$sCoords'");
				if($iPlanetsOnThisPos[0] == 0)
				{
					$coords = $sCoords;
					$bFoundPlanet = true;
				}
				else 
					$aRow = $lodb->getOne("SELECT coords FROM planets ORDER BY RAND() LIMIT 1");
			}
			while (!$bFoundPlanet);
		}
	}
 	$dia = $aiDia == - 1 ? mt_rand(5000,20000) : $aiDia;
  	$temp = mt_rand(-273,500);

  	$bild = "planet".mt_rand(1,10).".png";
      
    switch(coordType($coords))
    {
        case 1:
        {
            $pname = l("planet");
            
            $laCoords = explode(":",$coords);
            $planiPos = $laCoords[2];
            
            if($planiPos > 0 and $planiPos < 5)
            {
                $dia = mt_rand(2000,8000);
                $temp = mt_rand(200,480);
            }
            else if($planiPos > 4 and $planiPos < 8)
            {
                $dia = mt_rand(5000,20000);
                $temp = mt_rand(-150,300);
            }
            else if($planiPos > 7 and $planiPos < 12)
            {
                $dia = mt_rand(8000,15000);
                $temp = mt_rand(-200,0);
            }
            else
            {
                $dia = mt_rand(5000,12000);
                $temp = mt_rand(-273,-15);
            }
            
            $resQry = "INSERT INTO rohstoffe SET coords='$coords',r1=5000,r2=5000,r3=5000,r4=0,u1=UNIX_TIMESTAMP()";
            $buildQry = "INSERT INTO gebaeude SET coords='$coords', k1=1";
            
            //direkt ein TF erzeugen
            getTF($coords);
            break;
        }
        case 2:
            $pname = l("moon");
            $buildQry = "INSERT INTO gebaeude SET coords='$coords'";
            $resQry = "INSERT INTO rohstoffe SET coords='$coords', u1=UNIX_TIMESTAMP()";
            break;
        case 3:
            $pname = 'TF';
            $buildQry = "INSERT INTO gebaeude SET coords='$coords'";
            $resQry = "INSERT INTO rohstoffe SET coords='$coords', u1=UNIX_TIMESTAMP()";
            break;
        default:
            $pname = "-";
            $buildQry = "INSERT INTO gebaeude SET coords='$coords'";
            $resQry = "INSERT INTO rohstoffe SET coords='$coords', u1=UNIX_TIMESTAMP()";
            break;
        
    }
	//Planet hinzufuegen
	  
	$lodb->query("INSERT INTO planets SET coords='$coords',owner='$id',pname='$pname',temp='$temp',dia='$dia',pbild='$bild',punkte='0'");
	
	$lodb->query($resQry);
    $lodb->query($buildQry);
    
    $lodb->query("INSERT INTO schiffe SET coords='$coords',s=''");
	$lodb->query("INSERT INTO verteidigung SET coords='$coords',v=''");
    
    
	return $coords;
}

function getBuildings($coords = null)
{
    //kein verfickter Cache im eh du Hurensohn!
    if(defined("HANDLER_MODE"))
    {
        $laRow = gigraDB::db_open()->getOne("SELECT k1,k2,k3,k4,k5,k6,k7,k8,k9,k10,k11,k12,k13,k14,k15,k16,k17,k18,k19,k20,k21 FROM gebaeude WHERE coords = '$coords'");
        return $laRow;
    }
    if($coords == null)
        $coords = $_SESSION["coords"];
    if(!isset($_SESSION["k"]))
        $_SESSION["k"] = array();
    if(!isset($_SESSION["k"][$coords]))
    {
        $laRow = gigraDB::db_open()->getOne("SELECT k1,k2,k3,k4,k5,k6,k7,k8,k9,k10,k11,k12,k13,k14,k15,k16,k17,k18,k19,k20,k21 FROM gebaeude WHERE coords = '$coords'");
        $_SESSION["k"][$coords] = $laRow;
    }
    else
        $laRow = $_SESSION["k"][$coords];
    
    return $laRow;
}
function canForsch($coords = null)
{
    if($coords == null)
        $coords = $_SESSION["coords"];
    if(!isset($_SESSION["can_forsch"]))
    {
        $k = getBuildings($coords);
        $_SESSION["can_forsch"] = isset($k['k2']) && $k['k2'] > 0 ? true : false;
    }    
    
    return $_SESSION["can_forsch"];
}
function canShip($coords = null)
{
    if($coords == null)
        $coords = $_SESSION["coords"];
    if(!isset($_SESSION["can_ship"]))
    {
        $k = getBuildings($coords);
        $_SESSION["can_ship"] = isset($k['k13']) && $k['k13'] > 0 ? true : false;
    }    
    
    return $_SESSION["can_ship"];
}
function canDeff($coords = null)
{
    if($coords == null)
        $coords = $_SESSION["coords"];
    if(!isset($_SESSION["can_deff"]))
    {
        $k = getBuildings($coords);
        $_SESSION["can_deff"] = isset($k['k14']) && $k['k14'] > 0 ? true : false;
    }    
    
    return $_SESSION["can_deff"];
}

function isMoon($asCoords = null)
{
    return coordType($asCoords == null ? $_SESSION['coords'] : $asCoords) == 2;   
}


function getSensorRange()
{
    if(!isMoon()) return 0;
    
        //Sensorturm?
    $k = getBuildings($_SESSION["coords"]);
    
    $liSensorStufe = $k['k20'];
    if($liSensorStufe == 0) return -1;
    
    return pow($liSensorStufe,3) - 1;

}


function checkSensorRange($asTo)
{
    $laParts = explode(":",$asTo);
    if(count($laParts) != 4 || !is_numeric($laParts[1]))
        return false;
    
    $laMyParts = explode(":",$_SESSION["coords"]);
    if($laMyParts[0] != $laParts[0])
        return false;
    $liMySystem = $laMyParts[1];
    $liToSystem = $laParts[1];
    
    return (abs($liMySystem - $liToSystem)) <= getSensorRange();
}

function checkSensorRes()
{
    global $_ACTCONF;
    $laRes = read_res($_SESSION["coords"]);
    if($laRes[3] >= $_ACTCONF["sensor_cost"])
    {
        sub_res(array(0,0,0,$_ACTCONF["sensor_cost"]),$_SESSION['coords']);
        return true;
    }
    else
        return false;
}

function hasMoon()
{
    if(isMoon())
        return false;
    $laRow = gigraDB::db_open()->getOne("SELECT COUNT(*) FROM planets WHERE coords = '".substr($_SESSION['coords'],0,-2).":2'");
    
    return $laRow[0] > 0;
}

function hpPlanet($asCoords = null)
{
    $lodb = gigraDB::db_open();
    $asCoords = $asCoords == null ? $_SESSION['coords'] : $asCoords;
    
    if(coordType($asCoords) == 1)
    {
        //SQL rockt
        $lodb->query("UPDATE users SET mainplanet = '$asCoords' WHERE id = '".Uid()."' AND (SELECT COUNT(*) FROM planets WHERE owner = users.id AND coords = '$asCoords')");
    }
}

function leavePlanet($asCoords)
{
    $lodb = gigraDB::db_open();
    $asCoords = $lodb->escape($asCoords);
    $lodb->query("UPDATE planets SET owner='0' WHERE coords = '$asCoords'");
    changeProd($asCoords,array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0));
    
}

function destroyPlanet($asCoords)
{
    $lodb = gigraDB::db_open();
    
    //Flotten koordinieren   
    
    //Zu diesem Planeten alle zurück
    $lodb->query("SELECT id,userid FROM flotten WHERE toc = '{$asCoords}' AND tthere > 0");
    while($laRow = $lodb->fetch())
        fleetBack($laRow[0],$laRow[1]);
    
    //alle planeten von diesem Planet
    if(isMoon($asCoords))
        $lodb->query("UPDATE flotten SET fromc = '".substr($asCoords,0,-1)."1' WHERE fromc = '{$asCoords}'");
    else
        $lodb->query("UPDATE flotten SET fromc = (SELECT planets.coords FROM planets WHERE planets.owner = flotten.userid AND planets.coords != '{$asCoords}' ORDER BY RAND() LIMIT 1) WHERE fromc = '{$asCoords}'");
    
    //Planet auf zerstört setzen
    
    $lodb->query("UPDATE planets SET owner = '0', destructed = 1 WHERE coords = '{$asCoords}'");
    
    if(coordType($asCoords) > 1)
    {
        //Totale Vernichtung
        $lodb->query("DELETE from planets WHERE coords = '{$asCoords}'");
        $lodb->query("DELETE from gebaeude WHERE coords = '{$asCoords}'");
        $lodb->query("DELETE from rohstoffe WHERE coords = '{$asCoords}'");
        $lodb->query("DELETE from events WHERE coords = '{$asCoords}'");
        $lodb->query("DELETE from bauschleife WHERE coords = '{$asCoords}'");
        $lodb->query("DELETE from schiffe WHERE coords = '{$asCoords}'");
        $lodb->query("DELETE from verteidigung WHERE coords = '{$asCoords}'");
        $lodb->query("DELETE from planets WHERE coords = '{$asCoords}'");
    }
    if(!isMoon($asCoords))
    {
        //ALLES KAPUTT MACHEN!!!
        destroyPlanet(substr($asCoords,0,-1)."2");//Mond
    }
}
function invadePlanet($asCoords,$asUid)
{
     $lodb = gigraDB::db_open();  
     
     
     //Planet
     $lodb->query("UPDATE planets SET owner = '$asUid' WHERE coords = '$asCoords'");
     $lsCoords = coordReform($asCoords,2);
     //Mond
     $lodb->query("UPDATE planets SET owner = '$asUid' WHERE coords = '$lsCoords'");
     
     //Alle Flotten
     $lsCoords = substr($asCoords,0,-1)."%";
     $lodb->query("UPDATE flotten SET userid = '$asUid' WHERE fromc LIKE '$lsCoords'");
     
     
}


function isGigrania($asCoords)
{
    return $asCoords == "1:1:10:1";
}