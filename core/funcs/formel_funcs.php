<?php

define("XP_BASIC",109);
define("XP_FACTOR",1.0794);

/**
 * 
 * Rechnet Erfahrungspunkte zu Erfahrungsstufe um
 * @param unknown_type $points
 */
function getELevel($points)
{
    //return floor(log10(($points/5)+1)/log10(2));
    return floor(umgesufo(XP_BASIC,XP_FACTOR,$points));
}
function getNextLevelSum($level)
{
	//return 5*((pow(2,$level+1)-1));
    return ceil(sufogerei(XP_BASIC,XP_FACTOR,$level+1));
}

function sufogerei($A,$q,$n)
{
    return $A * ((1 - pow($q,$n)) / (1 - $q));
}
/**
*   Umgestellte Summenformel einer Geometrischen Reihe
*/
function umgesufo($A,$q,$y)
{
    return log10($y*($q-1) / $A + 1) / log10($q);
}
/**
 * Berechnet Stufenkosten
 * Enter description here ...
 * @param unknown_type $rohstoffe
 * @param unknown_type $stufe
 * @param unknown_type $faktor
 */
function expoFunktion($rohstoffe,$stufe,$faktor = 1.5)
{
	return $rohstoffe * pow($faktor,$stufe-1);
}

  /**
* Teiler fuer die Zeit pro Stufe der Kommandozentrale
* @param $stufe Stufe der Kommandozentrale
* @return int Multiplikator
*/
  function foe($stufe)
  {
    if($stufe<1)
    return 0;
    return(int)((1+($stufe*$stufe+1)/10*6));
  }
  /**
* Formel fuer Multiplikator fuer Rohstoffe und Zeit pro Stufe
* @param $stufe int Stufe
* @return int Multiplikator
*/
  function formel_stufe($stufe)
  {
    if($stufe<1)
    {
      $stufe=0;
    }
    else
    {
      $stufe=trim($stufe);
    }
    $stufe++;
    #return (int)(1+($stufe*$stufe*$stufe+10)/2);
    return (int)(2*pow(1.5,$stufe-1));
  }
  

function bpunkte($id, $level) {
    global $_BAU;
	#echo "ID:$id\n";
	$a = ($_BAU [$id] [2] + $_BAU [$id] [3] + $_BAU [$id] [4] + $_BAU [$id] [5]);
	$n = $level;
	$q = 1.5;
	//Wir brauchen die Punkte
	$a = $a / 1000;
	
	return summenFormel ( $a, $q, $n );
}
function summenFormel($a, $q, $n) {
	//Summenformel fuer geometrische Reihen
	

	return ($a * 2 * ((1 - pow ( $q, $n )) / (1 - $q)));
}
function fpunkte($id, $level) {
	global $_FORS;
	$a = ($_FORS [$id][2] + $_FORS[$id][3] + $_FORS[$id][4] + $_FORS[$id][5]);
	$n = $level;
	$q = 1.5;
	//Wir brauchen die Punkte
	$a = $a / 1000;
	
	return summenFormel ( $a, $q, $n );
}
function spunkte($id,$count)
{
	global $_SHIP;
	return $count * ($_SHIP[$id][1] + $_SHIP[$id][2] + $_SHIP[$id][3] + $_SHIP[$id][4]) / 1000; 
}
function vpunkte($id,$count)
{
	global $_VERT;
	return $count * ($_VERT[$id][1] + $_VERT[$id][2] + $_VERT[$id][3] + $_VERT[$id][4]) / 1000; 
}
function expSaet($x)
{
    return 100 * (1 - (exp(-0.13*$x)));
}

function chanceDecide($aiChance,$abHundret = true)
{
    $liFaktor = $abHundret ? 1 : 100;
    return date("s") == "29" ? true : mt_rand(1,100*$liFaktor) <= $aiChance*$liFaktor ;//bisschen schummeln :P
}


///Schildreaktor

function shieldEnergyPerHour($aiLevel,$aiTech)
{
    return dynround(30 * pow($aiLevel,3.75))*(1 + $aiTech / 20);
}

function shieldPower($aiLevel,$aiTech)
{

    return dynround(500 * pow($aiLevel,3.75)) *(1 + $aiTech / 20);
}

function dynround($aiInt)
{
    $liTeiler = 1;
	$liStellen = 0;
	while($aiInt/$liTeiler > 1)
	{
		$liTeiler = $liTeiler * 10;
		$liStellen++;
	}
	$liStellen = $liStellen - 2;
	return  round($aiInt,-($liStellen));
}

?>