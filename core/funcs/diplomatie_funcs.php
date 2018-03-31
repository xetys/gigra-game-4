<?php

/**
 * Gibt wahr aus, wenn zu diesen Koordinaten ein Spieler oder eine Allianz mit laufendem Krieg zu finden ist
 * Enter description here ...
 * @param string $coords
 * @return bool
 */
function inWar($coords)
{
    $lodb = gigraDB::db_open();
	$uid = $_SESSION["uid"];
	$ally_id = $lodb->getOne("SELECT allianz FROM users WHERE id = '$uid'");
	$ally_id = $ally_id[0];
	
	$lsQuery = "SELECT COUNT(*) as c
				FROM planets p 
				LEFT JOIN users u 
					ON p.owner = u.id 
				LEFT JOIN diplomatie du 
					ON 	(
							u.id = du.a_id AND du.b_id = '{$uid}'
						) OR
						(
							u.id = du.b_id AND du.a_id = '{$uid}'
						)
				WHERE coords = '{$coords}' 
				AND  du.begin < UNIX_TIMESTAMP() 
				AND (du.end > UNIX_TIMESTAMP() OR du.end = 0) 
				AND du.b_typ = 's' 
				AND du.a_typ = 's'
				AND du.diplotyp = 'war'
				AND du.status = 2
				UNION ALL
				SELECT COUNT(*) as c FROM planets p LEFT JOIN users u ON p.owner = u.id				

				LEFT JOIN diplomatie da
					ON	(
							u.allianz = da.a_id AND da.b_id = '{$ally_id}'
						) OR
						(
							u.allianz = da.b_id AND da.a_id = '{$ally_id}'
						)
				WHERE coords = '{$coords}'
				AND  da.begin < UNIX_TIMESTAMP() 
				AND (da.end > UNIX_TIMESTAMP() OR da.end = 0) 
				AND da.b_typ = 'a' 
				AND da.a_typ = 'a'
				AND da.diplotyp = 'war'
				AND da.status = 2
				ORDER BY c DESC LIMIT 1
				";
	
	$row = $lodb->getOne($lsQuery);
	if($row[0] == 0)
		return false;
	else
		return true;
}

?>