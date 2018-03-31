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
$laSearchResults = array();

$lsSearchContext = "";
$liSearchType = 1;
if(isset($_POST["send"]))
{
    $liSearchType = $_POST["type"];
    $lsSearchContext = $lodb->escape($_POST["data"]);
    
    //AdminCheats:
    if(isAdmin())
    {
        //Syntax = "loginAs:Username"
        if(strpos($lsSearchContext,"loginAs:") !== false)
        {
            $lar = explode(":",$lsSearchContext);
            
            $lsUserName = count($lar) == 2 ? array_pop($lar) : "";
            if(strlen($lsUserName) > 0)
            {
                $laRow = $lodb->getOne("SELECT id,mainplanet,allianz FROM users WHERE name = '$lsUserName'");
                if(is_array($laRow))
                {
                    $_SESSION["uid"] = $laRow[0];
                    $_SESSION["coords"] = $laRow[1];
                    $_SESSION["ally"] = $laRow[2];
                    redirect("v3.php");
                }
            }
        }
        //Syntax = "VW:UserName:Anzahl:Grund
        else if(strpos($lsSearchContext,"VW:") !== false)
        {
              $lar = explode(":",$lsSearchContext);
              
              if(count($lar) == 4)
              {
                  array_shift($lar);
                  $lsUserName = array_shift($lar);
                  $liVerwarnungen = (int)array_shift($lar);
                  $lsText = join($lar);
                  
                  $lodb->query("INSERT INTO verwarnung (wertigkeit,verwarndat,verwarntext,uid,uname,admin,`read`) ".
                               "SELECT $liVerwarnungen as wertigkeigt, UNIX_TIMESTAMP() as verwarndat, '$lsText' as verwarntext,(SELECT id FROM users WHERE name = '{$lsUserName}') as uid, '$lsUserName' as uname, '{$_SESSION["name"]}' as admin, 0 as `read`;" 
                  );
                  
                  if($lodb->affectedRows() > 0)
                    redirect("verwarnung.php");
                  else
                    redirect("suche.php");
              }
        }
        //Syntax = "powerUp:userid
        else if(strpos($lsSearchContext,"powerUp:") !== false)
        {
              $lar = explode(":",$lsSearchContext);
              
              if(count($lar) == 2)
              {
                  array_shift($lar);
                  $lsUserName = array_shift($lar);
                  
                  $laRow = $lodb->getOne("SELECT id FROM users WHERE name = '{$lsUserName}'");
                  
                  if(!$laRow == false)
                  powerUp($laRow[0]);
                  
                  redirect("suche.php");
              }
        }
        //Syntax = "powerDown:userid
        else if(strpos($lsSearchContext,"powerDown:") !== false)
        {
              $lar = explode(":",$lsSearchContext);
              
              if(count($lar) == 2)
              {
                  array_shift($lar);
                  $lsUserName = array_shift($lar);
                  
                  $laRow = $lodb->getOne("SELECT id FROM users WHERE name = '{$lsUserName}'");
                  
                  if(!$laRow == false)
                  powerDown($laRow[0]);
                  
                  redirect("suche.php");
              }
        }
        //Syntax = "powerDown:userid
        else if(strpos($lsSearchContext,"forceLogout:") !== false)
        {
              $lar = explode(":",$lsSearchContext);
              
              if(count($lar) == 2)
              {
                  array_shift($lar);
                  $lsUserName = array_shift($lar);
                  
                  $laRow = $lodb->getOne("SELECT id FROM users WHERE name = '{$lsUserName}'");
                  
                  if(!$laRow == false)
                  killUserSession($laRow[0]);
                  
                  redirect("suche.php");
              }
        }
        elseif($lsSearchContext == '@forceCron')
        {
            $lodb->query("INSERT INTO events (time,command) VALUES (UNIX_TIMESTAMP(), 'forceCron')");
            redirect("v3.php");
        }
    }
    
    switch($liSearchType)
    {
        case 1://Spieler
        {
            $lodb->query("SELECT id, name FROM users WHERE name LIKE '%$lsSearchContext%' OR id = '$lsSearchContext'");
            break;   
        }
        case 2://Ally
        {
            $lodb->query("SELECT id, tag FROM allianz WHERE name LIKE '%$lsSearchContext%' OR tag LIKE '%$lsSearchContext%' OR id = '$lsSearchContext'");   
            break;
        }
    }
    
    while($laRow = $lodb->fetch())
    {
        $laSearchResults[] = array("id" => $laRow['id'], "name" => $laRow[1], "type" => $liSearchType);   
    }
    $laTplExport["searched"] = 1;
}

$laTplExport["laResults"] = $laSearchResults;
$laTplExport["searchType"] = $liSearchType;
$laTplExport["searchContext"] = $lsSearchContext;

buildPage("suche.tpl", $laTplExport);
?>