<?php
if(!isset($INC['kampf_funcs']))
{
  $INC['kampf_funcs'] = true;
  /**
  *Berechnet wie viel mit $c Recyclern recycled werden kann.
  *@param $c int Anzahl der Recycler
  *@return double Vorfaktor fuer Rohstoffe
  */
  function recy($c)
  {
    $min = round(min((sqrt(($c  )*35)),100)*100);
    $max = round(min((sqrt(($c+2)*35)),100)*100);
    return mt_rand($min,$max)/10000;
  }
function shuffle_with_keys(&$array) 
{
    /* Auxiliary array to hold the new order */
    $aux = array();
    /* We work with an array of the keys */
    $keys = array_keys($array);
    /* We shuffle the keys */
    shuffle($keys);
    /* We iterate thru' the new order of the keys */
    foreach($keys as $key) {
      /* We insert the key, value pair in its new order */
      $aux[$key] = $array[$key];
      /* We remove the element from the old array to save memory */
      unset($array[$key]);
    }
    /* The auxiliary array with the new order overwrites the old variable */
    $array = $aux;
 }

 /**
  * KampFormel nach allgemeiner Logik
  *
  * @param int $a_count
  * @param int $v_count
  * @param int $a_ang
  * @param int $v_def
  * @return int (Anzahl restlicher Schiffe)
  */
function formel_kampf($a_count,$v_count,$a_ang,$v_def,$trefferQuote = 10,$rapidfire = 1)
{
    $schaden = rand(60,100);
    //28.12.2011 - nun echtes RF
    $schuss = $a_count;// * max(1,$rapidfire);
    
    if($schuss < 0)
		echo "komische anzahl $a_count<br>";
	$treffer = ($schuss * (rand($trefferQuote,100)/100));
    
	
	$angWert = $treffer * ($schaden / 100) * $a_ang;
	
	//$iRest = max(round((1 / ($v_def)) * (($v_count * $v_def) - ($angWert)) ),0);
    $iTmp = ((($v_def * $v_count) - $angWert) / ($v_def * $v_count)) * $v_count;
    $iTmp = max($iTmp,0);
    $iAbsorb = $v_def * ($v_count - $iTmp);
    
    $iRest = ceil($iTmp);
    //$iRest = (round(($v_count / ($v_count * $v_def)) * (($v_count * $v_def) - ($angWert)) ));

	//echo "($v_count / ($v_count * $v_def)) * (($v_count * $v_def) - ($angWert)) = $iRest<br/>";
	
	return array("rest" => $iRest, "schuss" => $schuss, "treffer" => $treffer, "schaden" => $angWert, "absorb" => $iAbsorb);
}
function chooseRange($aRange)
{
	//key summe
	$kSum = 0;
	foreach($aRange as $k => $data) $kSum = max($k,$kSum);
	$choose = rand(1,$kSum);
	#echo "choosed $choose from $kSum<br>";
	foreach ($aRange as $k => $data) if ($choose <= $k) return $data;
}
function buildRange($in)
{
	$rangecounter = 0;
	$range = array();
	foreach ($in  as $type => $data)
	{
		
		foreach($data as $spieler => $schiffe)
		{
			foreach($schiffe as $id => $anz)
			{
				$rangecounter += $anz;
				$range[$rangecounter] = array($spieler,$id,$anz,$type);
			}
		}
	}
	return $range;
}
function period($liCount)
{
    return max(1,pow(10,strlen("$liCount")-3));   
}
 /**
* Allianz-Kampfsystem
* @param $fa Array aufgeloestes IKF der Konstruktionen des Angreifers
* @param $sa Array aufgeloestes IKF Schiffe Angreifer
* @param $kv Array Konstruktionen Verteidiger
* @param $fv Array Forschungen Verteidiger
* @param $sv Array Schiffe Verteidiger
* @param $vv Array Verteidigungstuerme Verteidiger
* @param $debug [optional] int
*/
function aks($fa,$sa,$kv,$fv,$sv,$vv,$debug=0)
 {
 	global $_SHIP, $_VERT;
 	
 	$runden_ret = array();
 	$runden_gesamt = 0;
 	
	//Angriffsboni/malu
	$AngBonus = array();
    $AngBonusDeff = array();
	
 	$sa_n = $sa;
 	$sv_n = $sv;
 	$vv_n = $vv;
 	
 	$fv_quote = $fa_quote = array();
 	
 	foreach ($sv as $a => $data)
 	{
 		$fv[$a]['f5'] = isset($fv[$a]['f5']) ? $fv[$a]['f5'] : 0;
 		$fv[$a]['f6'] = isset($fv[$a]['f6']) ? $fv[$a]['f6'] : 0;
 		$fv[$a]['f7'] = isset($fv[$a]['f7']) ? $fv[$a]['f7'] : 0;
 		$fv[$a]['f9'] = isset($fv[$a]['f9']) ? $fv[$a]['f9'] : 0;
 		$fv[$a]['klevel'] = isset($fv[$a]['klevel']) ? $fv[$a]['klevel'] : 0;
 		
 		$fv_ang[$a] = 1 + (($fv[$a]['f5'] * 0.05 ) + ($fv[$a]['f6'] * 0.05 ) +  ($fv[$a]['f7'] * 0.05 ));
 		$fv_panz[$a] = 1 + ($fv[$a]['f9'] * 0.1);
 		
 		$fv_quote[$a] = expSaet($fv[$a]['klevel']);
 	}
 	foreach ($sa as $a => $data)
 	{
 		$fa[$a]['f5'] = isset($fa[$a]['f5']) ? $fa[$a]['f5'] : 0;
 		$fa[$a]['f6'] = isset($fa[$a]['f6']) ? $fa[$a]['f6'] : 0;
 		$fa[$a]['f7'] = isset($fa[$a]['f7']) ? $fa[$a]['f7'] : 0;
 		$fa[$a]['f9'] = isset($fa[$a]['f9']) ? $fa[$a]['f9'] : 0;
 		$fa[$a]['klevel'] = isset($fa[$a]['klevel']) ? $fa[$a]['klevel'] : 0;
 		
 		$fa_ang[$a] = 1 + (($fa[$a]['f5'] * 0.05 ) + ($fa[$a]['f6'] * 0.05 ) +  ($fa[$a]['f7'] * 0.05 ));
 		$fa_panz[$a] = 1 + ($fa[$a]['f9'] * 0.1);
 		
 		$fa_quote[$a] = expSaet($fa[$a]['klevel']);
 	}
     //5.12.2011 - schild verluste speichern
     //23.12.2011 - insgesamt verschoben
    $sa_absorb = array();
    $sv_absorb = array();
    $vv_absorb = array();
 	//zwichen stand
 	for($runde = 1; $runde <= 7; $runde++)
 	{
		//echo "next<br>";
 		$runden_gesamt = $runde;
 		//Zaehle Schiffe
 		$count_a = 0;
 		foreach ($sa_n as $a => $data) $count_a+=count($data);
 		$count_v = 0;
 		foreach ($sv_n as $a => $data) $count_v+=count($data);
 		foreach ($vv_n as $a => $data) $count_v+=count($data);
 		
 		if($count_a == 0)
 			$winner = "v";
 		else if ($count_v == 0) 
 			$winner = "a";	
 		else 
 		{
 			//Sauberkeit anlegen
 			$runden_ret[$runde]["sv"][1] = array();
 			foreach ($sa_n as $a => $data)
 			{
				$runden_ret[$runde]["a"][$a] = array();
	 			foreach ($data as $sa_sid => $sa_sc)
	 			{
					$ag_bonus = isset($AngBonus[$a][$sa_sid]) ? $AngBonus[$a][$sa_sid] : 0;
	 				$runden_ret[$runde]["a"][$a][$sa_sid]['c'] = $sa_sc;
	 				$runden_ret[$runde]["a"][$a][$sa_sid]['a'] = max(0,$_SHIP[$sa_sid][8]+$ag_bonus) * $fa_ang[$a];
	 				$runden_ret[$runde]["a"][$a][$sa_sid]['v'] = $_SHIP[$sa_sid][9] * $fa_panz[$a];
	 			}
 			}
 			foreach ($sv_n as $a => $data)
 			{
				$runden_ret[$runde]["sv"][$a] = array();
	 			foreach ($data as $sv_sid => $sv_sc)
	 			{
	 				$runden_ret[$runde]["sv"][$a][$sv_sid]['c'] = $sv_sc;
	 				$runden_ret[$runde]["sv"][$a][$sv_sid]['a'] = $_SHIP[$sv_sid][8] * $fv_ang[$a];
	 				$runden_ret[$runde]["sv"][$a][$sv_sid]['v'] = $_SHIP[$sv_sid][9] * $fv_panz[$a];
	 			}
				if($a == 1)
				{
					foreach ($vv_n[$a] as $sv_sid => $sv_sc)
					{
						$runden_ret[$runde]["vv"][$a][$sv_sid]['c'] = $sv_sc;
						$runden_ret[$runde]["vv"][$a][$sv_sid]['a'] = $_VERT[$sv_sid][8] * $fv_ang[$a];
						$runden_ret[$runde]["vv"][$a][$sv_sid]['v'] = $_VERT[$sv_sid][9] * $fv_panz[$a];
					}
				}
 			}
			//Schussfang mechanismus
			$range_a = buildRange(array('s' => $sa_n));
			//deffer
			/*
			$range_v = array();
			foreach($sv_n as $spieler => $schiffe)
			{
				foreach($schiffe as $id => $anz)
				{
					$rangecounter_v += $anz;
					$range_v[$rangecounter_v] = array($spieler,$id,$anz,'s');
				}
			}
			foreach($vv_n as $spieler => $schiffe)
			{
				foreach($schiffe as $id => $anz)
				{
					$rangecounter_v += $anz;
					$range_v[$rangecounter_v] = array($spieler,$id,$anz,'v');
				}
			}
			*/
			$range_v = buildRange(array('s' => $sv_n, 'v' => $vv_n));
			
 			$runde_a_ang = 0;
 			$runde_a_def = 0;
 			$runde_a_schuss = 0;
 			$runde_a_tref = 0;
 			
 			$runde_v_ang = 0;
 			$runde_v_def = 0;
 			$runde_v_schuss = 0;
 			$runde_v_tref = 0;
			
            
            //30.11.2011 - die verluste werden am ende der runde berechnet
            $sa_n_tmp = $sa_n;
            $sv_n_tmp = $sv_n;
            $vv_n_tmp = $vv_n;
            
            
			/*
			echo "<pre>";
			var_export($range_v);
			echo "</pre>";
			*/
			//10.11.2011 - Schussfangechanismus
			
			//jeder angreifer darf mal schiessen
            
			foreach($sa_n as $a => $data)
			{
				foreach($data as $sa_sid => $sa_sc)
				{
                    //30.11.2011 - nun noch genauer
                    $allC = $sa_sc;
                    $period = period($allC);
                    
                    for($liI = 1;$liI<=$sa_sc;$liI+=$period)
                    {
                        $sa_sc2 = $allC > $period ? $period : $allC;
                        $allC-=$period;
                            
    					//umbau, solange es noch was zu k�mpfen gibt
    					$ag_bonus = isset($AngBonus[$a][$sa_sid]) ? $AngBonus[$a][$sa_sid] : 0;
    					$ag_ag = max(0,$_SHIP[$sa_sid][8] + $ag_bonus) * $fa_ang[$a];
    					$a_left = $ag_ag * $sa_sc;
    					$a_it = 0;
                        $doneTypes = array();
                        $lbKannSchiessen = true;
    					while ($lbKannSchiessen && $a_left > 0)//zur sicherheit
    					{
    						$lbKannSchiessen = false;
                            
    						//Suche nun einen Deffer
    						$gegner = chooseRange($range_v);
    						$b = $gegner[0]; //Gegner
    						$sv_sid = $gegner[1]; //Schiff
                            
                            //das hier brauch ich nimmer
                            /*
                            if(in_array($sv_sid,$doneTypes))
                                continue;
                            else
                                $doneTypes[] = $sv_sid;
                            */
    						//$sv_sc = $gegner[2]; //anzahl
    						$sv_sc = $gegner[3] == 's' ? $sv_n[$b][$sv_sid] : $vv_n[$b][$sv_sid];
    						if($sv_sc < 1) continue;
    						#$sv_sc = min($sa_sc2,$sv_sc);//gegnerAnzahl
                            
    						$CONST = $gegner[3] == 's' ? $_SHIP : $_VERT;
    						
    						//Stelle Kampfwerte auf
    						$ag_bonus = isset($AngBonus[$a][$sa_sid]) ? $AngBonus[$a][$sa_sid] : 0;
    						$ag_ag = max(0,$_SHIP[$sa_sid][8] + $ag_bonus) * $fa_ang[$a];
    						$vt_ag = $CONST[$sv_sid][8] * $fv_ang[$b];
                            
                            $vorSchaden = $gegner[3] == "s" ? 
                                            (isset($sv_absorb[$b][$sv_sid]) ? $sv_absorb[$b][$sv_sid] : 0 ) : 
                                            (isset($vv_absorb[$b][$sv_sid]) ? $vv_absorb[$b][$sv_sid] : 0 );
                            $vorSchaden = $vorSchaden * (rand(50,150)/100);
    						$vt_df = max(1,($CONST[$sv_sid][9] * $fv_panz[$b]) - $vorSchaden);
                            if($gegner[3] == 's')
    						    $rapidFire_A = isset($_SHIP[$sa_sid]['rf'][$sv_sid]) ? $_SHIP[$sa_sid]['rf'][$sv_sid] : 1;
                            else
                                $rapidFire_A = isset($_SHIP[$sa_sid]['rfv'][$sv_sid]) ? $_SHIP[$sa_sid]['rfv'][$sv_sid] : 1;
                            
                            //28.12.2011 Das ist RF!!!
                            $lbKannSchiessen = chanceDecide(($rapidFire_A - 1 / $rapidFire_A) * 100);
    						
    						//Kampf
                            #echo "angriff mit $sa_sc2 bei period $period<br>";
    						$kf_v = formel_kampf($sa_sc2,$sv_sc,$ag_ag,$vt_df,$fa_quote[$a],$rapidFire_A);
    						
    						//angreifer
    						$lose_v = abs($sv_sc - $kf_v['rest']);
                            #echo "$liI : gegen $sv_sid : $lose_v<br>";
    						
    						
    						$runde_a_ang += $kf_v['schaden'];
    						$runde_a_def += $kf_v['absorb'];
    						$runde_a_schuss += $kf_v['schuss'];
    						$runde_a_tref += $kf_v['treffer'];
    						
    						
    						$a_left -= $kf_v['absorb'];
                            //absorption speichern, naja gut ok, die hälfte
                            if($gegner[3] == 's') 
                            {
                                if(!isset($sv_absorb[$b])) $sv_absorb[$b] = array();
                                $restAbsorb = $kf_v['absorb'] - ($lose_v * $vt_df);
                                $sv_absorb[$b][$sv_sid] += ($kf_v['absorb'] / $sv_sc);
                                
                                //addlose
                                $loseplus = floor($sv_absorb[$b][$sv_sid] / $vt_df);
                                if($loseplus > 1)
                                {
                                    $sv_absorb[$b][$sv_sid] -= $vt_df * $loseplus;
                                    $lose_v += $loseplus;
                                }
                            }
                            else 
                            {
    					        if(!isset($vv_absorb[$b])) $vv_absorb[$b] = array();
                                $restAbsorb = $kf_v['absorb'] - ($lose_v * $vt_df);
                                $vv_absorb[$b][$sv_sid] += ($kf_v['absorb'] / $sv_sc);
                                
                                //addlose
                                $loseplus = floor($vv_absorb[$b][$sv_sid] / $vt_df);
                                if($loseplus > 1)
                                {
                                    $vv_absorb[$b][$sv_sid] -= $vt_df * $loseplus;
                                    $lose_v += $loseplus;
                                }
                            }
                            
    						
    						if($gegner[3] == 's')
    							$sv_n_tmp[$b][$sv_sid] -= $lose_v;
    						else
    							$vv_n_tmp[$b][$sv_sid] -= $lose_v;
    							
    						
    					}
                    }
				}
			}
			//jeder deffer
			foreach($sv_n as $b => $data)
			{
				foreach($data as $sv_sid => $sv_sc)
				{
                    //30.11.2011 - nun noch genauer
                    $allC = $sv_sc;
                    $period = period($allC);
                    for($liI = 1;$liI<=$sv_sc;$liI+=$period)
                    {
                        
                        $sv_sc2 = $allC > $period ? $period : $allC;
                        $allC-=$period;
                        
    					$vt_ag = $_SHIP[$sv_sid][8] * $fv_ang[$b];
    					$v_left = $vt_ag * $sv_sc;
    					
                        $lbKannSchiessen = true;
    					while($lbKannSchiessen && $v_left > 0)
    					{
    						
    						//Suche nun einen Deffer
    						$gegner = chooseRange($range_a);
    						$a = $gegner[0]; //Gegner
    						$sa_sid = $gegner[1]; //Schiff
                            
                            /*
                            if(in_array($sa_sid,$doneTypes))
                                continue;
                            else
                                $doneTypes[] = $sa_sid;
                            */
    						//$sa_sc = $gegner[2]; //anzahl
    						$sa_sc = $sa_n[$a][$sa_sid];
                            
    						if($sa_sc < 1) continue;
    						#$sa_sc = min($sv_sc2,$sa_sc);
    						//Stelle Kampfwerte auf
                            $vorSchaden = (isset($sa_absorb[$a][$sa_sid]) ? $sa_absorb[$a][$sa_sid] : 0 );
                            $vorSchaden = $vorSchaden * (rand(50,150)/100);
    						$ag_df = max(1,$_SHIP[$sa_sid][9] * $fa_panz[$a] - $vorSchaden);
    						
    						$rapidFire_V = isset($_SHIP[$sv_sid]['rf'][$sa_sid]) ? $_SHIP[$sv_sid]['rf'][$sa_sid] : 1;
    						
                            //28.12.2011 Das ist RF!!!
                            $lbKannSchiessen = chanceDecide(($rapidFire_V - 1 / $rapidFire_V) * 100);
    						//Kampf
    						//if($sv_sc < 0)
    						//	echo "schiff $sv_sid hat anzahl $sv_sc, warum?<br>";
    						$kf_a = formel_kampf($sv_sc2,$sa_sc,$vt_ag,$ag_df,$fv_quote[$b],$rapidFire_V);
    						
    						//verteidiger
    						$lose_a = $sa_sc - $kf_a['rest'];
    						$runde_v_ang += $kf_a['schaden'];
    						$runde_v_def += $kf_a['absorb'];
    						$runde_v_schuss += $kf_a['schuss'];
    						$runde_v_tref += $kf_a['treffer'];
    						
    						$v_left -= $kf_a['absorb'];
                            
                            /*if(!isset($sa_absorb[$a])) $sa_absorb[$a] = array(); 
                                $sa_absorb[$a][$sa_sid] += ($kf_a['absorb'] / $sa_sc);*/
                                
                            if(!isset($sa_absorb[$a])) $sa_absorb[$a] = array();
                                $restAbsorb = $kf_a['absorb'] - ($lose_a * $ag_df);
                                $sa_absorb[$a][$sa_sid] += ($kf_a['absorb'] / $sa_sc);
                                
                                //addlose
                                $loseplus = floor($sa_absorb[$a][$sa_sid] / $ag_df);
                                if($loseplus > 1)
                                {
                                    $sa_absorb[$a][$sa_sid] -= $ag_df * $loseplus;
                                    $lose_a += $loseplus;
                                }
    						
    						$sa_n_tmp[$a][$sa_sid] -= $lose_a;
    					}
                    }
				}
			}			
			//jeder deffer(vv)
			foreach($vv_n as $b => $data)
			{
				foreach($data as $sv_sid => $sv_sc)
				{
                    //30.11.2011 - nun noch genauer
                    $allC = $sv_sc;
                    $period = period($allC);
                    for($liI = 1;$liI<=$sv_sc;$liI+=$period)
                    {
                        
                        $sv_sc2 = $allC > $period ? $period : $allC;
                        $allC-=$period;
                        
    					$vt_ag = $_VERT[$sv_sid][8] * $fv_ang[$b];
    					$v_left = $vt_ag * $sv_sc;
                        
                        $lbKannSchiessen = true;
    					while ($lbKannSchiessen && $v_left > 0)
    					{
    						
    						//Suche nun einen Deffer
    						$gegner = chooseRange($range_a);
    						$a = $gegner[0]; //Gegner
    						$sa_sid = $gegner[1]; //Schiff
                            
                            /*
                            if(in_array($sa_sid,$doneTypes))
                                continue;
                            else
                                $doneTypes[] = $sa_sid;
                                */
    						//$sa_sc = $gegner[2]; //anzahl
    						
    						$sa_sc = $sa_n[$a][$sa_sid];
    						if($sa_sc < 1) continue;
    						#$sa_sc = min($sv_sc2,$sa_sc);
                            
    						//EMP-Turm
                            
    						if($sv_sid == 5)
    						{
    							if(!isset($AngBonus[$a]))
    								$AngBonus[$a] = array();
    							$AngBonus[$a][$sa_sid] -= (20/$sa_sc) * $sv_sc;
    						}
    						//Stelle Kampfwerte auf
                            
                            $vorSchaden = (isset($sa_absorb[$a][$sa_sid]) ? $sa_absorb[$a][$sa_sid] : 0 );
                            $vorSchaden = $vorSchaden * (rand(50,150)/100);
                            
        					$ag_df = max(1,$_SHIP[$sa_sid][9] * $fa_panz[$a] - $vorSchaden);
    						
    						$rapidFire_V = isset($_VERT[$sv_sid]['rf'][$sa_sid]) ? $_VERT[$sv_sid]['rf'][$sa_sid] : 1;
    						
                            //28.12.2011 Das ist RF!!!
                            $lbKannSchiessen = chanceDecide(($rapidFire_V - 1 / $rapidFire_V) * 100);
                            
    						//Kampf
    						$kf_a = formel_kampf($sv_sc2,$sa_sc,$vt_ag,$ag_df,$fv_quote[$b],$rapidFire_V);
    						
    						//verteidiger
    						$lose_a = $sa_sc - $kf_a['rest'];
    						$runde_v_ang += $kf_a['schaden'];
    						$runde_v_def += $kf_a['absorb'];
    						$runde_v_schuss += $kf_a['schuss'];
    						$runde_v_tref += $kf_a['treffer'];
    						
    						$v_left -= $kf_a['absorb'];
                            
                            /*if(!isset($sa_absorb[$a])) $sa_absorb[$a] = array(); 
                                $sa_absorb[$a][$sa_sid] += ($kf_a['absorb'] / $sa_sc);*/
                            if(!isset($sa_absorb[$a])) $sa_absorb[$a] = array();
                                $restAbsorb = $kf_a['absorb'] - ($lose_a * $ag_df);
                                $sa_absorb[$a][$sa_sid] += ($kf_a['absorb'] / $sa_sc);
                                
                                //addlose
                                $loseplus = floor($sa_absorb[$a][$sa_sid] / $ag_df);
                                if($loseplus > 1)
                                {
                                    $sa_absorb[$a][$sa_sid] -= $ag_df * $loseplus;
                                    $lose_a += $loseplus;
                                }
    						
    						$sa_n_tmp[$a][$sa_sid] -= $lose_a;
    					}
                    }
				}
			}			
            
            $sa_n = $sa_n_tmp;
            $sv_n = $sv_n_tmp;
            $vv_n = $vv_n_tmp;
 			//Runde vorbei
 			$runden_ret[$runde]["a_ang"] = $runde_a_ang;
 			$runden_ret[$runde]["a_def"] = $runde_a_def;
 			$runden_ret[$runde]["a_schuss"] = $runde_a_schuss;
 			$runden_ret[$runde]["a_tref"] = $runde_a_tref;
 			
 			$runden_ret[$runde]["v_ang"] = $runde_v_ang;
 			$runden_ret[$runde]["v_def"] = $runde_v_def;
 			$runden_ret[$runde]["v_schuss"] = $runde_v_schuss;
 			$runden_ret[$runde]["v_tref"] = $runde_v_tref;
 			
 			//Reinigen
 			foreach ($sa_n as $a => $data) foreach ($sa_n[$a] as $sa_sid => $sa_sc) if($sa_sc <= 0) unset($sa_n[$a][$sa_sid]);
 			foreach ($sv_n as $a => $data) foreach ($sv_n[$a] as $sv_sid => $sv_sc) if($sv_sc <= 0) unset($sv_n[$a][$sv_sid]);
 			foreach ($vv_n as $a => $data) foreach ($vv_n[$a] as $vv_sid => $vv_sc) if($vv_sc <= 0) unset($vv_n[$a][$vv_sid]);

 			//Runde vorbei
 		}
 	}
 	//Runden vorbei, aber noch schiffe da?Zusammenfassunf
	$runde = $runden_gesamt+1;
	$runden_ret[$runde]["a"] = array();
	$runden_ret[$runde]["sv"] = array();
	$runden_ret[$runde]["vv"] = array();
	$count_a = 0;
	foreach ($sa_n as $a => $data) $count_a+=count($data);
	$count_v = 0;
	foreach ($sv_n as $a => $data) $count_v+=count($data);
	foreach ($vv_n as $a => $data) $count_v+=count($data);
 	if($count_a == 0)
	{
		$winner = "v";
	}
	else if ($count_v == 0) 
	{
		$winner = "a";
	}
	else 
	{
		$winner = "n";
	}
	foreach ($sa_n as $a => $data)
	{
		$runden_ret[$runde]["a"][$a] = array();
		foreach ($data as $sa_sid => $sa_sc)
		{
			$ag_bonus = isset($AngBonus[$a][$sa_sid]) ? $AngBonus[$a][$sa_sid] : 0;
			$runden_ret[$runde]["a"][$a][$sa_sid]['c'] = $sa_sc;
			$runden_ret[$runde]["a"][$a][$sa_sid]['a'] = max(0,$_SHIP[$sa_sid][8]+$ag_bonus) * $fa_ang[$a];
			$runden_ret[$runde]["a"][$a][$sa_sid]['v'] = $_SHIP[$sa_sid][9] * $fa_panz[$a];
		}
	}
	foreach ($sv_n as $a => $data)
	{
		$runden_ret[$runde]["sv"][$a] = array();
		foreach ($data as $sv_sid => $sv_sc)
		{
			$runden_ret[$runde]["sv"][$a][$sv_sid]['c'] = $sv_sc;
			$runden_ret[$runde]["sv"][$a][$sv_sid]['a'] = $_SHIP[$sv_sid][8] * $fv_ang[$a];
			$runden_ret[$runde]["sv"][$a][$sv_sid]['v'] = $_SHIP[$sv_sid][9] * $fv_panz[$a];
		}
		foreach ($vv_n[$a] as $sv_sid => $sv_sc)
		{
			$runden_ret[$runde]["vv"][$a][$sv_sid]['c'] = $sv_sc;
			$runden_ret[$runde]["vv"][$a][$sv_sid]['a'] = $_VERT[$sv_sid][8] * $fv_ang[$a];
			$runden_ret[$runde]["vv"][$a][$sv_sid]['v'] = $_VERT[$sv_sid][9] * $fv_panz[$a];
		}
	}
	
	return array("runde" => $runden_ret,"sa" => $sa_n, "sv" => $sv_n, "vv" => $vv_n,"winner" => $winner);
 }
}


function ogameKS($fa,$sa,$kv,$fv,$sv,$vv,$debug=0)
{
    global $_SHIP, $_VERT;
    
    /*
    
    Initialisierung

    Berechnung der Waffen, Schilde, Hülle (Die Hülle ist gleich (Metallkosten plus Kristallkosten) durch 10) unter Einbeziehung ihrer Techniken 
    */
    
    $laTechsAtter = array();
    $laTechsDeffer = array();
    
    $laShips = array("atter" => $sa, "defferS" => $sv, "defferV" => $vv);
    
    foreach ($sv as $a => $data)
    {
 		$fv[$a]['f5'] = isset($fv[$a]['f5']) ? $fv[$a]['f5'] : 0;
 		$fv[$a]['f6'] = isset($fv[$a]['f6']) ? $fv[$a]['f6'] : 0;
 		$fv[$a]['f7'] = isset($fv[$a]['f7']) ? $fv[$a]['f7'] : 0;
 		$fv[$a]['f9'] = isset($fv[$a]['f9']) ? $fv[$a]['f9'] : 0;
 		$fv[$a]['klevel'] = isset($fv[$a]['klevel']) ? $fv[$a]['klevel'] : 0;
 		
 		$laTechsDeffer[$a]["ang"] = 1 + (($fv[$a]['f5'] * 0.05 ) + ($fv[$a]['f6'] * 0.05 ) +  ($fv[$a]['f7'] * 0.05 ));
 		$laTechsDeffer[$a]["deff"] = 1 + ($fv[$a]['f9'] * 0.1);
 		
 		$laTechsDeffer[$a]["quote"] = expSaet($fv[$a]['klevel']);
 	}
 	foreach ($sa as $a => $data)
 	{
 		$fa[$a]['f5'] = isset($fa[$a]['f5']) ? $fa[$a]['f5'] : 0;
 		$fa[$a]['f6'] = isset($fa[$a]['f6']) ? $fa[$a]['f6'] : 0;
 		$fa[$a]['f7'] = isset($fa[$a]['f7']) ? $fa[$a]['f7'] : 0;
 		$fa[$a]['f9'] = isset($fa[$a]['f9']) ? $fa[$a]['f9'] : 0;
 		$fa[$a]['klevel'] = isset($fa[$a]['klevel']) ? $fa[$a]['klevel'] : 0;
 		
 		$laTechsAtter[$a]["ang"] = 1 + (($fa[$a]['f5'] * 0.05 ) + ($fa[$a]['f6'] * 0.05 ) +  ($fa[$a]['f7'] * 0.05 ));
 		$laTechsAtter[$a]["deff"] = 1 + ($fa[$a]['f9'] * 0.1);
 		
 		$laTechsAtter[$a]["quote"] = expSaet($fa[$a]['klevel']);  
 	}
     
     
    $runden_ret = array();
    
    $laHuelle = array();
    $laHuelle["atter"] = array();
    $laHuelle["defferS"] = array();
    $laHuelle["defferV"] = array();
    
    $lsWinner = "n";
    
    //KAMPF
    for($runde = 1; $runde < 7; $runde++)
    {
        $laRundenSchuss = array("atter" => 0, "deffer" => 0);
        $laRundenTref = array("atter" => 0, "deffer" => 0);
        $laRundenAK = array("atter" => 0, "deffer" => 0);
        $laRundenAbsorb = array("atter" => 0, "deffer" => 0);
        //Listen Bauen
        $laRange["atter"] = buildRange(array("s" => $laShips["atter"]));
        $laRange["deffer"] = buildRange(array("s" => $laShips["defferS"], "v" => $laShips["defferV"]));
        
        //Auswertung aktuelle Runde
        //Sauberkeit anlegen
 		$runden_ret[$runde]["sv"][1] = array();
 		foreach ($laShips["atter"] as $a => $data)
 		{
			$runden_ret[$runde]["a"][$a] = array();
 			foreach ($data as $sa_sid => $sa_sc)
 			{
				$ag_bonus = isset($AngBonus[$a][$sa_sid]) ? $AngBonus[$a][$sa_sid] : 0;
 				$runden_ret[$runde]["a"][$a][$sa_sid]['c'] = $sa_sc;
 				$runden_ret[$runde]["a"][$a][$sa_sid]['a'] = max(0,$_SHIP[$sa_sid][8]+$ag_bonus) * $laTechsAtter[$a]["ang"];
 				$runden_ret[$runde]["a"][$a][$sa_sid]['v'] = $_SHIP[$sa_sid][9] * $laTechsAtter[$a]["deff"];
 			}
 		}
 		foreach ($laShips["defferS"] as $a => $data)
 		{
			$runden_ret[$runde]["sv"][$a] = array();
 			foreach ($data as $sv_sid => $sv_sc)
 			{
 				$runden_ret[$runde]["sv"][$a][$sv_sid]['c'] = $sv_sc;
 				$runden_ret[$runde]["sv"][$a][$sv_sid]['a'] = $_SHIP[$sv_sid][8] * $laTechsDeffer[$a]["ang"];
 				$runden_ret[$runde]["sv"][$a][$sv_sid]['v'] = $_SHIP[$sv_sid][9] * $laTechsDeffer[$a]["deff"];
 			}
			if($a == 1)
			{
				foreach ($laShips["defferV"][$a] as $sv_sid => $sv_sc)
				{
					$runden_ret[$runde]["vv"][$a][$sv_sid]['c'] = $sv_sc;
					$runden_ret[$runde]["vv"][$a][$sv_sid]['a'] = $_VERT[$sv_sid][8] * $laTechsDeffer[$a]["ang"];
					$runden_ret[$runde]["vv"][$a][$sv_sid]['v'] = $_VERT[$sv_sid][9] * $laTechsDeffer[$a]["deff"];
				}
			}
 		}
         
        $laSchilde = array();
        $laSchilde["atter"] = array();
        $laSchilde["defferS"] = array();
        $laSchilde["defferV"] = array();
        
        $laExplosion = array();
        $laExplosion["atter"] = array();
        $laExplosion["defferS"] = array();
        $laExplosion["defferV"] = array();
        
        //Kampf geht los!
        //Angreifer
        foreach($laShips["atter"] as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $lsShipID => $liShipCount)
            {
                for($i = 1; $i<=$liShipCount;$i++)
                {
                    $lbKannSchiessen = true;
                    while($lbKannSchiessen)
                    {
                        $laRundenSchuss["atter"]++;
                        if(!chanceDecide($laTechsAtter[$liSpieler]))
                            continue;
                        $laGegner = chooseRange($laRange["deffer"]);
                        $liGegner = $laGegner[0];
                        $liGSchiff = $laGegner[1];
                        
                        $CONST = $laGegner[3] == 's' ? $_SHIP : $_VERT;
                        $lsSuffix = $laGegner[3] == 's' ? "S" : "V";
                        //Werte aufstellen
                        $liAngriff = $_SHIP[$lsShipID][8] * $laTechsAtter[$liSpieler]["ang"];
                        
                        if(isset($laHuelle["deffer".$lsSuffix][$liGegner][$liGSchiff]) && count($laHuelle["deffer".$lsSuffix][$liGegner][$liGSchiff]) > 0)
                            $liHuelle = array_shift($laHuelle["deffer".$lsSuffix][$liGegner][$liGSchiff]);
                        else
                        {
                            $liHuelle = ($CONST[$liGSchiff][1] + $CONST[$liGSchiff][2]) / 10;
                            $liHuelle = $liHuelle * $laTechsDeffer[$liGegner]["deff"];
                        }
                        
                        if(isset($laSchilde["deffer".$lsSuffix][$liGegner][$liGSchiff]) && count($laSchilde["deffer".$lsSuffix][$liGegner][$liGSchiff]) > 0)
                            $liSchilde = array_shift($laSchilde["deffer".$lsSuffix][$liGegner][$liGSchiff]);
                        else
                            $liSchilde = $CONST[$liGSchiff][9] * $laTechsDeffer[$liGegner]["deff"];
                        
                        //Huelle is schon im Arsch?
                        //Kampf
                        if($liAngriff < $liSchilde)                            
                        {
                            if($liAngriff * 100 < $liSchilde)
                            {
                                break;
                            }
                            else
                            {
                                $laRundenTref["atter"]++;
                                $laRundenAK["atter"] += $liAngriff;
                                $laRundenAbsorb["deffer"] += $liAngriff;
                                if(!isset($laSchilde["deffer".$lsSuffix][$liGegner])) $laSchilde["deffer".$lsSuffix][$liGegner] = array();
                                if(!isset($laSchilde["deffer".$lsSuffix][$liGegner][$liGSchiff])) $laSchilde["deffer".$lsSuffix][$liGegner][$liGSchiff] = array();
                                //Werte Speichern
                                $laSchilde["deffer".$lsSuffix][$liGegner][$liGSchiff][] = ($liSchilde - $liAngriff);
                            }
                            
                        }
                        else
                        {
                                $laRundenTref["atter"]++;
                                $laRundenAK["atter"] += $liAngriff;
                                $laRundenAbsorb["deffer"] += $liSchilde;
                            //Schilde sind durch
                            if(!isset($laSchilde["deffer".$lsSuffix][$liGegner])) $laSchilde["deffer".$lsSuffix][$liGegner] = array();
                            if(!isset($laSchilde["deffer".$lsSuffix][$liGegner][$liGSchiff])) $laSchilde["deffer".$lsSuffix][$liGegner][$liGSchiff] = array();
                            //Werte Speichern
                            $laSchilde["deffer".$lsSuffix][$liGegner][$liGSchiff][] = 0;
                            
                            //Huelle
                            $liSchaden = ($liAngriff - $liSchilde) > $liHuelle ? 1 : ($liAngriff - $liSchilde) / $liHuelle;
                            $laRundenAbsorb["deffer"] += ($liSchaden * $liHuelle);
                            $liSchaden = $liSchaden * 100;
                            
                            if($liSchaden >= 30)
                            {
                                $lbExploded = chanceDecide($liSchaden);
                                
                                if($lbExploded)
                                {
                                    if(!isset($laExplosion["deffer".$lsSuffix][$liGegner])) $laExplosion["deffer".$lsSuffix][$liGegner] = array();
                                    if(!isset($laExplosion["deffer".$lsSuffix][$liGegner][$liGSchiff])) $laExplosion["deffer".$lsSuffix][$liGegner][$liGSchiff] = 0;
                                    $laExplosion["deffer".$lsSuffix][$liGegner][$liGSchiff]++;
                                }
                            }
                            //Werte Speichern
                            if(!$lbExploded)
                            {
                                if(!isset($laHuelle["deffer".$lsSuffix][$liGegner])) $laHuelle["deffer".$lsSuffix][$liGegner] = array();
                                if(!isset($laHuelle["deffer".$lsSuffix][$liGegner][$liGSchiff])) $laHuelle["deffer".$lsSuffix][$liGegner][$liGSchiff] = array();
                                $laHuelle["deffer".$lsSuffix][$liGegner][$liGSchiff][] = ($liHuelle - ($liAngriff - $liSchilde));
                            }
                        }
                        
                        $lbKannSchiessen = false;
                        
                        //Rapidfire
                        $liRF = isset($_SHIP[$lsShipID]['rf'][$liGSchiff]) ? $_SHIP[$lsShipID]['rf'][$liGSchiff] : -1;
                        if($liRF > 1)
                        {
                            $liRFChance = (($liRF - 1) / $liRF) * 100;
                            
                            $lbKannSchiessen = chanceDecide($liRFChance);
                        }
                    }
                }
                
            }
        }
    
        //Verteidiger
        foreach($laShips["defferS"] as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $lsShipID => $liShipCount)
            {
                for($i = 1; $i<=$liShipCount;$i++)
                {
                    $lbKannSchiessen = true;
                    while($lbKannSchiessen)
                    {
                        $laRundenSchuss["deffer"]++;
                        if(!chanceDecide($laTechsDeffer[$liSpieler]))
                            continue;
                        $laGegner = chooseRange($laRange["atter"]);
                        $liGegner = $laGegner[0];
                        $liGSchiff = $laGegner[1];
                        
                        $CONST = $laGegner[3] == 's' ? $_SHIP : $_VERT;
                        //Werte aufstellen
                        $liAngriff = $_SHIP[$lsShipID][8] * $laTechsDeffer[$liSpieler]["ang"];
                        
                        if(isset($laHuelle["atter"][$liGegner][$liGSchiff]) && count($laHuelle["atter"][$liGegner][$liGSchiff]) > 0)
                            $liHuelle = array_shift($laHuelle["atter"][$liGegner][$liGSchiff]);
                        else
                        {
                            $liHuelle = ($CONST[$liGSchiff][1] + $CONST[$liGSchiff][2]) / 10;
                            $liHuelle = $liHuelle * $laTechsAtter[$liGegner]["deff"];
                        }
                        
                        if(isset($laSchilde["atter"][$liGegner][$liGSchiff]) && count($laSchilde["atter"][$liGegner][$liGSchiff]) > 0)
                            $liSchilde = array_shift($laSchilde["atter"][$liGegner][$liGSchiff]);
                        else
                            $liSchilde = $CONST[$liGSchiff][9] * $laTechsAtter[$liGegner]["deff"];
                        
                        //Kampf
                        if($liAngriff < $liSchilde)                            
                        {
                            if($liAngriff * 100 < $liSchilde)
                            {
                                break;
                            }
                            else
                            {
                                $laRundenTref["deffer"]++;
                                $laRundenAK["deffer"] += $liAngriff;
                                $laRundenAbsorb["atter"] += $liAngriff;
                                if(!isset($laSchilde["atter"][$liGegner])) $laSchilde["atter"][$liGegner] = array();
                                if(!isset($laSchilde["atter"][$liGegner][$liGSchiff])) $laSchilde["atter"][$liGegner][$liGSchiff] = array();
                                //Werte Speichern
                                $laSchilde["atter"][$liGegner][$liGSchiff][] = ($liSchilde - $liAngriff);
                            }
                        }
                        else
                        {
                             $laRundenTref["deffer"]++;
                                $laRundenAK["deffer"] += $liAngriff;
                                $laRundenAbsorb["atter"] += $liSchilde;
                            //Schilde sind durch
                            if(!isset($laSchilde["atter"][$liGegner])) $laSchilde["atter"][$liGegner] = array();
                            if(!isset($laSchilde["atter"][$liGegner][$liGSchiff])) $laSchilde["atter"][$liGegner][$liGSchiff] = array();
                            //Werte Speichern
                            $laSchilde["atter"][$liGegner][$liGSchiff][] = 0;
                            
                            //Huelle
                            $liSchaden = ($liAngriff - $liSchilde) > $liHuelle ? 1 : ($liAngriff - $liSchilde) / $liHuelle;
                            $laRundenAbsorb["atter"] += ($liSchaden * $liHuelle);
                            
                            $liSchaden = $liSchaden * 100;
                            if($liSchaden >= 30)
                            {
                                $lbExploded = chanceDecide($liSchaden);
                                if($lbExploded)
                                {
                                    if(!isset($laExplosion["atter"][$liGegner])) $laExplosion["atter"][$liGegner] = array();
                                    if(!isset($laExplosion["atter"][$liGegner][$liGSchiff])) $laExplosion["atter"][$liGegner][$liGSchiff] = 0;
                                    $laExplosion["atter"][$liGegner][$liGSchiff]++;
                                }
                            }
                            if(!$lbExploded)
                            {
                                //Werte Speichern
                                if(!isset($laHuelle["atter"][$liGegner])) $laHuelle["atter"][$liGegner] = array();
                                if(!isset($laHuelle["atter"][$liGegner][$liGSchiff])) $laHuelle["atter"][$liGegner][$liGSchiff] = array();
                                $laHuelle["atter"][$liGegner][$liGSchiff][] = $liHuelle - ($liAngriff - $liSchilde);
                            }
                        }
                        
                        $lbKannSchiessen = false;
                        
                        //Rapidfire
                        $liRF = isset($_SHIP[$lsShipID]['rf'][$liGSchiff]) ? $_SHIP[$lsShipID]['rf'][$liGSchiff] : -1;
                        if($liRF > 1)
                        {
                            $liRFChance = (($liRF - 1) / $liRF) * 100;
                            
                            $lbKannSchiessen = chanceDecide($liRFChance);
                        }
                    }
                }
                
            }
        }
        foreach($laShips["defferV"] as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $lsShipID => $liShipCount)
            {
                for($i = 1; $i<=$liShipCount;$i++)
                {
                    $lbKannSchiessen = true;
                    while($lbKannSchiessen)
                    {
                        $laRundenSchuss["deffer"]++;
                        if(!chanceDecide($laTechsDeffer[$liSpieler]))
                            continue;
                        $laGegner = chooseRange($laRange["atter"]);
                        $liGegner = $laGegner[0];
                        $liGSchiff = $laGegner[1];
                        
                        $CONST = $laGegner[3] == 's' ? $_SHIP : $_VERT;
                        //Werte aufstellen
                        $liAngriff = $_VERT[$lsShipID][8] * $laTechsDeffer[$liSpieler]["ang"];
                        
                        if(isset($laHuelle["atter"][$liGegner][$liGSchiff]) && count($laHuelle["atter"][$liGegner][$liGSchiff]) > 0)
                            $liHuelle = array_shift($laHuelle["atter"][$liGegner][$liGSchiff]);
                        else
                        {
                            $liHuelle = ($CONST[$liGSchiff][1] + $CONST[$liGSchiff][2]) / 10;
                            $liHuelle = $liHuelle * $laTechsAtter[$liGegner]["deff"];
                        }
                        
                        if(isset($laSchilde["atter"][$liGegner][$liGSchiff]) && count($laSchilde["atter"][$liGegner][$liGSchiff]) > 0)
                            $liSchilde = array_shift($laSchilde["atter"][$liGegner][$liGSchiff]);
                        else
                            $liSchilde = $CONST[$liGSchiff][9] * $laTechsAtter[$liGegner]["deff"];
                        
                        //Kampf
                        if($liAngriff < $liSchilde)                            
                        {
                            if($liAngriff * 100 < $liSchilde)
                            {
                                break;
                            }
                            else
                            {
                                $laRundenTref["deffer"]++;
                                $laRundenAK["deffer"] += $liAngriff;
                                $laRundenAbsorb["atter"] += $liAngriff;
                                if(!isset($laSchilde["atter"][$liGegner])) $laSchilde["atter"][$liGegner] = array();
                                if(!isset($laSchilde["atter"][$liGegner][$liGSchiff])) $laSchilde["atter"][$liGegner][$liGSchiff] = array();
                                //Werte Speichern
                                $laSchilde["atter"][$liGegner][$liGSchiff][] = ($liSchilde - $liAngriff);
                            }
                        }
                        else
                        {
                             
                                $laRundenAK["deffer"] += $liAngriff;
                                $laRundenAbsorb["atter"] += $liSchilde;
                            //Schilde sind durch
                            if(!isset($laSchilde["atter"][$liGegner])) $laSchilde["atter"][$liGegner] = array();
                            if(!isset($laSchilde["atter"][$liGegner][$liGSchiff])) $laSchilde["atter"][$liGegner][$liGSchiff] = array();
                            //Werte Speichern
                            $laSchilde["atter"][$liGegner][$liGSchiff][] = 0;
                            
                            //Huelle
                            $liSchaden = ($liAngriff - $liSchilde) > $liHuelle ? 1 : ($liAngriff - $liSchilde) / $liHuelle;
                            $laRundenAbsorb["atter"] += ($liSchaden * $liHuelle);
                            
                            $liSchaden = $liSchaden * 100;
                            if($liSchaden >= 30)
                            {
                                $lbExploded = chanceDecide($liSchaden);
                                if($lbExploded)
                                {
                                    if(!isset($laExplosion["atter"][$liGegner])) $laExplosion["atter"][$liGegner] = array();
                                    if(!isset($laExplosion["atter"][$liGegner][$liGSchiff])) $laExplosion["atter"][$liGegner][$liGSchiff] = 0;
                                    $laExplosion["atter"][$liGegner][$liGSchiff]++;
                                }
                            }
                            if(!$lbExploded)
                            {
                                //Werte Speichern
                                if(!isset($laHuelle["atter"][$liGegner])) $laHuelle["atter"][$liGegner] = array();
                                if(!isset($laHuelle["atter"][$liGegner][$liGSchiff])) $laHuelle["atter"][$liGegner][$liGSchiff] = array();
                                $laHuelle["atter"][$liGegner][$liGSchiff][] = $liHuelle - ($liAngriff - $liSchilde);
                            }
                        }
                        
                        $lbKannSchiessen = false;
                        
                        //Rapidfire
                        $liRF = isset($_VERT[$lsShipID]['rf'][$liGSchiff]) ? $_VERT[$lsShipID]['rf'][$liGSchiff] : -1;
                        if($liRF > 1)
                        {
                            $liRFChance = (($liRF - 1) / $liRF) * 100;
                            
                            $lbKannSchiessen = chanceDecide($liRFChance);
                        }
                    }
                }
                
            }
        }
        
        //Runden auswertung
        $runden_ret[$runde]["a_ang"] = $laRundenAK["atter"];
 		$runden_ret[$runde]["a_def"] = $laRundenAbsorb["deffer"];
 		$runden_ret[$runde]["a_schuss"] = $laRundenSchuss["atter"];
 		$runden_ret[$runde]["a_tref"] = $laRundenTref["atter"];;
 		
 		$runden_ret[$runde]["v_ang"] = $laRundenAK["deffer"];;
 		$runden_ret[$runde]["v_def"] = $laRundenAbsorb["atter"];
 		$runden_ret[$runde]["v_schuss"] = $laRundenSchuss["deffer"];
 		$runden_ret[$runde]["v_tref"] = $laRundenTref["deffer"];
        
        $liCountAtter = 0;
        $liCountDeffer = 0;
        //Entfernen
        foreach($laShips["atter"] as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $lsID => $liCount)
            {
                $liLost = isset($laExplosion["atter"][$liSpieler][$lsID]) ? $laExplosion["atter"][$liSpieler][$lsID] : 0;
                if(isset($laHuelle["atter"][$liSpieler][$lsID]))
                {
                    asort($laHuelle["atter"][$liSpieler][$lsID]);
                    for($j = 1; $j<=$liLost;$j++)
                        array_pop($laHuelle["atter"][$liSpieler][$lsID]);
                }
                $liCount -= $liLost;
                if($liCount <= 0)
                    unset($laShips["atter"][$liSpieler][$lsID]);
                else
                {
                    $liCountAtter += $liCount;
                    $laShips["atter"][$liSpieler][$lsID] = $liCount;
                }
            }
        }
        
        foreach($laShips["defferS"] as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $lsID => $liCount)
            {
                $liLost = isset($laExplosion["defferS"][$liSpieler][$lsID]) ? $laExplosion["defferS"][$liSpieler][$lsID] : 0;
                
                if(isset($laHuelle["defferS"][$liSpieler][$lsID]))
                {
                    asort($laHuelle["defferS"][$liSpieler][$lsID]);
                    for($j = 1; $j<=$liLost;$j++)
                        array_pop($laHuelle["defferS"][$liSpieler][$lsID]);
                }
                $liCount -= $liLost;
                
                if($liCount <= 0)
                    unset($laShips["defferS"][$liSpieler][$lsID]);
                else
                {
                    $liCountDeffer += $liCount;
                    $laShips["defferS"][$liSpieler][$lsID] = $liCount;
                }
            }
        }
        
        foreach($laShips["defferV"] as $liSpieler => $laSchiffe)
        {
            foreach($laSchiffe as $lsID => $liCount)
            {
                $liLost = isset($laExplosion["defferV"][$liSpieler][$lsID]) ? $laExplosion["defferV"][$liSpieler][$lsID] : 0;
                
                if(isset($laHuelle["defferV"][$liSpieler][$lsID]))
                {
                    asort($laHuelle["defferV"][$liSpieler][$lsID]);
                    for($j = 1; $j<=$liLost;$j++)
                        array_pop($laHuelle["defferV"][$liSpieler][$lsID]);
                }
                $liCount -= $liLost;
                
                if($liCount <= 0)
                    unset($laShips["defferV"][$liSpieler][$lsID]);
                else
                {
                    $liCountDeffer += $liCount;
                    $laShips["defferV"][$liSpieler][$lsID] = $liCount;
                }
            }
        }
        
        //Gibs schon nen Winner?
        if($liCountAtter == 0)
        {
            $lsWinner = "v";
            break;
        }
        if($liCountDeffer == 0)
        {
            $lsWinner = "a";
            break;
        }
        
        //Runde vorbei
    }
    
    //Endauswertung
    $runde = count($runden_ret) + 1;
    foreach ($laShips["atter"] as $a => $data)
 	{
		$runden_ret[$runde]["a"][$a] = array();
 		foreach ($data as $sa_sid => $sa_sc)
 		{
			$ag_bonus = isset($AngBonus[$a][$sa_sid]) ? $AngBonus[$a][$sa_sid] : 0;
 			$runden_ret[$runde]["a"][$a][$sa_sid]['c'] = $sa_sc;
 			$runden_ret[$runde]["a"][$a][$sa_sid]['a'] = max(0,$_SHIP[$sa_sid][8]+$ag_bonus) * $laTechsAtter[$a]["ang"];
 			$runden_ret[$runde]["a"][$a][$sa_sid]['v'] = $_SHIP[$sa_sid][9] * $laTechsAtter[$a]["deff"];
 		}
 	}
 	foreach ($laShips["defferS"] as $a => $data)
 	{
		$runden_ret[$runde]["sv"][$a] = array();
 		foreach ($data as $sv_sid => $sv_sc)
 		{
 			$runden_ret[$runde]["sv"][$a][$sv_sid]['c'] = $sv_sc;
 			$runden_ret[$runde]["sv"][$a][$sv_sid]['a'] = $_SHIP[$sv_sid][8] * $laTechsDeffer[$a]["ang"];
 			$runden_ret[$runde]["sv"][$a][$sv_sid]['v'] = $_SHIP[$sv_sid][9] * $laTechsDeffer[$a]["deff"];
 		}
		if($a == 1)
		{
			foreach ($laShips["defferV"][$a] as $sv_sid => $sv_sc)
			{
				$runden_ret[$runde]["vv"][$a][$sv_sid]['c'] = $sv_sc;
				$runden_ret[$runde]["vv"][$a][$sv_sid]['a'] = $_VERT[$sv_sid][8] * $laTechsDeffer[$a]["ang"];
				$runden_ret[$runde]["vv"][$a][$sv_sid]['v'] = $_VERT[$sv_sid][9] * $laTechsDeffer[$a]["deff"];
			}
		}
 	}

    return array("runde" => $runden_ret,"sa" => $laShips["atter"], "sv" => $laShips["defferS"], "vv" => $laShips["defferV"],"winner" => $lsWinner);
}

function chanceDecide($aiChance)
{
    return (rand(1,100) <= $aiChance);
}
function fac($n)
{
    return $n == 0 ? 1 : $n * fac($n - 1);   
}
class KSShips
{
    private $iaShips;
    private $iaTechs;
    private $isExploded = array();
    private $iaRealShips;
    private $iaShooted = array();
    private $iiCount = 0;
    
    public $CONST = array();
    
    public function __construct($aaShips,$aaTechs, $asType = "S")
    {
        global $_SHIP, $_VERT;
        
        $this->iaShips = $aaShips;
        $this->iaTechs = $aaTechs;
        $this->CONST = $asType == "V" ? $_VERT : $_SHIP;
        
        //Count?
        $this->recalcShips();
    }
    public function getCount()
    {
        return $this->iiCount;
    }
    public function getShips()
    {
        return $this->iaShips;
    }
    public function getShooted($liSpieler,$lsID)
    {
        return isset($this->iaShooted[$liSpieler][$lsID]) ? $this->iaShooted[$liSpieler][$lsID] : 0;
    }
    public function getAngriff($liSpieler,$lsID)
    {
        return $this->CONST[$lsID][8] * $this->iaTechs[$liSpieler]["ang"];
    }
    public function getTrefferQuote($aiSpieler)
    {
        return $this->iaTechs[$aiSpieler]["quote"];
    }
    public function getVert($liSpieler,$lsID)
    {
        return $this->CONST[$lsID][9] * $this->iaTechs[$liSpieler]["deff"];
    }
    private function expl($aiSpieler,$aiID,$aiCount)
    {
        if(!isset($this->iaExploded[$aiSpieler]))
            $this->iaExploded[$aiSpieler] = array();
        if(!isset($this->iaExploded[$aiSpieler][$aiID]))
            $this->iaExploded[$aiSpieler][$aiID] = 0;
        
        $this->iaExploded[$aiSpieler][$aiID]+=$aiCount;
    }
    public function subAtt($aiSid,$aiId,$aiAmount = 0)
    {
        $this->iaShips[$aiSid][$aiId]; 
    }
    public function fight($aoAng,$asAngType = "S",$asDefType = "S")
    {
        $liAllShot = 0;
        $liAllAK = 0;
        $liAllAbsorb = 0;
        $liAllTreff = 0;
        foreach($this->iaShips as $liSpieler => $laShips)
        {
            foreach($laShips as $lsID => $liCount)
            {
                $liDeff = $this->getVert($liSpieler,$lsID);
               //Nun den Angreifer
               foreach($aoAng->getShips() as $liGegner => $liGegSchiffe)
               {
                    foreach($liGegSchiffe as $lsGegID => $liGegCount)
                    {
                        $liAngriff = $aoAng->getAngriff($liGegner,$lsGegID);
                        $liTrQ = $aoAng->getTrefferQuote($liGegner);
                        //Schussanzahl
                        //Wie oft haste denn schon geschossen?
                        /*$liShooted = $aoAng->getShooted($liGegner,$lsGegID);
                        if($liShooted == $liGegCount)
                            continue;
                        */
                        //Schussanzahl
                        $liCurrShots = 0;
                        //den Ersten Schuss berechnen wir direkt
                        $liWK = $liCount / $this->getCount() * 100;
                        #echo "Erstchance: $liWK <br>";

                            //den ersten Schuss kriegen wir!
                            $liCurrShots+=$liGegCount;
                            
                            //Wie siehts mit Rapidfire aus?
                            $liRF = isset($aoAng->CONST[$lsGegID]['rf'][$lsID]) ? $aoAng->CONST[$lsGegID]['rf'][$lsID] : 1;
                            #echo "Rapidfire $liRF<br>";
                            $liRF = ($liRF - 1) / $liRF;
                            
                            if($liRF > 0)
                            {
                                /*$liMaxShot = $liGegCount - $liShooted;
                                
                                $liC = rand(1,$liMaxShot);
                                $liWK = fac($liC) / pow($this->getCount() * $liRF,$liC);
                                echo "max: $liMaxShot, schuss: $liC, gesamtWK $liWK (fac($liC) / pow(".$this->getCount()." * $liRF,$liC))<br>";
                                
                                $liCurrShots = chanceDecide($liWK * 100) ? $liCurrShots + $liC : 1;
                                */
                                for($i = 1;$i<=$liCount;$i+=1)
                                    if(chanceDecide(((ceil($liCount - $i) / ceil($this->getCount() -  $i) * $liRF) * 100)))
                                        $liCurrShots++;
                                    else
                                        break;
                            }
                             //Nun der Kampf
                            $liCurrShots = ceil($liCurrShots);
                            $liTreffer = ($liCurrShots * (rand($liTrQ,100)/100));
                            $liDest = $liCount - ((($liDeff * $liCount) - ($liAngriff * $liTreffer *($liWK / 100))) / ($liDeff * $liCount)) * $liCount;
                            
                            $liAllAbsorb += ($liDest * $liDeff);
                            #echo "$liDest = ((($liDeff * $liCount) - ($liAngriff * $liCurrShots)) / ($liDeff * $liCount)) * $liCount <br>";
                            
                            $liAllShot += $liCurrShots;
                            $liAllTreff += $liTreffer;
                            $liAllAK += $liCurrShots * $liAngriff;
                            $this->expl($liSpieler,$lsID,$liDest);
                    }
               }
            }
        }
        return array("schuss" => $liAllShot, "treffer" => $liAllTreff,"ak" => $liAllAK, "absorb" => $liAllAbsorb);
    }
    
    public function recalcShips()
    {
        $liAllCount = 0;
        foreach($this->iaShips as $liSpieler => $laShips)
        {
            foreach($laShips as $lsID => $liCount)
            {
                if(isset($this->iaExploded[$liSpieler][$lsID]))
                    $liCount = $liCount - $this->iaExploded[$liSpieler][$lsID];
                if($liCount <= 0)
                    unset($this->iaShips[$liSpieler][$lsID]);
                else
                {
                    $this->iaShips[$liSpieler][$lsID] = $liCount;
                    $liAllCount += $liCount;
                }
            }
        }
        
        //leeren
        
        $this->iaExploded = array();
        $this->iiCount = $liAllCount;
        return $liAllCount;
    }
    public function finalShips()
    {
        foreach($this->iaShips as $liSpieler => $laSchiffe)
            foreach($laSchiffe as $lsID => $liCount)
                $this->iaShips[$liSpieler][$lsID] = ceil($liCount);
    }
}

function fakeOKS($fa,$sa,$kv,$fv,$sv,$vv,$debug=0)
{
    global $_SHIP, $_VERT;
    
    /*
    
    Initialisierung

    Berechnung der Waffen, Schilde, Hülle (Die Hülle ist gleich (Metallkosten plus Kristallkosten) durch 10) unter Einbeziehung ihrer Techniken 
    */
    
    $laTechsAtter = array();
    $laTechsDeffer = array();
    
    $laShips = array("atter" => $sa, "defferS" => $sv, "defferV" => $vv);
    
    
    foreach ($sv as $a => $data)
    {
     	$fv[$a]['f5'] = isset($fv[$a]['f5']) ? $fv[$a]['f5'] : 0;
 		$fv[$a]['f6'] = isset($fv[$a]['f6']) ? $fv[$a]['f6'] : 0;
 		$fv[$a]['f7'] = isset($fv[$a]['f7']) ? $fv[$a]['f7'] : 0;
 		$fv[$a]['f9'] = isset($fv[$a]['f9']) ? $fv[$a]['f9'] : 0;
 		$fv[$a]['klevel'] = isset($fv[$a]['klevel']) ? $fv[$a]['klevel'] : 0;
 		
 		$laTechsDeffer[$a]["ang"] = 1 + (($fv[$a]['f5'] * 0.05 ) + ($fv[$a]['f6'] * 0.05 ) +  ($fv[$a]['f7'] * 0.05 ));
 		$laTechsDeffer[$a]["deff"] = 1 + ($fv[$a]['f9'] * 0.1);
         
        $laTechsDeffer[$a]["ang"] *= $fv[$a]["bonus"];
        $laTechsDeffer[$a]["deff"] *= $fv[$a]["bonus"];
 		
 		$laTechsDeffer[$a]["quote"] = expSaet($fv[$a]['klevel']);
         
        $laTechsDeffer[$a]["quote"] = min(100,$laTechsDeffer[$a]["quote"]*$fv[$a]["bonus"]);
 	}
 	foreach ($sa as $a => $data)
 	{
 		$fa[$a]['f5'] = isset($fa[$a]['f5']) ? $fa[$a]['f5'] : 0;
 		$fa[$a]['f6'] = isset($fa[$a]['f6']) ? $fa[$a]['f6'] : 0;
 		$fa[$a]['f7'] = isset($fa[$a]['f7']) ? $fa[$a]['f7'] : 0;
 		$fa[$a]['f9'] = isset($fa[$a]['f9']) ? $fa[$a]['f9'] : 0;
 		$fa[$a]['klevel'] = isset($fa[$a]['klevel']) ? $fa[$a]['klevel'] : 0;
 		
 		$laTechsAtter[$a]["ang"] = 1 + (($fa[$a]['f5'] * 0.05 ) + ($fa[$a]['f6'] * 0.05 ) +  ($fa[$a]['f7'] * 0.05 ));
 		$laTechsAtter[$a]["deff"] = 1 + ($fa[$a]['f9'] * 0.1);
         
         $laTechsAtter[$a]["ang"] *= $fa[$a]["bonus"];
         $laTechsAtter[$a]["deff"] *= $fa[$a]["bonus"];
 		
 		$laTechsAtter[$a]["quote"] = expSaet($fa[$a]['klevel']);  
         $laTechsAtter[$a]["quote"] = min(100,$laTechsAtter[$a]["quote"]*$fa[$a]["bonus"]);
 	}
     
    $loAngreifer = new KSShips($sa,$laTechsAtter,"S");
    $loDefferS = new KSShips($sv,$laTechsDeffer,"S");
    $loDefferV = new KSShips($vv,$laTechsDeffer,"V");
    
    
    $runden_ret = array();
    
    $laHuelle = array();
    $laHuelle["atter"] = array();
    $laHuelle["defferS"] = array();
    $laHuelle["defferV"] = array();
    
    $lsWinner = "n";
    
    for($runde = 1; $runde <=6; $runde++)
    {
        //Vorauswertung
        $laRundenSchuss = array("atter" => 0, "deffer" => 0);
        $laRundenTref = array("atter" => 0, "deffer" => 0);
        $laRundenAK = array("atter" => 0, "deffer" => 0);
        $laRundenAbsorb = array("atter" => 0, "deffer" => 0);

        
        //Auswertung aktuelle Runde
        //Sauberkeit anlegen
     	$runden_ret[$runde]["sv"][1] = array();
 		foreach ($loAngreifer->getShips() as $a => $data)
 		{
			$runden_ret[$runde]["a"][$a] = array();
 			foreach ($data as $sa_sid => $sa_sc)
 			{
				$ag_bonus = isset($AngBonus[$a][$sa_sid]) ? $AngBonus[$a][$sa_sid] : 0;
 				$runden_ret[$runde]["a"][$a][$sa_sid]['c'] = ceil($sa_sc);
 				$runden_ret[$runde]["a"][$a][$sa_sid]['a'] = max(0,$_SHIP[$sa_sid][8]+$ag_bonus) * $laTechsAtter[$a]["ang"];
 				$runden_ret[$runde]["a"][$a][$sa_sid]['v'] = $_SHIP[$sa_sid][9] * $laTechsAtter[$a]["deff"];
 			}
 		}
 		foreach ($loDefferS->getShips() as $a => $data)
 		{
			$runden_ret[$runde]["sv"][$a] = array();
 			foreach ($data as $sv_sid => $sv_sc)
 			{
 				$runden_ret[$runde]["sv"][$a][$sv_sid]['c'] = ceil($sv_sc);
 				$runden_ret[$runde]["sv"][$a][$sv_sid]['a'] = $_SHIP[$sv_sid][8] * $laTechsDeffer[$a]["ang"];
 				$runden_ret[$runde]["sv"][$a][$sv_sid]['v'] = $_SHIP[$sv_sid][9] * $laTechsDeffer[$a]["deff"];
 			}
			if($a == 1)
			{
                $laDefferData = $loDefferV->getShips();
                
				foreach ($laDefferData[$a] as $sv_sid => $sv_sc)
				{
					$runden_ret[$runde]["vv"][$a][$sv_sid]['c'] = ceil($sv_sc);
					$runden_ret[$runde]["vv"][$a][$sv_sid]['a'] = $_VERT[$sv_sid][8] * $laTechsDeffer[$a]["ang"];
					$runden_ret[$runde]["vv"][$a][$sv_sid]['v'] = $_VERT[$sv_sid][9] * $laTechsDeffer[$a]["deff"];
				}
			}
 		}
         
        //Kampf
        
        #$laAS_vs_DS = KampfRoutine($laShips["atter"],$laShips["defferS"],$laTechsAtter,$laTechsDeffer,"S","S");
        $laAS_vs_DS = $loDefferS->fight($loAngreifer);
        #$laAS_vs_DV = KampfRoutine($laShips["atter"],$laShips["defferV"],$laTechsAtter,$laTechsDeffer,"S","V");
        $laAS_vs_DV = $loDefferV->fight($loAngreifer);
        #$laDS_vs_AS = KampfRoutine($laShips["defferS"],$laShips["atter"],$laTechsDeffer,$laTechsAtter,"S","S");
        $laDS_vs_AS = $loAngreifer->fight($loDefferS);
        #$laDV_vs_AS = KampfRoutine($laShips["defferV"],$laShips["atter"],$laTechsDeffer,$laTechsAtter,"V","S");
        $laDV_vs_AS = $loAngreifer->fight($loDefferV);
        
        //Nachauswertung
        
        $laExplosion["atter"] = $laDS_vs_AS["dest"];
        
        /*
        foreach($laDS_vs_AS["dest"] as $liSpieler => $laSchiffe)
            foreach($laSchiffe as $lsID => $liCount)
                if(!isset($laExplosion[$liSpieler][$lsID]))
                    $laExplosion[$liSpieler][$lsID] = $liCount;
                else
                    $laExplosion[$liSpieler][$lsID] += $liCount;
        */
        
        
        $laExplosion["defferS"] = $laAS_vs_DS["dest"];
        $laExplosion["defferV"] = $laAS_vs_DV["dest"];
        
        $laRundenSchuss["atter"] = $laAS_vs_DS["schuss"] + $laAS_vs_DV["schuss"];
        $laRundenAK["atter"] = $laAS_vs_DS["ak"] + $laAS_vs_DV["ak"];
        $laRundenTref["atter"] = $laAS_vs_DS["treffer"] + $laAS_vs_DV["treffer"];
        $laRundenAbsorb["atter"] = $laDS_vs_AS["absorb"] + $laDV_vs_AS["absorb"];
        
        $laRundenSchuss["deffer"] = $laDS_vs_AS["schuss"] + $laDV_vs_AS["schuss"];
        $laRundenTref["deffer"] = $laDS_vs_AS["treffer"] + $laDV_vs_AS["treffer"];
        $laRundenAK["deffer"] = $laDS_vs_AS["ak"] + $laDV_vs_AS["ak"];
        $laRundenAbsorb["deffer"] = $laAS_vs_DS["absorb"] + $laAS_vs_DV["absorb"];
        
        
        //Runden auswertung
        $runden_ret[$runde]["a_ang"] = $laRundenAK["atter"];
     	$runden_ret[$runde]["a_def"] = $laRundenAbsorb["deffer"];
 		$runden_ret[$runde]["a_schuss"] = $laRundenSchuss["atter"];
 		$runden_ret[$runde]["a_tref"] = $laRundenTref["atter"];;
 		
 		$runden_ret[$runde]["v_ang"] = $laRundenAK["deffer"];;
 		$runden_ret[$runde]["v_def"] = $laRundenAbsorb["atter"];
 		$runden_ret[$runde]["v_schuss"] = $laRundenSchuss["deffer"];
 		$runden_ret[$runde]["v_tref"] = $laRundenTref["deffer"];
        
        $liCountAtter = 0;
        $liCountDeffer = 0;
        //Entfernen
        $liCountAtter = $loAngreifer->recalcShips();
        $liCountDeffer = $loDefferS->recalcShips();
        $liCountDeffer += $loDefferV->recalcShips();
        
        //Gibs schon nen Winner?
        if($liCountAtter == 0)
        {
            $lsWinner = "v";
            break;
        }
        if($liCountDeffer == 0)
        {
            $lsWinner = "a";
            break;
        }
        
        //Runde vorbei
        
    }
    $loAngreifer->finalShips();
    $loDefferS->finalShips();
    $loDefferV->finalShips();
    
    $laShips["atter"] = $loAngreifer->getShips();
    $laShips["defferS"] = $loDefferS->getShips();
    $laShips["defferV"] = $loDefferV->getShips();
    
    //Endauswertung
    $runde = count($runden_ret) + 1;
    foreach ($laShips["atter"] as $a => $data)
     {
		$runden_ret[$runde]["a"][$a] = array();
 		foreach ($data as $sa_sid => $sa_sc)
 		{
			$ag_bonus = isset($AngBonus[$a][$sa_sid]) ? $AngBonus[$a][$sa_sid] : 0;
 			$runden_ret[$runde]["a"][$a][$sa_sid]['c'] = $sa_sc;
 			$runden_ret[$runde]["a"][$a][$sa_sid]['a'] = max(0,$_SHIP[$sa_sid][8]+$ag_bonus) * $laTechsAtter[$a]["ang"];
 			$runden_ret[$runde]["a"][$a][$sa_sid]['v'] = $_SHIP[$sa_sid][9] * $laTechsAtter[$a]["deff"];
 		}
 	}
 	foreach ($laShips["defferS"] as $a => $data)
 	{
		$runden_ret[$runde]["sv"][$a] = array();
 		foreach ($data as $sv_sid => $sv_sc)
 		{
 			$runden_ret[$runde]["sv"][$a][$sv_sid]['c'] = $sv_sc;
 			$runden_ret[$runde]["sv"][$a][$sv_sid]['a'] = $_SHIP[$sv_sid][8] * $laTechsDeffer[$a]["ang"];
 			$runden_ret[$runde]["sv"][$a][$sv_sid]['v'] = $_SHIP[$sv_sid][9] * $laTechsDeffer[$a]["deff"];
 		}
		if($a == 1)
		{
			foreach ($laShips["defferV"][$a] as $sv_sid => $sv_sc)
			{
				$runden_ret[$runde]["vv"][$a][$sv_sid]['c'] = $sv_sc;
				$runden_ret[$runde]["vv"][$a][$sv_sid]['a'] = $_VERT[$sv_sid][8] * $laTechsDeffer[$a]["ang"];
				$runden_ret[$runde]["vv"][$a][$sv_sid]['v'] = $_VERT[$sv_sid][9] * $laTechsDeffer[$a]["deff"];
			}
		}
 	}
    
    return array("runde" => $runden_ret,"sa" => $laShips["atter"], "sv" => $laShips["defferS"], "vv" => $laShips["defferV"],"winner" => $lsWinner);
}
?>
