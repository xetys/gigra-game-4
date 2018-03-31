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

function recalcHighscore()
{
    $lodb = gigraDB::db_open();
    $lodb2 = gigraDB::db_open();
    $lodb3 = gigraDB::db_open();
    
    ehLog("Starte Punkteberechnung","yellow");
    
    $laKeepIDs = array();
    $lsK = "";
    for($i=1;$i<=21;$i++) $lsK .= ", k$i";
    
    $lodb->query("SELECT id,f,admin FROM users LEFT JOIN forschung ON users.id = forschung.uid");
    
    while($laRow = $lodb->fetch())
    {
        $liPALL = 0;
        $liPBuild = 0;
        $liPFleet = 0;
        $liPDef = 0;
        $liPForsch = 0;
        
        //Planeten
        if($laRow['admin'] == 0 and !isGesperrtUmod($laRow["id"]))
        {
            $lodb2->query("SELECT planets.coords{$lsK},s,v FROM planets LEFT JOIN gebaeude ON planets.coords = gebaeude.coords LEFT JOIN schiffe ON planets.coords = schiffe.coords LEFT JOIN verteidigung ON planets.coords = verteidigung.coords WHERE owner = '{$laRow['id']}'");
            while($laBuildRow = $lodb2->fetch())
            {
                //Gebs
                $liPPlanet = 0;
                for($i=1;$i<=21;$i++)
                {
                    $liPPlanet += bpunkte($i,$laBuildRow['k'.$i]);
                    
                }
                $liPBuild += $liPPlanet;
                $liPALL += $liPPlanet;
                
                $lodb3->query("UPDATE planets SET punkte = '{$liPPlanet}' WHERE coords = '{$laBuildRow['coords']}'");
                
                
                //Schiffe
                $liPlanetShipsP = 0;
                $laShips = read_schiffe($laBuildRow['coords']);//ikf2array($laBuildRow['s']);
                foreach($laShips as $id => $count)
                {
                    $liPlanetShipsP += spunkte($id,$count);
                }
                $liPFleet += $liPlanetShipsP;
                $liPALL += $liPlanetShipsP;
                
                $lodb3->query("UPDATE schiffe SET punkte = '{$liPlanetShipsP}' WHERE coords = '{$laBuildRow['coords']}'");
                
                //Def
                $liPlanetDefP = 0;
                $laShips = read_vert($laBuildRow['coords']);//ikf2array($laBuildRow['v']);
                foreach($laShips as $id => $count)
                {
                    $liPlanetDefP += vpunkte($id,$count);
                }
                
                $liPDef += $liPlanetDefP;
                $liPALL += $liPlanetDefP;
                
                $lodb3->query("UPDATE verteidigung SET punkte = '{$liPlanetDefP}' WHERE coords = '{$laBuildRow['coords']}'");
            }
            
            //Forschung
            $laForschung = ikf2array($laRow['f']);
            foreach($laForschung as $lsID => $liLvl)
            {
                $liPForsch += fpunkte(substr($lsID,1),$liLvl);
            }
            $liPALL += $liPForsch;
            $lodb3->query("UPDATE forschung SET punkte = '{$liPForsch}' WHERE uid = '{$laRow['id']}'");
            
            //fliegende Flotten
            $lodb2->query("SELECT schiffe FROM flotten WHERE userid = '{$laRow['id']}'");
            while($laFleetRow = $lodb2->fetch())
            {
                $laShips = ikf2array($laFleetRow[0]);
                foreach($laShips as $id => $count) 
                {
                    $liP_tmp = spunkte($id,$count);
                    $liPFleet += $liP_tmp;
                    $liPALL += $liP_tmp;
                }
            }
        }
        else
        {
            //Punkte reset
            $lodb2->query("SELECT coords FROM planets WHERE owner = '{$laRow['id']}'");
            while($laPlanRow = $lodb2->fetch())
            {
                $lsCoords = $laPlanRow[0];
                $lodb3->query("UPDATE planets SET punkte = 0 WHERE coords = '$lsCoords'");   
                $lodb3->query("UPDATE schiffe SET punkte = 0 WHERE coords = '$lsCoords'");   
                $lodb3->query("UPDATE verteidigung SET punkte = 0 WHERE coords = '$lsCoords'");   
            }
            $lodb3->query("UPDATE forschung SET punkte = 0 WHERE uid = '{$laRow['id']}'");   
        }
        //Abschluss
        $lodb3->query("DELETE FROM user_punkte WHERE uid = '{$laRow['id']}'");
        $lodb3->query("INSERT INTO user_punkte (uid,planeten,forschung,flotten,verteidigung) VALUES('{$laRow['id']}','{$liPBuild}','$liPForsch','$liPFleet','$liPDef')");
        
        //ID eintragen
        $laKeepIDs[] = "'{$laRow['id']}'";
    }
    
    //Saubermachen
    //$lodb3->query("DELETE FROM user_punkte WHERE uid NOT IN(".join(",",$laKeepIDs).")");
    
    //Ranking
    $liRank = 1;
	$lodb->query( "SELECT uid,pgesamt FROM v_punkte ORDER BY pgesamt DESC" );
	while ( $row = $lodb->fetch () ) {
		$lodb2->query( "UPDATE user_punkte SET rank = $liRank WHERE uid = '{$row[0]}'" );
		$liRank++;
	}
    
    ehLog("Punkteberechnung abgeschlossen","green");
}
function writeChronicle()
{
    $lodb = gigraDB::db_open();
    $lodb2 = gigraDB::db_open();
    
    //alte saetze raus
    $lodb->query("DELETE FROM user_chronik WHERE ctime < (UNIX_TIMESTAMP() - (3600*24*14));");
    //neue sätze rein
    $lodb->query("INSERT INTO user_chronik (uid,ctime,wasActive,punkte,rank) SELECT u.id as uid,UNIX_TIMESTAMP() as ctime, (u.lastclick > UNIX_TIMESTAMP() - (3600*24)) as wasActive, p.pgesamt as punkte, p.rank FROM users u LEFT JOIN v_punkte p ON u.id = p.uid;");
   
}
function deleteCron()
{
    $lodb = gigraDB::db_open();
    
    //Nachrichten
    $lodb->query("DELETE FROM msg WHERE time < (UNIX_TIMESTAMP() - (3600 *24*4)) AND ordner != 'archive'");
    //Logs
    $lodb->query("DELETE FROM log WHERE time < (UNIX_TIMESTAMP() - (3600 *24*14))");
    //Users, aber dies bleibt ersma aus
    $lodb->query("DELETE FROM users WHERE id IN (SELECT uid FROM loeschen WHERE time < UNIX_TIMESTAMP())");
    
}
function recalcHighscore_BU()
{
    $lodb = gigraDB::db_open();
    $lodb2 = gigraDB::db_open();
    $lodb3 = gigraDB::db_open();
    
    //ehLog("Starte Punkteberechnung","yellow");
    
    
    //Planeten
	$result = $lodb->query( 'SELECT * FROM gebaeude' );
	while ( $row = $lodb->fetch() ) {
		$p = 0;
		foreach ( $row as $i => $k ) {
			if (substr ( $i, 0, 1 ) == 'k') {
				$id = substr ( $i, 1 );
				$level = $k;
				$p += bpunkte ( $id, $level );
			}
		}
		$lodb2->query( "UPDATE planets SET punkte='$p' WHERE coords='{$row['coords']}'" );
	}
    
    //Forschung
	$result = $lodb->query( 'SELECT uid,f FROM forschung' );
	while ( $row = $lodb->fetch() ) {
		$p = 0;
		$k_ikf = ikf2array ($row[1]);
		foreach ( $k_ikf as $i => $k ) {
			if (substr ( $i, 0, 1 ) == 'f') {
				$id = substr ( $i, 1 );
				$level = $k;
				$p += fpunkte ( $id, $level );
			}
		}
		$lodb2->query( "UPDATE forschung SET punkte='$p' WHERE uid='$row[0]'" );
	
	}
    
    //Schiffe
	$result = $lodb->query( 'SELECT coords,s FROM schiffe' );
	while ( $row = $lodb->fetch() ) {
		$p = 0;
		$a = 0;
		$k_ikf = ikf2array ( $row [1] );
		foreach ( $k_ikf as $i => $k ) {
			$a += $k;
			$p += spunkte($i, $k);
		}
		$lodb2->query( "UPDATE schiffe SET punkte='$p',anzahl = '$a' WHERE coords='$row[0]'" );
	}
    
    //Deff
	$result = $lodb->query( 'SELECT coords,v FROM verteidigung' );
	while ( $row = $lodb->fetch() ) {
		$p = 0;
		$a = 0;
		$k_ikf = ikf2array ( $row [1] );
		foreach ( $k_ikf as $i => $k ) {
			$a += $k;
			$p += vpunkte($i, $k);
		}
		$lodb2->query( "UPDATE verteidigung SET punkte='$p', anzahl = '$a' WHERE coords='$row[0]'" );
	}
    
    
	//Nun fuer den User
	$result = $lodb->query( "SELECT id,admin FROM users" );
	while ( $row = $lodb->fetch() ) {
		if ($row ['admin'] == 1) {
			$pgesamt = $pforsch = $pflotte = $pvert = 0;
		} else {
			//Planetenpunkte (p)/Planetenanzahl (c)/Forschungspunkte (f)
			$row1 = $lodb2->getOne("SELECT (SELECT SUM(punkte) AS p FROM planets WHERE owner=id) AS p, (SELECT COUNT(punkte) AS c FROM planets WHERE owner=id) AS c, (SELECT punkte FROM forschung WHERE uid=id) AS f FROM users WHERE id='{$row[0]}'" );
			$panz = $row1 ['c'];
			$pgesamt = $row1 ['p'];
			$pforsch = $row1 ['f'];
			
			$pflotte = 0;
			$pvert = 0;
			//Flotten und Def:
			$res = $lodb2->query( "SELECT coords FROM planets WHERE owner = '{$row[0]}'" );
			while ( $row1 = $lodb2->fetch() ) {
				$row2 = $lodb3->getOne( "SELECT (SELECT punkte FROM schiffe WHERE planets.coords=schiffe.coords) AS s, (SELECT punkte FROM verteidigung WHERE planets.coords=verteidigung.coords) AS v FROM planets WHERE coords = '{$row1[0]}'" );
				$pflotte += $row2 ['s'];
				$pvert += $row2 ['v'];
			}
			//und bitte alles was fliegt!
			$res = $lodb2->query( "SELECT schiffe FROM flotten WHERE userid = '{$row[0]}'" );
			while ( $row1 = $lodb2->fetch() ) {
				$p = 0;
				$k_ikf = ikf2array ( $row1 [0] );
				foreach ( $k_ikf as $i => $k ) {
					$pflotte += spunkte($i, $k);
				}
			}
		
		
		}
		//Eintragen:
		$is = $lodb2->getOne( "SELECT COUNT(*) AS count FROM user_punkte WHERE uid = '{$row[0]}'" );
		if ($is[0] > 0)
			$lodb2->query( "UPDATE user_punkte SET planeten = '{$pgesamt}', forschung = '{$pforsch}', flotten = '{$pflotte}', verteidigung = '{$pvert}' WHERE uid = '{$row[0]}'" );
		else
			$lodb2->query( "INSERT INTO user_punkte SET planeten = '{$pgesamt}', forschung = '{$pforsch}', flotten = '{$pflotte}', verteidigung = '{$pvert}', uid = '{$row[0]}'" );
		
	}
    //Ranking
	$rank = 1;
	$res = $lodb->query( "SELECT uid,(planeten+forschung+flotten+verteidigung) as allp FROM user_punkte ORDER BY allp DESC" );
	while ( $row = $lodb->fetch () ) {
		$lodb2->query( "UPDATE user_punkte SET rank = $rank WHERE uid = '{$row[0]}'" );
		$rank ++;
	}
	
	//ehLog("Punkteberechnung abgeschlossen","green");
}