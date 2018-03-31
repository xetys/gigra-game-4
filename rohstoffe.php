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

$laTplExport = array();
$lodb = gigraDB::db_open();

if(isset($_GET["amd"]) && $_GET["amd"] == 1)
    allMinesDown(Uid());

if(isset($_GET["amu"]) && $_GET["amu"] == 1)
    allMinesUp(Uid());
    
//Neu berechnen der Rohstoffe
if(isset($_POST['recalc']))
{
    $laFormProdFaktors = array("1" => $_POST['prod1'], "2" => $_POST["prod2"], "3" => $_POST["prod3"], "4" => $_POST["prod4"], "5" => $_POST["prod5"]);
    if(checkUMOD(Uid()))
        $laFormProdFaktors = array("1" => 0, "2" => 0, "3" => 0, "4" => 0, "5" => 0);

    changeProd($_SESSION["coords"],$laFormProdFaktors);
}

$laRessProdList = array();


$k  = getBuildings();
$laProd = calc_prod($_SESSION['coords']);
$s = read_schiffe($_SESSION["coords"]);
$laForschung = getForschung(Uid());

$liEnergieBonus = 100 * (getResearchBonusFaktor(isset($laForschung['f15']) ? $laForschung['f15'] : 0) / 100);
$liMinenBonus = 100 * (getResearchBonusFaktor(isset($laForschung['f7']) ? $laForschung['f7'] : 0) / 100);
 
$laTplExport["mine_bonus"] = $liMinenBonus;
$laTplExport["energy_bonus"] = $liEnergieBonus;

$laSkills = getSkills(Uid());

$laTplExport['skill_bonus'] = $laSkills['infra_rohstoff'];

$laProdFaktor = $lodb->getOne("SELECT prod1, prod2, prod3, boost_percent, boost_until, prod4, prod5  FROM rohstoffe WHERE coords = '{$_SESSION['coords']}'");

$laTplExport['boost_percent'] = $laProdFaktor['boost_percent'];
$laTplExport['boost_until'] = $laProdFaktor['boost_until'];

//1. Eisenmine
$liStufe = isset($k['k3']) && $k['k3'] > 0 ? $k['k3'] : 0;
$liProd = $laProd[10];
$laRessProdList[1]['active'] = $liStufe > 0 ? true : false;
$laRessProdList[1]['title'] = l('item_b3');
$laRessProdList[1]['lvl'] = $liStufe;
$laRessProdList[1]['img'] = "/design/items/b3.gif";
$laRessProdList[1]['prod'] = array( "res1" => $liProd, "energy" => -$laProd[17]);   
$laRessProdList[1]['faktor'] = $laProdFaktor[0];

$laRessProdList[1]['capa'] = $laProd[6];


//2. Titanmine
$liStufe = isset($k['k4']) && $k['k4'] > 0 ? $k['k4'] : 0;
$liProd = $laProd[11];
$laRessProdList[2]['active'] = $liStufe > 0 ? true : false;
$laRessProdList[2]['title'] = l('item_b4');
$laRessProdList[2]['lvl'] = $liStufe;
$laRessProdList[2]['img'] = "/design/items/b4.gif";
$laRessProdList[2]['prod'] = array( "res2" => $liProd,"energy" => -$laProd[18]);

$laRessProdList[2]['capa'] = $laProd[7];
$laRessProdList[2]['faktor'] = $laProdFaktor[1];

//3. Bohrturm
$liStufe = isset($k['k5']) && $k['k5'] > 0 ? $k['k5'] : 0;
$liProd = $laProd[12];
$laRessProdList[3]['active'] = $liStufe > 0 ? true : false;
$laRessProdList[3]['title'] = l('item_b5');
$laRessProdList[3]['lvl'] = $liStufe;
$laRessProdList[3]['img'] = "/design/items/b5.gif";
$laRessProdList[3]['prod'] = array( "res3" => $liProd, "energy" => -$laProd[19]);                            

$laRessProdList[3]['capa'] = $laProd[8];
$laRessProdList[3]['faktor'] = $laProdFaktor[2];

//4. chemFab
$liStufe = isset($k['k6']) && $k['k6'] > 0 ? $k['k6'] : 0;
$liProd = $laProd[13] / 5;
$laRessProdList[4]['active'] = $liStufe > 0 ? true : false;
$laRessProdList[4]['title'] = l('item_b6');
$laRessProdList[4]['lvl'] = $liStufe;
$laRessProdList[4]['img'] = "/design/items/b6.gif";
$laRessProdList[4]['prod'] = array( "res4" => $liProd,"res3" => -$laProd[13], "energy" => -$laProd[20]);                            

$laRessProdList[4]['capa'] = $laProd[9];
$laRessProdList[4]['workfac'] = 10;//todo
$laRessProdList[4]['faktor'] = $laProdFaktor['prod4'];

//5. e chemFab
$liStufe = isset($k['k7']) && $k['k7'] > 0 ? $k['k7'] : 0;
$liProd = $laProd[14] / 2;
$laRessProdList[5]['active'] = $liStufe > 0 ? true : false;
$laRessProdList[5]['title'] = l('item_b7');
$laRessProdList[5]['lvl'] = $liStufe;
$laRessProdList[5]['img'] = "/design/items/b7.gif";
$laRessProdList[5]['prod'] = array( "res4" => $liProd,"res3" => -$laProd[14], "energy" => -$laProd[21]);                            
$laRessProdList[5]['capa'] = $laProd[9];
$laRessProdList[5]['workfac'] = 10;//todo
$laRessProdList[5]['faktor'] = $laProdFaktor['prod5'];


//6. Kraftwerk
$liStufe = isset($k['k8']) && $k['k8'] > 0 ? $k['k8'] : 0;
$liProd = $laProd[15];
$laRessProdList[6]['active'] = $liStufe > 0 ? true : false;
$laRessProdList[6]['title'] = l('item_b8');
$laRessProdList[6]['lvl'] = $liStufe;
$laRessProdList[6]['img'] = "/design/items/b8.gif";
$laRessProdList[6]['prod'] = array( "energy" => $liProd);                            
$laRessProdList[6]['cons'] = array();
$laRessProdList[6]['workfac'] = 10;//todo

//7. Solsats
$liStufe = isset($s['15']) && $s['15'] > 0 ? $s['15'] : 0;
$liProd = $laProd[16];
$laRessProdList[7]['active'] = $liStufe > 0 ? true : false;
$laRessProdList[7]['title'] = l('item_s15');
$laRessProdList[7]['lvl'] = $liStufe;
$laRessProdList[7]['img'] = "/design/items/s15.gif";
$laRessProdList[7]['prod'] = array( "energy" => $liProd);                            

$laRessProdList[7]['workfac'] = 10;//todo


//Gesamt uebersicht
$laAllProd = array("res1" => $laProd[0], "res2" => $laProd[1], "res3" => $laProd[2], "res4" => $laProd[3]);
$laTplExport["allProd"] = $laAllProd;
$laTplExport["laProd"] = $laProd;

$laTplExport['resProdList'] = $laRessProdList;
buildPage("rohstoffe.tpl",$laTplExport);