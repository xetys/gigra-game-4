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

$k = getBuildings();
$f = getForschung();

$laTechnik = array();

//Gebs

foreach($_BAU as $liID => $laValue)
{
    //sicht bar?
    $lbVisible = true;
    
    $laReq = listReqs("B",$liID);
    $lsReqs = "";
    foreach($laReq as $Req)
    {
        
        foreach($Req as $t => $l)
        {
            $lsColor = "lime";
            $lsType = substr($t,0,1);
            $liNid = substr($t,1);
            $liHas = $lsType == "B" ? $k["k".$liNid] : $f["f".$liNid];
 
            if($liHas < $l)
                $lsColor = "red";
            
            $lsItemName = l('item_'.strtolower($t));
            $lsItemLevel = $l > 0 ? "($liHas/$l)" : "";
            $lsReqs .= "<font color='$lsColor'>{$lsItemName}{$lsItemLevel}</font><br>";
        }
        $lsReqs = substr($lsReqs,0,-strlen("<br>"));//ja hergott ich weiß, ich hätte hier auch statt strlen(<br>) auch 4 nehmen könne, aber so wirst du idiot das besser verstehen wenn du das siehst ;)
        
        $lsReqs .= "<br>".l('tech_or')."<br>";
    }
    
    $lsReqs = substr($lsReqs,0,-strlen("<br>".l('tech_or')."<br>"));//hier aller ding ist das zwingend notwendig, verschiedene sprachen gell ;)
    
    //pic
    $lsImg = "design/items/b{$liID}.gif";
    if($lbVisible)
        $lsBg = "url($lsImg)";
    else
        $lsBg = "transparent";
        
    $link = "info.php?obj=b$liID";
    $laTechnik['b'][$liID] = array(
            "name"  => l('item_b'.$liID),
            "link" => $link,
            "bg" => $lsBg,
            "img" => $lsImg,
            "reqs" => $lsReqs
        );
}

foreach($_FORS as $liID => $laValue)
{
    //sicht bar?
    $lbVisible = true;
    
    $laReq = listReqs("F",$liID);
    $lsReqs = "";
    foreach($laReq as $Req)
    {

        foreach($Req as $t => $l)
        {
            $lsColor = "lime";
            $lsType = substr($t,0,1);
            $liNid = substr($t,1);
            $liHas = $lsType == "B" ? $k["k".$liNid] : $f["f".$liNid];
 
            if($liHas < $l)
                $lsColor = "red";
            
            $lsItemName = l('item_'.strtolower($t));
            $lsItemLevel = $l > 0 ? "($liHas/$l)" : "";
            $lsReqs .= "<font color='$lsColor'>{$lsItemName}{$lsItemLevel}</font><br>";
        }
        $lsReqs = substr($lsReqs,0,-strlen("<br>"));//ja hergott ich weiß, ich hätte hier auch statt strlen(<br>) auch 4 nehmen könne, aber so wirst du idiot das besser verstehen wenn du das siehst ;)
        
        $lsReqs .= "<br>".l('tech_or')."<br>";
    }
    
    $lsReqs = substr($lsReqs,0,-strlen("<br>".l('tech_or')."<br>"));//hier aller ding ist das zwingend notwendig, verschiedene sprachen gell ;)
    
    //pic
    $lsImg = "design/items/f{$liID}.gif";
    if($lbVisible)
        $lsBg = "url($lsImg)";
    else
        $lsBg = "transparent";
    
    $link = "info.php?obj=f$liID";
    $laTechnik['f'][$liID] = array(
            "name"  => l('item_f'.$liID),
            "link" => $link,
            "bg" => $lsBg,
            "img" => $lsImg,
            "reqs" => $lsReqs
        );
}


foreach($_SHIP as $liID => $laValue)
{
    //geheimschiff
    if($liID > 100 && !isAdmin())
        continue;
    //sicht bar?
    $lbVisible = true;
    
    $laReq = listReqs("S",$liID);
    $lsReqs = "";
    foreach($laReq as $Req)
    {
        
        foreach($Req as $t => $l)
        {
            $lsColor = "lime";
            $lsType = substr($t,0,1);
            $liNid = substr($t,1);
            $liHas = $lsType == "B" ? $k["k".$liNid] : $f["f".$liNid];
 
            if($liHas < $l)
                $lsColor = "red";
            
            $lsItemName = l('item_'.strtolower($t));
            $lsItemLevel = $l > 0 ? "($liHas/$l)" : "";
            $lsReqs .= "<font color='$lsColor'>{$lsItemName}{$lsItemLevel}</font><br>";
        }
        $lsReqs = substr($lsReqs,0,-strlen("<br>"));//ja hergott ich weiß, ich hätte hier auch statt strlen(<br>) auch 4 nehmen könne, aber so wirst du idiot das besser verstehen wenn du das siehst ;)
        
        $lsReqs .= "<br>".l('tech_or')."<br>";
    }
    
    $lsReqs = substr($lsReqs,0,-strlen("<br>".l('tech_or')."<br>"));//hier aller ding ist das zwingend notwendig, verschiedene sprachen gell ;)
    
    //pic
    $lsImg = "design/items/s{$liID}.gif";
    if($lbVisible)
        $lsBg = "url($lsImg)";
    else
        $lsBg = "transparent";
    
    $link = "info.php?obj=s$liID";
    $laTechnik['s'][$liID] = array(
            "name"  => l('item_s'.$liID),
            "link" => $link,
            "bg" => $lsBg,
            "img" => $lsImg,
            "reqs" => $lsReqs
        );
}


foreach($_VERT as $liID => $laValue)
{
    //sicht bar?
    $lbVisible = true;
    
    $laReq = listReqs("V",$liID);
    $lsReqs = "";
    foreach($laReq as $Req)
    {
        foreach($Req as $t => $l)
        {
            $lsColor = "lime";
            $lsType = substr($t,0,1);
            $liNid = substr($t,1);
            $liHas = $lsType == "B" ? $k["k".$liNid] : $f["f".$liNid];
 
            if($liHas < $l)
                $lsColor = "red";
            
            $lsItemName = l('item_'.strtolower($t));
            $lsItemLevel = $l > 0 ? "($liHas/$l)" : "";
            $lsReqs .= "<font color='$lsColor'>{$lsItemName}{$lsItemLevel}</font><br>";
        }
        $lsReqs = substr($lsReqs,0,-strlen("<br>"));//ja hergott ich weiß, ich hätte hier auch statt strlen(<br>) auch 4 nehmen könne, aber so wirst du idiot das besser verstehen wenn du das siehst ;)
        
        $lsReqs .= "<br>".l('tech_or')."<br>";
    }
    
    $lsReqs = substr($lsReqs,0,-strlen("<br>".l('tech_or')."<br>"));//hier aller ding ist das zwingend notwendig, verschiedene sprachen gell ;)
    
    //pic
    $lsImg = "design/items/v{$liID}.gif";
    if($lbVisible)
        $lsBg = "url($lsImg)";
    else
        $lsBg = "transparent";
    
    $link = "info.php?obj=v$liID";
    $laTechnik['v'][$liID] = array(
            "name"  => l('item_v'.$liID),
            "link" => $link,
            "bg" => $lsBg,
            "img" => $lsImg,
            "reqs" => $lsReqs
        );
}

$laTplExport["laTechnik"] = $laTechnik;

buildPage("technik.tpl", $laTplExport);