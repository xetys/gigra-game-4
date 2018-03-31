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
 
function getResearchBonusFaktor($n)
{
    return 0.05 * $n * ($n + 1);   
}
function getResItemQueue($coords)
{
    $lodb = gigraDB::db_open();
    
    $laQueueRes = array(0,0,0,0);
    $lsQuery = "SELECT r1,r2,r3,r4 FROM itemqueue WHERE coords = '$coords' AND (r1+r2+r3+r4) != 0";
    $lodb->query($lsQuery);
    
    while($laRow = $lodb->fetch())
    {
        $laQueueRes[0] += $laRow[0];
        $laQueueRes[1] += $laRow[1];
        $laQueueRes[2] += $laRow[2];
        $laQueueRes[3] += $laRow[3];
    }
    
    $lodb->query("DELETE FROM itemqueue WHERE coords = '$coords' AND (r1+r2+r3+r4) != 0");
    
    
    return $laQueueRes;
}

  function getMinMaxTemp($aiTemp,$aiDia)
  {
        $kelvin=$aiTemp+273;
        $bereich=($aiDia+100000)/121000;
        
        
        $liMinTemp = ($kelvin*$bereich-273);                                         //Niedrige Temperatur
        $liMaxTemp = ($kelvin*(2-$bereich)-273);                                     //Hohe     Temperatur
        
        return array($liMinTemp,$liMaxTemp);
  }
  function sf($i)
  {
		$wert = 100000+50000*2*pow(1.4,$i);
		
		$teiler = 1;
		$stellen = 0;
		while($wert/$teiler > 1)
		{
			$teiler = $teiler * 10;
			$stellen++;
		}
		$stellen = $stellen - 2;
		return  round($wert,-($stellen));
 }
 function resProd($stufe,$faktor,$temp=10)
 {
       return floor ($faktor * $stufe * pow (1.1, $stufe));
       $faktor = $faktor * 7;
       return (foe($stufe)*$faktor+$basis);
 }
 
 function getEisenProd($stufe)
 {
     return resProd($stufe,100,0);
 }
 
 
 function getTitanProd($stufe)
 {
     return resProd($stufe,70,0);
 }
 
 function getWasserProd($stufe,$maxtemp)
 {
     return floor ( 110 * $stufe * pow (1.15, $stufe)) * (1.28 - 0.002 * ($maxtemp));

 }
 
 function getChemFabVerbr($stufe)
 {
     return resProd($stufe,50,0);
 }
 
 function getErwChemFabVerbr($stufe)
 {
     return resProd($stufe,1819,0);
 }
 
 function getEnergieKW($stufe)
 {
     return resProd($stufe,130,0);
 }
 function getEnergieSat($anzahl,$temp)
 {
     return ($anzahl * ceil(max(1,($temp / 5)) + 50));
 }
 
 function getEnergieVerbrMinen($prod)
 {
    return floor(($prod) * 0.33);
 }
 function getEnergieVerbrChemFab($prod)
 {
    return ($prod / 5) * 0.33;   
 }
 function getEnergieVerbrErwChemFab($prod)
 {
    return ($prod / 2) * 0.33;   
 }
  /**
  
   * Berechnet die Produktion(0-3) und Speicherkapazitaet(4-7) auf einem Planeten
   *
   * @param coords String
   * @return Array_res
   */
  function calc_prod($coords,$res = null,$konstr=-1)
  {
    global $_ACTCONF;
    
    
    $db = gigraDB::db_open();
    //global $k;
    if($konstr != -1)
    {
      $k = $konstr;
    }
    else
    {
      //$k = $db->getOne("SELECT k3,k4,k5,k6,k7,k8,k9,k10,k11,k12 FROM gebaeude WHERE coords='$coords'");
      $k = getBuildings($coords);
    }
    
    #Temperatur des Planeten + user data
    $laRow = $db->getOne("SELECT temp,owner,dia FROM planets WHERE coords='$coords'",600);
    $SPEED = $_ACTCONF["speed_res"] * getNoobSpeed($laRow[1]);
    $temp = $laRow[0];
    $lsUid = $laRow[1];
    
    //Bonus
    $liBonusFaktor = getRessBonus($lsUid,$coords);
    
    //Forschungsfaktor
    $laForschung = getForschung($lsUid);
    
    $liEnergieFaktor = 1 + (getResearchBonusFaktor(isset($laForschung['f15']) ? $laForschung['f15'] : 0) / 100);
    $liMinenFaktor = 1 + (getResearchBonusFaktor(isset($laForschung['f7']) ? $laForschung['f7'] : 0) / 100);
    
    
    #print_r($k);
    //Zuerst komplette Prod berechnen (+Grundkram)
    //ProdFaktoren
    $laProdFRow = $db->getOne("SELECT prod1, prod2, prod3, prod4, prod5 FROM rohstoffe WHERE coords = '{$coords}'",0,"prod");
    $liProdEisen = $laProdFRow[0] / 10;
    $liProdTitan = $laProdFRow[1] / 10;
    $liProdWasser = $laProdFRow[2] / 10;

    
    $liProdChemFab = $laProdFRow[3] / 10;
    $liProdErwChemFab = $laProdFRow[4] / 10;
    
 
    
    
     //Formel fuer Temperatur
     $laTemps = getMinMaxTemp($temp,$laRow['dia']);
     
     $temp = $laTemps[1];
    $liEisenMine = getEisenProd($k['k3']) * $liProdEisen;
    $liTitanMine = getTitanProd($k['k4']) * $liProdTitan;
    $liBohrTurm = getWasserProd($k['k5'],$temp) * $liProdWasser;
    
    
    $prod[0] = $liEisenMine * $liBonusFaktor * $liMinenFaktor * $SPEED;      //Eisen
    $prod[1] = $liTitanMine * $liBonusFaktor * $liMinenFaktor * $SPEED;      //Titan
    $prod[2] = $liBohrTurm * $liBonusFaktor * $liMinenFaktor * $SPEED;       //Wasser
    $prod[3] = 0;                                                            //Wasserstoff

    $prodbup = $prod;
    $v_ges   = 0;
 
    //Zuerst erweiterte abziehen...
    $verbr    = min(getErwChemFabVerbr($k['k7']),$prod[2]) * $liProdErwChemFab;
    $verbr  *=  $SPEED;
    $h2_faktor = 1;
    $prod[2] -= $verbr;
    $liErwChemieFabrik = ceil($verbr/2);
    $prod[3] += $liErwChemieFabrik * $liBonusFaktor * $liMinenFaktor;
    $prodbup[4] = $verbr;
  
    //...dann die normale
    $verbr    = min(getChemFabVerbr($k['k6']),$prod[2]) * $liProdChemFab;
    $verbr  *=  $SPEED;
    
    $prod[2] -= $verbr;
    
    $liChemieFabrik = ceil($verbr/5);
    
    $prod[3] += $liChemieFabrik * $liBonusFaktor * $liMinenFaktor;
    $prodbup[3] = $verbr;
    //Energie
    #1.Stufe ermitteln
    $stufe_kraftwerk = $k['k5'];
    #2.Sateliten auch:
    $row = $db->getOne("SELECT s FROM schiffe WHERE coords='$coords'",60);
    $s = ikf2array($row[0]);
    $anz_sats = (isset($s['15'])) ? $s['15'] : 0;
    #3.Energie All von Kraftwerk errechnen
    $liKraftwerk = getEnergieKW($k['k8']);
    
    $prod[4]= $liKraftwerk * $liEnergieFaktor * $liBonusFaktor;
    
    
    #5.Sateliten Produktion errechnen
    $sat_prod = getEnergieSat($anz_sats,$temp) * $liEnergieFaktor * $liBonusFaktor;
    #echo $sat_prod;
    #6.Gesammtwerte auswerten
    $prodbup[5] = $prod[4];
    $prodbup[6] = floor($sat_prod);
    $prod[4] += floor($sat_prod);
    #7.Verbrauch ermitteln
    $ev[0] = getEnergieVerbrMinen($liEisenMine);
    $ev[1] = getEnergieVerbrMinen($liTitanMine);
    $ev[2] = getEnergieVerbrMinen($liBohrTurm);
    $ev[3] = getEnergieVerbrMinen($liChemieFabrik);
    $ev[4] = getEnergieVerbrMinen($liErwChemieFabrik);
    
    $gesamt_verbrauch = $ev[0] + $ev[1] + $ev[2] + $ev[3] + $ev[4];
    #8.Energie Ueberschuss
    $prod[5] = $prod[4] - $gesamt_verbrauch;
    #9.Produktionsfaktor berechnen:
    $prod_faktor = $gesamt_verbrauch == 0 ? 1 : min(1, $prod[4] / $gesamt_verbrauch);
    #10.Neuer Produktion uebernehmen
    
    #$prod[0] -= 10;
    #$prod[1] -= 10;
    #$prod[2] -= 10;
    #$prod[3] -= 10;
    
    $prod[0] *= $prod_faktor;
    $prod[1] *= $prod_faktor;
    $prod[2] *= $prod_faktor;
    $prod[3] *= $prod_faktor;
    
   
    if(coordType($coords) == 1)
    {
        $prod[0] += (10*$SPEED);
        $prod[1] += (10*$SPEED);
        $prod[2] += (10*$SPEED);
    }
    #$prod[3] += 10;

    return array(
    0  => (int)$prod[0], //Prod: Fe 0
    1  => (int)$prod[1], //Prod: Lut 1
    2  => (int)$prod[2], //Prod: H2O 2
    3  => (int)$prod[3],  //Prod: H2 3
    4  => $prod[4],  //Energie: Produktion
    5  => $prod[5],  //Energie: rest
    6  => sf($k['k9']),  //Lagerkap: Fe 4 
    7  => sf($k['k10']),  //Lagerkap: Lut 5
    8  => sf($k['k11']),//Lagerkap: H2O 6 
    9  => sf($k['k12']),   //Lagerkap: H2 7 
    10 => (int)$prodbup[0], //Purprod: Fe 8
    11 => (int)$prodbup[1], //Purprod: Lut 9
    12 => (int)$prodbup[2], //Purprod: H2O 10
    13 => (int)$prodbup[3], //Verbr Chem 11
    14 => (int)$prodbup[4], //Verbr Chem 11
    15 => (int)$prodbup[5], //Purprod Kraftwerk
    16 => (int)$prodbup[6],//Pur Prod Sats
    17 => (int)$ev[0], // Energieverbrauch Eisenmine
    18 => (int)$ev[1], // Energieverbrauch Titan
    19 => (int)$ev[2], // Energieverbrauch Wasser
    20 => (int)$ev[3], // Energieverbrauch Chemfabrik
    21 => (int)$ev[4], // Energieverbrauch Erw. Chem
    22 => (int)$gesamt_verbrauch,
    23 => $prod_faktor,
    ); //Purprod Sats
    //print_r($prod);
    return $prod;
  }


  function calc_res($row,$prod,$NOW=-1)
  {
    if($NOW==-1)
    $NOW = time();
    $ru1 = ($prod[0]/3600)*($NOW-$row[4]);
    $ru2 = ($prod[1]/3600)*($NOW-$row[4]);
    $ru3 = ($prod[2]/3600)*($NOW-$row[4]);
    $ru4 = ($prod[3]/3600)*($NOW-$row[4]);


    $ret = array(
    0 => max(min($row[0]+$ru1,$prod[6]),0), //Eisenbestand
    1 => max(min($row[1]+$ru2,$prod[7]),0), //Titanbestand
    2 => max(min($row[2]+$ru3,$prod[8]),0), //Wasserbestand
    3 => max(min($row[3]+$ru4,$prod[9]),0), //Wasserstoffbestand
    4 => $prod[4],                          //Energie gesamt
    5 => $prod[5],                          //Energie rest
    /*6 => $ru1['res'],                       
    7 => $ru2['res'],
    8 => $ru3['res'],
    9 => $ru4['res']*/
    );

    return $ret;
  }
  /**
   * Gibt aktuelle Rohstoffmenge auf einem Planeten zurueck
   *
   * Berechnet die Menge fuer $NOW gibt sie zurueck.
   *
   * @param $coords String Koordinaten
   * @param $NOW int Aktuelle Zeit 
   * @return Array Rohstoffe
   */

function read_res($asCoords,$abTF = false)
{
    $lodb = gigraDB::db_open();
    
    //ermittle rohstoffe und prod
    $laRow = $lodb->getOne("SELECT r1,r2,r3,r4,u1,mine1,mine2,mine3,mine4,e_all,e_used,capa1,capa2,capa3,capa4,recalc FROM rohstoffe WHERE coords = '{$asCoords}'");
    if(!$laRow)
        return false;
    
    
    if($laRow['recalc'] == 1)
    {
        $laProd = calc_prod($asCoords,$laRow);
        $lodb->query("UPDATE rohstoffe SET mine1 = {$laProd[0]}, ".
                        "mine2 = {$laProd[1]},"." mine3 = {$laProd[2]}, ".
                        "mine4 = {$laProd[3]},"." e_all = {$laProd[4]}, ".
                        "e_used = {$laProd[5]}, ".
                        "capa1 = {$laProd[6]}, ".
                        "capa2 = {$laProd[7]}, ".
                        "capa3 = {$laProd[8]}, ".
                        "capa4 = {$laProd[9]}, ".
                        "recalc = 0 ".
                        
                        "WHERE coords = '{$asCoords}'"
        );
    }
    else
    {
        $laProd = array(
                    0 => $laRow['mine1'],
                    1 => $laRow['mine2'],
                    2 => $laRow['mine3'],
                    3 => $laRow['mine4'],
                    4 => $laRow['e_all'],
                    5 => $laRow['e_used'],
                    6 => $laRow['capa1'],
                    7 => $laRow['capa2'],
                    8 => $laRow['capa3'],
                    9 => $laRow['capa4']            
            );
            
    }
    
    $laRes = calc_res($laRow,$laProd,time());
    
    //hole aus der queue
    $laQueue = getResItemQueue($asCoords);
    if(array_sum($laQueue) != 0)
    {
        
        $laRes[0] += $laQueue[0];
        $laRes[1] += $laQueue[1];
        $laRes[2] += $laQueue[2];
        $laRes[3] += $laQueue[3];
        //alles was nicht reinpasste ins TF
        $laTF = array();
        for($i=0;$i<4;$i++)
        {
            $laTF[$i] = 0;
            $liKapa = $laProd[6+$i];
        	if($laRes[$i] >= $liKapa)
        	{
        		$laTF[$i] = $laRes[$i] - $liKapa;
                $laRes[$i] = $liKapa;//!!!!!!!
        	}
        }
    
        addTF($asCoords,$laTF[0],$laTF[1],$laTF[2],$laTF[3]);
        $lsQuery = "UPDATE rohstoffe SET r1=$laRes[0],r2=$laRes[1],r3=$laRes[2],r4=$laRes[3],u1=UNIX_TIMESTAMP() WHERE coords='$asCoords'";
        
        $lodb->query($lsQuery);
    }
    $laRes["prod"] = $laProd;
    
    if($abTF)
    {
        $laTF = getTF($asCoords);   
        $laRes['tf1'] = $laTF[0];
        $laRes['tf2'] = $laTF[1];
        $laRes['tf3'] = $laTF[2];
        $laRes['tf4'] = $laTF[3];
    }
    
    
    return $laRes;
}
  function read_res_bu($coords,$NOW=-1,$tf=false)
  {
  	$db = gigraDB::db_open();
    if($NOW==-1)
    $NOW = time();
    if(!$tf)
        $lsQuery = "SELECT r1,r2,r3,r4,u1,e_used,e_all FROM rohstoffe WHERE coords='$coords'";
    else
      $lsQuery ="SELECT r1,r2,r3,r4,u1,e_used,e_all,tf1,tf2,tf3,tf4 FROM rohstoffe WHERE coords='$coords'";
     
    
    $res = $db->getOne($lsQuery);
    
    
 
    if($res == false)
      return array(0,0,0,0,0,0,0,0);
    $prod = calc_prod($coords,$res);
    
    $ret = calc_res($res,$prod,$NOW);
    if($tf)
    {
      $ret['tf1'] = $res['tf1'];
      $ret['tf2'] = $res['tf2'];
      $ret['tf3'] = $res['tf3'];
      $ret['tf4'] = $res['tf4'];
    }
    
    //hole aus der queue
    $laQueue = getResItemQueue($coords);
    if(array_sum($laQueue) != 0)
    {
        
        $ret[0] += $laQueue[0];
        $ret[1] += $laQueue[1];
        $ret[2] += $laQueue[2];
        $ret[3] += $laQueue[3];
        //alles was nicht reinpasste ins TF
        $tf = array();
        for($i=0;$i<4;$i++)
        {
            $tf[$i] = 0;
        	$kapa = $prod[6+$i];
        	if($ret[$i] >= $kapa)
        	{
        		$tf[$i] = $ret[$i] - $kapa;
                $ret['tf'.$i-1] = $tf[i];
                $ret[$i] = $kapa;
        	}
        }
    
        $query = "UPDATE rohstoffe SET r1=$ret[0],r2=$ret[1],r3=$ret[2],r4=$ret[3],tf1=tf1+$tf[0],tf2=tf2+$tf[1],tf3=tf3+$tf[2],tf4=tf4+$tf[3],u1=UNIX_TIMESTAMP() WHERE coords='$coords'";
        
        $db->query($query);
    }
    $ret["prod"] = $prod;
    //var_export($ret);
    
    return $ret;
  }

  /**
   * Fuegt Rohstoffe zu einem Konto hinzu.
   *
   * $res ein Array mit den Indices 0-3 und den Rohstoffwerten
   * $coords Ein String mit Koordinaten in der Form "12:34:5"
   *
   * @param res Array
   * @param coords String
   */

  function add_res($res,$coords,$NOW=-1)
  {
      
    gigraDB::db_open()->query(sprintf("INSERT INTO itemqueue (coords,r1,r2,r3,r4) VALUES ('$coords',%s,%s,%s,%s)",
        isset($res[0]) ? $res[0] : 0,
        isset($res[1]) ? $res[1] : 0,
        isset($res[2]) ? $res[2] : 0,
        isset($res[3]) ? $res[3] : 0
    ));
    
    return;
    
    waitUnlock("add_ress");
    lock("add_ress");
  	$db = gigraDB::db_open();
    if($NOW==-1) $NOW = time();
    $rold = read_res($coords,$NOW);
    $res[0] = $res[0] + $rold[0];
    $res[1] = $res[1] + $rold[1];
    $res[2] = $res[2] + $rold[2];
    $res[3] = $res[3] + $rold[3];
    
    //alles was nicht reinpasste ins TF
    $tf = array();
    for($i=0;$i<4;$i++)
    {
    	$tf[$i] = 0;
    	$kapa = $rold["prod"][6+$i];
    	if($res[$i] >= $kapa)
    	{
    		$tf[$i] = $res[$i] - $kapa;
    	}
    }

    $query = "UPDATE rohstoffe SET r1=$res[0],r2=$res[1],r3=$res[2],r4=$res[3],tf1=tf1+$tf[0],tf2=tf2+$tf[1],tf3=tf3+$tf[2],tf4=tf4+$tf[3],u1=UNIX_TIMESTAMP() WHERE coords='$coords'";
    $db->query($query);
    unlock("add_ress");
    gigraMC::clearCache("resource");
    //fix
    /*
    if(mysql_affected_rows() == 0)
    	mysql_query("INSERT INTO rohstoffe SET r1=$res[0],r2=$res[1],r3=$res[2],r4=$res[3],u1=UNIX_TIMESTAMP() WHERE coords='$coords'");  
    */	
    }

  /**
   * Das selbe wie add_res() zieht Rohstoffe aber ab.
   * @param $res Array Rohstoffe
   * @param $coords String Koordinaten
   */

  function sub_res($res,$coords,$NOW=-1)
  {
    $res[0] =-$res[0];
    $res[1] =-$res[1];
    $res[2] =-$res[2];
    $res[3] =-$res[3];
    add_res($res,$coords,$NOW);
  }


function resRecalc($asCoords)
{
    gigraDB::db_open()->query("UPDATE rohstoffe SET recalc = 1 WHERE coords = '$asCoords'");
    
    $laRes = read_res($asCoords);
    
    gigraDB::db_open()->query("UPDATE rohstoffe SET r1 = {$laRes[0]},r2 = {$laRes[1]},r3 = {$laRes[2]},r4 = {$laRes[3]} , u1 = UNIX_TIMESTAMP() WHERE coords = '$asCoords'");
}

function changeProd($asCoords,$aaProdFaktors)
{
    gigraMC::clearCache("prod");
    foreach($aaProdFaktors as $liProdType => $liProdFaktor)
    {
        if($liProdFaktor >= 0 && $liProdFaktor <= 10)
        {
            resRecalc($asCoords);
            gigraDB::db_open()->query("UPDATE rohstoffe SET prod{$liProdType} = '{$liProdFaktor}' WHERE coords = '{$asCoords}'");
        }
    }
}


function getHandelsKurse()
{
    $lodb = gigraDB::db_open();
    
    $liMinVal = -1;
    $laRes = array();
    
    for($i=1;$i<=4;$i++)
    {
        //$laRow = $lodb->getOne("SELECT sum(r$i) FROM ((select r$i from rohstoffe r left join planets p on p.coords = r.coords left join users u on u.id = p.owner where u.admin = 0 limit 20) union all (select tf$i as r$i from rohstoffe limit 0) union all (select load$i as r$i from flotten limit 0) order by r$i desc) as t");
        $laRow = $lodb->getOne("SELECT sum(r$i) FROM (select r$i from rohstoffe r left join planets p on p.coords = r.coords left join users u on u.id = p.owner where u.admin = 0 order by r$i desc limit 50) as t");
        $laRes[$i] = $laRow[0];
        
        $liMinVal = $liMinVal == -1 ? $laRes[$i] : min($liMinVal, $laRes[$i]);
    }
    $laKurse = array(
        1 => round($laRes[1] / $liMinVal,1),  
        2 => round($laRes[2] / $liMinVal,1),
        3 => round($laRes[3] / $liMinVal,1),
        4 => round($laRes[4] / $liMinVal,1),
    );
    
    return $laKurse;
}


function getTF($coords)
{
    $lodb = gigraDB::db_open();
    
    //reform coords
    $coords = coordReform($coords,"3");
    if(!$coords) return false;
    
    $laRow = $lodb->getOne("SELECT r1,r2,r3,r4 FROM rohstoffe WHERE coords = '$coords'");
    
    if(!$laRow)
        createPlanet('0',$coords,0);//Erstelle neues Truemmerfeld
    
    $laRow = $lodb->getOne("SELECT r1,r2,r3,r4 FROM rohstoffe WHERE coords = '$coords'");
    
    
    return array($laRow[0],$laRow[1],$laRow[2],$laRow[3]);
    
}
function addTF($coords,$r1,$r2,$r3,$r4)
{
    $coords = coordReform($coords,'3');
    if(!$coords) return false;
    
    $laTF = getTF($coords);
    
    if(!$laTF)
        return false;
    
    gigraDB::db_open()->query("UPDATE rohstoffe SET r1 = r1 + {$r1}, r2 = r2 + {$r2}, r3 = r3 + {$r3}, r4 = r4 + {$r4} WHERE coords = '{$coords}'");
}

function subTF($coords,$r1,$r2,$r3,$r4)
{
    addTF($coords,-($r1),-($r2),-($r3),-($r4));
}
?>
