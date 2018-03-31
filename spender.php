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

if(isset($_GET['submit']) && isAdmin())
    $lodb->query("UPDATE users SET donator = 2 WHERE id = '".$lodb->escape($_GET["submit"])."'");


if(isset($_GET["frompaypal"]) && $_GET["frompaypal"] == "1")
    $lodb->query("UPDATE users SET donator = 1 WHERE id = '".Uid()."'");

$laDonators = array();
$lodb->query("SELECT name FROM users WHERE donator = 2");
while($laRow = $lodb->fetch())
    $laDonators[] = $laRow[0];
    
$laTplExport['donators'] = $laDonators;

//wer tut so als wuerde er gespendet haben?
if(isAdmin())
{
    $laRequestors = array();
    $lodb->query("SELECT id,name,email FROM users WHERE donator = 1");   
    while($laRow = $lodb->fetch("assoc"))
    {
        $laRequestors[] = $laRow;
    }
    
    $laTplExport['requestors'] = $laRequestors;
}

buildPage("spender.tpl", $laTplExport);
?>