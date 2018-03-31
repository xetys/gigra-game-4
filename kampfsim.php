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

/*echo "<pre>";
var_export($_POST);
die();*/
define('FLEETS',20);
define("GIGRA_INTERN", true);
define("GIGRA_MUSTSESSION",false);

include 'core/core.php';


//dbg($_REQUEST);
if(isset($_POST['send']) && isset($_GET['send']))
    $_POST = $_POST;
else
{
    if(isset($_GET['send']))
        $_POST = $_GET;
}


//Eigene Forschungen
$laForschung = getForschung(Uid());
$laSkills = getSkills(Uid());

if((!isset($_POST['A_1_F5']) || $_POST['A_1_F5'] == '') && isset($laForschung['f5']))
{
    $_POST['A_1_F5'] = $laForschung['f5'];
}
if((!isset($_POST['A_1_F6']) || $_POST['A_1_F6'] == '') && isset($laForschung['f6']))
{
    $_POST['A_1_F6'] = $laForschung['f6'];
}
if((!isset($_POST['A_1_F9']) || $_POST['A_1_F9'] == '') && isset($laForschung['f9']))
{
    $_POST['A_1_F9'] = $laForschung['f9'];
}
if((!isset($_POST['A_1_KL']) || $_POST['A_1_KL'] == '') && isset($laSkills['krieg_treffer']))
{
    $_POST['A_1_KL'] = $laSkills['krieg_treffer'];
}

$laTplExport = array();


//fuer auswertung
$gewinner = "keiner";
$lost_a = 0;
$lost_v = 0;
$tf = "0 GS, 0 FT(ca. 0 Recycler)";
$beute_a = "0 GS, 0 FT, 0 H2 (ca. 0 Gr.Transporter)";
$mondchance = 0;
if($_POST['send']==1)
{
    //dbg($_POST);
	$angreifer_daten = array();
	$verteidiger_daten = array();

	$angreifer_schiffe = array();
	$verteidiger_schiffe = array();
	$verteidiger_tuerme = array();

	$angreifer_techniken = array();
	$verteidiger_techniken = array();

	$ressis = array();

	//Zaehle Schiffe
	$a_player = array();
	$v_player = array();
    $a_count_all;
	for($i=1;$i<=FLEETS;$i++)
	{
		$count_a = 0;
		$count_v = 0;
		foreach ($_SHIP AS $id => $v)
		{
			$count_a += $_POST['A'][$i][$id];
            $a_count_all += $_POST['A'][$i][$id];
			$count_v += $_POST['V'][$i][$id];
		}
		foreach ($_VERT AS $id => $v)
		{
			$count_v += $_POST['V'][$i]["V".$id];
		}		
		if($count_a>0)
			$a_player[$i] = true;
		if($count_v>0)
			$v_player[$i] = true;
	}
    
    if($a_count_all > 0)
    {
    	//Ermittle Angreifer
    	foreach ($a_player AS $a => $b)
    	{
    		$angreifer_daten[$a]["name"] = "Angreifer".$a;
    		$angreifer_daten[$a]["coords"] = "1:1:".$a;
    
    		$angreifer_techniken[$a]["f5"] = $_POST["A_{$a}_F5"];
    		$angreifer_techniken[$a]["f6"] = $_POST["A_{$a}_F6"];
    		$angreifer_techniken[$a]["f7"] = $_POST["A_{$a}_F7"];
    		$angreifer_techniken[$a]["f9"] = $_POST["A_{$a}_F9"];
    		$angreifer_techniken[$a]["klevel"] = $_POST["A_{$a}_KL"];
            $angreifer_techniken[$a]["bonus"] = $angreifer_daten[$a]["bonus"] = isset($_POST["A_{$a}_BON"]) && $_POST["A_{$a}_BON"] > 0 ? 1 + ($_POST["A_{$a}_BON"] / 100) : 1;
    		
    		$angreifer_daten[$a]["f"] = $angreifer_techniken[$a];
    		
    		
    		$schiffe = array();
    		foreach ($_SHIP AS $id => $v)
    		{
    
                if($_POST["A"][$a][$id] > 0)
    				$schiffe[$id] = $_POST["A"][$a][$id];
    
    		}
    		$angreifer_schiffe[$a] = $schiffe;
    	}
    	//und noch den Deffer schnell
    	foreach ($v_player AS $a => $b)
    	{
    		$verteidiger_daten[$a]["name"] = "Verteidiger".$a;
    		$verteidiger_daten[$a]["coords"] = "1:2:".$a;
    		
    		$verteidiger_techniken[$a]["f5"] = $_POST["V_{$a}_F5"];
    		$verteidiger_techniken[$a]["f6"] = $_POST["V_{$a}_F6"];
    		$verteidiger_techniken[$a]["f7"] = $_POST["V_{$a}_F7"];
    		$verteidiger_techniken[$a]["f9"] = $_POST["V_{$a}_F9"];
    		$verteidiger_techniken[$a]["klevel"] = $_POST["V_{$a}_KL"];
    		$verteidiger_techniken[$a]["bonus"] = $verteidiger_daten[$a]["bonus"] = isset($_POST["V_{$a}_BON"]) && $_POST["V_{$a}_BON"] > 0 ? 1 + ($_POST["V_{$a}_BON"] / 100) : 1;
    		$verteidiger_daten[$a]["f"] = $verteidiger_techniken[$a];
    		
    		$schiffe = array();
    		$tuerme = array();
    		
    		foreach ($_SHIP AS $id => $v)
    		{
                if($_POST["V"][$a][$id] > 0)
    				$schiffe[$id] = $_POST["V"][$a][$id];
    
    		}
    		if($a == 1)
    		{
    			foreach ($_VERT AS $id => $v)
    			{
                           if($_POST["V"][$a]["V".$id] > 0)
        			    $tuerme[$id] = $_POST["V"][$a]["V".$id];
    			}
    			$verteidiger_tuerme[$a] = $tuerme;
    		}
    		$verteidiger_schiffe[$a] = $schiffe;
    	}
    
    //Ressis
    $ressis["eisen"] = $_POST['eisen'];
    $ressis["titan"] = $_POST['titan'];
    $ressis["wasser"] = $_POST['wasser'];
    $ressis["wasserstoff"] = $_POST['wasserstoff'];
    
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[0] + $mtime[1];
    $start_time = $mtime;
    $ret = battle($angreifer_techniken,$angreifer_schiffe,array(),$verteidiger_techniken,$verteidiger_schiffe,$verteidiger_tuerme);
    
    //echo "<pre>";
   // var_export($ret);
    //die();
    $bericht = array();
        
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[0] + $mtime[1];
    $endtime = $mtime;
    $totaltime = $endtime - $start_time;
    
    //auswertung
    $bericht["atter_data"] = $angreifer_daten;
    $bericht["deffer_data"] = $verteidiger_daten;
    
    $bericht["kampf"] = $ret["runde"]; //Hier der gesammte Kampfprotokoll
    $bericht["winner"] = $ret["winner"];
    $bericht["a_lost"] = 0;
    $bericht["v_lost"] = 0;
    
    $loss = array("sa" => array(), "sv" => array(), "vv" => array());
    //Verluste berechnen
    foreach ($angreifer_schiffe as $a => $data)
    {
    	foreach ($data as $sa_sid => $sa_sc)
    	{
    		if(!isset($loss["sa"][$sa_sid]))
    			$loss["sa"][$sa_sid] = 0;
    		if(isset($ret["sa"][$a][$sa_sid]))
    			$loss["sa"][$sa_sid] += $sa_sc - $ret["sa"][$a][$sa_sid];
    		else 
    			$loss["sa"][$sa_sid] += $sa_sc;
    	}
    }
    foreach ($verteidiger_schiffe as $a => $data)
    {
    	foreach ($data as $sv_sid => $sv_sc)
    	{
    		if(isset($ret["sv"][$a][$sv_sid]))
    			$loss["sv"][$sv_sid] += $sv_sc - $ret["sv"][$a][$sv_sid];
    		else 
    			$loss["sv"][$sv_sid] += $sv_sc;
    	}
    }
    foreach ($verteidiger_tuerme as $a => $data)
    {
    	foreach ($data as $vv_sid => $vv_sc)
    	{
    		if(!isset($loss["vv"][$vv_sid]))
    			$loss["vv"][$vv_sid] = 0;
    		if(isset($ret["vv"][$a][$vv_sid]))
    			$loss["vv"][$vv_sid] += $vv_sc - $ret["vv"][$a][$vv_sid];
    		else 
    			$loss["vv"][$vv_sid] += $vv_sc;
    	}
    }
    $laderaum = 0;
    $sacount = 0;
    foreach ($ret["sa"] as $a => $data)
    {
    	foreach ($data as $sa_sid => $sa_sid)
    	{
    		$fa['f10'] = isset($fa['f10']) ? $fa['f10'] : 0;
    		$laderaum += $_SHIP[$sa_sid][10] * $sa_sc * (1 + $fa['f10'] * 0.01);
    		$sacount += $sa_sc; 
    	}
    }
    //TF
    /*
    TF-Regeln:
    Von 100% der zerstoerten einzelnen Rohstoffeanteile der Schiffe gehen
    	70% Eisen
    	50% Titan
    	80% Wasser
    	10% Wasserstoff
    ins TF
    Also bei einem Schiff das 1000 von allen 4 Rohstoffarten kostet kommen
    	700 Eisen
    	500 Titan
    	800 Wasser und
    	100 Wasserstoff
    	ins TF
    */
    $TF = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
    foreach ($loss["sa"] as $t => $c)
    {
    	$TF[1] += $_SHIP[$t][1] * $c * $_ACTCONF['debris_faktors'][0]; 
    	$TF[2] += $_SHIP[$t][2] * $c * $_ACTCONF['debris_faktors'][1];
    	$TF[3] += $_SHIP[$t][3] * $c * $_ACTCONF['debris_faktors'][2]; 
    	$TF[4] += $_SHIP[$t][4] * $c * $_ACTCONF['debris_faktors'][3];
    	
    	$bericht["a_lost"] += ($_SHIP[$t][1] + $_SHIP[$t][2] + $_SHIP[$t][3] + $_SHIP[$t][4]) * $c;
    }
    foreach ($loss["sv"] as $t => $c)
    {
    	$TF[1] += $_SHIP[$t][1] * $c * $_ACTCONF['debris_faktors'][0]; 
    	$TF[2] += $_SHIP[$t][2] * $c * $_ACTCONF['debris_faktors'][1]; 
    	$TF[3] += $_SHIP[$t][3] * $c * $_ACTCONF['debris_faktors'][2]; 
    	$TF[4] += $_SHIP[$t][4] * $c * $_ACTCONF['debris_faktors'][3];
    	
    	$bericht["v_lost"] += ($_SHIP[$t][1] + $_SHIP[$t][2] + $_SHIP[$t][3] + $_SHIP[$t][4]) * $c;
    }
    foreach ($loss["vv"] as $t => $c)
    {
    	if($_ACTCONF['defense_to_debris'])
        {
        	$TF[1] += $_VERT[$t][1] * $c * $_ACTCONF['debris_faktors'][0]; 
        	$TF[2] += $_VERT[$t][2] * $c * $_ACTCONF['debris_faktors'][1];
        	$TF[3] += $_VERT[$t][3] * $c * $_ACTCONF['debris_faktors'][2]; 
        	$TF[4] += $_VERT[$t][4] * $c * $_ACTCONF['debris_faktors'][3];
        }
    	
    	$bericht["v_lost"] += ($_VERT[$t][1] + $_VERT[$t][2] + $_VERT[$t][3] + $_VERT[$t][4]) * $c;
    }
    $bericht["tf"] = $TF;
    $bericht["mond"] = 0;
    
    //zur�ck zum Sim
    $lost_a = nicenum($bericht['a_lost']);
    $lost_v = nicenum($bericht['v_lost']);
    
    
    $tf_all = array_sum($TF);
    
    $kapazitaet_recycler = $_SHIP[2][10];
    $need_recycler = nicenum(ceil($tf_all/$kapazitaet_recycler));
    $tf = nicenum($TF[1]) . " E, " . nicenum($TF[2]) . " T, " . nicenum($TF[3]) . " W, " . nicenum($TF[4]) . " WS, (ca. {$need_recycler} Recycler)";
    $mond_chance = $tf_all > 100000 ? min(round($tf_all,-5) / 100000,40) : 0;
    $bericht["mondchance"] = $mond_chance;
    
    //beute
    
    $res = array(0 => $_POST["eisen"] , 1 => $_POST["titan"], 2 => $_POST["wasser"], 3 => $_POST["wasserstoff"] );
    for($ii=0;$ii<4;$ii++) $res[$ii] = $res[$ii]/2;
    $resSum = ($res[0] + $res[1] + $res[2] + $res[3]);
    $plu = array();
    if($resSum > 0)
    {
    	$ladefaktor = min(($laderaum/$resSum),1);
    	for($i=0;$i<4;$i++)  //Piraten! (fnord)
    	{
    	//$maxp = $res[$i]-(2000+400*$kv['k'.(8+$i)]*$kv['k'.(8+$i)]);  //Maximal Pluenderbar
    	$plu[$i] = round(($res[$i]) * $ladefaktor); // maximal 50% pluenderbar
    	//$plu[$i] = max(min($laderaum,$maxp),0);
    	$bericht['pr'.$i] = $plu[$i];
    	}
    }
    
    $beute_all = array_sum($plu);;
    $kapazitaet_gr_trans = $_SHIP[13][10];
    $need_gr_trans = nicenum(ceil($beute_all/$kapazitaet_gr_trans));
    $beute_a = nicenum($res[0]) . " E, " . nicenum($res[1]) . " T, " . nicenum($res[2]) . " W, " . nicenum($res[3]) . " WS, (ca. {$need_gr_trans} Gr.Handelsschiffe)";
    
    
    if($ret['winner']=="a" || $ret['winner']=="v")
    {
    	$gewinner = (($ret['winner'] == "a") ? "Angreifer" : "Verteidiger");
    }
    else 
    {
    	$gewinner = "keiner";
    }
    
    $mondchance = $mond_chance;
    
    
    $out = showKB($bericht,"X:X:X","X:X:X",time());
    
    if(isAdmin())
        $out .= "BattleID:{$ret["battleID"]}<table><tr><td class=c>Berechnungszeit</td></tr><tr><th>{$totaltime}</th></tr></table>";
    }
}
$laTplExport['out'] = $out;
$laTplExport['mondchance'] = $mondchance;
$laTplExport['beute_a'] = $beute_a;
$laTplExport['need_gr_trans'] = $need_gr_trans;
$laTplExport['need_recycler'] = $need_recycler;
$laTplExport['tf'] = $tf;
$laTplExport['lost_a'] = $lost_a;
$laTplExport['lost_v'] = $lost_v;
$laTplExport['gewinner'] = $gewinner;

$laTplExport['_SHIP'] = $_SHIP;
$laTplExport['_VERT'] = $_VERT;

buildPage("kampfsim.tpl", $laTplExport);
?>