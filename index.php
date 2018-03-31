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
define("GIGRA_MUSTSESSION",false);
//Nur hier bei Login
define("LOCAL_LOGIN",false);
include 'core/core.php';


if(loggedIn())
    redirect("v3.php");

//if(!LOCAL_LOGIN && $_SERVER["REQUEST_METHOD"] != "POST" and ($_GET["secret"] != 'r3f4ct2011'))
    //redirect("http://www.gigra-game.de");

$fehler = "";
$laRunden = array();
foreach ($_CONFIG as $liRunde => $laData)
	$laRunden[$liRunde] = $laData["name"];
buildPage("index.tpl", array(
	"laRunden" => $laRunden
));r
?>
