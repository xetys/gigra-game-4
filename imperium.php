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


$lsQuery = "SELECT p.coords,pname,pbild,temp,dia,k1,k2,k3,k4,k5,k6,k7,k8,k9,k10,k11,k12,k13,k14,k15,k16,k17,k18,k19,k20,k21,s,v FROM v_planets p  LEFT JOIN gebaeude g ON p.coords = g.coords LEFT JOIN schiffe s ON p.coords = s.coords LEFT JOIN verteidigung v ON p.coords = v.coords WHERE p.owner = '".Uid()."' ORDER BY gal,sys,plan,type";
$lodb->query($lsQuery);

$laPlanets = array();
$laAve = array(
        'res' => array(0,0,0,0,'-','-'),
        'schiffe' => array(),
        'deff' => array(),
    );
$liPCount = 0;
while($laRow = $lodb->fetch())
{
    $laPlanet = $laRow;
    $laPlanet['pbild'] = coordType($laRow[0]) == 2 ? "mond.png" : str_replace(".gif",".png",$laRow['pbild']);
    $kelvin = $laRow['temp']+273;
    $bereich = ($laRow['dia']+100000)/121000;
    $laPlanet['tempFrom'] = nicenum($kelvin*$bereich-273);
    $laPlanet['tempTo'] = nicenum($kelvin*(2-$bereich)-273);
    
    $laPlanet['res'] = read_res($laRow[0]);
    //var_export($laPlanet['res']);
    for($i=0;$i<4;$i++)
        $laAve['res'][$i] += $laPlanet['res'][$i];
    
    //gebs
    for($i=1;$i<=21;$i++)
    {
        if(!isset($laAve['k'.$i])) $laAve['k'.$i] = 0;
        $laAve['k'.$i] += $laRow['k'.$i];
    }
    
    $laPlanet['schiffe'] = ikf2array($laRow['s']);
    
    foreach($_SHIP as $id => $_x)
    {
        if(!isset($laAve['schiffe'][$id])) $laAve['schiffe'][$id] = 0;
        
        if(isset($laPlanet['schiffe'][$id]))
            $laAve['schiffe'][$id] += $laPlanet['schiffe'][$id];
    }
    
    
    $laPlanet['deff'] = ikf2array($laRow['v']);
    foreach($_VERT as $id => $_x)
    {
        if(!isset($laAve['deff'][$id])) $laAve['deff'][$id] = 0;
        
        if(isset($laPlanet['deff'][$id]))
            $laAve['deff'][$id] += $laPlanet['deff'][$id];
    }
    
    $laPlanets[] = $laPlanet;
    $liPCount++;
}

//Durschschnitts rechnung
for($i=1;$i<=21;$i++)
{
    $laAve['k'.$i] = round($laAve['k'.$i] / $liPCount,1);
}


$laTplExport['planets'] = $laPlanets;
$laTplExport['_BAU'] = $_BAU;
$laTplExport['_FORS'] = $_FORS;
$laTplExport['_SHIP'] = $_SHIP;
$laTplExport['_VERT'] = $_VERT;
$laTplExport['forschung'] = getForschung(Uid());
$laTplExport['ave'] = $laAve;

buildPage("imperium.tpl", $laTplExport);
?>